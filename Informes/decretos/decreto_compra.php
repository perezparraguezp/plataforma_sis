<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/proveedor.php");
include("../../php/class/decreto.php");
include("../../php/objetos/documento.php");


session_start();
//error_reporting(0);
//Fecha

$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$id_mio = $_SESSION['id_empleado'];

$f = new functionario($id_mio);

$id_formato_decreto = $_POST['id_formato_decreto'];
mysql_query("delete from vistos_decreto where id_decreto='$id_formato_decreto'");
$id_compra = $_POST['id_compra'];

$sql_1 = "select * from compras
          inner join compras_atributos_mecanismo on compras.id_tipo_compra=compras_atributos_mecanismo.id_atributo
          inner join compras_mecanismo on compras_atributos_mecanismo.id_mecanismo=compras_mecanismo.id_mecanismo
          where id_compra='$id_compra' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));


if($row_1['unidad']=='MUNICIPAL'){
    $nombre_depto_presupuesto = 'la <strong>Municipal</strong>';
}else{
    $nombre_depto_presupuesto = 'del Departamento de <strong>Salud</strong>';
}

$nombre_compra = trim($row_1['nombre_compra']);

$texto_compra = trim($row_1['texto_compra']);
$rut_compra = trim($row_1['rut_proveedor']);

$proveedor = new proveedor($rut_compra);

$nombre_proveedor = $proveedor->razon_social;

$razon_social = trim($row_1['razon_social']);
$monto_comprometido = trim($row_1['monto_comprometido']);
$nombre_mecanismo_compra = trim($row_1['nombre_mecanismo']);
$nombre_atributo_compra = trim($row_1['nombre_atributo']);


$sql_3 = "select * from expediente_compra
    inner join tipo_documento_expediente on expediente_compra.id_tipo_expediente=tipo_documento_expediente.id_tipo_expediente
    where id_compra='$id_compra' and expediente_compra.id_tipo_expediente='4' ;";//CIP
$res_3 = mysql_query($sql_3);
$lista_cip = "<ul style='text-decoration: none;text-align: right'>";
$i_cips=0;
while ($row_3 = mysql_fetch_array($res_3)){
    if($row_3['fecha_documento']!=''){

        list($anio_cip,$mes_cip,$dia_cip) = explode("-",$row_3['fecha_documento']);
    }else{
        list($fecha_cip,$hora_cip) = explode(" ",$row_3['fecha_carga']);
        list($anio_cip,$mes_cip,$dia_cip) = explode("-",$fecha_cip);
    }


    $lista_cip .= '<li> Certificado Nº <strong>'.$row_3['identificador'].'</strong>  de Fecha '.$dia_cip.'-'.$mes_cip.'-'.$anio_cip.'</li>';
    $existe_cip = true;
    $i_cips++;
}
if($i_cips==0){
    $existe_cip = false;
}
$lista_cip .= "</ul>";




$orden_compra = trim($row_1['orden_compra']);
$nombre_centro_costo = trim($row_1['nombre_centro']);



$sql_2 = "SELECT * from certificado_imputacion_cuentas
  inner join pc_cuenta on certificado_imputacion_cuentas.cuenta_general=pc_cuenta.codigo_general
  where anio='$anio' and numero_certificado='$numero_certificado'";
$res_2 = mysql_query($sql_2);
$cuentas = '';
$i = 0;
while($row_2 = mysql_fetch_array($res_2)){
    if($i>0){
        $cuentas.=';';
    }
    $cuentas.='['.$row_2['codigo_separado'].']';
    $i++;
}

$texto_decreto = '<p>';
$decreto1 = trim($_POST['decreto1']);
$decreto2 = trim($_POST['decreto2']);
$decreto3 = trim($_POST['decreto3']);
$i_decreto = 1;
if(trim($decreto1)!=''){
    $texto_decreto .= $i_decreto.'.-'.trim($decreto1).'<br />';
    $i_decreto++;
}
if(trim($decreto2)!=''){
    $texto_decreto .= $i_decreto.'.-'.trim($decreto2).'<br />';
    $i_decreto++;
}
if(trim($decreto3)!=''){
    $texto_decreto .= $i_decreto.'.-'.trim($decreto3).'<br />';
    $i_decreto++;
}
$texto_decreto .= '</p>';





$firma1 = $_POST['firma1'];//secretario
$firma2 = $_POST['firma2'];//alcalde
$firma3 = $_POST['firma3'];//controls

$encargados = trim($_POST['encargados']);

mysql_query("update formato_decreto set firma1='$firma1',firma2='$firma2',firma3='$firma3',encargados='$encargados' where id_decreto='$id_formato_decreto' ");



//Variables recibidas
$sqlF1 = "select * from firmantes where id_empleado='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from firmantes where id_empleado='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$sqlF3 = "select * from firmantes where id_empleado='$firma3' limit 1";
$rowF3 = mysql_fetch_array(mysql_query($sqlF3));


$nombreF1 = trim($rowF1['nombre_firma']);
$nombreF2 = trim($rowF2['nombre_firma']);

$nombre_control = trim($rowF3['nombre_firma']);

$encargados = trim($_POST['encargados']);

if($_POST['secretario_s']){
    $cargoF1 = "Secretario Municipal (s)";
}else{
    $cargoF1 = "Secretario Municipal";
}

if($_POST['alcalde_s']){
    $cargoF2 = "Alcalde (s)";
}else{
    $cargoF2 = $rowF2['cargo'];
}

if($_POST['control_s']){
    $cargoF3 = "Director de Control (s)";
}else{
    $cargoF3 = "Director de Control";
}

$texto = '';
$k = 1;
$check = $_POST['check'];
$vistos = $_POST['item'];
$texto_vistos = '<p>';
foreach ($vistos as $i => $val) {
    if(trim($val)!==''){
        if($check[$i]){
            $texto_vistos .="$k .-" .(trim($val))."<br />";
            $k++;
        }
    }
}


$texto_vistos .= '</p>';
foreach ($vistos as $i => $val) {
    if($check[$i]){
        $obligacion='SI';
    }else{
        $obligacion='NO';
    }
    $val = utf8_encode($val);
    mysql_query("insert into vistos_decreto(id_decreto,visto,obligacion)
    values('$id_formato_decreto','$val','$obligacion')");
}


$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

// create new PDF document
$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Decreto de Compra\n".$f->nombre_depto;
$documento = new documento($title_pdf,$sub_title_pdf,"Decreto de Compra");

$documento->crearFolio();

$documento->updateTipoDocumento("Decreto de Compra",$nombre_atributo_compra);
$folio = $documento->folio;

$documento->updateDatosDocumento($rut_compra,$proveedor->razon_social,''.$nombre_compra);

if($row_1['decreto_aprueba_compra']=='' || $row_1['decreto_aprueba_compra']=='0'){
    //numeramos compra con decreto
    $documento->NumerarDocumento($anio_cip);
    $numero_decreto = $documento->numero_decreto;
    $anio_decreto = $documento->anio_decreto;

    $fecha_decreto = $documento->fecha_decreto;

    mysql_query("update compras set
                decreto_aprueba_compra='$numero_decreto',fecha_decreto_compra=current_date(),
                estado_compra='DECRETADA',
                anio_decreto='$anio_decreto' 
                where id_compra='$id_compra'");

}else{
    $numero_decreto = $row_1['decreto_aprueba_compra'];
    $fecha_decreto = fechaNormal($row_1['fecha_decreto_compra']);
}


$html = '
<style type="text/css">
    p{
        text-align:justify;
        text-indent: 280px;
        font-size:0.7em;
        margin-top: -10px;
    }
    blockquote,strong{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    span{
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:10pt;
    }
    strong{
    font-size: 0.6em;
    }
</style>
<p>DECRETO N: <strong>'.$numero_decreto.'</strong></p>
<p>Carahue, <strong>'.$fecha_decreto.'</strong><br />VISTOS: Estos Antecedentes</p>
'.$texto_vistos.'
<p>
<strong><u>CONSIDERANDO</u></strong><br />
1.- Certificado(s) de Imputación Presupuestaria Emitidos por '.$nombre_depto_presupuesto.':
 '.$lista_cip.'<br />
2.- El Mecanismo de Compra <strong>"'.$nombre_mecanismo_compra.'"</strong> Basado en el <strong>"'.$nombre_atributo_compra.'"</strong>
</p>
<p><strong><u>DECRETO DE COMPRAS</u></strong></p>
 '.($texto_decreto).'
<blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE</blockquote>
<p></p>
<table>
<tr><td></td><td></td></tr>
<tr>
    <td style="text-align: center;font-size:14pt;">
        <strong>' . $nombreF1 . '</strong><br />
        <span style="font-size:12pt;">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;font-size:14pt;">
        <strong>' . $nombreF2 . '</strong><br />
       <span style="font-size:12pt;">' . $cargoF2 . '</span>
    </td>
</tr>
<tr>
    <td style="text-align: left;font-size:12pt;"><br /><strong>' . $encargados . '</strong></td>
    <td></td>
</tr>

<tr>
<td style="text-align: left;font-size:0.7em;">
<strong style="font: 0.7em;;"><u>DISTRIBUICION</u></strong>
<ul>
<li>Archivo Adquisiciones</li>
<li>Dir. Administración y Finanzas</li>
</ul>
</td>
</tr>
</table>
<p></p>
<table border="1px">
<tr>
    <td>
    <strong><u>DIRECCIÓN DE CONTROL</u></strong><br />
    <div style="font-size:8pt;text-align:left;">Realizado el examen del presente acto, el Director de Control Interno concluye que:<br />
    </div>
    </td>
</tr>
<tr>
    <td style="text-align:center;">
    <strong>'.$nombre_control.'</strong><br />
    <strong>'.$cargoF3.'</strong><br />
    </td>
</tr>
</table>
<strong style="font-size:0.5em;">Folio: '.$folio.'</strong>
';
// Print text using writeHTMLCell()
//$html_encode = str_replace("''","",$html);

mysql_query("update compras set pdf='$folio' where id_compra='$id_compra'");

$documento->CrearPDF($html);


