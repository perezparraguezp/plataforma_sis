<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['value'];
$fecha = $_POST['fecha_registro'];
$p = new persona($rut);
if($p->existe==true){
    $p->update_diabetes_pscv($column,$value,$fecha);
    echo 'ACTUALIZADO';
}else{
    echo 'PROBLEMAS AL REGISTRAR EL CAMBIO';
}
