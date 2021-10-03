


<div id="content-pacientes" class="content">
</div>
<script type="text/javascript">
    $(function () {
        load_lista_ad();
    });
    function load_lista_ad() {
        var div = 'content-pacientes';

        $.post('grid/pacientes.php',{
        },function(data){
            $("#"+div).html(data);
        });
    }
    function load_form_nuevo_ad() {
        var div = 'content-pacientes';
        loading_div(div);
        $.post('formulario/paciente.php',{
        },function(data){
            $("#"+div).html(data);
        });
    }
    function importar_pacientes_pscv() {
        var div = 'content-pacientes';
        loading_div(div);
        $.post('iframe/import_paciente.php',{
        },function(data){
            $("#"+div).html(data);
        });
    }
</script>
