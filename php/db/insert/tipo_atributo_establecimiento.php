<?php
include '../../config.php';
include '../../objetos/mysql.php';
$tipo = $_POST['tipo'];
$tipo = $_POST['nombre_atributos'];
$descripcion = $_POST['descripcion_atributos'];

$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_tipo_agrupacion($tipo,$descripcion);
if($mysql->result==false){
    echo "ERROR_SQL";
}
