<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/folio.php");
include("../../php/objetos/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');

error_reporting(0);
session_start();
$myId = $_SESSION['id_empleado'];
$id_licencia = $_POST['id_licencia'];
$encargados = $_POST['encargados'];
//decreto 51
$por_orden = $_POST['por_orden'];           //Ordena a Administradora
$alcalde_s = $_POST['alcalde_s'];           //Subrogante Secretario
$secretario_s = $_POST['secretario_s'];     //Subrogante Alcalde
$decreto = $_POST['decreto_texto'];         //decreto
$firma1 = $_POST['firma1'];                 //firma izquierda
$firma2 = $_POST['firma2'];                 //firma derecha

$sql_1 = "select * from licencias inner join funcionario on id_empleado=reloj 
        where id_licencia='$id_licencia' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));


$id_empleado = $row_1['id_empleado'];

if(@$_POST['registro']){
    @$registrese = $_POST['registro'];
}else{
    $registrese = "";
}

$tipo_contrato = $row_1['tipo'];
if($tipo_contrato == 'MUNICIPAL'){
    $depto_doc = "Direccion de Administracion y Finanzas";
    $oficina_personal = 'Diego Portales #295';
}else{
    if($tipo_contrato == 'EDUCACION'){
        $depto_doc = "Departamento de Educación";
        $oficina_personal = 'Pedro de Valdivia';
    }else{
        $depto_doc = "Departamento de Salud";
        $oficina_personal = 'Villagran';
    }
}


$title_pdf =  $depto_doc;
$sub_title_pdf = "Oficina de Personal\n".$oficina_personal;



mysql_query("update decreto set encargados='$encargados', texto_decreto='$por_orden',texto_subrrogante_secretario='$alcalde_s',texto_subrrogante_alcalde='$secretario_s'
    ,firma1='$firma1',firma2='$firma2',decreto_texto='$decreto' 
    where id_decreto='5'");

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
if (@$_POST['decreto'] == 'decreto_texto') {
    $texto_decreto = "<p>2.- $decreto</li>>";
    //echo $decreto;
}

function sumarDias($fecha, $dia) {
    $nuevafecha = strtotime('+' . $dia . ' day', strtotime($fecha));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    return $nuevafecha;
}

$sql1 = "select * from licencias inner join funcionario on id_empleado=reloj
    where id_licencia='$id_licencia' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1['tipo']=='MUNICIPAL'){
    $row_1 = mysql_fetch_array(mysql_query("select * from funcionario inner join escalafon using(id_escalafon) where reloj ='".$row1['reloj']."' limit 1"));
    $cargo = $row_1['nombre_escalafon'] . ' (' . $row_1['grado'] . ')';
}else{
    if($tipo_contrato =='EDUCACION'){
        $cargo = 'Funcionario DAEM';

    }else{
        $cargo = 'Honorario';
    }

}
$f = new functionario($row1['reloj']);
$empleado = $row1['reloj'];
$estado_licencia = $row1['estado_licencia'];
$rut = $row1['rut'];
$nombre_funcionario = $f->nombre;


list($y1, $m1, $d1) = explode("-", $row1['fecha_inicio']);
list($y2, $m2, $d2) = explode("-", sumarDias($row1['fecha_inicio'], $row1['dias_licencia']-1));

$numero_licencia = $row1['numero_licencia'];
$desde = $d1 . "-" . $m1 . "-" . $y1;
$hasta = $d2 . "-" . $m2 . "-" . $y2;
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

foreach ($li as $l) {
    $texto .="<p>" . $k . ".- " . $_POST['' . $l] . "</p>";
    $k++;

}
if($_POST['sub_alcalde']){
    $s2 = $_POST['sub_alcalde'];
    $cargoF2 = "Alcalde (s)";
}else{
    $cargoF2 = $rowF2['cargo_directivo'];
}
if($_POST['sub_secretario']){
    $cargoF1 = "Secretario Municipal (s)";
}else{
    $cargoF1 = 'Secretario Municipal';
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Permiso Administrativo');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Permiso Administrativo, Documento');


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


//datos folio
$folio = new folio($myId,'Personal con Registro','Licencia Medica');
$texto_folio = "Desde ".$desde." hasta ".$hasta." [".$row1['tipo_licencia']."]";
$folio->datos_decreto($rut, $nombre_funcionario,'', $texto_folio);
$folio->datos_sql('licencias','id_licencia', $id_licencia);

$desde_inicio = $row1['fecha_inicio'];
$dias_licencia = $row1['dias_licencia'];

for($i = 0 ; $i < $dias_licencia ; $i++){
    $sql2 = "insert into ausencia(dia,id_empleado,id_justificacion)
                values('$desde_inicio','".$row1['id_empleado']."','5')";
    mysql_query($sql2);
    $desde_inicio = strtotime ( '+1 day' , strtotime ($desde_inicio ) );
    $desde_inicio = date('Y-m-d',$desde_inicio);

}


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
if ($estado_licencia != 'RECHAZADA') {
    $texto .= "<p><strong><u>DECRETO</u></strong></p>
<p>1.- Autorizase para hacer uso de Licencia Medica en las fechas que se indican al siguiente funcionario:</p>
";
    $tabla_detalle = '<table border="1px">
<tr style="background-color: antiquewhite;font-weight: bold;">
   <td>RUT</td>
   <td>Nombre</td>
   <td>Cargo</td>
   <td style="width:150px;">Nº Licencia</td>
   <td style="width:80px;">Desde</td>
   <td style="width:80px;">Desde</td>
</tr>
<tr>
<td>' . $rut . '</td>
   <td>' . $nombre_funcionario . '</td>
   <td>' . $cargo . '</td>
   <td>' . $numero_licencia . '</td>
   <td>' . $desde . '</td>
   <td>' . $hasta . '</td>
</tr>
</table>';
} else {
    $texto .= "<p><strong><u>DECRETO</u></strong></p>
<p>1.- Se ha Rechazado la Licencia Medica que afecta entre las fechas que se indican al siguiente funcionario:</p>
";
    $tabla_detalle = '<table border="1px">
<tr style="background-color: antiquewhite;font-weight: bold;">
   <td>RUT</td>
   <td>Nombre</td>
   <td>Cargo</td>
   <td style="width:150px;">Nº Licencia</td>
   <td style="width:80px;">Desde</td>
   <td style="width:80px;">Desde</td>
</tr>
<tr>
<td>' . $rut . '</td>
   <td>' . $nombre_funcionario . '</td>
   <td>' . $cargo . '</td>
   <td>' . $numero_licencia . '</td>
   <td>' . $desde . '</td>
   <td>' . $hasta . '</td>
</tr>
</table>';
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
' . $texto . '
' . $tabla_detalle . '
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
        <span style="font-size:12pt;">' . $cargoF1 . '</span></td>
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

<td></td>
<td></td>
</tr>
</table><br /><br />
<div style="position:absolute;bottom:10px">
<strong style="font-size:12pt;">Folio: '.$folio->codigo.'</strong>
</div>
';
//echo $html;
mysql_query("update licencias set estado_decreto='DECRETADA' where id_licencia='$id_licencia' ");
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);




// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
