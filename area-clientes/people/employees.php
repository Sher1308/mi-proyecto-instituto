<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Obtener todos los empleados (excepto OWNER)
$stmt = $pdo->query("SELECT e.employee_id, e.name, e.surname, e.email, e.phone, r.role_name, e.status, e.rolID 
                     FROM employee e 
                     LEFT JOIN rol r ON e.rolID = r.rolID 
                     WHERE e.rolID != 1
                     ORDER BY e.name ASC");
$employees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Empleados - Recursos Humanos | INCARGO365</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
      margin: 0;
      color: #2c3e50;
    }

    header {
      background: #1f2937;
      color: white;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      font-size: 20px;
      font-weight: 600;
      margin: 0;
    }

    .logout {
      background-color: #e74c3c;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.3s;
    }

    .logout:hover {
      background-color: #c0392b;
    }

    .sidebar {
      width: 240px;
      height: 100vh;
      background: #1f2937;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 80px;
    }

    .sidebar a {
      display: block;
      color: #eee;
      padding: 15px 25px;
      text-decoration: none;
      transition: background 0.2s;
      font-size: 15px;
    }

    .sidebar a:hover {
      background-color: #374151;
    }

    .content {
      margin-left: 260px;
      padding: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    th, td {
      padding: 14px;
      border: 1px solid #e0e0e0;
      text-align: left;
    }

    th {
      background-color: #1f2937;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .actions a {
      margin-right: 10px;
      text-decoration: none;
      color: #0d3b66;
      font-weight: 500;
    }

    .actions a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <div style="flex: 1; text-align: center;">
    <h1>👥 Gestión de Empleados - Recursos Humanos</h1>
  </div>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="employees.php">👥 Ver Empleados</a>
  <a href="employee_add.php">➕ Añadir Empleado</a>
  <a href="roles.php">🛡️  Gestionar roles</a>
  <a href="tickets.php">🎫 Tickets</a>
</div>

<div class="content">
  <h2>📋 Lista de empleados</h2>

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $emp): ?>
        <tr>
          <td><?= htmlspecialchars($emp['name']) ?></td>
          <td><?= htmlspecialchars($emp['surname']) ?></td>
          <td><?= htmlspecialchars($emp['email']) ?></td>
          <td><?= htmlspecialchars($emp['phone']) ?></td>
          <td><?= htmlspecialchars($emp['role_name']) ?></td>
          <td><?= $emp['status'] === 'Active' ? '✅' : '❌' ?></td>
          <td class="actions">
            <a href="employee_edit.php?id=<?= $emp['employee_id'] ?>">✏️ Editar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
