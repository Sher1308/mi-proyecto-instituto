<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Validar que se pasó un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de vehículo no proporcionado.");
}

$fleetID = $_GET['id'];

// Eliminar el vehículo
$stmt = $pdo->prepare("DELETE FROM fleet WHERE fleetID = ?");
if ($stmt->execute([$fleetID])) {
    header("Location: fleet.php");
    exit;
} else {
    echo "Error al eliminar el vehículo.";
}
?>
