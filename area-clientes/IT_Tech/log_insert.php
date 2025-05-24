<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role_id'] != 4 && $_SESSION['role_id'] != 5)) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $log_type = trim($_POST['log_type']);
    $message = trim($_POST['message']);

    if ($log_type && $message) {
        $stmt = $pdo->prepare("INSERT INTO system_logs (log_type, message) VALUES (?, ?)");
        $stmt->execute([$log_type, $message]);

        $success = "✅ Log insertado correctamente.";
    } else {
        $error = "❌ Debes completar todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Log - INCARGO365</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 20px; }
        .sidebar {
            width: 220px; height: 100vh; background: #34495e;
            position: fixed; top: 0; left: 0; padding-top: 60px;
        }
        .sidebar a {
            display: block; color: white; padding: 15px; text-decoration: none;
        }
        .sidebar a:hover { background-color: #3d566e; }
        .container { margin-left: 240px; padding: 30px; }
        form {
            background: white; padding: 25px; border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 500px;
        }
        label { font-weight: bold; }
        input, textarea {
            width: 100%; padding: 10px; margin-top: 5px;
            margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;
        }
        button {
            padding: 12px; background-color: #2c3e50; color: white;
            border: none; border-radius: 6px; cursor: pointer;
        }
        .msg { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>📝 Insertar Nuevo Log</h1>
</div>

<div class="sidebar">
    <a href="dashboard.php">🏠 Inicio</a>
    <a href="logs.php">📄 Ver Logs</a>
    <a href="log_insert.php">➕ Insertar Log</a>
    <a href="profile.php">👤 Mi Perfil</a>
    <a href="../logout.php">🔓 Cerrar sesión</a>
</div>

<div class="container">
    <form method="post">
        <label for="log_type">Tipo de Log:</label>
        <input type="text" name="log_type" id="log_type" required>

        <label for="message">Mensaje:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <button type="submit">Guardar Log</button>

        <?php if (isset($success)): ?>
            <div class="msg" style="color: green;"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="msg" style="color: red;"><?= $error ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
