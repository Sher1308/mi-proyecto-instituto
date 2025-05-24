<?php
// admin/tickets.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Obtener nombre para el header
$stmtUser = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Cargar permisos (owner tiene todo, pero por consistencia)
$rolID = $_SESSION['role_id'];
$perm = $pdo->prepare("SELECT can_view_any_ticket, can_update_ticket, can_add_message FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
list($canViewAny, $canUpdate, $canAddMsg) = $perm->fetch(PDO::FETCH_NUM);
if (!$canViewAny) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para ver los tiquets.");
}

// Listar tiquets
$stmt = $pdo->query("
    SELECT t.ticketID, 
           c.name AS client_name, c.surname AS client_surname,
           e.name AS emp_name,   e.surname AS emp_surname,
           p.packageID,
           t.categoria,
           t.status,
           t.created_at, t.updated_at
    FROM ticket t
    LEFT JOIN client   c ON t.client_id   = c.client_id
    LEFT JOIN employee e ON t.employee_id = e.employee_id
    LEFT JOIN package  p ON t.packageID    = p.packageID
    ORDER BY t.created_at DESC
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎫 Tiquets – Owner | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f5f6fa; margin:0; color:#2c3e50; }
    header { background:#1f2937; color:#fff; padding:20px 30px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; flex:1; text-align:center; }
    .logout { background:#e74c3c; color:#fff; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; color:#eee; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; transition:background .2s; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
    .card { background:#fff; border-radius:12px; padding:25px 30px; box-shadow:0 3px 10px rgba(0,0,0,0.04); }
    .tools { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .tools h2 { margin:0; font-size:22px; }
    .tools a { background:#0d6efd; color:#fff; padding:10px 16px; border-radius:6px; text-decoration:none; }
    .tools a:hover { background:#0b5ed7; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px 10px; border-bottom:1px solid #ddd; font-size:14px; }
    th { background:#1f2937; color:#fff; }
    .actions a { margin-right:8px; text-decoration:none; font-size:16px; }
    .actions a.view { color:#0d6efd; }
    .actions a.reply { color:#28a745; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} }
  </style>
</head>
<body>

<header>
  <div style="flex:1; text-align:center;">
    <h1>Panel de Administración – Bienvenido, <?= htmlspecialchars($user['name'].' '.$user['surname']) ?></h1>
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
    <div class="tools">
      <h2>📋 Lista de Tiquets</h2>
      <a href="ticket_add.php">➕ Nuevo Tiquet</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Categoría</th>
          <th>Empleado</th>
          <th>Paquete</th>
          <th>Estado</th>
          <th>Creado</th>
          <th>Actualizado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tickets)): ?>
          <tr><td colspan="9" style="text-align:center;">No hay tiquets registrados.</td></tr>
        <?php else: foreach ($tickets as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['ticketID']) ?></td>
            <td><?= htmlspecialchars($t['client_name'].' '.$t['client_surname']) ?></td>
            <td><?= htmlspecialchars($t['categoria']) ?></td>
            <td><?= $t['emp_name'] ? htmlspecialchars($t['emp_name'].' '.$t['emp_surname']) : 'Sin asignar' ?></td>
            <td><?= htmlspecialchars($t['packageID'] ?? '—') ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td><?= htmlspecialchars($t['created_at']) ?></td>
            <td><?= htmlspecialchars($t['updated_at']) ?></td>
            <td class="actions">
              <?php if ($canUpdate): ?>
                <a href="ticket_edit.php?id=<?= $t['ticketID'] ?>" class="view">✏️</a>
              <?php endif; ?>
              <?php if ($canAddMsg): ?>
                <a href="message_add.php?ticketID=<?= $t['ticketID'] ?>" class="reply">💬</a>
              <?php endif; ?>
              <a href="ticket_detail.php?id=<?= $t['ticketID'] ?>" class="view">🔍</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
