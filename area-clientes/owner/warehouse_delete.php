<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de almacén no válido.";
    exit;
}

$warehouseID = $_GET['id'];

// Si se confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM warehouse WHERE warehouseID = ?");
    $stmt->execute([$warehouseID]);
    header("Location: warehouses.php");
    exit;
}

// Obtener datos del almacén
$stmt = $pdo->prepare("SELECT name FROM warehouse WHERE warehouseID = ?");
$stmt->execute([$warehouseID]);
$warehouse = $stmt->fetch();

if (!$warehouse) {
    echo "Almacén no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Almacén - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            padding: 60px;
        }
        .confirm-box {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #b00020;
        }
        p {
            margin: 20px 0;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        button, .cancel {
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }
        button {
            background: #b00020;
            color: white;
        }
        .cancel {
            background: #ccc;
            color: black;
            text-decoration: none;
            display: inline-block;
        }
        button:hover {
            background: #800015;
        }
        .cancel:hover {
            background: #aaa;
        }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>¿Eliminar almacén?</h2>
    <p>Estás a punto de eliminar el almacén <strong><?= htmlspecialchars($warehouse['name']) ?></strong>.</p>
    <form method="post">
        <div class="buttons">
            <button type="submit">Eliminar</button>
            <a href="warehouses.php" class="cancel">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
