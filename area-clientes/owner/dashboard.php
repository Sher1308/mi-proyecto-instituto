<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

date_default_timezone_set("Europe/Madrid");

// Función para contar registros con PDO
function contar($tabla) {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM $tabla");
    $row = $stmt->fetch();
    return $row['total'];
}

// Contadores
$empleados = contar('employee');
$clientes = contar('client');
$paquetes = contar('package');
$tickets = contar('ticket');
$vehiculos = contar('fleet');
$almacenes = contar('warehouse');

// Últimos accesos
$sql_logins = "SELECT * FROM login_history ORDER BY login_time DESC LIMIT 5";
$result_logins = $pdo->query($sql_logins);
$ultimos_logins = $result_logins->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Owner | INCARGO365</title>
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
      margin-bottom: 30px;
    }

    .card h2 {
      margin-top: 0;
      font-size: 20px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .stat-box {
      background: #f2f6fc;
      border-left: 6px solid #1f2937;
      border-radius: 10px;
      padding: 18px 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: all 0.2s ease-in-out;
    }

    .stat-box:hover {
      background: #e6eef9;
      transform: translateY(-2px);
    }

    .stat-box h3 {
      margin: 0;
      font-size: 15px;
      font-weight: 600;
      color: #1f2937;
    }

    .stat-box p {
      font-size: 26px;
      margin-top: 10px;
      font-weight: bold;
      color: #0d3b66;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table th, table td {
      padding: 12px 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background: #1f2937;
      color: white;
    }

    table td {
      font-size: 14px;
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
    <h1>Panel de Administración - Inicio</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="employees.php">👥 Empleados</a>
  <a href="clients.php">🧑‍💼 Clientes</a>
  <a href="roles.php">🛡️ Roles</a>
  <a href="fleet.php">🚚 Flota</a>
  <a href="warehouses.php">🏬 Almacenes</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="tickets.php">🎫 Tiquets</a>
  <a href="login_history.php">📜 Historial de Login</a>
</div>

<div class="content">
  <div class="card">
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h2>
    <p>Has iniciado sesión como <strong>Owner</strong>. Desde aquí puedes supervisar y gestionar todo el sistema.</p>
  </div>

  <div class="card">
    <h3 style="margin-bottom: 20px;">📊 Resumen general</h3>
    <div class="stats-grid">
      <div class="stat-box">
        <h3>👥 Empleados</h3>
        <p><?= $empleados ?></p>
      </div>
      <div class="stat-box">
        <h3>🧑‍💼 Clientes</h3>
        <p><?= $clientes ?></p>
      </div>
      <div class="stat-box">
        <h3>📦 Paquetes</h3>
        <p><?= $paquetes ?></p>
      </div>
      <div class="stat-box">
        <h3>🎫 Tiquets</h3>
        <p><?= $tickets ?></p>
      </div>
      <div class="stat-box">
        <h3>🚚 Vehículos</h3>
        <p><?= $vehiculos ?></p>
      </div>
      <div class="stat-box">
        <h3>🏬 Almacenes</h3>
        <p><?= $almacenes ?></p>
      </div>
    </div>
  </div>

  <div class="card">
    <h3>📜 Últimos accesos al sistema</h3>
    <table>
      <thead>
        <tr>
          <th>ID Usuario</th>
          <th>Fecha y Hora</th>
          <th>IP</th>
          <th>User-Agent</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ultimos_logins as $login): ?>
          <tr>
            <td><?= $login['employee_id'] ?? $login['client_id'] ?></td>
            <td><?= $login['login_time'] ?></td>
            <td><?= $login['IP_address'] ?></td>
            <td><?= substr($login['user_agent'], 0, 40) ?>...</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
