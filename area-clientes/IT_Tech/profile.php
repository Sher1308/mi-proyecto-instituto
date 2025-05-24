<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 5) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$stmt = $pdo->prepare("SELECT name, surname, email, phone, gender, profile_picture, status FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tech = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👤 Mi Perfil - IT_Tech INCARGO365</title>
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
      text-align: center;
    }

    .profile-picture {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card h2 {
      margin: 10px 0 15px;
      font-size: 22px;
      color: #1f2937;
    }

    .card p {
      font-size: 15px;
      margin: 8px 0;
    }

    strong {
      color: #1f2937;
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
    <h1>👤 Mi Perfil Técnico</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="tools.php">🧰 Herramientas</a>
  <a href="logs.php">📜 Logs</a>
  <a href="integrations.php">🔌 Integraciones</a>
  <a href="settings.php">⚙️ Configuración</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="tickets.php">🎫 Tickets</a>
  <a href="profile.php">👤 Mi Perfil</a>
</div>

<div class="content">
  <div class="card">
    <?php if ($tech['profile_picture']): ?>
      <img src="<?= htmlspecialchars($tech['profile_picture']) ?>" class="profile-picture" alt="Foto de perfil">
    <?php endif; ?>
    <h2><?= htmlspecialchars($tech['name'] . ' ' . $tech['surname']) ?></h2>
    <p><strong>📧 Correo:</strong> <?= htmlspecialchars($tech['email']) ?></p>
    <p><strong>📞 Teléfono:</strong> <?= htmlspecialchars($tech['phone']) ?: '—' ?></p>
    <p><strong>👤 Género:</strong> <?= $tech['gender'] ?: '—' ?></p>
    <p><strong>⚙️ Estado:</strong> <?= $tech['status'] === 'Active' ? '✅ Activo' : '❌ Inactivo' ?></p>
  </div>
</div>

</body>
</html>
