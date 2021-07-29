<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/class/decreto.php");

require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);




$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);
$unidad = $f->unidad;
$anio = $_POST['anio_dp'];

$proveedor = $_POST['nombre_dp'];

$glosa = trim($_POST['glosa_dp']);
$devengado = $_POST['devengado'];

$array_banco_debe = $_POST['valor_pagar_debe'];
$array_banco_haber = $_POST['valor_pagar_haber'];

$decreto = new decreto();
$folio = $decreto->folio;

$array_dp ;
$cuentas_texto = '';


$ul_lista = '<ul>';
$tr_imputacion = '';
foreach ($devengado as $i => $valor){

    $sql1 = "select * from libro_devengados  
            inner join pc_tipo_documentos on libro_devengados.tipo_documento=pc_tipo_documentos.id_tipo_doc
            where libro_devengados.numero_devengado='$valor' and libro_devengados.anio='$anio' ";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    $id_compra = $row1['id_compra'];
    mysql_query("update compras set estado_compra='DECRETO PAGO' WHERE id_compra='$id_compra' limit 1");
    $fila = $row1['nombre_tipo']." ".$row1['numero_documento'];

    if($i==5){
        $ul_lista.='</ul></td><td><ul>';
        $ul_lista.='<li>'.$fila.'</li>';
    }else{
        if($i==10){
            $ul_lista.='<li>Y OTROS.</li>';
        }else{
            $ul_lista.='<li>'.$fila.'</li>';
        }
    }
    $sql2 = "select * from libro_devengado_detalle 
              where anio='$anio' and numero_devengado='$valor' and cuenta like '2%'";
    $res2 = mysql_query($sql2);
    while($row2 = mysql_fetch_array($res2)){
        $array_dp[''.$row2['cuenta']]['nombre_cuenta'] = $row2['nombre_cuenta'];
        $array_dp[''.$row2['cuenta']]['debe'] += $row2['debe'];
        $array_dp[''.$row2['cuenta']]['haber'] += $row2['haber'];
    }

}
foreach ($array_dp as $columna => $array){
    $cuentas_texto .= '#&'.$columna;
    //detalle imputacion
    $tr_imputacion .= '<tr>
            <td>'.$columna.'</td>
            <td>'.$array['nombre_cuenta'].'</td>
            <td style="text-align:right;">$ '.number_format($array['haber'],0,'','.').'</td>
            <td style="text-align:right;">$ '.number_format($array['debe'],0,'','.').'</td>
            </tr>';
}
foreach ($array_banco_debe as $item_banco => $monto){
    $sql3 = "select * from pc_cuenta where codigo_general='$item_banco' limit 1";
    $row3 = mysql_fetch_array(mysql_query($sql3));
    $nombre_banco = $row3['nombre_cuenta'];


    //detalle imputacion
    $tr_imputacion .= '<tr>
            <td>'.$item_banco.'</td>
            <td>'.strtoupper(trim(limpiaCadena($nombre_banco))).'</td>
            <td style="text-align:right;">'.$array_banco_debe[$item_banco].'</td>
            <td style="text-align:right;">'.$array_banco_haber[$item_banco].'</td>
            </tr>';
}

$ul_lista .= '</ul>';



$monto = $_POST['total_a_pagar'];

$monto_en_letras = convertir_numero_a_letra($monto);


$firma1 = $_POST['firma1'];//secmun
$firma2 = $_POST['firma2'];//alcalde
$firma3 = $_POST['firma3'];//tesorero
$firma4 = $_POST['firma4'];//finanzas

$sql0 = "select * from decreto_pago where anio='$anio' order by numero_decreto desc limit 1";
$row0 = mysql_fetch_array(mysql_query($sql0));
if($row0){
    $numero_dp = $row0['numero_decreto']+1;
}else{
    $numero_dp = 1;
}

if($_POST['id_dp']){
    mysql_query("delete from decreto_pago WHERE id_dp='".$_POST['id_dp']."'");
    $numero_dp = $_POST['numero_dp'];
}


$sql0 = "insert into decreto_pago(numero_decreto,anio,firma_alcalde,firma_secmun,firma_tesorero,firma_finanza,monto_dp,id_empleado,cuentas_dp,folio,glosa)
         values('$numero_dp','$anio','$firma2','$firma1','$firma3','$firma4','$monto','$id_mio','$cuentas_texto','$folio','$glosa')";
mysql_query($sql0);


mysql_query("update formato_decreto set firma1='$firma1',firma2='$firma2', encargados='$encargados'
    where id_decreto='$id_formato_decreto'");
//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$sqlF3 = "select * from directivo where id_directivo='$firma3' limit 1";
$rowF3 = mysql_fetch_array(mysql_query($sqlF3));
$sqlF4 = "select * from directivo where id_directivo='$firma4' limit 1";
$rowF4 = mysql_fetch_array(mysql_query($sqlF4));
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
$nombreF3 = $rowF3['nombre_directivo'];
$nombreF4 = $rowF4['nombre_directivo'];
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

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Decreto de Pago');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Decreto de Pago, Documento');


//cabecera decreto
$head1 = "I. Municipalidad de Carahue                                            ";
$head2 = "Dirección de Administracion y Finanzas\nPortales #295, Carahue";
$title_pdf = "I. Municipalidad de Carahue";
$sub_title_pdf = "Dirección de Administración y Finanzas\nPortales #295, Carahue";
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
        font-size:0.7em;
        margin-top: 0px;
    }
    BLOCKQUOTE{
        font-size:1em;
    }
    table{
        font-size:0.7em;
    }
    span{
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:1.2em;
    margin-top: 5px;
    font-weight: bold;
    }
</style>
<p style="text-indent: 280px;">DECRETO DE PAGO Nº: <strong>'.$numero_dp.' / '.$anio.'</strong></p>
<p style="text-indent: 280px;">Carahue, '.date('d/m/Y').'</p>        


<p><strong>Vistos</strong></p>
<p>1.- El presupuesto de ingresos y gastos aprobado para el presente año
<br/>2.- Las facultades que me confiera la ley Nº 18.695/88</p>
<p style="font-size:1em;text-align:center;"><strong>DECRETO</strong></p>
<p>1.- Paguese, atraves del departamento de finanzas a:<br />
<table border="1px" style="font-size:1em;">
    <tr>
        <td><strong>'.strtoupper($proveedor).'</strong></td>
    </tr>
    <tr>
        <td>La suma de $'.number_format($monto,0,'','.').'.- ('.$monto_en_letras.')</td>
    </tr>
    <tr>
        <td style="height:80px;">Por: <br />'.strtoupper(limpiaCadena($glosa)).'</td>
    </tr>
    <tr>
        <td style="height:80px;">Correspondiente a los siguientes documentos:<br /><table>
        <tr>
            <td>
                '.$ul_lista.'
            </td>
        </tr>
        </table> </td>
    </tr>
</table></p>
<p>2.- Imputese el gasto de acuerdo al siguiente detalle:<br /><table width="100%" border="1px" style="font-size:1em;">
        <tr style="font-weight:bold;">
            <td style="width:20%;">Codigo</td>
            <td style="width:40%">Cuenta</td>
            <td style="width:20%;">Debe</td>
            <td style="width:20%;">Haber</td>
        </tr>
        '.$tr_imputacion.'
        </table><blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE</blockquote> 
</p>
<p></p>
<p></p>
<table style="font-size:1.1em;">
<tr>
    <td style="text-align: center;">
        <strong style="font-size:0.7em;">' . $nombreF1 . '</strong><br />
        <span style="">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;">
        <strong style="font-size:0.7em;">' . $nombreF2 . '</strong><br />
       <span style="">' . $cargoF2 . '</span>
    </td>
</tr>
</table>
<p></p>
<p></p>
<table border="1">
<tr>
    <td style="text-align:center;height:100px;padding-top: 20px;"><br/><br /><br /><hr style="width:50%;" />
    <strong style="font-size:0.8em;margin-top:20px;">' . $nombreF3 . '</strong><br />
       <span style="font-size:0.7em;">Tesorera Municipal</span>
    </td>
    <td style="text-align:center;height:100px;"><br/><br /><br /><hr style="width:50%;" />
    <strong style="font-size:0.8em;height:100px;">' . $nombreF4 . '</strong><br />
       <span style="font-size:0.7em;">Dir. de Admin. y Finanzas</span>
    </td>
</tr>
</table>
<table border="1">
<tr>
<td style="width:70%;">Cta. Cte.<br />CHEQUES<br />TALONARIO</td>
<td style="width:30%;">
    <table>
        <tr><td>Cheque Retirado Por:</td></tr>
        <tr><td></td></tr>
        <tr><td>Nombre</td></tr>
        <tr><td><hr style="width:100%;" /></td></tr>
        <tr><td>Rut</td></tr>
        <tr><td><hr style="width:100%;" /></td></tr>
        <tr><td>Firma</td></tr>
    </table>
</td>
</tr>
</table>
';
//echo $html;
//argamos los datos de este PDF
$decreto->tipo_decreto('Decreto de Pago',$unidad);
$decreto->datos_afectado('','','','Decreto de pago por un valor de: '.$monto_en_letras.' para el pago de '.$glosa);

$sql_pdf = "insert into pdf_respaldo(folio,nombre_doc,head1,head2,html) 
      values('$folio','Decreto de Pago','$head1','$head2','$html')";
mysql_query($sql_pdf);

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Decreto de Pago.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
