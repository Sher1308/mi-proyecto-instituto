<?php
session_start();
if (!isset($_SESSION['username']) || !in_array($_SESSION['role_id'], [1, 3])) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Verificar si hay ID válido
if (!isset($_GET['id'])) {
    header("Location: employees.php");
    exit;
}

$employee_id = $_GET['id'];

// Obtener datos del empleado
$stmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header("Location: employees.php");
    exit;
}
// Si el rol es People y está intentando editar al Owner, redirige
if ($_SESSION['role_id'] == 3 && $employee['rolID'] == 1) {
    header("Location: employees.php");
    exit;
}


// Obtener roles para desplegable
$roles = $pdo->query("SELECT rolID, role_name FROM rol")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $rolID = $_POST['rolID'];
    $status = $_POST['status'];

    $updateStmt = $pdo->prepare("UPDATE employee SET name = ?, surname = ?, email = ?, phone = ?, rolID = ?, status = ? WHERE employee_id = ?");
    $updateStmt->execute([$name, $surname, $email, $phone, $rolID, $status, $employee_id]);

    header("Location: employees.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado - INCARGO365</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f0f0; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 20px; }
        .container { padding: 30px; margin-left: 240px; }
        .form-box { background: white; padding: 25px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 12px 20px; background: #2c3e50; color: white; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #1a242f; }
    </style>
</head>
<body>
<div class="header">
    <h1>✏️ Editar Empleado</h1>
</div>

<div class="container">
    <div class="form-box">
        <form method="POST">
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($employee['name']) ?>" required>

            <label for="surname">Apellido:</label>
            <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($employee['surname']) ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($employee['email']) ?>" required>

            <label for="phone">Teléfono:</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($employee['phone']) ?>">

            <label for="rolID">Rol:</label>
            <select name="rolID" id="rolID" required>
                <?php foreach ($roles as $role): ?>
                    <?php if ($_SESSION['role_id'] == 3 && $role['rolID'] == 1) continue; ?>
                    <option value="<?= $role['rolID'] ?>" <?= $role['rolID'] == $employee['rolID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role['role_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="status">Estado:</label>
            <select name="status" id="status">
                <option value="Active" <?= $employee['status'] == 'Active' ? 'selected' : '' ?>>Activo</option>
                <option value="Inactive" <?= $employee['status'] == 'Inactive' ? 'selected' : '' ?>>Inactivo</option>
            </select>

            <button type="submit">💾 Guardar Cambios</button>
        </form>
    </div>
</div>
</body>
</html>
