<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/proceso_compra.php";
include "../../php/objetos/proveedor.php";

session_start();
$myId = $_SESSION['id_empleado'];
$id_proceso = $_POST['id_proceso'];
$orden_compra = $_POST['orden_compra'];
$id_proceso = $_POST['id_proceso'];
$proceso_compra = new proceso_compra();
$proceso_compra->cargarProcesoCompra($id_proceso);
$proveedor = explode('<br />',$_POST['proveedor']);
list($texto,$rut) = explode(":",trim($proveedor[0]));
$p = new proveedor($rut);
$cip = $_POST['cip'];
$monto = "$ ".number_format($_POST['monto'],0,'','.')." (".convertir_numero_a_letra($_POST['monto']).")";
$control = new functionario($_POST['control']);
$secmun = new functionario($_POST['secmun']);
$administrador = new functionario($_POST['administracion']);

$aprueba_modifica = $_POST['aprueba_modifica'];

$f = new functionario($myId);
$titulo_superior = $aprueba_modifica;
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'DECRETO ANULACION');
$submit = $_POST['submit'];
$html = $_POST['decreto'];
if($submit == 'NUMERAR DECRETO'){

    $documento->crearFolio();
    $documento->updateTipoDocumento('Decreto de Compra',$aprueba_modifica);
    $documento->updateDatosDocumento($rut,$p->razon_social,$proceso_compra->nombre_proceso);
    $documento->NumerarDocumento(date('Y'));
    $html = str_replace("XXXXX",''.$documento->numero_decreto,$html);
    $html = str_replace("XYXYX",''.$documento->folio,$html);

    $proceso_compra->addExpediente($documento->folio,$documento->detalle_documento,$documento->numero_decreto);

}else{
    $html = trim($html).$submit;
}


//echo $html;
$documento->CrearPDF($html);