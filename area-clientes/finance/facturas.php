<?php
session_start();
require_once '../db.php';

// 1) Sólo Finanzas (role_id = 2) pueden acceder
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit;
}

// 2) Procesar nueva factura
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['client_id'];
    $packageID = (int)$_POST['packageID'];
    $amount    = (float)$_POST['amount'];

    $stmt = $pdo->prepare("
        INSERT INTO invoice (client_id, packageID, amount)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$client_id, $packageID, $amount]);

    header("Location: facturas.php");
    exit;
}

// 3) Datos para selects
$clients  = $pdo->query("SELECT client_id, name, surname FROM client ORDER BY name")->fetchAll();
$packages = $pdo->query("SELECT packageID FROM package ORDER BY packageID")->fetchAll();

// 4) Listado de facturas
$invoices = $pdo->query("
    SELECT i.invoice_id, 
           CONCAT(c.name,' ',c.surname) AS cliente,
           i.packageID, 
           i.amount,
           DATE_FORMAT(i.created_at, '%d/%m/%Y %H:%i') AS fecha
    FROM invoice i
    JOIN client c ON c.client_id = i.client_id
    ORDER BY i.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Facturas – Finanzas | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Segoe UI',sans-serif; background:#f5f6fa; margin:0; color:#2c3e50; }
    header { background:#1f2937; color:white; padding:20px; display:flex; justify-content:space-between; }
    header h1 { margin:0; font-size:20px; }
    .logout { background:#e74c3c; color:white; padding:10px 18px; border:none; border-radius:6px; text-decoration:none; }
    .sidebar { width:240px; height:100vh; background:#1f2937; position:fixed; top:0; left:0; padding-top:80px; }
    .sidebar a { display:block; color:#eee; padding:15px 25px; text-decoration:none; }
    .sidebar a:hover { background:#374151; }
    .content { margin-left:260px; padding:30px; }
    .card { background:white; border-radius:12px; padding:25px; box-shadow:0 3px 10px rgba(0,0,0,0.04); margin-bottom:30px; }
    .card h2 { margin-top:0; color:#0d3b66; }
    form label { display:block; margin:12px 0 6px; font-weight:bold; }
    form select, form input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:16px; }
    form button { background:#0d3b66; color:white; padding:12px; border:none; border-radius:6px; width:100%; font-size:16px; }
    form button:hover { background:#095c9d; cursor:pointer; }
    table { width:100%; border-collapse:collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding:12px 15px; border-bottom:1px solid #ddd; text-align:left; }
    th { background:#0d3b66; color:white; }
    tr:hover { background:#f1f1f1; }
  </style>
</head>
<body>

<header>
  <h1>🧾 Facturas – Finanzas</h1>
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
    <h2>Generar Nueva Factura</h2>
    <form method="post">
      <label for="client_id">Cliente</label>
      <select name="client_id" id="client_id" required>
        <option value="">-- Selecciona Cliente --</option>
        <?php foreach($clients as $c): ?>
          <option value="<?= $c['client_id'] ?>">
            <?= htmlspecialchars($c['name'].' '.$c['surname']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="packageID">Envío (Paquete)</label>
      <select name="packageID" id="packageID" required>
        <option value="">-- Selecciona Envío --</option>
        <?php foreach($packages as $p): ?>
          <option value="<?= $p['packageID'] ?>"><?= $p['packageID'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="amount">Importe (€)</label>
      <input type="number" name="amount" id="amount" step="0.01" min="0" required>

      <button type="submit">Generar Factura</button>
    </form>
  </div>

  <div class="card">
    <h2>Facturas Emitidas</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Envío</th>
          <th>Importe (€)</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($invoices)): ?>
          <tr><td colspan="5" style="text-align:center;">No hay facturas aún.</td></tr>
        <?php else: ?>
          <?php foreach($invoices as $inv): ?>
            <tr>
              <td><?= $inv['invoice_id'] ?></td>
              <td><?= htmlspecialchars($inv['cliente']) ?></td>
              <td><?= $inv['packageID'] ?></td>
              <td><?= number_format($inv['amount'],2) ?></td>
              <td><?= $inv['fecha'] ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
