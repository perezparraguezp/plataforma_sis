<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
session_start();
$rut = $_POST['rut'];//rut paciente

$fecha = $_POST['fecha'];
$obs = $_POST['obs'];
$mrs = $_POST['valor_examen'];

$paciente = new persona($rut);

$paciente->insert_pauta_mrs($fecha,$mrs,$obs);
echo 'REGISTRADO';
