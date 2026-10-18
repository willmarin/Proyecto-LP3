<?php
    if($_GET['form']=='add'){ ?>
        <section class="content-header">
            <h1>
                <i class="fa fa-edit icon-title">Agregar Tipo de Producto</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
                <li><a href="?module=tipo_producto">Tipos de Producto</a></li>
                <li class="active">Agregar</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form role="form" class="form-horizontal" action="modules/tipo_producto/proses.php?act=insert" method="POST">
                            <div class="box-body">
                                <?php
                                //Método para generar código
                                    $query_id = mysqli_query($mysqli, "SELECT MAX(cod_tipo_prod) as id FROM tipo_producto")
                                                                        or die('Error'.mysqli_error($mysqli));
                                    $count = mysqli_num_rows($query_id);
                                    if($count <> 0){
                                        $data_id = mysqli_fetch_assoc($query_id);
                                        $codigo = $data_id['id']+1;
                                    }else{
                                        $codigo=1;
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Código</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Descripción</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="t_p_descrip" placeholder="Ingrese un tipo de producto" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=tipo_producto" class="btn btn-default btn-reset">Cancelar</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <?php }
    elseif($_GET['form']=='edit'){
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
            $query = mysqli_query($mysqli, "SELECT * FROM tipo_producto WHERE cod_tipo_prod = $id")
                                            or die('Error'.mysqli_error($mysqli));
            $data = mysqli_fetch_assoc($query);
        } ?>
        <section class="content-header">
            <h1>
                <i class="fa fa-edit icon-title">Modificar Tipo de Producto</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
                <li><a href="?module=tipo_producto">Tipos de Producto</a></li>
                <li class="active">Modificar</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form role="form" class="form-horizontal" action="modules/tipo_producto/proses.php?act=update" method="POST">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Código</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="codigo" value="<?php echo $data['cod_tipo_prod']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Descripción</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="t_p_descrip" value="<?php echo htmlspecialchars($data['t_p_descrip']); ?>" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=tipo_producto" class="btn btn-default btn-reset">Cancelar</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
   <?php }
?>