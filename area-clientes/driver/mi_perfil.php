<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 8) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$driver_id = $_SESSION['user_id'];

// Obtener datos del conductor y su vehículo
$stmt = $pdo->prepare("
    SELECT e.name, e.surname, e.email, e.phone, f.license_plate, f.capacity_kg, f.status
    FROM employee e
    LEFT JOIN fleet f ON e.fleetID = f.fleetID
    WHERE e.employee_id = ?
");
$stmt->execute([$driver_id]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - INCARGO365 Conductor</title>
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
            max-width: 600px;
        }

        h2 {
            margin-top: 0;
            font-size: 22px;
        }

        p {
            margin: 10px 0;
            font-size: 15px;
        }

        hr {
            border: 0;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
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
    <h1>👤 Mi Perfil</h1>
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
        <h2><?= htmlspecialchars($perfil['name'] . ' ' . $perfil['surname']) ?></h2>
        <p><strong>📧 Email:</strong> <?= htmlspecialchars($perfil['email']) ?></p>
        <p><strong>📞 Teléfono:</strong> <?= htmlspecialchars($perfil['phone'] ?? 'No registrado') ?></p>

        <hr>
        <h3>🚚 Vehículo Asignado</h3>
        <p><strong>Matrícula:</strong> <?= htmlspecialchars($perfil['license_plate'] ?? 'No asignado') ?></p>
        <p><strong>Capacidad:</strong> <?= $perfil['capacity_kg'] ? htmlspecialchars($perfil['capacity_kg']) . ' kg' : 'N/A' ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($perfil['status'] ?? '-') ?></p>
    </div>
</div>

</body>
</html>
