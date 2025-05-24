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

// Total por estado
$estadoStmt = $pdo->prepare("
    SELECT status, COUNT(*) AS total 
    FROM package 
    WHERE warehouseID = ?
    GROUP BY status
");
$estadoStmt->execute([$warehouseID]);
$porEstado = $estadoStmt->fetchAll(PDO::FETCH_ASSOC);

// Total por cliente
$clienteStmt = $pdo->prepare("
    SELECT client_id, COUNT(*) AS total 
    FROM package 
    WHERE warehouseID = ?
    GROUP BY client_id
");
$clienteStmt->execute([$warehouseID]);
$porCliente = $clienteStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📋 Inventario | INCARGO365</title>
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
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 0;
            font-size: 20px;
            color: #2c3e50;
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
    <h1>📋 Inventario del Almacén</h1>
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
        <h2>📊 Paquetes por Estado</h2>
        <table>
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($porEstado as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['status']) ?></td>
                        <td><?= htmlspecialchars($fila['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>👥 Paquetes por Cliente</h2>
        <table>
            <thead>
                <tr>
                    <th>ID del Cliente</th>
                    <th>Total de Paquetes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($porCliente as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['client_id']) ?></td>
                        <td><?= htmlspecialchars($fila['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
