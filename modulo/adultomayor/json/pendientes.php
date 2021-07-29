<?php
#Include the connect.php file
include("../../../php/config.php");
include("../../../php/objetos/persona.php");



$id_establecimiento = $_SESSION['id_establecimiento'];

$indicador = $_GET['indicador'];

if($indicador!=''){

}
$rut = trim($_GET['rut']);
if($rut!=''){
    $filtro_rut =" and paciente_establecimiento.rut='$rut' ";
    $persona_filtro = new persona($rut);
}else{
    $filtro_rut = "";
}

//filtro tope de edad

$sql = "select * from paciente_establecimiento
              right join historial_parametros_am using(rut)
              where paciente_establecimiento.m_adulto_mayor='SI'
              and paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut
              and historial_paciente.fecha_registro > adddate(historial_paciente.fecha_registro, interval 395  DAY)
              group by paciente_establecimiento.rut";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $paciente = new persona($row['rut']);
    $customers[] = array(
        'rut' => $paciente->rut,
        'link' => $paciente->rut,
        'mail' => $paciente->email,'nombre' => $paciente->nombre,
        'tipo' => 'CONTROL DE SALUD',
        'indicador' => 'MAYOR A 1 AÑO',
        'ultima_ev' => $paciente->getUltimaEval(),
        'edad_actual' => $paciente->edad,
        'contacto' => $paciente->telefono,
        'establecimiento' => $paciente->getEstablecimiento()
    );
    $i++;
}

if($i>0){
    $data[] = array(
        'TotalRows' => ''.$i,
        'Rows' => $customers
    );
    echo json_encode($data);
}

?>
