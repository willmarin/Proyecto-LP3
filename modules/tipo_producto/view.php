<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=tipo_producto">Tipos de Producto</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-tags icon-title"></i>Datos de Tipos de Producto
    <a class="btn btn-primary btn-social pull-right" href="?module=form_tipo_producto&form=add" title="Agregar" data-toggle="tooltip">
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
                No se pudo realizar la operación. Verifique que no esté siendo utilizado por otro registro.
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right" href="modules/tipo_producto/print.php" target="_blank">
                            <i class="fa fa-print"></i>Imprimir
                        </a>
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de tipos de producto</h2>
                        <thead>
                            <tr>
                                <th class="center">Código</th>
                                <th class="center">Descripción</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query= mysqli_query($mysqli, "SELECT * FROM tipo_producto ORDER BY cod_tipo_prod ASC")
                                                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                                echo "<tr>
                                <td class='center'>$data[cod_tipo_prod]</td>
                                <td class='center'>$data[t_p_descrip]</td>
                                <td class='center' width='80'>
                                <div>
                                <a data-toggle='tooltip' data-placement='top' title='Modificar' style='margin-right:5px'
                                class='btn btn-primary btn-sm' href='?module=form_tipo_producto&form=edit&id=$data[cod_tipo_prod]'>
                                    <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a>";
                                ?>
                                <a data-toggle="tooltip" data-placement="top" title="Eliminar datos" class="btn btn-danger btn-sm"
                                href="modules/tipo_producto/proses.php?act=delete&cod_tipo_prod=<?php echo $data['cod_tipo_prod']; ?>"
                                onclick="return confirm('¿Estás seguro de eliminar <?php echo addslashes($data['t_p_descrip']); ?>?')">
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
