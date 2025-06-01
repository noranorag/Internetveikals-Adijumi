<?php
require('libs/fpdf/fpdf.php');
include 'database/db_connection.php';

// Retrieve the order ID from the URL
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    die('Order ID is missing.');
}

// Fetch the order details from the database
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}
// Generate the PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Rēķins', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Datums: ' . date('Y-m-d'), 0, 1, 'R');

// Add order details to the PDF
$pdf->Cell(0, 10, 'Pasūtījuma ID: ' . $order['id'], 0, 1);
$pdf->Cell(0, 10, 'Vārds: ' . $order['name'], 0, 1);
$pdf->Cell(0, 10, 'Uzvārds: ' . $order['surname'], 0, 1);
$pdf->Cell(0, 10, 'E-pasts: ' . $order['email'], 0, 1);
$pdf->Cell(0, 10, 'Tālrunis: ' . $order['phone'], 0, 1);
$pdf->Cell(0, 10, 'Piegādes metode: ' . $order['delivery'], 0, 1);
$pdf->Cell(0, 10, 'Pakomāta adrese: ' . $order['pickup_address'], 0, 1);
$pdf->Cell(0, 10, 'Kopējā summa: €' . number_format($order['total_amount'], 2), 0, 1);

// Output the PDF
$pdf->Output('I', 'Rekins.pdf');
?>