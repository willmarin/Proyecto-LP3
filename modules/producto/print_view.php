<?php
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT p.*, t.t_p_descrip, u.u_descrip
                                FROM producto p
                                INNER JOIN tipo_producto t ON t.cod_tipo_prod = p.cod_tipo_prod
                                INNER JOIN u_medida u ON u.id_u_medida = p.id_u_medida
                                ORDER BY p.cod_producto ASC")
                                or die('Error'.mysqli_error($mysqli));

$count = mysqli_num_rows($query);
?>

<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de productos</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <img src="../../images/asuncion.jpg" width="220" style="width:220px; height:auto;">
        </div>
        <div>
            Reporte de productos
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
                    <th height="10" align="center" valing="middle"><small>Tipo de Producto</small></th>
                    <th height="10" align="center" valing="middle"><small>Unidad de Medida</small></th>
                    <th height="50" align="center" valing="middle"><small>Descripción</small></th>
                    <th height="10" align="center" valing="middle"><small>Precio</small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($data = mysqli_fetch_assoc($query)){
                        $codigo = $data['cod_producto'];
                        $t_p_descrip = $data['t_p_descrip'];
                        $u_descrip = $data['u_descrip'];
                        $p_descrip = $data['p_descrip'];
                        $precio = number_format($data['precio']);

                        echo "<tr>
                        <td width='60' align='left'>$codigo</td>
                        <td width='120' align='left'>$t_p_descrip</td>
                        <td width='120' align='left'>$u_descrip</td>
                        <td width='200' align='left'>$p_descrip</td>
                        <td width='100' align='left'>Gs. $precio</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    </body>
</html>
