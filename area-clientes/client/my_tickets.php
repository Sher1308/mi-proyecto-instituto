<?php
// area-clientes/client/my_tickets.php
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

// Obtener nombre del cliente para el header
$stmtClient = $pdo->prepare("SELECT name, surname FROM client WHERE client_id = ?");
$stmtClient->execute([$client_id]);
$client = $stmtClient->fetch();

// Obtener lista de tiquets del cliente
$stmt = $pdo->prepare("
    SELECT t.ticketID,
           t.categoria,
           t.status,
           t.created_at,
           t.updated_at,
           e.name AS emp_name,
           e.surname AS emp_surname
    FROM ticket t
    LEFT JOIN employee e ON t.employee_id = e.employee_id
    WHERE t.client_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$client_id]);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎫 Mis Tiquets | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f5f6fa; margin: 0; color: #2c3e50; }
    header { background: #1f2937; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    header h1 { font-size: 20px; font-weight: 600; margin: 0; text-align: center; flex: 1; }
    .logout { background-color: #e74c3c; color: white; padding: 10px 18px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; transition: background 0.3s; }
    .logout:hover { background-color: #c0392b; }
    .sidebar { width: 240px; height: 100vh; background: #1f2937; color: white; position: fixed; top: 0; left: 0; padding-top: 80px; }
    .sidebar a { display: block; color: #eee; padding: 15px 25px; text-decoration: none; transition: background 0.2s; font-size: 15px; }
    .sidebar a:hover { background-color: #374151; }
    .content { margin-left: 260px; padding: 30px; }
    .card { background: white; border-radius: 12px; padding: 25px 30px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
    .card h2 { font-size: 22px; margin-top: 0; }
    .btn-crear { display: inline-block; background-color: #3498db; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-size: 15px; margin-bottom: 20px; transition: background 0.3s; }
    .btn-crear:hover { background-color: #2980b9; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    th, td { padding: 14px 16px; border-bottom: 1px solid #e0e0e0; text-align: left; font-size: 14px; }
    th { background-color: #1f2937; color: white; }
    tr:hover { background-color: #f1f5f9; }
    .actions a { margin-right: 8px; color: #0d3b66; text-decoration: none; font-weight: 500; }
    .actions a:hover { text-decoration: underline; }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .content { margin-left: 0; padding: 20px; }
      header { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

<header>
  <h1>🎫 Mis Tiquets – <?= htmlspecialchars($client['name'] . ' ' . $client['surname']) ?></h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Panel</a>
  <a href="my_packages.php">📦 Mis Paquetes</a>
  <a href="my_tickets.php">🎫 Mis Tiquets</a>
  <a href="ticket_create.php">➕ Nuevo Tiquet</a>
</div>

<div class="content">
  <div class="card">
    <h2>📋 Lista de Tiquets</h2>
    <a href="ticket_create.php" class="btn-crear">➕ Crear Tiquet</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Categoría</th>
          <th>Estado</th>
          <th>Empleado</th>
          <th>Creado</th>
          <th>Actualizado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tickets)): ?>
          <tr><td colspan="7" style="text-align:center;">No tienes tiquets aún.</td></tr>
        <?php else: foreach ($tickets as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['ticketID']) ?></td>
            <td><?= htmlspecialchars($t['categoria']) ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td><?= $t['emp_name'] ? htmlspecialchars($t['emp_name'] . ' ' . $t['emp_surname']) : 'Sin asignar' ?></td>
            <td><?= htmlspecialchars($t['created_at']) ?></td>
            <td><?= htmlspecialchars($t['updated_at']) ?></td>
            <td class="actions">
              <a href="ticket_detail.php?id=<?= $t['ticketID'] ?>">Ver detalle</a>
              <a href="message_add.php?ticketID=<?= $t['ticketID'] ?>">Añadir mensaje</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
