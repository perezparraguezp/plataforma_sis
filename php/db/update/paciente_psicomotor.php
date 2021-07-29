<?php
include "../../config.php";
include "../../objetos/persona.php";

$val = $_POST['val'];
$column = $_POST['column'];
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];


$paciente = new persona($rut);
$paciente->update_Psicomotor($column,$val,$fecha_registro);

echo "ACTUALIZADO";