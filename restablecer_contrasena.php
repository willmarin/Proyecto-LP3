<?php
include "config/database.php";
$email = $_GET['email'] ?? '';
$message = '';

if (isset($_POST['cambiar'])) {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if ($pass1 === $pass2) {
        $hash = md5($pass1);
        mysqli_query($mysqli, "UPDATE usuarios SET password='$hash', codigo_reset=NULL WHERE email='$email'");
        $message = "<div class='alert alert-success'>Contraseña actualizada correctamente. <a href='index.php'>Iniciar sesión</a></div>";
    } else {
        $message = "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña | SysWeb</title>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="login-box">
    <div class="login-logo" style="color:#3c8dbc"><b>SysWeb</b> Nueva contraseña</div>
    <div class="login-box-body">
        <?= $message ?>
        <form method="POST">
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="pass1" placeholder="Nueva contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="pass2" placeholder="Repite contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" name="cambiar" class="btn btn-primary btn-block btn-flat">Cambiar</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
