<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/persona.php";

session_start();
$myId = $_SESSION['id_empleado'];

$listado = $_POST['lista_honorarios'];

$titulo_superior = "Certificado de Pago Impuestos Honorarios                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'CERTIFICADO PAGO IMPUESTO');

$listado_boletas = explode("#",$listado);

$monto_cuenta = Array();
$total_boletas = Array();

$mes_plantilla = '';

foreach ($listado_boletas as $i => $boleta) {
    $sql1 = "select * from honorarios
              inner join plantilla_pago using(id_plantilla_pago)
              inner join contrato_honorario on honorarios.id_contratoH=contrato_honorario.id_contrato
              inner join programas_municipales using(id_programa)
              inner join historial_programa_municipal on programas_municipales.id_programa=historial_programa_municipal.id_programa
              inner join pc_cuenta on historial_programa_municipal.codigo_pagar=pc_cuenta.codigo_general 
              where honorarios.id='$boleta'
              limit 1";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    $nro_boleta = $row1['nro_boleta'];
    $nombre_cuenta = $row1['nombre_cuenta'];
    $rut = $row1['rut'];
    $persona = new persona($rut);
    $dp = $row1['decreto_pago'];
    $anio_dp = $row1['anio_dp'];
    $cuenta = $row1['codigo_general'];
    list($anio_dp,$mes_dp,$dia_dp) = explode("-",$row1['fecha_dp']);
    $fecha_dp = $row1['fecha_dp'];
    $mes_plantilla = $meses[(int)$mes_dp - 1]."/".$anio_dp;
    if($head_table[$cuenta] == null){
        $head_table[$cuenta] = '<table style="width: 100%" border="1">
                                    <tr>
                                        <td colspan="2" style="text-align: right;background-color: grey;">CUENTA DE PAGO</td>
                                        <td colspan="3">'.$cuenta.': '.$nombre_cuenta.'</td>
                                    </tr>
                                    <tr style="background-color: #d7efff;font-weight: bold;">
                                        <td style="width: 15%;">RUT</td>
                                        <td style="width: 40%;">NOMBRE COMPLETO</td>
                                        <td style="width: 20%">DP</td>
                                        <td style="width: 15%">NRO. BOLETA</td>
                                        <td style="width: 10%">IMPUESTO</td>
                                    </tr>';
    }

    $body_table[$cuenta] .= '<tr>
                                <td style="text-align: right;">'.$rut.'</td>
                                <td>'.$persona->nombre_completo.'</td>
                                <td style="text-align: right;"><strong>'.$dp.'</strong> ['.fechaNormal($fecha_dp).']</td>
                                <td style="text-align: right;">'.$nro_boleta.'</td>
                                <td style="text-align: right;"> $'.number_format($row1['impuestos'],0,'','.').'</td>
                            </tr>';

    $monto_cuenta[$cuenta] += $row1['impuestos'];
    $total_boletas[$cuenta]++;

}


$documento->crearCabeceraPagina();

$html = '
<style type="text/css">
table{
font-size: 0.8em;;
font-family: "Times New Roman", Georgia, Serif;
}
h5{
text-align: center;
font-size: 1.1em;
}

</style>
<h5>CERTIFICADO DE PAGO<br />IMPUESTOS BOLETAS DE HONORARIOS<br />'.$mes_plantilla.'</h5>
';
foreach ($head_table as $cuenta => $html_table){

    if($cuenta !=''){
        $footer_table[$cuenta] .='<tr style="background-color: #d7efff">
                            <td colspan="5" style="text-align: right;">DETALLE DEL PAGO - ['.$cuenta.']</td>
                        </tr>';
        $footer_table[$cuenta] .='<tr style="background-color: grey;text-align: right">
                            <td colspan="4">DOCUMENTOS A PAGAR</td>
                            <td colspan="1">'.$total_boletas[$cuenta].'</td>
                        </tr>';
        $footer_table[$cuenta] .='<tr style="background-color: grey;text-align: right">
                            <td colspan="4">TOTAL A PAGAR</td>
                            <td colspan="1"> $'.number_format($monto_cuenta[$cuenta],0,'','.').'</td>
                        </tr>';
        $footer_table[$cuenta] .='</table>';



        $table = $head_table[$cuenta].$body_table[$cuenta].$footer_table[$cuenta];

        //echo $html.$table;
        $documento->addPagina($html.$table);
    }
}


$html = '
<style type="text/css">
table{
font-size: 0.8em;;
font-family: "Times New Roman", Georgia, Serif;
}
h5{
text-align: center;
font-size: 1.2em;
}

</style>
<h5>RESUMEN DE PAGO<br />IMPUESTOS HONORARIOS<br />'.$mes_plantilla.'</h5>
';
$table_resumen = '<table style="width: 100%;" border="1px"> 
                    <tr style="background-color: #d7efff;font-weight: bold;">
                        <td style="width: 70%;">Cuenta Bancaria</td>
                        <td style="width: 15%;">Total de Boletas</td>
                        <td style="width: 15%;">Monto a Pagar</td>
                    </tr>';

$total_resumen_boletas = 0;
$total_resumen_impuestos = 0;
foreach ($head_table as $cuenta => $html_table){
    if($cuenta !=''){
        $sql = "select * from pc_cuenta where codigo_general='$cuenta' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));

        $table_resumen .= '<tr>
                               <td>'.$cuenta.' '.$row['nombre_cuenta'].'</td>
                               <td>'.$total_boletas[$cuenta].'</td>
                               <td style="text-align: right;"> $ '.number_format($monto_cuenta[$cuenta],0,'','.').'</td>
                        </tr>';
        $total_resumen_boletas += $total_boletas[$cuenta];
        $total_resumen_impuestos += $monto_cuenta[$cuenta];


    }
}
$table_resumen .= '<tr style="background-color: #d7efff;font-weight: bold;">
                               <td style="text-align: right">TOTAL GENERAL</td>
                               <td>'.$total_resumen_boletas.'</td>
                               <td style="text-align: right;"> $ '.number_format($total_resumen_impuestos,0,'','.').'</td>
                        </tr>';
$table_resumen .= '</table>';
$documento->addPagina($html.$table_resumen);
//var_dump($head_table);
$documento->outDocuemnto();

//echo $html;
//$documento->CrearPDF($html);