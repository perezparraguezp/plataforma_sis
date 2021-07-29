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
            <div class="col l8">NOMBRE ESTAMENTO: <strong><?php echo $establecimiento->nombre; ?></strong></div>
            <div class="col l8">SECTOR COMUNAL: <strong><?php echo $establecimiento->getNombreSectorComunal($id_centro_interno); ?></strong></div>
            <div class="col l8">NOMBRE ESTABLECIMIENTO: <strong><?php echo $establecimiento->getNombreCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l6">TELEFONO ESTABLECIMIENTO<strong><?php echo $establecimiento->getTelefonoCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l6">E-MAIL ESTABLECIMIENTO<strong><?php echo $establecimiento->getEmailCentroInterno($id_centro_interno); ?></strong></div>
            <div class="col l8">DIRECCIÓN ESTABLECIMIENTO: <strong><?php echo $establecimiento->getDireccionCentroInterno($id_centro_interno); ?></strong></div>
        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            <script type="text/javascript">
                $(document).ready(function () {
                    // Create jqxTabs.
                    $('#tabs').jqxTabs({ width: '100%', height: 450, position: 'top'});
                    $("#nombre_sector").jqxInput({placeHolder: "INGRESE UN NOMBRE PARA EL SECTOR", height: 30, minLength: 1});

                });
            </script>
            <div id='tabs'>
                <ul>
                    <li>SECTORES DEL ESTABLECIMIENTO</li>
                    <li>EDITAR ESTABLECIMIENTO</li>
                </ul>
                <div>
                    <!-- SECTORES DEL ESTABLECIMIENTO-->
                    <div class="col l12">
                        <div class="row">
                            <div class="col l12">
                                <p>EN ESTA PESTAÑA EL USUARIO PODRA CREAR Y ADMINISTRAR LOS SECTORES INTERNOS QUE POSEA EL ESTABLECIMIENTO</p>
                            </div>
                        </div>
                        <hr class="row" />
                        <div class="row">
                            <div class="col l4">
                                <form id="form_SectorCentroInterno" class="col l12" style="background-color: #d7efff;border: solid 1px black">
                                    <input type="hidden" id="id_centro_interno" name="id_centro_interno" value="<?php echo $id_centro_interno; ?>" />

                                    <header>FORMULARIO DE REGISTRO</header>
                                    <div class="row">
                                        <label for="nombre_sector">NOMBRE SECTOR</label>
                                        <input type="text" name="nombre_sector" id="nombre_sector" style="background-color: white;" />
                                    </div>
                                    <div class="row">
                                        <input type="button"
                                               onclick="insertSectorInterno()"
                                               class="btn green accent-4 white-text" value="AGREGAR SECTOR" />
                                    </div>
                                </form>
                            </div>
                            <div class="col l8">
                                <div id="grid_sectores"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- EDITAR ESTABLECIMIENTO -->
                    <form name="form_edit_establecimiento" id="form_edit_establecimiento" class="col l12">
                        <input type="hidden" name="id_centro_interno" id="id_centro_interno" value="<?php echo $id_centro_interno; ?>" />
                        <div class="col l5" style="background-color: #d7efff;border: solid 1px black">
                            <header style="font-weight: bold;font-size: 1em;">DATOS DEL ESTABLECIMIENTO</header>
                            <div class="row">
                                <div class="col l12">
                                    <label for="sector_comunal">SECTOR COMUNAL</label>
                                    <select name="sector_comunal" id="sector_comunal">

                                        <?php
                                        $sql = "select * from sector_comunal order by nombre_sector_comunal";
                                        $res = mysql_query($sql);
                                        while ($row = mysql_fetch_array($res)){
                                            if($establecimiento->getIdSectorComunal($id_centro_interno)==$row['id_sector_comunal']){
                                                ?>
                                                <option selected="selected" value="<?php echo $row['id_sector_comunal']; ?>"><?php echo strtoupper($row['nombre_sector_comunal']) ?></option>
                                                <?php
                                            }else{
                                                ?>
                                                <option value="<?php echo $row['id_sector_comunal']; ?>"><?php echo strtoupper($row['nombre_sector_comunal']) ?></option>
                                                <?php
                                            }

                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col l12">
                                    <label for="nombre">NOMBRE ESTABLECIMIENTO</label>
                                    <input type="text" name="nombre" id="nombre" value="<?php echo $establecimiento->getNombreCentroInterno($id_centro_interno); ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col l12">
                                    <label for="direccion">DIRECCION ESTABLECIMIENTO</label>
                                    <input type="text" name="direccion" id="direccion" value="<?php echo $establecimiento->getDireccionCentroInterno($id_centro_interno); ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col l12">
                                    <label for="telefono">TELEFONO ESTABLECIMIENTO</label>
                                    <input type="text" name="telefono" id="telefono" value="<?php echo $establecimiento->getTelefonoCentroInterno($id_centro_interno); ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col l12">
                                    <label for="email">E-AMIL ESTABLECIMIENTO</label>
                                    <input type="text" name="email" id="email" value="<?php echo $establecimiento->getEmailCentroInterno($id_centro_interno); ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col l12">
                                    <input type="button"
                                           onclick="updateDatosEstablecimiento()"
                                           value="ACTUALIZAR DATOS" class="btn green darken-3 white-text col l12" />
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                $('#sector_comunal').jqxDropDownList({
                                    width: '100%',
                                    height: '25px'
                                });
                                $("#nombre").jqxInput({height: 25, width: '100%'});
                                $("#direccion").jqxInput({height: 25, width: '100%'});
                                $("#email").jqxInput({height: 25, width: '100%'});
                                $("#telefono").jqxInput({height: 25, width: '100%'});
                            })
                        </script>
                    </form>
                    <style type="text/css">
                        #form_edit_establecimiento input{
                            background-color: white;;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    loadListaSectoresInternos();

    function updateDatosEstablecimiento() {
        if(confirm('DESEA ACTUALIZAR ESTA INFORMACIÓN')){
            $.post('php/db/update/establecimiento.php',
                $("#form_edit_establecimiento").serialize()
                ,function (data) {
                    alertaLateral(data);
                    loadListaSectoresInternos();
                });
        }
    }

    function deleteSectorInterno(id) {
        if(confirm('DESEA ELIMINAR ESTE SECTOR')){
            $.post('php/db/delete/sector_centro_interno.php',
                {id:id}
                ,function (data) {
                    alertaLateral(data);
                    loadListaSectoresInternos();
                });
        }
    }

    function insertSectorInterno(){
        $.post('php/db/insert/sector_centro_interno.php',
        $("#form_SectorCentroInterno").serialize(),function (data) {
                alertaLateral(data);
                loadListaSectoresInternos();
            });
    }
    function loadListaSectoresInternos() {
        var source =
            {
                url: 'php/json/config/lista_sectores_internos.php?id_centro_interno=<?php echo $id_centro_interno; ?>',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'codigo', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'borrar', type: 'string'},
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);

        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<a ' +
                'onclick="deleteSectorInterno(\''+value+'\')" ' +
                'class="btn  red darken-4 white-text" style="margin-left: 10px;">BORRAR</a>';
        }
        $("#grid_sectores").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                filtermode: 'excel',
                autoshowfiltericon: false,
                showfilterrow: true,
                rowsheight: 40,
                pagesize: 20,
                columns: [
                    { text: 'CODIGO', datafield: 'codigo', width: 100 ,cellsalign: 'right'},
                    { text: 'NOMBRE SECTOR', datafield: 'nombre' },
                    { text: ' ', datafield: 'borrar', width: 120 , cellsalign: 'center',cellsrenderer: cell_borrar,filterable:false},


                ]
            });
    }
</script>
