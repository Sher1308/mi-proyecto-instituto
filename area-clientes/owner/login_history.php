<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

$search = $_GET['search'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT lh.loginID, lh.login_time, lh.IP_address, lh.user_agent,
           c.name AS client_name, c.surname AS client_surname,
           e.name AS emp_name, e.surname AS emp_surname
    FROM login_history lh
    LEFT JOIN client c ON lh.client_id = c.client_id
    LEFT JOIN employee e ON lh.employee_id = e.employee_id
    WHERE 1 = 1
";

$params = [];

if ($search) {
    $query .= " AND (
        c.name LIKE :search OR c.surname LIKE :search OR 
        e.name LIKE :search OR e.surname LIKE :search OR
        lh.IP_address LIKE :search
    )";
    $params['search'] = "%$search%";
}
if ($from && $to) {
    $query .= " AND DATE(lh.login_time) BETWEEN :from AND :to";
    $params['from'] = $from;
    $params['to'] = $to;
}

$query .= " ORDER BY lh.login_time DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$logins = $stmt->fetchAll();

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="login_history.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Usuario', 'Fecha y Hora', 'IP', 'User-Agent']);
    foreach ($logins as $log) {
        $user = $log['client_name'] ? "Cliente: {$log['client_name']} {$log['client_surname']}" :
                ($log['emp_name'] ? "Empleado: {$log['emp_name']} {$log['emp_surname']}" : '—');
        fputcsv($output, [
            $log['loginID'], $user, $log['login_time'], $log['IP_address'], $log['user_agent']
        ]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Login - INCARGO365</title>
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

    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 20px;
    }

    .filters input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .filters button, .filters a {
      background: #0d6efd;
      color: white;
      padding: 10px 14px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
    }

    .filters button:hover, .filters a:hover {
      background: #095c9d;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #1f2937;
      color: white;
    }

    td.small-text {
      font-size: 12px;
      color: #555;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .content {
        margin-left: 0;
        padding: 20px;
      }

      .filters {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>Panel de Administración - Historial de Login</h1>
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
    <form method="get" class="filters">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Buscar usuario/IP...">
      <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
      <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
      <button type="submit">Filtrar</button>
      <a href="login_history.php">🔄 Limpiar</a>
      <a href="?export=csv<?= $search ? '&search=' . urlencode($search) : '' ?><?= ($from && $to) ? '&from=' . $from . '&to=' . $to : '' ?>">⬇️ CSV</a>
    </form>

    <h2>📜 Historial de Inicios de Sesión</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Fecha y Hora</th>
          <th>IP</th>
          <th>User-Agent</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logins as $log): ?>
          <tr>
            <td><?= $log['loginID'] ?></td>
            <td>
              <?php
              if ($log['client_name']) {
                  echo 'Cliente: ' . htmlspecialchars($log['client_name'] . ' ' . $log['client_surname']);
              } elseif ($log['emp_name']) {
                  echo 'Empleado: ' . htmlspecialchars($log['emp_name'] . ' ' . $log['emp_surname']);
              } else {
                  echo '—';
              }
              ?>
            </td>
            <td><?= $log['login_time'] ?></td>
            <td><?= $log['IP_address'] ?? 'N/A' ?></td>
            <td class="small-text"><?= htmlspecialchars($log['user_agent']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
