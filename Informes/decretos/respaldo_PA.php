<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);
$id_formato_decreto = $_POST['id_formato_decreto'];
mysql_query("delete from vistos_decreto where id_decreto='$id_formato_decreto'");

//error_reporting(0);
$id_solicitud = $_POST['id_solicitud'];
$sql_1 = "select * from solicitudausencia where id_solicitud='$id_solicitud' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));

if(@$_POST['registro']){
    @$registrese = $_POST['registro'];
}else{
    $registrese = "";
}
$vistos = $_POST['item'];
$encargados = $_POST['encargados'];
//decreto 51
$decreto = $_POST['decreto_texto']; //decreto
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];
mysql_query("update formato_decreto set firma1='$firma1',firma2='$firma2', encargados='$encargados'
    where id_decreto='$id_formato_decreto'");
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
//Actualizar Estado Solicitud;
mysql_query("update solicitudausencia set estado_solicitud='DECRETADA' 
    where id_solicitud='$id_solicitud'");

$sql1 = "select * from solicitudausencia
    inner join funcionario on id_empleado=reloj
    where id_solicitud='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$f = new functionario($row1['id_empleado']);
$rut = $row1['rut'];
$empleado = $row1['reloj'];
$tipo_contrato = $row1['tipo'];

$nombre_funcionario_informe = $row1['paterno']." ".$row1['materno'].", ".$row1['nombres'];

$sql1 = "select * from solicitudausencia
    inner join funcionario on id_empleado=reloj
    inner join escalafon using(id_escalafon)
    where id_solicitud='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1){
    $cargo = $row1['nombre_escalafon'] . ' (' . $row1['grado'] . ')';
}else{
    if($tipo_contrato =='EDUCACION'){
        $cargo = 'Funcionario DAEM';
    }else{
        if($tipo_contrato =='SALUD'){
            $cargo = 'Funcionario de Salud';

        }else{
            $cargo = $f->contrato;
        }
    }

}

mysql_query("insert into estado_solicitud(id_solicitud,id_empleado,fecha_cambio,hora_cambio,estado,tabla)
        values('$id_solicitud','$empleado',current_date(),current_time(),'DECRETADA','solicitudausencia')");
mysql_query("update funcionario set d_PA='$diasHabiles' where='" . $row1['reloj'] . "'");


if($tipo_contrato == 'MUNICIPAL'){
    $depto_doc = "Direccion de Administracion y Finanzas";
    $oficina_personal = 'Diego Portales #295';
    $f2 = new functionario($row2['reloj']);
    $responsable = $f2->nombre;
}else{
    if($tipo_contrato == 'EDUCACION'){
        $depto_doc = "Departamento de Educación";
        $oficina_personal = 'Villagrán #240, 2do Piso';
        $f2 = new functionario($row2['reloj']);
        $responsable = $f2->nombre;
    }else{
        $depto_doc = "Departamento de Salud";
        $oficina_personal = 'Villagrán #256, 2do Piso';
        $f2 = new functionario($row2['reloj']);
        $responsable = $f2->nombre;
    }
}
if(@$_POST['registro']){
    @$registrese = $_POST['registro'];
}else{
    $registrese = "";
}


//fecha
$fecha = $_POST['fecha'];
$i = 0;
$medios_dias = 0;
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
                $medios_dias++;
                $detalleDias .= "<tr><td>" . $dia . "</td><td>Media MaÃ±ana</td></tr>";
            } else {
                $medios_dias++;
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
$mm = $medios_dias / 2;
$md = $medios_dias % 2;
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
        $totalDiasPA = $i." Dia Completo  ";

    }
    if($medios_dias > 0){
        if($medios_dias > 1){
            $totalDiasPA.= "y Medios dias";
        }else{
            $totalDiasPA.= "y  Medio dia";
        }

    }
}

//$totalDiasPA = "Dias $i, Medios Dias ".($medios_dias);

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
$k = 1;
$check = $_POST['check'];
$vistos = $_POST['item'];
$texto_vistos = '';
foreach ($vistos as $i => $val) {
    if($check[$i]){
        $texto_vistos .="<p>$k .-" ." $val</p>";
        $k++;
    }

}

foreach ($vistos as $i => $val) {
    if($check[$i]){
        $obligacion='SI';
    }else{
        $obligacion='NO';
    }
    mysql_query("insert into vistos_decreto(id_decreto,visto,obligacion)
    values('$id_formato_decreto','$val','$obligacion')");
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Permiso Administrativo');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Permiso Administrativo, Documento');


//Generacion de Folio

$tipo_decreto = $_POST['tipo_decreto'];
$atributo_decreto = "&&TRANSPARENCIA&";
$referencia_decreto = 'Permiso Administrativo Fecha(s): ';
$f = new functionario($row1['id_empleado']);

$nombre_funcionario = $f->nombre;

$sql = "insert into decretos(id_empleado,fecha_creacion,tipo_decreto,atributos,referencia_afectado,descripcion,afecta_sobre,rut_afectado,nombre_afectado,sector_afectado,texto_afectado,estado_decreto)
        values('$id_mio',current_date(),'Personal con Registro','$atributo_decreto','$referencia_decreto','Permiso WEB','Una Sola Persona','$rut','$nombre_funcionario','$nombre_funcionario','','EN CREACION')";

mysql_query($sql);
$row1 = mysql_fetch_array(mysql_query("select * from decretos order by id_interno desc limit 1"));
$id_interno = $row1['id_interno'];

$folio = $f_mio->depto."-".date('Y')."-".$id_interno;

mysql_query("update decretos set folio = '$folio',tabla_sql='solicitudausencia',id_sql='id_solicitud',valor_sql='$id_solicitud' where id_interno='$id_interno' ");



$sql5 ="insert into estado_decreto(id_decreto,folio_decreto,estado_old,estado_new,fecha_mod,hora_mod,id_empleado)
        values('$id_interno','$folio','NULL','CREADO',CURRENT_DATE(),CURRENT_TIME(),'$id_mio')";
mysql_query($sql5);

$title_pdf = "$depto_doc                                            ";
$sub_title_pdf = "Oficina de Personal\n$oficina_personal";
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

$texto_decreto = "";
if ($_POST['decreto_inferior']) {
    $texto_decreto = "<p>2.- ".$_POST['decreto_inferior']."</li>";
    //echo $decreto;
}

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
' . $texto_vistos . '

<p><strong><u>DECRETO</u></strong></p>
<p>1.- Autorizase para hacer uso de Permiso Administrativo en las fechas que se indican al siguiente funcionario:</p>
<table border="1px">
<tr style="background-color: antiquewhite;font-weight: bold;">
    <td style="width:80px;">RUN</td>
   <td>Nombre</td>
   <td style="width:100px;">Cargo</td>
   <td style="width:150px;">Dias</td>
   <td style="width:200px;">Detalle</td>
</tr>
<tr>
   <td>' . $rut . '</td>
   <td>' . $nombre_funcionario_informe . '</td>
   <td>' . $cargo . '</td>
   <td>' . $totalDiasPA . '</td>
   <td>' . $detalleDias . '</td>
</tr>
</table>
' . $texto_decreto . '
<blockquote>ANOTESE'.$registrese.', COMUNIQUESE Y ARCHIVESE<blockquote>' . $porOrden . '</blockquote></blockquote>
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
<td>
<td></td>
</tr>
</table><br /><br />
<div style="position:absolute;bottom:10px">
<strong style="font-size:12pt;">Folio: '.$folio.'</strong>
</div>
';
//echo $html;
//argamos los datos de este PDF
mysql_query("update solicitudausencia set pdf='$html' 
    where id_solicitud='$id_solicitud'");

$sql6 = "update decretos set 
        texto_referencia='Permiso Administrativo, Fecha(s): <br />$detalleDias' where folio='$folio'";
mysql_query($sql6);


// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
