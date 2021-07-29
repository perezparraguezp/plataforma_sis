<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
error_reporting(0);
$diasHabiles = $_POST['diasHabilesPA'];
$encargados = $_POST['encargados'];
//decreto 51
$por_orden = $_POST['por_orden']; //Ordena a Administradora
$alcalde_s = $_POST['alcalde_s']; //Subrogante Secretario
$secretario_s = $_POST['secretario_s']; //Subrogante Alcalde
$decreto = $_POST['decreto_texto']; //decreto
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];

mysql_query("update decreto set encargados='$encargados', texto_decreto='$por_orden',texto_subrrogante_secretario='$alcalde_s',texto_subrrogante_alcalde='$secretario_s'
    ,firma1='$firma1',firma2='$firma2',decreto_texto='$decreto' 
    where id_decreto='1'");
//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
$porOrden = "";
$SS = "";
$AS = "";
$texto_decreto = "";
if (@$_POST['decreto']=='decreto_texto') {
    $texto_decreto = "<p>2.- $decreto</li>>";
    //echo $decreto;
}
$id_solicitud = $_POST['id_solicitud'];
//Actualizar Estado Solicitud;
mysql_query("update solicitudausencia set estado_solicitud='DECRETADA' 
    where id_solicitud='$id_solicitud'");

$sql1 = "select * from solicitudausencia inner join funcionario on id_empleado=reloj 
    where id_solicitud='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$f = new functionario($row1['reloj']);
$empleado = $row1['reloj'];
mysql_query("insrt into estado_solicitud(id_solicitud,id_empleado,fecha_cambio,hora_cambio,estado,tabla)
        values('$id_solicitud','$empleado',current_date(),current_time(),'DECRETADA','solicitudausencia')");
$nombre_funcionario = $f->nombre;
$cargo = "Honorario";
mysql_query("update funcionario set d_PA='$diasHabiles' where='" . $row1['reloj'] . "'");

$sql2 = "select * from solicitudausencia inner join funcionario on id_responsable=reloj 
    inner join escalafon using(id_escalafon)
    where id_solicitud='$id_solicitud' limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));
$f2 = new functionario($row2['reloj']);
$responsable = $f2->nombre;

//fecha
$fecha = $_POST['fecha'];
$i = 0;
$m = 0;
$completo = 0;
$mediodia = 0;
$detalleDias = "<table>";
mysql_query("update fechas_solicitud set permitida='0' where id_solicitud='$id_solicitud'");
foreach (@$fecha as $t) {
    $pos = strrpos($t, "=");
    if ($pos != false) {
        list($dia, $j) = explode("=", $t);
        $fechas[$i] = $dia;
        $justificaciones[$i] = $j;
        if ($j == 3) {
            $detalleDias .= "<tr><td>" . $dia . "</td><td>Dia Completo</td></tr>";
            $i++;
        } else {
            if ($j == 1) {
                $m++;
                $detalleDias .= "<tr><td>" . $dia . "</td><td>Media Mañana</td></tr>";
            } else {
                $m++;
                $detalleDias .= "<tr><td>" . $dia . "</td><td>Media Tarde</td></tr>";
            }
        }
        list($d, $m, $a) = explode("/", $dia);
        $fecha_sql = $a . "-" . $m . "-" . $d;
        $sqlACEPTAR = "update fechas_solicitadas set permitida='1' 
            where fecha_solicitud='$fecha_sql' and id_solicitud='$id_solicitud'";
        mysql_query($sqlACEPTAR);
    }
}
$sqlM = "select count(*) as total from fechas_solicitadas 
    where fecha_solicitud='$fecha_sql' and id_solicitud='$id_solicitud' 
    and jornada!='3' and permitida='1'
    group by id_solicitud 
    limit 1";

$rowM = mysql_fetch_array(mysql_query($sqlM));
if($rowM){
    $m = $rowM['total'];
}else{
    $m = 0;
}
$mm = $m / 2;
$md = $m % 2;
if ($md == 1) {
    $medios = "1/2";
} else {
    $medios = "";
}
if ($mm >= 1) {
    $medios = "1/2";
}
if($i == 0){
    $totalDiasPA = $medios;
}else{
    if($i > 1){
        $totalDiasPA = $i." Dias Completos";
    }else{
        $totalDiasPA = $i." Dia Completo";
    }
    if($m > 0){
        if($m > 1){
            $totalDiasPA.= "<br />".$m." Medios dias";
        }else{
            $totalDiasPA.= "<br />".$m." Medio dia";
        }
        
    }
}   

$detalleDias .= "</table>";
for ($m = 0; $m < $i; $m++) {
    if ($justificaciones[$m] == 3) {//Dia Completo
        $completo++;
    } else {
        $mediodia++;
    }
}
if ($completo == 0) {
    if ($mediodia > 1) {
        $jornada = $mediodia . " Medios dias ";
    } else {
        $jornada = $mediodia . " Medio dia";
    }
} else {
    if ($completo > 1) {
        $jornada = $completo . " Dias Completos ";
    } else {
        $jornada = $completo . " Dia Completo ";
    }
    if ($mediodia != 0) {
        if ($mediodia > 1) {
            $jornada .= " y " . $mediodia . " Medio Dias";
        } else {
            $jornada .= " y " . $mediodia . " Medio Dia";
        }
    }
}
//echo $completo."-".$mediodia." -- >".$jornada."<br />";
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

//texto 
$texto = '';
$li = $_POST['li'];
$k = 1;
$cargoF2 = $rowF2['cargo_directivo'];
foreach ($li as $l) {
    $texto .="<p>" . $k . ".- " . $_POST['' . $l] . "</p>";
    $k++;
    //validamos texto
    if ($_POST['' . $l] == $por_orden) {
        $porOrden = "POR ORDEN DEL SR. ALCALDE";
        $cargoF2 = $rowF2['cargo_directivo'];
    }
    if ($_POST['' . $l] == $alcalde_s) {
        $cargoF2 = "Alcalde ";
        $AS = " (S)";
    }
    if ($_POST['' . $l] == $secretario_s) {
        $SS = " (S)";
    }
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Permiso Administrativo');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Permiso Administrativo, Documento');

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
$obs = "";
if ($row1['texto_estado'] != '') {
    $obs = '<br /><span>Obs: <strong>' . $row1['texto_estado'] . '</strong></span>';
}

$texto .= "<p><strong><u>DECRETO</u></strong></p>
<p>1.- Autorizase para hacer uso de Permiso Administrativo en las fechas que se indican al siguiente funcionario:</p>
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
   <td style="width:150px;">Dias</td>
   <td style="width:200px;">Detalle</td>
</tr>
<tr>
   <td>' . $nombre_funcionario . '</td>
   <td>' . $cargo . '</td>
   <td>' . $totalDiasPA . '</td>
   <td>' . $detalleDias . '</td>
</tr>
</table>
' . $texto_decreto . '
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
    <td></td>
    <td></td>
</tr>
<tr>
    <td style="text-align: left;font-size:12pt;"><br /><strong>' . $encargados . '</strong></td>
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
//echo $html;
//argamos los datos de este PDF
mysql_query("update solicitudausencia set pdf='$html' 
    where id_solicitud='$id_solicitud'");
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
