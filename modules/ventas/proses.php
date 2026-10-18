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
                $codigo_cliente = intval($_POST['codigo_cliente']);
                $fecha = mysqli_real_escape_string($mysqli, trim($_POST['fecha']));
                $hora = mysqli_real_escape_string($mysqli, trim($_POST['hora']));
                $nro_factura = intval($_POST['nro_factura']);
                $estado = 'activo';
                //El usuario se toma siempre de la sesión, nunca de un campo del formulario
                $usuario = intval($_SESSION['id_user']);
                $session_id = session_id();

                //Validación de los datos de cabecera
                if($codigo_deposito <= 0 || $codigo_cliente <= 0 || $nro_factura <= 0){
                    header("Location: ../../main.php?module=ventas&alert=3");
                    exit;
                }

                //Convertir la fecha de dd-mm-yyyy a yyyy-mm-dd para la base de datos
                $fecha_partes = explode('-', $fecha);
                if(count($fecha_partes) == 3){
                    $fecha = $fecha_partes[2].'-'.$fecha_partes[1].'-'.$fecha_partes[0];
                }

                //Traer el carrito (tmp) correspondiente solamente a esta sesión
                $carrito = mysqli_query($mysqli, "SELECT * FROM producto, tmp
                                                    WHERE producto.cod_producto = tmp.id_producto
                                                    AND tmp.session_id = '$session_id'");

                if(mysqli_num_rows($carrito) == 0){
                    //No se puede registrar una venta sin productos
                    header("Location: ../../main.php?module=ventas&alert=3");
                    exit;
                }

                //1) Armar la lista de items consolidando por producto (por si el carrito
                //   tuviera más de una fila para el mismo producto), calcular el total
                //   en el servidor y validar que exista stock suficiente en el depósito
                //   elegido antes de descontar nada
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

                foreach($items as $item){
                    $codigo_producto = $item['cod_producto'];
                    $cantidad = $item['cantidad'];

                    $stock_actual = 0;
                    $query_stock = mysqli_query($mysqli, "SELECT cantidad FROM stock
                                                            WHERE cod_producto = $codigo_producto
                                                            AND cod_deposito = $codigo_deposito");
                    if(mysqli_num_rows($query_stock) > 0){
                        $data_stock = mysqli_fetch_assoc($query_stock);
                        $stock_actual = $data_stock['cantidad'];
                    }

                    if($cantidad > $stock_actual){
                        //No hay stock suficiente, se cancela la operación (no se modifica nada)
                        header("Location: ../../main.php?module=ventas&alert=4");
                        exit;
                    }
                }

                //2) Insertar cabecera de venta
                $insert_venta = mysqli_query($mysqli, "INSERT INTO venta (cod_venta, id_cliente, fecha, hora,
                nro_factura, estado, total_venta, id_user)
                VALUES ($codigo, $codigo_cliente, '$fecha', '$hora', $nro_factura, '$estado', $suma_total, $usuario)");

                //3) Insertar detalle de venta y descontar stock
                foreach($items as $item){
                    $codigo_producto = $item['cod_producto'];
                    $precio = $item['precio'];
                    $cantidad = $item['cantidad'];

                    //Segunda capa de seguridad: si por algún motivo ya existiera una fila
                    //para este producto en esta venta, se suma en vez de fallar por clave duplicada
                    mysqli_query($mysqli, "INSERT INTO det_venta (cod_producto, cod_venta, cod_deposito,
                    det_precio_unit, det_cantidad) VALUES ($codigo_producto, $codigo, $codigo_deposito, $precio, $cantidad)
                    ON DUPLICATE KEY UPDATE det_cantidad = det_cantidad + VALUES(det_cantidad), det_precio_unit = VALUES(det_precio_unit)");

                    mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad - $cantidad
                    WHERE cod_producto = $codigo_producto
                    AND cod_deposito = $codigo_deposito");
                }

                //4) Vaciar el carrito temporal de esta sesión
                mysqli_query($mysqli, "DELETE FROM tmp WHERE session_id = '$session_id'");

                if($insert_venta){
                    header("Location: ../../main.php?module=ventas&alert=1");
                }else{
                    header("Location: ../../main.php?module=ventas&alert=3");
                }
            }catch(\Throwable $e){
                //Cualquier error inesperado de base de datos (ej. una restricción que
                //no se contempló) se registra en el log del servidor y se muestra un
                //aviso genérico en vez de una pantalla de error de PHP
                error_log('Error al registrar venta: '.$e->getMessage());
                header("Location: ../../main.php?module=ventas&alert=3");
                exit;
            }
        }
    }
    elseif($_GET['act']=='anular'){
        if(isset($_GET['cod_venta'])){
            try{
                $codigo = intval($_GET['cod_venta']);

                //Verificar que la venta esté activa antes de anular (evita anular dos veces)
                $query_estado = mysqli_query($mysqli, "SELECT estado FROM venta WHERE cod_venta = $codigo");
                $data_estado = mysqli_fetch_assoc($query_estado);

                if(!$data_estado || $data_estado['estado'] != 'activo'){
                    header("Location: ../../main.php?module=ventas&alert=3");
                    exit;
                }

                //Anular cabecera de venta (cambiar a estado anulado)
                $query = mysqli_query($mysqli, "UPDATE venta SET estado ='anulado' WHERE cod_venta=$codigo");

                //Consultar el detalle de la venta anulada y devolver la cantidad al stock
                $sql = mysqli_query($mysqli, "SELECT * FROM det_venta WHERE cod_venta=$codigo");
                while($row = mysqli_fetch_array($sql)){
                    $codigo_producto = $row['cod_producto'];
                    $codigo_deposito = $row['cod_deposito'];
                    $cantidad = $row['det_cantidad'];

                    //Si existe el registro de stock, se incrementa; si no existe (caso raro), se crea
                    $query_stock = mysqli_query($mysqli, "SELECT * FROM stock WHERE cod_producto=$codigo_producto
                    AND cod_deposito=$codigo_deposito");

                    if(mysqli_num_rows($query_stock) > 0){
                        mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad + $cantidad
                        WHERE cod_producto=$codigo_producto
                        AND cod_deposito=$codigo_deposito");
                    }else{
                        mysqli_query($mysqli, "INSERT INTO stock (cod_deposito, cod_producto, cantidad)
                        VALUES ($codigo_deposito, $codigo_producto, $cantidad)");
                    }
                }

                if($query){
                    header("Location: ../../main.php?module=ventas&alert=2");
                }else{
                    header("Location: ../../main.php?module=ventas&alert=3");
                }
            }catch(\Throwable $e){
                error_log('Error al anular venta: '.$e->getMessage());
                header("Location: ../../main.php?module=ventas&alert=3");
                exit;
            }
        }
    }
}
?>
