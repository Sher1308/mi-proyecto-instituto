<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Obtener localidades disponibles
$towns = $pdo->query("SELECT townID, townName FROM location ORDER BY townName ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $townID = $_POST['townID'];

    $stmt = $pdo->prepare("INSERT INTO warehouse (name, townID) VALUES (?, ?)");
    $stmt->execute([$name, $townID]);

    header("Location: warehouses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Almacén - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            padding: 40px;
            margin: 0;
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
    <h2>🏬 Añadir nuevo almacén</h2>
    <form method="post">
        <label for="name">Nombre del almacén</label>
        <input type="text" name="name" id="name" required>

        <label for="townID">Ciudad</label>
        <select name="townID" id="townID" required>
            <?php foreach ($towns as $town): ?>
                <option value="<?= $town['townID'] ?>"><?= htmlspecialchars($town['townName']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Guardar almacén</button>
    </form>
    <a href="warehouses.php" class="back-link">← Volver al listado de almacenes</a>
</div>
</body>
</html>
