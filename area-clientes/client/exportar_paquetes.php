<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 9) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

$client_id = $_SESSION['user_id'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mis_paquetes.csv');

$output = fopen('php://output', 'w');

// Encabezados del archivo CSV
fputcsv($output, ['ID', 'Peso (kg)', 'Estado', 'Fecha de entrega', 'Confirmación', 'Vehículo', 'Almacén']);

$stmt = $pdo->prepare("
    SELECT p.packageID, p.weight, p.status, p.delivery_date, p.delivery_confirmation, 
           f.license_plate AS vehicle, w.name AS warehouse_name
    FROM package p
    LEFT JOIN fleet f ON p.fleetID = f.fleetID
    LEFT JOIN warehouse w ON p.warehouseID = w.warehouseID
    WHERE p.client_id = ?
    ORDER BY p.delivery_date DESC
");
$stmt->execute([$client_id]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['packageID'],
        $row['weight'],
        $row['status'],
        $row['delivery_date'],
        $row['delivery_confirmation'] ? 'Sí' : 'No',
        $row['vehicle'] ?? '-',
        $row['warehouse_name'] ?? '-'
    ]);
}

fclose($output);
exit;
