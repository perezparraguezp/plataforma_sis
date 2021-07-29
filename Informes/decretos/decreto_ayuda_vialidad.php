<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/proveedor.php");
include("../../php/class/decreto.php");
include("../../php/objetos/documento.php");


session_start();
error_reporting(0);


$decreto1 = $_POST['decreto1'];
$decreto2 = $_POST['decreto2'];
$decreto3 = $_POST['decreto3'];
$visto1 = $_POST['visto1'];
$visto2 = $_POST['visto2'];
$visto3 = $_POST['visto3'];
$visto4 = $_POST['visto4'];
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];
$rutP = $_POST['rutP'];
$nombreP = $_POST['nombreP'];



$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$id_mio = $_SESSION['id_empleado'];

$f = new functionario($id_mio);



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


//Variables recibidas
$sqlF1 = "select * from firmantes where id_empleado='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from firmantes where id_empleado='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));

$nombreF1 = trim($rowF1['nombre_firma']);
$nombreF2 = trim($rowF2['nombre_firma']);

$nombre_control = trim($rowF3['nombre_firma']);

$encargados = trim($_POST['encargados']);

if($_POST['secretario_s']){
    $cargoF2 = "Secretario Municipal (s)";
}else{
    $cargoF2 = "Secretario Municipal";
}

if($_POST['alcalde_s']){
    $cargoF1 = "Alcalde (s)";
}else{
    $cargoF1 = "Alcalde";
}


$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

// create new PDF document
$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Decreto de Ayuda Social\n".$f->nombre_depto;
$documento = new documento($title_pdf,$sub_title_pdf,"Decreto de Ayuda Social");

$documento->crearFolio();

$documento->updateTipoDocumento("Alcaldicios sin Registro","Ayuda Social");
$folio = $documento->folio;

$documento->updateDatosDocumento($rutP,$nombreP,'Ayuda social');





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
<p><strong>DECRETO N:</strong><strong></strong></p>
<p><strong>CARAHUE,</strong><strong></strong><br /><strong>VISTOS: Estos Antecedentes</strong></p>
<p></p><p></p><p>
1.-'.$visto1.'<br />
2.-'.$visto2.'<br />
3.-'.$visto3.'<br />';


if ($visto4){
    $html.= '3.-'.$visto4.'<br />';
}

$html.='</p>
<p><strong><u>DECRETO</u></strong></p>
<p>
1.-'.$decreto1.'<br />
2.-'.$decreto2.'<br />';

if ($decreto3){
    $html.= '3.-'.$decreto3.'<br />';
}

$html.='<p></p><p></p><p></p><p></p>
<blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE</blockquote>
<p></p><p></p><p></p>
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
<br/><br/><br/><br/>
<tr>
<td style="text-align: left;font-size:0.7em;">
<strong style="font: 0.7em;">'.$encargados.'</strong><br/>
<strong style="font: 0.7em;">DISTRIBUICION</strong>
<ul>
<li>Archivo Municipal</li>
<br/>
<li>Interesados</li>
<br/>
<li>Folio N° '.$folio.'</li>
</ul>
</td>
</tr>
</table>
<p></p>
';


$documento->CrearPDF($html);


?>