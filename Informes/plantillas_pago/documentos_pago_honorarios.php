<?php

include("../../php/conex.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/plantilla_pago.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$plantilla_pago = new plantilla_pago();
$plantilla_pago->crearPlantillaPago('HONORARIOS');
$numero_plantilla = $plantilla_pago->id_plantilla;

$listado = $_POST['list_decreto'];
$titulo_superior = "Plantilla de Pago                    ";
$titulo_inferior = "Pago de Boletas de Honorarios \nMunicipalidad de Carahue";
$documento = new documento($titulo_superior,$titulo_inferior,'Plantilla de Pago');
$item = explode("#",$listado);


$bruto_transferencias = 0;
$neto_transferencias = 0;
$impuesto_transferencias = 0;
$bruto_cheques = 0;
$neto_cheques = 0;
$impuesto_cheques = 0;
$bruto_cheques = 0;
$total_transferencias = 0;
$total_cheques = 0;
function sumaTransferencias($netoF, $brutoF, $impuestoF){
    global $bruto_transferencias;
    global $neto_transferencias;
    global $impuesto_transferencias;
    global $total_transferencias;

    $bruto_transferencias   =   $bruto_transferencias   + $brutoF;
    $neto_transferencias    =   $neto_transferencias    + $netoF;
    $impuesto_transferencias=   $impuesto_transferencias+ $impuestoF;
    $total_transferencias++;
}
function sumaCheques($netoF, $brutoF, $impuestoF){
    global $bruto_cheques;
    global $neto_cheques;
    global $impuesto_cheques;
    global $total_cheques;

    $bruto_cheques      = $bruto_cheques    + $brutoF;
    $neto_cheques       = $neto_cheques     + $netoF;
    $impuesto_cheques   = $impuesto_cheques + $impuestoF;
    $total_cheques++;
}

$html='<style type="text/css">
        p{
            text-align:left;
            text-indent: 280px;
            font-size:1em;;
            margin-top: -10px;
        }
        blockquote,strong{
            font-size:10pt;
        }
        table{
            font-size:8pt;
        }
        table tr td{
        padding-top: 3px;;
        line-break: initial;
        
        }
        span{
            font-size:10pt;
            text-align: right;
            }
        ol li{
        font-size:10pt;
        }
        h4{
        text-align: center;
        }
    </style>';

$html2=$html.' <h4>PAGOS MEDIANTE TRANSFERENCIA ELECTRONICA<br />Plantilla Nº '.$numero_plantilla.'/'.date('Y').'</h4>
                    <p>A continuación se detalla el listado de los funcionarios de los cuales su pago se realiza mediante transferencia electronica</p>
                
                    <table  width="100%" border="1px">
                            <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                                <th style="width: 2%;">#</th>
                                <th style="width: 11%;">RUT</th>
                                <th style="width: 27%;">NOMBRE</th>
                                <th style="width: 10%;">BANCO</th>
                                <th style="width: 11%;">TIPO CUENTA</th>
                                <th style="width: 19%;">CUENTA</th>
                                <th style="width: 20%;">MONTO A TRANSFERIR</th>
                            </tr>';

$html3=$html.'<h4>PAGOS MEDIANTE CHEQUE<br />Plantilla Nº '.$numero_plantilla.'/'.date('Y').'</h4>
                    <p>A continuación se detalla el listado de los funcionarios de los cuales su pago se realiza mediante transferencia electronica</p>
                
                    <table  width="100%" border="1px">
                            <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                                <th style="width: 2%;">#</th>
                                <th style="width: 11%;">Rut</th>
                                <th style="width: 27%;">Nombre</th>
                                <th style="width: 10%;">NUMERO DE CHEQUE</th>
                                <th style="width: 11%;">MONTO</th>
                                <th style="width: 19%;">FECHA</th>
                                <th style="width: 20%;">FIRMA</th>
                            </tr>';

$html.='<h4>PLANTILLA DE PAGO<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
    <p>A continuación se detalla el listado de los documentos a los cuales se realizara el proceso de pago:</p>

    <table  width="100%" border="1px">
            <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                <th style="width: 9%;">RUT</th>
                <th style="width: 27%;">NOMBRE</th>
                <th style="width: 7%;">DECRETO</th>
                <th style="width: 19%;">PROGRAMA</th>
                <th style="width: 9%;">NRO BOLETA</th>
                <th style="width: 8%;">BRUTO</th>
                <th style="width: 8%;">NETO</th>
                <th style="width: 8%;">IMPUESTO</th>
                <th style="width: 5%;">MES</th>
            </tr>
            
';

$sumatorianeto = 0;
$sumatoriaimpuesto = 0;
$sumatoriabruto = 0;
$t=1;
$c=1;
foreach ($item as $i => $data) {

    if ($data != '') {
        $ds = explode("^", $data); //datos persona extraidos


        $sumatorianeto = $sumatorianeto + $ds[5];
        $sumatoriabruto = $sumatoriabruto + $ds[6];
        $sumatoriaimpuesto = $sumatoriaimpuesto + $ds[7];
        $row  = mysql_fetch_array(mysql_query("select id from honorarios where rut='$ds[0]' and nro_boleta='$ds[4]'"));
        $id_boleta = $row['id'];
        $html .= '
                <tr >
                    <td >' . $ds[0] . '</td>
                    <td >' . $ds[1] . '</td>
                    <td >' . $ds[2] . '</td>
                    <td >' . $ds[3] . '</td>
                    <td >' . $ds[4] . '</td>
                    <td >$' . number_format($ds[6],0,'','.') . '</td>
                    <td >$' . number_format($ds[5],0,'','.') . '</td>
                    <td >$' . number_format($ds[7],0,'','.') . '</td>
                    <td >' . $ds[8] . '</td>
                </tr>
                
        ';
        $plantilla_pago->insertDocumento($id_boleta);

        $sql01=mysql_fetch_array(mysql_query("select cuenta_bancaria.banco,cuenta_bancaria.tipo_cuenta,cuenta_bancaria.numero_cuenta 
                                                    from cuenta_bancaria  
                                                    where cuenta_bancaria.rut='$ds[0]' limit 1"));



        if($sql01){
            //SI ESTA EN TABLA DE CUENTA BANCARIA ENTRA ACA

            /*
             * ADEMAS PREGUNTAREMOS SI QUIERE CHEQUE O TRANSFERENCIA
             */
            $rut = $ds[0];
            $sql04 = "select * from funcionario where upper(rut)=upper('$rut') limit 1";
            $row04 = mysql_fetch_array(mysql_query($sql04));

            if($row04){
                //es funcionario municipal
                if($row04['tipo_de_pago']=='CHEQUE'){
                    //CON CHEQUE
                    $html3.='<tr >
                        <td >' . $c . '</td>
                        <td >' . $ds[0] . '</td>
                        <td >' . $ds[1] . '</td>
                        <td ></td>
                        <td >$' . number_format($ds[5],0,'','.') . '</td>
                        <td ></td>
                        <td ></td>
                        </tr>
                        ';
                    sumaCheques($ds[5],$ds[6],$ds[7]);
                }else{
                    //CON TRANSFERENCA
                    $html2.='<tr >
                        <td >' . $t . '</td>
                        <td >' . $ds[0] . '</td>
                        <td >' . $ds[1] . '</td>
                        <td >' . $sql01['banco'] . '</td>
                        <td >' . $sql01['tipo_cuenta'] . '</td>
                        <td >' . $sql01['numero_cuenta'] . '</td>
                        <td >$' . number_format($ds[5],0,'','.') . '</td>
                        </tr>
                        ';
                    sumaTransferencias($ds[5],$ds[6],$ds[7]);
                }

            }else{
                //CON TRANSFERENCA
                $html2.='<tr >
                    <td >' . $t . '</td>
                    <td >' . $ds[0] . '</td>
                    <td >' . $ds[1] . '</td>
                    <td >' . $sql01['banco'] . '</td>
                    <td >' . $sql01['tipo_cuenta'] . '</td>
                    <td >' . $sql01['numero_cuenta'] . '</td>
                    <td >$' . number_format($ds[5],0,'','.') . '</td>
                    </tr>
                    ';
                sumaTransferencias($ds[5],$ds[6],$ds[7]);
            }

            $t++;

        }else{
            $html3.='<tr style="line-height: 5px">
                    <td >' . $c . '</td>
                    <td >' . $ds[0] . '</td>
                    <td >' . $ds[1] . '</td>
                    <td ></td>
                    <td >$' . number_format($ds[5],0,'','.') . '</td>
                    <td ></td>
                    <td ></td>
                    </tr>
                    ';$c++;
            sumaCheques($ds[5],$ds[6],$ds[7]);
        }
    }
}
$html.='<tr>
            <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTALES</td>
            <td style="text-align: right">$ '.number_format($sumatoriabruto,0,'','.').'</td>
            <td style="text-align: right">$ '.number_format($sumatorianeto,0,'','.').'</td>
            <td style="text-align: right">$ '.number_format($sumatoriaimpuesto,0,'','.').'</td>
        </tr>
</table>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr><tr>
        <td><h4>RESUMEN DE PAGOS:</h4></td>
        <td></td>
        <td></td>
    </tr>
    
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>NUMERO DE TRANSFERENCIAS:</td>
        <td>'.$total_transferencias.'</td>
        <td></td>
    </tr>
    <tr>
        <td>BRUTO TOTAL EN TRANSFERENCIAS:</td>
        <td>$ '.number_format($bruto_transferencias,0,'','.').'</td>
        <td></td>
    </tr>
    <tr>
        <td>NETO TOTAL EN TRANSFERENCIAS:</td>
        <td>$ '.number_format($neto_transferencias,0,'','.').'</td>
        <td></td>
    </tr>
    <tr>
        <td>IMPUESTO TOTAL EN TRANSFERENCIAS:</td>
        <td>$ '.number_format($impuesto_transferencias,0,'','.').'</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>NUMERO DE CHEQUES:</td>
        <td>'.$total_cheques.'</td>
        <td></td>
    </tr>
    <tr>
        <td>BRUTO TOTAL EN CHEQUES:</td>
        <td>$ '.number_format($bruto_cheques,0,'','.').'</td>
        <td></td>
    </tr>
    <tr>
        <td>NETO TOTAL EN CHEQUES:</td>
        <td>$ '.number_format($neto_cheques,0,'','.').'</td>
        <td></td>
    </tr>
    <tr>
        <td>IMPUESTO TOTAL EN CHEQUES:</td>
        <td>$ '.number_format($impuesto_cheques,0,'','.').'</td>
        <td></td>
    </tr>
</table>


';
$html2.='</table>';
$html3.='</table>';

$documento -> crearCabeceraPagina();
$documento -> addPagina($html);//plantilla
$documento -> addPagina($html2);//detalle transferencias
$documento -> addPagina($html3);//detalle cheques
$documento->crearFolio();
$plantilla_pago->asignarFolio($documento->folio);
$documento->outDocuemnto();

?>