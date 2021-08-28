<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$tipo = $_POST['tipo_hormona'];
$desde = $_POST['desde'];
$obs = $_POST['obs'];

$paciente = new persona($rut);

$paciente->insert_hormona_reemplazo($tipo,$desde,$obs);