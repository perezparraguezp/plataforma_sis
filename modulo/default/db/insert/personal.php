<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
include '../../../../php/objetos/persona.php';


$id_establecimiento = $_SESSION['id_establecimiento'];

$tipo_contrato   = $_POST['tipo_contrato'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$indefinido = $_POST['indefinido'];
$rut = str_replace(".","",$_POST['rut']);//limpiamos caracteres
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$horas = $_POST['horas'];

$mysql = new mysql($_SESSION['id_usuario']);
$mysql->insert_persona($rut,$nombre,$telefono,$email);
$p = new persona($rut);

if($_POST['indefinido']){
    $hasta = 'INDEFINIDO';
}
$p->insert_contrato($rut,$tipo_contrato,$desde,$hasta,$horas,$id_establecimiento);
$id_profesional = $p->getIDProfesional($rut,$id_establecimiento);
$p->insertUsuario($rut,$id_establecimiento,$tipo_contrato,$id_profesional);
if($email!=''){
    $para      = $email;
    $mensaje   = '';
    $titulo    = 'BIENVENIDO A SIS EH-OPEN';
    $mensaje   = 'Hola';
    $cabeceras = 'From: soporte@eh-open.com' . "\r\n" .
        'Reply-To: soporte@eh-open.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($para, $titulo, $mensaje, $cabeceras);
}


