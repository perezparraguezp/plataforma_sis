
<div class="content container" id="div_form_paciente">

</div>
<script type="text/javascript">
    $(function(){
        loadForm_gridPacientes();
    });
    function loadForm_newPaciente(){
        $.post('formulario/nuevo_paciente.php',
            {},function (data){
                $("#div_form_paciente").html(data);
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
    }
    function loadForm_gridPacientes(){
        $.post('grid/pacientes.php',
            {},function (data){
                $("#div_form_paciente").html(data);
            });
    }
    function loadForm_ImportPacientes(){
        $.post('iframe/import_paciente.php',
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