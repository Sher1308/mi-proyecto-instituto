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

// Procesar salida de paquete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['package_id'])) {
    $packageID = intval($_POST['package_id']);
    $updateStmt = $pdo->prepare("UPDATE package SET status = 'In Transit' WHERE packageID = ? AND warehouseID = ?");
    $updateStmt->execute([$packageID, $warehouseID]);
}

// Obtener paquetes almacenados
$packagesStmt = $pdo->prepare("
    SELECT packageID, client_id, weight, delivery_date 
    FROM package
    WHERE status = 'Almacenado' AND warehouseID = ?
    ORDER BY delivery_date DESC
");
$packagesStmt->execute([$warehouseID]);
$packages = $packagesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🚚 Salida de Paquetes | INCARGO365</title>
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

        .btn-salida {
            padding: 8px 14px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-salida:hover {
            background-color: #2980b9;
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
    <h1>🚚 Salida de Paquetes</h1>
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
        <h2>📤 Marcar paquetes como "En reparto"</h2>

        <?php if (count($packages) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Peso</th>
                        <th>Fecha de entrega</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $package): ?>
                        <tr>
                            <td><?= htmlspecialchars($package['packageID']) ?></td>
                            <td><?= htmlspecialchars($package['client_id']) ?></td>
                            <td><?= htmlspecialchars($package['weight']) ?> kg</td>
                            <td><?= htmlspecialchars($package['delivery_date']) ?></td>
                            <td>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="package_id" value="<?= $package['packageID'] ?>">
                                    <button type="submit" class="btn-salida">Marcar como En reparto</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-msg">
                🚫 No hay paquetes disponibles para salida.
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
