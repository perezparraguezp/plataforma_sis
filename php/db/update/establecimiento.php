<?php
include '../../config.php';
include '../../objetos/mysql.php';
include '../../objetos/establecimiento.php';



$id_centro_interno = $_POST['id_centro_interno'];
$sector_comunal = $_POST['sector_comunal'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];




$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_establecimiento($rut,$tipo,$comuna,$nombre,$direccion,$telefono,$email);
if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
