<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 2/24/20
 * Time: 3:30 PM
 */
?>

<div class="col l12 m12 s12">
    <div id='tabs_formulario_registro'
         style="font-size: 0.8em;">
        <ul>
            <li style="margin-left: 30px;text-align: center">REGISTRO POR RUT</li>
            <li style="margin-left: 30px;" onclick="loadForm_offline()">REGISTRO MEDIANTE IMPORTACIÓN</li>
        </ul>
        <div style="padding-left: 10px;">
            <!-- REGISTRO POR RUT -->
            <div id="form_busqueda">
                <div class="row">
                    <div class="col l12">
                        <p>Ingrese el RUT del paciente para comenzar con el registro.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 right" style="margin-right: 10px;">
                        <input type="button"
                               id="button_buscar"
                               onclick="loadForm_RegistroFichaLocal_1()"
                               value="REALIZAR BUSQUEDA" class="btn-large light-blue darken-4 white-text" style="width: 100%;" />
                    </div>
                    <div class="col l2 right" style="margin-right: 10px;">
                        <input type="date"
                               style="font-size: 0.9em"
                               value="<?php echo date('Y-m-d'); ?>"
                               id="fecha_registro" name="fecha_registro"  />
                    </div>
                    <div class="col l2 right" style="margin-right: 10px;line-height: 30px;text-align: right;">
                        FECHA REGISTRO
                    </div>
                    <div class="col l2 right" style="margin-right: 10px;">
                        <input id="rut_paciente"
                               name="rut_paciente"
                               onchange="validar_Rut()"
                               type="text" required placeholder="Ej. 11222333-4">
                    </div>
                    <div class="col l2 right" style="margin-right: 10px;line-height: 30px;text-align: right;">
                        RUT PACIENTE
                    </div>
                </div>
                <div class="row">
                    <div id="div_listado_pacientes_dsm">sd</div>
                </div>
            </div>


            <div id="form_ficha">
                <div class="row">
                    <div class="col l12" id="registroFichaLocal">

                    </div>
                </div>
            </div>
        </div>
        <div id="div_registro_importacion">
            <!-- REGISTRO POR IMPORTACION -->
        </div>
    </div>
</div>

<script type="text/javascript">
    function validar_Rut(){
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
        $("#rut_paciente").jqxInput({
            placeHolder: "11222333-4", height: 30 });
        $("#fecha_registro").jqxInput({
            placeHolder: "dd/mm/YYYY", height: 30 });
        $("#button_buscar").jqxButton({ height: 30 });
    }

    function loadForm_RegistroFichaLocal_1() {

        var rut = $("#rut_paciente").val();
        if(rut !== ''){
            $.post('php/formulario/registro_ficha/registro_ficha.php',{
                rut: rut,
                fecha_registro:$("#fecha_registro").val()
            },function(data){
                $("#form_busqueda").hide();
                $("#registroFichaLocal").html(data);
                $("#form_ficha").show();
            });
        }else{
            alertaLateral('DEBE INGRESAR UN RUT PARA REALIZAR LA BUSQUEDA');
        }
    }
    function volverFichaSearch_1(){
        $("#form_ficha").hide();
        $("#form_busqueda").show();
    }
    function loadForm_offline(){
        $.post('php/formulario/registro_ficha/registro_offline.php',{

        },function(data){
            $("#div_registro_importacion").html(data);
        });
    }

    function loadForm_gridPacientes(){
        $.post('php/formulario/registro_ficha/grid_pacientes.php',
            {},function (data){
                $("#div_listado_pacientes_dsm").html(data);
            });
    }


</script>