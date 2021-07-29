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
$codigo_documento = $_POST['codigo_documento'];
mysql_query("delete from base_texto_documento where codigo_documento='$codigo_documento'");


$decreto = new decreto();
$folio = $decreto->folio;


$item = $_POST['item'];
$text_li = '';
$indice = 1;
foreach ($item as $i => $value){
    $value = str_replace('<div>','',$value);
    $value = str_replace('</div>','',$value);
    $value = trim($value);
    if($indice==1){
        //Solo el primer visto
        $decreto->datos_afectado('','','',$value);
    }
    $text_li .='<p>'.$indice.'.- '.$value.'</p>';
    mysql_query("insert into base_texto_documento(codigo_documento,texto_li,activo,tipo_texto,orden)
    values('$codigo_documento','$value',1,'DECRETO',$indice)");
    $indice++;
}
$decreto->tipo_decreto('Alcaldicios sin Registro','Comercio Ambulante');


$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];

//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
if($_POST['secretario_s']){
    $cargoF1 = "Secretario Municipal (s)";
}else{
    $cargoF1 = "Secretario Municipal";
}

if($_POST['alcalde_s']){
    $cargoF2 = "Alcalde (s)";
}else{
    $cargoF2 = $rowF2['cargo_directivo'];
}
$depto_doc = "Direccion de Administracion y Finanzas";
$oficina_personal = 'Diego Portales #295';


if(@$_POST['registro']){
    @$registrese = $_POST['registro'];
}else{
    $registrese = "";
}


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
$pdf->SetTitle('Comercio Ambulante');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Comercio Ambulante, Documento');


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Rentas Municipales\nPortales #295, Carahue";
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
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:10pt;
    }
</style>
<p>DECRETO N:</p>
<p>Carahue, <br />VISTOS: Estos Antecedentes</p>        
<p>1.- La solicitud presentada para efectuar BENEFICIO y a la vez quedar exento de pago de los correspondientes derechos municipales.</p>
<p>2.- La Ley Nº 19.418 de 1995 sobre organizaciones comunitarias territoriales y funcionales.</p>
<p>3.- El Articulo Nº 7 de la ordenanza municipal de fecha 30 de Diciembre de 2005.</p>
<p>4.- La Ley Nº 19.925 de 2004, sobre el Expendio y Consumo de Bebidas Alcohólicas.</p>
<p>5.- Las facultades que me confiere el texto refundido de la Ley 18.695, <strong>Orgánica Constitucional de Municipalidades</strong>.</p></p>

<p><strong><u>DECRETO</u></strong></p>
'.$text_li.'

<blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE<blockquote></blockquote></blockquote>
<p></p>
<table>
<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>

</table>
<table>
<tr>
    <td style="text-align: center;font-size:14pt;">
        <strong>' . $nombreF1 . '</strong><br />
        <span style="font-size:12pt;">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;font-size:14pt;">
        <strong>' . $nombreF2 . '</strong><br />
       <span style="font-size:12pt;">' . $cargoF2 . '</span>
    </td>
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
    <td style="text-align: left;font-size:12pt;"><br /><strong></strong></td>
    <td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: left;font-size:12pt;">
<strong><u>DISTRIBUICION</u></strong>
<ul>
<li>Interesado</li>
<li>Control</li>
<li>Archivo Municipal</li>
<li>Archivo Depto.</li>
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
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
