<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
$listado = $_GET['list_decreto'];

$id_empleado = $_SESSION['id_empleado'];
$f = new functionario($id_empleado);



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Seguimiento');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Seguimiento, Documento');


$title_pdf = $f->nombre_depto."                                            ";
$sub_title_pdf = "Seguimiento";
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

//Procedimiento
$sql1 = "insert into seguimiento_decretos(id_empleado,fecha_inicio,lista_decretos)
            values('$id_empleado',current_date(),'$listado') ";
mysql_query($sql1);
$sql2 = "select * from seguimiento_decretos where id_empleado='$id_empleado' order by id_seguimiento desc limit 1";

$row1 = mysql_fetch_array(mysql_query($sql2));
$id_seguimiento = $row1['id_seguimiento'];


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
<p>Carahue, '.date('d/m/Y').' a las '.date('h:i:s').'</p><br />
<p>Seguimiento N: <strong>'.$id_seguimiento.'</strong>   </p>
';



$table = '
<table style="font-size: 0.6em;">
    <tr style="background-color: #c1ff61; font-weight: bold;">
        <td style="width: 80px;">Folio</td>
        <td style="width: 80px;">Numero</td>
        <td style="width: 80px;">Fecha</td>
        <td style="width: 200px;;">Origen</td>
        <td style="width: 200px;;">Texto</td>
    </tr>';
$data = explode("#",$listado);
foreach($data as $i => $value){
    if($value != ''){
        $sql3 = "select * from decretos where id_interno='$value' limit 1";
        $row3 = mysql_fetch_array(mysql_query($sql3));
        $texto = $row3['texto_afectado'];
        $f = new functionario(($row3['id_empleado']));
        $table .= '<tr >
                            <td style="padding: 3px;">'.$row3['id_interno'].'</td>
                            <td style="padding: 3px;">'.$row3['numero_decreto'].'</td>
                            <td style="padding: 3px;">'.$row3['fecha_decreto'].'</td>
                            <td style="padding: 3px;">'.$f->nombre_depto.' <br />['.$f->nombre.']</td>
                            <td style="padding: 3px;">'.trim($texto).'</td>
                            
                   </tr>
                   <tr><td colspan="3"></td></tr>';

    }
}





$table .= '</table>';
$html.=$table;
$html.='<br /><table >
<tr><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td></tr>
<tr style="text-align: center;">
    <td></td>
    <td></td>
    <td style="text-align: center;">Nombre Receptor<br />______________________<br />Firma Receptor</td>
    <td></td>
    <td></td>
</tr>
</table>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Seguimiento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
