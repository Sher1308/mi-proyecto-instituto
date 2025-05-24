<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 6) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$stmt = $pdo->prepare("
    SELECT e.name, e.surname, e.email, e.phone, r.role_name, e.status, e.created_at
    FROM employee e
    LEFT JOIN rol r ON e.rolID = r.rolID
    WHERE e.employee_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👤 Mi Perfil - Atención al Cliente | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      background: #f4f6fb;
      color: #2c3e50;
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
      font-size: 15px;
      transition: background 0.2s;
    }

    .sidebar a:hover {
      background-color: #374151;
    }

    .container {
      margin-left: 260px;
      padding: 30px;
    }

    .card {
      background: white;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
      max-width: 600px;
    }

    h2 {
      font-size: 22px;
      margin-bottom: 20px;
    }

    p {
      margin: 10px 0;
      font-size: 15px;
      line-height: 1.6;
    }

    strong {
      color: #2c3e50;
    }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .container { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<header style="background: #1f2937; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
  <h1 style="flex: 1; text-align: center; font-size: 20px; font-weight: 600; margin: 0;">
    👤 Atención al Cliente - Mi Perfil
  </h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="tickets.php">🎫 Tiquets</a>
  <a href="clients.php">👥 Clientes</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="profile.php">👤 Mi Perfil</a>
</div>

<div class="container">
  <div class="card">
    <h2>📋 Información Personal</h2>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['phone'] ?? '—') ?></p>
    <p><strong>Rol:</strong> <?= htmlspecialchars($user['role_name']) ?></p>
    <p><strong>Estado:</strong> <?= $user['status'] === 'Active' ? '✅ Activo' : '❌ Inactivo' ?></p>
    <p><strong>Fecha de Alta:</strong> <?= $user['created_at'] ?></p>
  </div>
</div>

</body>
</html>
