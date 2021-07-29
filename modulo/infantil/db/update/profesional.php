<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
include '../../../../php/objetos/profesional.php';

$rut = $_POST['rut'];

$p = new persona($rut);
$profesional = new profesional($_POST['id_profesional']);

$p->update_datosPersonal('nombre_completo',$_POST['nombre']);
$p->update_datosPersonal('email',$_POST['email']);
$p->update_datosPersonal('telefono',$_POST['telefono']);
$p->update_datosPersonal('direccion',$_POST['direccion']);

$profesional->updateClave($_POST['clave']);
echo 'ACTUALIZADO';