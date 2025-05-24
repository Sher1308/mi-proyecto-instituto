<?php
session_start();
require_once '../db.php';

// Solo roles con can_view_any_ticket = 1
$rolID = $_SESSION['role_id'];
$perm = $pdo->prepare("SELECT can_view_any_ticket FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
if (!$perm->fetchColumn()) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para ver los tickets.");
}

// Lista tickets ya ordenados
$tickets = $pdo->query("SELECT * FROM vw_tickets_empresas")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Finanzas | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
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
      margin-bottom: 30px;
    }

    .card h2 {
      font-size: 20px;
      margin-top: 0;
      margin-bottom: 15px;
    }

    ul {
      padding-left: 20px;
    }

    ul li {
      margin: 12px 0;
      font-size: 16px;
    }

    ul li a {
      color: #0d3b66;
      font-weight: 500;
      text-decoration: none;
    }

    ul li a:hover {
      text-decoration: underline;
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
        <h1>Panel de Finanzas - Inicio</h1>
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
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h2>
    <p>Has iniciado sesión como <strong>Finanzas</strong>. Desde aquí puedes controlar las funciones financieras del sistema.</p>
  </div>

  <div class="card">
    <h2>📁 Herramientas de Finanzas</h2>
    <ul>
      <li>🧾 <a href="facturas.php">Ver o generar facturas por cliente o envío</a></li>
      <li>💰 <a href="costes_logistica.php">Análisis del coste por almacén o peso entregado</a></li>
      <li>📤 <a href="exportar_excel.php">Exportación de paquetes/entregas a Excel o CSV</a></li>
      <li>📑 <a href="historial_pagos.php">Visualizar pagos recibidos o pendientes</a></li>
      <li>🎫 <a href="tickets.php">Gestionar Tickets</a></li>
    </ul>
  </div>
</div>

</body>
</html>
