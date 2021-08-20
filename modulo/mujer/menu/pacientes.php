<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 5/29/20
 * Time: 11:45 AM
 */

?>
<div id="content-pacientes" class="content">

</div>
<script type="text/javascript">
    $(function () {
        load_lista_am();
    });
    function load_lista_am() {
        var div = 'content-pacientes';
        loading_div(div);
        $.post('grid/pacientes.php',{
        },function(data){
            $("#"+div).html(data);
        });
    }
    function load_form_nuevo_am() {
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
