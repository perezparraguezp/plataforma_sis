<?php
include "../../config.php";
include "../../objetos/persona.php";

$val = $_POST['val'];
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

if($val=='true'){
    $val='SI';
}else{
    $val='NO';
}
$paciente = new persona($rut);
$paciente->update_dental_ges6($val,$fecha_registro);

echo "ACTUALIZADO";