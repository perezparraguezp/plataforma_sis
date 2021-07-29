<?php
include '../../config.php';
include '../../objetos/mysql.php';
include '../../objetos/establecimiento.php';


$rut   = $_POST['rut_es'];
$tipo   = $_POST['tipo'];
$comuna = $_POST['comuna'];
$nombre = $_POST['nombre_es'];
$direccion = $_POST['dire_es'];
$email = $_POST['mail_es'];
$telefono = $_POST['tel_es'];
list($dia,$mes,$anio) = explode("/",$_POST['fecha']);
$fecha = $anio."-".$mes."-".$dia;


$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_establecimiento($rut,$tipo,$comuna,$nombre,$direccion,$telefono,$email);
if($mysql->result==false){
    echo "ERROR_SQL";
}else{

}
