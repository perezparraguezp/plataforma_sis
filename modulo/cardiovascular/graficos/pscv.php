<form id="grafico_pscv">
    <input type="hidden" name="sector_interno" id="sector_interno" value="<?php echo $_POST['sector_interno']; ?>" />
    <input type="hidden" name="centro_interno" id="centro_interno" value="<?php echo $_POST['centro_interno']; ?>" />
    <input type="hidden" name="sector_comunal" id="sector_comunal" value="<?php echo $_POST['sector_comunal']; ?>" />

    <div class="row">
        <div class="col l4 m6 s12">
            <label>INDICADOR</label>
            <select name="indicador_pscv" id="indicador_pscv"
                    onchange="select_atributo_indicador()">
                <option>SELECCIONAR INDICADOR</option>
                <option>PATOLOGIAS</option>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#indicador_pscv').jqxDropDownList({
                        width: '98%',
                        height: '25px'
                    });
                    $('#indicador_pscv').on('change',function () {
                        var indicador = $("#indicador_pscv").val();
                        $.post('ajax/select/atributo_indicador_pscv.php', {
                            indicador:indicador
                        }, function (data) {
                            $("#atributo_indicador_div").html(data);

                        });
                    })
                });

            </script>
        </div>
        <div class="col l4 m6 s12" id="atributo_indicador_div">

        </div>
    </div>
</form>
