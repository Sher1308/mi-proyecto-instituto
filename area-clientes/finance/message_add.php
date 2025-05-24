<?php
session_start();
require_once '../db.php';

// 1) Verifica sesión y permiso de añadir mensajes
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];
$perm = $pdo->prepare("SELECT can_add_message FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
if (!$perm->fetchColumn()) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para añadir mensajes.");
}

// 2) Obtén el ticketID de la query
$ticketID = isset($_GET['ticketID']) ? (int)$_GET['ticketID'] : 0;
if (!$ticketID) {
    echo "ID de ticket inválido";
    exit;
}

// 3) Comprueba que el ticket exista
$stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticketID = ?");
$stmt->execute([$ticketID]);
$ticket = $stmt->fetch();
if (!$ticket) {
    echo "Ticket no encontrado";
    exit;
}

// 4) Identifica al autor del mensaje
// Asumimos que para Finanzas el usuario es un empleado
$authorField = 'employee_id';
$authorId    = $_SESSION['user_id']; // Debes guardar employee_id en la sesión al hacer login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message_text']);
    if ($text === '') {
        $error = "El mensaje no puede estar vacío.";
    } else {
        // 5) Inserta el nuevo mensaje
        $ins = $pdo->prepare("
            INSERT INTO message (ticketID, {$authorField}, message_text)
            VALUES (?, ?, ?)
        ");
        $ins->execute([$ticketID, $authorId, $text]);

        // 6) Actualiza updated_at en ticket
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
  <title>Añadir Mensaje – Ticket #<?= $ticketID ?> | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Segoe UI',sans-serif; background:#f5f6fa; margin:0; padding:40px; color:#2c3e50; }
    .form-container {
      max-width:600px; margin:auto; background:white; padding:30px;
      border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);
    }
    h2 { margin-top:0; color:#0d3b66; text-align:center; }
    label { display:block; margin:15px 0 8px; font-weight:bold; }
    textarea { width:100%; height:150px; padding:10px; border:1px solid #ccc; border-radius:6px; font-family:'Segoe UI'; }
    .error { color:#e74c3c; margin-bottom:15px; }
    button {
      margin-top:20px; width:100%; padding:12px; background:#0d3b66;
      color:white; border:none; border-radius:6px; font-size:16px;
    }
    button:hover { background:#095c9d; cursor:pointer; }
    .back-link { display:block; text-align:center; margin-top:20px; color:#0d3b66; text-decoration:none; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>➕ Añadir Mensaje – Ticket #<?= $ticketID ?></h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <label for="message_text">Mensaje</label>
      <textarea name="message_text" id="message_text" required><?= isset($_POST['message_text']) ? htmlspecialchars($_POST['message_text']) : '' ?></textarea>
      <button type="submit">Enviar Mensaje</button>
    </form>
    <a href="tickets.php" class="back-link">← Volver a Tickets</a>
  </div>
</body>
</html>
