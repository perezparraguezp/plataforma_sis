<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");

include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');


function sumarDias($fecha, $dia) {
    $nuevafecha = strtotime('+' . $dia . ' day', strtotime($fecha));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    return $nuevafecha;
}



session_start();
$myId = $_SESSION['id_empleado'];
//error_reporting(0);
//Eliminamos los textos del documento
list($folio_org,$nombre_org,$rut_org,$id_org)= explode(" | ",$_POST['id_org']);

$rut_solicitante = str_replace(".","",$_POST['rut_solicitante']);

$para = trim($_POST['para']);
if($para == 'OTROS'){
    $para = $_POST['otra'];
}
$firma = trim($_POST['firma']);
$otro = trim($_POST['otro']);

$s = trim($_POST['s']);
$numero = $_POST['numero'];
$fecha_emision = $_POST['fechaemision'];

$titulo_documento = "<h4>CERTIFICADO PROVISORIO<br />ORGANIZACION COMUNITARIA</h4>";

$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);



mysql_query("update contador_documentos set numero_pj='$numero'");


$sql1 = "select * from organizaciones_sociales where id_org='$id_org' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$nombre_org = $row1['nombre'];
$direccion_org = $row1['direccion'];

$numero_municipal = $row1['folio_org'];
$rut_org = $row1['rut_org'];
$estado_org = $row1['estado_org'];
$fecha_creacion = fechaNormal($row1['fecha_creacion']);
$numero_civil = $row1['numero_registro'];
$tipo_org = $row1['tipo'];


$sql2 = "select count(*) as total, year(desde) as anio from org_socios  where id_org='$id_org' ORDER BY anio limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));
if($row2){
    $socios = $row2['total'];
    $anio_socios = '['.$row2['anio'].']';
}else{
    $socios = 0;
    $anio_socios = '';
}


    $sql3 = "select * from directiva  
                WHERE id_org='$id_org' and estado='ACTIVA' 
                order by hasta desc limit 1";
    $row3 = mysql_fetch_array(mysql_query($sql3));

    $desde = $row3['desde'];
    $hasta = $row3['hasta'];
    $valido_hasta = fechaNormal(sumarDias($desde,30));


    if($row3['hasta'] >= date('Y-m-d')){
        $vigencia = "<strong>VIGENTE HASTA:</strong> ".fechaNormal($hasta);
    }else{
        $vigencia = "<strong>EXPIRADA CON FECHA:</strong> ".fechaNormal($hasta);
    }
//$vigencia = '';

    $directorio = "
<hr style='width: 100%;' />
<h4>CERTIFICADO DE VIGENCIA<br />DIRECTORIO ORGANIZACION COMUNITARIA</h4>
<h6>DATOS DEL DIRECTORIO</h6>
                <table><tr><td></td><td>".$vigencia."</td></tr></table><br /><br />";
    $directorio .= '<table style="width: 100%;border:1px solid;font-size: 12px;"  >
        <tr style="background-color: #ccfdff;font-weight: bold;">
            <td style="width: 20%;">CARGO</td>
            <td style="width: 60%;">NOMBRE</td>
            <td style="width: 20%;">R.U.N.</td>
        </tr>
        <tr><td></td><td></td><td></td></tr>';

    if($row3['hasta']>date('Y-m-d')){
        $p1 = new persona($row3['presidente']);

        $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">PRESIDENTE(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
        $p1 = new persona($row3['secretario']);
        $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">SECRETARIO(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
        $p1 = new persona($row3['tesorero']);
        $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">TESORERO(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
        if($_POST['SUPLENTES']=='SI'){
            $p1 = new persona($row3['otro1']);
            $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">1º SUPLENTE(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
            $p1 = new persona($row3['otro2']);
            $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">2º SUPLENTE(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
            $p1 = new persona($row3['otro3']);
            $directorio .= '<table>
        <tr>
            <td  style="width: 20%;">3º SUPLENTE(A)</td>
            <td style="width: 60%;">'.($p1->nombre_completo).'</td>
            <td style="text-align: right;width: 20%;">'.$p1->rut.'</td>
        </tr>';
        }

    }else{

        $directorio .= "<table>
        <tr>
            <td>PRESIDENTE(A)</td>
            <td></td>
            <td style='text-align: right;'></td>
        </tr>";

        $directorio .= "<table>
        <tr>
            <td>SECRETARIO(A)</td>
            <td></td>
            <td style='text-align: right;'></td>
        </tr>";

        $directorio .= "<table>
        <tr>
            <td>TESORERO(A)</td>
            <td></td>
            <td style='text-align: right;'></td>
        </tr>";
        $directorio .= "<table>
        <tr>
            <td>1º SUPLENTE</td>
            <td></td>
            <td style='text-align: right;'></td>
        </tr>";
        $directorio .= '<table border="1">
        <tr>
            <td>2º SUPLENTE</td>
            <td></td>
            <td style="text-align: right;"></td>
        </tr>';
        $directorio .= "<table>
        <tr>
            <td>3º SUPLENTE</td>
            <td></td>
            <td style='text-align: right;'></td>
        </tr>";
    }

    $directorio .= "</table>";



$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);

$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;



// create new PDF document
define ('PDF_PAGE_FORMAT', 'A4');
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
$pdf->AddPage();
// set text shadow effect



$html = '
<style type="text/css">
    table{
        font-size: 12pt;
    }
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
        font-size:12pt;
        text-align: left;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h5{
    font-size: 1.2em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    }
</style>
<p style="text-align: right;">'.$numero.' / '.date('Y').'</p>
'.$titulo_documento.'
<h6>PERSONALIDAD JURIDICA</h6>
<table style="width:100%;font-size:0.8em;">
    <tr>
        <td colspan="2" style="text-align:right">Fecha Emisión: '.fechaNormal($fecha_emision).'</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:left"><strong></strong></td>
    </tr>
    <tr>
        <td style="width:200px;">Nombre Organizacion</td>
        <td>'.$nombre_org.'</td>
    </tr>
    <tr>
        <td>RUT</td>
        <td>'.$rut_org.'</td>
    </tr>
    <tr>
        <td>Dirección</td>
        <td>'.$direccion_org.'</td>
    </tr>
    <tr>
        <td>Estado Persona Juridica</td>
        <td>'.$estado_org.'</td>
    </tr>
    <tr>
        <td>Numero de Registro Civil</td>
        <td>'.$numero_civil.'</td>
    </tr>
    <tr>
        <td>Numero Registro Municipal</td>
        <td>'.$numero_municipal.'</td>
    </tr>
    <tr>
        <td>Fecha de Inscripcion</td>
        <td>'.$fecha_creacion.'</td>
    </tr>
    <tr>
        <td>Tipo de Organizacion</td>
        <td>'.$tipo_org.'</td>
    </tr>
    <tr>
        <td>Numero de Socios</td>
        <td>'.$socios.'</td>
    </tr>

</table>
<br /><br />
'.$directorio.'
<br /><br />
<span>Lo anterior, según los antecedentes proporcionados por la organización,
y que se mantiene en archivo de la dirección de desarrollo comunitario.</span>
<br /><br />
<span>Se extiende el presente certificado a petición de la organizacion, para ser presentado en: '.$para.'</span>
<br /><br />
<span>El Presente Certificao tiene una vigencia de 30 días desde la fecha de emisión. Para Generar nuevos certificados una vez expirado el plazo, debera concurrir a Oficinas de Registro Civil para poder obtener un nuevo certificado.</span>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<h5>'.strtoupper($firma).'<br/>SECRETARIO MUNICIPAL'.$s.'</h5>

';
//echo $html;
//argamos los datos de este PDF



// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);


$sql3 = "insert into historial_certificado_pj(id_org,rut_solicitante,id_empleado,para,pdf,numero_certificado) 
        values('$id_org','$rut_solicitante','$myId','$para','','$numero')";
mysql_query($sql3);
//echo $sql3;
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.



$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
