<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento

$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);

$departamento = $f_mio->nombre_depto;

$decreto = new decreto();
$decreto->tipo_decreto('Ordinarios','Ordinarios');
$folio = $decreto->folio;

//Variables recibida
$distribucion = $_POST['distribucion'];
$antecedente = $_POST['antecedente'];
$materia = $_POST['materia'];
$emisor = $_POST['emisor'];
$cargo = $_POST['cargo'];
$receptor = $_POST['receptor'];
$texto = trim($_POST['editor']);

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
$pdf->SetTitle('Ordinario');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Comercio Ambulante, Documento');


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = $departamento."\nCarahue, Tel. 45-2681500";
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
$pdf->AddPage('P','LETTER');
// set text shadow effect
//$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print

$html = '
<style type="text/css">
    p{
        text-align: left;
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
        font-size:10pt;
        text-align: left;
        }
    li{
    font-size:10pt;
    }
</style>
<p>ORD Nº:</p>
<p>ANT: <strong>'.$antecedente.'</strong></p>
<p>MAT: <strong>'.$materia.'</strong></p>
<p>CARAHUE,</p>
<span>DE: <strong>'.$emisor.'</strong></span><br />
<span>A: <strong>'.$receptor.'</strong></span><br />
<hr style="width:100%;" />
<span></span>
<span style="font-size:10pt;">'.$texto.'</span>


<p></p>

<div style="text-align:center;font-size:12pt;">'.$emisor.'<br />'.$cargo.'</div>

<table>
<tr>
    <td style="text-align: left;font-size:12pt;"><br /><strong></strong></td>
    <td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: left;font-size:12pt;">
'.$distribucion.'<br />
<strong><u>DISTRIBUICION</u></strong>
<ul>
<li>Interesado</li>
<li>Archivo Municipal</li>
</ul>
</td>
<td>
</td>
</tr>

</table><br /><br />
<div style="position:absolute;bottom:10px">
<strong style="font-size:12pt;">Folio: '.$folio.'</strong>
</div>
';
//echo $html;
//argamos los datos de este PDF

// Print text using writeHTMLCell()
$style = array(
    'position' => 'absolute',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'bottom' => 0,
    'fontsize' => 6,
    'stretchtext' => 4
);
$style['position'] = 'R';
$pdf->setBarcode($folio);
//$pdf->write1DBarcode($folio, 'C128A', '', '', '', 10, 0.2, $style, 'N');
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
