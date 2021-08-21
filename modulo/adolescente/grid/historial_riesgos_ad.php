<div class="container">
    <hr class="row" />
    <div class="row">
        <div class="col l2">
            <i class="mdi-action-info ultra-small" title="INFO"></i>
        </div>
        <div class="col l6">INDICADOR</div>
        <div class="col l4">EDAD</div>
    </div>

    <hr class="row" />
    <?php
    include "../../../php/config.php";
    include '../../../php/objetos/persona.php';

    $rut = str_replace('.','',$_POST['rut']);
    $tipo_riesgo = $_POST['tipo_riesgo'];
    $sql1 = "select * from riesgo_adolescente 
        inner join tipo_riesgo_adolescente using(id_tipo_riesgo) 
        where rut='$rut'  AND tipo_area='$tipo_riesgo'
        order by fecha_registro desc";

    $res1 = mysql_query($sql1);
    while($row1 = mysql_fetch_array($res1)){
        $persona = new persona($row1['rut']);

        $fecha = $row1['fecha_registro'];

        $persona->calcularEdadFecha($fecha);
        $indidcador = $row1['nombre_riesgo'];

        if(true){
            ?>
            <div class="row">
                <div class="col l2">
                    <?php echo fechaNormal($row1['fecha_registro']); ?>
                </div>
                <div class="col l6"><?php echo $indidcador; ?></div>
                <div class="col l4"><?php echo $persona->edad; ?></div>
            </div>
            <?php
        }

    }

    ?>
    <script type="text/javascript">
        $(function(){
            $('.tooltipped').tooltip({delay: 50});
        });
    </script>

</div>