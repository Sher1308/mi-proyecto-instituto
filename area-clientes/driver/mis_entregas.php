<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 8) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$driver_id = $_SESSION['user_id'];

// Buscar fleetID asignado al conductor
$stmt = $pdo->prepare("SELECT fleetID FROM employee WHERE employee_id = ?");
$stmt->execute([$driver_id]);
$fleetID = $stmt->fetchColumn();

// Preparamos entregas si hay vehículo asignado
$entregas = [];
if ($fleetID) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS client_name, c.surname AS client_surname 
        FROM package p 
        LEFT JOIN client c ON p.client_id = c.client_id 
        WHERE p.fleetID = ?
    ");
    $stmt->execute([$fleetID]);
    $entregas = $stmt->fetchAll();

    // Si se pulsa el botón de entrega
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
        $package_id = $_POST['package_id'];
        $update = $pdo->prepare("
            UPDATE package 
            SET status = 'Entregado', delivery_date = NOW(), delivery_confirmation = 1 
            WHERE packageID = ? AND fleetID = ?
        ");
        $update->execute([$package_id, $fleetID]);
        header("Location: mis_entregas.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Mis Entregas | INCARGO365</title>
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

        .btn {
            background: #27ae60;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1e8449;
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
    <h1>📦 Mis Entregas</h1>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🚛 Inicio</a>
    <a href="mis_rutas.php">🗺️ Mis Rutas</a>
    <a href="mis_entregas.php">📦 Mis Entregas</a>
    <a href="mi_perfil.php">👤 Mi Perfil</a>
</div>

<div class="content">
    <div class="card">
        <h2>Entregas asignadas</h2>
        <?php if (!$fleetID): ?>
            <div class="no-data" style="color: #c0392b; font-weight: 600;">
                ❌ Este conductor no tiene ningún vehículo asignado. Contacta con el administrador.
            </div>
        <?php elseif (count($entregas) === 0): ?>
            <div class="no-data">🚫 No tienes entregas asignadas.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Peso (kg)</th>
                        <th>Estado</th>
                        <th>Fecha Entrega</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entregas as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['packageID']) ?></td>
                            <td><?= htmlspecialchars($e['client_name'] . ' ' . $e['client_surname']) ?></td>
                            <td><?= htmlspecialchars($e['weight']) ?></td>
                            <td><?= htmlspecialchars($e['status']) ?></td>
                            <td><?= $e['delivery_date'] ?? '-' ?></td>
                            <td>
                                <?php if ($e['status'] !== 'Entregado'): ?>
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="package_id" value="<?= $e['packageID'] ?>">
                                        <button class="btn" type="submit">Marcar Entregado</button>
                                    </form>
                                <?php else: ?>
                                    ✅ Entregado
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
