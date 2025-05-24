<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 7) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Obtener warehouseID del empleado
$stmt = $pdo->prepare("SELECT warehouseID FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$warehouseID = $stmt->fetchColumn();

// Obtener conteo por estado
$counts = [
    'Almacenado' => 0,
    'In Transit' => 0,
    'Delivered' => 0
];

$countStmt = $pdo->prepare("SELECT status, COUNT(*) as total FROM package WHERE warehouseID = ? GROUP BY status");
$countStmt->execute([$warehouseID]);
foreach ($countStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $counts[$row['status']] = $row['total'];
}

// Filtro
$estado = $_GET['estado'] ?? '';
if ($estado && in_array($estado, ['Almacenado', 'In Transit', 'Delivered'])) {
    $query = "SELECT * FROM package WHERE status = ? AND warehouseID = ? ORDER BY delivery_date DESC";
    $params = [$estado, $warehouseID];
} else {
    $query = "SELECT * FROM package WHERE warehouseID = ? ORDER BY delivery_date DESC";
    $params = [$warehouseID];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Paquetes en Almacén | INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f6fa;
            margin: 0;
            color: #2c3e50;
        }

        header {
            background: #1f2937;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .logout {
            background-color: #e74c3c;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px;
        }

        .sidebar a {
            display: block;
            color: #eee;
            padding: 15px 25px;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 15px;
        }

        .sidebar a:hover {
            background-color: #374151;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
            margin-bottom: 30px;
        }

        select, button {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-right: 10px;
        }

        button {
            background-color: #1f2937;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }

        th {
            background-color: #1f2937;
            color: white;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .empty-msg {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<header>
    <div style="flex: 1; text-align: center;">
        <h1>📦 Paquetes en Almacén</h1>
    </div>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🏠 Inicio</a>
    <a href="paquetes_almacen.php">📦 Paquetes en almacén</a>
    <a href="salida_paquetes.php">🚚 Salida de paquetes</a>
    <a href="inventario.php">📋 Inventario</a>
    <a href="mi_perfil.php">👤 Mi Perfil</a>
</div>

<div class="content">
    <div class="card">
        <form method="get">
            <label for="estado">🔎 Filtrar por estado:</label>
            <select name="estado" id="estado" onchange="this.form.submit()">
                <option value="">-- Todos (<?= array_sum($counts) ?>) --</option>
                <option value="Almacenado" <?= $estado === 'Almacenado' ? 'selected' : '' ?>>🔵 Almacenado (<?= $counts['Almacenado'] ?>)</option>
                <option value="In Transit" <?= $estado === 'In Transit' ? 'selected' : '' ?>>🟡 En tránsito (<?= $counts['In Transit'] ?>)</option>
                <option value="Delivered" <?= $estado === 'Delivered' ? 'selected' : '' ?>>✅ Entregado (<?= $counts['Delivered'] ?>)</option>
            </select>
        </form>
    </div>

    <div class="card">
        <?php if (count($packages) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Peso (kg)</th>
                        <th>Estado</th>
                        <th>Fecha de entrega</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['packageID']) ?></td>
                            <td><?= htmlspecialchars($p['client_id']) ?></td>
                            <td><?= htmlspecialchars($p['weight']) ?></td>
                            <td><?= htmlspecialchars($p['status']) ?></td>
                            <td><?= htmlspecialchars($p['delivery_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-msg">🚫 No hay paquetes disponibles para este filtro.</div>
        <?php endif; ?>
    </div>

    <div class="card">
        <form method="get" action="export_paquetes.php">
            <input type="hidden" name="estado" value="<?= htmlspecialchars($estado) ?>">
            <button type="submit">📥 Exportar a CSV</button>
        </form>
    </div>
</div>

</body>
</html>
