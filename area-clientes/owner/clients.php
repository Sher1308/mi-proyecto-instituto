<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT c.client_id, c.name, c.surname, c.email, c.phone, r.role_name, c.status, c.created_at
        FROM client c
        LEFT JOIN rol r ON c.rolID = r.rolID
        WHERE c.name LIKE :search OR c.surname LIKE :search OR r.role_name LIKE :search
        ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$clients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes - INCARGO365 Owner</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
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
      text-align: center;
      flex: 1;
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

    .container {
      margin-left: 260px;
      padding: 30px;
    }

    .tools {
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .tools form {
      display: flex;
      gap: 10px;
    }

    .tools input {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 280px;
    }

    .tools button {
      padding: 10px 16px;
      background-color: #0d6efd;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .tools button:hover {
      background-color: #0b5ed7;
    }

    .tools a {
      background: #28a745;
      padding: 10px 18px;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
    }

    .tools a:hover {
      background: #218838;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    th, td {
      padding: 14px 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background: #1f2937;
      color: white;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    .acciones a {
      margin-right: 8px;
      text-decoration: none;
      font-size: 18px;
    }

    .acciones a:hover {
      opacity: 0.8;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .container {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Panel de Administración - Clientes</h1>
  <a href="../logout.php" class="logout">Cerrar sesión</a>
</header>

<div class="sidebar">
  <a href="dashboard.php">🏠 Inicio</a>
  <a href="employees.php">👥 Empleados</a>
  <a href="clients.php">🧑‍💼 Clientes</a>
  <a href="roles.php">🛡️ Roles</a>
  <a href="fleet.php">🚚 Flota</a>
  <a href="warehouses.php">🏬 Almacenes</a>
  <a href="packages.php">📦 Paquetes</a>
  <a href="tickets.php">🎫 Tiquets</a>
  <a href="login_history.php">📜 Historial de Login</a>
</div>

<div class="container">
  <div class="tools">
    <form method="get" action="">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Buscar cliente...">
      <button type="submit">Buscar</button>
    </form>
    <a href="client_add.php">➕ Nuevo cliente</a>
  </div>

  <h2>Lista de Clientes</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Teléfono</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Creado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($clients as $client): ?>
        <tr>
          <td><?= $client['client_id'] ?></td>
          <td><?= htmlspecialchars($client['name']) ?></td>
          <td><?= htmlspecialchars($client['surname']) ?></td>
          <td><?= htmlspecialchars($client['email']) ?></td>
          <td><?= htmlspecialchars($client['phone']) ?></td>
          <td><?= htmlspecialchars($client['role_name']) ?></td>
          <td><?= $client['status'] ?></td>
          <td><?= $client['created_at'] ?></td>
          <td class="acciones">
            <a href="client_edit.php?id=<?= $client['client_id'] ?>">✏️</a>
            <a href="client_delete.php?id=<?= $client['client_id'] ?>" onclick="return confirm('¿Eliminar cliente permanentemente?');">🗑️</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
