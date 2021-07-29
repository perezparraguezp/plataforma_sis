<?php
include '../config.php';

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);

$indicador      = $_POST['indicador'];

$filtro = '';

if(in_array('TODOS',$sector_comunal)){
    //existe el filtro todos
    $filtro .= '';
}else{
    $a = 0;
    foreach ($sector_comunal as $i => $id_sector_comunal){
        $id_sector_comunal = trim($id_sector_comunal);
        if($id_sector_comunal!='' && $id_sector_comunal != null){
            if($a>0){
                $filtro .= 'or ';
            }
            $filtro .= " centros_internos.id_sector_comunal='$id_sector_comunal' ";
            $a++;
        }
    }
    if($a>0){
        $filtro = " and (".$filtro.")";
    }

    //PROCEDEMOS A VALIDAR LOS CENTROS INTERNOS
    $filtro_2 = "";
    if(in_array('TODOS',$centro_interno)){
        $filtro_2 .= "";
    }else{
        $b = 0;
        foreach ($centro_interno as $i => $id_centro_interno){
            $id_centro_interno = trim($id_centro_interno);
            if($id_centro_interno!='' && $id_centro_interno != null){
                if($b>0){
                    $filtro_2 .= 'or ';
                }
                $filtro_2 .= " centros_internos.id_centro_interno='$id_centro_interno' ";
                $b++;
            }
        }
        if($b>0){
            $filtro = " and (".$filtro_2.")";
        }
        //PROCEDEMOS A VALIDAR LOS SECTORES INTERNOS
        $filtro_3 = "";
        if(in_array('TODOS',$sector_interno)){
            $filtro_3 .= "";
        }else{
            $b = 0;
            foreach ($sector_interno as $i => $id_sector_centro_interno){
                $id_sector_centro_interno = trim($id_sector_centro_interno);
                if($id_sector_centro_interno!='' && $id_sector_centro_interno != null){
                    if($b>0){
                        $filtro_3 .= 'or ';
                    }
                    $filtro_3 .= " sectores_centros_internos.id_sector_centro_interno='$id_sector_centro_interno' ";
                    $b++;
                }
            }
            if($b>0){
                $filtro = " and (".$filtro_3.")";
            }

        }
    }
}

$sql1 = "select sectores_centros_internos.nombre_sector_interno,id_sector_centro_interno,nombre_sector_comunal,nombre_centro_interno  
                from persona
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on persona.rut=antropometria.rut  
                where paciente_establecimiento.id_establecimiento='$id_establecimiento' ".$filtro." group by id_sector";
$res1 = mysql_query($sql1);
$array_sectores = Array();
$array_nombre_sectores = Array();
$i = 0;
while($row1 = mysql_fetch_array($res1)){
    $array_sectores[$i] = $row1['id_sector_centro_interno'];
    $array_nombre_sectores[$row1['id_sector_centro_interno']] = $row1['nombre_sector_interno']." [".$row1['nombre_centro_interno']."]";
    $i++;
}

?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  sampleData = [
            <?php

            $sql2 = "select * from antropometria
                          inner join paciente_establecimiento on antropometria.rut=paciente_establecimiento.rut
                          where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                          group by DNI";

            $res2 = mysql_query($sql2);
            while($row2 = mysql_fetch_array($res2)){
                $DNI = $row2['DNI'];

                if($DNI!=''){
                    $rango .= "{ Rango:'".$DNI."' ";

                    foreach ($array_sectores as $i => $id_sector_centro_interno){
                        $sql1 = "select count(*) as total,sectores_centros_internos.nombre_sector_interno,
                                    nombre_sector_comunal,nombre_centro_interno   
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where persona.edad_total<(6*12) and paciente_establecimiento.id_establecimiento='$id_establecimiento' ".$filtro;
                        //echo $sql1;
                        $sql1 .= "AND antropometria.DNI='$DNI' and paciente_establecimiento.id_sector='$id_sector_centro_interno' ";
                        $row1 = mysql_fetch_array(mysql_query($sql1));
                        if($row1){
                            $total = $row1['total'];
                            $nombre_sector = $row1['nombre_sector_interno']." [".$row1['nombre_centro_interno']."]";
                        }else{
                            $total = 0;
                            $nombre_sector = '';
                        }
                        $rango .=" ,'".$id_sector_centro_interno."':".$total;
                    }

                    $res1 = mysql_query($sql1);


                    $row1 = mysql_fetch_array(mysql_query($sql1));




                    $rango .= "},";

                    //$series .=" { dataField: '$id_sector_centro_interno', displayText: 'Sector: ".$nombre_sector."'},";
                }

            }


            echo $rango;

            $series = '';
            foreach ($array_sectores as $i => $id_sector_interno){
                $series .=" { dataField: '$id_sector_interno', displayText: 'Sector: ".$array_nombre_sectores[$id_sector_interno]."'},";
            }

            ?>
        ];
        // prepare jqxChart settings
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
            var content = $('#div_indicador_grafico')[0].outerHTML;
            var newWindow = window.open('', '', 'width=900, height=600'),
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
