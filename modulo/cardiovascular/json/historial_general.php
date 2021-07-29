<?php
#Include the connect.php file
include("../../../php/config.php");
include("../../../php/objetos/profesional.php");



$rut = $_GET['rut'];

$sql = "select * from historial_pscv  
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
        'tipo' => 'ANTECEDENTES',
        'texto' => $row['indicador'].' ['.$row['valor'].']',
        'fecha' => fechaNormal($fecha_registro),
        'profesional' => $profesional->nombre,
        'borrar' => $borrar,
    );
    $i++;
}
$sql = "select * from historial_parametros_pscv  
          where rut='$rut' 
          order by id_historial desc";
$res = mysql_query($sql);

while($row = mysql_fetch_array($res)){
    $profesional = new profesional($row['id_profesional']);
    list($fecha_registro,$hora_registro) = explode(" ",$row['fecha_registro']);
    if($_SESSION['id_usuario']==$row['id_profesional']){
        $borrar = $row['id_historial'];
    }else{
        $borrar='';
    }
    $customers[] = array(
        'tipo' => 'PARAMETROS',
        'texto' => $row['indicador'].' ['.$row['valor'].']',
        'fecha' => fechaNormal($fecha_registro),
        'profesional' => $profesional->nombre,
        'borrar' => $borrar,
    );
    $i++;
}

$sql = "select * from historial_diabetes_mellitus  
          where rut='$rut' 
          order by id_historial desc";
$res = mysql_query($sql);

while($row = mysql_fetch_array($res)){
    $profesional = new profesional($row['id_profesional']);
    list($fecha_registro,$hora_registro) = explode(" ",$row['fecha_registro']);
    if($_SESSION['id_usuario']==$row['id_profesional']){
        $borrar = $row['id_historial'];
    }else{
        $borrar='';
    }
    $customers[] = array(
        'tipo' => 'DIABETES',
        'texto' => $row['indicador'].' ['.$row['valor'].']',
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
