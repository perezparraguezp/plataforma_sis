<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/documento_dte.php";
include "../../php/objetos/plantilla_pago.php";

$listado = $_POST['lista']; // lista de viaticos a pagar
$listado = explode("#",$listado);

session_start();
$myId = $_SESSION['id_empleado'];
$creador = new functionario($myId);

$plantilla_pago = new plantilla_pago();
$plantilla_pago->crearPlantillaPago('VIATICOS');
$numero_plantilla = $plantilla_pago->id_plantilla;

$total_viaticos = 0;
$monto_total_viaticos = 0;
$monto_total_adicional = 0;
$monto_plantilla = 0;


$procesos_funcionario[] = array();
$valor_viatico_funcionario[] = array();
$valor_adicional_funcionario[] = array();

$fila_detalle = '';

$mes_cometido = date('m');
$mes_cometido = (int)$mes_cometido - 1;

$meses = Array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$mes_cometido = $meses[$mes_cometido];


foreach ( $listado as $i => $id_viatico) {
    if($id_viatico!=''){
        $sql2 = "select * from viaticos 
                where id_viatico='$id_viatico' limit 1";
        $row2 = mysql_fetch_array(mysql_query($sql2));

        $monto_viatico = $row2['monto_viatico'];
        $monto_adicional = $row2['monto_adicional'];
        $f = new functionario($row2['id_empleado']);

        //valores de plantilla
        $monto_total_viaticos += $row2['monto_viatico'];
        $monto_total_adicional += $row2['monto_adicional'];


        $procesos_funcionario[$f->id_empleado]+=1;
        $valor_viatico_funcionario[$f->id_empleado]+=$monto_viatico;
        $valor_adicional_funcionario[$f->id_empleado]+=$monto_adicional;




        $objetivo = "Lugar: <strong style='font-size: 0.8em;'>".strtoupper($row2['lugar_viatico'])."</strong><br />";
        $objetivo .= "Salida: <strong style='font-size: 0.8em;'>".strtoupper($row2['hora_salida'])."</strong><br />";
        $objetivo .= "Llegada: <strong style='font-size: 0.8em;'>".strtoupper($row2['hora_llegada'])."</strong><br />";
        if($row2['objetivo_viatico']!=''){
            $objetivo .= 'Objetivo: <strong style="font-size: 0.8em;">'.strtoupper($row2['objetivo_viatico']).'</strong>';
        }

        $fila_detalle .='<tr>
                        <td>'.$f->nombre_completo.'</td>
                        <td>'.$f->grado.'</td>
                        <td>'.$objetivo.'</td>
                        <td style="text-align: right">$ '.number_format(($monto_viatico),0,'','.').'</td>
                        <td style="text-align: right">$ '.number_format(($monto_adicional),0,'','.').'</td>
                        <td style="text-align: right">$ '.number_format(($monto_viatico+$monto_adicional),0,'','.').'</td>
                    </tr>';

        $sql3 = "update viaticos 
                    set id_plantilla_pago='$plantilla_pago->id_plantilla',monto_plantilla='$monto_total_adicional'+ '$monto_total_viaticos',
                    estado_viatico='EN PLANTILLA'
                    where id_viatico='$id_viatico'";
        mysql_query($sql3);
        $total_procesos++;
    }
}
$plantilla_pago->calcularMontoPlantilla();

$tabla_detalle_pago = '<table width="100%" border="1px">
        <tr style="background-color: #fdff8b;;font-weight: bold">
            <td style="width: 32%;">FUNCIONARIO</td>
            <td style="width: 8%;">GRADO</td>
            <td style="width: 30%;">OBJETIVO</td>
            <td style="width: 10%;">VALOR VIATICO</td>
            <td style="width: 10%;">VALOR ADICIONAL</td>
            <td style="width: 10%;">VALOR A PAGAR</td>
        </tr>
        '.$fila_detalle.'
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL VIATICOS</td>
    <td style="text-align: right">'.$plantilla_pago->cantidad_documentos.'</td>
</tr>
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL PLANTILLA</td>
    <td style="text-align: right">$ '.number_format($plantilla_pago->monto_plantilla,0,'','.').'</td>
</tr>
</table>';

$titulo_superior = "Personal Exento";
$titulo_inferior = "Pago de Viaticos Funcionarios\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'Plantilla de Pago');
$documento->crearFolio();
$plantilla_pago->asignarFolio($documento->folio);

$html = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:1em;;
        margin-top: -10px;
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
<h4>PLANTILLA DE PAGO<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
<p style="font-size: 0.8em;">El siguiente listado el Detalle del Pago de Cometidos al 40%, los cuales se encuentran previamente registrado '.$fecha_actual.' por '.$f->nombre_completo.'.</p>';

$html .=$tabla_detalle_pago;
$html .= '<p></p>';
$html .= '<table style="width: 100%;text-align: center;"><tr>
<td></td>
<td>'.$f->nombre_completo.'<br />'.$creador->nombre_escalafon.'[Grado:'.$creador->grado.']'.'</td>
</tr></table>';


$fila_resumen_pago = '';
$gasto_depto_viatico = array();
$gasto_depto_adicional = array();
foreach ($valor_viatico_funcionario as $id_funcionario => $monto_viatico){
    if($id_funcionario!=''){
        $f = new functionario($id_funcionario);
        $fila_resumen_pago .= '<tr>
                            <td>'.$f->nombre_completo.'</td>
                            <td>'.$f->grado.'</td>
                            <td style="text-align: center;">'.$procesos_funcionario[$id_funcionario].'</td>
                            <td style="text-align: right;">$ '.number_format($monto_viatico,0,'','.').'</td>
                            <td style="text-align: right;">$ '.number_format($valor_adicional_funcionario[$id_funcionario],0,'','.').'</td>
                            <td style="text-align: right;">$ '.number_format($valor_adicional_funcionario[$id_funcionario] + $monto_viatico,0,'','.').'</td>
                            <td style="text-align: center">'.$f->codigo_banco.'</td>
                            <td style="text-align: center">'.$f->codigo_tipo_cuenta.'</td>
                            <td style="text-align: right;">'.$f->numero_cuenta_banco.'</td>
                        </tr>';


        $gasto_depto_viatico[$f->depto][$f->contrato] += $monto_viatico;
        $gasto_depto_adicional[$f->depto][$f->contrato] += $valor_adicional_funcionario[$id_funcionario];

        $gasto_cuentas_viatico[$f->depto][$f->contrato] = $f->cuenta_viatico;

    }
}

$tabla_resumen_pago = '<table width="100%" border="1px">
        <tr style="background-color: #fdff8b;;font-weight: bold">
            <td style="width: 28%;">FUNCIONARIO</td>
            <td style="width: 8%;">GRADO</td>
            <td style="width: 10%;">VIATICOS</td>
            <td style="width: 10%;">MONTO VIATICOS</td>
            <td style="width: 10%;">MONTO ADICIONAL</td>
            <td style="width: 10%;">VALOR A PAGAR</td>
            <td style="width: 8%;">BANCO</td>
            <td style="width: 8%;">TIPO CUENTA</td>
            <td style="width: 8%;">NUMERO CUENTA</td>
        </tr>
        '.$fila_resumen_pago.'
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL VIATICOS</td>
    <td style="text-align: right">'.$plantilla_pago->cantidad_documentos.'</td>
</tr>
<tr>
    <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL PLANTILLA</td>
    <td style="text-align: right">$ '.number_format($plantilla_pago->monto_plantilla,0,'','.').'</td>
</tr>
</table>';

//detalle pagos agrupados
$html1 = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:1em;;
        margin-top: -10px;
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
<h4>RESUMEN DEL PAGO<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
<p style="font-size: 0.8em;">El siguiente detalle corresponde al resumen a pagar por conceptos de "Pago de Cometidos al 40%", el cual se encuentra adjunto a estos documentos como respaldo.</p>';

$html1 .=$tabla_resumen_pago;
$html1 .= '<p></p>';


//detalle contable del gasto
$fila_detalle_contable  = '';
$detalle_contable = array();
$cuenta_adicional = '2152208007';
$sql5 = "select * from pc_cuenta where codigo_general='$cuenta_adicional' limit 1";
$row5 = mysql_fetch_array(mysql_query($sql5));
$nombre_cuenta_adicional = $row5['nombre_cuenta'];
foreach ($gasto_depto_viatico as $depto => $array){
    $sql4 = "select * from departamento where id_depto='$depto' limit 1";
    $row4 = mysql_fetch_array(mysql_query($sql4));
    $fila_detalle_contable .= '<tr> 
                        <td>'.strtoupper($row4['nombre_depto']).'</td>
                        <td>
                            <table style="width: 100%;" border="1px">
                                    <tr style="background-color: #fdff8b;font-weight: bold;">
                                        <td style="width: 25%;;">TIPO CUENTA</td>
                                        <td style="width: 20%;;">CUENTA</td>
                                        <td style="width: 40%;;">NOMBRE CUENTA</td>
                                        <td style="width: 15%;;">MONTO TOTAL</td>
                                    </tr>';
    foreach ($array as $indice => $valor){
        $cuenta_viatico = $gasto_cuentas_viatico[$depto][$indice];
        $sql5 = "select * from pc_cuenta where codigo_general='$cuenta_viatico' limit 1";
        $row5 = mysql_fetch_array(mysql_query($sql5));
        $nombre_cuenta_viatico = $row5['nombre_cuenta'];

        $fila_detalle_contable .='<tr>
                                       <td>'.$indice.'</td>
                                       <td>'.$cuenta_viatico.'</td>
                                       <td>'.$nombre_cuenta_viatico.'</td>
                                       <td style="text-align: right;">$ '.number_format($valor,0,'','.').'</td>
                                </tr>';

        //valor adicional
        $fila_detalle_contable .='<tr>
                                       <td>'.$indice.'</td>
                                       <td>'.$cuenta_adicional.'</td>
                                       <td>'.$nombre_cuenta_adicional.'</td>
                                       <td style="text-align: right;">$ '.number_format($gasto_depto_adicional[$depto][$indice],0,'','.').'</td>
                                </tr>';
    }
    $fila_detalle_contable .='</table></td></tr>';

}
$tabla_resumen_contable = '<table width="100%" border="1px">
        <tr style="background-color: #fdff8b;;font-weight: bold">
            <td style="width: 15%;">DEPARTAMENTO</td>
            <td style="width: 85%;">DETALLE PAGO</td>
        </tr>
        '.$fila_detalle_contable.'
</table>';


$html3 = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:1em;;
        margin-top: -10px;
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
<h4>DETALLE CONTABLE<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
<p style="font-size: 0.8em;">El siguiente listado el Detalle del Pago de Cometidos al 40%, los cuales se encuentran previamente registrado '.$fecha_actual.' por '.$f->nombre_completo.'.</p>';

$html3 .=$tabla_resumen_contable;
$html3 .= '<p></p>';


//DECRETO
$visto3 = 'La Plantilla de Pago Nº '.$numero_plantilla."/".$plantilla_pago->anio.", correspondiente al pago de Cometidos de Funcionarios.";
$visto4 = 'Las Facultades que me Confiere el Texto Refundido de la Ley Nº18.695, "Orgánica Constitucional de Municipalidades"';

$decreto1 = 'Apruebése el Pago de Cometidos de los Funcionarios Municipales de Planta y Contrata, correspondiente al mes de '.$mes_cometido.' de '.date('Y').'.';
$decreto2 = 'Para todos los Efectos Legales, la Plantilla de Pago Nº'.$numero_plantilla."/".$plantilla_pago->anio.', pasa a formar parte integra del presente Decreto.';
$decreto3 = 'Los Gasos Ocasionados por el Presente Decreto Serán Imputados al ítem 215.21.01.004.006, 215.21.02.004.006 y 215.22.08.007, Segun se detalla en la Plantilla de Pago Adjunta.';
$decreto4 = 'Los Gasos Ocasionados  Serán Imputados al ítem 215.21.01.004.006, 215.21.02.004.006 y 215.22.08.007, Segun  la Plantilla de Pago.';

$firmante1 = new functionario($_POST['firma1']);
$firmante2 = new functionario($_POST['firma2']);
if($_POST['alcalde_s']){
    $cargo_firma2 = "Alcalde(s)";
}else{
    $cargo_firma2 = $firmante2->cargo;
}
$cargo_firma1 = 'Secretario Municipal'.$_POST['secretario_s'];


$firma_control = new functionario($_POST['firma_control']);
$firma_directivo = new functionario($_POST['firma_directivo']);

$cargo_control = 'Dir. Control'.$_POST['control_s'];
$cargo_directivo = 'Directivo '.$_POST['directivo_s'];

$html4 = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:0.8em;;
        margin-top: -10px;
    }
    u{
    text-indent: 0px;
    font-size: 0.7em;;
    }
    
</style>
<p>DECRETO</p>
<p>FECHA</p>

<p><strong>VISTOS:</strong><br />1.-'.trim($_POST['visto1']).'<br />2.-'.trim($_POST['visto2']).'<br />3.-'.trim($visto3).'<br />1.-'.trim($visto4).'<br /></p>
<p><strong>DECRETO:</strong><br />1.-'.trim($decreto1).'<br />2.-'.trim($decreto2).'<br />3.-'.trim($decreto3).'<br /></p>';
$html4 .= '<p></p>';
$html4 .= '<p></p>';
$html4 .= '<p style="text-indent: 180px;">ANOTESE, REGISTRESE, COMUNIQUESE Y ARCHIVESE</p>';
$html4 .= '<p></p>';
$html4 .= '<p></p>';
$html4 .= '<table style="font-size: 0.8em;">
                <tr>
                    <td style="text-align: center;font-size: 0.9em;">'.$firmante1->nombre_completo.'<br />'.$cargo_firma1.'</td>
                    <td style="text-align: center;font-size: 0.9em;">'.$firmante2->nombre_completo.'<br />'.$cargo_firma2.'</td>
                </tr>
                <tr><td></td><td></td></tr>
                <tr><td></td><td></td></tr>
                <tr><td></td><td></td></tr>
                <tr><td></td><td></td></tr>
                <tr style="border: solid 1px;">
                    <td style="text-align: center;font-size: 0.9em;">'.$firma_directivo->nombre_completo.'<br />'.$cargo_directivo.'</td>
                    <td style="text-align: center;font-size: 0.9em;">'.$firma_control->nombre_completo.'<br />'.$cargo_control.'</td>
                </tr>
            </table>';
$html4 .= '<div style="font-size: 0.7em;">';
$html4 .= '<br /><span style="font-size: 0.7em;text-indent: 0px;">'.$_POST['distribucion'].'</span><br />';
$html4 .= '<span style="font-size: 0.7em;text-indent: 0px;">-Archivo Municipal</span><br />';
$html4 .= '<span style="font-size: 0.7em;text-indent: 0px;">-Oficina de Personal</span><br />';
$html4 .= '<span style="font-size: 0.7em;text-indent: 0px;">-Folio: <strong>'.$documento->folio.'</strong></span><br />';
$html4 .= '</div>';

$monro_total_cip = $monto_total_viaticos + $monto_total_adicional;
$sql6 ="update decretos set tiene_gasto='SI',monto_cip='$monro_total_cip',referencia_afectado='VIATICOS',
tipo_decreto='Pago de Viaticos',rut_afectado='$f->rut',nombre_afectado='$f->nombre_completo',texto_afectado='$decreto4'
 WHERE id_interno='$documento->folio'";
mysql_query($sql6);

$documento -> crearCabeceraPagina();
//$documento -> addPagina($html);//detalle Viaticos
$documento -> addPagina($html1);//detalle Pagos
$documento -> addPagina($html3);//detalle Pagos
$documento -> addPagina_Vertical($html4);//detalle Pagos


$documento->outDocuemnto();


//echo $html;
//$documento->CrearPDF_Horizontal($html);