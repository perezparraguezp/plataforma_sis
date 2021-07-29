<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/class/decreto.php");

require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
$id_mio = $_SESSION['id_empleado'];
$f = new functionario($id_mio);

//Generacion de Folio

$decreto = new decreto();


$folio = $decreto->folio;

//variables
$recepcion = $_POST['recepcion'];
$id_tipo_doc = $_POST['tipo_doc'];
$sql4 = "SELECT * from pc_tipo_documentos where id_tipo_doc='$id_tipo_doc' limit 1";
$row4 = mysql_fetch_array(mysql_query($sql4));
$nombre_tipo_doc = $row4['nombre_tipo'];

$fecha_devengado = $_POST['fecha_devengado'];
list($anio,$mes,$dia) = explode("-",$fecha_devengado);

$numero_doc = $_POST['numero_documento'];
$fecha_documento = $_POST['fecha_documento'];
$rut_proveedor = $_POST['rut_proveedor'];
$nombre_depto = $_POST['nombre_depto'];
$id_comprador = $_POST['id_comprador'];
$id_cip = $_POST['id_cip'];
$id_compra = $_POST['id_compra'];

$devengar           = $_POST['devengar'];
$cuenta_origen      = $_POST['cuenta_origen'];
$monto_cip          = $_POST['monto_cip'];

$monto_total_cip          = $_POST['monto_total_cip'];

$sql_1 = "select * from libro_devengados WHERE anio='$anio' order by numero_devengado desc limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
if($row_1){
    $numero = $row_1['numero_devengado']+1;
}else{
    $numero = 1;
}

$sql0 = "insert into libro_devengados(anio,numero_devengado,id_empleado,id_cip,numero_documento,tipo_documento,rut_proveedor,folio,id_compra) 
        values('$anio','$numero','$id_mio','$id_cip','$numero_doc','$id_tipo_doc','$rut_proveedor','$folio','$id_compra')";

mysql_query($sql0);

$datos_devengado = '';

$total_debe = $total_haber = 0;

foreach ($devengar as $i => $value){

    list($velue_item,$nombre) = explode(" | ",$value);
    $datos_devengado .= '<tr>';
    $origen = $cuenta_origen[$i];

    $sql1 = "select * from pc_cuenta where codigo_general='$origen' limit 1";
    $sql2 = "select * from pc_cuenta where codigo_general='$velue_item' limit 1";

    $row1 = mysql_fetch_array(mysql_query($sql1));
    $row2 = mysql_fetch_array(mysql_query($sql2));


    $haber = str_replace("$ ",'',str_replace(".","",$monto_cip[$i]));


    $debe = $haber;

    $total_debe += $debe;
    $total_haber += $haber;


    $datos_devengado .= '<td>'.$velue_item.'</td>';
    $datos_devengado .= '<td>'.$row2['nombre_cuenta'].'</td>';
    $datos_devengado .= '<td style="text-align: right;">$ '.number_format($debe,0,'','.').'</td>';
    $datos_devengado .= '<td style="text-align: right;">$ 0</td>';

    $datos_devengado.= '</tr>';

    $datos_devengado .= '<tr>';
    $datos_devengado .= '<td>'.$origen.'</td>';
    $datos_devengado .= '<td>'.$row1['nombre_cuenta'].'</td>';
    $datos_devengado .= '<td style="text-align: right;">$ 0</td>';
    $datos_devengado .= '<td style="text-align: right;">$ '.number_format($haber,0,'','.').'</td>';

    $datos_devengado.= '</tr>';

    $sql3 = "insert into libro_devengado_detalle(id_empleado,numero_devengado,anio,cuenta,nombre_cuenta,debe,haber) 
              values('$id_mio','$numero','$anio','$value','".$row2['nombre_cuenta']."','$debe','0')";
    $sql4 = "insert into libro_devengado_detalle(id_empleado,numero_devengado,anio,cuenta,nombre_cuenta,debe,haber) 
              values('$id_mio','$numero','$anio','$origen','".$row1['nombre_cuenta']."','0','$haber')";

    mysql_query($sql3);
    mysql_query($sql4);
}

mysql_query("update libro_devengados 
              set monto_devengar='$total_haber' 
              where folio='$folio' ");



//Fecha
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
$pdf->SetTitle('Decreto de Compra');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Decreto de Compra , Documento');





$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Adquisiciones\n".$nombre_depto;
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
        width: 100%;
    }
        
    span{
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;
    }
</style>
<table>
    <tr>
        <td></td>
        <td></td>
        <td>
            <table border="1">
                <tr>
                    <td>Nº DEVENGADO</td>
                    <td><strong>'.$numero.'</strong></td>
                </tr>
                <tr>
                    <td>FECHA</td>
                    <td>'.date('d/m/Y').'</td>
                </tr>
                <tr>
                    <td>Nº RECEPCION</td>
                    <td>'.$recepcion.'</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<h4>DEVENGADO DE FACTURAS Y DOCUMENTOS</h4>

<table>
    <tr>
        <td>
            <table>
                <tr>
                    <td>TIPO DOCUMENTO</td>
                    <td><strong>'.$nombre_tipo_doc.'</strong></td>
                </tr>
                <tr>
                    <td>NUMERO DOCUMENTO</td>
                    <td><strong>'.$numero_doc.'</strong></td>
                </tr>
                <tr>
                    <td>FECHA DOCUMENTO</td>
                    <td><strong>'.$fecha_documento.'</strong></td>
                </tr>
                <tr>
                    <td>FOLIO REGISTRO</td>
                    <td><strong>'.$folio.'</strong></td>
                </tr>
                <tr>
                    <td>DEPARTAMENTO ASOCIADO</td>
                    <td><strong>'.$nombre_depto.'</strong></td>
                </tr>
            </table>
        </td>
        <td>
            <table border="1">
                <tr>
                    <td>MONTO TOTAL DE IMPUTACIÓN</td>
                    <td style="text-align:right;">$ '.number_format($monto_total_cip,0,'','.').'</td>
                </tr>
                <tr>
                    <td>MONTO TOTAL DEL COMPROMISO</td>
                    <td style="text-align:right;">$ '.number_format($monto_compromiso,0,'','.').'</td>
                </tr>
                <tr>
                    <td>MONTO DEVENGADO ACTUAL</td>
                    <td style="text-align:right;">$ '.number_format($total_haber,0,'','.').'</td>
                </tr>
                <tr>
                    <td>MONTO PENDIENTE</td>
                    <td style="text-align:right;">$ '.number_format($monto_pendiente,0,'','.').'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr><td></td><td></td></tr>
    <tr><td></td><td></td></tr>
</table>
<br />
<table border="1">
    <tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;padding: 3px;">
        <td>CUENTA</td>
        <td>DEMONINACION</td>
        <td>DEBE</td>
        <td>HABER</td>
    </tr>
    '.$datos_devengado.'
    
    <tr>
        <td colspan="2" style="text-align: right;">Total</td>
        <td style="text-align: right;">$ '.number_format($total_debe,0,'','.').'</td>
        <td style="text-align: right;">$ '.number_format($total_haber,0,'','.').'</td>
    </tr>
</table>
<hr />
<p></p>
<table border="1">
<tr>
    <td style="text-align: right;">Preparado por:</td>
    <td><strong>'.$f->nombre.'</strong></td>
</tr>
</table>

<table>
<tr>
    <td></td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td>
<td></td>
</tr>
</table><br /><br />
</div>
';

// Print text using writeHTMLCell()



$sql_pdf = "insert into pdf_respaldo(folio,nombre_doc,head1,head2,html,table_sql,id_sql) 
      values('$folio','DEVENGADO','$title_pdf','$sub_title_pdf','$html','devengado','')";
mysql_query($sql_pdf);

$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('DECRETO_COMPRA.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
