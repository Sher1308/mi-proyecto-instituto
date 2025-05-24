<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

function mostrarRespuesta($mensaje, $exito = true) {
    $color = $exito ? "#4CAF50" : "#D32F2F";
    $icono = $exito ? "✅" : "❌";
    echo "<div style='padding: 40px; font-family: Arial; color: $color; text-align: center;'>
        <h2>$icono $mensaje</h2>
        <a href='index.html' style='text-decoration:none; color:white; background:#d88c27; padding:10px 20px; border-radius:5px;'>Volver</a>
    </div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["subscriber_email"])) {
    $subscriber = filter_var(trim($_POST["subscriber_email"]), FILTER_SANITIZE_EMAIL);

    if (!filter_var($subscriber, FILTER_VALIDATE_EMAIL)) {
        mostrarRespuesta("Correo electrónico no válido.", false);
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'AKIA6GBMCG4VYHX7NRTR';
        $mail->Password = 'BJ/FW6rKiEsns+sX/p7cD9o/3CZ6PiQKIaxxY/RbDoIk';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('contacto@incargo365.com', 'INCARGO365');
        $mail->addAddress('contacto@incargo365.com'); // Tú mismo
        $mail->Subject = 'Nuevo suscriptor al boletín';
        $mail->Body = "Un nuevo usuario se ha suscrito con el correo: $subscriber";

        $mail->send();
        mostrarRespuesta("¡Gracias por suscribirte! Muy pronto recibirás nuestras novedades.");
    } catch (Exception $e) {
        mostrarRespuesta("Error al enviar. {$mail->ErrorInfo}", false);
    }
} else {
    mostrarRespuesta("Acceso no permitido.", false);
}
?>
