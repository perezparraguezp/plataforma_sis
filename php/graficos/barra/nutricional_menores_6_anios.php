<?php
include '../../config.php';
include '../../objetos/persona.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro = $_POST['id_centro'];
$id_sector = $_POST['id_sector_centro'];

if($id_centro=='TODOS'){
    $filtro = '';
}else{
    $filtro = "and sectores_centros_internos.id_centro_interno='$id_centro' ";

    if($id_sector!='TODOS'){
        $filtro .= " and paciente_establecimiento.id_sector='$id_sector' ";
    }
}

$sql1 = "select * from persona
          inner join antropometria using(rut)
          where edad_total<(12*6)
          group by DNI";
$res1 = mysql_query($sql1);
$rangos = array();
$i = 0;
while($row1 = mysql_fetch_array($res1)){
    $rangos[$i] = $row1['DNI'];
    $i++;
}

?>


<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  sampleData = [
            <?php


            $array_series = array();
            $rango = '';
            foreach ($rangos as $i => $DNI){
                $sql2 = "select count(*) as total, DNI,id_sector,nombre_sector_interno,sectores_centros_internos.id_centro_interno,nombre_centro_interno from persona
                          inner join antropometria using(rut)
                          inner join paciente_establecimiento using (rut)
                          inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                          inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                          where edad_total<(12*6) and DNI='$DNI'
                          $filtro
                          group by DNI,paciente_establecimiento.id_sector";
                $res2 = mysql_query($sql2);
                $rango .= "{ Rango:'".$DNI."' ";

                while($row2 = mysql_fetch_array($res2)){
                    $sector = $row2['nombre_sector_interno'];
                    $total_pacientes = $row2['total'];
                    $rango .= ",$sector:$total_pacientes ";
                    if(!in_array($sector,$array_series)){
                        array_push($array_series,$sector);
                    }
                }
                $rango .= "},";
            }


            echo $rango;

            $series = '';
            foreach ($array_series as $i => $value){
                $series .=" { dataField: '$value', displayText: 'Sector: $value'},";
            }

            ?>
        ];
        // prepare jqxChart settings
        var settings = {
            title: "ESTADO NUTRICIONAL",
            description: "Menores de 6 Años",
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: sampleData,
            xAxis:
                {
                    dataField: 'Rango',
                    unitInterval: 1,
                    axisSize: 'auto',
                    tickMarks: {
                        visible: true,
                        interval: 1,
                        color: '#BCBCBC'
                    },
                    gridLines: {
                        visible: true,
                        interval: 1,
                        color: '#BCBCBC'
                    }
                },
            valueAxis:
                {
                    unitInterval: 10,
                    minValue: 0,
                    title: { text: 'Cantidad de Niños/as' },
                    labels: { horizontalAlignment: 'right' },
                    tickMarks: { color: '#BCBCBC' }
                },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'stackedcolumn',
                        columnsGapPercent: 50,
                        seriesGapPercent: 0,
                        series: [
                            <?php echo $series; ?>

                        ]
                    }
                ]
        };
        var settings1 = {
            title: 'ESTADO NUTRICIONAL DNI',
            description: "Menores de 6 Años",
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: sampleData,
            xAxis:
                {
                    dataField: 'Rango',
                    showGridLines: true
                },
            colorScheme: 'scheme01',

            seriesGroups:
                [
                    {
                        type: 'column',
                        columnsGapPercent: 50,
                        seriesGapPercent: 0,
                        valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                displayValueAxis: true,
                                description: 'Cantidad',
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                        series: [
                            <?php echo $series; ?>
                        ]
                    }
                ]
        };

        // setup the chart
        $('#dni_menor_6anios').jqxChart(settings1);

        $("#print").click(function () {
            var content = $('#graficos_antropometria')[0].outerHTML;
            var newWindow = window.open('', '', 'width=800, height=500'),
                document = newWindow.document.open(),
                pageContent =
                    '<!DOCTYPE html>' +
                    '<html>' +
                    '<head>' +
                    '<meta charset="utf-8" />' +
                    '</head>' +
                    '<body>' + content + '</body></html>';
            try
            {
                document.write(pageContent);
                document.close();
                newWindow.print();
                newWindow.close();
            }
            catch (error) {
            }
        });
        $("#print").jqxButton({});
    });
</script>

<div>
    <div class="row">
        <div class="col l12 right-align">
            <input type="button" id="print" value="IMPRIMIR GRAFICO" />
        </div>
    </div>
    <div id='host' style="margin: 0 auto; width:100% height:400px;">
        <div id='dni_menor_6anios' style="width:95%; height:400px; position: relative; left: 0px; top: 0px;">
        </div>
    </div>
</div>
