<?php
include "../../config.php";
include "../../objetos/persona.php";

$val = $_POST['val'];
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];


$paciente = new persona($rut);
$paciente->update_scoreIRA($val,$fecha_registro);

echo "ACTUALIZADO";