<?php
// area-clientes/customer_service/ticket_detail.php
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

// Obtener flags
$perm = $pdo->prepare("SELECT can_view_any_ticket, can_add_message FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
list($canViewAny, $canAddMsg) = $perm->fetch(PDO::FETCH_NUM);
if (!$canViewAny) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para ver los tiquets.");
}

// 2) Cargar datos del tiquet
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "ID de tiquet inválido"; exit;
}
$stmt = $pdo->prepare("
  SELECT t.*,
         c.name AS client_name,   c.surname AS client_surname,
         e.name AS emp_name,      e.surname AS emp_surname,
         r.role_name
  FROM ticket t
  JOIN client   c ON t.client_id   = c.client_id
  LEFT JOIN employee e ON t.employee_id = e.employee_id
  LEFT JOIN rol      r ON e.rolID       = r.rolID
  WHERE t.ticketID = ?
");
$stmt->execute([$id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ticket) {
    echo "Tiquet no encontrado"; exit;
}

// 3) Cargar mensajes
$msgStmt = $pdo->prepare("
  SELECT m.*,
         c.name    AS client_name,   c.surname AS client_surname,
         e.name    AS emp_name,      e.surname AS emp_surname,
         r.role_name
  FROM message m
  LEFT JOIN client   c ON m.client_id   = c.client_id
  LEFT JOIN employee e ON m.employee_id = e.employee_id
  LEFT JOIN rol      r ON e.rolID       = r.rolID
  WHERE m.ticketID = ?
  ORDER BY m.created_at ASC
");
$msgStmt->execute([$id]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

// 4) Procesar nuevo mensaje
$error = "";
if ($canAddMsg && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text']);
    if ($text === '') {
        $error = "El mensaje no puede estar vacío.";
    } else {
        $ins = $pdo->prepare("
          INSERT INTO message (ticketID, employee_id, message_text)
          VALUES (?, ?, ?)
        ");
        $ins->execute([$id, $_SESSION['user_id'], $text]);
        $pdo->prepare("UPDATE ticket SET updated_at = NOW() WHERE ticketID = ?")
            ->execute([$id]);
        header("Location: ticket_detail.php?id=$id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle Tiquet #<?= $id ?> – Atención al Cliente</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f4f6fb; margin:0; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; font-size:14px; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; color:white; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; font-size:15px; transition:background .2s; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; max-width:800px; }
    .ticket-info { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); margin-bottom:30px; }
    .ticket-info h2 { margin-top:0; color:#0d3b66; }
    .ticket-info p { margin:5px 0; }
    .messages { list-style:none; padding:0; margin:0 0 30px; }
    .messages li { background:white; padding:15px; border-radius:6px; box-shadow:0 1px 4px rgba(0,0,0,0.08); margin-bottom:15px; }
    .msg-header { font-size:14px; color:#555; margin-bottom:8px; }
    .msg-header strong { color:#333; }
    .msg-text { font-size:16px; color:#222; white-space:pre-wrap; }
    .add-msg { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    .add-msg h3 { margin-top:0; color:#0d3b66; }
    .add-msg .error { color:#e74c3c; margin-bottom:10px; }
    .add-msg label { display:block; margin-bottom:8px; font-weight:bold; }
    .add-msg textarea { width:100%; height:120px; padding:10px; border:1px solid #ccc; border-radius:6px; font-family:'Inter'; }
    .add-msg button { display:block; margin:0 auto; padding:10px 20px; background:#0d3b66; color:white; border:none; border-radius:6px; cursor:pointer; }
    .add-msg button:hover { background:#095c9d; }
    .back-link { display:block; text-align:center; margin-top:20px; color:#0d3b66; text-decoration:none; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>👥 Atención al Cliente - Bienvenido, <?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?></h1>
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
  <div class="ticket-info">
    <h2><?= htmlspecialchars($ticket['categoria']) ?> — <?= htmlspecialchars($ticket['status']) ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($ticket['client_name'].' '.$ticket['client_surname']) ?></p>
    <p><strong>Empleado:</strong> <?= $ticket['emp_name'] ? htmlspecialchars($ticket['emp_name'].' '.$ticket['emp_surname'].' ('.$ticket['role_name'].')') : '–' ?></p>
    <p><strong>Creado:</strong> <?= $ticket['created_at'] ?> | <strong>Actualizado:</strong> <?= $ticket['updated_at'] ?></p>
    <p><strong>Mensaje inicial:</strong><br><?= nl2br(htmlspecialchars($ticket['message'] ?? '')) ?></p>
  </div>

  <ul class="messages">
    <?php if (empty($messages)): ?>
      <li>No hay mensajes.</li>
    <?php else: foreach ($messages as $m):
      $autor = $m['client_id']
        ? htmlspecialchars($m['client_name'].' '.$m['client_surname'].' (Cliente)')
        : htmlspecialchars($m['emp_name'].' '.$m['emp_surname'].' ('.htmlspecialchars($m['role_name']).')');
      $fecha = date('d/m/Y H:i', strtotime($m['created_at']));
    ?>
      <li>
        <div class="msg-header">
          <strong><?= $autor ?></strong>
          <span style="float:right;"><?= $fecha ?></span>
        </div>
        <div class="msg-text"><?= nl2br(htmlspecialchars($m['message_text'])) ?></div>
      </li>
    <?php endforeach; endif; ?>
  </ul>

  <?php if ($canAddMsg): ?>
    <div class="add-msg">
      <h3>➕ Añadir Mensaje</h3>
      <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <label for="message_text">Mensaje</label>
        <textarea name="message_text" id="message_text" required><?= isset($_POST['message_text']) ? htmlspecialchars($_POST['message_text']) : '' ?></textarea>
        <button type="submit">Enviar Mensaje</button>
      </form>
    </div>
  <?php endif; ?>

  <a href="tickets.php" class="back-link">← Volver a Tiquets</a>
</div>

</body>
</html>
