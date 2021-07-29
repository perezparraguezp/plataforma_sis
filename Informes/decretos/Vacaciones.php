<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
error_reporting(0);
session_start();
$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);
$id_solicitud = $_POST['id_vacaciones'];
//Generacion de Folio
$sql_1 = "select * from vacaciones where id_vacaciones='$id_solicitud' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
$id = $id_solicitud;
$sql1 = "select * from vacaciones where id_vacaciones='$id' limit 1";
$res1 = mysql_query($sql1);
$row1 = mysql_fetch_array($res1);
$desde = $row1['desde_v'];
$hasta = $row1['hasta_v'];
$decreto = $row1['decreto'];
$empleado = $row1['id_empleado'];
list($a1, $m1, $d1) = explode("-", $desde);
list($a2, $m2, $d2) = explode("-", $hasta);

$fechaInicio = strtotime("$d1-$m1-$a1");
$fechaFin = strtotime("$d2-$m2-$a2");




$tipo_decreto = $_POST['tipo_decreto'];
$atributo_decreto = "&&TRANSPARENCIA&";
$referencia_decreto = 'Feriado Legal';

$sql1 = "select * from vacaciones inner join funcionario on id_empleado=reloj
    where id_vacaciones='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$f = new functionario($row1['reloj']);
$rut = $row1['rut'];
$nombre_funcionario = $f->nombre;

$texto_decreto_grilla='Feriado Legal desde '.fechaNormal($desde)." hasta ".fechaNormal($hasta);


$id_formato_decreto = $_POST['id_formato_decreto'];
//mysql_query("delete from vistos_decreto where id_decreto='$id_formato_decreto'");

$sql2 = "select * from vacaciones inner join funcionario on id_empleado=reloj
    where id_vacaciones='$id_solicitud' limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));
$id_empleado_solicitante = $row2['id_empleado'];
$sql_1 = "select * from funcionario where reloj='$id_empleado_solicitante' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
//echo $sql2;;
$tipo_contrato = $row_1['tipo'];
if($tipo_contrato == 'MUNICIPAL'){
    $depto_doc = "Direccion de Administracion y Finanzas";
    $oficina_personal = 'Diego Portales #295';
}else{
    if($tipo_contrato == 'EDUCACION'){
        $depto_doc = "Departamento de Educación";
        $oficina_personal = 'Pedro de Valdivia 241';
    }else{
        $depto_doc = "Departamento de Salud";
        $oficina_personal = 'Villagrán #256, 2do Piso';
    }
}

if($_POST['registro']){
    $registrese = $_POST['registro'];
}else{
    $registrese = "";
}
//funciones para calcular dias habiles
function sumarDias($fecha, $dia) {
    $nuevafecha = strtotime('+' . $dia . ' day', strtotime($fecha));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    return $nuevafecha;
}


function Feriado($anio, $mes, $dia) {
    $sqlFeriado = "select * from feriado where dia='$dia' and mes='" . (int) $mes . "' limit 1";
    $rowFeriado = mysql_fetch_array(mysql_query($sqlFeriado));
    if ($rowFeriado) {
        return true;
    } else {
        return false;
    }
}

function dias_transcurridos($fecha_i, $fecha_f) {
    $d = $fecha_i;
    $validar = false;

    if($fecha_f == $fecha_i){
        $total = 1;//solo se descuenta un dia
    }else{
        $total = 0;
        while ($validar == false) {
            if ($d == $fecha_f) {
                $validar = true;
            }
            list($anio, $mes, $dia) = explode("-", $d);
            $semana = diaSemana($anio, $mes, $dia);
            if ($semana != 0 && $semana != 6) {//Lunes a viernes
                $feriado = Feriado($anio, $mes, $dia);//feriado
                if ($feriado == false) {
                    $total++;
                }
            }
            //aumentamos en un dia cuando terminamos de consultar la fecha
            $d = sumarDias($d, 1);
        }
    }
    return $total;//cantidad de dias solicitados
}

$diasHabiles = $_POST['diasHabilesV'];
$encargados = $_POST['encargados'];
//decreto 51
//decreto 51
if(@$_POST['por_orden_vacaciones']){
    $por_orden = $_POST['por_orden_vacaciones']; //Ordena a Administradora
}else{
    $por_orden = $_POST['por_orden']; //Ordena a Administradora
}
$alcalde_s = $_POST['alcalde_s']; //Subrogante Secretario
$secretario_s = $_POST['secretario_s']; //Subrogante Alcalde
$decreto = $_POST['decreto_texto']; //decreto
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];


mysql_query("update formato_decreto set firma1='$firma1',firma2='$firma2', encargados='$encargados'
    where id_decreto='$id_formato_decreto'");

//Variables recibidas
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
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


//Actualizar Estado Solicitud;
mysql_query("update vacaciones set estado_v='DECRETADA' where id_vacaciones='$id_solicitud'");
$sql1 = "select * from vacaciones inner join funcionario on id_empleado=reloj
    where id_vacaciones='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$f = new functionario($row1['reloj']);
$rut = $row1['rut'];
$empleado = $row1['reloj'];






mysql_query("insrt into estado_solicitud(id_solicitud,id_empleado,fecha_cambio,hora_cambio,estado,tabla)
        values('$id_solicitud','$empleado',current_date(),current_time(),'DECRETADA','solicitudausencia')");
$f2 = new functionario($row1['id_encargado']);
$responsable = $f2->nombre;
$nombre_funcionario = $f->nombre;
$sql1 = "select * from vacaciones inner join funcionario on id_empleado=reloj
      inner join escalafon using(id_escalafon)
    where id_vacaciones='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1){
    $cargo = $row1['nombre_escalafon'] . ' (' . $row1['grado'] . ')';
}else{
    if($tipo_contrato =='EDUCACION'){
        $cargo = 'Funcionario DAEM';
    }else{
        if($tipo_contrato == 'SALUD'){
            $cargo = 'Funcionario de Salud';
        }else{
            $cargo = 'Honorario';
        }
    }
}
mysql_query("update funcionario set d_V='$diasHabiles' where='" . $row1['reloj'] . "'");

//fechas
$desde = $_POST['desde_v'];
$hasta = $_POST['hasta_v'];
$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');

$texto_decreto = "";
if (@$_POST['check_decreto_inferior']) {
    $texto_decreto = "<p>2.- ".$_POST['decreto_inferior']."</li>>";
    //echo $decreto;
}

$documento = new documento("Feriado Legal","Oficina de Personal","Feriado Legal");
$documento->crearFolio();
$documento->updateTipoDocumento("Personal con Registro","Feriado Legal");
$documento->updateDatosDocumento($rut,$nombre_funcionario,$texto_decreto_grilla);
$id_interno = $documento->folio;

//texto 
$texto = '';
$k = 1;
$check = $_POST['check'];
$vistos = $_POST['item'];
$texto_vistos = '';
foreach ($vistos as $i => $val) {
    if($check[$i]){
        $texto_vistos .="<p>$k .- " .limpiaCadena($val)."</p>";
        $k++;
    }

}
mysql_query("delete from vistos_decreto where id_decreto='$id_formato_decreto'");
foreach ($vistos as $i => $val) {
    if($check[$i]){
        $obligacion='SI';
    }else{
        $obligacion='NO';
    }
    mysql_query("insert into vistos_decreto(id_decreto,visto,obligacion)
    values('$id_formato_decreto','$val','$obligacion')");
}
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
//Dias 
$total_dias = dias_transcurridos($desde, $hasta);
$obs = "<br /><span>Obs: <strong>Se acepta conforme a lo solicitado</strong></span>";

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
' . $texto_vistos . '

<p><strong><u>DECRETO</u></strong></p>
<p>1.-Autorizase para hacer uso de Feriado Legal en las fechas que se indican al siguiente funcionario:</p>
<table border="1px">
<tr style="background-color: antiquewhite;font-weight: bold;">
   <td>RUN</td>
   <td>Nombre</td>
   <td>Cargo</td>
   <td>Dias</td>
   <td>Desde</td>
   <td>Hasta</td>
</tr>
<tr>
   <td>' . $rut . '</td>
   <td>' . $nombre_funcionario . '</td>
   <td>' . $cargo . '</td>
   <td>' . $total_dias . '</td>
   <td>' . $desde . '</td>
   <td>' . $hasta . '</td>
</tr>
</table>
'.$texto_decreto.'
<blockquote>ANOTESE'.$registrese.', COMUNIQUESE Y ARCHIVESE<blockquote>' . $porOrden . '</blockquote></blockquote>
<p></p>
<table>
<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>

</table>
<table>
<tr>
    <td style="text-align: center;font-size:1em;">
        <span style="text-align: center;font-size:1.2em;;font-weight: bold;">' . $nombreF1 . '</span><br />
        <span style="font-size:1.1em;;">' . $cargoF1 . '</span>
        </td>
    <td style="text-align: center;font-size:1.1em;;">
        <span style="text-align: center;font-size:1.2em;;font-weight: bold;">' . $nombreF2 . '</span><br />
       <span style="font-size:1.1em;">' . $cargoF2 . '</span>
    </td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
<tr>
    <td style="text-align: left;font-size:12pt;">
        <strong>' . $encargados . '</strong>
    </td>
    <td></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: left;font-size:12pt;">
<strong><u>DISTRIBUICION</u></strong>
<ul>
<li>Archivo Municipal</li>
<li>Archivo Personal</li>
<li>Interesado</li>
<li>Folio: <strong>'.$id_interno.'</strong></li>
</ul>
</td>
<td>
</td>
</tr>
</table>

';

mysql_query("update vacaciones set pdf='$html'
    where id_vacaciones='$id_solicitud'");
// Print text using writeHTMLCell()
$documento->CrearPDF($html);
//============================================================+
// END OF FILE
//============================================================+
