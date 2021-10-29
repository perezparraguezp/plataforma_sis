<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id = $_POST['id'];
$fecha = $_POST['fecha_egreso'];
$obs = $_POST['obs_alta'];


$paciente = new persona($rut);


$paciente->Alta_Antecedente($id,$fecha,$obs);
echo 'ACTUALIZADO';