<?php
    if($_GET['form']=='add'){ ?>
        <section class="content-header">
            <h1>
                <i class="fa fa-edit icon-title">Agregar Producto</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
                <li><a href="?module=producto">Producto</a></li>
                <li class="active">Agregar</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form role="form" class="form-horizontal" action="modules/producto/proses.php?act=insert" method="POST">
                            <div class="box-body">
                                <?php
                                //Método para generar código
                                    $query_id = mysqli_query($mysqli, "SELECT MAX(cod_producto) as id FROM producto")
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
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tipo de Producto</label>
                                    <div class="col-sm-5">
                                        <select class="chosen-select" name="tipo_producto" data-placeholder="-- Seleccionar Tipo de Producto --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_tp = mysqli_query($mysqli, "SELECT cod_tipo_prod, t_p_descrip FROM tipo_producto ORDER BY t_p_descrip ASC")
                                                                    or die('Error'.mysqli_error($mysqli));
                                                while($data_tp = mysqli_fetch_assoc($query_tp)){
                                                    echo "<option value='".$data_tp['cod_tipo_prod']."'";
                                                    if (isset($_POST['tipo_producto']) && $_POST['tipo_producto'] == $data_tp['cod_tipo_prod']) {
                                                        echo " selected";
                                                    }
                                                    echo ">";
                                                    echo $data_tp['t_p_descrip'];
                                                    echo "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Unidad de Medida</label>
                                    <div class="col-sm-5">
                                        <select class="chosen-select" name="u_medida" data-placeholder="-- Seleccionar Unidad de Medida --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_um = mysqli_query($mysqli, "SELECT id_u_medida, u_descrip FROM u_medida ORDER BY u_descrip ASC")
                                                                    or die('Error'.mysqli_error($mysqli));
                                                while($data_um = mysqli_fetch_assoc($query_um)){
                                                    echo "<option value='".$data_um['id_u_medida']."'";
                                                    if (isset($_POST['u_medida']) && $_POST['u_medida'] == $data_um['id_u_medida']) {
                                                        echo " selected";
                                                    }
                                                    echo ">";
                                                    echo $data_um['u_descrip'];
                                                    echo "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Descripción</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="p_descrip" placeholder="Ingrese la descripción del producto" maxlength="50" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Precio</label>
                                    <div class="col-sm-3">
                                        <input type="number" min="0" step="1" class="form-control" name="precio" placeholder="Ingrese el precio" required>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=producto" class="btn btn-default btn-reset">Cancelar</a>
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
            $query = mysqli_query($mysqli, "SELECT * FROM producto WHERE cod_producto = $id")
                                            or die('Error'.mysqli_error($mysqli));
            $data = mysqli_fetch_assoc($query);
        } ?>
        <section class="content-header">
            <h1>
                <i class="fa fa-edit icon-title">Modificar Producto</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
                <li><a href="?module=producto">Producto</a></li>
                <li class="active">Modificar</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form role="form" class="form-horizontal" action="modules/producto/proses.php?act=update" method="POST">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Código</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="codigo" value="<?php echo $data['cod_producto']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tipo de Producto</label>
                                    <div class="col-sm-5">
                                        <select class="chosen-select" name="tipo_producto" data-placeholder="-- Seleccionar Tipo de Producto --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_tp = mysqli_query($mysqli, "SELECT cod_tipo_prod, t_p_descrip FROM tipo_producto ORDER BY t_p_descrip ASC")
                                                                    or die('Error'.mysqli_error($mysqli));
                                                while($data_tp = mysqli_fetch_assoc($query_tp)){
                                                    echo "<option value='".$data_tp['cod_tipo_prod']."'";
                                                    if ($data['cod_tipo_prod'] == $data_tp['cod_tipo_prod']) {
                                                        echo " selected";
                                                    }
                                                    echo ">";
                                                    echo $data_tp['t_p_descrip'];
                                                    echo "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Unidad de Medida</label>
                                    <div class="col-sm-5">
                                        <select class="chosen-select" name="u_medida" data-placeholder="-- Seleccionar Unidad de Medida --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_um = mysqli_query($mysqli, "SELECT id_u_medida, u_descrip FROM u_medida ORDER BY u_descrip ASC")
                                                                    or die('Error'.mysqli_error($mysqli));
                                                while($data_um = mysqli_fetch_assoc($query_um)){
                                                    echo "<option value='".$data_um['id_u_medida']."'";
                                                    if ($data['id_u_medida'] == $data_um['id_u_medida']) {
                                                        echo " selected";
                                                    }
                                                    echo ">";
                                                    echo $data_um['u_descrip'];
                                                    echo "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Descripción</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="p_descrip" value="<?php echo htmlspecialchars($data['p_descrip']); ?>" maxlength="50" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Precio</label>
                                    <div class="col-sm-3">
                                        <input type="number" min="0" step="1" class="form-control" name="precio" value="<?php echo $data['precio']; ?>" required>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=producto" class="btn btn-default btn-reset">Cancelar</a>
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
