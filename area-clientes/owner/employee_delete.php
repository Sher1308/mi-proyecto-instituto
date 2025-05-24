<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Obtener ID del empleado
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de empleado no proporcionado.";
    exit;
}

// Obtener datos del empleado
$stmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();

if (!$emp) {
    echo "Empleado no encontrado.";
    exit;
}

// Si se confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM employee WHERE employee_id = ?");
    $stmt->execute([$id]);

    header("Location: employees.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Empleado - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fefefe;
            margin: 0;
            padding: 40px;
        }

        .confirm-box {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #b00020;
        }

        p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 20px;
            margin: 0 10px;
            font-size: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #b00020;
            color: white;
        }

        .btn-cancel {
            background-color: #ccc;
            color: black;
        }

        .btn-danger:hover {
            background-color: #880014;
        }

        .btn-cancel:hover {
            background-color: #aaa;
        }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>⚠️ Confirmar eliminación</h2>
    <p>¿Estás seguro de que deseas eliminar al empleado <strong><?= htmlspecialchars($emp['name'] . ' ' . $emp['surname']) ?></strong>?</p>

    <form method="post" style="display:inline;">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    </form>
    <a href="employees.php" class="btn btn-cancel">Cancelar</a>
</div>

</body>
</html>
