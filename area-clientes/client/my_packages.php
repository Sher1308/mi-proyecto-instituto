<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 9) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$client_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.packageID, p.weight, p.status, p.delivery_date, p.delivery_confirmation, 
           f.license_plate AS vehicle, w.name AS warehouse_name
    FROM package p
    LEFT JOIN fleet f ON p.fleetID = f.fleetID
    LEFT JOIN warehouse w ON p.warehouseID = w.warehouseID
    WHERE p.client_id = ?
    ORDER BY p.delivery_date DESC
");
$stmt->execute([$client_id]);
$packages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Mis Paquetes | INCARGO365</title>
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
            flex: 1;
            text-align: center;
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 22px;
            margin-top: 0;
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

        .export-btn {
            margin-bottom: 20px;
            padding: 10px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .export-btn:hover {
            background-color: #2980b9;
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
    <h1>📦 Mis Paquetes</h1>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🏠 Panel</a>
    <a href="my_packages.php">📦 Mis Paquetes</a>
    <a href="my_tickets.php">🎫 Mis Tiquets</a>
    <a href="ticket_create.php">➕ Nuevo Tiquet</a>
</div>

<div class="content">
    <div class="card">
        <h2>Lista de paquetes enviados</h2>
        <form method="get" action="exportar_paquetes.php">
            <button type="submit" class="export-btn">📥 Exportar a CSV</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Peso (kg)</th>
                    <th>Estado</th>
                    <th>Entrega</th>
                    <th>Confirmación</th>
                    <th>Vehículo</th>
                    <th>Almacén</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['packageID']) ?></td>
                        <td><?= htmlspecialchars($p['weight']) ?></td>
                        <td><?= htmlspecialchars($p['status']) ?></td>
                        <td><?= $p['delivery_date'] ?? '-' ?></td>
                        <td><?= $p['delivery_confirmation'] ? '✅' : '❌' ?></td>
                        <td><?= htmlspecialchars($p['vehicle'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['warehouse_name'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
