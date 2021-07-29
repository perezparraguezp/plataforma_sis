<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/tipo_documento.php");

$sql = "select * from tipo_documento 
          order by nombre_tipo_doc";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $t = new tipo_documento($row['id_tipo_doc']);
    if($t->existe){
        $customers[] = array(
            'nombre' => $t->nombre_tipo,
            'texto' => $t->texto_tipo,
            'edit' => '<i class="mdi-editor-border-color blue-text" style="font-size: 1em;cursor: pointer;"></i>',
            'delete' => '<i class="mdi-action-delete red-text" style="font-size: 1em;cursor: pointer;"></i>',
            'info' => '<i class="mdi-action-info" style="font-size: 0.9em;cursor: pointer;"></i>'
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
