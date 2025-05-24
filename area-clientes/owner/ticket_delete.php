<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) { echo "ID no válido"; exit; }

$stmt = $pdo->prepare("DELETE FROM ticket WHERE ticketID = ?");
$stmt->execute([$id]);

header("Location: tickets.php");
exit;
?>
