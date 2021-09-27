<div class="card-panel">
    <div class="row">
        <div class="col l8 m3 s12">
            <label for="indicador">INDICADOR
                <select name="indicador" class="browser-default"
                                                     onchange="loadGridPendientes()"
                                                     id="indicador">
                    <option selected="selected" disabled="disabled">SELECCIONAR PENDIENTES</option>
                    <option>ANTROPOMETRIA</option>
                    <option>DENTAL</option>
                    <option>PSICOMOTOR</option>
                    <option>VACUNAS</option>
                    <option>CONTROL DE SALUD</option>
                    <option value="">TODOS</option>

                </select></label>
        </div>
    </div>
</div>
<div id="content_pendientes"></div>
<script type="text/javascript">
    $(function () {
        // loadGridPendientes();
    });
    function loadGridPendientes() {
        var indicador = $("#indicador").val();
        $.post('grid/pendientes.php',{
            indicador:indicador
        },function(data){
            $("#content_pendientes").html(data);

        });
    }
</script>