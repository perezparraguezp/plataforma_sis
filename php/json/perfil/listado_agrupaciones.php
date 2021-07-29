<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/tipo_agrupacion.php");
include("../../objetos/agrupacion.php");

$sql = "select * from agrupacion_escolar 
          order by fecha_hasta desc";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $t = new tipo_agrupacion($row['id_tipo_agrupacion']);
    $a = new agrupacion($row['id_agrupacion']);
    if($t->existe){
        $customers[] = array(
            'tipo' => $t->nombre_tipo,
            'desde' => $a->desde,
            'hasta' => $a->hasta,
            'estado' => $a->estado,
            'info' => '<i onclick="boxInfoAgrupacion(\''.$a->id.'\')" class="mdi-action-info" style="font-size: 0.9em;cursor: pointer;"></i>'
        );
    }
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
