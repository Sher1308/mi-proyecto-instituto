<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no proporcionado.";
    exit;
}

// Obtener datos del almacén actual
$stmt = $pdo->prepare("SELECT * FROM warehouse WHERE warehouseID = ?");
$stmt->execute([$id]);
$warehouse = $stmt->fetch();
if (!$warehouse) {
    echo "Almacén no encontrado.";
    exit;
}

// Obtener localizaciones
$locations = $pdo->query("SELECT townID, townName, country FROM location")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $townID = $_POST['townID'] ?: null;

    $stmt = $pdo->prepare("UPDATE warehouse SET name = ?, townID = ? WHERE warehouseID = ?");
    $stmt->execute([$name, $townID, $id]);

    header("Location: warehouses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Almacén - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 40px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0d3b66;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0d3b66;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #095c9d;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0d3b66;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>✏️ Editar Almacén</h2>
    <form method="post">
        <label for="name">Nombre del almacén</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($warehouse['name']) ?>" required>

        <label for="townID">Ciudad</label>
        <select name="townID" id="townID">
            <option value="">-- Sin asignar --</option>
            <?php foreach ($locations as $loc): ?>
                <option value="<?= $loc['townID'] ?>" <?= $loc['townID'] == $warehouse['townID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($loc['townName'] . ' - ' . $loc['country']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Actualizar almacén</button>
    </form>
    <a href="warehouses.php" class="back-link">← Volver a almacenes</a>
</div>
</body>
</html>
