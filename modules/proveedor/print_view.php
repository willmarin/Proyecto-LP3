<?php
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM proveedor ORDER BY cod_proveedor ASC")
                                or die('Error'.mysqli_error($mysqli));

$count = mysqli_num_rows($query);
?>

<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de proveedores</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <img src="../../images/asuncion.jpg" width="220" style="width:220px; height:auto;">
        </div>
        <div>
            Reporte de proveedores
        </div>
        <div align="center">
            Cantidad: <?php echo $count; ?>
    </div>
    <hr>
    <div>
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
            <thead style="background:#e8ecee">
                <tr class="table-title">
                    <th height="10" align="center" valing="middle"><small>Código</small></th>
                    <th height="10" align="center" valing="middle"><small>Razón Social</small></th>
                    <th height="10" align="center" valing="middle"><small>RUC</small></th>
                    <th height="10" align="center" valing="middle"><small>Dirección</small></th>
                    <th height="10" align="center" valing="middle"><small>Teléfono</small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($data = mysqli_fetch_assoc($query)){
                        $codigo = $data['cod_proveedor'];
                        $razon_social = $data['razon_social'];
                        $ruc = $data['ruc'];
                        $direccion = $data['direccion'];
                        $telefono = $data['telefono'];

                        echo "<tr>
                        <td width='60' align='left'>$codigo</td>
                        <td width='150' align='left'>$razon_social</td>
                        <td width='100' align='left'>$ruc</td>
                        <td width='150' align='left'>$direccion</td>
                        <td width='100' align='left'>$telefono</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    </body>
</html>
