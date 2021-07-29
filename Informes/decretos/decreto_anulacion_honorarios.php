<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/proveedor.php");
include("../../php/class/decreto.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/persona.php");
session_start();
//error_reporting(0);
//Fecha
$id_contrato = $_POST['id_contrato'];
$tipo_decreto = $_POST['tipo_decreto'];
list($dia3, $mes3, $anio3) = explode("-",fechaNormal($_POST['fecha_carta']));
list($dia1, $mes1, $anio1) = explode("-",fechaNormal($_POST['fecha_termino']));
$rut_afectado = $_POST['rut_afectado'];
$afectado = new persona($rut_afectado);
$nombre_afectado = $afectado -> nombres." ".$afectado ->paterno." ".$afectado->materno;
$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$id_mio = $_SESSION['id_empleado'];

$row1 = mysql_fetch_array(mysql_query("select fecha_inicio from contrato_honorario where id_contrato='$id_contrato'"));
list($dia2, $mes2, $anio2) = explode("-",fechaNormal($row1['fecha_inicio']));

$f = new functionario($id_mio);

$id_formato_decreto = $_POST['id_formato_decreto'];
mysql_query("delete from vistos_decreto where id_decreto='$id_formato_decreto'");

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

$encargados = trim($_POST['encargados']);

mysql_query("update formato_decreto set firma1='$firma1',firma2='$firma2',encargados='$encargados' where id_decreto='$id_formato_decreto'");

//Variables recibidas
$sqlF1 = "select * from firmantes where id_empleado='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from firmantes where id_empleado='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$nombreF1 = trim($rowF1['nombre_firma']);
$nombreF2 = trim($rowF2['nombre_firma']);


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

// create new PDF document
$title_pdf = "Decreto de anulacion                                            ";
$sub_title_pdf = "Oficina de personal"."\n"."Municipalidad de Carahue";
$documento = new documento($title_pdf,$sub_title_pdf,"Decreto Anulacion de Contrato");

if($tipo_decreto!="anulacion"){
    $documento->crearFolio();
    $folio = $documento->folio;
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
<p>DECRETO Nº: <strong>'.$numero_decreto.'</strong></p>
<p>Carahue, <strong></strong><br/><br/><br/><strong>VISTOS:</strong> Estos Antecedentes</p>
'.$texto_vistos.'
<p>

<p><strong><u>DECRETO</u></strong></p>
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
<br/>
<br/>
<br/>
<tr>
    <td style="text-align: left;font-size:12pt;"><br /><strong>' . $encargados . '</strong></td>
    <td></td>
</tr>
<br/>
<br/>
<br/>
<br/>
<br/>
<tr>
<td style="text-align: left;font-size:0.7em;">
<br/>
<br/>
<strong style="font: 0.7em;;"><u>DISTRIBUCION</u></strong>
<ul>
<li>Archivo Municipal<br/></li>
<li>Finanzas<br/></li>
<li>Interesados<br/><br/><br/></li>
<div style="font-size: 10pt;"><strong>Folio: '.$folio.'</strong></div>
</ul>
</td>
</tr>
</table>
<p></p>

';
$html2='
<style type="text/css">
    p{
        text-align:justify;
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
    #sangria{
        text-indent: 100px;
    }
</style>

<h4 style="text-align: center;">Carta de Renuncia</h4>
<p></p>
<p style="text-align: right;">'.$dia3.' de '.$meses[$mes3-1].' del '.$anio3.'</p>
<p></p>
<p><strong>Para: Alejandro Saez Veliz.<br/>Alcalde de la comuna de Carahue.</strong></p><br/>
<p><strong>De: '.$nombre_afectado.'<br/></strong></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>De mi consideracion.<br/></p>
<p style="text-indent: 100px;">Por intermedio de la presente carta, comunico usted mi renuncia voluntaria en prestacion de servicios a 
honorarios a partir del '.$dia2.' de '.$meses[$mes2-1].' del '.$anio2.', 
la cual se hara efectiva a partir del '.$dia1.' de '.$meses[$mes1-1].' del '.$anio1.'</p>
<p style="text-indent: 100px;">Agradeciendo el haberme permitido laborar en vuestra institucion, le comunico que los motivos de mi renuncia atienden a temas personales.</p>
<p></p>
<p></p>
<p>Atentamente.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
<tr>
<td></td>
<td style="text-align: center;">_________________________________________________<br/>'.$nombre_afectado.'<br/>'.$rut_afectado.'</td>
</tr>
</table>
';


if($tipo_decreto!="anulacion"){
    mysql_query("update contrato_honorario set estado_contrato='ANULADO' where id_contrato='$id_contrato'");
    mysql_query("update contrato_honorario set folio_anulacion='$folio' where id_contrato='$id_contrato'");

    $documento -> crearCabeceraPagina();
    $documento -> addPagina_Vertical($html);//Decreto
    if($tipo_decreto=="renuncia"){
        $documento -> addPagina_Vertical($html2);//Carta de renuncia
    }
    $documento -> html = $html;
    $documento -> outDocuemnto();

}




