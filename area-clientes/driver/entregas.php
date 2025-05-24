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

// Si se pulsa el botón de entrega
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    $package_id = $_POST['package_id'];
    $update = $pdo->prepare("UPDATE package SET status = 'Entregado', delivery_date = NOW(), delivery_confirmation = 1 WHERE packageID = ? AND fleetID = ?");
    $update->execute([$package_id, $fleetID]);
    header("Location: mis_entregas.php");
    exit;
}

// Obtener paquetes asignados
$stmt = $pdo->prepare("SELECT p.*, c.name AS client_name, c.surname AS client_surname FROM package p LEFT JOIN client c ON p.client_id = c.client_id WHERE p.fleetID = ?");
$stmt->execute([$fleetID]);
$entregas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Entregas - INCARGO365 Conductor</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 20px; }
        .container { padding: 20px; margin-left: 240px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #34495e; color: white; }
        tr:hover { background: #f1f1f1; }
        .sidebar { width: 220px; height: 100vh; background: #444; color: white; position: fixed; top: 0; left: 0; padding-top: 60px; }
        .sidebar a { display: block; color: white; padding: 15px; text-decoration: none; }
        .sidebar a:hover { background-color: #666; }
        form { display: inline; }
        .btn { background: #27ae60; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #1e8449; }
    </style>
</head>
<body>

<div class="header">
    <h1>Panel del Conductor - Mis Entregas</h1>
</div>

<div class="sidebar">
    <a href="dashboard.php">🏠 Inicio</a>
    <a href="mis_rutas.php">🛣️ Mis Rutas</a>
    <a href="mis_entregas.php">📦 Mis Entregas</a>
    <a href="../logout.php">🚪 Cerrar sesión</a>
</div>

<div class="container">
    <h2>Entregas asignadas</h2>
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
                    <td><?= $e['packageID'] ?></td>
                    <td><?= htmlspecialchars($e['client_name'] . ' ' . $e['client_surname']) ?></td>
                    <td><?= $e['weight'] ?></td>
                    <td><?= $e['status'] ?></td>
                    <td><?= $e['delivery_date'] ?? '-' ?></td>
                    <td>
                        <?php if ($e['status'] !== 'Entregado'): ?>
                            <form method="POST">
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
</div>

</body>
</html>
