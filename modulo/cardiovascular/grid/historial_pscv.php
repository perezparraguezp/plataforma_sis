<hr class="row" />
<div class="row">
    <div class="col l2">
        <i class="mdi-action-info ultra-small" title="INFO"></i>
    </div>
    <div class="col l3">INDICADOR</div>
    <div class="col l3">EVALUACION</div>
    <div class="col l4">EDAD</div>
</div>

<hr class="row" />
<?php
include "../../../php/config.php";
include '../../../php/objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$indidcador = $_POST['indicador'];

mysql_query($sql0);
$sql1 = "select * from historial_pscv 
        where rut='$rut' and valor!='' 
        and indicador='$indidcador' 
        order by id_historial desc";


$res1 = mysql_query($sql1);
while($row1 = mysql_fetch_array($res1)){
    $persona = new persona($row1['rut']);

    $fecha = $row1['fecha_registro'];

    $persona->calcularEdadFecha($fecha);

    $indidcador = $row1['indicador'];
    $value = $row1['valor'];

    if($value!=''){
        ?>
        <div class="row">
            <div class="col l2">
                <?php echo fechaNormal($row1['fecha_registro']); ?>
            </div>
            <div class="col l3"><?php echo $indidcador; ?></div>
            <div class="col l3 <?php echo $color; ?>"><?php echo $value; ?></div>
            <div class="col l3"><?php echo $persona->edad; ?></div>
            <div class="col l1">
                <?php
                if($persona->myID==$row1['id_profesional']){
                    ?>
                    <i class="mdi-action-delete red-text"
                       onclick="deleteHistorial('historial_pscv','<?php echo $row1['id_historial']; ?>')"
                       style="cursor: pointer;"></i>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

}

?>
<script type="text/javascript">
    $(function(){
        $('.tooltipped').tooltip({delay: 50});
    });
    function deleteHistorial(table,id) {
        if(confirm('DESEA ELIMINAR ESTE REGISTRO')){
            $.post('db/delete/historial.php',{
                table:table,
                id:id
            },function (data) {
                if(data!=='ERROR_SQL'){
                    alertaLateral('REGISTRO ELIMINADO');
                    loadHistorialPSCV('<?php echo $rut ?>','<?php echo $indidcador ?>')
                }
            });
        }
    }
</script>
