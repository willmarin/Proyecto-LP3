<?php
session_start();

require_once '../../config/database.php';

if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){
            try{
                $codigo = intval($_POST['codigo']);
                $codigo_deposito = intval($_POST['codigo_deposito']);
                $codigo_proveedor = intval($_POST['codigo_proveedor']);
                $fecha = mysqli_real_escape_string($mysqli, trim($_POST['fecha']));
                $hora = mysqli_real_escape_string($mysqli, trim($_POST['hora']));
                $nro_factura = mysqli_real_escape_string($mysqli, trim($_POST['nro_factura']));
                $estado = 'activo';
                //El usuario se toma siempre de la sesión, nunca de un campo del formulario
                $usuario = intval($_SESSION['id_user']);
                $session_id = session_id();

                //Validación de los datos de cabecera
                if($codigo_deposito <= 0 || $codigo_proveedor <= 0 || $nro_factura == ''){
                    header("Location: ../../main.php?module=compras&alert=3");
                    exit;
                }

                //Convertir la fecha de dd-mm-yyyy (formato del datepicker) a yyyy-mm-dd para la base de datos
                $fecha_partes = explode('-', $fecha);
                if(count($fecha_partes) == 3){
                    $fecha = $fecha_partes[2].'-'.$fecha_partes[1].'-'.$fecha_partes[0];
                }

                //Traer el carrito (tmp) correspondiente solamente a esta sesión
                $carrito = mysqli_query($mysqli, "SELECT * FROM producto, tmp
                                                    WHERE producto.cod_producto = tmp.id_producto
                                                    AND tmp.session_id = '$session_id'");

                if(mysqli_num_rows($carrito) == 0){
                    //No se puede registrar una compra sin productos
                    header("Location: ../../main.php?module=compras&alert=3");
                    exit;
                }

                //Armar la lista de items consolidando por producto (por si el carrito
                //tuviera más de una fila para el mismo producto) y calcular el total
                //en el servidor (no confiar en el total enviado por el navegador)
                $items = array();
                while($row = mysqli_fetch_array($carrito)){
                    $codigo_producto = intval($row['id_producto']);
                    $precio = intval($row['precio_tmp']);
                    $cantidad = intval($row['cantidad_tmp']);

                    if(isset($items[$codigo_producto])){
                        //Ya había una fila de este producto: se suman las cantidades
                        $items[$codigo_producto]['cantidad'] += $cantidad;
                        $items[$codigo_producto]['precio'] = $precio;
                    }else{
                        $items[$codigo_producto] = array('cod_producto'=>$codigo_producto, 'precio'=>$precio, 'cantidad'=>$cantidad);
                    }
                }

                $suma_total = 0;
                foreach($items as $item){
                    $suma_total += $item['precio'] * $item['cantidad'];
                }

                //Insertar cabecera de compra
                $insert_compra = mysqli_query($mysqli, "INSERT INTO compra (cod_compra, cod_proveedor, cod_deposito,
                nro_factura, fecha, hora, estado, total_compra, id_user)
                VALUES ($codigo, $codigo_proveedor, $codigo_deposito, '$nro_factura', '$fecha', '$hora', '$estado', $suma_total, $usuario)");

                //Insertar detalle de compra y actualizar/crear stock
                foreach($items as $item){
                    $codigo_producto = $item['cod_producto'];
                    $precio = $item['precio'];
                    $cantidad = $item['cantidad'];

                    //Segunda capa de seguridad: si por algún motivo ya existiera una fila
                    //para este producto en esta compra, se suma en vez de fallar por clave duplicada
                    mysqli_query($mysqli, "INSERT INTO detalle_compra (cod_producto, cod_compra, cod_deposito,
                    precio, cantidad) VALUES ($codigo_producto, $codigo, $codigo_deposito, $precio, $cantidad)
                    ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad), precio = VALUES(precio)");

                    $query_stock = mysqli_query($mysqli, "SELECT * FROM stock WHERE cod_producto=$codigo_producto
                    AND cod_deposito=$codigo_deposito");

                    if(mysqli_num_rows($query_stock) == 0){
                        mysqli_query($mysqli, "INSERT INTO stock (cod_deposito, cod_producto, cantidad)
                        VALUES ($codigo_deposito, $codigo_producto, $cantidad)");
                    }else{
                        mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad + $cantidad
                        WHERE cod_producto=$codigo_producto
                        AND cod_deposito=$codigo_deposito");
                    }
                }

                //Vaciar el carrito temporal de esta sesión
                mysqli_query($mysqli, "DELETE FROM tmp WHERE session_id = '$session_id'");

                if($insert_compra){
                    header("Location: ../../main.php?module=compras&alert=1");
                }else{
                    header("Location: ../../main.php?module=compras&alert=3");
                }
            }catch(\Throwable $e){
                //Cualquier error inesperado de base de datos se registra en el log del
                //servidor y se muestra un aviso genérico en vez de una pantalla de error de PHP
                error_log('Error al registrar compra: '.$e->getMessage());
                header("Location: ../../main.php?module=compras&alert=3");
                exit;
            }
        }
    }
    elseif($_GET['act']=='anular'){
        if(isset($_GET['cod_compra'])){
            try{
                $codigo = intval($_GET['cod_compra']);

                //Verificar que la compra esté activa antes de anular (evita anular dos veces)
                $query_estado = mysqli_query($mysqli, "SELECT estado FROM compra WHERE cod_compra = $codigo");
                $data_estado = mysqli_fetch_assoc($query_estado);

                if(!$data_estado || $data_estado['estado'] != 'activo'){
                    header("Location: ../../main.php?module=compras&alert=3");
                    exit;
                }

                //Anular cabecera de compra (cambiar a estado anulado)
                $query = mysqli_query($mysqli, "UPDATE compra SET estado ='anulado' WHERE cod_compra=$codigo");

                //Consultar el detalle de la compra anulada y descontar del stock lo que había ingresado
                $sql = mysqli_query($mysqli, "SELECT * FROM detalle_compra WHERE cod_compra=$codigo");
                while($row = mysqli_fetch_array($sql)){
                    $codigo_producto = $row['cod_producto'];
                    $codigo_deposito = $row['cod_deposito'];
                    $cantidad = $row['cantidad'];

                    mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad - $cantidad
                    WHERE cod_producto=$codigo_producto
                    AND cod_deposito=$codigo_deposito");
                }

                if($query){
                    header("Location: ../../main.php?module=compras&alert=2");
                }else{
                    header("Location: ../../main.php?module=compras&alert=3");
                }
            }catch(\Throwable $e){
                error_log('Error al anular compra: '.$e->getMessage());
                header("Location: ../../main.php?module=compras&alert=3");
                exit;
            }
        }
    }
}

?>
