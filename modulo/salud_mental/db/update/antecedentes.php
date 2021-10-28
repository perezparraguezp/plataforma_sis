<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$column = $_POST['column'];
$value = $_POST['value'];
$fecha = $_POST['fecha'];

$persona = new persona($rut);

$persona->update_antecedentes_sm($column,$value,$fecha);