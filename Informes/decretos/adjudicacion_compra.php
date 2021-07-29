<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/proceso_compra.php";
include "../../php/objetos/proveedor.php";

session_start();
$myId = $_SESSION['id_empleado'];
$id_proceso=$_POST['id_proceso'];
$garantias = $_POST['garantias'];
$codigo_licitacion = $_POST['licitacion'];
$decreto_bases = $_POST['decreto_bases'];
$proceso_compra = new proceso_compra();
$proceso_compra->cargarProcesoCompra($id_proceso);
$proveedor = explode('<br />',$_POST['proveedor']);
list($texto,$rut) = explode(":",trim($proveedor[0]));
$p = new proveedor($rut);
$cip = $_POST['cip'];
$fecha_limite = $_POST['fecha_limite'];
$monto_int = $_POST['monto'];
$monto = "$ ".number_format($_POST['monto'],0,'','.')." (".convertir_numero_a_letra($_POST['monto']).")";
$control = new functionario($_POST['control']);
$secmun = new functionario($_POST['secmun']);
$administrador = new functionario($_POST['administracion']);
$funcionario = new functionario($myId);


$f = new functionario($myId);
$titulo_superior = "Decreto de Adjudicacion                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'DECRETO ANULACION');
$submit = $_POST['submit'];
$html = $_POST['decreto'];


if($submit == 'NUMERAR DECRETO'){

    $documento->crearFolio();
    $documento->updateTipoDocumento('Decreto de Compra','Decreto de Adjudicacion');
    $documento->updateDatosDocumento($rut,$p->razon_social,$proceso_compra->nombre_proceso);
    $documento->NumerarDocumento(date('Y'));

    $html = str_replace("XXXXX",''.$documento->numero_decreto,$html);//numerar decreto
    $html = str_replace("XYXYX",''.$documento->folio,$html);//foliar decreto

    $ruta = "Informes/respaldos/folio.php?folio=".$documento->folio;

    $proceso_compra->addExpediente($documento->folio,$documento->detalle_documento,$documento->numero_decreto);
    $proceso_compra->adjudicar($rut,$cip,$documento->folio,$monto_int,$fecha_limite,$garantias);
    $texto_historial = "Se realiza la adjudicacion al Proveedor ".$p->razon_social.", realizada por el usuario ".$funcionario->nombre_completo.", con fecha  ".date('d-m-Y');
    //generar relacion entre proceso y proveedor
    $proceso_compra->insertHistorialProcesoCompra($texto_historial);


}else{
    $html = trim($html).$submit;
}
//echo $html;
$documento->CrearPDF($html);