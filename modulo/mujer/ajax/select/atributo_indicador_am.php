<label>ATRIBUTO</label>
<select name="atributo"
        id="atributo">
    <option selected disabled value="">SELECCIONAR ATRIBUTO</option>
    <?php
    $indicador = $_POST['indicador'];//parametro de la table
    $table_sql = $_POST['table_sql'];//tabla
    $tiene_estado = true;
    switch ($indicador){
        case 'FUNCIONALIDAD':{
            ?>
            <option >AUTOVALENTE SIN RIESGO</option>
            <option >AUTOVALENTE CON RIESGO</option>
            <option >RIESGO DEPENDENCIA</option>
            <option >DEPENDENCIA LEVE</option>
            <option >DEPENDENCIA MODERADO</option>
            <option >DEPENDENCIA GRAVE</option>
            <option >DEPENDENCIA TOTAL</option>
            <?php
            break;
        }
        case 'ACTIVIDAD FISICA':{
            ?>
            <option>SI</option>
            <option>NO</option>
            <?php
            break;
        }
        case 'ESTADO NUTRICIONAL':{
            ?>
            <option value="BP">BAJO PESO</option>
            <option value="N">NORMAL</option>
            <option value="SP">SOBREPESO</option>
            <option value="OB">OBESIDAD</option>
            <?php
            break;
        }
        case 'SOSPECHA MALTRATO':{
            ?>
            <option>SI</option>
            <option>NO</option>
            <?php
            break;
        }
        case '+ ADULTO MAYOR':{
            ?>
            <option >AUTOVALENTE SIN RIESGO</option>
            <option >AUTOVALENTE CON RIESGO</option>
            <option >RIESGO DEPENDENCIA</option>
            <?php
            break;
        }
        case 'RIESGO CAIDA : TIMED UP AND GO':{
            ?>
            <option>NORMAL</option>
            <option>LEVE</option>
            <option>ALTO</option>
            <?php
            break;
        }

        case 'RIESGO CAIDA : ESTACION UNIPODAL':{
            ?>
            <option>NORMAL</option>
            <option>ALTERADO</option>
            <?php
            break;
        }

    }
    ?>
</select>
<script type="text/javascript">
    $(function(){
        $('#atributo').jqxDropDownList({
            width: '98%',
            theme: 'eh-open',
            height: '25px'
        });
    });
</script>