<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento


$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);

$id_reunion = $_POST['id_reunion'];

$sql1 = "SELECT * from concejo_reunion where id_reunion='$id_reunion' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$numero_reunion = $row1['numero_reunion'];

$sql1_1 = "select * from firmantes where id_empleado='".$row1['id_presidente']."' limit 1";
$row1_1 = mysql_fetch_array(mysql_query($sql1_1));
$presidente = $row1_1['nombre_firma']."<br />Presidente";

$sql1_2 = "select * from firmantes where id_empleado='".$row1['id_secretario']."' limit 1";
$row1_2 = mysql_fetch_array(mysql_query($sql1_2));
$secretario = $row1_2['nombre_firma']."<br />Secretario Municipal";

$sql2 = "select * from concejal
        where desde<=current_date() and hasta>=CURRENT_DATE()";
$res2 = mysql_query($sql2);

$lista_concejales_asistencia = '';
while($row2 = mysql_fetch_array($res2)){
    $lista_concejales_asistencia .= '<tr>
        <td>'.strtoupper($row2['nombre_concejal']).'</td>
        <td>
            <label>O Presente</label>
            <label>O Ausente</label>
            <label>O Justificado</label>
        </td>
    </tr>';
}


$lista_temas_reunion = '';
$sql3 = "SELECT * from concejo_reunion
            inner join concejo_temas_reunion using(id_reunion)
            inner join concejo_temas using(id_tema) 
            where id_reunion='$id_reunion' 
            order by orden asc";
$res3 = mysql_query($sql3);
while($row3 = mysql_fetch_array($res3)){
    $lista_temas_reunion .= '<tr>
                <td>'.strtoupper($row3['expositor']).'</td>
                <td>'.strtoupper($row3['tema_texto']).'</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
}






$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('ACTA');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, ACTA, Documento');


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Secretaria Municipal\nDiego Portales 295, Carahue, Tel. 45-2681500";
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
// Set some content to print


$html = '
<style type="text/css">
    p{
        text-align: left;
        font-size:12pt;
        margin-top: 0px;
        
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:1em;
    }
    span{
        font-size:10pt;
        text-align: left;
        }
    li{
    font-size:10pt;
    }
    h2{
    text-align: center;
    }
</style>
<h2>Asistencia Concejo Municipal Nº '.$numero_reunion.'</h2>
<p>Con fecha '.fechaNormal($row1['fecha']).', se realiza la asistencia de la siguiente forma:</p>
<table border="1">
    <tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;">
        <td style="padding: 10px;">Nombre Completo</td>
        <td style="padding: 10px;">Asistencia</td>
    </tr>
    '.$lista_concejales_asistencia.'
</table>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td style="text-align:center">'.$presidente.'</td>
        <td style="text-align:center">'.$secretario.'</td>
    </tr>
</table>
';
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
$pdf->AddPage();
$html = '
<style type="text/css">
    p{
        text-align: left;
        font-size:12pt;
        margin-top: 0px;
        
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:0.8em;
    }
    span{
        font-size:10pt;
        text-align: left;
        }
    li{
    font-size:10pt;
    }
    h2{
    text-align: center;
    }
</style>
<h2>Tabla de Contenidos<br/>Concejo Municipal Nº '.$numero_reunion.'</h2>
<p>Con fecha '.fechaNormal($row1['fecha']).', se realiza la asistencia de la siguiente forma:</p>
<table border="1">
    <tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;">
        <td rowspan="2" style="width:30%;">Exponente</td>
        <td rowspan="2" style="width:50%;">Tema</td>
        <td colspan="3" style="text-align:center;width:20%">Votacion</td>
    </tr>
    <tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;text-align:center">
        <td>SI</td>
        <td>NO</td>
        <td>ABS.</td>
    </tr>
    '.$lista_temas_reunion.'
</table>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td style="text-align:center">'.$presidente.'</td>
        <td style="text-align:center">'.$secretario.'</td>
    </tr>
</table>
';
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

$sql3 = "SELECT * from concejo_reunion
            inner join concejo_temas_reunion using(id_reunion)
            inner join concejo_temas using(id_tema) 
            where id_reunion='$id_reunion' 
            order by orden asc";
$res3 = mysql_query($sql3);
while($row3 = mysql_fetch_array($res3)){
    if($row3['requiere_acuerdo'=='SI']){
        $html = '<h2 style="text-align: center;">Votación Detallada</h2>';
        $html .= '<p><strong>TEMA:</strong>'.strtoupper($row3['tema_texto']).'</p>';
        $html .= '<p><strong>EXPOSITOR:</strong>'.strtoupper($row3['expositor']).'</p>';

        $html .= '<table border="1">
                <tr style="background-color: #00a2e8;font-weight: bold">
                    <td style="width: 70%;">Nombre Concejal</td>
                    <td style="width: 10%;text-align: center;">SI</td>
                    <td style="width: 10%;text-align: center;">ABS</td>
                    <td style="width: 10%;text-align: center;">NO</td>
                </tr>';
        $sql2 = "select * from concejal
        where desde<=current_date() and hasta>=CURRENT_DATE()";
        $res2 = mysql_query($sql2);

        while($row2 = mysql_fetch_array($res2)){
            $html .= '<tr>
        <td>'.strtoupper($row2['nombre_concejal']).'</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>';
        }


        $html .= '</table>';
        $html .='<p></p>';
        $html .='<p></p>';
        $html .='<table>
                    <tr>
                        <td style="text-align:center"></td>
                        <td style="text-align:center">'.$secretario.'</td>
                    </tr>
                </table>';


        $pdf->AddPage();
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    }
}

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$pdf->Output('Acta.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
