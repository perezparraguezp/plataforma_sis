<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");

include("../../php/objetos/documento.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
//Eliminamos los textos del documento

$documento = new documento("Certificado de Imputacion Presupuestaria","Departamento de Contabilidad\nMunicipalidad de Carahue","Certificado");
$documento->updateTipoDocumento("Certificado de Imputacion Presupuestaria","EGRESO");
$documento->updateDatosDocumento('','','');
$documento->crearFolio();
$documento->NumerarDocumento(date('Y'));



$tipo_doc = 'CERTIFICADO IMPUTACION';


$id_empleado = $_SESSION['id_empleado'];

$area_gestion = $_POST['area_gestion'];
$centro_costo = $_POST['centro_costo'];

$mecanismo = $_POST['mecanismo'];
$atributo = $_POST['atributo'];
$bien_servicio = $_POST['bien_servicio'];
$licitacion = $_POST['licitacion'];
$oc = $_POST['oc'];
$nombre = $_POST['nombre'];
$anio = $_POST['anio_cert'];
$unidad = $_POST['unidad_cert'];

$array_cuenta = $_POST['cuenta_array'];
$array_monto = $_POST['monto_array'];

$anio = $_POST['anio_cert'];
$licitacion = $_POST['licitacion'];
$oc = $_POST['oc'];
$cuenta = $_POST['cuenta'];
$centro = $_POST['centro_costo'];
$area = $_POST['area'];

$proveedor = $_POST['proveedor'];

$folio = $documento->folio;

$numero = $documento->numero_decreto;

$sql = "insert into compras_certificado_imputacion(numero_certificado,anio,unidad,id_empleado,id_atributo,
                    bien_servicio,id_centro_costo,cuenta_general,monto_certificado,nombre_certificado,
                    cod_licitacion,oc,folio) 
                    values('$numero','$anio','$unidad','$id_empleado','$atributo','$bien_servicio',
                    '$centro_costo','','','$nombre',upper('$licitacion'),'$oc','$folio')";

mysql_query($sql)or die($sql);
$row = mysql_fetch_array(mysql_query("select * from compras_certificado_imputacion where id_empleado='$id_empleado' order by id_certificado desc limit 1"));
$id_certificado = $row['id_certificado'];

$monto_total_cip = 0;
$monto = 0;
$fila = '';
foreach ($array_cuenta as $i => $valor){

    $monto = $array_monto[$i];//MONTO DOCUMENTO
    $monto_total_cip += $monto;

    $sql_cuenta = "insert into certificado_imputacion_cuentas(numero_certificado,anio,cuenta_general,monto_certificado,unidad,id_certificado) 
      values('$numero','$anio','$valor','$monto','$unidad','$id_certificado')";
    mysql_query($sql_cuenta);



    $sql1 = "select * from pc_cuenta WHERE codigo_general='$valor' limit 1";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    $nombre_cuenta = $row1['nombre_cuenta'];


    $sql2 = "select * from pc_area_gestion inner join pc_centro_costo using(id_area_gestion) 
            where id_centro_costo='$centro' limit 1";
    $row2 = mysql_fetch_array(mysql_query($sql2));
    $nombre_area = $row2['nombre_area'];
    $nombre_centro = $row2['nombre_centro'];

    $sql3 = "SELECT * from pc_monto_cuenta_presupuesto INNER JOIN pc_cuentas_centro_costo using(id_cuenta_centro_costo)
          INNER JOIN pc_centro_costo using(id_centro_costo)
          where id_centro_costo='$centro' and codigo_cuenta='$valor' 
           and pc_monto_cuenta_presupuesto.anio='$anio' and pc_monto_cuenta_presupuesto.unidad='$unidad'
          limit 1;";

    $row3 = mysql_fetch_array(mysql_query($sql3));
    $monto_vigente = $row3['monto_modificado'];
    $saldo_disponible = $row3['saldo_disponible'];

    $id_cuenta_centro_costo = $row3['id_cuenta_centro_costo'];

    $monto_comprometido = $row3['monto_comprometido'];

    $saldo_disponible = $monto_vigente - ( $monto + $monto_comprometido );


    $sql_update = "update pc_monto_cuenta_presupuesto 
              set monto_comprometido='".($monto_comprometido+$monto)."',
              saldo_disponible='$saldo_disponible'
              where id_cuenta_centro_costo='$id_cuenta_centro_costo'  
              and anio='$anio' and unidad='$unidad' ";

    mysql_query($sql_update);

    $sql_update2 = "update compras_certificado_imputacion 
                    set monto_vigente='$monto_vigente',
                    monto_comprometido='$monto_comprometido',
                    saldo_disponible='$saldo_disponible'
                    where numero_certificado='$numero' and anio='$anio' ";
    mysql_query($sql_update2);

    $fila .= '
    <tr>
        <td>'.$valor.'</td>
        <td>'.$nombre_cuenta.'</td>
        <td>$ '.number_format($monto_vigente,0,'','.').'</td>
        <td>$ '.number_format($monto_comprometido,0,'','.').'</td>
        <td>$ '.number_format($monto,0,'','.').'</td>
        <td>$ '.number_format($saldo_disponible,0,'','.').'</td>
    </tr>
    ';
}




$titulo_documento = "<h4>CERTIFICADO DE IMPUTACION PRESUPUESTARIA<br />Nº ".$numero."</h4>";

if($licitacion!=''){
    $frase = ' indicados en las bases de la licitación: <strong>'.strtoupper($licitacion).'</strong>';
}else{
    if($oc!=''){
        $frase = '.<br />Orden de Compra: <strong>'.$oc.'</strong>';
    }else{
        $frase = '.';
    }
}

//firmante
$sql4 = "select * from firmantes_documentos where nombre_doc='$tipo_doc' limit 1";
$row4 = mysql_fetch_array(mysql_query($sql4));


$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
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
        font-size:12pt;
        text-align: left;
        
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h6{
    font-size: 1em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    
    }
</style>
<p style="text-align: right;">FECHA <strong>'.date('d/m/Y').'</strong></p>
'.$titulo_documento.'

<span>De conformidad al presupuesto aprobado para este Municipio por el Concejo Municipal para el año '.$anio.', 
certifico que, a la fecha del presente documento,  esta institución cuenta con el presupuesto para el 
financiamiento de los bienes y/o servicios'.$frase.'</span>
<br />
<div style="font-size:0.9em;"><strong>'.$nombre_area.' - '.$nombre_centro.'</strong></div>
<br />

<table border="1" style="font-size:0.5em;">
    <tr style="background-color:#CCFDFF;font-weight: bold;">
        <td>ITEM</td>
        <td>CUENTA</td>
        <td>Ppto. Vigente</td>
        <td>Monto Comprom.</td>
        <td>Monto Documento</td>
        <td>Saldo Disponible</td>
    </tr>
    '.$fila.'
</table>
<br />
<br />
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<h5 style="text-align:center">'.$row4['nombre_firmante'].'<br/>'.$row4['cargo_firmante'].'</h5>
';

$sql = "update compras_certificado_imputacion 
                set monto_certificado='$monto_total_cip' 
                where id_certificado='$id_certificado' ";

mysql_query($sql);

$documento->CrearPDF($html);

