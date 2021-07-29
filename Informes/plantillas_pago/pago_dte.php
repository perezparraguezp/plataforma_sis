<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/documento_dte.php";
include "../../php/objetos/plantilla_pago.php";

session_start();
$myId = $_SESSION['id_empleado'];
$f = new functionario($myId);
$plantilla_pago = new plantilla_pago();
$plantilla_pago->crearPlantillaPago('PROVEEDOR');
$numero_plantilla = $plantilla_pago->id_plantilla;

$fecha_actual = date('d-m-Y');

$lista_dte = explode("#",$_POST['lista_dte']);
$dte = new documento_dte();
$tr = '';
$tr = '';
$tr_transferencia ='';
$total_transferencia = 0;
$tr_cheque ='';
$total_cheque = 0;
$total_procesos = 0;

$total_plantilla = 0;
foreach ( $lista_dte as $item => $id_dte) {
    if($id_dte!=''){
        $total_procesos++;
        $dte->cargar_dte($id_dte);

        $monto_original = 0;
        $monto_pagar = 0;



        if($dte->factoring!=''){
            //tiene factoring asociado
            $pagar_a = new proveedor($dte->factoring);
            $factoring = '<strong style=" font-size: 0.7em;;">[FACTORING]</strong>';

        }else{
            $pagar_a = new proveedor($dte->rut);
            $factoring = '<strong style=" font-size: 0.7em;;">[PROVEEDOR]</strong>';
        }

        $p = new proveedor(($dte->rut));
        $motivo = 'motivo';
        $decreto = 'decreto';

        $tr .= '<tr  style="height: 50px;" nobr="true">
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;">MOTIVO PAGO</td>
                    <td colspan="3">'.$dte->buscarMotivo().'</td>
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;">DECRETO</td>
                    <td colspan="1">'.$dte->decreto_motivo.'</td>
                </tr>
                <tr style="height: 50px;" nobr="true">
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;">RUT PROVEEDOR</td>
                    <td>'.rut_formato($dte->rut).'</td>
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;">RAZON SOCIAL</td>
                    <td colspan="3">'.$p->razon_social.'</td>
                </tr>
                <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                    <td colspan="2">TIPO DOCUMENTO</td>
                    <td style="text-align: right">FOLIO</td>
                    <td style="text-align: center">FECHA DOCUMENTO</td>
                    <td style="text-align: right">MONTO DOCUMENTO</td>
                    <td>CIP</td>
                </tr>
                <tr>
                    <td colspan="2">'.$dte->tipo_documento.'</td>
                    <td style="text-align: right">'.$dte->folio.'</td>
                    <td style="text-align: center">'.fechaNormal($dte->fecha_emision).'</td>
                    <td style="text-align: right">$ '.number_format($dte->monto_total,0,'','.').'</td>
                    <td>'.$dte->cip.'</td>
                </tr>
                ';
        $monto_original = $dte->monto_total;
        $monto_pagar = $dte->monto_total;


        $total_plantilla+=$dte->monto_total;

        //buscamos si tiene notas de credito asociadas al dte
        $sql1 = "select * from dte_descuentos where id_documento_original='$dte->id_documento' ";
        $res1 = mysql_query($sql1);
        $folio_actual = $dte->folio;
        while($row1 = mysql_fetch_array($res1)){

            $dte->cargar_dte($row1['id_documento_descuento']);
            $p = new proveedor(($dte->rut));
            $motivo = $row1['tipo_descuento'].'<br />Ref. ['.$folio_actual.']';
            $decreto = '';
            $tr .= '<tr style="height: 50px;" nobr="true">
                        <td colspan="2">'.$dte->tipo_documento.'</td>
                        <td style="text-align: right">'.$dte->folio.'</td>
                        <td style="text-align: center">'.fechaNormal($dte->fecha_emision).'</td>
                        <td style="text-align: right;background-color: #d7efff;">(-) $ '.number_format($dte->monto_total,0,'','.').'</td>
                        <td>'.$dte->cip.'</td>
                    </tr>';
            $total_plantilla-=$dte->monto_total;

            $monto_pagar -= $dte->monto_total;

        }

        $tr.= ' <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                    <td colspan="6">DOCUMENTOS ADJUNTOS</td>
                </tr>';
        $tr.= ' <tr style="padding: 50px;">
                    <td>__ SOLICITUD DE PEDIDO</td>
                    <td>__ DECRETO DE ADJUDICACION</td>
                    <td>__ CERTIFICADO DE RECEPCIÓN</td>
                    <td>__ CERTIFICADO DE IMPUTACION</td>
                    <td>__ DTE</td>
                    <td>__ OTROS DOCUMENTOS</td>
                </tr>';
        $tr.= ' <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                    <td colspan="6">DETALLE DEL PAGO</td>
                </tr>';
        $tr.= ' <tr>
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;" colspan="1">PAGAR A '.$factoring.' </td>
                    <td colspan="3">'.$pagar_a->razon_social.'</td>
                    <td style="background-color: #fdff8b;font-weight: bold;padding: 5px;" colspan="1">MONTO A PAGAR</td>
                    <td colspan="1">$ '.number_format($monto_pagar,0,'','.').'</td>
                </tr>';
        $tr.= ' <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;height: 20px;">
                    <td colspan="6" style="padding: 20px;"></td>
                </tr>';
        if($pagar_a->banco!=''){
            //tiene banco registrado
            $tr_transferencia .='<tr>
                                    <td>'.$dte->decreto_motivo.'</td>
                                    <td>'.rut_formato($pagar_a->rut).'</td>
                                    <td>'.$pagar_a->razon_social.'</td>
                                    <td>'.$pagar_a->nombre_banco.'</td>
                                    <td>'.$pagar_a->nombre_tipo_cuenta.'</td>
                                    <td>'.$pagar_a->numero_cuenta.'</td>
                                    <td>$ '.number_format($monto_pagar,0,'','.').'</td>
                                </tr>';
        }else{
            $tr_cheque .='<tr>
                                    <td>'.$dte->decreto_motivo.'</td>
                                    <td>'.rut_formato($pagar_a->rut).'</td>
                                    <td>'.$pagar_a->razon_social.'</td>
                                    <td>$ '.number_format($monto_pagar,0,'','.').'</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
        }
        $plantilla_pago->insertDocumento($id_dte);



    }
}
$plantilla_pago->calcularMontoPlantilla();

$tabla_detalle_pago = '<table width="100%" border="1px">
        '.$tr.'
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL PROCESOS</td>
    <td style="text-align: right">'.$total_procesos.'</td>
</tr>
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL PLANTILLA</td>
    <td style="text-align: right">$ '.number_format($total_plantilla,0,'','.').'</td>
</tr>
</table>';

$titulo_superior = "Expediente para el Pago               ";
$titulo_inferior = "Pago de Documentos Electronicos Tributarios (DTE)\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'Plantilla de Pago');

$html = '<style type="text/css">
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
    
</style>
<h4>EXPEDIENTE PARA EL PAGO<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
<p style="font-size: 0.8em;">El siguiente listado contiene el resumen de la documentación necesaria para la gestión del pago por la unidad de contabilidad, este 
 documento fue generado el día '.$fecha_actual.' por '.$f->nombre_completo.'.</p>';
$html .=$tabla_detalle_pago;
$html .= '<p></p>';
$html .= '<table style="width: 100%;text-align: center;"><tr>
<td></td>
<td>'.$f->nombre_completo.'<br />'.$f->nombre_escalafon.'[Grado:'.$f->grado.']'.'</td>
</tr></table>';


$html1 = '<style type="text/css">
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
    padding: 10px;;
    
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
    
</style>
<h4>DETALLE DE PAGO: TRANSFERENCIAS<br />Nº '.$numero_plantilla.'/'.date('Y').'<br /></h4>
    <table style="width: 100%;" border="1px">
    <tr style="background-color: #fdff8b;font-weight: bold;padding: 2px;height: 30px;">
        <td style="width: 10%;">DECRETO</td>
        <td style="width: 10%;">RUT</td>
        <td style="width: 20%;">PROVEEDOR</td>
        <td style="width: 15%;">BANCO</td>
        <td style="width: 15%;">TIPO CUENTA</td>
        <td style="width: 15%;">NUMERO CUENTA</td>
        <td style="width: 15%;">MONTO</td>
    </tr>'.$tr_transferencia.'
    </table>';

$html2 = '<style type="text/css">
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
    
</style>
<h4>DETALLE DE PAGO: CHEQUES<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
    <table style="width: 100%;" border="1px">
    <tr style="background-color: #fdff8b;font-weight: bold;padding: 2px;height: 30px;">
        <td style="width: 10%;">DECRETO</td>
        <td style="width: 10%;">RUT</td>
        <td style="width: 20%;">PROVEEDOR</td>
        <td style="width: 15%;">MONTO</td>
        <td>FOLIO CHEQUE</td>
    </tr>'.$tr_cheque.'
    </table>';

$documento -> crearCabeceraPagina();

$documento -> addPagina($html);//detalle cheques
$documento -> addPagina($html1);//detalle cheques
$documento -> addPagina($html2);//detalle cheques
$documento->crearFolio();
$plantilla_pago->asignarFolio($documento->folio);
$documento->outDocuemnto();


//echo $html;
//$documento->CrearPDF_Horizontal($html);