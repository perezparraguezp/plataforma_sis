<?php
include '../../../php/config.php';
include '../../../php/objetos/persona.php';
include '../../../php/objetos/profesional.php';

$rut = $_POST['rut'];
$indicador = $_POST['indicador'];
$paciente = new persona($rut);
$paciente->loadAntropometria();

$sql = "select * from historial_antropometria 
          where rut='$paciente->rut'  
          and indicador='$indicador' 
          group by indicador,fecha_registro 
          order by fecha_registro desc ";

$res = mysql_query($sql);
$data = '';

$color = ['amber lighten-4','yellow lighten-4'];
$i = 0;
while($row = mysql_fetch_array($res)){
    list($fecha,$hora) = explode(" ",$row['fecha_registro']);

    $sql0 = "select * from historial_antropometria 
          where rut='$paciente->rut'  and indicador='$indicador' 
          order by id_historial desc limit 1";

    $row0 = mysql_fetch_array(mysql_query($sql0));


    $valor = trim($row0['valor']);

    if($valor!=''){
        $profesional = new profesional($row0['id_empleado']);
        if($profesional->existe){
            if($indicador == 'SCORE_IRA' && $valor!='LEVE' ){
                $sql1 = "select * from historial_antropometria 
                  where rut='$paciente->rut'  and indicador='VISITA_SCORE' 
                  and fecha_registro='$fecha'  
                  order by id_historial desc limit 1  ";;

                $row1 = mysql_fetch_array(mysql_query($sql1));
                if($row1){
                    $valor = $valor.'<br />[VISITA DOM.: '.$row1['valor'].']';
                }

            }
            $c = $color[$i%2];
            $data .= '<div class="row '.$c.'">
                        <div class="col l2">'.fechaNormal($fecha).'</div>
                        <div class="col l2">'.$indicador.'</div>
                        <div class="col l2">'.$valor.'</div>
                        <div class="col l6">'.$profesional->nombre.'</div>
                    </div>';

            $i++;
        }

    }

}


?>
<div class="modal-content">
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="col l12">
            <div class="row amber">
                <div class="col l2">FECHA</div>
                <div class="col l2">INDICADOR</div>
                <div class="col l2">EVALUACION</div>
                <div class="col l6">PROFESIONAL</div>
            </div>
            <?php echo $data; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>

