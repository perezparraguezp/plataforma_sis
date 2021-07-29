<?php
include "../config.php";
$comuna = $_POST['comuna'];
$documento = $_POST['documento'];


?>
<div class="row">
    <div class="sample-chart-wrapper">
        <canvas id="pie-chart-sample" ></canvas>
    </div>
    <p class="header center">Documentos por Comuna</p>
</div>
<form class="row" id="form_grafico1">
    <div class="col l4">
        <select name="comuna">
            <option selected disabled>Comunas</option>
            <option selected disabled>------------------</option>
            <option selected value="ALL">Todas</option>
            <?php
            $sql2 = "select * from documento_establecimiento 
                              INNER JOIN establecimiento USING(id_establecimiento)
                              group by comuna 
                              order by comuna";
            $res2 = mysql_query($sql2);
            while ($row2 = mysql_fetch_array($res2)){
                if($row2['comuna']==$comuna){
                    ?>
                    <option selected><?php echo $row2['comuna']; ?></option>
                    <?php
                }else{
                    ?>
                    <option><?php echo $row2['comuna']; ?></option>
                    <?php
                }

            }
            ?>
        </select>
    </div>
    <div class="col l4">
        <select name="documento">
            <option selected disabled>Documento</option>
            <option selected disabled>------------------</option>
            <option selected value="ALL">Todas</option>
            <?php
            $sql2 = "select id_tipo_doc,nombre_tipo_doc from documento_establecimiento INNER JOIN tipo_documento USING(id_tipo_doc) order by nombre_tipo_doc";
            $res2 = mysql_query($sql2);
            while ($row2 = mysql_fetch_array($res2)){
                if($row2['id_tipo_doc']==$documento){
                    ?>
                    <option selected value="<?php echo $row2['id_tipo_doc']; ?>"><?php echo $row2['nombre_tipo_doc']; ?></option>
                    <?php
                }else{
                    ?>
                    <option value="<?php echo $row2['id_tipo_doc']; ?>"><?php echo $row2['nombre_tipo_doc']; ?></option>
                    <?php
                }

            }
            ?>
        </select>
    </div>
    <input type="hidden" name="grafico1" value="VER" />
    <div class="col l4">
        <input type="button" class="btn orange" onclick="loadGRafico_documentos_por_comuna()" value="VER" />
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').jqxDropDownList({
            filterable: true,
            filterPlaceHolder: "Buscar",
            width: '100%',
            height: '25px'
        });
        //graficos
        var PieDoughnutChartSampleData = [
            <?php
            $filtro = '';
            if($_POST['grafico1']){//solo si existen filtros cargados
                if($comuna!='ALL'){
                    $filtro .= "AND upper(comuna)=upper('$comuna') ";
                }
                if($documento !='ALL'){
                    $filtro .= "AND id_tipo_doc=upper('$documento') ";
                }
            }
            $sql3 = "select comuna,id_tipo_doc,nombre_tipo_doc,count(*) as total from documento_establecimiento
                          INNER JOIN establecimiento USING(id_establecimiento)
                          INNER JOIN tipo_documento USING(id_tipo_doc)
                          WHERE 1=1 $filtro
                          GROUP BY id_establecimiento,id_tipo_doc,comuna";
            $res3 = mysql_query($sql3);
            while($row3 = mysql_fetch_array($res3)){
            $color = substr(md5(time()* rand(0,199)), 0, 6);

                ?>
                {
                value: <?php echo $row3['total']; ?>,
                color:"#<?php echo $color; ?>",
                label: "[<?php echo $row3['comuna']; ?>] <?php echo $row3['nombre_tipo_doc'] ?>"
                },
            <?php
            }
            ?>
        ]
        window.PieChartSample = new Chart(document.getElementById("pie-chart-sample").getContext("2d")).Pie(PieDoughnutChartSampleData,{
            responsive:true
        });

    });
</script>