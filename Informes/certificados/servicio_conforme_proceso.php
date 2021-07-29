<?php
include("../../php/config.php");
include '../../php/objetos/functionario.php';
include '../../php/objetos/persona.php';
include '../../php/objetos/certificado.php';
//include '../../php/objetos/compra.php';
include '../../php/objetos/documento.php';
include '../../php/objetos/documento_dte.php';
include '../../php/objetos/proceso_compra.php';


session_start();
error_reporting(0);
//Eliminamos los textos del documento
function rut( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}
$id_empleado = $_SESSION['id_empleado'];
$id_proceso = $_POST['id_proceso'];
$id_adquisicion = $_POST['listado_adquisiciones'];
$id_documento = $_POST['dte'];

$documento = new documento('CERTIFICADO DE RECEPCION','Municipalidad de Carahue','Portales 295, Carahue');
$documento->updateTipoDocumento('CERTIFICADO DE RECEPCION','ADQUISICIONES - SERVICIOS');
$documento->crearFolio();

//ATRIBUTOS DEL PROCESO DE COMPRA
$proceso = new proceso_compra();
$proceso ->cargarProcesoCompra($id_proceso);
$nombre_proceso = $proceso -> nombre_proceso;
$nombre_atributo_compras = $proceso -> atributo_mecanismo;
$nombre_atributo_compras = mysql_fetch_array(mysql_query("select nombre_atributo from compras_atributos_mecanismo where id_atributo = '$nombre_atributo_compras'"));
$nombre_atributo_compras = $nombre_atributo_compras['nombre_atributo'];
$mecanismo = $proceso -> mecanismo_compra;
$mecanismo = mysql_fetch_array(mysql_query("select nombre_atributo from compras_atributos_mecanismo where id_atributo = '$mecanismo'"));
$mecanismo = $mecanismo['nombre_atributo'];

$solicitante = $proceso -> id_solicitante;
$encargado = $proceso -> id_directivo;
$id_departamento = $proceso -> id_depto;
//----------------------------------


//CONSULTA ATRIBUTOS COMPRA Y ADQUISICION
$sql = mysql_fetch_array(mysql_query("select id_adquisicion,rut_proveedor, razon_social, numero_decreto, anio_decreto from adquisicion_proceso_compra
    inner join adjudicacion_proceso_compra using (id_adjudicacion)
    inner join decretos on adquisicion_proceso_compra.folio_decreto = id_interno
    inner join proveedor using (rut_proveedor)
    inner join proceso_compra using (id_proceso_compra)
    where id_adquisicion = '$id_adquisicion'"));
$rut_proveedor = $sql['rut_proveedor'];
$razon_social = $sql['razon_social'];
$numero_decreto = $sql['numero_decreto'];
$anio_decreto = $sql['anio_decreto'];
//----------------------------------------

$documento->updateDatosDocumento('','','Recepcion de Servicio '.$proceso->nombre_proceso);


$tabla_facturas ='
        <table style="width: 100%;font-size: 0.8em;" width="100%" border="1px">
            <tr style="background-color: #d7efff;font-weight: bold;">
            <td>Tipo Documento</td>
            <td>Fecha Emision</td>
            <td>Numero Documento</td>
            <td>Monto Documento</td>
</tr>';

foreach ($id_documento as $i => $id){
    $dte = new documento_dte();
    $dte->cargar_dte($id);
    $tabla_facturas.='<tr>
            <td>'.($dte->tipo_documento).'</td>
            <td>'.fechaNormal($dte->fecha_emision).'</td>
            <td>'.$dte->folio.'</td>
            <td>$ '.number_format($dte->monto_total,0,'','.').'</td>
        </tr>';

//    $dte->registrarEstado('RECEPCION CONFORME');
//    $dte->registrarRecepcion();
}
$tabla_facturas .= '</table>';

$documento->NumerarDocumento(date('Y'));



$numero_certificado = $documento->numero_decreto."/".date('Y');

//DIRECTIVO Y SOLICITANTE
if($encargado==''){
    $sql_jefe = "select * from funcionario 
                where id_depto='".$id_departamento."' 
                and activo='SI' and grado!=0 and planta_municipal='PLANTA' 
                order by grado ASC LIMIT 1;";
    $row_jefe = mysql_fetch_array(mysql_query($sql_jefe));
    $director = new functionario($row_jefe['reloj']);
}else{
    $id_directivo = $encargado;
    $director = new functionario($id_directivo);
}

if($solicitante!=''){
    $solicitante = new functionario($solicitante);
    $nombre_solicitante = $solicitante->nombre_completo;
}else{
    $nombre_solicitante = '';
}



$monto_factura = $dte->monto_total;
$monto_compra = "$ ".number_format($monto_factura,0,'','.');

$decreto_compra = $row1['decreto_aprueba_compra'];

$oc = $row1['orden_compra'];


$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = 2019;//date('Y');
$m = 12;//date('m');
$d = 31;//date('d');

$dia = diaSemana('2019', '12', '31');

$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;


$html = '
<style type="text/css">
    span{
        text-align:left;
        text-indent: 300px;
        font-size:12pt;
        margin-top: 0px;
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    p{
        font-size:10pt;
        text-align: justify;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h5{
    font-size: 0.8em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    }
    h4{
    text-align: center;
    }
    h3{
    font-size: .7em;;
    text-align: center;
    }
</style>
<strong style="text-align:right;text-indent: 200px;font-size: 0.8em;">'.$fecha.'</strong>
<h4>Certificado de Recepción<br /> N° '.$numero_certificado.'</h4>
<p></p>
<p>Quien suscribe certifica haber recibido conforme en cuando a la calidad y cantidad contratados del Servicio <strong>"'.trim($nombre_proceso).'"</strong> 
prestado por el proveedor <strong>'.$razon_social.'</strong> cuyo RUT es <strong> '.$rut_proveedor.'</strong>, el cual se registró mediante
 el Decreto de Compra Nº <strong>'.$numero_decreto.'</strong> del año <strong>'.$anio_decreto.'</strong>, mediante el proceso de compras llamado  
 "<strong>'.trim($nombre_atributo_compras).'</strong>" basado en el mecanismo de compra "<strong>'.$mecanismo.'</strong>".
  <br />El proveedor realizo la emision del los siguientes documentos tributarios para solicitar el pago del servicio prestado:</p>
  '.$tabla_facturas.'
 <p></p>
<p>Esté Servicio se realizo según las especificaciones descritas a continuación:</p>
<p></p>
<table style="width: 100%;font-size: 0.8em;" width="100%" border="1px">
<tr>
<td style="width: 40%;">Fecha Inicio</td>
<td style="width: 60%;"><p></p><p></p></td>
</tr>
<tr>
<td>Fecha Termino</td>
<td><p></p><p></p></td>
</tr>
<tr>
<td>Ubicación</td>
<td><p></p><p></p><p></p></td>
</tr>
<tr>
<td>Documentos Adjuntos
<p style="font-size: 0.6em;">El funcionario podrá adjuntar documentación anexa, la cual pueda servir de evidencia al momento de realizar un control del servicio prestado por el proveedor.</p>
</td>
<td>
    <table>
        <tr><td></td><td></td></tr>
        <tr><td>LISTA DE ASISTENCIA</td><td>____</td></tr>
        <tr><td>FOTOGRAFIAS</td><td>____</td></tr>
        <tr><td>DOCUMENTOS</td><td>____</td></tr>
        <tr><td>OTROS</td><td>____</td></tr>
        <tr><td></td><td></td></tr>
    </table>
</td>
</tr>
<tr>
<td>Responsable Certificado</td>
<td><p></p><p>'.$nombre_solicitante.'</p><p></p></td>
</tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<table width="100%" style="font-size: 1em;">
<tr>
    <td><h3>'.$director->nombre_completo.'<br />Director</h3></td>
    <td><h3>'.$nombre_solicitante.'<br />Firma Responsable</h3></td>
</tr>
</table>
';

//$dte->update_estado('ASIGNADO');

//$dte->asignarCompra($id_compra);




//$compra->insertCertificadoRecepcion($certificado->id_certificado);
//$compra->insertDocumentoDTE($dte->rut,$dte->folio);
//$compra->updateRecepcion($certificado->id_certificado);

$documento->CrearPDF($html);





