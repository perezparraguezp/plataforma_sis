<?php
include '../config.php';
include '../objetos/persona.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro = $_POST['centro'];
$meses = $_POST['meses'];
if($meses==''){
    $meses = 108;
}
$sql1 = "select count(*) as total,edad_total,DNI
          from persona inner join paciente_establecimiento using(rut)
          inner join antropometria on persona.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento'
          and edad_total<'$meses' ";
$row1 = mysql_fetch_array(mysql_query($sql1));
$total_pacientes = $row1['total'];

$sql1 = "select count(*) as total,edad_total,DNI
          from persona inner join paciente_establecimiento using(rut)
          inner join antropometria on persona.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento'
          and edad_total<'$meses'
          group by antropometria.DNI;
          ";

$res1 = mysql_query($sql1);


?>

<script type="text/javascript">
    $(document).ready(function () {
        var dataStatCounter =
            [
                <?php
                $option = '';
                while($row1 = mysql_fetch_array($res1)){
                    $tipo = $row1['DNI'];
                    $total_tipo = $row1['total'];
                    $porcentaje = number_format(($total_tipo * 100) / $total_pacientes,2,'.','');
                    if($tipo==''){
                        $tipo = 'PENDIENTE';
                    }
                    echo "{ Browser: '".$tipo."', Share: ".$porcentaje." },";
                    $option.='<option>'.$tipo.'</option>';
                }
                ?>

            ];
        var charts = [
            { title: 'Niños/as hasta los 9 Años', label: 'Stat', dataSource: dataStatCounter },
        ];

        var chartSettings = {
            source: charts[0].dataSource,
            title: '% Estado Nutricional Poblacion Infantil',
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
                                maxAngle: 360,
                                centerOffset: 0,
                                offsetY: 170,
                                formatFunction: function (value, itemIdx, serieIndex, groupIndex) {
                                    if (isNaN(value))
                                        return value;
                                    return value + '%';
                                }
                            }
                        ]
                }
            ]
        };
        // select container and apply settings
        var selector = '#chartContainer1';
        $(selector).jqxChart(chartSettings);
    });
</script>
<div class="row">
    <div class="col l6">
        <div id='chartContainer1' style="width: 100%; height: 250px;">
        </div>
    </div>
    <div class="col l1">-</div>
    <div class="col l5" style="background-color: #d7efff;border: solid 1px;">
        <div class="row">
            <div class="col l4">ESTADO NUTRICIONAL</div>
            <div class="col l8">
                <select name="estado_nutricional"
                        id="estado_nutricional"
                        onchange="loadGrafico_Nutricional_Sexo_Estado()">
                    <?php echo $option; ?>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#estado_nutricional').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });

                        $('#estado_nutricional').on('change',function(){
                            alertaLateral('actualizando');
                            var estado = $('#estado_nutricional').val();
                            $.post('php/graficos/pie_estado_nutricional_sexo.php',{
                                estado:estado,
                                meses:'<?php echo $meses ?>',
                                centro:'<?php echo $id_centro ?>'
                            },function(data){
                                $("#chartContainer2").html(data);
                            });
                        });
                        //loadGrafico_Nutricional_Sexo_Estado();
                    });
                    function loadGrafico_Nutricional_Sexo_Estado(){

                    }
                </script>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <div id='chartContainer2' style="width: 100%; height: 250px;"></div>
            </div>
        </div>
    </div>
</div>