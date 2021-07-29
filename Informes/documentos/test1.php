<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/persona.php";
session_start();

$doc = new documento('T1','T2','T3');

$doc->crearCabeceraPagina();

$doc->addPagina('hola pagina 1');
$doc->addPagina('hola pagina 2');
$doc->addPagina('hola pagina 3');

$doc->outDocuemnto();
