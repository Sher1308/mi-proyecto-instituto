<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 6) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$stmt = $pdo->query("SELECT client_id, name, surname, email, phone, status FROM client ORDER BY name ASC");
$clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👥 Clientes - Atención al Cliente | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      background: #f4f6fb;
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
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 14px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #1f2937;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
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
    👥 Atención al Cliente - Clientes
  </h1>
  <a href="../logout.php" class="logout" style="background-color: #e74c3c; color: white; padding: 10px 18px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; transition: background 0.3s;">
    Cerrar sesión
  </a>
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
    <h2>📋 Lista de Clientes - Atención al cliente</h2>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clients as $c): ?>
          <tr>
            <td><?= $c['client_id'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['surname']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['phone']) ?></td>
            <td><?= $c['status'] === 'Active' ? '✅ Activo' : '❌ Inactivo' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
