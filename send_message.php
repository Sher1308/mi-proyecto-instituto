<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

// Función para mostrar respuesta con diseño
function mostrarRespuesta($mensaje, $exito = true) {
    $icono = $exito ? "✅" : "❌";
    $color = $exito ? "#4CAF50" : "#D32F2F";
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensaje Enviado - INCARGO365</title>
    <link rel="stylesheet" href="main.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .response-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .response-container h1 { color: $color; font-size: 28px; }
        .response-container p { margin-top: 20px; font-size: 18px; color: #333; }
        .response-container a {
            margin-top: 30px; display: inline-block; text-decoration: none;
            background-color: #d88c27; color: white; padding: 12px 24px;
            border-radius: 6px; transition: background 0.3s;
        }
        .response-container a:hover { background-color: #b6701f; }
    </style>
</head>
<body>
    <div class="response-container">
        <h1>$icono $mensaje</h1>
        <p>Volver a la página principal o navegar a otra sección desde el menú superior.</p>
        <a href="index.html">Volver al Inicio</a>
    </div>
</body>
</html>
HTML;
    exit;
}

try {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($message)) {
        mostrarRespuesta("Por favor, rellena todos los campos.", false);
    }

    // Configuración SMTP para Amazon SES
    $mail->isSMTP();
    $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'AKIA6GBMCG4VYHX7NRTR';
    $mail->Password = 'BJ/FW6rKiEsns+sX/p7cD9o/3CZ6PiQKIaxxY/RbDoIk';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Enviar mensaje principal a INCARGO
    $mail->setFrom('contacto@incargo365.com', 'INCARGO365');
    $mail->addAddress('contacto@incargo365.com');
    $mail->addReplyTo($email, $name);
    $mail->Subject = "Nuevo mensaje de contacto de $name";
    $mail->Body = "Nombre: $name\nCorreo: $email\nMensaje:\n$message";
    $mail->send();

    // Enviar copia al cliente
    $mailCliente = new PHPMailer(true);
    $mailCliente->isSMTP();
    $mailCliente->Host = 'email-smtp.us-east-1.amazonaws.com';
    $mailCliente->SMTPAuth = true;
    $mailCliente->Username = 'AKIA6GBMCG4VYHX7NRTR';
    $mailCliente->Password = 'BJ/FW6rKiEsns+sX/p7cD9o/3CZ6PiQKIaxxY/RbDoIk';
    $mailCliente->SMTPSecure = 'tls';
    $mailCliente->Port = 587;

    $mailCliente->setFrom('contacto@incargo365.com', 'INCARGO365');
    $mailCliente->addAddress($email, $name);
    $mailCliente->Subject = "📩 Confirmación de contacto - INCARGO365";
    $mailCliente->Body = "Hola $name,\n\n"
                       . "Gracias por escribirnos. Hemos recibido tu mensaje y te responderemos lo antes posible.\n\n"
                       . "📝 Tu mensaje:\n$message\n\n"
                       . "📍 Dirección: Av. d'Esplugues, 40, Barcelona\n"
                       . "📞 Teléfono: 932 033 332\n"
                       . "📧 Email: contacto@incargo365.com\n\n"
                       . "Un saludo,\nEl equipo de INCARGO365";
    $mailCliente->send();

    mostrarRespuesta("Mensaje enviado con éxito. ¡Te hemos enviado una copia de confirmación!");

} catch (Exception $e) {
    mostrarRespuesta("Error al enviar el mensaje: " . $mail->ErrorInfo . " | Copia cliente: " . ($mailCliente->ErrorInfo ?? 'N/A'), false);
}

?>
