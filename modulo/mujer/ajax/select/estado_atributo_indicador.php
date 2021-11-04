<label>SELECCIONE ESTADO</label>
<select name="estado" id="estado">
    <?php
    $indicador = $_POST['indicador'];//parametro de la table
    $table_sql = $_POST['table_sql'];//tabla
    $atributo = $_POST['atributo'];//tabla
    $tiene_estado = true;
    switch ($atributo){
        case 'RIESGO BIOPSICOSOCIAL':{
            ?>
            <option>SIN RIESGO</option>
            <option>CON RIESGO BIOPSICOSOCIAL</option>
            <option>PRESENTA VIOLENCIA DE GENERO</option>
            <option>PRESENTA ARO (alto riesgo obstétrico)</option>
            <?php
            break;
        }
        case 'IMC GESTACIONAL':{
            ?>
            <option>OBESA</option>
            <option>SOBREPESO</option>
            <option>NORMAL</option>
            <option>BAJO PESO</option>
            <?php
            break;
        }
    }
    ?>
</select>

<script type="text/javascript">
    $(function(){
        $('#estado').jqxDropDownList({
            width: '98%',
            theme: 'eh-open',
            height: '25px'
        });
    });
</script>