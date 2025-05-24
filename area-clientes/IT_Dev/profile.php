<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 4) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$stmt = $pdo->prepare("SELECT name, surname, email, phone, status, last_login FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👤 Mi Perfil - IT Dev | INCARGO365</title>
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
      max-width: 600px;
    }

    .card h2 {
      font-size: 20px;
      margin-top: 0;
      margin-bottom: 20px;
    }

    .card p {
      margin: 10px 0;
      font-size: 16px;
    }

    .card strong {
      color: #2c3e50;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>👤 Mi Perfil</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="tools.php">🧰 Herramientas</a>
  <a href="packages.php">📦 Ver Paquetes</a>
  <a href="logs.php">📜 Logs del Sistema</a>
  <a href="integrations.php">🔌 Integraciones</a>
  <a href="settings.php">⚙️ Configuración</a>
  <a href="profile.php">👤 Mi Perfil</a>
</div>

<div class="content">
  <div class="card">
    <h2>📄 Información personal</h2>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
    <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['phone'] ?? '—') ?></p>
    <p><strong>Estado:</strong> <?= $user['status'] === 'Active' ? '✅ Activo' : '❌ Inactivo' ?></p>
    <p><strong>Último acceso:</strong> <?= $user['last_login'] ?? '—' ?></p>
  </div>
</div>

</body>
</html>
