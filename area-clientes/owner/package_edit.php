<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Obtener ID de paquete a editar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de paquete inválido.");
}
$packageID = $_GET['id'];

// Obtener datos actuales del paquete
$stmt = $pdo->prepare("SELECT * FROM package WHERE packageID = ?");
$stmt->execute([$packageID]);
$package = $stmt->fetch();
if (!$package) {
    die("Paquete no encontrado.");
}

// Obtener listas
$clients = $pdo->query("SELECT client_id, name, surname FROM client WHERE status = 'Active'")->fetchAll();
$warehouses = $pdo->query("SELECT warehouseID, name FROM warehouse")->fetchAll();
$fleet = $pdo->query("SELECT fleetID, license_plate FROM fleet WHERE status IN ('Available', 'On Route')")->fetchAll();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = $_POST['client_id'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $warehouseID = $_POST['warehouseID'] ?: null;
    $fleetID = $_POST['fleetID'] ?: null;
    $delivery_date = $_POST['delivery_date'] ?: null;
    $confirmation = isset($_POST['delivery_confirmation']) ? 1 : 0;

    $update = $pdo->prepare("UPDATE package SET client_id=?, weight=?, status=?, warehouseID=?, fleetID=?, delivery_date=?, delivery_confirmation=? WHERE packageID=?");
    $update->execute([$clientID, $weight, $status, $warehouseID, $fleetID, $delivery_date, $confirmation, $packageID]);

    header("Location: packages.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paquete - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .form-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0d3b66;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #0d3b66;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #095c9d;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #0d3b66;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>✏️ Editar Paquete</h2>
    <form method="post">
        <label for="client_id">Cliente</label>
        <select name="client_id" id="client_id" required>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['client_id'] ?>" <?= $c['client_id'] == $package['clientID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name'] . ' ' . $c['surname']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="weight">Peso (kg)</label>
        <input type="number" step="0.01" name="weight" id="weight" value="<?= $package['weight'] ?>" required>

        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="Pending" <?= $package['status'] == 'Pending' ? 'selected' : '' ?>>Pendiente</option>
            <option value="In Transit" <?= $package['status'] == 'In Transit' ? 'selected' : '' ?>>En tránsito</option>
            <option value="Delivered" <?= $package['status'] == 'Delivered' ? 'selected' : '' ?>>Entregado</option>
        </select>

        <label for="warehouseID">Almacén</label>
        <select name="warehouseID" id="warehouseID">
            <option value="">-- Ninguno --</option>
            <?php foreach ($warehouses as $w): ?>
                <option value="<?= $w['warehouseID'] ?>" <?= $w['warehouseID'] == $package['warehouseID'] ? 'selected' : '' ?>><?= htmlspecialchars($w['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="fleetID">Vehículo</label>
        <select name="fleetID" id="fleetID">
            <option value="">-- Ninguno --</option>
            <?php foreach ($fleet as $f): ?>
                <option value="<?= $f['fleetID'] ?>" <?= $f['fleetID'] == $package['fleetID'] ? 'selected' : '' ?>><?= htmlspecialchars($f['license_plate']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="delivery_date">Fecha de entrega</label>
        <input type="datetime-local" name="delivery_date" id="delivery_date" value="<?= $package['delivery_date'] ? date('Y-m-d\TH:i', strtotime($package['delivery_date'])) : '' ?>">

        <label><input type="checkbox" name="delivery_confirmation" <?= $package['delivery_confirmation'] ? 'checked' : '' ?>> Confirmado</label>

        <button type="submit">Actualizar Paquete</button>
    </form>
    <a href="packages.php" class="back-link">← Volver a paquetes</a>
</div>
</body>
</html>
