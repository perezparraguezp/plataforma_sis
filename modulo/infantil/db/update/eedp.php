<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

$val = $_POST['val'];
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);
$paciente->update_eedp($val,$fecha_registro);
if($val=='NORMAL'){
    $paciente->update_eedp_lenguaje($val,$fecha_registro);
    $paciente->update_eedp_motrocidad($val,$fecha_registro);
    $paciente->update_eedp_social($val,$fecha_registro);
    $paciente->update_eedp_coordinacion($val,$fecha_registro);
}
echo "ACTUALIZADO";