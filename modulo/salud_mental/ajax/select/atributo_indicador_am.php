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
        case 'REGISTRO DE ACTIVIDADES':{
            $sql = "select * from activiad_sm order by nombre_actividas";
            $res = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($res)){
                ?>
                <option><?php echo $row['nombre_actividas']; ?></option>
                <?php
                $i++;
            }
            break;
        }
        case 'REGISTROS DE ANTECEDENTES':{
            $sql = "select * from antecedentes_sm order by nombre_antecedente";
            $res = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($res)){
                ?>
                <option><?php echo $row['nombre_antecedente']; ?></option>
                <?php
                $i++;
            }
            break;
        }
        case 'REGISTRO DE DIAGNOSTICOS':{
            $sql = "select * from tipo_diagnostico_sm order by nombre_tipo";
            $res = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($res)){
                ?>
                <option><?php echo $row['nombre_tipo']; ?></option>
                <?php
                $i++;
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