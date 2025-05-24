<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID de cliente no especificado.";
    exit;
}

// Obtener datos del cliente para mostrar nombre
$stmt = $pdo->prepare("SELECT name, surname FROM client WHERE client_id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    echo "Cliente no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirmar eliminación
    $stmt = $pdo->prepare("DELETE FROM client WHERE client_id = ?");
    $stmt->execute([$id]);
    header("Location: clients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cliente - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        .confirm-box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #b30000;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #333;
        }

        form {
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px;
            font-size: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #b30000;
            color: white;
        }

        .btn-secondary {
            background-color: #ccc;
            color: black;
        }

        .btn-danger:hover {
            background-color: #900;
        }

        .btn-secondary:hover {
            background-color: #999;
        }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>⚠️ Confirmar eliminación</h2>
    <p>¿Estás seguro de que deseas eliminar al cliente <strong><?= htmlspecialchars($client['name']) . ' ' . htmlspecialchars($client['surname']) ?></strong>?</p>

    <form method="post">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a href="clients.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
