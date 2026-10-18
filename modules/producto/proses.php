<?php
session_start();
require_once "../../config/database.php";

if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){
            $codigo = intval($_POST['codigo']);
            $tipo_producto = intval($_POST['tipo_producto']);
            $u_medida = intval($_POST['u_medida']);
            $p_descrip = mysqli_real_escape_string($mysqli, trim($_POST['p_descrip']));
            $precio = intval($_POST['precio']);

            if($tipo_producto <= 0 || $u_medida <= 0 || $p_descrip == '' || $precio < 0){
                header("Location: ../../main.php?module=producto&alert=4");
                exit;
            }

            $query = mysqli_query($mysqli, "INSERT INTO producto (cod_producto, cod_tipo_prod, id_u_medida, p_descrip, precio)
            VALUES ($codigo, $tipo_producto, $u_medida, '$p_descrip', $precio)") or die ('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=producto&alert=1");
            }else{
                header("Location: ../../main.php?module=producto&alert=4");
            }
        }
    }
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['codigo'])){
                $codigo = intval($_POST['codigo']);
                $tipo_producto = intval($_POST['tipo_producto']);
                $u_medida = intval($_POST['u_medida']);
                $p_descrip = mysqli_real_escape_string($mysqli, trim($_POST['p_descrip']));
                $precio = intval($_POST['precio']);

                if($tipo_producto <= 0 || $u_medida <= 0 || $p_descrip == '' || $precio < 0){
                    header("Location: ../../main.php?module=producto&alert=4");
                    exit;
                }

                $query = mysqli_query($mysqli, "UPDATE producto SET cod_tipo_prod = $tipo_producto,
                                                                    id_u_medida = $u_medida,
                                                                    p_descrip = '$p_descrip',
                                                                    precio = $precio
                                                                    WHERE cod_producto = $codigo")
                                                                    or die ('Error'.mysqli_error($mysqli));

                if($query){
                    header("Location: ../../main.php?module=producto&alert=2");
                }else{
                    header("Location: ../../main.php?module=producto&alert=4");
                }
            }
        }
    }
    elseif($_GET['act']=='delete'){
        if(isset($_GET['cod_producto'])){
            $codigo = intval($_GET['cod_producto']);

            $query = mysqli_query($mysqli, "DELETE FROM producto
                                            WHERE cod_producto = $codigo");

            if($query){
                header("Location: ../../main.php?module=producto&alert=3");
            }else{
                header("Location: ../../main.php?module=producto&alert=4");
            }
        }
    }
}
?>
