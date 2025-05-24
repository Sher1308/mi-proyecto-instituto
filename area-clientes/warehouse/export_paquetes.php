<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 7) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

$estado = $_GET['estado'] ?? '';
$allowed = ['Almacenado', 'In Transit', 'Delivered'];

// Obtener warehouseID del empleado
$stmt = $pdo->prepare("SELECT warehouseID FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$warehouseID = $stmt->fetchColumn();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=paquetes_export.csv');
$output = fopen('php://output', 'w');

// Encabezados del CSV
fputcsv($output, ['ID', 'Cliente', 'Peso', 'Estado', 'Fecha de entrega']);

if ($estado && in_array($estado, $allowed)) {
    $query = "SELECT * FROM package WHERE warehouseID = ? AND status = ? ORDER BY delivery_date DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$warehouseID, $estado]);
} else {
    $query = "SELECT * FROM package WHERE warehouseID = ? ORDER BY delivery_date DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$warehouseID]);
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['packageID'], $row['client_id'], $row['weight'], $row['status'], $row['delivery_date']]);
}

fclose($output);
exit;
