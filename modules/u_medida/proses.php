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
            $u_descrip = mysqli_real_escape_string($mysqli, trim($_POST['u_descrip']));

            if($u_descrip == ''){
                header("Location: ../../main.php?module=u_medida&alert=4");
                exit;
            }

            $query = mysqli_query($mysqli, "INSERT INTO u_medida (id_u_medida, u_descrip)
            VALUES ($codigo, '$u_descrip')") or die ('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=u_medida&alert=1");
            }else{
                header("Location: ../../main.php?module=u_medida&alert=4");
            }
        }
    }
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['codigo'])){
                $codigo = intval($_POST['codigo']);
                $u_descrip = mysqli_real_escape_string($mysqli, trim($_POST['u_descrip']));

                if($u_descrip == ''){
                    header("Location: ../../main.php?module=u_medida&alert=4");
                    exit;
                }

                $query = mysqli_query($mysqli, "UPDATE u_medida SET u_descrip = '$u_descrip'
                                                                    WHERE id_u_medida = $codigo")
                                                                    or die ('Error'.mysqli_error($mysqli));

                if($query){
                    header("Location: ../../main.php?module=u_medida&alert=2");
                }else{
                    header("Location: ../../main.php?module=u_medida&alert=4");
                }
            }
        }
    }
    elseif($_GET['act']=='delete'){
        if(isset($_GET['id_u_medida'])){
            $codigo = intval($_GET['id_u_medida']);

            $query = mysqli_query($mysqli, "DELETE FROM u_medida
                                            WHERE id_u_medida = $codigo");

            if($query){
                header("Location: ../../main.php?module=u_medida&alert=3");
            }else{
                header("Location: ../../main.php?module=u_medida&alert=4");
            }
        }
    }
}
?>
