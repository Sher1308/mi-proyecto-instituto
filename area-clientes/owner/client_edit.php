<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Verificar que el ID del cliente existe
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de cliente no válido.";
    exit;
}
$client_id = $_GET['id'];

// Obtener datos actuales del cliente
$stmt = $pdo->prepare("SELECT * FROM client WHERE client_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    echo "Cliente no encontrado.";
    exit;
}

// Actualizar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE client SET name = ?, surname = ?, email = ?, phone = ?, status = ? WHERE client_id = ?");
    $stmt->execute([$name, $surname, $email, $phone, $status, $client_id]);

    header("Location: clients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - INCARGO365</title>
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
    <h2>✏️ Editar cliente</h2>
    <form method="post">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($client['name']) ?>" required>

        <label for="surname">Apellido</label>
        <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($client['surname']) ?>" required>

        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($client['email']) ?>" required>

        <label for="phone">Teléfono</label>
        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($client['phone']) ?>">

        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="Active" <?= $client['status'] === 'Active' ? 'selected' : '' ?>>Activo</option>
            <option value="Inactive" <?= $client['status'] === 'Inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit">Actualizar cliente</button>
    </form>
    <a href="clients.php" class="back-link">← Volver al listado de clientes</a>
</div>

</body>
</html>
