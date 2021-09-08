<?php

include("../../php/config.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');

include '../../php/objetos/persona.php';
include '../../php/objetos/establecimiento.php';
session_start();
error_reporting(0);

$id_establecimiento = $_SESSION['id_establecimiento'];
$rut = $_GET['rut'];
$p = new persona($rut);
$e = new establecimiento($id_establecimiento);




// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Tarjetero');
$pdf->SetSubject('Tarjetero');

$pagina2 = '<table border="1px" style="border: dotted 1px black;">
                <tr style="background-color: #cffcff;width: 100%;">
                    <td colspan="4" style="text-align: center;font-weight: bold;">TARJETERO INFANIL</td>
                </tr>
                <tr>
                    <td style="width: 20%;">RUT</td>
                    <td style="width: 30%;font-weight: bold;">'.strtoupper($p->rut).'</td>
                    <td style="width: 20%;">NACIMIENTO</td>
                    <td style="width: 30%;font-weight: bold;">'.fechaNormal($p->fecha_nacimiento).'</td>
                </tr>
                <tr>
                    <td style="width: 10%;">NOMBRE</td>
                    <td style="width: 65%;font-weight: bold;">'.strtoupper($p->nombre).'</td>
                    <td style="width: 10%;">EDAD</td>
                    <td style="width: 15%;font-weight: bold;">'.strtoupper($p->edad_anios).' años</td>
                </tr>
                <tr>
                    <td style="width: 20%;">DIRECCIÓN</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->direccion).'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">TELÉFONO</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->telefono).'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">E-MAIL</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->email).'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">SECTOR</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->nombre_sector_comunal).'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">CENTRO MEDICO</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->nombre_centro_medico).'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">SECTOR INTERNO</td>
                    <td style="width: 80%;font-weight: bold;">'.strtoupper($p->nombre_sector_interno).'</td>
                </tr>
                <tr style="background-color: #cffcff;">
                    <td colspan="4" style="text-align: center;font-weight: bold;">
                        DATOS DE NACIMIENTO
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">EOA</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('EOA')).'</td>
                    <td style="width: 25%;">PKU</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('PKU')).'</td>
                </tr>
                <tr>
                    <td style="width: 25%;">HC</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('HC')).'</td>
                    <td style="width: 25%;">APEGO INMEDIATO</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('APEGO_INMEDIATO')).'</td>
                </tr>
                <tr>
                    <td style="width: 25%;">VACUNA BCG</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('VACUNA_BCG')).'</td>
                    <td style="width: 25%;">VACUNA HEPATITIS B</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->getDatosNacimiento('VACUNA_HP')).'</td>
                </tr>
                <tr style="background-color: #cffcff;">
                    <td colspan="4" style="text-align: center;font-weight: bold;">REGISTRO DE VACUNAS</td>
                </tr>
                <tr>
                    <td style="width: 25%;">2 MESES</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna2M()).'</td>
                    <td style="width: 25%;">4 MESES</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna4M()).'</td>
                </tr>
                <tr>
                    <td style="width: 25%;">6 MESES</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna6M()).'</td>
                    <td style="width: 25%;">12 MESES</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna12M()).'</td>
                </tr>
                <tr>
                    <td style="width: 25%;">18 MESES</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna18M()).'</td>
                    <td style="width: 25%;">5 AÑOS</td>
                    <td style="width: 25%;font-weight: bold;">'.strtoupper($p->vacuna5Anios()).'</td>
                </tr>
            </table>';



$pagina1 = '';

$html ='<style type="text/css">
            table{
            font-size: 0.8em;;
            }
            table tr{
            line-height: 2em;;
            }
        </style>';
$html .= '<table border="1" style="border: solid black;width: 100%;">
            <tr>
                <td>'.$pagina1.'</td>
                <td>
                '.$pagina2.'
                </td>
            </tr>
        </table>';

$title_pdf = $e->nombre;
$sub_title_pdf = $p->nombre_centro_medico;
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
$pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(2, 2, 2);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings


// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();
$pdf->AddPage('L', 'A4');
// set text shadow effect
//$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print


// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Tarjetero_'.$rut.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
