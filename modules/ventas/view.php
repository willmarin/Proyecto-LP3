<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=ventas">Ventas</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-cart-plus icon-title"></i>Datos de Ventas
    <a class="btn btn-primary btn-social pull-right" href="?module=form_ventas&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar
    </a>
</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if(empty($_GET['alert'])){
                echo "";
            }
            elseif($_GET['alert']==1){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i> Exitoso! </h4>
                Venta registrada correctamente
                </div>";
            }
            elseif($_GET['alert']==2){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exitoso! </h4>
                Venta anulada correctamente. El stock fue restituido
                </div>";
            }
            elseif($_GET['alert']==3){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Error! </h4>
                No se pudo realizar la acción
                </div>";
            }
            elseif($_GET['alert']==4){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Error! </h4>
                Stock insuficiente para completar la venta en el depósito seleccionado
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <section class="content-header">
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Ventas</h2>
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">Cliente</th>
                                <th class="center">Nro Factura</th>
                                <th class="center">Fecha</th>
                                <th class="center">Hora</th>
                                <th class="center">Total</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query= mysqli_query($mysqli, "SELECT * FROM v_venta WHERE estado = 'activo' ORDER BY cod_venta DESC")
                                                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                                $cod = $data['cod_venta'];
                                $cliente = $data['cli_nombre'].' '.$data['cli_apellido'];
                                $nro_factura = $data['nro_factura'];
                                $fecha = $data['fecha'];
                                $hora = $data['hora'];
                                $total_venta = number_format($data['total_venta']);

                                echo "<tr>
                                <td class='center'>$cod</td>
                                <td class='center'>$cliente</td>
                                <td class='center'>$nro_factura</td>
                                <td class='center'>$fecha</td>
                                <td class='center'>$hora</td>
                                <td class='center'>Gs. $total_venta</td>
                                <td class='center' width='80'>
                                <div>"; ?>
                                <a data-toggle="tooltip" data-placement="top" title="Anular Venta" class="btn btn-danger btn-sm"
                                 href="modules/ventas/proses.php?act=anular&cod_venta=<?php echo $data['cod_venta']; ?>"
                                 onclick="return confirm('¿Estás seguro de anular la venta N° <?php echo $data['cod_venta']; ?>? Esta acción restituirá el stock.');">
                                    <i style="color:#000" class="glyphicon glyphicon-trash"></i>
                                </a>

                                <a data-toggle="tooltip" data-placement="top" title="Imprimir factura de venta" class="btn btn-warning btn-sm"
                                href="modules/ventas/print.php?act=imprimir&cod_venta=<?php echo $data['cod_venta']; ?>" target="_blank">
                                    <i style="color:#000" class="fa fa-print"></i>
                                </a>
                                <?php echo "</div>
                                </td>
                                </tr>" ?>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
