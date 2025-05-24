<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

$success = "";
$error = "";

// Añadir nuevo rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_role'])) {
    $role_name = trim($_POST['role_name']);
    if ($role_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rol (role_name) VALUES (?)");
            $stmt->execute([$role_name]);
            $success = "✅ Rol añadido correctamente.";
        } catch (PDOException $e) {
            $error = "❌ Error al añadir: " . $e->getMessage();
        }
    } else {
        $error = "❗ El nombre del rol no puede estar vacío.";
    }
}

// Eliminar rol (excepto protegidos)
if (isset($_GET['delete'])) {
    $rolID = (int)$_GET['delete'];
    if ($rolID !== 0 && $rolID !== 1) {
        try {
            $stmt = $pdo->prepare("DELETE FROM rol WHERE rolID = ?");
            $stmt->execute([$rolID]);
            $success = "✅ Rol eliminado.";
        } catch (PDOException $e) {
            $error = "❌ No se puede eliminar: " . $e->getMessage();
        }
    } else {
        $error = "⚠️ Este rol está protegido y no puede eliminarse.";
    }
}

// Obtener roles
$roles = $pdo->query("SELECT * FROM rol ORDER BY role_name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Roles - Recursos Humanos | INCARGO365</title>
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

    form {
      margin-top: 30px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      max-width: 500px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
    }

    button {
      margin-top: 15px;
      background-color: #2c3e50;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .message {
      margin-top: 15px;
      font-weight: bold;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
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
    <h1>🛡️  Gestión de Roles - Recursos Humanos </h1>
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
  <h2>📋 Lista de roles existentes</h2>

  <?php if ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php elseif ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre del rol</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($roles as $role): ?>
          <?php if ($role['role_name'] === 'client') continue; ?>
        <tr>
          <td><?= $role['rolID'] ?></td>
          <td><?= htmlspecialchars($role['role_name']) ?></td>
          <td class="actions">
            <?php if ($role['rolID'] != 0 && $role['rolID'] != 1): ?>
              <a href="role_edit.php?id=<?= $role['rolID'] ?>">✏️ Editar</a>
              <a href="roles.php?delete=<?= $role['rolID'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este rol?')">🗑️ Eliminar</a>
            <?php else: ?>
              🔒 Protegido
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <form method="post">
    <h3>➕ Añadir nuevo rol</h3>
    <label for="role_name">Nombre del nuevo rol:</label>
    <input type="text" name="role_name" id="role_name" required>
    <button type="submit" name="new_role">Crear rol</button>
  </form>
</div>

</body>
</html>
