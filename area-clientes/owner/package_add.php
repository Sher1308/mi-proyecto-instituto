<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Obtener clientes, almacenes y vehículos
$clients = $pdo->query("SELECT client_id, name, surname FROM client ORDER BY name")->fetchAll();
$warehouses = $pdo->query("SELECT warehouseID, name FROM warehouse ORDER BY name")->fetchAll();
$vehicles = $pdo->query("SELECT fleetID, license_plate FROM fleet WHERE status != 'Maintenance' ORDER BY license_plate")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = $_POST['clientID'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $warehouseID = $_POST['warehouseID'];
    $fleetID = $_POST['fleetID'] ?: null;
    $delivery_date = $_POST['delivery_date'];
    $delivery_confirmation = isset($_POST['delivery_confirmation']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO package (clientID, weight, status, warehouseID, fleetID, delivery_date, delivery_confirmation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$clientID, $weight, $status, $warehouseID, $fleetID, $delivery_date, $delivery_confirmation]);

    header("Location: packages.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Paquete - INCARGO365</title>
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
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
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
    <h2>📦 Nuevo Paquete</h2>
    <form method="post">
        <label>Cliente</label>
        <select name="clientID" required>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['client_id'] ?>"><?= htmlspecialchars($c['name'] . ' ' . $c['surname']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Peso (kg)</label>
        <input type="number" name="weight" step="0.01" min="0.1" required>

        <label>Estado</label>
        <select name="status" required>
            <option value="Pending">Pendiente</option>
            <option value="In Transit">En tránsito</option>
            <option value="Delivered">Entregado</option>
        </select>

        <label>Almacén</label>
        <select name="warehouseID" required>
            <?php foreach ($warehouses as $w): ?>
                <option value="<?= $w['warehouseID'] ?>"><?= htmlspecialchars($w['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Vehículo (opcional)</label>
        <select name="fleetID">
            <option value="">-- Sin asignar --</option>
            <?php foreach ($vehicles as $v): ?>
                <option value="<?= $v['fleetID'] ?>"><?= htmlspecialchars($v['license_plate']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Fecha de entrega</label>
        <input type="datetime-local" name="delivery_date" required>

        <label><input type="checkbox" name="delivery_confirmation"> Entrega confirmada</label>

        <button type="submit">Guardar paquete</button>
    </form>
    <a href="packages.php" class="back-link">← Volver al listado</a>
</div>
</body>
</html>
