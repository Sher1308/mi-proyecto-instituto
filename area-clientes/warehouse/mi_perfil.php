<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 7) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Obtener datos del empleado
$stmt = $pdo->prepare("
    SELECT e.*, w.name AS warehouse_name 
    FROM employee e
    LEFT JOIN warehouse w ON e.warehouseID = w.warehouseID
    WHERE e.employee_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Empleado no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>👤 Mi Perfil | INCARGO365</title>
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
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            max-width: 600px;
        }

        h2 {
            margin-top: 0;
            font-size: 22px;
        }

        .info {
            margin-bottom: 15px;
            font-size: 15px;
        }

        .info label {
            font-weight: 600;
            display: inline-block;
            width: 160px;
            color: #34495e;
        }

        .profile-pic {
            float: right;
            max-width: 120px;
            max-height: 120px;
            border-radius: 10px;
            object-fit: cover;
            margin-left: 20px;
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

            .profile-pic {
                float: none;
                display: block;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>👤 Mi Perfil</h1>
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
        <h2><?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?></h2>
        <?php if (!empty($user['profile_picture'])): ?>
            <img class="profile-pic" src="../uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Foto de perfil">
        <?php endif; ?>
        <div class="info"><label>📧 Email:</label> <?= htmlspecialchars($user['email']) ?></div>
        <div class="info"><label>📞 Teléfono:</label> <?= htmlspecialchars($user['phone'] ?? 'No disponible') ?></div>
        <div class="info"><label>🏢 Almacén asignado:</label> <?= htmlspecialchars($user['warehouse_name'] ?? 'Sin asignar') ?></div>
        <div class="info"><label>🔐 Estado:</label> <?= htmlspecialchars($user['status']) ?></div>
    </div>
</div>

</body>
</html>
