<?php
// area-clientes/customer_service/ticket_edit.php
session_start();
require_once '../db.php';

// 1) Sólo Atención al Cliente (role_id = 6) y roles con permiso de actualizar tickets
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 6) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];

// Obtener nombre para el header
$stmtUser = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Verificar permiso can_update_ticket
$perm = $pdo->prepare("SELECT can_update_ticket FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
if (!$perm->fetchColumn()) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para editar tiquets.");
}

// 2) Obtener ID y datos del ticket
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "ID de tiquet inválido"; exit;
}
$stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticketID = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ticket) {
    echo "Tiquet no encontrado"; exit;
}

// 3) Datos para selects
$employees = $pdo->query("SELECT employee_id, name, surname FROM employee ORDER BY name")->fetchAll();
$packages  = $pdo->query("SELECT packageID FROM package ORDER BY packageID")->fetchAll();

// 4) Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria   = $_POST['categoria'];
    $packageID   = ($_POST['packageID'] !== '') ? $_POST['packageID'] : null;
    $status      = $_POST['status'];
    $employee_id = ($_POST['employee_id'] !== '') ? $_POST['employee_id'] : null;
    $message     = trim($_POST['message']);

    $update = $pdo->prepare("
        UPDATE ticket SET
          categoria   = ?,
          packageID   = ?,
          status      = ?,
          employee_id = ?,
          message     = ?,
          updated_at  = NOW()
        WHERE ticketID = ?
    ");
    $update->execute([$categoria, $packageID, $status, $employee_id, $message, $id]);

    header("Location: tickets.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✏️ Editar Tiquet #<?= $id ?> – Atención al Cliente</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f4f6fb; margin:0; color:#2c3e50; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; font-size:14px; }
    .logout:hover { background:#c0392b; }
    .sidebar { width:240px; height:100vh; background:#1f2937; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; font-size:15px; transition:background .2s; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
    .form-container { background:white; padding:30px; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1); max-width:600px; margin:auto; }
    h2 { text-align:center; color:#0d3b66; margin-bottom:20px; }
    label { display:block; margin-bottom:8px; font-weight:bold; }
    select, textarea, input { width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:6px; font-family:'Inter'; }
    button { width:100%; padding:12px; background:#0d3b66; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer; }
    button:hover { background:#095c9d; }
    .hidden { display:none; }
    .back-link { display:block; text-align:center; margin-top:15px; color:#0d3b66; text-decoration:none; }
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
  <div class="form-container">
    <h2>✏️ Editar Tiquet #<?= $id ?></h2>
    <form method="post" id="editForm">
      <!-- Categoría -->
      <label for="categoria">Tipo de solicitud</label>
      <select name="categoria" id="categoria" required>
        <?php foreach (['Incidencias','Reclamaciones','Consulta','Info'] as $cat): ?>
          <option value="<?= $cat ?>" <?= $ticket['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Paquete afectado -->
      <div id="paquete-container" class="<?= in_array($ticket['categoria'], ['Incidencias','Reclamaciones']) ? '' : 'hidden' ?>">
        <label for="packageID">Paquete afectado</label>
        <select name="packageID" id="packageID">
          <option value="">-- Sin asignar --</option>
          <?php foreach ($packages as $p): ?>
            <option value="<?= $p['packageID'] ?>" <?= $ticket['packageID'] == $p['packageID'] ? 'selected' : '' ?>><?= $p['packageID'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Estado -->
      <label for="status">Estado</label>
      <select name="status" id="status" required>
        <?php foreach (['Pending','In Progress','Resolved'] as $st): ?>
          <option value="<?= $st ?>" <?= $ticket['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Empleado asignado -->
      <label for="employee_id">Empleado asignado</label>
      <select name="employee_id" id="employee_id">
        <option value="">-- Sin asignar --</option>
        <?php foreach ($employees as $e): ?>
          <option value="<?= $e['employee_id'] ?>" <?= $ticket['employee_id'] == $e['employee_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($e['name'].' '.$e['surname']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Mensaje -->
      <label for="message">Mensaje</label>
      <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($ticket['message'] ?? '') ?></textarea>

      <button type="submit">Actualizar Tiquet</button>
    </form>
    <a href="tickets.php" class="back-link">← Volver a Tiquets</a>
  </div>
</div>

<script>
  document.getElementById('categoria').addEventListener('change', function() {
    const cont = document.getElementById('paquete-container');
    if (['Incidencias','Reclamaciones'].includes(this.value)) {
      cont.classList.remove('hidden');
    } else {
      cont.classList.add('hidden');
      document.getElementById('packageID').value = '';
    }
  });
  document.getElementById('categoria').dispatchEvent(new Event('change'));
</script>

</body>
</html>

