<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = trim($_POST['role_name']);

    if ($role_name === '' || strtolower($role_name) === 'owner') {
        $error = "Nombre de rol inválido o reservado.";
    } else {
        // Verificar si ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rol WHERE role_name = ?");
        $stmt->execute([$role_name]);
        if ($stmt->fetchColumn() > 0) {
            $error = "El rol ya existe.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO rol (role_name) VALUES (?)");
            $stmt->execute([$role_name]);
            $success = "Rol añadido correctamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Rol - INCARGO365</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0d3b66;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
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

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>➕ Añadir nuevo rol</h2>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="role_name">Nombre del rol</label>
        <input type="text" name="role_name" id="role_name" required>
        <button type="submit">Guardar rol</button>
    </form>

    <a class="back-link" href="roles.php">← Volver al listado de roles</a>
</div>

</body>
</html>
