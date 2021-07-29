<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");

include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento

$id_compra = $_POST['id_compra'];
$id_empelado = $_SESSION['id_empleado'];

$anio = $_POST['anio_resolucion'];

list($rut_proveedor,$nombre_proveedor) = explode(" | ",$_POST['datos_proveedor']);

$fecha = $_POST['fecha'];
$numero_doc = $_POST['numero_doc'];

$documento = $_POST['tipo_documento'];
$monto = str_replace("$ ","",str_replace(".","",$_POST['monto']));


$sql1 = "select * from compras_resoluciones WHERE anio='$anio' order by numero_resolucion desc limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1){
    $numero = $row1['numero_resolucion'] +1;
}else{
    $numero = 1;
}

$sql = "insert into compras_resoluciones(id_empleado,numero_resolucion,anio,rut_proveedor,tipo_documento,fecha_documento,monto_resolucion,id_compra,numero_documento) 
        values('$id_empelado','$numero','$anio','$rut_proveedor','$documento','$fecha','$monto','$id_compra','$numero_doc')";
mysql_query($sql)or die('ERROR_SQL');






$titulo_documento = "<h4>DECRETO DE COMPRA <br />Nº ".$numero."</h4>";

$tabla_detalle = '<table style="width: 100%;padding: 3px;" border="1">
    <tr>
        <td>MECANISMO DE COMPRA</td>
        <td>XXXXX</td>
    </tr>
    <tr>
        <td>[O/C] [LICITACION] [OC. INTERNA]</td>
        <td>Nº XXXXX</td>
    </tr>
    <tr>
        <td>CERTIFICADO DE IMPUTACION PRESUPUESTARIA (C.I.P.)</td>
        <td>Nº XXXX</td>
    </tr>
    <tr>
        <td>MONTO C.I.P.</td>
        <td>$ XXXXXX</td>
    </tr>
    <tr>
        <td>MONTO DECRETO</td>
        <td>$ XXXXXX</td>
    </tr>
</table>';

$table_firmantes = '<table style="text-align: center;font-size: 1em" border="1">
<tr>
    <td>
        <u>Nombre</u><br/>
        DEPTO. ORIGEN
    </td>
    <td>
        <u>Nombre</u><br/>
        DIRECCION DE CONTROL
    </td>
</tr>
<tr>
    <td>
        <u>Nombre</u><br/>
        SEC. MUNICIPAL
    </td>
    <td>
        <u>Nombre</u><br/>
        ALCALDE/ADMINISTRADOR
    </td>
</tr>
</table>';



$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);

$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $diaX = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $diaX;
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Certificado');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, documento, Documento');


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Dir. de Administracion y Finanzas\nPortales #295, Carahue";
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
$pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print

$html = '
<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:12pt;
        margin-top: 0px;
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    span{
        font-size:12pt;
        text-align: left;
        
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h6{
    font-size: 1em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    
    }
</style>
<p style="text-align: right;">FECHA <strong>'.date('d/m/Y').'</strong></p>
'.$titulo_documento.'
<p>VISTOS</p>
<p>1.-El decreto alcaldicio numero <strong>XXXX</strong>, de fecha XX de mes de anio XXXX, que aprueba el presupuesto municipal anio XXXX.</p>
<p>2.-La ley Nro. 19.866, Ley de Bases de Contratos Administrativos de Suministro y Prestación de Servicios.</p>
'.$tabla_detalle.'
<p></p>
'.$table_firmantes.'
';
//echo $html;
//argamos los datos de este PDF

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('resolucion_compra.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
