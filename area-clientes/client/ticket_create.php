<?php
// area-clientes/client/ticket_create.php
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

// 1) Obtener nombre para el header
$stmtClient = $pdo->prepare("SELECT name, surname FROM client WHERE client_id = ?");
$stmtClient->execute([$client_id]);
$client = $stmtClient->fetch();

// 2) Obtener lista de paquetes del cliente
$pkStmt = $pdo->prepare("SELECT packageID FROM package WHERE client_id = ?");
$pkStmt->execute([$client_id]);
$packages = $pkStmt->fetchAll();

// 3) Manejo de POST
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = $_POST['categoria'];
    $packageID = $_POST['packageID'] !== '' ? $_POST['packageID'] : null;
    $message   = trim($_POST['message']);

    if ($message === "") {
        $error = "El mensaje no puede estar vacío.";
    } else {
        $ins = $pdo->prepare("
            INSERT INTO ticket (client_id, categoria, packageID, status, message, created_at, updated_at)
            VALUES (?, ?, ?, 'Pending', ?, NOW(), NOW())
        ");
        $ins->execute([$client_id, $categoria, $packageID, $message]);
        header("Location: my_tickets.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>➕ Crear Tiquet – Cliente | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Inter',sans-serif; background:#f5f6fa; margin:0; color:#2c3e50; }
    header { background:#1f2937; color:white; padding:20px 30px; display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; font-weight:600; margin:0; text-align:center; flex:1; }
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
    select, textarea { width:100%; padding:10px; margin-bottom:20px; border-radius:6px; border:1px solid #ccc; font-family:'Inter'; }
    textarea { height:120px; resize:vertical; }
    button { width:100%; padding:12px; background:#0d3b66; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer; }
    button:hover { background:#095c9d; }
    @media(max-width:768px){ .sidebar{display:none;} .content{margin-left:0;padding:20px;} header{flex-direction:column;align-items:flex-start;} }
  </style>
</head>
<body>

<header>
  <h1>➕ Crear Tiquet – <?= htmlspecialchars($client['name'].' '.$client['surname']) ?></h1>
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
    <h2>Crear nuevo tiquet</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <label for="categoria">Tipo de solicitud</label>
      <select name="categoria" id="categoria" required>
        <option value="Incidencias">Incidencias</option>
        <option value="Reclamaciones">Reclamaciones</option>
        <option value="Consulta">Consulta</option>
        <option value="Info">Info</option>
      </select>

      <label for="packageID">Paquete relacionado (opcional)</label>
      <select name="packageID" id="packageID">
        <option value="">-- Sin paquete --</option>
        <?php foreach ($packages as $p): ?>
          <option value="<?= $p['packageID'] ?>">Paquete #<?= $p['packageID'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="message">Descripción</label>
      <textarea name="message" id="message" placeholder="Describe tu incidencia o consulta..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

      <button type="submit">Enviar Tiquet</button>
    </form>
  </div>
</div>

</body>
</html>
