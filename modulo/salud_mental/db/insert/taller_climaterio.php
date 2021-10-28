<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
session_start();
$rut = $_POST['rut'];//rut paciente

$fecha = $_POST['fecha'];
$obs = $_POST['obs'];


$paciente = new persona($rut);

$paciente->insertTALLER_CLIMATERIO_M($fecha,$obs);
echo 'REGISTRADO';
