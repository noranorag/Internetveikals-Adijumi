<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('libs/fpdf/tfpdf.php');
include 'database/db_connection.php';


chdir(__DIR__); // Set the working directory to the current script's directory


$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    die('Order ID is missing.');
}


$stmt = $conn->prepare("SELECT * FROM orders WHERE order_ID = ?");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}


$shippingPrice = $order['shipping_price'] ?? 0.00; 


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


$pdf = new tFPDF();
$pdf->SetTitle('Rēķins', true); 
$pdf->AddPage();


$pdf->AddFont('Times', '', 'times', true);
$pdf->AddFont('Times', 'I', 'timesi', true);
$pdf->AddFont('Times', 'B', 'timesbd', true);
$pdf->AddFont('Times', 'BI', 'timesbi', true);


$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'Rēķins', 0, 1, 'C');


$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 10, 'Datums: ' . date('d/m/Y'), 0, 0, 'L'); 
$pdf->Cell(95, 10, 'Nr. GGG-' . $orderId . '/' . date('Y'), 0, 1, 'R');


$pdf->Ln(5);


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


$pdf->Ln(5);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(10, 10, 'Nr.', 1, 0, 'C');
$pdf->Cell(90, 10, 'Pakalpojuma nosaukums; pakalpojuma sniegšanas datums', 1, 0, 'C');
$pdf->Cell(20, 10, 'Mērvienība', 1, 0, 'C');
$pdf->Cell(20, 10, 'Daudzums', 1, 0, 'C');


$x = $pdf->GetX(); 
$y = $pdf->GetY(); 

$pdf->MultiCell(25, 5, "Cena EUR\nbez PVN", 1, 'C'); 
$x2 = $pdf->GetX(); 
$pdf->SetXY($x + 25, $y); 

$pdf->MultiCell(25, 5, "Summa EUR\nbez PVN", 1, 'C'); 
$pdf->SetXY($x2 + 25, $y); 


$pdf->Ln(10);


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


$total += $shippingPrice;

$pdf->Cell(10, 10, $counter++, 1, 0, 'C');
$pdf->Cell(90, 10, 'Piegāde', 1, 0, 'L');
$pdf->Cell(20, 10, '-', 1, 0, 'C');
$pdf->Cell(20, 10, '-', 1, 0, 'C');
$pdf->Cell(25, 10, number_format($shippingPrice, 2), 1, 0, 'R'); 
$pdf->Cell(25, 10, number_format($shippingPrice, 2), 1, 1, 'R'); 



$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); 
$pdf->Cell(25, 10, 'Kopā:', 1, 0, 'L'); 
$pdf->Cell(25, 10, number_format($total, 2), 1, 1, 'R'); 


$pdf->SetFont('Times', '', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); 
$pdf->Cell(25, 10, 'Atlaide:', 1, 0, 'L'); 
$pdf->Cell(25, 10, '-', 1, 1, 'R'); 


$pdf->Cell(140, 10, '', 0, 0, 'C'); 
$pdf->MultiCell(25, 5, "Summa\nar atlaidi:", 1, 'L'); 
$pdf->SetXY($pdf->GetX() + 165, $pdf->GetY() - 10); 
$pdf->Cell(25, 10, '-', 1, 1, 'R'); 


$pdf->Cell(140, 10, '', 0, 0, 'C'); 
$pdf->Cell(25, 10, 'PVN likme 21%:', 1, 0, 'L'); 
$pdf->Cell(25, 10, '-', 1, 1, 'R'); 


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(140, 10, '', 0, 0, 'C'); 
$pdf->MultiCell(25, 5, "Summa\napmaksai:", 1, 'L'); 
$pdf->SetXY($pdf->GetX() + 165, $pdf->GetY() - 10); 
$pdf->Cell(25, 10, number_format($total, 2), 1, 1, 'R'); 

$pdf->Ln(5); 


$pdf->SetFont('Times', '', 11);
$pdf->Cell(0, 10, 'Rēķins jāsamaksā 12 stundu laikā, vai arī tas var tikt anulēts.', 0, 1, 'L'); 


$invoiceDir = 'invoices/';
if (!is_dir($invoiceDir)) {
    mkdir($invoiceDir, 0777, true); 
}

$invoiceFile = $invoiceDir . 'invoice_' . $orderId . '.pdf';
$pdf->Output('F', $invoiceFile); 


$updateInvoicePathStmt = $conn->prepare("UPDATE orders SET invoice_path = ? WHERE order_ID = ?");
$updateInvoicePathStmt->bind_param('si', $invoiceFile, $orderId);
$updateInvoicePathStmt->execute();
$updateInvoicePathStmt->close();


$pdf->Output('I', 'Rekins.pdf');
?>