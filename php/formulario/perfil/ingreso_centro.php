<!--
formulario ingreso Centro

permite que el administrador ingrese nuevos centros de saluds

variables
tipo_atributo
valor

-->
<?php
include '../../config.php';
include '../../objetos/tipo_agrupacion.php';

?>
<div class=" card-panel">
    <form class="col l12" id="form_updateAtributo">
        <div class="row">
            <h4 class="header">Registrar Nuevo Centro Interno</h4>
            <p class="left">Ingrese los Datos Solicitados.</p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>NOMBRE DEL CENTRO</label>
            <input type="text" name="nombre_centro" id="nombre_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>DIRECCIÓN DEL CENTRO</label>
            <input type="text" name="direccion_centro" id="direccion_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>TELÉFONO DEL CENTRO</label>
            <input type="text" name="telefono_centro" id="telefono_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>EMAIL DEL CENTRO</label>
            <input type="text" name="email_centro" id="email_centro" />
        </div>
        <div class="row">
            <div class="input-field col s12">
                <a href="#!" onclick="insertCentroInterno()"
                   class="btn waves-effect waves-light  col s12"> REGISTRAR CENTRO INTERNO</a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">
    function insertCentroInterno() {
        var nombre = $("#nombre_centro").val();
        var direccion = $("#direccion_centro").val();
        var telefono = $("#telefono_centro").val();
        var email = $("#email_centro").val();
        if(nombre!==''){
            if(direccion!==''){
                if(telefono!==''){
                    if(email!==''){
                        $.post('php/db/insert/centro_interno.php',$("#form_updateAtributo").serialize(),
                            function (data) {
                                if(data !== 'ERROR_SQL'){
                                    var texto = 'EXITO: CENTRO DE SALUD REGISTRADO SIN PROBLEMAS';
                                    alertaLateral(texto);
                                    loadGrid_Centros();
                                }else{
                                    var texto = 'ERROR: SE HA PRODUCIDO UN ERROR, VUELVA A INTENTARLO';
                                    alertaLateral(texto);
                                }
                            });
                    }else{
                        //error mail

                        var texto = 'ERROR: DEBE REGISTRAR UN CORREO ELECTRONICO  PARA EL CENTRO INTERNO';
                        alertaLateral(texto);
                        focusInput('email_centro');
                    }
                }else {
                    //error telefono
                    var texto = 'ERROR: DEBE REGISTRAR UN NUMERO TELEFONICO PARA EL CENTRO INTERNO';
                    alertaLateral(texto);
                    focusInput('telefono_centro');
                }
            }else{
                //error direcion
                var texto = 'ERROR: DEBE INGRESAR LA DIRECCION PARA EL CENTRO INTERNO';
                alertaLateral(texto);
                focusInput('direccion_centro');
            }
        }else{
            //error nombre
            var texto = 'ERROR: DEBE INGRESAR EL NOMBRE PARA EL CENTRO INTERNO';
            alertaLateral(texto);
            focusInput('nombre_centro');
        }

    }



</script>