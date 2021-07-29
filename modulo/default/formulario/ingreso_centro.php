<!--
formulario ingreso Centro

permite que el administrador ingrese nuevos centros de saluds

variables
tipo_atributo
valor

-->
<?php


include '../../../php/config.php';
include '../../../php/objetos/tipo_agrupacion.php';
?>
<div class=" card-panel">
    <form class="col l12" id="form_updateAtributo">
        <div class="row">
            <h4 class="header">Registrar Nuevo Establecimiento</h4>
            <p class="left">Ingrese los Datos Solicitados.</p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>SECTOR DEL ESTABLECIMIENTO</label>
            <select id="sector_comunal" name="sector_comunal">
                <?php
                $sql = "select * from sector_comunal order by nombre_sector_comunal";
                $res = mysql_query($sql);
                while ($row = mysql_fetch_array($res)){
                    ?>
                    <option value="<?php echo $row['id_sector_comunal']; ?>"><?php echo strtoupper($row['nombre_sector_comunal']) ?></option>
                    <?php
                }
                ?>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#sector_comunal').jqxDropDownList({
                        width: '100%',
                        height: '25px'
                    });
                })
            </script>
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>NOMBRE DEL ESTABLECIMIENTO</label>
            <input type="text" name="nombre_centro" id="nombre_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>DIRECCIÓN DEL ESTABLECIMIENTO</label>
            <input type="text" name="direccion_centro" id="direccion_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>TELÉFONO DEL ESTABLECIMIENTO</label>
            <input type="text" name="telefono_centro" id="telefono_centro" />
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>EMAIL DEL ESTABLECIMIENTO</label>
            <input type="text" name="email_centro" id="email_centro" />
        </div>
        <div class="row">
            <div class="col l6">
                <a href="#!" onclick="loadGrid_Centros()"
                   class="btn red darken-4 col s12"> CANCELAR REGISTRO</a>
            </div>
            <div class="col l6">
                <a href="#!" onclick="insertCentroInterno()"
                   class="btn waves-effect waves-light  col s12"> REGISTRAR ESTABLECIMIENTO</a>
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
                        $.post('db/insert/centro_interno.php',$("#form_updateAtributo").serialize(),
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