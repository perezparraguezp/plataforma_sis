<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

$val = $_POST['val'];
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
if($val=='true'){
    $val='NORMAL';
}else{
    $val='ALTERADO';
}
$paciente = new persona($rut);
$paciente->update_eedp_motrocidad($val,$fecha_registro);

echo "ACTUALIZADO";