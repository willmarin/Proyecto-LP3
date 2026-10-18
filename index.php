<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>Login | SysWeb</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes' name='viewport'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="SysWeb">
<meta name="autor" content="Williams Marin">

<!-- favicon -->
<link rel="shortcut icon" href="assets/img/favicon.ico"/>

<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

<link href="assets/css/style.css" rel="stylesheet" type="text/css" />

<body>
    <div class="login-box">
        <div style="color:#3c8dbc" class="login-logo">
            <img style="margin-top:-15px" src="assets/img/favicon.ico" alt="SysWeb" heigth="50">
            <b>SysWeb</b>
        </div>
    <?php
        if(empty($_GET['alert'])){
            echo "";
        }
        elseif($_GET['alert']==1){
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4> <i class='icon fa fa-times-circle'></i>Error al ingresar!</h4>
            Usuario o contraseña incorrecta, vuelva a ingresar sus datos.
            </div>";
        }
        elseif($_GET['alert']==2){
            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4> <i class='icon fa fa-times-circle'></i>Salida exitosa!</h4>
            Has cerrado tu sesión correctamente.
            </div>";
        }
        elseif($_GET['alert']==3){
            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4> <i class='icon fa fa-check-circle'></i>Atención!</h4>
            Debes ingresar un usuario y contraseña para interactuar dentro del sistema.
            </div>";
        }
        ?>

        <div class="login-box-body">
            <p class="login-box-msg"><i class="fa fa-user icon-title"></i>Por favor inicie sesión</p>
            <br>
            <form action="login-check.php" method="POST">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Usuario" autocomplete="off" required>
                    <span class="glyphicon glyphicon-user form-control-feedback">
                </div>

                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="off" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback">
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="submit" class="btn-primary btn-lg btn-block btn-flat" name="login" value="Ingresar"> 
                    </div>
                </div>
                <!--Se incluye opción de olvide contraseña-->
                <div class="text-center" style="margin-top:15px;">
                    <a href="olvide_contrasena.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>