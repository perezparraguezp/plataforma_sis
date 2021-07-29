<?php

include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
include '../../../../php/objetos/establecimiento.php';


$id_centro_interno = $_POST['id_centro_interno'];
$sector_comunal = $_POST['sector_comunal'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];




$mysql = new mysql($_SESSION['id_usuario']);
$mysql->update_establecimiento($id_centro_interno,$nombre,$direccion,$telefono,$email,$sector_comunal);
if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
