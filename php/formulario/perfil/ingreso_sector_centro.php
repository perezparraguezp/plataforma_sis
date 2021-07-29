
<?php
include '../../config.php';
$id_establecimiento = $_SESSION['id_establecimiento'];

?>
<div class=" card-panel">
    <form class="col l12" id="form_newPersonal">
        <div class="row">
            <h4 class="header">Asignar un Sector para el Centro Interno</h4>
            <p class="left">
                Este formulario permite que el administrador registre el o los sectores relacionados con los
                centros de salud internos correspondientes al establecimiento.
            </p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>Tipo de Contrato </label>
            <select name="id_centro_interno" id="id_centro_interno">
                <?php

                $sql = "select * from centros_internos  
                        where id_establecimiento='$id_establecimiento' 
                        order by nombre_centro_interno";
                //echo $sql;
                $res = mysql_query($sql);
                while ($row = mysql_fetch_array($res)){
                    ?>
                    <option value="<?php echo $row['id_centro_interno'];?>"><?php echo $row['nombre_centro_interno']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>NOMBRE DEL SECTOR</label>
            <input type="text" name="nombre_sector" id="nombre_sector" />
        </div>
        <p>PARA INGRESAR MAS DE UN SECTOR, DIGITE LOS NOMBRES SEPARADOS POR PUNTO Y COMA.</p>
        <hr />
        <div class="row">
            <div class="input-field col s12">
                <a href="#!" onclick="insertSectorCentroInterno()" class="btn waves-effect waves-light  col s12"> REGISTRAR SECTOR</a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">

    function insertSectorCentroInterno() {
        $.post('php/db/insert/sector_centro_interno.php',$("#form_newPersonal").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadGrid_Centros();
                    var texto = 'SE HAN REGISTRADO EL/LOS SECTORES INGRESADOS';
                    alertaLateral(texto);
                }
            });
    }

</script>