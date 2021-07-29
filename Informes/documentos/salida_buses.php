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
$decreto->tipo_decreto('Alcaldicios sin Registro','Salida de Buses');
$folio = $decreto->folio;

//Variables recibida
$distribucion = $_POST['distribucion'];
list($fecha1,$fecha2) = explode(" - ",$_POST['fecha']);
$org = $_POST['organizacion'];
$actividad = $_POST['actividad'];
$destino = $_POST['destino'];
$vehiculo = $_POST['vehiculo'];
$conductor = $_POST['conductor'];
$salida = $_POST['salida'];
$regreso = $_POST['regreso'];
$arriendo = $_POST['arriendo'];
$extras = $_POST['extras'];
$viatico = $_POST['viatico'];
$valor = $_POST['total_a_pagar'];
$restricciones = $_POST['restricciones'];

$texto = 'Destino: '.$destino.", Actividad: ".$actividad."<br />";
$texto .= 'Chofer: '.$conductor." [".$vehiculo."]<br />";
$texto .= 'Salida: '.$salida."<br />";
$texto .= 'Restricciones: '.$restricciones."<br />";

$decreto->texto_afectado($texto);

//firmas

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
}else {
    $cargoF2 = $rowF2['cargo_directivo'];
}


$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
//$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;
if($fecha1 == $fecha2){
    $fecha = "el día ".$fecha1;
}else{
    $fecha = 'Desde el '.$fecha1." hasta ".$fecha2;
}
//creacion tabla

$tabla ='<table style="width:100%;" border="1">';

$tabla .='<tr>
            <td style="width: 20%;">FECHA</td>
            <td style="width: 80%;font-weight: bold;">'.$fecha.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">ORGANIZACION</td>
            <td style="width: 80%;font-weight: bold;">'.$org.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">ACTIVIDAD</td>
            <td style="width: 80%;font-weight: bold;">'.$actividad.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">DESTINO</td>
            <td style="width: 80%;font-weight: bold;">'.$destino.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">VEHICULO</td>
            <td style="width: 80%;font-weight: bold;">'.$vehiculo.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">CONDUCTOR</td>
            <td style="width: 80%;font-weight: bold;">'.$conductor.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">HORARIO</td>
            <td style="width: 80%;font-weight: bold;">'.$salida.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;"></td>
            <td style="width: 80%;font-weight: bold;">'.$regreso.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">ARRIENDO</td>
            <td style="width: 80%;font-weight: bold;">'.$arriendo.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">HORAS EXTRAS</td>
            <td style="width: 80%;font-weight: bold;">'.$extras.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">VIATICO SIMPLE</td>
            <td style="width: 80%;font-weight: bold;">'.$viatico.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">TOTAL VALOR A PAGAR</td>
            <td style="width: 80%;font-weight: bold;">'.$valor.'</td>
        </tr>';
$tabla .='<tr>
            <td style="width: 20%;">RESTRICCIONES</td>
            <td style="width: 80%;font-weight: bold;">'.$restricciones.'</td>
        </tr>';

$tabla .='</table>';



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
$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print

$html = '
<style type="text/css">
    p{
        text-align: left;
        text-indent: 280px;
        font-size:9pt;
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
<p>DECRETO</p>
<p>CARAHUE,</p>
<p>VISTOS; Estos antecedentes</p>

<p>1.- El Decreto Ley N° 799 de Diciembre de 1974, y la circular N° 9277 de 1975 de la Contraloría General de la República sobre uso de vehículos estatales.</p>
<p>2.- El Decreto N°5.780 de fecha 29 de Diciembre de 2016, que aprueba el presupuesto para el año 2017.</p>
<p>3.- La solicitud de bus presentada por <strong style="font-size:9pt;">'.$org.'</strong> para trasladar delegación, que participa de <strong>'.$actividad.'</strong> a realizarse <strong>'.$fecha.'</strong> en la comuna de <strong>'.$destino.'</strong>.</p>
<p>4.- La orden de Ingresos Municipales, por un monto de <strong>'.$valor.'</strong> para cubrir los gastos de honorario conductor y uso de bus.</p>
<p>5.- La ordenanza Municipal publicada de acuerdo al Decreto Alcaldicio N°1322 de fecha 30 de diciembre de 2005.</p>
<p>6.- El artículo N°63, la letra ñ de la Ley N°18.695 Orgánica Constitucional de Municipalidades.</p>
<p>7.- Las facultades que me confiere el texto refundido de la ley N°18.695, Orgánica Constitucional de Municipalidades.</p>
<p>DECRETO</p>
<p>1.- Autorizase la salida del vehículo municipal que se indica, para trasladar a la siguiente delegación:</p>
<!--aqui va la tabla-->
'.$tabla.'
<p>2.- Disponese comisión de servicio y la realización de trabajos extraordinarios al conductor del Vehículo Municipal Placa Patente <strong>'.$vehiculo.'</strong>, <strong>'.$conductor.'</strong>, para que realice el traslado de <strong>'.$org.'</strong> señalada en el punto anterior, el día <strong>'.$fecha.'</strong> desde las <strong>'.$salida.'</strong> hasta las <strong>'.$regreso.'</strong> hrs. quien deberá <strong>'.$restricciones.'</strong>.</p>
<p>3.- Las horas extraordinarias <strong>efectivamente trabajadas y viatico correspondiente</strong>, serán cancelados al conductor, a través del Departamento de Administración y Finanzas Municipal, de acuerdo a lo percibido por parte de la organización, en relación al horario y la fecha establecida en el presente Decreto Alcaldicio</p>
<table>
<tr><td></td><td></td></tr>
<tr>
    <td style="text-align: center;font-size:12pt;">
        <strong>' . $nombreF1 . '</strong><br />
        <span style="font-size:12pt;">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;font-size:12pt;">
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
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: left;font-size:12pt;">
<span style="font-size:8pt;">'.$distribucion.'</span><br />
<strong style="font-size:8pt;"><u>DISTRIBUICION</u></strong>
<ul style="font-size:8pt;">
<li>Interesado</li>
<li>Control</li>
<li>Archivo Municipal</li>
<li>Archivo Depto.</li>
</ul>
</td>
<td>
</td>
</tr>
<tr><td style="font-size:10pt;"><strong>Folio: '.$folio.'</strong></td></tr>
</table>
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
