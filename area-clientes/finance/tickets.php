<?php
session_start();
require_once '../db.php';

// 1) Sólo Finanzas (role_id = 2) y roles con can_view_any_ticket = 1
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];
$perm = $pdo->prepare("SELECT can_view_any_ticket, can_update_ticket, can_add_message 
                       FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
list($canViewAny, $canUpdate, $canAddMsg) = $perm->fetch(PDO::FETCH_NUM);
if (!$canViewAny) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para ver los tickets.");
}

// 2) Carga de tickets
$tickets = $pdo->query("SELECT * FROM vw_tickets_empresas")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tickets - Finanzas | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; margin:0; background:#f5f6fa; color:#2c3e50; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { margin:0; font-size:20px; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; }
    .sidebar { width:240px; height:100vh; background:#1f2937; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
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
  <h1>🎫 Tickets – Finanzas</h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏦 Inicio</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="reportes.php">📈 Reportes</a>
  <a href="tickets.php">🎫 Tickets</a>
  <a href="facturas.php">🧾 Facturas</a>
</div>

<div class="content">
  <div class="card">
    <h2>Listado de Tickets</h2>
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
      <?php foreach($tickets as $t): ?>
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
            <?php if($canUpdate): ?>
              <a href="ticket_edit.php?id=<?= $t['ticketID'] ?>">Editar</a>
            <?php endif; ?>
            <?php if($canAddMsg): ?>
              <a href="message_add.php?ticketID=<?= $t['ticketID'] ?>">Añadir mensaje</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
