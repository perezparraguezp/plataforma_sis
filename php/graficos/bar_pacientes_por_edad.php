<?php
include '../config.php';
include '../objetos/persona.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro = $_POST['id_centro'];
$id_sector = $_POST['id_sector_centro'];

if($id_centro=='TODOS'){
    $filtro = '';
}else{
    $filtro = "where id_centro_interno='$id_centro' ";

    if($id_sector!='TODOS'){
        $filtro .= " and id_sector_centro_interno='$id_sector' ";
    }
}

$sql1 = "select * from sectores_centros_internos 
            inner join centros_internos using(id_centro_interno)
            $filtro 
            limit 1";

$row1 = mysql_fetch_array(mysql_query($sql1));
$nombre_centro = $row1['nombre_centro_interno'];
if($id_sector=='TODOS'){
    $nombre_sector = 'TODOS';
}else{
    $nombre_sector = $row1['nombre_sector_interno'];
}


$rangos[1] = [0,6];
$rangos[2] = [7,11];
$rangos[3] = [12,24];
$rangos[4] = [25,48];
$rangos[5] = [49,72];
$rangos[6] = [73,108];

?>


<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  sampleData = [
            <?php

            $sql1 = "select * from persona inner join paciente_establecimiento using(rut) 
                      where id_establecimiento='$id_establecimiento' 
                      ";
            if($id_sector!='TODOS'){
                $sql1 .= "and paciente_establecimiento.id_sector='$id_sector' ";
            }

            $res1 = mysql_query($sql1);

            $rango1['M'] = $rango1['F'] = 0;
            $rango2['M'] = $rango2['F'] = 0;
            $rango3['M'] = $rango3['F'] = 0;
            $rango4['M'] = $rango4['F'] = 0;
            $rango5['M'] = $rango5['F'] = 0;
            $rango6['M'] = $rango6['F'] = 0;

            while($row1 = mysql_fetch_array($res1)){
                $p = new persona($row1['rut']);
                if($p->sexo=='F'){
                    if($p->total_meses>=0 && $p->total_meses<=6){
                        $rango1['F'] +=1;
                    }else{
                        if($p->total_meses>=7 && $p->total_meses<=11){
                            $rango2['F'] +=1;
                        }else{
                            if($p->total_meses>=12 && $p->total_meses<=24){
                                $rango3['F'] +=1;
                            }else{
                                if($p->total_meses>=25 && $p->total_meses<=48){
                                    $rango4['F'] +=1;
                                }else{
                                    if($p->total_meses>=49 && $p->total_meses<=72){
                                        $rango5['F'] +=1;
                                    }else{
                                        if($p->total_meses>=73 && $p->total_meses<=108){
                                            $rango6['F'] +=1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    if($p->total_meses>=0 && $p->total_meses<=6){
                        $rango1['M'] +=1;
                    }else{
                        if($p->total_meses>=7 && $p->total_meses<=11){
                            $rango2['M'] +=1;
                        }else{
                            if($p->total_meses>=12 && $p->total_meses<=24){
                                $rango3['M'] +=1;
                            }else{
                                if($p->total_meses>=25 && $p->total_meses<=48){
                                    $rango4['M'] +=1;
                                }else{
                                    if($p->total_meses>=49 && $p->total_meses<=72){
                                        $rango5['M'] +=1;
                                    }else{
                                        if($p->total_meses>=73 && $p->total_meses<=108){
                                            $rango6['M'] +=1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
            { Rango:'0 - 6m', M:<?php echo $rango1['M']; ?>, F:<?php echo $rango1['F']; ?>,T:<?php echo $rango1['F']+$rango1['M']; ?>},
            { Rango:'7m - 11m', M:<?php echo $rango2['M']; ?>, F:<?php echo $rango2['F']; ?>,T:<?php echo $rango2['F']+$rango2['M']; ?>},
            { Rango:'1a - 2a', M:<?php echo $rango3['M']; ?>, F:<?php echo $rango3['F']; ?>,T:<?php echo $rango3['F']+$rango3['M']; ?>},
            { Rango:'2a 1d - 4a', M:<?php echo $rango4['M']; ?>, F:<?php echo $rango4['F']; ?>,T:<?php echo $rango4['F']+$rango4['M']; ?>},
            { Rango:'4a 1d - 6a', M:<?php echo $rango5['M']; ?>, F:<?php echo $rango5['F']; ?>,T:<?php echo $rango5['F']+$rango5['M']; ?>},
            { Rango:'6a 1d - 9a', M:<?php echo $rango6['M']; ?>, F:<?php echo $rango6['F']; ?>,T:<?php echo $rango6['F']+$rango6['M']; ?>},
        ];
        // prepare jqxChart settings
        var settings = {
            title: "% DISTRIBUCIÓN DE POBLACION INFANTIL",
            description: "CENTRO: <?php echo $nombre_centro." - SECTOR: ".$nombre_sector; ?>",
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
                            { dataField: 'M', displayText: 'Masculino'},
                            { dataField: 'F', displayText: 'Femenino'},

                        ]
                    },
                    {
                        type: 'line',
                        valueAxis:
                            {
                                visible: true,
                                position: 'top',
                                unitInterval: 10,
                                gridLines: { visible: false },
                                labels: { horizontalAlignment: 'left' }
                            },
                        series: [
                            { dataField: 'T', displayText: 'Total' }
                        ]
                    }
                ]
        };
        var settings1 = {
            title: "% DISTRIBUCIÓN DE POBLACION INFANTIL",
            description: "CENTRO: <?php echo $nombre_centro." - SECTOR: ".$nombre_sector; ?>",
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
                        type: 'stackedcolumn',
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
                            { dataField: 'M', displayText: 'Masculino'},
                            { dataField: 'F', displayText: 'Femenino'},
                            { dataField: 'T', displayText: 'Total'}
                        ]
                    }
                ]
        };

        // setup the chart
        $('#jqxChart').jqxChart(settings);
    });
</script>

<div>
    <div id='host' style="margin: 0 auto; width:100% height:400px;">
        <div id='jqxChart' style="width:100%; height:400px; position: relative; left: 0px; top: 0px;">
        </div>
    </div>
</div>
