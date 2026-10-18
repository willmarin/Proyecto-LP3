<?php
session_start();
$session_id = session_id();
if(isset($_POST['id'])){$id=intval($_POST['id']);}
if(isset($_POST['cantidad'])){$cantidad = intval($_POST['cantidad']);}
if(isset($_POST['precio_compra_'])){$precio_compra_ = intval($_POST['precio_compra_']);}

require_once '../config/database.php';

if(!empty($id) and !empty($cantidad) and !empty($precio_compra_)){
    try{
        //Si el producto ya está en el carrito de esta sesión, se suma la cantidad
        //y se actualiza el precio en lugar de crear una fila duplicada (esto es lo
        //que antes generaba productos repetidos en la grilla y, al guardar, un
        //error de clave primaria duplicada en detalle_compra/det_venta).
        $existe = mysqli_query($mysqli, "SELECT id_tmp, cantidad_tmp FROM tmp
                                            WHERE id_producto = $id
                                            AND session_id = '$session_id'");
        if($existe && mysqli_num_rows($existe) > 0){
            $fila = mysqli_fetch_assoc($existe);
            $nueva_cantidad = intval($fila['cantidad_tmp']) + $cantidad;
            mysqli_query($mysqli, "UPDATE tmp SET cantidad_tmp = $nueva_cantidad, precio_tmp = $precio_compra_
                                    WHERE id_tmp = ".intval($fila['id_tmp']));
        }else{
            mysqli_query($mysqli, "INSERT INTO tmp (id_producto, cantidad_tmp, precio_tmp, session_id)
            VALUES($id, $cantidad, $precio_compra_, '$session_id')");
        }
    }catch(\Throwable $e){
        error_log('Error al agregar producto al carrito: '.$e->getMessage());
    }
}
if(isset($_GET['id'])){
    $id=intval($_GET['id']);
    $delete=mysqli_query($mysqli, "DELETE FROM tmp WHERE id_tmp='".$id."'");
}
?>
<table class="table table-bordered table-striped table-hover">
    <tr class="warning">
        <th>Código</th>
        <th>Tipo de Produ.</th>
        <th>Unid. de Medida</th>
        <th>Producto</th>
        <th><span class="pull-rigth">Cantidad</span></th>
        <th><span class="pull-rigth">Precio</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <?php
        $suma_total=0;
        $sql=mysqli_query($mysqli, "SELECT * FROM producto, tmp WHERE producto.cod_producto=tmp.id_producto AND tmp.session_id='".$session_id."'");
        while($row=mysqli_fetch_array($sql)){
            $id_tmp=$row['id_tmp'];
            $codigo_producto=$row['cod_producto'];
            $descrip_producto=$row['p_descrip'];
            $cantidad=$row['cantidad_tmp'];

            $codigo_tproducto=$row['cod_tipo_prod'];
            $sql_tproducto = mysqli_query($mysqli, "SELECT t_p_descrip FROM tipo_producto WHERE cod_tipo_prod=' $codigo_tproducto'");
            $rw_tproducto = mysqli_fetch_array($sql_tproducto);
            $tproducto_nombre= $rw_tproducto['t_p_descrip'];

            $id_u_medida=$row['id_u_medida'];
            $sql_umedida = mysqli_query($mysqli, "SELECT u_descrip FROM u_medida WHERE id_u_medida=' $id_u_medida'");
            $rw_u_medida = mysqli_fetch_array($sql_umedida);
            $u_medida_nombre= $rw_u_medida['u_descrip'];

            $precio_compra_=$row['precio_tmp'];
            $precio_compra_f=number_format($precio_compra_); //Formatear una variable (poner ,)
            $precio_compra_r=str_replace(",","",$precio_compra_f);//Reemplazar la ,
            $precio_total=$precio_compra_r*$cantidad;
            $precio_total_f=number_format($precio_total);
            $precio_total_r=str_replace(",","",$precio_total_f);
            $suma_total+=$precio_total_r;//Sumador total
            ?>
            <tr>
                <td><?php echo $codigo_producto; ?></td>
                <td><?php echo $tproducto_nombre; ?></td>
                <td><?php echo $u_medida_nombre; ?></td>
                <td><?php echo $descrip_producto; ?></td>
                <td><span class="pull-right"><?php echo $cantidad; ?></span></td>
                <td><span class="pull-right"><?php echo $precio_total_f; ?></span></td>
                <td><span class="pull-rigth"><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
            </tr>
        <?php }
    ?> 
            <tr>
                <input type="hidden" class="form-control" name="suma_total" value="<?php echo $suma_total; ?>">
                <?php if(empty($codigo_producto)){
                    $codigo_producto=0;
                }else{
                    $codigo_producto;
                } ?>
                <input type="hidden" class="form-control" name="codigo_producto" value="<?php echo $codigo_producto; ?>">
                <?php if(empty($cantidad)){
                    $cantidad=0;
                }else{
                    $cantidad;
                } ?>
                <input type="hidden" class="form-control" name="cantidad" value="<?php echo $cantidad; ?>">
                <td colspan=4><span class="pull-right">Total Gs.</span></td>
                <td><strong><span class="pull-right"><?php echo number_format($suma_total) ?></span></strong></td>
            </tr>
</table>
