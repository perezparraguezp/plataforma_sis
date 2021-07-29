<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/proceso_compra.php";
include "../../php/objetos/proveedor.php";

session_start();
$myId = $_SESSION['id_empleado'];
$id_proceso = $_POST['id_proceso'];
$decreto_adjudicacion = $_POST['decreto_adjudicacion'];
$proceso_compra = new proceso_compra();
$proceso_compra->cargarProcesoCompra($id_proceso);
$orden_compra = $_POST['orden_compra'];
$monto_int = $_POST['monto'];
$monto = "$ ".number_format($_POST['monto'],0,'','.')." (".convertir_numero_a_letra($_POST['monto']).")";
$control = new functionario($_POST['control']);
$secmun = new functionario($_POST['secmun']);
$administrador = new functionario($_POST['administracion']);
$funcionario = new functionario($myId);

$f = new functionario($myId);
$titulo_superior = "Decreto de Adquisicion                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'DECRETO ANULACION');
$submit = $_POST['submit'];
$html = $_POST['decreto'];
$unidad = $_POST['unidad1'];

$sql = mysql_fetch_array(mysql_query("select id_adjudicacion from adjudicacion_proceso_compra 
                                                        where folio_documento='$decreto_adjudicacion' limit 1"));

$id_adjudicacion = $sql['id_adjudicacion'];

if($submit == 'NUMERAR DECRETO'){
    $documento->crearFolio();
    $documento->updateTipoDocumento('Decreto de Compra','Decreto de Adquisicion');
    $documento->updateDatosDocumento($rut,$p->razon_social,$proceso_compra->nombre_proceso);
    $documento->NumerarDocumento(date('Y'));

    $html = str_replace("XXXXX",''.$documento->numero_decreto,$html);//numerar decreto
    $html = str_replace("XYXYX",''.$documento->folio,$html);//foliar decreto

    $ruta = "Informes/respaldos/folio.php?folio=".$documento->folio;



    $proceso_compra->addExpediente($documento->folio,$documento->detalle_documento,$documento->numero_decreto);//decreto de compra

    $proceso_compra->adquirir($id_adjudicacion,$documento->folio,$monto_int,$orden_compra,$unidad);
    $texto_historial = "Se realiza la adquisicion al Proveedor ".$p->razon_social.", realizada por el usuario ".$funcionario->nombre_completo.", con fecha  ".date('d-m-Y');
    //generar relacion entre proceso y proveedor
    $proceso_compra->insertHistorialProcesoCompra($texto_historial);


}else{
    $html = trim($html).$submit;
}
//echo $html;
$documento->CrearPDF($html);