<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

$error = "";

function registrarLogin($pdo, $employee_id = null, $client_id = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';

    $stmt = $pdo->prepare("INSERT INTO login_history (employee_id, client_id, IP_address, user_agent, login_time) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$employee_id, $client_id, $ip, $user_agent]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Buscar en empleados
    $stmt = $pdo->prepare("
        SELECT e.*, r.role_name 
        FROM employee e 
        LEFT JOIN rol r ON e.rolID = r.rolID 
        WHERE e.email = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['status'] !== 'Active') {
            $error = "Cuenta deshabilitada. Contacta con administración.";
        } elseif (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['employee_id'];
            $_SESSION['username'] = $user['email'];
            $_SESSION['role_id'] = $user['rolID'];
            $_SESSION['role_name'] = $user['role_name'];
            $_SESSION['nombre'] = $user['name'];
            $_SESSION['apellido'] = $user['surname'];

            registrarLogin($pdo, $user['employee_id'], null);

            switch ($user['rolID']) {
                case 1: header("Location: /area-clientes/owner/dashboard.php"); exit;
                case 2: header("Location: /area-clientes/finance/dashboard.php"); exit;
                case 3: header("Location: /area-clientes/people/dashboard.php"); exit;
                case 4: header("Location: /area-clientes/IT_Dev/dashboard.php"); exit;
                case 5: header("Location: /area-clientes/IT_Tech/dashboard.php"); exit;
                case 6: header("Location: /area-clientes/customer_service/dashboard.php"); exit;
                case 7: header("Location: /area-clientes/warehouse/dashboard.php"); exit;
                case 8: header("Location: /area-clientes/driver/dashboard.php"); exit;
                default: $error = "Rol no válido (empleado).";
            }
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    } else {
        // Buscar en clientes
        $stmt = $pdo->prepare("
            SELECT c.*, r.role_name 
            FROM client c 
            LEFT JOIN rol r ON c.rolID = r.rolID 
            WHERE c.email = ?
        ");
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            if ($client['status'] !== 'Active') {
                $error = "Cuenta deshabilitada. Contacta con administración.";
            } elseif (password_verify($password, $client['password_hash'])) {
                $_SESSION['user_id'] = $client['client_id'];
                $_SESSION['username'] = $client['email'];
                $_SESSION['role_id'] = $client['rolID'];
                $_SESSION['role_name'] = $client['role_name'];

                registrarLogin($pdo, null, $client['client_id']);

                header("Location: client/dashboard.php");
                exit;
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - INCARGO365</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f6a640, #fcd9a5);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-container img {
      max-width: 150px;
      margin-bottom: 20px;
    }

    h2 {
      margin-bottom: 25px;
      color: #4a2600;
    }

    .input-group {
      text-align: left;
      margin-bottom: 15px;
    }

    label {
      font-weight: bold;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-top: 5px;
    }

    .login-btn {
      background-color: #b33b3b;
      color: white;
      border: none;
      width: 100%;
      padding: 12px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }

    .login-btn:hover {
      background-color: #932e2e;
    }

    .error-message {
      color: red;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .links {
      margin-top: 25px;
      font-size: 14px;
    }

    .links a {
      color: #4a2600;
      text-decoration: none;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .home-btn {
      display: inline-block;
      margin-top: 20px;
      font-size: 14px;
      background-color: #a8e6cf;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
    }

    .home-btn:hover {
      background-color: #3da9fc;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="../imagenes/logo-incargo365-sinfondo.png" alt="INCARGO365 Logo">
    <h2>Inicio de Sesión</h2>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="input-group">
        <label for="username">Correo</label>
        <input type="text" id="username" name="username" required />
      </div>
      <div class="input-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit" class="login-btn">Acceder</button>
    </form>

    <div class="links">
      <p><a href="forget_password.php">¿Olvidaste tu contraseña?</a></p>
      <p><a href="/" class="home-btn">🏠 Volver al Inicio</a></p>
      <p><a href="registro_cliente.php" class="home-btn" style="background-color:#a8e6cf;">📝 Crear cuenta (Cliente)</a></p>
    </div>
  </div>
</body>
</html>
