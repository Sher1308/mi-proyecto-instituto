<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

$search = $_GET['search'] ?? '';
$sql = "
    SELECT p.packageID, c.name AS client_name, c.surname AS client_surname, 
           p.weight, p.status, w.name AS warehouse_name, 
           f.license_plate AS vehicle, p.delivery_date, p.delivery_confirmation
    FROM package p
    LEFT JOIN client c ON p.client_id = c.client_id
    LEFT JOIN warehouse w ON p.warehouseID = w.warehouseID
    LEFT JOIN fleet f ON p.fleetID = f.fleetID
    WHERE c.name LIKE :search OR c.surname LIKE :search OR p.status LIKE :search
    ORDER BY p.delivery_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$packages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Paquetes - INCARGO365 Owner</title>
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
            margin: 0 auto;
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

        .container {
            margin-left: 260px;
            padding: 30px;
        }

        .tools {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tools form {
            display: flex;
            gap: 10px;
        }

        .tools input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }

        .tools button, .tools a {
            background-color: #0d3b66;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .tools button:hover, .tools a:hover {
            background-color: #095c9d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f2937;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .acciones a {
            margin-right: 10px;
            text-decoration: none;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .container {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Panel del Administración - Paquetes</h1>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🏠 Inicio</a>
    <a href="employees.php">👥 Empleados</a>
    <a href="clients.php">🧑‍💼 Clientes</a>
    <a href="roles.php">🛡️ Roles</a>
    <a href="fleet.php">🚚 Flota</a>
    <a href="warehouses.php">🏬 Almacenes</a>
    <a href="packages.php">📦 Paquetes</a>
    <a href="tickets.php">🎫 Tiquets</a>
    <a href="login_history.php">📜 Historial de Login</a>
</div>

<div class="container">
    <div class="tools">
        <form method="get" action="">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Buscar paquete...">
            <button type="submit">Buscar</button>
        </form>
        <a href="package_add.php">➕ Nuevo paquete</a>
    </div>

    <h2>Lista de Paquetes</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Peso (kg)</th>
            <th>Estado</th>
            <th>Almacén</th>
            <th>Vehículo</th>
            <th>Entrega</th>
            <th>Confirmado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($packages as $p): ?>
            <tr>
                <td><?= $p['packageID'] ?></td>
                <td><?= htmlspecialchars($p['client_name'] . ' ' . $p['client_surname']) ?></td>
                <td><?= $p['weight'] ?></td>
                <td><?= $p['status'] ?></td>
                <td><?= htmlspecialchars($p['warehouse_name'] ?? 'No asignado') ?></td>
                <td><?= htmlspecialchars($p['vehicle'] ?? '-') ?></td>
                <td><?= $p['delivery_date'] ?? '-' ?></td>
                <td><?= $p['delivery_confirmation'] ? '✅' : '❌' ?></td>
                <td class="acciones">
                    <a href="package_edit.php?id=<?= $p['packageID'] ?>">✏️</a>
                    <a href="package_delete.php?id=<?= $p['packageID'] ?>" onclick="return confirm('¿Eliminar este paquete?');">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
