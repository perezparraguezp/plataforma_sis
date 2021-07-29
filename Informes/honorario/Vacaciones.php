<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

//funciones para calcular dias habiles
function sumarDias($fecha, $dia) {
    $nuevafecha = strtotime('+' . $dia . ' day', strtotime($fecha));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    return $nuevafecha;
}

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $dia;
}

function Feriado($anio, $mes, $dia) {
    $sqlFeriado = "select * from feriado where dia='$dia' and mes='" . (int) $mes . "' limit 1";
    $rowFeriado = mysql_fetch_array(mysql_query($sqlFeriado));
    if ($rowFeriado) {
        return true;
    } else {
        return false;
    }
}

function dias_transcurridos($fecha_i, $fecha_f) {
    $d = $fecha_i;
    $validar = false;
    $total = 0;
    while ($validar == false) {
        if ($d == $fecha_f) {
            $validar = true;
        }
        list($anio, $mes, $dia) = explode("-", $d);
        $semana = diaSemana($anio, $mes, $dia);
        if ($semana != 0 && $semana != 6) {//Sabados y Domingos
            $feriado = Feriado($anio, $mes, $dia);
            if ($feriado == false) {
                $total++;
            }
        }
        $d = sumarDias($d, 1);
    }
    return $total;
}

$diasHabiles = $_POST['diasHabilesV'];
$encargados = $_POST['encargados'];
//decreto 51
//decreto 51
$por_orden = $_POST['por_orden']; //Ordena a Administradora
$alcalde_s = $_POST['alcalde_s']; //Subrogante Secretario
$secretario_s = $_POST['secretario_s']; //Subrogante Alcalde
$decreto = $_POST['decreto_texto']; //decreto
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];

mysql_query("update decreto set encargados='$encargados', texto_decreto='$por_orden',texto_subrrogante_secretario='$alcalde_s',texto_subrrogante_alcalde='$secretario_s'
    ,firma1='$firma1',firma2='$firma2',decreto_texto='$decreto' 
    where id_decreto='4'");

//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$SS = "";
$AS = "";
$porOrden = "";
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
$id_solicitud = $_POST['id_solicitud'];
//Actualizar Estado Solicitud;
mysql_query("update vacaciones set estado_v='DECRETADA' where id_vacaciones='$id_solicitud'");
$sql1 = "select * from vacaciones inner join funcionario on id_empleado=reloj 
    where id_vacaciones='$id_solicitud' limit 1";

$row1 = mysql_fetch_array(mysql_query($sql1));
$f = new functionario($row1['reloj']);
$empleado = $row1['reloj'];
mysql_query("insrt into estado_solicitud(id_solicitud,id_empleado,fecha_cambio,hora_cambio,estado,tabla)
        values('$id_solicitud','$empleado',current_date(),current_time(),'DECRETADA','solicitudausencia')");
$f2 = new functionario($empleado);
$responsable = $f2->nombre;
$nombre_funcionario = $f->nombre;
$cargo = "Honorario";
mysql_query("update funcionario set d_V='$diasHabiles' where='" . $row1['reloj'] . "'");

//fechas
$desde = $_POST['desde_v'];
$hasta = $_POST['hasta_v'];
$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');

if(@$_POST['decreto']){
    $texto_decreto = "<p>2.- ".$decreto."</p>";
}else{
    $texto_decreto = "";
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Feriado Legal');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Feriado Legal, Documento');

$title_pdf = "Direccion de Administracion y Finanzas                                            ";
$sub_title_pdf = "Oficina de Personal\nDiego Portales #295";
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
//texto 
$texto = '';
$li = $_POST['li'];
$i = 1;
foreach ($li as $k => $l) {
    $texto .="<p>" . $i . ".- " . $_POST['' . $l] . "</p>";
    $i++;
}
$cargoF2 = 'Alcalde ';
foreach ($li as $k => $l) {
    if ($k == 2) {
        $porOrden = 'POR ORDEN DEL SR. ALCALDE';
        $cargoF2 = $rowF2['cargo_directivo'];
    } else {
        if ($k == 3) {
            $cargoF2 = 'Alcalde (S)';
        }
    }
    if ($k == 4) {
        $SS = ' (S)';
    }
}
//Dias 
$total_dias = dias_transcurridos($desde, $hasta);
$obs = "<br /><span>Obs: <strong>Se acepta conforme a lo solicitado</strong></span>";
$texto .= "<p><strong><u>DECRETO</u></strong></p>
<p>1.-Autorizase para hacer uso de Feriado Legal en las fechas que se indican al siguiente funcionario:</p>

";

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
' . $texto . '
<table border="1px">
<tr style="background-color: antiquewhite;font-weight: bold;">
   <td>Nombre</td>
   <td>Cargo</td>
   <td>Dias</td>
   <td>Desde</td>
   <td>Hasta</td>
</tr>
<tr>
   <td>' . $nombre_funcionario . '</td>
   <td>' . $cargo . '</td>
   <td>' . $total_dias . '</td>
   <td>' . $desde . '</td>
   <td>' . $hasta . '</td>
</tr>
</table>
'.$texto_decreto.'
<blockquote>ANOTESE COMUNIQUESE Y ARCHIVESE<blockquote>' . $porOrden . '</blockquote></blockquote>
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
        <span style="font-size:12pt;">Secretario Municipal' . $SS . '</span></td>
    <td style="text-align: center;font-size:14pt;">
        <strong>' . $nombreF2 . '</strong><br />
        ' . $cargoF2 . '
    </td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
<tr>
    <td style="text-align: left;font-size:12pt;">
        <strong>' . $encargados . '</strong>
    </td>
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
<li>Archivo Municipal</li>
<li>Archivo Personal</li>
<li>Interesado</li>
</ul>
</td>
<td>
<span>Aprobado por: <strong>' . $responsable . '</strong></span>
    ' . $obs . '
</td>
</tr>
</table>

';

mysql_query("update vacaciones set pdf='$html' 
    where id_vacaciones='$id_solicitud'");
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
