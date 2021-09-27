<div class="card-panel">
    <div class="row">
        <div class="col l8 m3 s12">
            <label for="indicador">INDICADOR
                <select name="indicador_PSCV"
                        style="font-size: 1.5em;"
                        class="browser-default"
                        onchange="loadGridPendientes_pscv()"
                        id="indicador_PSCV">
                    <option selected="selected" disabled="disabled">SELECCIONAR PENDIENTES</option>
                    <option value="">TODOS</option>
                    <option>PARAMETROS</option>
                    <option>DIABETES</option>
                    <option>PENDIENTES SIGGES</option>
                    <option>CONTROL DE SALUD</option>
                </select></label>
        </div>
    </div>
</div>
<div id="content_pendientes"></div>
<script type="text/javascript">
    $(function () {
        // loadGridPendientes_pscv();
    });
    function loadGridPendientes_pscv() {
        var indicador = $("#indicador_PSCV").val();
        // alertaLateral(indicador);
        $.post('grid/pendientes.php',{
            indicador:indicador
        },function(data){
            $("#content_pendientes").html(data);

        });
    }
</script>