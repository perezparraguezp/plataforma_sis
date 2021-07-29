<?php
include "../config.php";



?>
<div class="sample-chart-wrapper" >
    <canvas id="bar-chart-sample" height="200"></canvas>
    <p class="header center">Total Documentos por Comuna</p>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        //graficos
        var BarChartSampleData = {
            labels: [
                <?php
                $sql1 = "select count(*) as total,comuna from documento_establecimiento 
                          INNER JOIN establecimiento using(id_establecimiento) GROUP BY comuna;";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    "<?php echo $row1['comuna']; ?>",
                    <?php
                }
                ?>
            ],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "#0D47A1",
                    strokeColor: "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data: [
                        <?php
                        $sql1 = "select count(*) as total,comuna from documento_establecimiento 
                          INNER JOIN establecimiento using(id_establecimiento) GROUP BY comuna;";
                        $res1 = mysql_query($sql1);
                        while($row1 = mysql_fetch_array($res1)){
                        ?>
                        <?php echo $row1['total']; ?>,
                        <?php
                        }
                        ?>
                    ]
                }
            ],

        };
        window.BarChartSample = new Chart(document.getElementById("bar-chart-sample").getContext("2d")).Bar(BarChartSampleData,{
            responsive:true
        });

    });
</script>
