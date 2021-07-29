<?php
include '../config.php';
include '../objetos/persona.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro = $_POST['centro'];
$meses = $_POST['meses'];
$estado = $_POST['estado'];
if($meses==''){
    $meses = 108;
}
$sql1 = "select count(*) as total,edad_total,sexo
          from persona inner join paciente_establecimiento using(rut)
          inner join antropometria on persona.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento'
          and edad_total<'$meses' 
          and DNI='$estado'";
$row1 = mysql_fetch_array(mysql_query($sql1));
$total_pacientes = $row1['total'];

$sql1 = "select count(*) as total,edad_total,sexo
          from persona inner join paciente_establecimiento using(rut)
          inner join antropometria on persona.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento'
          and edad_total<'$meses'
          and DNI='$estado'
          group by persona.sexo;
          ";

$res1 = mysql_query($sql1);


?>

<script type="text/javascript">
    $(document).ready(function () {
        var dataStatCounter =
            [
                <?php
                while($row1 = mysql_fetch_array($res1)){
                    $tipo = $row1['sexo'];
                    $total_tipo = $row1['total'];
                    $porcentaje = number_format($total_tipo ,2,'.','');
                    if($tipo==''){
                        $tipo = 'PENDIENTE';
                    }
                    echo "{ Browser: '".$tipo."', Share: ".$porcentaje." },";
                }
                ?>

            ];
        var charts = [
            { title: 'Niños/as hasta los 9 Años', label: 'Stat', dataSource: dataStatCounter },
        ];

        var chartSettings = {
            source: charts[0].dataSource,
            title: '% Nutricional <?php echo $estado ?>, Según Sexo',
            description: charts[0].title,
            enableAnimations: false,
            showLegend: true,
            showBorderLine: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
            colorScheme: 'scheme03',
            seriesGroups: [
                {
                    type: 'pie',
                    showLegend: true,
                    enableSeriesToggle: true,
                    series:
                        [
                            {
                                dataField: 'Share',
                                displayText: 'Browser',
                                showLabels: true,
                                labelRadius: 160,
                                labelLinesEnabled: true,
                                labelLinesAngles: true,
                                labelsAutoRotate: false,
                                initialAngle: 0,
                                radius: 125,
                                minAngle: 0,
                                maxAngle: 180,
                                centerOffset: 0,
                                offsetY: 170,
                                formatFunction: function (value, itemIdx, serieIndex, groupIndex) {
                                    if (isNaN(value))
                                        return value;
                                    return value ;
                                }
                            }
                        ]
                }
            ]
        };
        // select container and apply settings
        var selector = '#nutricional_sexo_estado';
        $(selector).jqxChart(chartSettings);
    });
</script>
<div class="row">
    <div class="col l12">
        <div id='nutricional_sexo_estado' style="width: 100%; height: 250px;">
        </div>
    </div>
</div>