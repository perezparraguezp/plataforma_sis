<!--
formulario ingreso atributo

permite que el establecimiento ingrese los atributos al sistema

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
            <h4 class="header">Actualizar Datos del Establecimiento  </h4>
            <p class="left">Cada establecimiento debe actualizar la información para poder tener estadisticas.</p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>Tipo de Atributo </label>
            <select name="tipo_atributo" id="tipo_atributo">
                <option value="0">Seleccione Atributo a Modificar</option>
                <?php
                $sql1 = "select * from atributo_establecimiento order by nombre_atributo";
                $res = mysql_query($sql1);
                while($row = mysql_fetch_array($res)){
                    ?>
                    <option value="<?php echo $row['id_atributo']; ?>"><?php echo $row['nombre_atributo'];  ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="row" id="data_form">

        </div>

        <div class="row">
            <div class="input-field col s12">
                <a href="#!" onclick="updateAtributoEstablecimiento()" class="btn waves-effect waves-light  col s12"> Actualizar Atributo</a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">
    function updateAtributoEstablecimiento() {
        $.post('php/db/update/atributo_establecimiento.php',$("#form_updateAtributo").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadForm_newAtributo();
                }
            });
    }

</script>