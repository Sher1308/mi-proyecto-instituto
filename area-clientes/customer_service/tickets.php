<?php
// area-clientes/customer_service/tickets.php
session_start();
require_once '../db.php';

// 1) Sólo Atención al Cliente (role_id = 6) y roles con permisos de tickets
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 6) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];

// Obtener nombre para el header
$stmtUser = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Obtener flags de permisos
$perm = $pdo->prepare("
    SELECT can_view_any_ticket, can_create_ticket, can_update_ticket, can_add_message 
    FROM rol 
    WHERE rolID = ?
");
$perm->execute([$rolID]);
list($canViewAny, $canCreate, $canUpdate, $canAddMsg) = $perm->fetch(PDO::FETCH_NUM);

if (!$canViewAny) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para ver los tickets.");
}

// 2) Carga tickets según vista de empresas
$tickets = $pdo->query("SELECT * FROM vw_tickets_empresas")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎫 Tiquets – Atención al Cliente | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; margin:0; background:#f5f6fa; color:#2c3e50; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; font-size:14px; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; font-size:15px; transition:background .2s; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
    .card { background:white; border-radius:12px; padding:25px 30px; box-shadow:0 3px 10px rgba(0,0,0,0.04); margin-bottom:30px; }
    .card h2 { font-size:20px; margin:0 0 15px; }
    .new-ticket-btn { display:inline-block; margin-bottom:15px; padding:8px 12px; background:#0d3b66; color:white; border-radius:6px; text-decoration:none; }
    .new-ticket-btn:hover { background:#095c9d; }
    table { width:100%; border-collapse:collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding:12px 15px; border-bottom:1px solid #ddd; text-align:left; font-size:14px; }
    th { background:#0d3b66; color:white; }
    tr:hover { background:#f1f1f1; }
    .actions a { margin-right:8px; text-decoration:none; color:#0d3b66; font-weight:500; }
    .actions a:hover { text-decoration:underline; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h2>Tickets -  Atención al Cliente</h2>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="tickets.php">🎫 Tiquets</a>
  <a href="clients.php">👥 Clientes</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="profile.php">👤 Mi Perfil</a>
</div>

<div class="content">
  <div class="card">
    <h2>🎫 Lista de Tiquets</h2>

    <?php if ($canCreate): ?>
      <a href="ticket_add.php" class="new-ticket-btn">➕ Nuevo Tiquet</a>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Categoría</th>
          <th>Estado</th>
          <th>Cliente</th>
          <th>Empleado</th>
          <th>Creado</th>
          <th>Actualizado</th>
          <th>Mensaje</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($tickets as $t): ?>
        <tr>
          <td><?= $t['ticketID'] ?></td>
          <td><?= htmlspecialchars($t['categoria']) ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
          <td><?= $t['client_id'] ?></td>
          <td><?= $t['employee_id'] ?? '-' ?></td>
          <td><?= $t['created_at'] ?></td>
          <td><?= $t['updated_at'] ?></td>
          <td><?= htmlspecialchars(mb_strimwidth($t['message'] ?? '', 0, 40, '…')) ?></td>
          <td class="actions">
            <?php if ($canUpdate): ?>
              <a href="ticket_edit.php?id=<?= $t['ticketID'] ?>">Editar</a>
            <?php endif; ?>
            <?php if ($canAddMsg): ?>
              <a href="message_add.php?ticketID=<?= $t['ticketID'] ?>">Añadir mensaje</a>
            <?php endif; ?>
            <a href="ticket_detail.php?id=<?= $t['ticketID'] ?>">Ver detalle</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
