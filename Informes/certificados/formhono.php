<?php

include("../../php/config.php");
include("../../php/class/decreto.php");

include '../../php/objetos/persona.php';
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento
$id_org = $_POST['id_org'];

$actas = $_POST['acta'];
$antecedentes = $_POST['antecedentes'];

$sql1 = "select * from organizaciones_sociales where id_org='$id_org' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$nombre_org = $row1['nombre'];

$hasta = fechaNormal($row1['fecha_vigencia']);


$sql2 = "select * from directiva where id_org='$id_org' order by hasta desc limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));

$desde = $row2['desde'];

$p1 = new persona($row2['presidente']);
$p2 = new persona($row2['secretario']);
$p3 = new persona($row2['tesorero']);
$p4 = new persona($row2['otro1']);
$p5 = new persona($row2['otro2']);
$p6 = new persona($row2['otro3']);

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
$sub_title_pdf = "Secretaria Municipal\nPortales #295, Carahue";
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
        text-align: left;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h5{
    font-size: 1em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    }
    h4{
    text-align: center;
    }
</style>
<h4>Certificado N°</h4>

<br /><br />
<table style="width:100%;" border="1">
    <tr>
        <td colspan="3" style="text-align:center;font-size: 1em;"><strong>'.$nombre_org.'</strong></td>
    </tr>
    <tr>
        <td>INSCRIPCION SERVICIO DE REGISTRO CIVIL E IDENTIFICACION</td>
        <td>Nº '.$row1['numero_registro'].'</td>
        <td>'.fechaNormal($row1['fecha_registro']).'</td>
    </tr>
    <tr>
        <td>DOMICILIO</td>
        <td colspan="2">'.$row1['direccion'].'</td>
    </tr>
    <br>
    <tr>
        <td><STRONG>PERSONALIDAD JURIDICA</STRONG></td>
        <td><STRONG>NUMERO</STRONG><br><STRONG>FECHA</STRONG></td>
        <td><STRONG>'.$row1['folio_org'].'</STRONG><br><STRONG>'.fechaNormal($row1['fecha_creacion']).'</STRONG></td>
    </tr>
    <br>
    <tr>
        <td><STRONG>DOCUMENTOS PRESENTADOS</STRONG></td>
        <td><STRONG>ACTA CAMBIO DE DIRECTIVA</STRONG><br><STRONG>CERTIFICADOS DE ANTECEDENTES</STRONG></td>
        <td><STRONG>'.$actas.'</STRONG><br><STRONG>'.$antecedentes.'</STRONG></td>
    </tr>
    <br>
    <tr>
        <td><STRONG>VIGENCIA DEL DIRECTORIO</STRONG></td>
        <td><STRONG>DESDE</STRONG><br><STRONG>HASTA</STRONG></td>
        <td><STRONG>'.$desde.'</STRONG><br><STRONG>'.$hasta.'</STRONG></td>
    </tr>
</table>
    <br>
    <span>Que, de acuerdo a los antecedentes proporcionados por la organizacion su <strong>DIRECTORIO DEFINITIVO</strong> esta conformado como sigue:</span>
    <br>
    <br>
<table style="width: 100%;" border="1">
    <tr>
        <td colspan="4">
            <STRONG>TITULARES</STRONG>
        </td>
    </tr>
    <tr>
        <td style="width:10%;"></td>
        <td style="width:20%;">Presidente</td>
        <td style="width:20%;">'.$p1->rut.'</td>
        <td style="width:50%;">'.$p1->nombre_completo.'</td>
    </tr>
    <tr>
        <td></td>
        <td>Secretario</td>
        <td>'.$p2->rut.'</td>
        <td>'.$p2->nombre_completo.'</td>
    </tr>
    <tr>
        <td></td>
        <td>Tesorero</td>
        <td>'.$p3->rut.'</td>
        <td>'.$p3->nombre_completo.'</td>
    </tr>
    <tr>
        <td colspan="4">
            <STRONG>SUPLENTES</STRONG>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>1º SUPLENTE</td>
        <td>'.$p4->rut.'</td>
        <td>'.$p4->nombre_completo.'</td>
    </tr>
    <tr>
        <td></td>
        <td>2º SUPLENTE</td>
        <td>'.$p5->rut.'</td>
        <td>'.$p5->nombre_completo.'</td>
    </tr>
    <tr>
        <td></td>
        <td>3º SUPLENTE</td>
        <td>'.$p6->rut.'</td>
        <td>'.$p6->nombre_completo.'</td>
    </tr>
</table>
<p></p>
<span>No habiendo observaciones al respecto, procedase al registro del nuevo directorio en el Registro Nacional de Organizaciones del Servicio de Registro Civil e Identificacion</span>
<p></p>
<table>
    <tr>
        <td style="text-align:left">
            <u>DISTRIBUICION</u>
                <ul>
                    <li>Archivo Municipal</li>
                    <li>Archivo Personal</li>
                    <li>Interesado</li>
                </ul>
        </td>
    </tr>
</table>
<p></p>
<p></p>
<h5>Roberto Samuel Rojas Leon<br/>Secretario Municipal</h5>';
$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);




// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
