<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 5/29/20
 * Time: 11:45 AM
 */

?>

<div class="row">
    <div class="col l12">
        <div class="col l4">
        </div>
        <div class="col l4">
            <a class="btn waves-effect waves-light col s12" onclick="load_lista_am()"><i class="mdi-action-account-child"></i> VER LISTADO DE PACIENTES</a>
        </div>
        <div class="col l4">
<!--            <a class="btn light-green lighten-2 col s12" onclick="importar_pacientes_pscv()"><i class="mdi-communication-import-export"></i> IMPORTAR PACIENTES</a>-->
        </div>
    </div>
</div>
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
