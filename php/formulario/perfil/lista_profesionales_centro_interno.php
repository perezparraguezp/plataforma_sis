<?php
include '../../config.php';
include '../../objetos/persona.php';
$id_centro_interno = $_POST['id_centro_interno'];
$sector = $_POST['sector'];

?>
<div class="col l12">
    <?php
    $sql = "select * from personal_establecimiento 
            inner join persona using(rut) 
            order by nombre_completo";
    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res)){
        $p = new persona($row['rut']);

        $sql1 = "select * from profesionales_sector_centro 
                  where rut='$p->rut' and id_sector_centro_interno='$sector'
                  limit 1";

        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $check = 'checked="checked"';
        }else{
            $check = '';
        }


        ?>
        <p>
            <input type="checkbox" class="filled-in"
                   <?php echo $check; ?>
                   id="<?php echo $p->rut ?>-check"
                   onchange="updateProfesionalSectorCentro('<?php echo $p->rut ?>','<?php echo $sector; ?>')" />
            <label for="<?php echo $p->rut ?>-check"><?php echo $p->nombre; ?></label>
        </p>
    <?php
    }
    ?>

</div>
<script type="text/javascript">
    function updateProfesionalSectorCentro(rut,sector){
        $.post('php/db/update/profesional_sector_centro.php',{
            rut:rut,sector:sector
        },function(data){
            var texto = 'SE HAN REGISTRADO EL CAMBIO SELECCIONADO';
            alertaLateral(texto);
        });
    }
</script>
