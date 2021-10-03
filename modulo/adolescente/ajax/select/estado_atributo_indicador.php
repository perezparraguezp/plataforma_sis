<label>ESTADO</label>
<select name="estado"
        id="estado">
    <?php
    include "../../../../php/config.php";
    $indicador = $_POST['indicador'];
    $atributo  = $_POST['atributo'];
    $table_sql = $_POST['table_sql'];

    if($atributo=='fondo_ojo'){
        $sql = "select * from $table_sql where indicador='$atributo'  group by indicador order by indicador desc ";

        $atributo = 'valor';
    }else{
        if($atributo=='patologia_hta' || $atributo=='patologia_dm'){
            $sql = "select * from $table_sql where $atributo!=''  group by $atributo order by $atributo desc ";
        }else{
            if($atributo=='insulina'){

            }else{
                $sql = "select * from $table_sql where $atributo!=''  group by $atributo order by $atributo desc ";
            }
        }
    }
    if($indicador=='compensacion'){
        list($atributo,$table_sql,$historial) = explode('|',$_POST['atributo']);

        if($atributo=='patologia_dm'){
            $sql = "select * from pscv_diabetes_mellitus where hba1c!= '' group by hba1c";
            $atributo = 'hba1c';
            $sql = '';
        }else{
            $sql = "select * from parametros_pscv where pa!= '' group by pa";
            $atributo = 'pa';
            $sql = '';
        }


    }
    if($indicador=='paciente_pscv'){
        $atributo = $_POST['atributo'];
        if($atributo=='factor_riesgo_iam'){
            $sql = "select * from $table_sql where $atributo!=''  group by $atributo order by $atributo desc ";

        }
    }

    $res = mysql_query($sql);
    $i=0;
    while($row = mysql_fetch_array($res)){
        ?>
        <option><?php echo $row[$atributo]; ?></option>
        <?php
        $i++;
    }
    if($i==0){
        ?>
        <option>SI</option>
        <?php
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