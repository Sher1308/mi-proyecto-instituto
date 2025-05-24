<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Obtener nombre para mostrar en el header
$stmt = $pdo->prepare("SELECT name, surname FROM employee WHERE employee_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$error = "";
$success = "";

// Obtener roles disponibles (excluir cliente si existe con rolID = 0)
$rolesStmt = $pdo->query("SELECT rolID, role_name FROM rol WHERE rolID NOT IN (0, 1) ORDER BY role_name ASC");
$roles = $rolesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rolID = $_POST['rolID'];

    if ($name && $surname && $email && $password && $rolID) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO employee (name, surname, email, password_hash, rolID, status) VALUES (?, ?, ?, ?, ?, 'Active')");

        try {
            $stmt->execute([$name, $surname, $email, $hash, $rolID]);
            $success = "✅ Empleado añadido correctamente.";
        } catch (PDOException $e) {
            $error = "❌ Error al insertar: " . $e->getMessage();
        }
    } else {
        $error = "❗ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Empleado - Recursos Humanos | INCARGO365</title>
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

    .form-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
      max-width: 500px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 20px;
      background-color: #2c3e50;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #1a252f;
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
    <h1> Añadir Empleado - Recursos Humanos</h1>
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
  <div class="form-card">
    <form method="post">
      <label for="name">Nombre</label>
      <input type="text" name="name" required>

      <label for="surname">Apellido</label>
      <input type="text" name="surname" required>

      <label for="email">Correo electrónico</label>
      <input type="email" name="email" required>

      <label for="password">Contraseña</label>
      <input type="password" name="password" required>

      <label for="rolID">Rol</label>
      <select name="rolID" required>
        <option value="">-- Selecciona un rol --</option>
        <?php foreach ($roles as $role): ?>
          <option value="<?= $role['rolID'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Crear empleado</button>

      <?php if ($success): ?>
        <p class="message success"><?= $success ?></p>
      <?php elseif ($error): ?>
        <p class="message error"><?= $error ?></p>
      <?php endif; ?>
    </form>
  </div>
</div>

</body>
</html>
