<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 8) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Obtener datos del conductor
$stmt = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Conductor no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Conductor | INCARGO365</title>
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
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.04);
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 0;
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
    <h1>🚛 Panel del Conductor</h1>
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
        <h2>👋 Bienvenido, <?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?></h2>
        <p>Este es tu panel de control como conductor. Aquí verás tus próximas rutas, entregas y actividad.</p>
    </div>

    <div class="card">
        <h2>Resumen de Hoy</h2>
        <p>📦 Entregas asignadas: <strong>3</strong></p>
        <p>🛣️ Rutas pendientes: <strong>2</strong></p>
        <p>✅ Última entrega realizada: <strong>09:15h</strong></p>
    </div>

    <div class="card">
        <h2>Próxima Entrega</h2>
        <p><strong>Destino:</strong> Calle Mallorca, 185, Barcelona</p>
        <p><strong>Paquete:</strong> ID #10293</p>
        <p><strong>Hora estimada:</strong> 11:45h</p>
    </div>
</div>

</body>
</html>
