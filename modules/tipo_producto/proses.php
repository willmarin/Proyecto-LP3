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
            $t_p_descrip = mysqli_real_escape_string($mysqli, trim($_POST['t_p_descrip']));

            if($t_p_descrip == ''){
                header("Location: ../../main.php?module=tipo_producto&alert=4");
                exit;
            }

            $query = mysqli_query($mysqli, "INSERT INTO tipo_producto (cod_tipo_prod, t_p_descrip)
            VALUES ($codigo, '$t_p_descrip')") or die ('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=tipo_producto&alert=1");
            }else{
                header("Location: ../../main.php?module=tipo_producto&alert=4");
            }
        }
    }
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['codigo'])){
                $codigo = intval($_POST['codigo']);
                $t_p_descrip = mysqli_real_escape_string($mysqli, trim($_POST['t_p_descrip']));

                if($t_p_descrip == ''){
                    header("Location: ../../main.php?module=tipo_producto&alert=4");
                    exit;
                }

                $query = mysqli_query($mysqli, "UPDATE tipo_producto SET t_p_descrip = '$t_p_descrip'
                                                                    WHERE cod_tipo_prod = $codigo")
                                                                    or die ('Error'.mysqli_error($mysqli));

                if($query){
                    header("Location: ../../main.php?module=tipo_producto&alert=2");
                }else{
                    header("Location: ../../main.php?module=tipo_producto&alert=4");
                }
            }
        }
    }
    elseif($_GET['act']=='delete'){
        if(isset($_GET['cod_tipo_prod'])){
            $codigo = intval($_GET['cod_tipo_prod']);

            $query = mysqli_query($mysqli, "DELETE FROM tipo_producto
                                            WHERE cod_tipo_prod = $codigo");

            if($query){
                header("Location: ../../main.php?module=tipo_producto&alert=3");
            }else{
                header("Location: ../../main.php?module=tipo_producto&alert=4");
            }
        }
    }
}
?>
