<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 8) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$driver_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT * FROM route
    WHERE driver_id = ?
    ORDER BY scheduled_date ASC
");
$stmt->execute([$driver_id]);
$routes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🗺️ Mis Rutas | INCARGO365</title>
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
            text-align: center;
            flex: 1;
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

        .no-data {
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        h2 {
            font-size: 20px;
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
    <h1>🗺️ Mis Rutas</h1>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🚛 Inicio</a>
    <a href="mis_rutas.php">🗺️ Mis Rutas</a>
    <a href="mis_entregas.php">📦 Entregas</a>
    <a href="mi_perfil.php">👤 Mi Perfil</a>
</div>

<div class="content">
    <div class="card">
        <h2>Rutas Asignadas</h2>
        <?php if (count($routes) === 0): ?>
            <div class="no-data">🚫 No tienes rutas asignadas por ahora.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Ruta</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha Programada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($routes as $route): ?>
                        <tr>
                            <td><?= htmlspecialchars($route['route_id']) ?></td>
                            <td><?= htmlspecialchars($route['origin']) ?></td>
                            <td><?= htmlspecialchars($route['destination']) ?></td>
                            <td><?= htmlspecialchars($route['scheduled_date']) ?></td>
                            <td><?= htmlspecialchars($route['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
