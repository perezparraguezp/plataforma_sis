<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
include '../../../../php/objetos/profesional.php';

$id_establecimiento = $_SESSION['id_establecimiento'];
$id_profesional = $_POST['id_profesional'];
$id_modulo = $_POST['id_modulo'];
$activo = $_POST['activo'];
$p = new profesional($id_profesional);
$rut = $p->rut;

if($activo=='SI'){
    $sql = "insert into menu_usuario(id_establecimiento,rut,id_modulo) 
                values('$id_establecimiento','$rut','$id_modulo')";
    $msj = 'ASIGNADO EL MODULO';
}else{
    $sql = "delete from menu_usuario 
                    where id_establecimiento='$id_establecimiento' and rut='$rut' and id_modulo='$id_modulo'";
    $msj = 'MODULO ELIMINADO DEL USUARIO';
}
mysql_query($sql);
echo $msj;

