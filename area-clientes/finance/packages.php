<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Totales por estado
$totales = $pdo->query("
    SELECT status, COUNT(*) AS total 
    FROM package 
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Peso total almacenado
$pesoTotal = $pdo->query("SELECT SUM(weight) FROM package WHERE status = 'Almacenado'")->fetchColumn() ?: 0;

// Entregas confirmadas
$entregadas = $pdo->query("SELECT COUNT(*) FROM package WHERE delivery_confirmation = 1")->fetchColumn();

// Paquetes por almacén (versión corregida)
$porAlmacen = $pdo->query("
    SELECT w.name AS warehouse, COUNT(p.packageID) AS total
    FROM warehouse w
    LEFT JOIN package p ON p.warehouseID = w.warehouseID
    GROUP BY w.warehouseID, w.name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Paquetes - Finanzas | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f5f6fa; margin: 0; color: #2c3e50; }
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
      font-size: 14px;
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
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
      margin-bottom: 30px;
    }
    .card h2 {
      font-size: 20px;
      margin-top: 0;
      margin-bottom: 15px;
    }
    ul { padding-left: 20px; }
    ul li {
      margin: 12px 0;
      font-size: 16px;
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
      background: #0d3b66;
      color: white;
    }
    tr:hover { background: #f1f1f1; }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>Panel de Finanzas - Paquetes</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏦 Inicio</a>
  <a href="packages.php">📦 Ver Paquetes</a>
  <a href="reportes.php">📈 Reportes</a>
  <a href="tickets.php">🎫 Tickets</a>
  <a href="facturas.php">🧾 Facturas</a>
</div>

<div class="content">
  <div class="card">
    <h2>📊 Resumen General</h2>
    <ul>
      <li>📦 <strong>Pendientes:</strong> <?= $totales['Pending'] ?? 0 ?></li>
      <li>🚚 <strong>En tránsito:</strong> <?= $totales['In Transit'] ?? 0 ?></li>
      <li>✅ <strong>Entregados:</strong> <?= $totales['Delivered'] ?? 0 ?></li>
      <li>🏢 <strong>Almacenados:</strong> <?= $totales['Almacenado'] ?? 0 ?></li>
      <li>⚖️ <strong>Peso total en almacén:</strong> <?= number_format($pesoTotal, 2) ?> kg</li>
      <li>📬 <strong>Entregas confirmadas:</strong> <?= $entregadas ?></li>
    </ul>
  </div>

  <div class="card">
    <h2>🏬 Paquetes por Almacén</h2>
    <table>
      <thead>
        <tr>
          <th>Almacén</th>
          <th>Total de Paquetes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($porAlmacen as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['warehouse'] ?? 'Sin asignar') ?></td>
            <td><?= $row['total'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
