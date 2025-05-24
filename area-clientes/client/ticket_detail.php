<?php
// area-clientes/client/ticket_detail.php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 9) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$client_id = $_SESSION['user_id'];

// Obtener nombre para el header
$stmtClient = $pdo->prepare("SELECT name, surname FROM client WHERE client_id = ?");
$stmtClient->execute([$client_id]);
$client = $stmtClient->fetch();

// Obtener ticket y validar que le pertenece
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("
    SELECT t.*, 
           e.name AS emp_name, e.surname AS emp_surname 
    FROM ticket t
    LEFT JOIN employee e ON t.employee_id = e.employee_id
    WHERE t.ticketID = ? AND t.client_id = ?
");
$stmt->execute([$id, $client_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ticket) {
    echo "Tiquet no encontrado o sin permiso."; exit;
}

// Obtener mensajes ordenados
$msgStmt = $pdo->prepare("
    SELECT m.*, 
           c.name AS client_name, c.surname AS client_surname,
           e.name AS emp_name,    e.surname AS emp_surname 
    FROM message m
    LEFT JOIN client   c ON m.client_id   = c.client_id
    LEFT JOIN employee e ON m.employee_id = e.employee_id
    WHERE m.ticketID = ?
    ORDER BY m.created_at ASC
");
$msgStmt->execute([$id]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar respuesta del cliente
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text']);
    if ($text === '') {
        $error = "El mensaje no puede estar vacío.";
    } else {
        $ins = $pdo->prepare("
            INSERT INTO message (ticketID, client_id, message_text)
            VALUES (?, ?, ?)
        ");
        $ins->execute([$id, $client_id, $text]);
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
  <title>Detalle Tiquet #<?= $id ?> – Cliente | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f4f6fb; margin:0; }
    header { background:#1f2937; color:white; padding:20px 30px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; flex:1; text-align:center; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; color:white; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; transition:background .2s; }
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
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} header{flex-direction:column;align-items:flex-start;} }
  </style>
</head>
<body>

<header>
  <h1>🎫 Detalle Tiquet – <?= htmlspecialchars($client['name'].' '.$client['surname']) ?></h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Panel</a>
  <a href="my_packages.php">📦 Mis Paquetes</a>
  <a href="my_tickets.php">🎫 Mis Tiquets</a>
  <a href="ticket_create.php">➕ Nuevo Tiquet</a>
</div>

<div class="content">
  <div class="ticket-info">
    <h2><?= htmlspecialchars($ticket['categoria']) ?> — <?= htmlspecialchars($ticket['status']) ?></h2>
    <p><strong>Creado:</strong> <?= htmlspecialchars($ticket['created_at']) ?> | <strong>Actualizado:</strong> <?= htmlspecialchars($ticket['updated_at']) ?></p>
    <p><strong>Empleado:</strong> <?= $ticket['emp_name'] ? htmlspecialchars($ticket['emp_name'].' '.$ticket['emp_surname']) : 'Sin asignar' ?></p>
    <p><strong>Mensaje inicial:</strong><br><?= nl2br(htmlspecialchars($ticket['message'])) ?></p>
  </div>

  <ul class="messages">
    <?php if (empty($messages)): ?>
      <li>No hay mensajes aún.</li>
    <?php else: foreach ($messages as $m):
      $autor = $m['client_id']
        ? htmlspecialchars($m['client_name'].' '.$m['client_surname'].' (Tú)')
        : htmlspecialchars($m['emp_name'].' '.$m['emp_surname'].' (Soporte)');
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

  <div class="add-msg">
    <h3>➕ Responder Tiquet</h3>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <label for="message_text">Tu mensaje</label>
      <textarea name="message_text" id="message_text" required><?= htmlspecialchars($_POST['message_text'] ?? '') ?></textarea>
      <button type="submit">Enviar Respuesta</button>
    </form>
  </div>

  <a href="my_tickets.php" class="back-link">← Volver a Mis Tiquets</a>
</div>

</body>
</html>
