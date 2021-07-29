<?php
include '../../config.php';
include '../../objetos/mysql.php';



$nombre = $_POST['nombre'];
$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_sector_comunal($nombre);
if($mysql->result==true){
    echo 'SECTOR REGISTRADO CON EXITO';
}else{
    echo 'ERROR: INTENTELO DE NUEVO';
}

