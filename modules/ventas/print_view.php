<?php
    require_once "../../config/database.php";
    if($_GET['act']=='imprimir'){
        if(isset($_GET['cod_venta'])){
            $codigo = intval($_GET['cod_venta']);
            //Cabecera de venta
            $cabecera_venta = mysqli_query($mysqli, "SELECT * FROM v_venta WHERE cod_venta = $codigo")
                                                        or die('Error'.mysqli_error($mysqli));
                                                        while($data = mysqli_fetch_assoc($cabecera_venta)){
                                                        $cod = $data['cod_venta'];
                                                        $cliente = $data['cli_nombre'].' '.$data['cli_apellido'];
                                                        $ruc = $data['ci_ruc'];
                                                        $nro_factura = $data['nro_factura'];
                                                        $fecha = $data['fecha'];
                                                        $hora = $data['hora'];
                                                        $total_venta = $data['total_venta'];
                                                        $usuario = $data['name_user'];}
            //Detalle de venta
            $detalle_venta = mysqli_query($mysqli, "SELECT * FROM v_det_venta WHERE cod_venta = $codigo")
                                                        or die('Error'.mysqli_error($mysqli));
        }

    }
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Factura de venta</title>
    </head>
    <body>
        <div align='center'>
            Registro de factura de venta <br>
            <label><strong>Cliente: </strong><?php echo $cliente ?></label><br>
            <label><strong>RUC/CI: </strong><?php echo $ruc ?></label><br>
            <label><strong>Nro Factura: </strong><?php echo $nro_factura ?></label><br>
            <label><strong>Fecha: </strong><?php echo $fecha ?></label><br>
            <label><strong>Hora: </strong><?php echo $hora ?></label><br>
            <label><strong>Usuario: </strong><?php echo $usuario ?></label>
        </div>
        <hr>
            <div>
                <table width="100%" border="0.3" cellpadding="0" align="center">
                    <thead style="background:#e8ecee">
                        <tr class="table-title">
                            <th height="20" align="center" valign="middle"><small>Tipo de producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Unidad de medida</small></th>
                            <th height="20" align="center" valign="middle"><small>Precio</small></th>
                            <th height="20" align="center" valign="middle"><small>Cantidad</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($data2 = mysqli_fetch_assoc($detalle_venta)){
                                $t_p_descrip = $data2['t_p_descrip'];
                                $p_descrip = $data2['p_descrip'];
                                $u_descrip = $data2['u_descrip'];
                                $precio = $data2['det_precio_unit'];
                                $cantidad = $data2['det_cantidad'];

                                echo "<tr>
                                        <td width='100' align='left'>$t_p_descrip</td>
                                        <td width='100' align='left'>$p_descrip</td>
                                        <td width='100' align='left'>$u_descrip</td>
                                        <td width='100' align='left'>$precio</td>
                                        <td width='100' align='left'>$cantidad</td>
                                        </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <div align='center'>
                <label><strong>El total de la venta es: Gs.<?php echo number_format($total_venta); ?></strong></label>
            </div>
    </body>
</html>
