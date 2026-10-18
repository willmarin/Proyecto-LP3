<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=stock">Stock</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Stock de Productos
</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-body">
                    <form role="form" class="form-horizontal" method="POST">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Depósito</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" name="codigo_deposito" data-placeholder="-- Seleccionar Depósito --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_dep = mysqli_query($mysqli, "SELECT cod_deposito, descrip
                                                FROM deposito
                                                ORDER BY cod_deposito ASC") or die ('Error'.mysqli_error($mysqli));
                                                while ($data_dep = mysqli_fetch_assoc($query_dep)){
                                                    $selected = (!empty($_POST['codigo_deposito']) && $_POST['codigo_deposito']==$data_dep['cod_deposito']) ? 'selected' : '';
                                                    echo "<option value=\"$data_dep[cod_deposito]\" $selected>$data_dep[cod_deposito] | $data_dep[descrip]</option>";
                                                }
                                            ?>
                                    </select>
                                    </div>

                                    <!--Combo buscador de productos: filtra la tabla de stock por producto-->
                                    <label class="col-sm-2 control-label">Producto</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" name="codigo_producto" data-placeholder="-- Todos los productos --"
                                        autocomplete="off">
                                            <option value="">-- Todos los productos --</option>
                                            <?php
                                                $query_prod = mysqli_query($mysqli, "SELECT cod_producto, p_descrip
                                                FROM producto
                                                ORDER BY p_descrip ASC") or die ('Error'.mysqli_error($mysqli));
                                                while ($data_prod = mysqli_fetch_assoc($query_prod)){
                                                    $selected = (!empty($_POST['codigo_producto']) && $_POST['codigo_producto']==$data_prod['cod_producto']) ? 'selected' : '';
                                                    echo "<option value=\"$data_prod[cod_producto]\" $selected>$data_prod[p_descrip]</option>";
                                                }
                                            ?>
                                    </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary btn-social btn-submit" style="width:200px">
                                            <i class="fa fa-search icon-title"></i>Buscar
                                        </button>
                                    </div>
                                </div>
                    </form>

                    <section class="content-header">
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <?php
                        //Depósito a consultar (por defecto el primero, igual que el comportamiento original)
                        if(!empty($_POST['codigo_deposito'])){
                            $cod_deposito = intval($_POST['codigo_deposito']);
                        }else{
                            $cod_deposito=1;
                        }

                        //Producto a filtrar (opcional). Si no se selecciona ninguno, se listan todos
                        $cod_producto = (!empty($_POST['codigo_producto'])) ? intval($_POST['codigo_producto']) : 0;

                        $sql_stock = "SELECT * FROM v_stock WHERE cod_deposito=$cod_deposito";
                        if($cod_producto > 0){
                            $sql_stock .= " AND cod_producto=$cod_producto";
                        }

                        $dep_descrip='';
                        $query= mysqli_query($mysqli, $sql_stock)
                                                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                                $dep_descrip=$data['descrip'];
                            }
                        ?>
                        <h2>Stock de Productos: <?php echo $dep_descrip;?> </h2>
                        <thead>
                            <tr>
                                <th class="center">Depósito</th>
                                <th class="center">Tip. Producto</th>
                                <th class="center">Producto</th>
                                <th class="center">U. Medida</th>
                                <th class="center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query= mysqli_query($mysqli, $sql_stock)
                                                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                                $p_descrip = $data['p_descrip'];
                                $dep_descrip = $data['descrip'];
                                $t_p_descrip = $data['t_p_descrip'];
                                $u_descrip = $data['u_descrip'];
                                $cantidad = $data['cantidad'];

                                echo "<tr>
                                <td class='center'>$dep_descrip</td>
                                <td class='center'>$t_p_descrip</td>
                                <td class='center'>$p_descrip</td>
                                <td class='center'>$u_descrip</td>
                                <td class='center'>$cantidad</td>
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
