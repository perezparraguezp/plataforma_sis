<?php
$rut = $_POST['rut'];
?>
<form id="form_Hormona" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO HORMONA</div>
        <div class="col l8 s8 m8">
            <select name="tipo_hormona" id="tipo_hormona">
                <option>ORAL COMBINADO</option>
                <option>ORAL PROGESTÁGENO</option>
                <option>INYECTABLE COMBINADO</option>
                <option>INYECTABLE PROGESTÁGENO</option>
                <option>IMPLANTE ETONOGESTREL (3 AÑOS)</option>
                <option>IMPLANTE LEVONORGESTREL (5 AÑOS)</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">VENCIMIENTO</div>
        <div class="col l8 s8 m8">
            <input type="date" name="vencimiento" id="vencimiento" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertHormonaPaciente()"> REGISTRAR </div>
    </div>


</form>
<script type="text/javascript">
    $(function(){
        $('#tipo_hormona').jqxDropDownList({
            width: '100%',
            height: '25px'
        });

    });
    function insertHormonaPaciente(){
        $.post('db/insert/hormona.php',
            $("#form_Hormona").serialize()
            ,function (data) {

        });
    }
</script>
