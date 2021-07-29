<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 3/10/20
 * Time: 8:50 PM
 */
?>
<span class="clearfix"></span>

<style type="text/css">
    #form_paciente .row{
        margin-top: 10px;
    }
</style>
<div class="row">
    <div class="col l12">
        <div class="col l4">
            <a class="btn waves-effect waves-light col s12" onclick="loadForm_newPaciente()"><i class="mdi-social-person"></i>REGISTRAR NUEVO PACIENTE</a>
        </div>
        <div class="col l4">
            <a class="btn waves-effect waves-light col s12" onclick="loadForm_gridPacientes()"><i class="mdi-action-account-child"></i> VER LISTADO DE PACIENTES</a>
        </div>
        <div class="col l4">
            <a class="btn light-green lighten-2 col s12" onclick="loadForm_ImportPacientes()"><i class="mdi-communication-import-export"></i> IMPORTAR PACIENTES</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col l12" id="div_form_paciente">
    </div>
</div>
<script type="text/javascript">
    $(function(){
        loadForm_gridPacientes();

        $('#rut_paciente').Rut({
            on_error: function() {
                $(this).focus();
                //alert('Rut incorrecto');
                alertaLateral('RUT INCORRECTO');
                $('#rut_paciente').val('');
                $(this).css({
                    "border": "solid red 1px"
                });
            }
        });

    });
    function loadForm_newPaciente(){
        $.post('formulario/nuevo_paciente.php',
            {},function (data){
                $("#div_form_paciente").html(data);
            });
    }
    function loadForm_gridPacientes(){
        $.post('php/modulo/registro_paciente/grid_pacientes.php',
            {},function (data){
                $("#div_form_paciente").html(data);
            });
    }
    function loadForm_ImportPacientes(){
        $.post('php/modulo/registro_paciente/import_paciente.php',
            {},function (data){
                $("#div_form_paciente").html(data);
            });
    }

    function validar_Rut_Paciente(){
        $('#rut_paciente').Rut({
            on_error: function() {
                $(this).focus();
                //alert('Rut incorrecto');
                alertaLateral('RUT INCORRECTO');
                $('#rut_paciente').val('');
                $(this).css({
                    "border": "solid red 1px"
                });
            }
        });
    }
</script>
