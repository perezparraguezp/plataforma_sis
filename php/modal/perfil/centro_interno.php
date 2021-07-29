<?php
include "../../config.php";
include "../../objetos/persona.php";
include "../../objetos/establecimiento.php";

session_start();
$id_centro_interno = $_POST['id'];


$establecimiento = new establecimiento($_SESSION['id_establecimiento']);



?>
<div class="modal-content">
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="row col l12" style="margin-bottom: 0px;">
            <div class="col l8">NOMBRE ESTABLECIMIENTO: <strong><?php echo $establecimiento->nombre; ?></strong></div>
            <div class="col l8">NOMBRE CENTRO INTERNO: <strong><?php echo $establecimiento->getNombreCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l6">TELEFONO <strong><?php echo $establecimiento->getTelefonoCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l6">E-MAIL <strong><?php echo $establecimiento->getEmailCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l8">DIRECCIÓN CENTRO INTERNO: <strong><?php echo $establecimiento->getDireccionCentroInterno($id_centro_interno); ?></strong></div>
        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            <script type="text/javascript">
                $(document).ready(function () {
                    // Create jqxTabs.
                    $('#tabs').jqxTabs({ width: '100%', height: 250, position: 'top'});
                    $('#sector_centro').jqxDropDownList({
                        filterable: true,
                        filterPlaceHolder: "Buscar Sector",
                        width: '100%',
                        height: '25px'
                    });
                    $('#sector_centro').on('change',function(){
                       $.post('php/formulario/perfil/lista_profesionales_centro_interno.php',{
                           sector:$("#sector_centro").val(),
                           id_centro_interno:'<?php echo $id_centro_interno ?>'
                       },function(data){
                           $("#formListaProfesionales").html(data);
                       });
                    });
                });
            </script>
            <div id='tabs'>
                <ul>
                    <li style="margin-left: 30px;">PROFESIONALES DEL CENTRO</li>
                    <li>ESTADISTICA LOCAL DEL CENTRO</li>
                    <li>SECTORES DEL CENTRO</li>
                </ul>
                <div>
                    <!-- PROFESIONALES-->
                    <div class="col l12">
                        <p>En esta sección el usuario podrá indicar cuales seran los profeionales que pueden realizar registros en el centro de salud interno</p>
                        <div class="row">
                            <div class="col l4">
                                <div class="row">
                                    <div class="col l4">SECTOR</div>
                                    <div class="col l8">
                                        <select name="sector_centro" id="sector_centro">
                                            <option disabled selected>SELECCIONAR SECTOR</option>
                                            <?php
                                            $sql1 = "select * from sectores_centros_internos where id_centro_interno='$id_centro_interno' order by nombre_sector_interno";
                                            $res1 = mysql_query($sql1);
                                            while($row1 = mysql_fetch_array($res1)){
                                                ?>
                                                <option value="<?php echo $row1['id_sector_centro_interno']; ?>"><?php echo $row1['nombre_sector_interno']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <p>DEBERA INDICAR EL SECTOR CORRESPONDIENTE PARA LA ASIGNACION DE LOS PROFESIONALES.</p>
                                </div>
                            </div>
                            <div class="col l8">
                                <div class="row col l12">
                                    LISTA DE PROFESIONALES
                                </div>
                                <div class="row col l12" id="formListaProfesionales">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <div class="row">

    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
