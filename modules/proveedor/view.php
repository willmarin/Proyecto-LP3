<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=proveedor">Proveedor</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-truck icon-title"></i>Datos de Proveedores
    <a class="btn btn-primary btn-social pull-right" href="?module=form_proveedor&form=add" title="Agregar" data-toggle="tooltip">
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
                Datos registrados correctamente
                </div>";
            }
            elseif($_GET['alert']==2){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exitoso! </h4>
                Datos modificados correctamente
                </div>";
            }
            elseif($_GET['alert']==3){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exitoso! </h4>
                Datos eliminados correctamente
                </div>";
            }
            elseif($_GET['alert']==4){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Error! </h4>
                No se pudo realizar la operación. Verifique los datos ingresados.
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right" href="modules/proveedor/print.php" target="_blank">
                            <i class="fa fa-print"></i>Imprimir
                        </a>
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de proveedores</h2>
                        <thead>
                            <tr>
                                <th class="center">Código</th>
                                <th class="center">Razón Social</th>
                                <th class="center">RUC</th>
                                <th class="center">Dirección</th>
                                <th class="center">Teléfono</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query= mysqli_query($mysqli, "SELECT * FROM proveedor ORDER BY cod_proveedor ASC")
                                                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                                $cod_proveedor = $data['cod_proveedor'];
                                $razon_social = $data['razon_social'];
                                $ruc = $data['ruc'];
                                $direccion = $data['direccion'];
                                $telefono = $data['telefono'];

                                echo "<tr>
                                <td class='center'>$cod_proveedor</td>
                                <td class='center'>$razon_social</td>
                                <td class='center'>$ruc</td>
                                <td class='center'>$direccion</td>
                                <td class='center'>$telefono</td>
                                <td class='center' width='80'>
                                <div>
                                <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Proveedor' style='margin-right:5px'
                                class='btn btn-primary btn-sm' href='?module=form_proveedor&form=edit&id=$data[cod_proveedor]'>
                                    <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a>";
                                ?>
                                <a data-toggle="tooltip" data-placement="top" title="Eliminar datos" class="btn btn-danger btn-sm"
                                href="modules/proveedor/proses.php?act=delete&cod_proveedor=<?php echo $data['cod_proveedor']; ?>"
                                onclick="return confirm('¿Estás seguro de eliminar <?php echo addslashes($data['razon_social']); ?>?')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?php
                                echo "</div>
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
