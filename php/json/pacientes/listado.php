<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/persona.php");


$id_establecimiento = $_SESSION['id_establecimiento'];


$sql = "select * from paciente_establecimiento
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $paciente = new persona($row['rut']);

    $customers[] = array(
        'rut' => strtoupper($paciente->rut),
        'link' => strtoupper($paciente->rut),
        'editar' => strtoupper($paciente->rut),
        'nombre' => strtoupper($paciente->nombre),
        'sexo' => strtoupper($paciente->sexo),
        'nacimiento' => fechaNormal($paciente->fecha_nacimiento),
        'comuna' => strtoupper($paciente->comuna),
        'establecimiento' => strtoupper($row['nombre_centro_interno']),
        'sector_comunal' => strtoupper($row['nombre_sector_comunal']),
        'sector_interno' => strtoupper($row['nombre_sector_interno']),

        'edad' => $paciente->total_meses,
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
