<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/proveedor.php");
include("../../php/class/decreto.php");
include("../../php/objetos/documento.php");

$folio = $_GET['folio'];
$documento = new documento('','','Documento Municipal');
$documento->cargarFolio($folio);
$documento->RecuperarDocumento();

$documento->CrearPDF($documento->html);


