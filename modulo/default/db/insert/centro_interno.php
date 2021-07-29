<?php


include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
include '../../../../php/objetos/persona.php';

$nombre_centro = $_POST['nombre_centro'];
$direccion     = $_POST['direccion_centro'];
$telefono     = $_POST['telefono_centro'];
$email        = $_POST['email_centro'];
$sector_comunal        = $_POST['sector_comunal'];

$mysql = new mysql($_SESSION['id_usuario']);

$mysql->insert_centro_interno($sector_comunal,$nombre_centro,$direccion,$telefono,$email);

if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
