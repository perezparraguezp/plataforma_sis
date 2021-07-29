<hr class="row" />
<div class="row">
    <div class="col l1">
        <i class="mdi-action-info ultra-small" title="INFO"></i>
    </div>
    <div class="col l4">INDICADOR</div>
    <div class="col l3">EVALUACION</div>
    <div class="col l4">EDAD DEL PACIENTE</div>
</div>

<hr class="row" />
<?php
include "../../../php/config.php";
include '../../../php/objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$sql1 = "select * from historial_psicomotor 
        where rut='$rut' and valor_indicador!='' 
        group by fecha_registro,indicador 
        order by fecha_registro desc";

$res1 = mysql_query($sql1);
while($row1 = mysql_fetch_array($res1)){
    $persona = new persona($row1['rut']);

    $fecha = $row1['fecha_registro'];

    $persona->calcularEdadFecha($fecha);


    $indidcador = $row1['indicador'];
    $sql2 = "select * from historial_psicomotor 
              where rut='$rut' and fecha_registro='$fecha'
              and indicador='$indidcador' 
              order by id_historial desc limit 1";
    $row2 = mysql_fetch_array(mysql_query($sql2));

    $valor_indicador = $row2['valor_indicador'];
    if($valor_indicador != 'NORMAL'){
        $color = 'red darken-4 white-text';
    }else{
        $color = 'green lighten-2';
    }

    $title = 'Fecha Registro :'.fechaNormal($fecha).'';
    $value = $row2['valor_indicador'];
    if($value!=''){
        ?>
        <div class="row">
            <div class="col l1">
                <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="<?php echo $title; ?>">(?)</strong>
            </div>
            <div class="col l4"><?php echo $row1['indicador']; ?></div>
            <div class="col l3 <?php echo $color; ?>"><?php echo $value; ?></div>
            <div class="col l4"><?php echo $persona->edad; ?></div>
        </div>
        <?php
    }

}

?>
<script type="text/javascript">
    $(function(){
        $('.tooltipped').tooltip({delay: 50});
    })
</script>
