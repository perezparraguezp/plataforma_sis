<?php

include '../../../../php/config.php';
include '../../../../php/objetos/profesional.php';

session_start();
$myId = $_SESSION['id_usuario'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$pass = $_POST['password'];

$profesional = new profesional($myId);

$profesional->updateClave($pass);
$profesional->updateDatosPersonales('nombre_completo',$nombre);
$profesional->updateDatosPersonales('telefono',$telefono);
$profesional->updateDatosPersonales('email',$correo);