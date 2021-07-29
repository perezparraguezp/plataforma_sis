<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");


//Eliminamos los textos del documento

$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);

$codigo_documento = $_POST['codigo_documento'];
mysql_query("delete from base_texto_documento where codigo_documento='$codigo_documento'");

$row = mysql_fetch_array(mysql_query(
    "select * from base_documentos where codigo_documento='$codigo_documento' limit 1"
));
$tipo_documento = $row['nombre_documento'];

if($tipo_documento == 'Comercio Ambulante'){
    $vistos_decreto = '<p>1.- La  solicitud  presentada, para efectuar COMERCIO AMBULANTE en la comuna de Carahue.</p>
<p>2.- El Artículo 4, números 1, 2, 3 y 4 del título Derechos por Comercio Ambulante, de la ordenanza municipal de fecha 30 de diciembre de 2005.</p>
<p>3.- El articulo Nº 7 de la ordenanza municipal de fecha 30 de diciembre de 2005.</p>
<p>4.- Las facultades que me confiere el texto refundido de la Ley 18.695, <strong>Orgánica Constitucional de Municipalidades</strong>.</p>';
}else{
    if($tipo_documento == 'Beneficios'){
        $vistos_decreto = '<p>1.- La solicitud presentada para efectuar BENEFICIO en la comuna de Carahue.</p>
<p>2.- El articulo Derechos Varios, N°2, 3, 4, 5, 6, 7 y 8,  de la ordenanza municipal de fecha 30 de Diciembre de 2005.</p>
<p>3.- El articulo Nº 7 de la ordenanza municipal de fecha 30 de Diciembre de 2005.</p>
<p>4.- Las facultades que me confiere el texto refundido de la Ley 18.695, <strong>Orgánica Constitucional de Municipalidades</strong>.</p>';
    }else{
        if($tipo_documento == 'Patentes Comerciales'){
            $vistos_decreto = '<p>1.- La solicitud de Patente  presentada para inicio de actividad comercial en la comuna de Carahue.</p>
<p>2.- El articulo Nº 14 de D.S. Nº484 de 1980.</p>
<p>3.- Los artículos 23 al 34 del Decreto Ley N°3.063 de 1979,”Ley de Rentas Municipales”.</p>
<p>4.- Las facultades que me confiere el texto refundido de la Ley 18.695, <strong>Orgánica Constitucional de Municipalidades</strong>.</p>';
        }else{
            $vistos_decreto = '';
        }
    }
}

$documento = new documento("I. Municipaldiad de Carahue","Rentas Municipales - $tipo_documento\nPortales #295, Carahue","Decreto");



$documento->crearFolio();
$folio = $documento->folio;

$item = $_POST['item'];
$text_li = '';
$indice = 1;
foreach ($item as $i => $value){
    $value = str_replace('<div>','',$value);
    $value = str_replace('</div>','',$value);

    $value = trim($value);
    if($indice==1){
        //Solo el primer visto
        $documento->updateDatosDocumento('','',trim($value));
        mysql_query("insert into doc_imprimir(folio,id_empleado,id_depto,fecha_creacion,tipo_doc,referencia)
                values('".$folio."','".$id_mio."','".$f_mio->depto."',current_date(),'".$tipo_documento."','".strip_tags($value)."')
        ");

    }
    $text_li .='<p style="text-indent: 0px;">'.$indice.'.- '.trim($value).'</p>';
    mysql_query("insert into base_texto_documento(codigo_documento,texto_li,activo,tipo_texto,orden)
    values('$codigo_documento','".strip_tags($value)."',1,'DECRETO',$indice)");
    $indice++;
}


$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];

//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$nombreF1 = trim($rowF1['nombre_directivo']);
$nombreF2 = trim($rowF2['nombre_directivo']);
if($_POST['secretario_s']){
    $cargoF1 = "Secretario Municipal (s)";
}else{
    $cargoF1 = "Secretario Municipal";
}

if($_POST['alcalde_s']){
    $cargoF2 = "Alcalde (s)";
}else{
    $cargoF2 = $rowF2['cargo_directivo'];
}
$depto_doc = "Direccion de Administracion y Finanzas";
$oficina_personal = 'Diego Portales #295';


if(@$_POST['registro']){
    @$registrese = $_POST['registro'];
}else{
    $registrese = "";
}


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
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:10pt;
    }
</style>
<p>DECRETO N:</p>
<p>Carahue, <br />VISTOS: Estos Antecedentes</p>        
'.$vistos_decreto.'

<p><strong><u>DECRETO</u></strong></p>
'.$text_li.'
<blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE<blockquote></blockquote></blockquote>
<table>
<tr><td></td></tr>
</table>
<table>
<tr>
    <td style="text-align: center;font-size:1em;">
        <span style="text-align: center;font-size:1.2em;;font-weight: bold;">' . $nombreF1 . '</span><br />
        <span style="font-size:1em;;">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;font-size:1.1em;;">
        <span style="text-align: center;font-size:1.2em;;font-weight: bold;">' . $nombreF2 . '</span><br />
       <span style="font-size:1em;">' . $cargoF2 . '</span>
    </td>
</tr>
<tr>
    <td style="text-align: left;font-size:12pt;"><br /><strong></strong></td>
    <td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: left;font-size:0.8em;">
<strong><u>DISTRIBUICION</u></strong>
<ul>
<li style="text-align: left;font-size:0.8em;">Interesado</li>
<li style="text-align: left;font-size:0.8em;">Control</li>
<li style="text-align: left;font-size:0.8em;">Archivo Municipal</li>
<li style="text-align: left;font-size:0.8em;">Archivo Depto.</li>
<li style="text-align: left;font-size:1em;">Folio: '.$documento->folio.'</li>
</ul>
</td>
<td>
</td>
</tr>
</table><br />
';
$documento->updateTipoDocumento('Alcaldicios sin Registro',$tipo_documento);
$documento->CrearPDF($html);


