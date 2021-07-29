<?php
include '../../php/config.php';

include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");

include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$id_comprador = $_SESSION['id_empleado'];

$anio = $_POST['anio_ingreso'];
$unidiad = $_POST['unidad_ingreso'];

$numero_certificado = $_POST['certificado'];

$nombre_compra = $_POST['nombre_compra'];
$detalle = $_POST['detalle_compra'];
$solicitante = $_POST['solicitante'];
$tipo_compra = 2;//menor a 3 utm

$rut_proveedor = str_replace(".","",$_POST['rut_proveedor']);
$nombre_proveedor = $_POST['nombre_proveedor'];



$cantidad = $_POST['cantidad'];
$medida = $_POST['medida'];
$nombre = $_POST['nombre'];
$valor = $_POST['precio'];
$total_fila = $_POST['total_fila'];

$table_productos = '<table border="1">
                        <tr STYLE="background-color: rgba(204,253,255,0.99);font-weight: bold;">
                        <td colspan="3" style="text-align: right;width: 70%;"></td>
                        <td colspan="2" style="text-align: center;width: 30%;">VALORES</td>
                        </tr>
                        <tr STYLE="background-color: rgba(204,253,255,0.99);font-weight: bold;">
                            <td style="width: 10%;font-size: 0.9em;">CANTIDAD</td>
                            <td style="width: 10%">MEDIDA</td>
                            <td style="width:50%;;">DETALLE</td>
                            <td style="width: 15%;text-align: center;">UNITARIO</td>
                            <td style="width: 15%;text-align: center;">TOTAL</td>
                        </tr>';
foreach ($cantidad as $i_cantidad => $valor_cantidad){
    $total_fila_tr = ($valor_cantidad * $valor[$i_cantidad]);
    $table_productos .= '
        <tr>
            <td style="text-align: center;">'.$valor_cantidad.'</td>    
            <td style="text-align: center;">'.$medida[$i_cantidad].'</td>    
            <td style="text-align: left;">'.strtoupper($nombre[$i_cantidad]).'</td>    
            <td style="text-align: right;">$ '.number_format($valor[$i_cantidad],0,'','.').'</td>    
            <td style="text-align: right;">$ '.number_format($total_fila_tr,0,'','.').'</td>    
        </tr>
                ';
}
$total_general = str_replace("$ ","",str_replace(".","",$_POST['total_general']));
$total_iva = str_replace("$ ","",str_replace(".","",$_POST['iva']));
$sub_total = str_replace("$ ","",str_replace(".","",$_POST['sub_total']));
$table_productos .= '
        <tr>
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
        </tr>
        <tr>
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
        </tr>
        <tr>
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
            <td></td>    
        </tr>
            <tr style="">
                <td colspan="3" style="text-align: right;width: 70%;font-weight: bold;background-color: rgba(204,253,255,0.99);">SUB-TOTAL</td>
                <td colspan="2" style="text-align: right;width: 30%;font-weight: bold;">$ '.number_format($sub_total,0,''.'').'</td>
            </tr>
            <tr style="">
                <td colspan="3" style="text-align: right;width: 70%;font-weight: bold;background-color: rgba(204,253,255,0.99);">I.V.A. (19%) </td>
                <td colspan="2" style="text-align: right;width: 30%;font-weight: bold;">$ '.number_format($total_iva,0,'','.').'</td>
            </tr>
            <tr style="">
                <td colspan="3" style="text-align: right;width: 70%;font-weight: bold;background-color: rgba(204,253,255,0.99);">TOTAL GENERAL</td>
                <td colspan="2" style="text-align: right;width: 30%;font-weight: bold;">$ '.number_format($total_general,0,'','.').'</td>
            </tr>
            </table>';


$sql = "insert into oc_internas(numero_certificado,anio_certificado,rut_proveedor,total_oc,id_empleado)
        values('$numero_certificado','$anio','$rut_proveedor','$total_general','$id_comprador')";

mysql_query($sql);
$row = mysql_fetch_array(mysql_query("select * from oc_internas WHERE id_empleado='$id_comprador' order by id_oc desc limit 1"));
$id_oc = $row['id_oc'];
mysql_query("update oc_internas set codigo_oc='OC-$id_oc' where id_oc='$id_oc'");

$sql1 = "insert into compras(anio,unidad,id_comprador,id_tipo_compra,numero_certificado,nombre_compra,texto_compra,rut_proveedor,orden_compra,monto_comprometido) 
        VALUES('$anio','$unidiad','$id_comprador','$tipo_compra','$numero_certificado',upper('$nombre_compra'),upper('$detalle'),'$rut_proveedor','OC-$id_oc','$total_general')";
mysql_query($sql1)or die('ERROR_SQL');
$row1 = mysql_fetch_array(mysql_query("select * from compras where id_comprador='$id_comprador' order by id_compra desc limit 1"));
$id_compra = $row1['id_compra'];

$sql2 = "insert into cip_compras(numero_certificado,anio,id_compra) 
        values('$numero_certificado','$anio','$id_compra')";

?>
<?php

//Eliminamos los textos del documento

$id_empelado = $_SESSION['id_empleado'];




$titulo_documento = "<h4>ORDEN DE COMPRA INTERNA <br />Nº OC-".$$id_oc."</h4>";



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
<p>1.- El decreto alcaldicio Numero5780, de fecha 29 de Diciembre de 2016, que aprueba el presupuesto Municipal año 2017.</p>
<p></p>

'.$table_productos.'
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


