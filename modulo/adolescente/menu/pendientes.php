
<div id="content_pendientes"></div>
<script type="text/javascript">
    $(function () {
        loadGridPendientes_ad();
    });
    function loadGridPendientes_ad() {
        var indicador = '';
        // alertaLateral(indicador);
        $.post('grid/pendientes.php',{
            indicador:indicador
        },function(data){
            $("#content_pendientes").html(data);

        });
    }
</script>