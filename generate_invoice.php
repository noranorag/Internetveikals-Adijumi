<?php
require_once __DIR__ . '/dompdf/autoload.inc.php'; // Include dompdf

use Dompdf\Dompdf;

// Database connection
include 'database/db_connection.php';

// Get the order ID from the URL
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    die('Order ID is missing.');
}

// Fetch the order details from the database
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_ID = ?");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch the shipping price
$shippingPrice = $order['shipping_price'] ?? 0.00;

// Fetch the products associated with the order
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

// Start building the HTML for the invoice
$html = '<style>
    body {
        font-family: DejaVu Sans, sans-serif; /* Use DejaVu Sans for UTF-8 support */
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 5px;
        text-align: left;
    }
    .details {
        margin-top: 20px;
    }
    .details div {
        margin-bottom: 5px;
    }
</style>';

$html .= '<div style="text-align: center; margin-bottom: 20px;">
    <h2 style="font-size: 24px; margin: 0;">Rēķins</h2>
</div>';


$html .= '<div style="width: 100%; margin-bottom: 20px;">
    <span style="float: left;">Datums: ' . date('d/m/Y') . '</span>
    <span style="float: right;">Nr. GGG-' . $orderId . '/' . date('Y') . '</span>
</div>';

// Add provider and recipient details side by side, aligned on the same line
$html .= '<div style="display: flex; justify-content: space-between; margin-top: 20px; width: 100%;">
    <div style="width: 48%; text-align: left; word-wrap: break-word;">
        <div style="font-weight: bold; white-space: nowrap;">Pakalpojumu sniedzējs</div>
        <div style="margin-bottom: 10px;"></div> <!-- Added extra space -->
        <div>Nosaukums: G.G.G., IU</div>
        <div>Reģ. Nr.: 41202014505</div>
        <div>Juridiskā adrese: "Pinnes", Alsungas pag., Kuldīgas nov. LV-3306</div>
        <div>Konts: LV82UNLA005002007793</div>
        <div>Norēķinu veids: Ar pārskaitījumu</div>
    </div>
    <div style="width: 48%; text-align: left; word-wrap: break-word;">
        <div style="font-weight: bold; white-space: nowrap;">Pakalpojumu saņēmējs</div>
        <div>Nosaukums: ' . $order['name'] . ' ' . $order['surname'] . '</div>
        <div>Reģ. Nr.: -</div>
        <div>Juridiskā adrese: -</div>
        <div>Samaksas termiņš: Priekšapmaksa</div>
    </div>
</div>';

// Add a table for the products
$html .= '<table style="margin-top: 20px;">
    <thead>
        <tr>
            <th>Nr.</th>
            <th>Pakalpojuma nosaukums; pakalpojuma sniegšanas datums</th>
            <th>Mērvienība</th>
            <th>Daudzums</th>
            <th>Cena EUR bez PVN</th>
            <th>Summa EUR bez PVN</th>
        </tr>
    </thead>
    <tbody>';

$counter = 1;
$total = 0;
while ($product = $products->fetch_assoc()) {
    $price = $product['price'];
    $quantity = $product['quantity'];
    $sum = $price * $quantity;
    $total += $sum;

    $html .= '<tr>
        <td style="text-align: center;">' . $counter++ . '</td>
        <td>' . $product['product_name'] . '</td>
        <td style="text-align: center;">gab.</td>
        <td style="text-align: center;">' . $quantity . '</td>
        <td style="text-align: right;">' . number_format($price, 2) . '</td>
        <td style="text-align: right;">' . number_format($sum, 2) . '</td>
    </tr>';
}

// Add the shipping price to the total
$total += $shippingPrice;

$html .= '<tr>
    <td style="text-align: center;">' . $counter++ . '</td>
    <td>Piegāde</td>
    <td style="text-align: center;">-</td>
    <td style="text-align: center;">-</td>
    <td style="text-align: right;">' . number_format($shippingPrice, 2) . '</td>
    <td style="text-align: right;">' . number_format($shippingPrice, 2) . '</td>
</tr>';

$html .= '</tbody></table>';

// Add totals
$html .= '<table style="margin-top: 20px;">
    <tr>
        <td style="width: 70%;"></td>
        <td style="width: 15%; font-weight: bold;">Kopā:</td>
        <td style="width: 15%; text-align: right;">' . number_format($total, 2) . ' EUR</td>
    </tr>
</table>';

// Add a note
$html .= '<p style="margin-top: 20px;">Rēķins jāsamaksā 12 stundu laikā, vai arī tas var tikt anulēts.</p>';

// Initialize dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the PDF to the browser
$dompdf->stream('Rekins.pdf', ['Attachment' => false]);
?>