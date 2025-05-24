<?php
session_start();
require_once '../db.php';

// 1) Sólo Recursos Humanos (role_id = 3) y roles con permiso de crear tickets
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php");
    exit;
}
$rolID = $_SESSION['role_id'];
$perm = $pdo->prepare("SELECT can_create_ticket FROM rol WHERE rolID = ?");
$perm->execute([$rolID]);
if (!$perm->fetchColumn()) {
    header("HTTP/1.1 403 Forbidden");
    exit("No tienes permiso para crear tickets.");
}

// 2) Datos para los select
$clients  = $pdo->query("SELECT client_id, name, surname FROM client ORDER BY name")->fetchAll();
$packages = $pdo->query("SELECT packageID FROM package ORDER BY packageID")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $categoria = $_POST['categoria'];
    $packageID = ($_POST['packageID'] !== '') ? $_POST['packageID'] : null;
    $status    = $_POST['status'];
    $message   = trim($_POST['message']);

    $stmt = $pdo->prepare("
        INSERT INTO ticket
            (client_id, categoria, packageID, status, message)
        VALUES
            (?,         ?,         ?,         ?,      ?)
    ");
    $stmt->execute([$client_id, $categoria, $packageID, $status, $message]);

    header("Location: tickets.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>➕ Nuevo Ticket – Recursos Humanos | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Segoe UI',sans-serif; background:#f5f6fa; margin:0; color:#2c3e50; }
    header { background:#1f2937; color:#fff; padding:20px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { margin:0; font-size:20px; }
    .logout { background:#e74c3c; color:#fff; padding:8px 16px; border:none; border-radius:6px; text-decoration:none; }
    .sidebar { width:240px; height:100vh; background:#1f2937; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
    .form-container { background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); max-width:600px; margin:auto; }
    h2 { text-align:center; color:#0d3b66; margin-bottom:20px; }
    label { display:block; margin-bottom:8px; font-weight:bold; }
    select, textarea, input { width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:6px; font-family:'Segoe UI'; }
    button { width:100%; padding:12px; background:#0d3b66; color:#fff; border:none; border-radius:6px; font-size:16px; }
    button:hover { background:#095c9d; cursor:pointer; }
    .hidden { display:none; }
  </style>
</head>
<body>

<header>
  <h1>➕ Nuevo Ticket – Recursos Humanos</h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="employees.php">👥 Empleados</a>
  <a href="tickets.php">🎫 Tickets</a>
</div>

<div class="content">
  <div class="form-container">
    <h2>Crear Ticket</h2>
    <form method="post" id="ticketForm">
      <!-- Cliente -->
      <label for="client_id">Cliente</label>
      <select name="client_id" id="client_id" required>
        <?php foreach ($clients as $c): ?>
          <option value="<?= $c['client_id'] ?>">
            <?= htmlspecialchars($c['name'].' '.$c['surname']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Categoría -->
      <label for="categoria">Tipo de solicitud</label>
      <select name="categoria" id="categoria" required>
        <option value="Incidencias">Incidencias</option>
        <option value="Reclamaciones">Reclamaciones</option>
        <option value="Consulta">Consulta</option>
        <option value="Info">Info</option>
      </select>

      <!-- Paquete afectado (solo Incidencias/Reclamaciones) -->
      <div id="paquete-container" class="hidden">
        <label for="packageID">Paquete afectado</label>
        <select name="packageID" id="packageID">
          <option value="">-- Sin asignar --</option>
          <?php foreach ($packages as $p): ?>
            <option value="<?= $p['packageID'] ?>"><?= $p['packageID'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Estado -->
      <label for="status">Estado</label>
      <select name="status" id="status" required>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Resolved">Resolved</option>
      </select>

      <!-- Descripción -->
      <label for="message">Descripción</label>
      <textarea name="message" id="message" rows="5" placeholder="Texto inicial..." required></textarea>

      <button type="submit">Guardar Ticket</button>
    </form>
  </div>
</div>

<script>
// Mostrar/ocultar el selector de paquete según categoría
document.getElementById('categoria').addEventListener('change', function() {
  const cont = document.getElementById('paquete-container');
  if (['Incidencias','Reclamaciones'].includes(this.value)) {
    cont.classList.remove('hidden');
  } else {
    cont.classList.add('hidden');
    document.getElementById('packageID').value = '';
  }
});
// Dispara al cargar
document.getElementById('categoria').dispatchEvent(new Event('change'));
</script>

</body>
</html>
