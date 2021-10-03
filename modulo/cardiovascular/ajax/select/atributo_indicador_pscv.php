<label>ATRIBUTO</label>
<select name="atributo"
        id="atributo">
    <option selected disabled value="">SELECCIONAR ATRIBUTO</option>
    <?php
    $indicador = $_POST['indicador'];
    $table_sql = $_POST['table_sql'];
    $tiene_estado = true;
    switch ($indicador){
        case 'PATOLOGIAS':{
            ?>
            <option value="patologia_hta">HTA</option>
            <option value="patologia_dm">DM</option>
            <option value="patologia_dlp">DLP</option>
            <?php
            break;
        }
        case 'FACTOR DE RIESGO':{
            ?>
            <option value="riesgo_cv">CARDIOVASCULAR</option>
            <option value="factor_riesgo_tabaquismo">TABAQUISMO</option>
            <option value="factor_riesgo_iam">IAM</option>
            <option value="factor_riesgo_enf_cv">ENF. CARDIOVASCULAR</option>
            <?php
            break;
        }
        case 'TRATAMIENTO':{
            ?>
            <option value="tratamiento_aas">AAS</option>
            <option value="tratamiento_ieeca">IEECA</option>
            <option value="tratamiento_estatina">ESTATINA</option>
            <option value="tratamiento_araii">ARA II</option>
            <?php
            break;
        }
        case 'PACIENTES DEPENDIENTES':{
            ?>
            <option value="postrado">POSTRADOS</option>
            <option value="hemodialisis">HEMODIALISIS</option>
            <?php
            break;
        }
        case 'FONDO DE OJOS':{
            ?>
            <option value="TODO">EXAMENES AL DIA</option>
            <option value="CON RETINOPATIA">% CON RETINOPATIA</option>
            <option value="SIN RETINOPATIA">% SIN RETINOPATIA</option>
            <?php
            $tiene_estado = false;
            break;
        }
        case 'ELECTROCARDIOGRAMA':{
            ?>
            <option value="VIGENTE">% VIGENTE</option>

            <?php
            $tiene_estado = false;
            break;
        }
        case 'VIGENCIAS':{
            ?>
            <option value="ekg|historial_parametros_pscv|parametros_pscv">ELECTROCARDIOGRAMA</option>
            <option value="rac|historial_parametros_pscv|parametros_pscv">RAC</option>
            <option value="erc_vfg|historial_parametros_pscv|parametros_pscv">VFG</option>
            <option value="ldl|historial_parametros_pscv|parametros_pscv">LDL</option>
<!--            <option value="glicemia|historial_parametros_pscv|parametros_pscv">GLICEMIA</option>-->
            <option value="imc|historial_parametros_pscv|parametros_pscv">IMC</option>
            <option value="pa|historial_parametros_pscv|parametros_pscv">PRESION ARTERIAL</option>
            <option value="ev_pie|historial_diabetes_mellitus|pscv_diabetes_mellitus">EV PIE</option>
            <option value="fondo_ojo|historial_diabetes_mellitus|pscv_diabetes_mellitus">FONDO DE OJO</option>
            <option value="hba1c|historial_diabetes_mellitus|pscv_diabetes_mellitus">hba1c</option>
            <option value="podologia|historial_diabetes_mellitus|pscv_diabetes_mellitus">PODOLOGIA</option>

            <?php
            $tiene_estado = false;
            break;
        }
        case 'COMPENSACIÓN':{
            ?>
            <option value="patologia_hta|paciente_pscv|historial_pscv">HTA</option>
            <option value="patologia_dm|paciente_pscv|historial_pscv">DM</option>
<!--            <option value="insulina|pscv_diabetes_mellitus|historial_diabetes_mellitus">INSULINA</option>-->

            <?php
            $tiene_estado = true;
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
        <?php
            if($tiene_estado==true){
                ?>
                $('#atributo').on('change',function () {
                    var atributo = $("#atributo").val();
                    var indicador = $("#indicador").val();
                    $.post('ajax/select/estado_atributo_indicador.php', {
                        indicador:indicador,
                        table_sql:'<?php echo $table_sql; ?>',
                        atributo:atributo,
                    }, function (data) {
                        $("#estado_indicador_div").html(data);

                    });
                });
                <?php
            }
        ?>

    });
</script>