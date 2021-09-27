<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];
?>
<form id="form_Ecografia" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <input type="hidden" name="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO ECOGRAFIA</div>
        <div class="col l8 s8 m8">
            <select name="tipo_eco" id="tipo_eco">
                <option>INTRASISTEMA</option>
                <option>EXTRASISTEMA</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">FECHA ECOGRAFIA</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_eco" id="fecha_eco" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">TRIMESTRE</div>
        <div class="col l8 s8 m8">
            <select name="trimestre" id="trimestre">
                <option>PRIMER</option>
                <option>SEGUNDO</option>
                <option>TERCERO</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertEcografia()"> REGISTRAR ECOGRAFIA</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#tipo_eco').jqxDropDownList({
            width: '100%',
            theme: 'eh-open',
            height: '25px'
        });
        $('#trimestre').jqxDropDownList({
            width: '100%',
            theme: 'eh-open',
            height: '25px'
        });

    });
    function insertEcografia(){
        if("¿Seguro que desea asignar esta ecografía al registro de esta gestación?"){
            $.post('db/insert/ecografia_mujer.php',
                $("#form_Ecografia").serialize()
                ,function (data) {
                    load_m_gestaciones('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
