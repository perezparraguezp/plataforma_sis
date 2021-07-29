<?php
include '../../config.php';

$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro = $_POST['id_centro'];
$id_sector = $_POST['id_sector_centro'];

$filtro = '';
if($id_centro=='TODOS'){
    $filtro = '';
}else{
    $filtro = "and sectores_centros_internos.id_centro_interno='$id_centro' ";

    if($id_sector!='TODOS'){
        $filtro .= " and paciente_establecimiento.id_sector='$id_sector' ";
    }
}


$sql1 = "select nombre_centro_interno,nombre_sector_interno,ges6,cero,count(*) as total,sum(upper(cero)='SI') as total_cero
from persona
inner join paciente_establecimiento using (rut)
inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
inner join paciente_dental using(rut)
where edad_total<(12*6) $filtro
group by centros_internos.id_centro_interno,id_sector_centro_interno 
order by nombre_centro_interno,nombre_sector_interno;";
$res1 = mysql_query($sql1);

$cluster = '';
while($row1 = mysql_fetch_array($res1)){
    $total_pacientes = $row1['total'];//total de la muestra
    $total_si = $row1['total_cero'];//total cero
    $nombre_centro = $row1['nombre_centro_interno'];
    $nombre_sector = $row1['nombre_sector_interno'];

    $cluster .= "{
                        name: 'PROGRAMA CERO - ".$nombre_centro."',
                        descripcion:'Sector: ".$nombre_sector."',
                        value: $total_si,
                        max: $total_pacientes
                    },";


}

?>

<script type="text/javascript">
    $(document).ready(function () {
        function diplayDentalCero() {
            var metrics =
                [
                   <?php echo $cluster; ?>
                ];
            for (var i = 0; i < metrics.length; i++) {

                $("#dental_cero").append("<div id='dental_cero"+i+"' style='width: 400px; height: 180px;'></div>");

                var data = [];
                data.push({ text: 'Cumple', value: metrics[i].value }); // current
                data.push({ text: 'pendientes', value: metrics[i].max - metrics[i].value }); // remaining
                var settings = {
                    title: metrics[i].name,
                    description: metrics[i].descripcion,
                    enableAnimations: true,
                    showLegend: false,
                    showBorderLine: true,
                    backgroundColor: '#FAFAFA',
                    padding: { left: 5, top: 5, right: 5, bottom: 5 },
                    titlePadding: { left: 5, top: 5, right: 5, bottom: 5 },
                    source: data,
                    showToolTips: true,
                    seriesGroups:
                        [
                            {
                                type: 'donut',
                                useGradientColors: false,
                                series:
                                    [
                                        {
                                            showLabels: false,
                                            enableSelection: true,
                                            displayText: 'text',
                                            dataField: 'value',
                                            labelRadius: 120,
                                            initialAngle: 90,
                                            radius: 60,
                                            innerRadius: 50,
                                            centerOffset: 0
                                        }
                                    ]
                            }
                        ]
                };
                var selector = '#dental_cero'+ (i).toString();
                var valueText = metrics[i].value.toString();
                settings.drawBefore = function (renderer, rect) {
                    sz = renderer.measureText(valueText, 0, { 'class': 'chart-inner-text' });
                    renderer.text(
                        valueText,
                        rect.x + (rect.width - sz.width) / 2,
                        rect.y + rect.height / 2,
                        0,
                        0,
                        0,
                        { 'class': 'chart-inner-text' }
                    );
                }

                //imprimir
                $(selector).jqxChart(settings);
                $(selector).jqxChart('addColorScheme', 'customColorScheme', ['#00BAFF', '#EDE6E7']);
                $(selector).jqxChart({ colorScheme: 'customColorScheme' });
            }


        }
        diplayDentalCero();
    });
</script>
<div id="dental_cero">

</div>

