<?php
    if($_GET['form']=='add'){ ?>
        <section class="content-header">
            <h1>
                <i class="fa fa-edit icon-title">Agregar Venta</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
                <li><a href="?module=ventas">Ventas</a></li>
                <li class="active">Agregar</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form role="form" class="form-horizontal" action="modules/ventas/proses.php?act=insert" method="POST">
                            <div class="box-body">
                                <?php
                                //Método para generar código
                                    $query_id = mysqli_query($mysqli, "SELECT MAX(cod_venta) as id FROM venta")
                                                                        or die('Error'.mysqli_error($mysqli));
                                    $count = mysqli_num_rows($query_id);
                                    if($count <> 0){
                                        $data_id = mysqli_fetch_assoc($query_id);
                                        $codigo = $data_id['id']+1;
                                    }else{
                                        $codigo=1;
                                    }

                                //Método para generar el N° de Factura automáticamente (evita carga manual)
                                    $query_factura = mysqli_query($mysqli, "SELECT MAX(nro_factura) as nro FROM venta")
                                                                        or die('Error'.mysqli_error($mysqli));
                                    $data_factura = mysqli_fetch_assoc($query_factura);
                                    if($data_factura['nro'] === null){
                                        $nro_factura = 1001; //Primer número de factura del sistema
                                    }else{
                                        $nro_factura = $data_factura['nro']+1;
                                    }
                                ?>
                                <input type="hidden" name="id_user" value="<?php echo intval($_SESSION['id_user']); ?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Código</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                    </div>

                                    <label class="col-sm-1 control-label" >Fecha</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="fecha" autocomplete="off"
                                        value="<?php echo date("d-m-Y"); ?>" readonly>
                                    </div>

                                    <label class="col-sm-1 control-label" >Hora</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control date-picker" data-date-format="H-mm-ss" name="hora" autocomplete="off"
                                        value="<?php echo date("H:i:s"); ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cliente</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" name="codigo_cliente" data-placeholder="-- Seleccionar Cliente --"
                                        autocomplete="off" required>
                                            <option value=""></option>
                                            <?php
                                                $query_cli = mysqli_query($mysqli, "SELECT id_cliente, cli_nombre, cli_apellido, ci_ruc
                                                FROM clientes
                                                ORDER BY id_cliente ASC") or die ('Error'.mysqli_error($mysqli));
                                                while ($data_cli = mysqli_fetch_assoc($query_cli)){
                                                    echo "<option value=\"$data_cli[id_cliente]\">$data_cli[cli_nombre] $data_cli[cli_apellido] | $data_cli[ci_ruc]</option>";
                                                }
                                            ?>
                                    </select>
                                    </div>
                                    <label class="col-sm-2 control-label">N° de Factura</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="nro_factura" value="<?php echo $nro_factura; ?>" readonly>
                                    </div>
                                </div>

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
                                                    echo "<option value=\"$data_dep[cod_deposito]\">$data_dep[cod_deposito] | $data_dep[descrip]</option>";
                                                }
                                            ?>
                                    </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="col-sm-2 control-label">Productos</label>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                            <span class="glyphicon glyphicon-plus">Agregar Productos</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="resultados" class="col-md-9">

                                </div>

                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=ventas" class="btn btn-default btn-reset">Cancelar</a>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

    <script>
        $(document).ready(function(){
            load(1);
        });

        function load(page){
            var x = $("#x").val();
            var parametros = {"action":"ajax","page":page, "x":x};
            $("#loader").fadeIn('slow');
            $.ajax({
                url:'./ajax/productos_pedido.php',
                data: parametros,
                beforeSend: function(objeto){
                    $('#loader').html('<img src="./images/ajax-loader2.gif">Cargando...');
                },
                success:function(data){
                    $(".outer_div").html(data).fadeIn('slow');
                    $('#loader').html('');
                }
            })
        }
    </script>

    <script>
        function agregar(id){
            var precio_compra=$('#precio_compra_'+id).val();
            var cantidad=$('#cantidad_'+id).val();
            if(isNaN(cantidad) || parseFloat(cantidad) <= 0){
                alert('Ingrese una cantidad válida (mayor a 0)');
                document.getElementById('cantidad_'+id).focus();
                return false;
            }
            if(isNaN(precio_compra) || parseFloat(precio_compra) <= 0){
                alert('Ingrese un precio válido (mayor a 0)');
                document.getElementById('precio_compra_'+id).focus();
                return false;
            }
            //Fin de validación
            var parametros={"id":id,"precio_compra_":precio_compra,"cantidad":cantidad};
            $.ajax({
                type: "POST",
                url: "./ajax/agregar_pedido.php",
                data: parametros,
                beforeSend: function(objeto){
                    $("#resultados").html("Mensaje: Cargando...");
                },
                success: function(datos){
                    $("#resultados").html(datos);
                }
            });
        }
        function eliminar(id){
            $.ajax({
                type: "GET",
                url: "./ajax/agregar_pedido.php",
                data: "id="+id,
                beforeSend: function(objeto){
                    $("#resultados").html("Mensaje: Cargando...");
                },
                success: function(datos){
                    $("#resultados").html(datos);
                }
            });
        }
    </script>

    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Buscar Productos</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="x" placeholder="Buscar Productos" onkeyup="load(1)">
                            </div>
                            <button type="button" class="btn btn-default" onclick="load(1)"><span class="glyphicon glyphicon-search"></span>Buscar</button>
                        </div>
                    </form>
                    <div id="loader" style="position: absolute; text-align; center; top: 55px; width:100%; display:none;"></div>
                    <div class="outer_div"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
