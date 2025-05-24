<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Totales
$total = $pdo->query("SELECT COUNT(*) FROM package")->fetchColumn();
$delivered = $pdo->query("SELECT COUNT(*) FROM package WHERE status = 'Delivered'")->fetchColumn();
$stored = $pdo->query("SELECT COUNT(*) FROM package WHERE status = 'Almacenado'")->fetchColumn();
$inTransit = $pdo->query("SELECT COUNT(*) FROM package WHERE status = 'In Transit'")->fetchColumn();
$totalWeight = $pdo->query("SELECT SUM(weight) FROM package")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Finanzas | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f5f6fa; color: #2c3e50; }
    header {
      background: #1f2937;
      color: white;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logout {
      background-color: #e74c3c;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
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
    }
    .sidebar a:hover { background-color: #374151; }
    .content {
      margin-left: 260px;
      padding: 30px;
    }
    .card {
      background: white;
      border-radius: 12px;
      padding: 25px 30px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      margin-bottom: 30px;
    }
    .card h3 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #1a1a1a;
    }
    .card p {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
    }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>Panel de Finanzas - Reportes</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏦 Inicio</a>
  <a href="packages.php">📦 Ver Paquetes</a>
  <a href="reportes.php">📊 Reportes</a>
  <a href="tickets.php">🎫 Tickets</a>
  <a href="facturas.php">🧾 Facturas</a>
</div>

<div class="content">
  <h2>Resumen de Actividad Logística</h2>

  <div class="card">
    <h3>📦 Total de Paquetes</h3>
    <p><?= $total ?></p>
  </div>

  <div class="card">
    <h3>✅ Entregados</h3>
    <p><?= $delivered ?></p>
  </div>

  <div class="card">
    <h3>🏢 Almacenados</h3>
    <p><?= $stored ?></p>
  </div>

  <div class="card">
    <h3>🚚 En Tránsito</h3>
    <p><?= $inTransit ?></p>
  </div>

  <div class="card">
    <h3>⚖️ Peso Total</h3>
    <p><?= number_format($totalWeight, 2) ?> kg</p>
  </div>
</div>

</body>
</html>
