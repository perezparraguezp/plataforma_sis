<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id = $_POST['id'];

$paciente = new persona($rut);
$paciente->deleteHormonaReemplazo($id);
echo 'REGISTRO ELIMINADO';