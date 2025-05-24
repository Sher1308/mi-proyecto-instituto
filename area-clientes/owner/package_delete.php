<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de paquete inválido.";
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM package WHERE packageID = ?");
if ($stmt->execute([$id])) {
    header("Location: packages.php");
    exit;
} else {
    echo "Error al eliminar el paquete.";
}
?>
