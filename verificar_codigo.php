<?php
include "config/database.php";
$email = $_GET['email'] ?? '';
$message = '';

if (isset($_POST['verificar'])) {
    $codigo = mysqli_real_escape_string($mysqli, $_POST['codigo']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);

    $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE email='$email' AND codigo_reset='$codigo'");
    if (mysqli_num_rows($query) > 0) {
        header("Location: restablecer_contrasena.php?email=$email");
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Código incorrecto.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Verificar código | SysWeb</title>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="login-box">
    <div class="login-logo" style="color:#3c8dbc"><b>SysWeb</b> Verificación</div>
    <div class="login-box-body">
        <?= $message ?>
        <form method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="codigo" placeholder="Código recibido" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" name="verificar" class="btn btn-primary btn-block btn-flat">Verificar</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
