<?php
include "config/database.php";
require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';
require 'includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if (isset($_POST['enviar'])) {
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $codigo = rand(100000, 999999);

    $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE email='$email'") or die(mysqli_error($mysqli));

    if (mysqli_num_rows($query) > 0) {
        mysqli_query($mysqli, "UPDATE usuarios SET codigo_reset='$codigo' WHERE email='$email'");

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'orlando.marin97@gmail.com';
            $mail->Password = 'deufgntipdxiekza'; // contraseña de aplicación Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tu_correo@gmail.com', 'SysWeb');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - SysWeb';
            $mail->Body = "<p>Tu código de verificación es: <b>$codigo</b></p>";

            $mail->send();
            $message = "<div class='alert alert-success'>Código enviado a tu correo. <a href='verificar_codigo.php?email=$email'>Verificar código</a></div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Error al enviar correo: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>El correo no está registrado.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña | SysWeb</title>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="login-box">
    <div class="login-logo" style="color:#3c8dbc">
        <b>SysWeb</b> Cambiar contraseña
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Ingresa tu correo para recuperar tu contraseña</p>
        <?= $message ?>
        <form method="POST">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Correo registrado" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" name="enviar" class="btn btn-primary btn-block btn-flat">Enviar código</button>
                </div>
            </div>
        </form>
        <br>
        <a href="index.php">Volver al inicio</a>
    </div>
</div>
</body>
</html>
