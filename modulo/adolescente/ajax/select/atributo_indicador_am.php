<label>ATRIBUTO</label>
<select name="atributo"
        id="atributo">
    <option selected disabled value="">SELECCIONAR ATRIBUTO</option>
    <?php
    include "../../../../php/config.php";
    $indicador = $_POST['indicador'];//parametro de la table
    $table_sql = $_POST['table_sql'];//tabla
    $tiene_estado = true;
    switch ($indicador){
        case 'IMC':{
            ?>
            <option value="DN2#imc">DESNUTRICION SECUNDARIA</option>
            <option value="DN#imc">DESNUTRICION</option>
            <option value="BP#imc">BAJO PESO</option>
            <option value="N#imc">NORMAL</option>
            <option value="SP#imc">SOBREPESO</option>
            <option value="OB#imc">OBESIDAD</option>
            <option value="OBM#imc">OBESIDAD MORBIDA</option>
            <?php
            break;
        }
        case 'PERIMETRO CINTURA':{
            ?>
            <option VALUE="NORMAL#cintura">NORMAL</option>
            <option VALUE="RIOB#cintura">RIESGO OBESIDAD</option>
            <option VALUE="OBAD#cintura">OBESIDAD ABDOMINAL</option>
            <?php
            break;
        }
        case 'TALLA EDAD':{
            ?>
            <option value="BAJA#talla_edad">BAJA</option>
            <option value="NBAJA#talla_edad">NORMAL BAJA</option>
            <option value="NORMAL#talla_edad">NORMAL</option>
            <option value="NALTA#talla_edad">NORMAL ALTA</option>
            <option value="ALTA#talla_edad">ALTA</option>
            <?php
            break;
        }
        case 'EDUCACION':{
            ?>
            <option value="ESTUDIANTE#educacion">ESTUDIANTE</option>
            <option value="DESERCION#educacion">DESERCIÓN ESCOLAR</option>
            <option value="TRABAJO_INFANTIL#educacion">TRABAJO INFANTIL</option>
            <option value="TRABAJO_JUVENIL#educacion">TRABAJO JUVENIL</option>
            <option value="PEOR#educacion">PEORES FORMA DE TRABAJO INFANTIL</option>
            <option value="SERVICIO#educacion">SERVICIO DOMESTICO NO REMUNERADO PELIGROSO</option>
            <?php
            break;
        }
        case 'AREA RIESGO':{
            $sql = "select * from tipo_riesgo_adolescente 
                                where tipo_area='RIESGO' 
                                ORDER BY nombre_riesgo;";
            $res = mysql_query($sql);
            while($row = mysql_fetch_array($res)){
                ?>
                <option value="<?php echo $row['id_tipo_riesgo']; ?>#estado_riesgo"><?php echo $row['nombre_riesgo']; ?></option>
                <?php
            }

            break;
        }
        case 'GINECO URULOGIA':{
            $sql = "select * from tipo_riesgo_adolescente 
                        where tipo_area='GINE' ORDER BY nombre_riesgo;";
            $res = mysql_query($sql);
            while($row = mysql_fetch_array($res)){
                $tipo_gine = str_replace("ADOLESCENTE CON ","",$row['nombre_riesgo']);
                $tipo_gine = str_replace("ADOLESCENTE QUE ","",$tipo_gine);
                ?>
                <option value="<?php echo $row['id_tipo_riesgo']; ?>#estado_riesgo"><?php echo $tipo_gine; ?></option>
                <?php
            }

            break;
        }
        case 'CONCEJERIAS':{
            $sql = "select * from tipo_consejerias_adolescente  ORDER BY nombre_consejeria;";
            $res = mysql_query($sql);
            while($row = mysql_fetch_array($res)){
                ?>
                <option value="<?php echo $row['id_tipo_consejeria']; ?>#nombre_consejeria"><?php echo $row['nombre_consejeria']; ?></option>
                <?php
            }

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