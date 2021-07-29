<?php

include("../../php/config.php");
include("../../php/class/decreto.php");

include '../../php/objetos/persona.php';
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento



$rut = str_replace(".","",$_POST['rut']);
$anio = $_POST['anio'];
$encargado = $_POST['encargado'];
list($encargadoR, $encargadoN) = split('[/]', $encargado);

$totalB = 0;
$totalR = 0;

$sql2 = "SELECT MAX(nro_cert) nro_cert FROM certificado_nro1_honorarios where anio='$anio'";
$rows = mysql_fetch_array(mysql_query($sql2));
$nro_cert = 1;


$sqll = "select * from certificado_nro1_honorarios where rut_persona='$rut' and anio='$anio' limit 1";
$rows = mysql_fetch_array(mysql_query($sqll));

    if($rows['rut_persona']!="") {
        //$html = $rows['html'];
        $nro_cert = $rows['nro_cert'];// mismo certificado
        $insert = false;
    }
    else {
        if ($rows['nro_cert'] >= 1) {
            $nro_cert = $rows['nro_cert'] + 1;
            $insert = true;
        } else {
            $nro_cert = 1;
            $insert = true;
        }
    }



    $factores = Array(0,0,0,0,0,0,0,0,0,0,0,0); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE********************************************
    $brutos = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $impuestos = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $brutosactualizados = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $retencionesactualizadas = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $sql1 = "select factor,mes from factores_actualizacion_honorarios where anio='$anio' ORDER by mes ";
    $res = mysql_query($sql1);
    $i = 0;
    while ($row = mysql_fetch_array($res)) {
        $factores[$i] = $row['factor'];
        $i++;
    }
    $sql1 = "select id,month(fecha_dp) as mes,bruto,neto,impuestos from honorarios inner join plantilla_pago using (id_plantilla_pago)
              where rut='$rut' and anio='$anio' and estado='PAGADA NETO'";
    $res = mysql_query($sql1);
        while ($row = mysql_fetch_array($res)) {
            $id = $row['id'];
            switch ($row['mes']) {
                case 1:
                    $brutos[0] = $brutos[0] + $row['bruto'];
                    $impuestos[0] = $impuestos[0] + $row['impuestos'];
                    break;
                case 2:
                    $brutos[1] = $brutos[1] + $row['bruto'];
                    $impuestos[1] = $impuestos[1] + $row['impuestos'];
                    break;
                case 3:
                    $brutos[2] = $brutos[2] + $row['bruto'];
                    $impuestos[2] = $impuestos[2] + $row['impuestos'];
                    break;
                case 4:
                    $brutos[3] = $brutos[3] + $row['bruto'];
                    $impuestos[3] = $impuestos[3] + $row['impuestos'];
                    break;
                case 5:
                    $brutos[4] = $brutos[4] + $row['bruto'];
                    $impuestos[4] = $impuestos[4] + $row['impuestos'];
                    break;
                case 6:
                    $brutos[5] = $brutos[5] + $row['bruto'];
                    $impuestos[5] = $impuestos[5] + $row['impuestos'];
                    break;
                case 7:
                    $brutos[6] = $brutos[6] + $row['bruto'];
                    $impuestos[6] = $impuestos[6] + $row['impuestos'];
                    break;
                case 8:
                    $brutos[7] = $brutos[7] + $row['bruto'];
                    $impuestos[7] = $impuestos[7] + $row['impuestos'];
                    break;
                case 9:
                    $brutos[8] = $brutos[8] + $row['bruto'];
                    $impuestos[8] = $impuestos[8] + $row['impuestos'];
                    break;
                case 10:
                    $brutos[9] = $brutos[9] + $row['bruto'];
                    $impuestos[9] = $impuestos[9] + $row['impuestos'];
                    break;
                case 11:
                    $brutos[10] = $brutos[10] + $row['bruto'];
                    $impuestos[10] = $impuestos[10] + $row['impuestos'];
                    break;
                case 12:
                    $brutos[11] = $brutos[11] + $row['bruto'];
                    $impuestos[11] = $impuestos[11] + $row['impuestos'];
                    break;
            }
            $brutos[12] = $brutos[12] + $row['bruto'];
            $impuestos[12] = $impuestos[12] + $row['impuestos'];

        }
        for ($i = 0; $i < 12; $i++) {
            $brutosactualizados[$i] = $brutos[$i] * $factores[$i];
            $retencionesactualizadas[$i] = $impuestos[$i] * $factores[$i];

            $totalB += round($brutosactualizados[$i]);
            $totalR += round($retencionesactualizadas[$i]);

        }

        $obj = new persona($rut);
        $name = $obj->nombre_completo;

        $dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
        $meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $a = date('Y');
        $m = date('m');
        $d = date('d');
        $dia = diaSemana($a, $m, $d);
        $fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

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
       text-align: center;
       }
       h5{
       font-size: 1em;;
       text-align: center;
       bottom: 10px;
       position: absolute;
       }
       h4{
       text-align: center;
       }
       </style>
    
    
       <table>
           <tr> <td></td><td style="text-align: right;">Certificado Nº</td>  <td><strong>' . $nro_cert . '</strong></td></tr>
           <tr> <td></td><td style="text-align: right;">Ciudad</td>  <td><strong>Carahue</strong></td></tr>
           <tr> <td></td><td style="text-align: right;">Fecha</td>  <td><strong>' . $fecha . '</strong></td></tr>
    
       </table>
       <table>
           <tr><td></td></tr>
           <tr><td>Razon Social de la Empresa  <strong>MUNICIPALIDAD DE CARAHUE</strong></td></tr>
           <tr><td>Rut Nº  <strong>69.190.500-4</strong></td></tr>
           <tr><td>Direccion   <strong>PORTALES #295, CARAHUE</strong></td></tr>
           <tr><td>Giro o Actividad    <strong>SERVICIO PUBLICO</strong></td></tr>
           <tr><td></td></tr>
           <tr ><td><strong>CERTIFICADO Nº 1 SOBRE HONORARIOS</strong></td></tr>
           <tr ><td></td></tr>
           <tr><td >La Empresa, Sociedad, o Institución
               <strong>MUNICIPALIDAD DE CARAHUE</strong> Certifica que al Sr.
               <strong>' . $name . '</strong> Rut Nº
               <strong>' . $rut . '</strong> Durante el año
               <strong>' . $anio . '</strong> se le han pagado las siguientes rentas por el concepto de <strong>HONORARIOS</strong>,
           y sobre las cuales se practicaron las siguientes retenciones de impuesto que se señalan.</td></tr>
           <tr ><td></td></tr>
    
       </table>
    
       <table style="width: 100%;" border="1">
           <tr>
               <td width="11%" ROWSPAN="2" style="text-align: center;">PERIODOS</td>
               <td ROWSPAN="2" style="text-align: center;">HONORARIO BRUTO</td>
               <td ROWSPAN="2" style="text-align: center;">RETENCIÓN DE IMPUESTO</td>
               <td ROWSPAN="2" style="text-align: center;">FACTOR DE ACTUALIZACIÓN</td>
               <td width="37%" colspan="2" style="text-align: center;">MONTOS ACTUALIZADOS</td>
           </tr>
           <tr>
    
               <td style="text-align: center;">HONORARIO BRUTO</td>
               <td style="text-align: center;">RETENCIONES DE IMPUESTO</td>
           </tr>
           <tr>
               <td>ENERO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[0], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[0], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[0] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[0], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[0], 0, ",", ".") . '</td>
           </tr>
           <tr>
               <td>FEBRERO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[1], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[1], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[1] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[1], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[1], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>MARZO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[2], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[2], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[2] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[2], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[2], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>ABRIL</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[3], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[3], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[3] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[3], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[3], 0, ",", ".") . '</td>
           </tr>
    
    
           <tr>
               <td>MAYO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[4], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[4], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[4] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[4], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[4], 0, ",", ".") . '</td>
           </tr>
    
    
           <tr>
               <td>JUNIO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[5], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[5], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[5] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[5], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[5], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>JULIO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[6], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[6], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[6] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[6], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[6], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>AGOSTO</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[7], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[7], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[7] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[7], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[7], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>SEPTIEMBRE</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[8], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[8], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[8] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[8], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[8], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>OCTUBRE</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[9], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[9], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[9] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[9], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[9], 0, ",", ".") . '</td>
           </tr>
    
    
           <tr>
               <td>NOVIEMBRE</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[10], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[10], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[10] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[10], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[10], 0, ",", ".") . '</td>
           </tr>
    
           <tr>
               <td>DICIEMBRE</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[11], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($impuestos[11], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . $factores[11] . '</td>
               <td style="text-align: center;">' . '$' . number_format($brutosactualizados[11], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($retencionesactualizadas[11], 0, ",", ".") . '</td>
           </tr>
            <tr><td></td></tr>
           <tr>
               <td>TOTALES</td>
               <td style="text-align: center;">' . '$' . number_format($brutos[12], 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format(round($impuestos[12]), 0, ",", ".") . '</td>
               <td style="text-align: center;"></td>
               <td style="text-align: center;">' . '$' . number_format($totalB, 0, ",", ".") . '</td>
               <td style="text-align: center;">' . '$' . number_format($totalR, 0, ",", ".") . '</td>
           </tr>
       </table>
       <p></p>
       <table>
            <tr ><td>Se extiende el presente certificado en cumplimiento de lo establecido en la Resolución Ex. Nº 6509 del servicio de Impuestos Internos,
            publicada en el Diario Oficial de la fecha 20 de Diciembre de 1993, y sus modificaciones posteriores.</td></tr>
       </table>
       <p></p>
       <p></p>
       <p></p>
       <p></p>
       <p></p>
       <p></p>
       <table>
       <table>
            <tr><td></td><td style="text-align:center;"><strong>' . $encargadoN . '</strong></td></tr>
            <tr><td></td><td style="text-align:center;"></td></tr>
            <tr><td></td><td style="text-align:center;"><strong>' . $encargadoR . '</strong></td></tr>
            <tr><td></td><td style="text-align:center;"></td></tr>
            <tr><td></td><td style="text-align:center;">Nombre, Nº Rut y firma del Dueño o Representante Legal de la Empresa, Sociedad o Institución, según corresponda.</td></tr>
       </table>';

        if($insert==true){
            $sql = "INSERT INTO certificado_nro1_honorarios (rut_persona, anio, html,nro_cert,impuesto_actualizado) values ('$rut',$anio,'$html',$nro_cert,$totalR)";
        }else{
            $sql = "update certificado_nro1_honorarios set html='$html', impuesto_actualizado='$totalR' 
                        where nro_cert='$nro_cert' and rut_persona='$rut' and anio='$anio' ";
        }

    mysql_query($sql);



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Certificado Nº 1');
$pdf->SetSubject('Honorarios');
$pdf->SetKeywords('Honorarios, PDF, documento, Documento');
$title_pdf = "Certificado Nº 1, Sobre Honorarios";
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
$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Certificado_1_Honorarios'.$rut.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

