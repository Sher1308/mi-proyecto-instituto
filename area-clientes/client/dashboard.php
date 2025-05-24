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

// Obtener nombre del cliente
$stmt = $pdo->prepare("SELECT name, surname FROM client WHERE client_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$client = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente | INCARGO365</title>
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
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            font-size: 22px;
        }

        p {
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
    <h1>👋 Bienvenido, <?= htmlspecialchars($client['name'] . ' ' . $client['surname']) ?></h1>
    <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
    <a href="dashboard.php">🏠 Panel</a>
    <a href="my_packages.php">📦 Mis Paquetes</a>
    <a href="ticket_create.php">➕ Nuevo Tiquet</a>
    <a href="my_tickets.php">🎫 Mis Tiquets</a>
</div>

<div class="content">
    <div class="card">
        <h2>📊 Panel de Cliente</h2>
        <p>Aquí podrás gestionar y consultar tus paquetes, enviar tiquets de soporte y seguir el estado de tus entregas.</p>
    </div>
</div>

</body>
</html>
