<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];
?>
<form id="form_CONTROL_NUTRI" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <input type="hidden" name="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">FECHA CONTROL</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_control" id="fecha_control" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">EVALUACION NUTRICIONAL</div>
        <div class="col l8 s8 m8">
            <select name="evaluacion" id="evaluacion">
                <option>NORMAL</option>
                <option>BAJO PESO</option>
                <option>OBESA</option>
                <option>SOBRE PESO</option>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#evaluacion').jqxDropDownList({
                        width: '100%',
                        theme: 'eh-open',
                        height: '25px'
                    });
                })
            </script>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">TIPO CONTROL</div>
        <div class="col l8 s8 m8">
            <select name="tipo_control" id="tipo_control">
                <option>REGULAR</option>
                <option>3º MES POST PARTO</option>
                <option>6º MES POST PARTO</option>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#tipo_control').jqxDropDownList({
                        width: '100%',
                        theme: 'eh-open',
                        height: '25px'
                    });
                })
            </script>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES</div>
        <div class="col l8 s8 m8">
            <textarea name="obs_control"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertControlNutriGestacion()"> REGISTRAR CONTROL NUTRICIONAL</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){

    });
    function insertControlNutriGestacion(){
        if("¿Seguro que desea registrar esta visita al paciente?"){
            $.post('db/insert/control_nutri_gestacion.php',
                $("#form_CONTROL_NUTRI").serialize()
                ,function (data) {
                    load_m_gestaciones('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
