<?php
include '../../config.php';
include '../../objetos/mysql.php';

$id_tipo = $_POST['tipo_agrupacion'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

$mysql = new mysql($_SESSION['id_usuario']);

$rut_presidente = $_POST['rut_presidente'];
$nombre_presidente = $_POST['nombre_presidente'];
$telefono_presidente = $_POST['telefono_presidente'];

$mysql->insert_persona($rut_presidente,$nombre_presidente,$telefono_presidente);

$rut_tesorero = $_POST['rut_tesorero'];
$nombre_tesorero = $_POST['nombre_tesorero'];
$telefono_tesorero = $_POST['telefono_tesorero'];

$mysql->insert_persona($rut_tesorero,$nombre_tesorero,$telefono_tesorero);

$rut_secretario = $_POST['rut_secretario'];
$nombre_secretario = $_POST['nombre_secretario'];
$telefono_secretario = $_POST['telefono_secretario'];

$mysql->insert_persona($rut_secretario,$nombre_secretario,$telefono_secretario);

$id_establecimiento = $_SESSION['id_establecimiento'];
$mysql->insert_agrupacion_escolar($id_tipo,$desde,$hasta,$rut_presidente,$rut_tesorero,$rut_secretario,$id_establecimiento);
if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
