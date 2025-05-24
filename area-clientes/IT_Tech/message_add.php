<?php
// area-clientes/it_tech/message_add.php
session_start();
require_once '../db.php';

// 1) Sólo IT_Tech (role_id = 5) y roles con permiso de añadir mensajes
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 5) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];

// Obtener nombre para el header
$stmtUser = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Verificar permiso can_add_message
$perm = $pdo->prepare("SELECT can_add_message FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
if (!$perm->fetchColumn()) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para añadir mensajes.");
}

// 2) Obtener ticketID
$ticketID = isset($_GET['ticketID']) ? (int)$_GET['ticketID'] : 0;
if (!$ticketID) {
    echo "ID de ticket inválido";
    exit;
}

// 3) Comprobar que el ticket existe
$stmt = $pdo->prepare("SELECT ticketID, categoria, status FROM ticket WHERE ticketID = ?");
$stmt->execute([$ticketID]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ticket) {
    echo "Ticket no encontrado";
    exit;
}

// 4) Procesar POST
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text']);
    if ($text === '') {
        $error = "El mensaje no puede estar vacío.";
    } else {
        $ins = $pdo->prepare("
            INSERT INTO message (ticketID, employee_id, message_text)
            VALUES (?, ?, ?)
        ");
        $ins->execute([$ticketID, $_SESSION['user_id'], $text]);
        // Actualizar ticket.updated_at
        $pdo->prepare("UPDATE ticket SET updated_at = NOW() WHERE ticketID = ?")
            ->execute([$ticketID]);
        header("Location: tickets.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Mensaje – Ticket #<?= $ticketID ?> | Panel Técnico</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f5f6fa; margin:0; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; font-size:14px; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; color:white; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; font-size:15px; transition:background .2s; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; display:flex; justify-content:center; }
    .form-container { background:white; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); width:100%; max-width:600px; }
    h2 { text-align:center; color:#0d3b66; margin-bottom:20px; }
    .error { color:#e74c3c; margin-bottom:15px; text-align:center; }
    label { display:block; margin-bottom:8px; font-weight:bold; }
    textarea { width:100%; height:150px; padding:10px; border:1px solid #ccc; border-radius:6px; font-family:'Inter'; box-sizing:border-box; }
    button { display:block; margin:20px auto 0; padding:12px 30px; background:#0d3b66; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer; }
    button:hover { background:#095c9d; }
    .back-link { display:block; text-align:center; margin-top:20px; color:#0d3b66; text-decoration:none; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>Panel Técnico - Bienvenido, <?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?></h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="tools.php">🧰 Herramientas</a>
  <a href="logs.php">📜 Logs</a>
  <a href="integrations.php">🔌 Integraciones</a>
  <a href="settings.php">⚙️ Configuración</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="tickets.php">🎫 Tickets</a>
  <a href="profile.php">👤 Mi Perfil</a>
</div>

<div class="content">
  <div class="form-container">
    <h2>➕ Añadir Mensaje – Ticket #<?= $ticketID ?></h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <label for="message_text">Mensaje</label>
      <textarea name="message_text" id="message_text" required><?= isset($_POST['message_text']) ? htmlspecialchars($_POST['message_text']) : '' ?></textarea>
      <button type="submit">Enviar Mensaje</button>
    </form>
    <a href="tickets.php" class="back-link">← Volver a Tickets</a>
  </div>
</div>

</body>
</html>
