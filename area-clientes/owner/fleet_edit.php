<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Obtener ID del vehículo
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no proporcionado.";
    exit;
}

// Obtener datos actuales del vehículo
$stmt = $pdo->prepare("SELECT * FROM fleet WHERE fleetID = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch();
if (!$vehicle) {
    echo "Vehículo no encontrado.";
    exit;
}

// Obtener localidades para el select
$towns = $pdo->query("SELECT townID, townName FROM location ORDER BY townName")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $license_plate = $_POST['license_plate'];
    $capacity_kg = $_POST['capacity_kg'];
    $status = $_POST['status'];
    $lastMaintenance = $_POST['lastMaintenance'] ?: null;
    $townID = $_POST['townID'] ?: null;

    $stmt = $pdo->prepare("UPDATE fleet SET type = ?, license_plate = ?, capacity_kg = ?, status = ?, lastMaintenance = ?, townID = ? WHERE fleetID = ?");
    $stmt->execute([$type, $license_plate, $capacity_kg, $status, $lastMaintenance, $townID, $id]);

    header("Location: fleet.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vehículo - INCARGO365</title>
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
    <h2>✏️ Editar vehículo</h2>
    <form method="post">
        <label for="type">Tipo de vehículo</label>
        <select name="type" id="type" required>
            <option value="Truck" <?= $vehicle['type'] == 'Truck' ? 'selected' : '' ?>>Camión</option>
            <option value="Van" <?= $vehicle['type'] == 'Van' ? 'selected' : '' ?>>Furgoneta</option>
            <option value="Container" <?= $vehicle['type'] == 'Container' ? 'selected' : '' ?>>Contenedor</option>
        </select>

        <label for="license_plate">Matrícula</label>
        <input type="text" name="license_plate" id="license_plate" value="<?= htmlspecialchars($vehicle['license_plate']) ?>" required>

        <label for="capacity_kg">Capacidad en kg</label>
        <input type="number" step="0.01" name="capacity_kg" id="capacity_kg" value="<?= $vehicle['capacity_kg'] ?>">

        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="Available" <?= $vehicle['status'] == 'Available' ? 'selected' : '' ?>>Disponible</option>
            <option value="On Route" <?= $vehicle['status'] == 'On Route' ? 'selected' : '' ?>>En Ruta</option>
            <option value="Maintenance" <?= $vehicle['status'] == 'Maintenance' ? 'selected' : '' ?>>Mantenimiento</option>
        </select>

        <label for="lastMaintenance">Último mantenimiento</label>
        <input type="date" name="lastMaintenance" id="lastMaintenance" value="<?= $vehicle['lastMaintenance'] ?>">

        <label for="townID">Ubicación</label>
        <select name="townID" id="townID">
            <option value="">-- Seleccionar --</option>
            <?php foreach ($towns as $town): ?>
                <option value="<?= $town['townID'] ?>" <?= $vehicle['townID'] == $town['townID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($town['townName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Actualizar vehículo</button>
    </form>
    <a href="fleet.php" class="back-link">← Volver al listado de flota</a>
</div>

</body>
</html>
