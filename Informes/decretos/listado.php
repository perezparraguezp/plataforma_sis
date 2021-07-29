<?php

include("../../php/config.php");
include("../../php/objetos/funcionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
error_reporting(0);
$id_mio = $_SESSION['id_empleado'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$tipo_decreto = $_POST['tipo'];
$anio  = $_POST['anio'];

$sql = "select * from decretos where tipo_decreto='$tipo_decreto' AND 
                  numero_decreto>=$desde AND numero_decreto<=$hasta 
                  and anio_decreto=$anio
                  order by numero_decreto";
$res = mysql_query($sql);
$filas = '';
while($row = mysql_fetch_array($res)){
    $f = new funcionario($row['id_empleado']);
    $filas .= '<tr>';
    $filas .= '<td>'.$row['numero_decreto'].'</td>';
    $filas .= '<td>'.fechaNormal($row['fecha_decreto']).'</td>';
    $filas .= '<td>'.$row['texto_afectado'].'</td>';
    $filas .= '<td>'.$f->nombre.'</td>';
    $filas .= '</tr>';
    $filas .= '<tr><td colspan="4"></td></tr>';

}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Lista Decretos');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Documento');

$title_pdf = "SECMUN                                            ";
$sub_title_pdf = "Secretaria Municipal\nPortales #295";
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
</style>
<div>
<table border="1px">
<tr>
<td style="width: 10%;">NUMERO</td>
<td style="width: 15%;">FECHA</td>
<td style="width: 55%;">MATERIA</td>
<td style="width: 20%;">GENERADOR</td>
</tr>'.$filas.'
</table>
</div>
</div>
';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
