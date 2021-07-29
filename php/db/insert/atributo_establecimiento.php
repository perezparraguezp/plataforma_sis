<?php
include '../../config.php';
include '../../objetos/mysql.php';

$id_tipo = $_POST['tipo_agrupacion'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

$mysql = new mysql($_SESSION['id_usuario']);

$tipo_atributo = $_POST['tipo_atributo'];
$nombre_atributo = $_POST['nombre_atributos'];
$texto_atributo = $_POST['descripcion_atributos'];

$mysql->insert_atributo_establecimiento($tipo_atributo,$nombre_atributo,$texto_atributo);

if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
