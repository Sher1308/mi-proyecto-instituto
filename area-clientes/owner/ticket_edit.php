<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) { echo "ID inválido"; exit; }

// 1) Cargar datos del ticket
$stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticketID = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

// 2) Listas para selects
$employees = $pdo->query("SELECT employee_id, name, surname FROM employee ORDER BY name")->fetchAll();
$packages  = $pdo->query("SELECT packageID FROM package ORDER BY packageID")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria   = $_POST['categoria'];
    $packageID   = ($_POST['packageID'] !== '') ? $_POST['packageID'] : null;
    $status      = $_POST['status'];
    $employee_id = $_POST['employee_id'] ?: null;
    $message     = trim($_POST['message']);

    $update = $pdo->prepare("
        UPDATE ticket SET
            categoria    = ?,
            packageID    = ?,
            status       = ?,
            employee_id  = ?,
            message      = ?,
            updated_at   = NOW()
        WHERE ticketID = ?
    ");
    $update->execute([
        $categoria,
        $packageID,
        $status,
        $employee_id,
        $message,
        $id
    ]);

    header("Location: tickets.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ticket #<?= $id ?> – INCARGO365</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f2f2f2; padding: 40px; }
        .form-container {
            max-width: 600px; margin: auto; background: white; padding: 30px;
            border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #0d3b66; margin-bottom: 30px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        select, textarea {
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
    <h2>✏️ Editar Ticket #<?= $id ?></h2>
    <form method="post" id="editForm">
        <!-- Categoría -->
        <label for="categoria">Tipo de solicitud</label>
        <select name="categoria" id="categoria" required>
            <?php foreach (['Incidencias','Reclamaciones','Consulta','Info'] as $cat): ?>
                <option value="<?= $cat ?>"
                    <?= $ticket['categoria'] === $cat ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Paquete afectado -->
        <div id="paquete-container" class="<?= in_array($ticket['categoria'], ['Incidencias','Reclamaciones']) ? '' : 'hidden' ?>">
            <label for="packageID">Paquete afectado</label>
            <select name="packageID" id="packageID">
                <option value="">-- Sin asignar --</option>
                <?php foreach ($packages as $p): ?>
                    <option value="<?= $p['packageID'] ?>"
                        <?= $ticket['packageID'] == $p['packageID'] ? 'selected' : '' ?>>
                        <?= $p['packageID'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Estado -->
        <label for="status">Estado</label>
        <select name="status" id="status" required>
            <?php foreach (['Pending','In Progress','Resolved'] as $st): ?>
                <option value="<?= $st ?>"
                    <?= $ticket['status'] === $st ? 'selected' : '' ?>>
                    <?= $st ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Empleado asignado -->
        <label for="employee_id">Empleado asignado</label>
        <select name="employee_id" id="employee_id">
            <option value="">-- Sin asignar --</option>
            <?php foreach ($employees as $e): ?>
                <option value="<?= $e['employee_id'] ?>"
                    <?= $ticket['employee_id'] == $e['employee_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['name'].' '.$e['surname']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Mensaje / Descripción -->
        <label for="message">Mensaje del cliente</label>
        <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($ticket['message'] ?? '') ?></textarea>

        <button type="submit">Actualizar Ticket</button>
    </form>
    <a href="tickets.php" class="back-link">← Volver</a>
</div>

<script>
// Mostrar/ocultar paquete según categoría
document.getElementById('categoria').addEventListener('change', function() {
    const cont = document.getElementById('paquete-container');
    if (['Incidencias','Reclamaciones'].includes(this.value)) {
        cont.classList.remove('hidden');
    } else {
        cont.classList.add('hidden');
        document.getElementById('packageID').value = '';
    }
});
// Disparar al cargar
document.getElementById('categoria').dispatchEvent(new Event('change'));
</script>
</body>
</html>
