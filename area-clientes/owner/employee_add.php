<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rolID = $_POST['rolID'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO employee (name, surname, email, phone, password_hash, rolID, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $email, $phone, $password_hash, $rolID, $status]);

    header("Location: employees.php");
    exit;
}

$roles = $pdo->query("SELECT rolID, role_name FROM rol WHERE role_name != 'client'")->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Empleado - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0d3b66;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0d3b66;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #095c9d;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0d3b66;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>➕ Añadir nuevo empleado</h2>
    <form method="post">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" required>

        <label for="surname">Apellido</label>
        <input type="text" name="surname" id="surname" required>

        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" required>

        <label for="phone">Teléfono</label>
        <input type="text" name="phone" id="phone">

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>

        <label for="rolID">Rol</label>
        <select name="rolID" id="rolID" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['rolID'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="Active">Activo</option>
            <option value="Inactive">Inactivo</option>
        </select>

        <button type="submit">Guardar empleado</button>
    </form>
    <a href="employees.php" class="back-link">← Volver al listado de empleados</a>
</div>

</body>
</html>
