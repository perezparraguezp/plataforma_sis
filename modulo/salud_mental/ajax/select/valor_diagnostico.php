<select name="evaluacion" id="evaluacion">
    <?php
    include "../../../../php/config.php";
    $tipo = $_POST['tipo'];


    $sql = "select * from valor_diagnostico_sm where id_tipo='$tipo' group by valor";
    $res = mysql_query($sql);
    $i=0;
    while($row = mysql_fetch_array($res)){
        ?>
        <option><?php echo $row['valor']; ?></option>
        <?php
        $i++;
    }
    if($i==0){
        ?>
        <option></option>
        <?php
    }
    ?>
</select>
    <script type="text/javascript">
        $(function(){
            $('#evaluacion').jqxDropDownList({
                width: '98%',
                theme: 'eh-open',
                height: '25px'
            });
        })
    </script>

