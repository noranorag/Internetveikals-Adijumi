<?php
require('libs/fpdf/tfpdf.php');
include 'database/db_connection.php';

// Retrieve the order ID from the URL
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    die('Order ID is missing.');
}

// Fetch order details from the database
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_ID = ?");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch the shipping price from the order
$shippingPrice = $order['shipping_price'] ?? 0.00; // Default to 0.00 if not found

// Fetch products from the order_items table
$productStmt = $conn->prepare("
    SELECT 
        p.name AS product_name, 
        oi.quantity, 
        oi.price 
    FROM order_items oi
    INNER JOIN product p ON oi.ID_product = p.product_ID
    WHERE oi.ID_order = ?
");
$productStmt->bind_param('i', $orderId);
$productStmt->execute();
$products = $productStmt->get_result();

// Generate the PDF
$pdf = new tFPDF();
$pdf->SetTitle('Rēķins', true); // Set the title of the PDF
$pdf->AddPage();

// Add Times New Roman font (uses UTF-8)
$pdf->AddFont('Times', '', 'times.ttf', true); // Regular font
$pdf->AddFont('Times', 'B', 'timesbd.ttf', true); // Bold font

// Title
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'Rēķins', 0, 1, 'C');

// Invoice Number and Date
$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 10, 'Datums: ' . date('d/m/Y'), 0, 0, 'L'); // Latvian date format
$pdf->Cell(95, 10, 'Nr. GGG-' . $orderId . '/' . date('Y'), 0, 1, 'R');

// Add spacing
$pdf->Ln(5);

// Service Provider Section
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(95, 10, 'Pakalpojumu sniedzējs', 0, 0, 'L');
$pdf->Cell(95, 10, 'Pakalpojumu saņēmējs', 0, 1, 'L');

$pdf->SetFont('Times', '', 11);
$pdf->Cell(95, 5, 'Nosaukums: G.G.G., IU', 0, 0, 'L');
$pdf->Cell(95, 5, 'Nosaukums: ' . $order['name'] . ' ' . $order['surname'], 0, 1, 'L');
$pdf->Cell(95, 10, 'Reģ. Nr.: 41202014505', 0, 0, 'L');
$pdf->Cell(95, 10, 'Reģ. Nr.: -', 0, 1, 'L');
$pdf->Cell(95, 5, 'Juridiskā adrese: "Pinnes", Alsungas pag., Kuldīgas', 0, 0, 'L');
$pdf->Cell(95, 5, 'Juridiskā adrese: -', 0, 1, 'L');
$pdf->Cell(95, 5, 'nov. LV-3306', 0, 0, 'L');
$pdf->Cell(95, 5, '', 0, 1, 'L'); 

$pdf->Cell(115, 10, 'Konts: LV82UNLA005002007793', 0, 1, 'L');

$pdf->Cell(95, 10, 'Norēķinu veids: Ar pārskaitījumu', 0, 0, 'L');
$pdf->Cell(95, 10, 'Samaksas termiņš: Priekšapmaksa', 0, 1, 'L');

// Add spacing
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(10, 10, 'Nr.', 1, 0, 'C');
$pdf->Cell(90, 10, 'Pakalpojuma nosaukums; pakalpojuma sniegšanas datums', 1, 0, 'C');
$pdf->Cell(20, 10, 'Mērvienība', 1, 0, 'C');
$pdf->Cell(20, 10, 'Daudzums', 1, 0, 'C');

// Use MultiCell for two-line headers with manual positioning
$x = $pdf->GetX(); // Save the current X position
$y = $pdf->GetY(); // Save the current Y position

$pdf->MultiCell(25, 5, "Cena EUR\nbez PVN", 1, 'C'); // First two-line header
$x2 = $pdf->GetX(); // Save the new X position after MultiCell
$pdf->SetXY($x + 25, $y); // Reset position for the next header

$pdf->MultiCell(25, 5, "Summa EUR\nbez PVN", 1, 'C'); // Second two-line header
$pdf->SetXY($x2 + 25, $y); // Reset position for the next cell

// Move the cursor to the next line for the table rows
$pdf->Ln(10);

// Table Rows (Products)
$pdf->SetFont('Times', '', 11);
$counter = 1;
$total = 0;
while ($product = $products->fetch_assoc()) {
    $price = $product['price'];
    $quantity = $product['quantity'];
    $sum = $price * $quantity;
    $total += $sum;

    $pdf->Cell(10, 10, $counter++, 1, 0, 'C');
    $pdf->Cell(90, 10, $product['product_name'], 1, 0, 'L');
    $pdf->Cell(20, 10, 'gab.', 1, 0, 'C');
    $pdf->Cell(20, 10, $quantity, 1, 0, 'C');
    $pdf->Cell(25, 10, number_format($price, 2), 1, 0, 'R');
    $pdf->Cell(25, 10, number_format($sum, 2), 1, 1, 'R');
}

// Add shipping price to the invoice
$total += $shippingPrice;

$pdf->Cell(10, 10, $counter++, 1, 0, 'C');
$pdf->Cell(90, 10, 'Piegāde', 1, 0, 'L');
$pdf->Cell(20, 10, '-', 1, 0, 'C');
$pdf->Cell(20, 10, '-', 1, 0, 'C');
$pdf->Cell(25, 10, number_format($shippingPrice, 2), 1, 0, 'R'); // Use shipping_price from the orders table
$pdf->Cell(25, 10, number_format($shippingPrice, 2), 1, 1, 'R'); // Use shipping_price from the orders table


// Summary Section
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); // Empty space for alignment
$pdf->Cell(25, 10, 'Kopā:', 1, 0, 'L'); // Text under "Cena EUR bez PVN" aligned left
$pdf->Cell(25, 10, number_format($total, 2), 1, 1, 'R'); // Number under "Summa EUR bez PVN"

// Atlaide (Discount)
$pdf->SetFont('Times', '', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); // Empty space for alignment
$pdf->Cell(25, 10, 'Atlaide:', 1, 0, 'L'); // Text under "Cena EUR bez PVN" aligned left
$pdf->Cell(25, 10, '-', 1, 1, 'R'); // Number under "Summa EUR bez PVN"

// Summa ar atlaidi (Total with Discount)
$pdf->Cell(140, 10, '', 0, 0, 'C'); // Empty space for alignment
$pdf->MultiCell(25, 5, "Summa\nar atlaidi:", 1, 'L'); // Two-line text under "Cena EUR bez PVN"
$pdf->SetXY($pdf->GetX() + 165, $pdf->GetY() - 10); // Adjust position for the next column
$pdf->Cell(25, 10, '-', 1, 1, 'R'); // Number under "Summa EUR bez PVN"

// PVN likme (VAT Rate)
$pdf->Cell(140, 10, '', 0, 0, 'C'); // Empty space for alignment
$pdf->Cell(25, 10, 'PVN likme 21%:', 1, 0, 'L'); // Text under "Cena EUR bez PVN" aligned left
$pdf->Cell(25, 10, '-', 1, 1, 'R'); // Number under "Summa EUR bez PVN"

// Summa apmaksai (Total Payable)
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); // Empty space for alignment
$pdf->MultiCell(25, 5, "Summa\napmaksai:", 1, 'L'); // Two-line text under "Cena EUR bez PVN"
$pdf->SetXY($pdf->GetX() + 165, $pdf->GetY() - 10); // Adjust position for the next column
$pdf->Cell(25, 10, number_format($total, 2), 1, 1, 'R'); // Use $total for the number under "Summa EUR bez PVN"

$pdf->Ln(5); // Add 5 units of vertical spacing

// Add the payment deadline note
$pdf->SetFont('Times', '', 11);
$pdf->Cell(0, 10, 'Rēķins jāsamaksā 12 stundu laikā, vai arī tas var tikt anulēts.', 0, 1, 'L'); // Align text to the left

// Output the PDF
$pdf->Output('I', 'Rekins.pdf');
?>