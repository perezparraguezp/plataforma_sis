<?php
include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_actividad_lista" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO ACTIVIDAD</div>
        <div class="col l8 s8 m8">
            <select name="tipo" id="tipo">
                <option value="" disabled="disabled" selected="selected">SELECCIONE UN ANTECEDENTE</option>
                <option disabled="disabled">-------------------------------------</option>
                <?php
                $sql = "select * from activiad_sm order by nombre_actividas";
                $res = mysql_query($sql);
                while($row = mysql_fetch_array($res)){
                    ?>
                    <option><?php echo $row['nombre_actividas']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">FECHA REGISTRO</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACION</div>
        <div class="col l8 s8 m8">
            <textarea name="obs" id="obs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertActividad()"> REGISTRAR ACTIVIDAD</div>
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



    });
    function insertActividad(){
        var obs = $("#obs").val();
        var antecedente = $("#tipo").val();
        if(antecedente !== '' ){
            if(obs !== ''){
                if(confirm("¿Seguro que desea asignar esta Actividad al Paciente?")){
                    $.post('db/insert/actividad.php',
                        $("#form_actividad_lista").serialize()
                        ,function (data) {
                            load_sm_actividad('<?php echo $rut; ?>');
                            document.getElementById("close_modal").click();
                        });
                }
            }else{
                alertaLateral('DEBE INGRESAR UNA OBSERVACION PARA LA ACTIVIDAD');
                $("#obs").css({
                    'background-color':'pink',
                    'border':'solid 1px red'
                });
                $("#obs").focus();
            }
        }else{
            alertaLateral('DEBE INGRESAR UNA ACTIVIDAD');
            $("#tipo").css({
                'background-color':'pink',
                'border':'solid 1px red'
            });
            $("#tipo").focus();
        }


    }
</script>
