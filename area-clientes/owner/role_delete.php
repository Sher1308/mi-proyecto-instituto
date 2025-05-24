<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

if (!isset($_GET['id'])) {
    die("ID de rol no especificado.");
}

$rolID = (int) $_GET['id'];

// Obtener el nombre del rol
$stmt = $pdo->prepare("SELECT role_name FROM rol WHERE rolID = ?");
$stmt->execute([$rolID]);
$rol = $stmt->fetch();

if (!$rol) {
    die("Rol no encontrado.");
}

// No permitir eliminar el rol "owner"
if (strtolower($rol['role_name']) === 'owner') {
    die("⚠️ No se puede eliminar el rol 'owner'.");
}

// Si se confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM rol WHERE rolID = ?");
    $stmt->execute([$rolID]);
    header("Location: roles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Rol - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff3f3;
            margin: 0;
            padding: 40px;
        }

        .delete-box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            border: 2px solid #ff4d4d;
            box-shadow: 0 0 10px rgba(255, 77, 77, 0.3);
        }

        h2 {
            color: #b30000;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            text-align: center;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .actions button,
        .actions a {
            width: 48%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
        }

        .btn-cancel {
            background: #ccc;
            color: #333;
            text-decoration: none;
        }

        .btn-delete {
            background: #e60000;
            color: white;
        }

        .btn-cancel:hover {
            background: #bbb;
        }

        .btn-delete:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>

<div class="delete-box">
    <h2>⚠️ Eliminar Rol</h2>
    <p>¿Estás seguro de que deseas eliminar el rol <strong><?= htmlspecialchars($rol['role_name']) ?></strong>?</p>

    <form method="post" class="actions">
        <a href="roles.php" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-delete">Sí, eliminar</button>
    </form>
</div>

</body>
</html>
