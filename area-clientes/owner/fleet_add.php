<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Obtener localidades para el select
$towns = $pdo->query("SELECT townID, townName FROM location ORDER BY townName ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $license_plate = $_POST['license_plate'];
    $capacity_kg = $_POST['capacity_kg'];
    $status = $_POST['status'];
    $lastMaintenance = $_POST['lastMaintenance'] ?: null;
    $townID = $_POST['townID'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO fleet (type, license_plate, capacity_kg, status, lastMaintenance, townID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$type, $license_plate, $capacity_kg, $status, $lastMaintenance, $townID]);

    header("Location: fleet.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Vehículo - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
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
    <h2>➕ Añadir Vehículo a la Flota</h2>
    <form method="post">
        <label for="type">Tipo de vehículo</label>
        <select name="type" id="type" required>
            <option value="Truck">Camión</option>
            <option value="Van">Furgoneta</option>
            <option value="Container">Contenedor</option>
        </select>

        <label for="license_plate">Matrícula</label>
        <input type="text" name="license_plate" id="license_plate" required>

        <label for="capacity_kg">Capacidad (kg)</label>
        <input type="number" name="capacity_kg" id="capacity_kg" step="0.01" required>

        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="Available">Disponible</option>
            <option value="On Route">En Ruta</option>
            <option value="Maintenance">Mantenimiento</option>
        </select>

        <label for="lastMaintenance">Último mantenimiento</label>
        <input type="date" name="lastMaintenance" id="lastMaintenance">

        <label for="townID">Ubicación</label>
        <select name="townID" id="townID">
            <option value="">-- Selecciona una ciudad --</option>
            <?php foreach ($towns as $town): ?>
                <option value="<?= $town['townID'] ?>"><?= htmlspecialchars($town['townName']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Guardar Vehículo</button>
    </form>
    <a href="fleet.php" class="back-link">← Volver a la lista de flota</a>
</div>
</body>
</html>
