<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];
$rut = str_replace(".","",$rut);
$p = new persona($rut);
echo $p->nombre.";".$p->telefono.";".$p->direccion.";".$p->email.";".$rut;