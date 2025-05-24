<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

// Para el selector de clientes
$clients  = $pdo->query("SELECT client_id, name, surname FROM client ORDER BY name")->fetchAll();
// Para el selector de paquetes
$packages = $pdo->query("SELECT packageID FROM package ORDER BY packageID")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $categoria = $_POST['categoria'];
    $packageID = ($_POST['packageID'] !== '') ? $_POST['packageID'] : null;
    $status    = $_POST['status'];
    $message   = trim($_POST['message']);

    $stmt = $pdo->prepare("
        INSERT INTO ticket 
            (client_id, categoria, packageID, status, message)
        VALUES 
            (?,         ?,         ?,         ?,      ?)
    ");
    $stmt->execute([$client_id, $categoria, $packageID, $status, $message]);

    header("Location: tickets.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ticket – INCARGO365</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f2f2f2; padding: 40px; }
        .form-container {
            max-width: 600px; margin: auto; background: white; padding: 30px;
            border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #0d3b66; margin-bottom: 30px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        select, input, textarea {
            width: 100%; padding: 10px; margin-bottom: 20px;
            border-radius: 6px; border: 1px solid #ccc;
            font-family: 'Segoe UI', sans-serif;
        }
        button {
            width: 100%; padding: 12px; background: #0d3b66; color: #fff;
            border: none; border-radius: 6px; font-size: 16px;
        }
        button:hover { background: #095c9d; cursor: pointer; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #0d3b66; text-decoration: none; }
        .hidden { display: none; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>➕ Nuevo Ticket</h2>
    <form method="post" id="ticketForm">
        <!-- Cliente -->
        <label for="client_id">Cliente</label>
        <select name="client_id" id="client_id" required>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['client_id'] ?>">
                    <?= htmlspecialchars($c['name'].' '.$c['surname']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Tipo de solicitud -->
        <label for="categoria">Tipo de solicitud</label>
        <select name="categoria" id="categoria" required>
            <option value="Incidencias">Incidencias</option>
            <option value="Reclamaciones">Reclamaciones</option>
            <option value="Consulta">Consulta</option>
            <option value="Info">Info</option>
        </select>

        <!-- Paquete afectado (solo Incidencias/Reclamaciones) -->
        <div id="paquete-container" class="hidden">
            <label for="packageID">Paquete afectado</label>
            <select name="packageID" id="packageID">
                <option value="">-- Sin asignar --</option>
                <?php foreach ($packages as $p): ?>
                    <option value="<?= $p['packageID'] ?>"><?= $p['packageID'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Estado -->
        <label for="status">Estado</label>
        <select name="status" id="status" required>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Resolved">Resolved</option>
        </select>

        <!-- Descripción / Mensaje inicial -->
        <label for="message">Descripción</label>
        <textarea name="message" id="message" rows="5"
                  placeholder="Añade un mensaje inicial de ticket..." required>
        </textarea>

        <button type="submit">Guardar Ticket</button>
    </form>
    <a href="tickets.php" class="back-link">← Volver</a>
</div>

<script>
// Mostrar/ocultar campo de paquete según categoría
document.getElementById('categoria').addEventListener('change', function() {
    const val = this.value;
    const cont = document.getElementById('paquete-container');
    if (val === 'Incidencias' || val === 'Reclamaciones') {
        cont.classList.remove('hidden');
    } else {
        cont.classList.add('hidden');
        document.getElementById('packageID').value = '';
    }
});
// Al cargar, disparar el evento para estado inicial
document.getElementById('categoria').dispatchEvent(new Event('change'));
</script>
</body>
</html>
