<?php
include '../../config.php';
include '../../objetos/mysql.php';
$documento = $_POST['documento'];
$descripcion = $_POST['descripcion'];

$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_tipo_documento($documento,$descripcion);
if($mysql->result==false){
    echo "ERROR_SQL";
}
