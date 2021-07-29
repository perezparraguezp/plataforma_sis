<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/profesional.php");


$rut = $_GET['rut'];

$sql = "select * from historial_paciente  
          where rut='$rut' 
        
          order by id_historial desc";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $profesional = new profesional($row['id_profesional']);
    list($fecha_registro,$hora_registro) = explode(" ",$row['fecha_registro']);
    if($_SESSION['id_usuario']==$row['id_profesional']){
        $borrar = $row['id_historial'];
    }else{
       $borrar='';
    }
    $customers[] = array(
        'tipo' => $row['tipo_historial'],
        'texto' => $row['texto'],
        'fecha' => fechaNormal($fecha_registro),
        'profesional' => $profesional->nombre,
        'borrar' => $borrar,
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
