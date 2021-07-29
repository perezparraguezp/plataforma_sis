<?php
$filename = $_POST['filename'];
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename.xls");
$array = $_POST;

foreach ($array as $fila => $item){
    echo $item;
}