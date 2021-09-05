<?php

$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['val'];
$fecha = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];


?>
<form id="form_BIOPSICOSOCIAL" class="container">
    <input type="hidden" name="column" value="<?php echo $column; ?>" />
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="value" value="<?php echo $value; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha; ?>" />
    <input type="hidden" name="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <div class="row">
        <label for="obs_psicosocial">TIPO DE REGISTRO BIOPSICOSOCIAL</label><br />
        <strong><?php echo $value; ?></strong>
    </div>
    <div class="row">
        <label for="obs_psicosocial">OBSERVACIONES BIOPSICOSOCIAL</label>
        <textarea rows="10" id="obs_psicosocial" name="obs_psicosocial"></textarea>
    </div>
    <div class="row">
        <div class="col l12">
            <input type="button" style="width: 98%;"
                   onclick="confirmar_BIOPSICOSOCIAL()"
                   class="btn-large green" value="CONFIRMAR REGISTRO BIOPSICOSOCIAL"   />
        </div>
    </div>
</form>
<script type="text/javascript">
    function confirmar_BIOPSICOSOCIAL(){
        var val = $("#riesgo_biopsicosocial").val();
        $.post('db/update/m_gestante.php',
            $("#form_BIOPSICOSOCIAL").serialize()
            ,function(data){
            alertaLateral(data);
            $('.tooltipped').tooltip({delay: 50});
            if($("#riesgo_biopsicosocial").val() !=='SIN RIESGO'){
                $('#div_vdi').show();
            }else{
                $('#div_vdi').hide();
            }
        });
    }

</script>
