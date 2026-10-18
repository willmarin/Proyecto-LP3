<?php
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM departamento")
                                or die('Error'.mysqli_error($mysqli));

$count = mysqli_num_rows($query);
?>

<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de departamenos</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <img src="../../images/asuncion.jpg" width="220" style="width:220px; height:auto;">
        </div>
        <div>
            Reporte de ciudad
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
                    <th height="50" align="center" valing="middle"><small>Descripción</small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($data = mysqli_fetch_assoc($query)){
                        $codigo = $data['id_departamento'];
                        $dep_descripcion = $data['dep_descripcion'];

                        echo "<tr>
                        <td width='100' align='left'>$codigo</td>
                        <td width='150' align='left'>$dep_descripcion</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    </body>
</html>