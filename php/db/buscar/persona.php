<?php
include "../../config.php";
include "../../objetos/persona.php";

$rut = $_POST['rut'];

$p = new persona($rut);
echo $p->nombre.";".$p->telefono.";".$p->direccion.";".$p->email;