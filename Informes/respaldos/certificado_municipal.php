<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/proveedor.php");
include("../../php/class/decreto.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/certificado.php");

$id_certificado = $_GET['id'];
$certificado = new certificado($_SESSION['id_empleado']);
$certificado->loadCertificado($id_certificado);

$documento = new documento('Certificado Municipal','Municipalidad de Carahue','Certificado Municipal');


$documento->CrearPDF($certificado->pdf);


