<?php

include("../../php/conex.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/plantilla_pago.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/contrato_honorario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$id_plantilla = $_GET['id_plantilla'];
$numero_plantilla = $id_plantilla;

$plantilla_pago = new plantilla_pago();
$plantilla_pago->cargarPlantillaPago($id_plantilla);

$titulo_superior = "Plantilla de Pago                    ";
$titulo_inferior = "Pago de Boletas de Honorarios \nMunicipalidad de Carahue";
$documento = new documento($titulo_superior,$titulo_inferior,'Plantilla de Pago');

$total_transferencia = 0;
$total_cheques = 0;


if($plantilla_pago->tipo_plantilla == 'HONORARIOS'){
    //PLANTILLA DE HONORARIOS
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
                                <th style="width: 4%;">#</th>
                                <th style="width: 11%;">RUT</th>
                                <th style="width: 27%;">NOMBRE</th>
                                <th style="width: 10%;">BANCO</th>
                                <th style="width: 11%;">TIPO CUENTA</th>
                                <th style="width: 19%;">CUENTA</th>
                                <th style="width: 16%;">MONTO A TRANSFERIR</th>
                            </tr>';

    $html3=$html.'<h4>PAGOS MEDIANTE CHEQUE<br />Plantilla Nº '.$numero_plantilla.'/'.date('Y').'</h4>
                    <p>A continuación se detalla el listado de los funcionarios de los cuales su pago se realiza mediante transferencia electronica</p>
                
                    <table  width="100%" border="1px">
                            <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                                <th style="width: 4%;">#</th>
                                <th style="width: 11%;">Rut</th>
                                <th style="width: 27%;">Nombre</th>
                                <th style="width: 10%;">NUMERO DE CHEQUE</th>
                                <th style="width: 11%;">MONTO</th>
                                <th style="width: 19%;">FECHA</th>
                                <th style="width: 16%;">FIRMA</th>
                            </tr>';

    $html.='<h4>PLANTILLA DE PAGO<br />Nº '.$plantilla_pago->id_plantilla.'/'.date('Y').'</h4>
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
    $SQL = "SELECT honorarios.rut,honorarios.id_contratoH,honorarios.id,
          honorarios.impuestos,honorarios,neto,honorarios.bruto
          honorarios.nro_boleta,honorarios.mes 
          FROM honorarios 
          inner join persona on honorarios.rut=persona.rut
          inner join contrato_honorario on id_contratoH=id_contrato
          where id_plantilla_pago='$plantilla_pago->id_plantilla' 
          group by nro_boleta,honorarios.rut 
          order by persona.paterno,persona.materno ";

    $SQL = "select * from honorarios 
            inner join persona on honorarios.rut=persona.rut 
            where id_plantilla_pago='$plantilla_pago->id_plantilla'
            order by persona.paterno,persona.materno  ";

    $RES = mysql_query($SQL);
    while($ds = mysql_fetch_array($RES)){

        $id_contrato = $ds['id_contratoH'];

        $contrato = new contrato_honorario($_SESSION['id_empleado']);
        $contrato ->updateInfoContrato($id_contrato);
        $programa = $contrato->getNombreProgramaCentro();
        $numero_decreto = $contrato->numero_decreto;

        $rut = $ds['rut']; // unico

        $persona = new persona($rut);

        if($persona->existe){
            $id_boleta = $ds['id'];

            $sumatorianeto = $sumatorianeto + $ds['neto'];
            $sumatoriabruto = $sumatoriabruto + $ds['bruto'];
            $sumatoriaimpuesto = $sumatoriaimpuesto + $ds['impuestos'];

            $html .= '
                <tr >
                    <td >' . $persona->rut . '</td>
                    <td >' . strtoupper(trim($ds['paterno'])." ".trim($ds['materno'])." ".trim($ds['nombres'])) . '</td>
                    <td >' . $numero_decreto. '</td>
                    <td >' . $programa . '</td>
                    <td >' . $ds['nro_boleta'] . '</td>
                    <td >$' . number_format($ds['bruto'],0,'','.') . '</td>
                    <td >$' . number_format($ds['neto'],0,'','.') . '</td>
                    <td >$' . number_format($ds['impuestos'],0,'','.') . '</td>
                    <td >' . $ds['mes'] . '</td>
                </tr>
                
        ';

            $sql01 = "select cuenta_bancaria.banco,cuenta_bancaria.tipo_cuenta,cuenta_bancaria.numero_cuenta 
                                                    from cuenta_bancaria 
                                                    where cuenta_bancaria.rut='$persona->rut' limit 1";
            $row01=mysql_fetch_array(mysql_query($sql01));

            if($row01){
                //SI ESTA EN TABLA DE CUENTA BANCARIA ENTRA ACA

                /*
                 * ADEMAS PREGUNTAREMOS SI QUIERE CHEQUE O TRANSFERENCIA
                 */

                $sql04 = "select * from funcionario where upper(rut)=upper('$rut') limit 1";
                $row04 = mysql_fetch_array(mysql_query($sql04));
                if($row04){
                    //es funcionario municipal y tiene cuenta bancaria
                    if($row04['tipo_de_pago']=='CHEQUE'){
                        //CON CHEQUE
                        $html3.='<tr >
                    <td >' . $t . '</td>
                    <td >' . $rut . '</td>
                    <td >' . strtoupper(trim($ds['paterno'])." ".trim($ds['materno'])." ".trim($ds['nombres'])) . '</td>
                    <td >' . $row01['banco'] . '</td>
                    <td >' . $row01['tipo_cuenta'] . '</td>
                    <td >' . $row01['numero_cuenta'] . '</td>
                    <td >$' . number_format($ds['neto'],0,'','.') . '</td>
                </tr>
                ';
                        $total_cheques+=$ds['neto'];
                    }else{
                        //CON TRANSFERENCA
                        $html2.='<tr >
                    <td >' . $t . '</td>
                    <td >' . $rut . '</td>
                    <td >' . strtoupper(trim($ds['paterno'])." ".trim($ds['materno'])." ".trim($ds['nombres'])) . '</td>
                    <td >' . $row01['banco'] . '</td>
                    <td >' . $row01['tipo_cuenta'] . '</td>
                    <td >' . $row01['numero_cuenta'] . '</td>
                    <td >$' . number_format($ds['neto'],0,'','.') . '</td>
                </tr>
                ';
                        $total_transferencia+=$ds['neto'];
                    }
                }else{
                    //no es funcionario municipal y tiene cuenta bancaria
                    $html2.='<tr >
                    <td >' . $t . '</td>
                    <td >' . $rut . '</td>
                    <td >' . strtoupper(trim($ds['paterno'])." ".trim($ds['materno'])." ".trim($ds['nombres'])) . '</td>
                    <td >' . $row01['banco'] . '</td>
                    <td >' . $row01['tipo_cuenta'] . '</td>
                    <td >' . $row01['numero_cuenta'] . '</td>
                    <td >$' . number_format($ds['neto'],0,'','.') . '</td>
                </tr>
                ';
                    $total_transferencia+=$ds['neto'];
                }
                $t++;

            }else{
                //no tiene cuenta bancaria registrada
                $html3.='<tr style="line-height: 5px">
                    <td >' . $c . '</td>
                    <td >' . $rut . '</td>
                    <td >' . strtoupper(trim($ds['paterno'])." ".trim($ds['materno'])." ".trim($ds['nombres'])) . '</td>
                    <td ></td>
                    <td >$' . number_format($ds['neto'],0,'','.') . '</td>
                    <td ></td>
                    <td ></td>
                </tr>
                ';
                $c++;
                $total_cheques+=$ds['neto'];
            }
        }//solo para personas registradas

    }

    $html.='<tr>
            <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTALES</td>
            <td style="text-align: right">$ '.number_format($sumatoriabruto,0,'','.').'</td>
            <td style="text-align: right">$ '.number_format($sumatorianeto,0,'','.').'</td>
            <td style="text-align: right">$ '.number_format($sumatoriaimpuesto,0,'','.').'</td>
        </tr>
</table>';
    $html .= '<p></p>
        <table width="40%" border="1">
            <tr style="background-color: #fdff8b;font-weight: bold;line-height: 2px"><td colspan="2">Detalle de Pagos</td></tr>
            <tr style="line-height: 2px">
                <td>CANTIDAD DE TRANSFERENCIAS</td>
                <td style="text-align: right;">'.($t-1).'</td>
            </tr>
            <tr>
                <td>MONTO TOTAL A TRASFERIR</td>
                <td style="text-align: right;">$ '.number_format($total_transferencia,0,'','.').'</td>
            </tr>
            <tr style="line-height: 2px">
                <td>CANTIDAD DE CHEQUES</td>
                <td style="text-align: right;">'.($c-1).'</td>
            </tr>
            <tr>
                <td>TOTAL A PAGAR CON CHEQUE</td>
                <td style="text-align: right;">$ '.number_format($total_cheques,0,'','.').'</td>
            </tr>
        </table>';
    $html2.='</table>';
    $html3.='</table>';

    $documento -> crearCabeceraPagina();
    $documento -> addPagina($html);//plantilla
    $documento -> addPagina($html2);//detalle transferencias
    $documento -> addPagina($html3);//detalle cheques
    $documento->outDocuemnto();
}else{

}

?>