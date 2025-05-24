<?php
// area-clientes/client/message_add.php
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

// 1) Obtener ticketID y validar que pertenece al cliente
$ticketID = isset($_GET['ticketID']) ? (int)$_GET['ticketID'] : 0;
$stmt = $pdo->prepare("SELECT ticketID FROM ticket WHERE ticketID = ? AND client_id = ?");
$stmt->execute([$ticketID, $client_id]);
if (!$stmt->fetch()) {
    echo "Tiquet no encontrado o sin permiso."; exit;
}

// 2) Insertar mensaje
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text']);
    if ($text === '') {
        $error = "El mensaje no puede estar vacío.";
    } else {
        $ins = $pdo->prepare("
            INSERT INTO message (ticketID, client_id, message_text, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        $ins->execute([$ticketID, $client_id, $text]);
        // actualizar updated_at del ticket
        $pdo->prepare("UPDATE ticket SET updated_at = NOW() WHERE ticketID = ?")
            ->execute([$ticketID]);
        header("Location: ticket_detail.php?id=$ticketID");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>➕ Añadir Mensaje – Tiquet #<?= $ticketID ?> | Cliente</title>
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
    .content { margin-left:260px; padding:30px; display:flex; justify-content:center; }
    .form-container { background:white; padding:30px; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1); width:100%; max-width:600px; }
    h2 { text-align:center; color:#0d3b66; margin-bottom:20px; }
    .error { color:#e74c3c; text-align:center; margin-bottom:15px; }
    label { display:block; margin-bottom:8px; font-weight:bold; }
    textarea { width:100%; padding:10px; height:120px; border:1px solid #ccc; border-radius:6px; font-family:'Inter'; resize:vertical; }
    button { width:100%; padding:12px; background:#0d3b66; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer; }
    button:hover { background:#095c9d; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} header{flex-direction:column;align-items:flex-start;} }
  </style>
</head>
<body>

<header>
  <h1>➕ Añadir Mensaje – <?= htmlspecialchars($client['name'].' '.$client['surname']) ?></h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Panel</a>
  <a href="my_packages.php">📦 Mis Paquetes</a>
  <a href="my_tickets.php">🎫 Mis Tiquets</a>
  <a href="ticket_create.php">➕ Nuevo Tiquet</a>
</div>

<div class="content">
  <div class="form-container">
    <h2>Mensaje para Tiquet #<?= $ticketID ?></h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <label for="message_text">Tu mensaje</label>
      <textarea name="message_text" id="message_text" required><?= htmlspecialchars($_POST['message_text'] ?? '') ?></textarea>
      <button type="submit">Enviar Mensaje</button>
    </form>
  </div>
</div>

</body>
</html>
