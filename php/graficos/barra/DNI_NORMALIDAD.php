<?php
include '../../config.php';

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);



$indicador      = $_POST['indicador'];



if($indicador!=''){
    $DNI = $indicador;
}else{
    $DNI = 'NORMAL';
}

$edad      = $_POST['edad_dni'];

if($edad == ''){
    $edad = 'TODOS';
    $filtro_edad = 'persona.edad_total<(11*12) ';//menores de 11 años
}else{
    if($edad == 'MENORES DE 6 AÑOS'){
        $filtro_edad = 'persona.edad_total<(6*12) ';//menores de 11 años
    }else{
        if($edad == 'TODOS'){
            $filtro_edad = 'persona.edad_total<(11*12) ';//menores de 11 años
        }else{
            $filtro_edad = 'persona.edad_total>=(6*12) and persona.edad_total<=(11*12) ';//menores de 11 años
        }
    }
}



$filtro = '';
$comunal = $establecimientos = $sectores = false;

if(in_array('TODOS',$sector_comunal)){

   $comunal = true;
}else{

    if(in_array('TODOS',$centro_interno)){
        $establecimientos = true;
    }else{
        if(in_array('TODOS',$sector_interno)){
            $sectores = true;
        }
    }
}

$rango = '';
$series = '';

if($comunal==true){
    //para todos los sectores comunales
    $sql1 = "select count(*) as total,'GENERAL' as nombre_base ,sum(DNI='$DNI') as total_indicador,
                                    SUM(persona.sexo='M' AND antropometria.DNI='$DNI') as hombres,
                                    SUM(persona.sexo='F' AND antropometria.DNI='$DNI') as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    and antropometria.DNI!='' ";

    $row1 = mysql_fetch_array(mysql_query($sql1));

    $total = $row1['total'];
    $total_hombres   = $row1['hombres'];
    $total_mujeres   = $row1['mujeres'];

    $total_indicador = $row1['total_indicador'];
    $porcentaje = number_format(($total_indicador*100/$total),0,'.','');


    $rango .= "{ Rango:'".$DNI."',GENERAL: ".$porcentaje."},";
    $series .=" { dataField: 'GENERAL', displayText: 'GENERAL',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
}else{
    if($establecimientos==true){


        //para todos los establecimientos pero segun el sector comunal seleccionado
        $sql1 = "select count(*) as total,sector_comunal.nombre_sector_comunal as nombre_base,
                                    SUM(persona.sexo='M' AND antropometria.DNI='$DNI') as hombres,
                                    SUM(persona.sexo='F' AND antropometria.DNI='$DNI') as mujeres,
                                    centros_internos.id_sector_comunal as id,sum(DNI='$DNI') as total_indicador 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and antropometria.DNI!=''  
                                    and (";
        $a = 0;
        foreach ($sector_comunal as $i => $id_sector_comunal){
            $id_sector_comunal = trim($id_sector_comunal);
            if($id_sector_comunal!='' && $id_sector_comunal != null){
                if($a>0){
                    $sql1.=' or ';
                }
                $sql1 .= "centros_internos.id_sector_comunal='$id_sector_comunal' ";
                $a++;
            }

        }
        $sql1.=') 
        group by centros_internos.id_sector_comunal ';

        $res1 = mysql_query($sql1);

        $rango .= "{ Rango:'".$DNI."' ";
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];
            $id = $row1['id'];

            $total_hombres   = $row1['hombres'];
            $total_mujeres   = $row1['mujeres'];

            $total = $row1['total'];
            $total_indicador = $row1['total_indicador'];
            $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

            $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango .= ",$id:$porcentaje";
        }
        $rango .= "},";



    }else{
       if($sectores==true){
           //para todos los sectores internos
           $sql1 = "select count(*) as total,centros_internos.nombre_centro_interno as nombre_base,
                                    SUM(persona.sexo='M' AND antropometria.DNI='$DNI') as hombres,
                                    SUM(persona.sexo='F' AND antropometria.DNI='$DNI') as mujeres,
                                    centros_internos.id_centro_interno as id,sum(DNI='$DNI') as total_indicador  
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and antropometria.DNI!=''  
                                    and (";
           $a = 0;
           foreach ($centro_interno as $i => $id_centro_interno){
               $id_centro_interno = trim($id_centro_interno);
               if($id_centro_interno!='' && $id_centro_interno != null){
                   if($a>0){
                       $sql1.=' or ';
                   }
                   $sql1 .= "centros_internos.id_centro_interno='$id_centro_interno' ";
                   $a++;
               }

           }
           $sql1.=') 
        group by centros_internos.id_centro_interno ';
           //echo $sql1;

           $res1 = mysql_query($sql1);

           $rango .= "{ Rango:'".$DNI."' ";
           while($row1 = mysql_fetch_array($res1)){
               $nombre_base = $row1['nombre_base'];
               $id = $row1['id'];
               $total_hombres   = $row1['hombres'];
               $total_mujeres   = $row1['mujeres'];

               $total = $row1['total'];
               $total_indicador = $row1['total_indicador'];
               $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

               $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
               $rango .= ",$id:$porcentaje";
           }
           $rango .= "},";

       }else{


           $sql1 = "select count(*) as total,sectores_centros_internos.nombre_sector_interno as nombre_base,
                                    sectores_centros_internos.id_sector_centro_interno as id,
                                    centros_internos.nombre_centro_interno as nombre_establecimiento,
                                    SUM(persona.sexo='M' AND antropometria.DNI='$DNI') as hombres,
                                    SUM(persona.sexo='F' AND antropometria.DNI='$DNI') as mujeres,
                                    sum(DNI='$DNI') as total_indicador 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and antropometria.DNI!=''  
                                    and (";
           $a = 0;
           foreach ($sector_interno as $i => $id_sector_interno){
               $id_sector_interno = trim($id_sector_interno);
               if($id_sector_interno!='' && $id_sector_interno != null){
                   if($a>0){
                       $sql1.=' or ';
                   }
                   $sql1 .= "sectores_centros_internos.id_sector_centro_interno='$id_sector_interno' ";
                   $a++;
               }

           }
           $sql1.=') 
        group by sectores_centros_internos.id_sector_centro_interno';

           $res1 = mysql_query($sql1);

           $rango .= "{ Rango:'".$DNI."' ";
           while($row1 = mysql_fetch_array($res1)){
               $nombre_base = $row1['nombre_base'];
               $nombre_establecimiento = $row1['nombre_establecimiento'];
               $id = $row1['id'];
               $total_hombres   = $row1['hombres'];
               $total_mujeres   = $row1['mujeres'];

               $total = $row1['total'];
               $total_indicador = $row1['total_indicador'];
               $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

               $series .=" { dataField: '$id', displayText: '$nombre_base [$nombre_establecimiento]',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
               $rango .= ",$id:$porcentaje";
           }
           $rango .= "},";

       }
    }
}


?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  sampleData = [
            <?php echo $rango; ?>
        ];


        var toolTips_DNI = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = sampleData[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_indicador+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                '</DIV>';
        };


        // prepare jqxChart settings
        var settings1 = {
            title: 'DNI - <?php echo $DNI; ?>',
            description: "",
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
                        toolTipFormatFunction: toolTips_DNI,
                        valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                maxValue: 100,
                                displayValueAxis: true,
                                description: 'Porcentaje',
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

        function myEventHandler(event) {
            var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";

            //$('#eventText').html(eventData);
            alertaLateral(eventData);
        };
        // select the chartContainer DIV element and render the chart.

        $('#dni_menor_6anios').on('click', function (event) {
            if (event.args)
                myEventHandler(event);

        });

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
        <div class="col l4 left-align">
            <label for="select_dni">DNI</label>
            <select id="select_dni" name="select_dni" >
                <option><?php echo $DNI; ?></option>
                <option disabled>----------------</option>
                <?php
                $sql3 = "select * from antropometria WHERE DNI!='' group BY DNI";
                $res3 = mysql_query($sql3);
                while($row3 = mysql_fetch_array($res3)){
                    ?>
                    <option><?php echo $row3['DNI']; ?></option>
                <?php
                }
                ?>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#select_dni').jqxDropDownList({
                        width: '100%',
                        height: '25px'
                    });
                    $('#select_dni').on('select', function (event) {
                        loadIndicador_Grafico_DNI();
                    });
                    $('#edad_dni').jqxDropDownList({
                        width: '100%',
                        height: '25px'
                    });
                    $('#edad_dni').on('select', function (event) {
                        loadIndicador_Grafico_DNI();
                    });
                });
                function loadIndicador_Grafico_DNI() {
                    var indicador = $("#select_dni").val();
                    var edad_dni = $("#edad_dni").val();

                    $.post('php/graficos/barra/DNI_NORMALIDAD.php',{
                        sector_comunal:sector_comunal,
                        centro_interno:centro_interno,
                        sector_interno:sector_interno,
                        indicador:indicador,
                        edad_dni:edad_dni

                    },function(data){
                        $("#div_indicador_grafico").html(data);
                        //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                    });
                    updateHeadEscritorio();

                }
            </script>
        </div>
        <div class="col l4 left-center">
            <label for="edad_dni">RANGO DE EDAD</label>
            <select id="edad_dni" name="edad_dni" >
                <option><?php echo $edad; ?></option>
                <option disabled>----------------</option>
                <option>TODOS</option>
                <option>MENORES DE 6 AÑOS</option>
                <option>ENTRE 6 A 9 AÑOS</option>
            </select>
        </div>
        <div class="col l4 right-align">
            <input type="button" id="print" value="IMPRIMIR GRAFICO" />
        </div>
    </div>
    <div id='host' style="margin: 0 auto; width:100% height:400px;">
        <div id='dni_menor_6anios' style="width:95%; height:400px; position: relative; left: 0px; top: 0px;">
        </div>
    </div>
</div>
