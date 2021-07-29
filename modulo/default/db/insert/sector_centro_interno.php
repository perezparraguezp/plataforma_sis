<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';



$nombre_sector = $_POST['nombre_sector'];
$id_centro_interno = $_POST['id_centro_interno'];
$mysql = new mysql($_SESSION['id_usuario']);

if($nombre_sector !=''){
    echo $nombre_sector;
    $mysql->insert_sector_interno($nombre_sector,$id_centro_interno);
    if($mysql->result==false){
        echo "ERROR_SQL";
    }else{

    }
}