<?php
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM v_clientes")
                                or die('Error'.mysqli_error($mysqli));

$count = mysqli_num_rows($query);
?>

<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Clientes</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <img src="../../images/asuncion.jpg" width="220" style="width:220px; height:auto;">
        </div>
        <div>
            Reporte de Clientes
        </div>
        <div align="center">
            Cantidad: <?php echo $count; ?>
    </div>
    <hr>
    <div>
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
            <thead style="background:#e8ecee">
                <tr class="table-title">
                    <th height="20" align="center" valing="middle"><small>ID</small></th>
                    <th height="30" align="center" valing="middle"><small>RUC</small></th>
                    <th height="30" align="center" valing="middle"><small>Departamento</small></th>
                    <th height="30" align="center" valing="middle"><small>Ciudad</small></th>
                    <th height="30" align="center" valing="middle"><small>Nombre</small></th>
                    <th height="30" align="center" valing="middle"><small>Apellido</small></th>
                    <th height="30" align="center" valing="middle"><small>Teléfono</small></th>
                    </tr>
            </thead>
            <tbody>
                <?php
                    while($data = mysqli_fetch_assoc($query)){
                                $id_cliente = $data['id_cliente'];
                                $ci_ruc = $data['ci_ruc'];
                                $dep_descripcion = $data['dep_descripcion'];
                                $descrip_ciudad = $data['descrip_ciudad'];  
                                $cli_nombre = $data['cli_nombre'];
                                $cli_apellido = $data['cli_apellido'];
                                $cli_telefono = $data['cli_telefono'];

                        echo "<tr>
                        <td width='60' align='left'>$id_cliente</td>
                        <td width='90' align='left'>$ci_ruc</td>
                        <td width='110' align='left'>$dep_descripcion</td>
                        <td width='110' align='left'>$descrip_ciudad</td>
                        <td width='110' align='left'>$cli_nombre</td>
                        <td width='110' align='left'>$cli_apellido</td>
                        <td width='100' align='left'>$cli_telefono</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    </body>
</html>