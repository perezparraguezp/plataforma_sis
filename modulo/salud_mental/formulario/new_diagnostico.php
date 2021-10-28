<?php
include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_diagnostico" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO DIAGNOSTICO</div>
        <div class="col l8 s8 m8">
            <select name="tipo" id="tipo">
                <option disabled="disabled" selected="selected">SELECCIONE UN DIAGNOSTICO</option>
                <option disabled="disabled">-------------------------------------</option>
                <?php
                $sql = "select * from tipo_diagnostico_sm order by nombre_tipo";
                $res = mysql_query($sql);
                while($row = mysql_fetch_array($res)){
                    ?>
                    <option value="<?php echo $row['id_tipo']; ?>"><?php echo $row['nombre_tipo']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">EVALUACION DIAGNOSTICO</div>
        <div class="col l8 s8 m8" id="div_eval">
            <select name="evaluacion" id="evaluacion">
                <option>SELECCIONE UN DIAGNOSTICO</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">FECHA INGRESO</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACION</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertDiagnostico()"> REGISTRAR DIAGNOSTICO</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#tipo').jqxDropDownList({
            width: '100%',
            theme: 'eh-open',
            height: '25px'
        });

        $("#tipo").on('change',function(){
            $.post('ajax/select/valor_diagnostico.php',
                {
                    tipo:$("#tipo").val()
                }
                ,function (data) {
                    $("#div_eval").html(data);

                });
        });


    });
    function insertDiagnostico(){
        if("¿Seguro que desea asignar esta hormona al paciente?"){
            $.post('db/insert/diagnostico.php',
                $("#form_diagnostico").serialize()
                ,function (data) {
                    load_sm_diagnosticos('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
