<?php
include '../../config.php';
include '../../objetos/mysql.php';

$id = $_POST['id'];

$mysql = new mysql($_SESSION['id_usuario']);

$mysql->delete_sector_interno($id);
if($mysql->result==true){
    echo 'SECTOR ELIMINADO';
}else{
    echo 'VUELVA A INTENTARLO';
}



