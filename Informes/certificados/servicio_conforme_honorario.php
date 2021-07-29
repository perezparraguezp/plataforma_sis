<?php

include("../../php/config.php");

include '../../php/objetos/functionario.php';
include '../../php/objetos/persona.php';
include '../../php/objetos/certificado.php';
include '../../php/objetos/compra.php';
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
$rut = $_POST['rut'];
$proveedor = $_POST['nombre'];
$orden_de_compra = $_POST['orden_de_compra'];
$nro_boleta = $_POST['nro_boleta'];
$fecha_boleta= $_POST['fecha_boleta'];
$bruto = $_POST['bruto'];
$neto = $_POST['neto'];
$fecha_emision = $_POST['fecha_emision'];
$impuesto = $_POST['impuesto'];


list($dB,$mB,$aB) = explode("-",fechaNormal($fecha_boleta));


//Datso de la ADQUISICION
$decreto_compra = mysql_fetch_array(mysql_query("select numero_decreto,anio from decretos inner join adquisicion_proceso_compra on folio_decreto = id_interno
                                                                where orden_de_compra = '$orden_de_compra'"));

//Datos del PROCESO DE COMPRA
$proceso = new proceso_compra();
$proceso ->cargarProcesoCompra($id_proceso);
$atributo_proceso_compra = $proceso->nombre_atributo_compra;
$nombre_proceso = $proceso->nombre_proceso;
$nombre_mecanismo  = $proceso->nombre_mecanismo_compra;


    //SOLICITANTE Y DIRECTIVO
    $nombre_solicitante = $proceso->nombre_solicitante;
    $nombre_directivo  = $proceso->nombre_directivo;




//honorarios recepcionados
$tabla_honorarios = '<table border="1">
    <tr>
        <td><strong>Tipo Documento</strong></td>
        <td><strong>Fecha Emision</strong></td>
        <td><strong>Número Documento</strong></td>
        <td><strong>Monto Documento</strong></td>
    </tr>
    <tr>
        <td>Boleta de Honorarios</td>
        <td>'.fechaNormal($fecha_boleta).'</td>
        <td>'.$nro_boleta.'</td>
        <td>$ '.$bruto.'</td>
    </tr>
</table>';






//Datos del CERTIFICADO

$documento = new documento('CERTIFICADO DE RECEPCION','Municipalidad de Carahue','Portales 295, Carahue');
$documento->updateTipoDocumento('CERTIFICADO DE RECEPCION','ADQUISICIONES - SERVICIOS');
$documento->crearFolio();
$documento->NumerarDocumento(date('Y'));
$numero_certificado = $documento->numero_decreto."/".date('Y');




//
//$id_compra = $_POST['id_compra'];
//$id_documento = $_POST['dte'];
//$compra = new compra($id_empleado);
//$compra->loadCompra($id_compra);
//$certificado = new certificado($id_empleado);
//
//$certificado->insertCertificado('RECEPCION DE SERVICIO');
//
//$sql1 = "select *
//        from compras
//        inner join proveedor on compras.rut_proveedor=proveedor.rut_proveedor
//        where id_compra='$id_compra'
//        group by id_compra
//        limit 1";
//$row1 = mysql_fetch_array(mysql_query($sql1));
//
//$id_depto_compra = $row1['id_depto'];
//
//if($row1['id_directivo']==''){
//    $sql_jefe = "select * from funcionario
//                where id_depto='".$id_depto_compra."'
//                and activo='SI' and grado!=0 and planta_municipal='PLANTA'
//                order by grado ASC LIMIT 1;";
//    $row_jefe = mysql_fetch_array(mysql_query($sql_jefe));
//    $director = new functionario($row_jefe['reloj']);
//}else{
//    $id_directivo = $row1['id_directivo'];
//    $director = new functionario($id_directivo);
//}
//
//if($row1['id_solicitante']!=''){
//    $solicitante = new functionario($row1['id_solicitante']);
//    $nombre_solicitante = $solicitante->nombre_completo;
//}else{
//    $nombre_solicitante = '';
//}



//
//
//$proveedor = limpiaCadena($row1['razon_social']);
//$rut_proveedor = rut($row1['rut_proveedor']);
//$nombre_compra = str_replace("SERVICIO DE","",limpiaCadena(strtoupper($row1['nombre_compra'])));
////$monto_compra = "$ ".number_format($row1['monto_comprometido'],0,'','.');
//$monto_factura = $dte->monto_total;
//$monto_compra = "$ ".number_format($monto_factura,0,'','.');
//
//$decreto_compra = $row1['decreto_aprueba_compra'];
//$anio_decreto = $row1['anio'];
//
//$oc = $row1['orden_compra'];
//
//$id_atributo = $row1['id_tipo_compra'];
//
//$sql2 = "select * from compras_mecanismo
//          inner join compras_atributos_mecanismo on compras_atributos_mecanismo.id_mecanismo=compras_mecanismo.id_mecanismo
//          where id_atributo='$id_atributo' limit 1";
//$row2 = mysql_fetch_array(mysql_query($sql2));
//
//$mecanismo = $row2['nombre_mecanismo'];
//$nombre_atributo_compra = $row2['nombre_atributo'];



$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');

$fecha = $d . " de " . $meses[$m - 1] . " del " . $a;




//<strong style="text-align:right;text-indent: 200px;font-size: 0.8em;">'.$fecha.'</strong>
//<h4>Certificado de Recepción<br /> N° '.$certificado->id_certificado.'</h4>
//<p></p>
//<p>Quien suscribe certifica haber recibido conforme en cuando a la calidad y cantidad contratados del Servicio <strong>"'.trim($nombre_compra).'"</strong>
//prestado por el proveedor <strong>'.$proveedor.'</strong> cuyo RUT es '.$rut_proveedor.', el cual se registro mediante
// el Decreto de Compra Nº <strong>'.$decreto_compra.'</strong> del año '.$anio_decreto.', mediante el proceso de compras llamado
// "<strong>'.trim($nombre_atributo_compra).'</strong>" basado en el mecanismo de compra "<strong>'.$mecanismo.'</strong>".
//  <br />El proveedor realizo la emisión de una boleta de honorarios, de la cual se muestran los detalles a continuación:</p>
//'."dsoadks".'
// <p></p>

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
<p>Quien suscribe certifica haber recibido conforme en cuando a la calidad y cantidad contratados del Servicio <strong>"'.$nombre_proceso.'"</strong> 
prestado por el proveedor <strong>'.$proveedor.'</strong> cuyo RUT es <strong>'.$rut.'</strong>, el cual se registro mediante
 el Decreto de Compra Nº <strong>'.$decreto_compra['numero_decreto'].'</strong> del año <strong>'.$decreto_compra['anio'].'</strong>, mediante el proceso de compras llamado  
 "<strong>'.$atributo_proceso_compra.'</strong>" basado en el mecanismo de compra "<strong>'.$nombre_mecanismo.'</strong>".
  <br />El proveedor realizo la emisión de una boleta de honorarios, de la cual se muestran los detalles a continuación:</p>
  '.$tabla_honorarios.'
 <p></p>
<p>Esté Servicio se realizo según las especificaciones descritas a continuación:</p>
<p></p>
<table style="width: 100%;font-size: 0.8em;" width="100%" border="1">
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
    <td><h3>'.$nombre_directivo.'<br />Director</h3></td>
    <td><h3>'.$nombre_solicitante.'<br />Firma Responsable</h3></td>
</tr>
</table>
';


$rut = str_replace(".","",$rut);
$bruto = str_replace("$","",str_replace(".","",str_replace(" ","",$bruto)));
$neto = str_replace("$","",str_replace(".","",str_replace(" ","",$neto)));
$impuesto = str_replace("$","",str_replace(".","",str_replace(" ","",$impuesto)));
mysql_query("insert into honorarios (rut, mes, anio, nro_boleta, bruto, neto, impuestos, id_contratoH, estado) values
                                        ('$rut','$mB','$aB','$nro_boleta','$bruto','$neto','$impuesto','1','PENDIENTE')");

//$certificado->updatePDF($html);

//$compra->insertCertificadoRecepcion($certificado->id_certificado);
//$compra->insertDocumentoDTE($dte->rut,$dte->folio);
//$compra->updateRecepcion($certificado->id_certificado);
$documento = new documento('Certificado de Recepcion','Municipalidad de Carahue','Certificado');
$documento->CrearPDF($html);





