<?php
include("../../php/conex.php");
include("../../php/class/decreto.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/documento.php");
include '../../php/objetos/persona.php';
session_start();
error_reporting(0);
//Eliminamos los textos del documento


//// Extend the TCPDF class to create custom Header and Footer
//class MYPDF extends TCPDF {
//
//    // Page footer
//    public function Footer() {
//        // Position at 15 mm from bottom
//        $this->SetY(-15);
//        // Set font
//        $this->SetFont('helvetica', 'I', 7);
//        // Page number
//        $this->Cell(0, 10, "Municipalidad de Carahue", 0, false, 'C', 0, '', 0, false, 'T', 'M');
//    }
//}

$titulo_superior = "Impuesto Honorario";
$titulo_inferior = "Certificado Nº 1, Sobre Honorarios\nMunicipalidad de Carahue";

$pdf = new documento($titulo_superior,$titulo_inferior,"Impuesto Honorario");

$pdf -> crearCabeceraPagina();


//RESUMEN DE HONORARIOS

//$html2 = '
//<style type="text/css">
//   p{
//       text-align:left;
//       text-indent: 280px;
//       font-size:7pt;
//       margin-top: 0px;
//   }
//   BLOCKQUOTE{
//       font-size:10pt;
//   }
//   table{
//       font-size:7pt;
//   }
//   span{
//       font-size:10pt;
//       text-align: left;
//       }
//   li{
//   font-size:10pt;
//   }
//   h4{
//   text-align: center;
//   }
//   h5{
//   font-size: 1em;;
//   text-align: center;
//   bottom: 10px;
//   position: absolute;
//   }
//   h4{
//   text-align: center;
//   }
//   </style>
//   <table style="width: 100%;" border="1">
//    <tr>
//        <td rowspan="3" colspan="2" width="9%"><p>RUT del receptor de la renta</p></td>
//        <td colspan="3" width="30%"><p>Monto retenido anual actualizado (Del 01/01 al 31/12)</p></td>
//        <td colspan="12" rowspan="2" width="36%"><p>Período al cuál corresponden las rentas</p></td>
//        <td rowspan="3" width="10%"><p>Honorarios y otros actualizados trabajadores de las artes y espectáculos</p></td>
//        <td rowspan="3" width="10%"><p>Monto pagado anual actualizado por servicios prestados en Isla de Pascua</p></td>
//        <td rowspan="3" width="7%"><p>Número Certificado</p></td>
//
//    </tr>
//    <tr>
//        <td><p>Honorarios y otros (Art. 42 Nº 2)</p></td>
//        <td colspan="2"><p>Remuneración de directores (Art. 48)</p></td>
//    </tr>
//    <tr>
//        <td width="10%"><p>Tasa 10%</p></td>
//        <td width="10%"><p>Tasa 10%</p></td>
//        <td width="10%"><p>Tasa 35%</p></td>
//        <td width="3%"><p>Ene</p></td>
//        <td width="3%"><p>Feb</p></td>
//        <td width="3%"><p>Mar</p></td>
//        <td width="3%"><p>Abr</p></td>
//        <td width="3%"><p>May</p></td>
//        <td width="3%"><p>Jun</p></td>
//        <td width="3%"><p>Jul</p></td>
//        <td width="3%"><p>Ago</p></td>
//        <td width="3%"><p>Sep</p></td>
//        <td width="3%"><p>Oct</p></td>
//        <td width="3%"><p>Nov</p></td>
//        <td width="3%"><p>Dic</p></td>
//    </tr>';


$anio = $_POST['anio'];
$encargado = $_POST['encargado'];

list($encargadoR, $encargadoN) = split('[/]', $encargado);
$rut = str_replace(".","",$rutP);
$sqlrut = "select distinct rut from honorarios_parcial where anio='$anio' ";
$resrut = mysql_query($sqlrut);
$sql2 = "SELECT MAX(nro_cert) nro_cert
FROM certificado_nro1_honorarios where anio=$anio";
$rows = mysql_fetch_array(mysql_query($sql2));
$nro_cert = 1;
if($rows['nro_cert']>=1){
    $nro_cert = $rows['nro_cert']+1;
}
while ($rowrut = mysql_fetch_array($resrut)) {
    $totalR = 0;
    $totalB = 0;
    $rut = $rowrut['rut'];
    $title_pdf = "Certificado Nº 1, Sobre Honorarios";
    $sub_title_pdf = "Municipalidad de Carahue\nPortales #295, Carahue";
    // set default header data

    $factores = Array(0,0,0,0,0,0,0,0,0,0,0,0); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE********************************************
    $brutos = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $impuestos = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $brutosactualizados = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $retencionesactualizadas = Array(0,0,0,0,0,0,0,0,0,0,0,0);
    $sql1 = "select factor,mes from factores_actualizacion_honorarios where anio=$anio ORDER by mes ";
    $res = mysql_query($sql1);
    $i = 0;
    while ($row = mysql_fetch_array($res)) {
        $factores[$i] = $row['factor'];
        $i++;
    }
    $sql1 = "select * from honorarios_parcial
                where rut='$rut' and anio='$anio'";
    $res = mysql_query($sql1);
    while ($row = mysql_fetch_array($res)) {

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
        $totalB = $totalB + round($brutosactualizados[$i]);
        $totalR = $totalR + round($retencionesactualizadas[$i]);

    }

    $obj = new persona($rut);
    $name = $obj->nombre_completo;

//    function diaSemana($ano, $mes, $dia)
//    {
//        // 0->domingo   | 6->sabado
//        $diaX = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
//        return $diaX;
//    }

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
//
//    list($rutSDV,$DV) = split('-',$rut);

//    $html2.='<tr>
//        <td width="7%">'.$rutSDV.'</td>
//        <td width="2%">'.$DV.'</td>
//        <td width="10%"></td>
//        <td width="10%"></td>
//        <td width="10%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="3%"></td>
//        <td width="10%"></td>
//        <td width="10%"></td>
//        <td width="7%"></td>
//    </tr>';






    $sql = "INSERT INTO certificado_nro1_honorarios (rut_persona, anio, html,nro_cert,impuesto_actualizado) values ('$rut','$anio','$html','$nro_cert','$totalR')";
    mysql_query($sql);
    $pdf -> addPagina_Vertical($html);//detalle Pagos
    $nro_cert++;
}










   
    
//
//$html2.=   '</table>';

//$pdf -> addPagina($html2);//detalle Pagos
$pdf -> imprimeDocumento();







