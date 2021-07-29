<?php
include '../../config.php';

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);



$indicador      = $_POST['indicador'];

if($indicador!=''){
    $psicomotor = $indicador;
}else{
    $psicomotor = 'EV NEUROSENSORIAL';
}

$column = '';
switch ($psicomotor){
    case 'EV NEUROSENSORIAL':{
        $column = 'ev_neurosensorial';
        $filtro_edad = 'and DATEDIFF(current_date(),persona.fecha_nacimiento)>=0 
                        and DATEDIFF(current_date(),persona.fecha_nacimiento)<=90 ';//formato en dias
        break;
    }
    case 'RX PELVIS':{
        $column = 'rx_pelvis';
        $filtro_edad = 'and persona.edad_total>=3 and persona.edad_total<6 ';
        break;
    }
    case 'EEDP MENOR 12 MESES':{
        $column = 'eedp';
        $filtro_edad = 'and persona.edad_total<12 ';
        break;
    }
    case 'EEDP ENTRE 12 A 23 MESES':{
        $column = 'eedp';
        $filtro_edad = 'and persona.edad_total>=12 and persona.edad_total<24 ';
        break;
    }
    case 'TEPSI':{
        $column = 'tepsi';
        $filtro_edad = 'and persona.edad_total>=(2*12) and persona.edad_total<(5*12) ';
        break;
    }
    case 'EDIMBURGO':{
        $column = 'edimburgo';
        break;
    }
}
$estado_indidacor = $_POST['estados_psicomotor'];
if($estado_indidacor==''){
    $sql2 = "select * from paciente_psicomotor 
                            where $column!='' 
                            group by $column limit 1";
    $row2 = mysql_fetch_array(mysql_query($sql2));
    if($row2){

        $estado_indidacor = $row2[$column];
    }else{
        echo 'NO EXISTEN ESTADOS DISPONIBLES';
    }
}



//$filtro_edad = '';


$sql_column = '';
$sql_column .= ",sum(paciente_psicomotor.$column='$estado_indidacor' $filtro_edad) as total_indicador";
$sql_column .= ",sum(paciente_psicomotor.$column='$estado_indidacor' and persona.sexo='M' $filtro_edad) as total_hombres";
$sql_column .= ",sum(paciente_psicomotor.$column='$estado_indidacor' and persona.sexo='F' $filtro_edad) as total_mujeres";

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
    $sql1 = "select 'GENERAL' as nombre_base,count(*) as total
                                   $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_psicomotor on persona.rut=paciente_psicomotor.rut  
                                    where paciente_psicomotor.$column!='' 
                                    $filtro_edad
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' ";


    $row1 = mysql_fetch_array(mysql_query($sql1));
    $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
    $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
    $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
    $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;

    $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

    $rango .= "{ Rango:'GENERAL',estado:$porcentaje},";
    $series .=" { dataField: 'estado', displayText: '$estado_indidacor',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";





}else{
    if($establecimientos==true){
        $sql1 = "select sector_comunal.nombre_sector_comunal as nombre_base,count(*) as total,sector_comunal.id_sector_comunal as id
                                   $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_psicomotor on persona.rut=paciente_psicomotor.rut  
                                    where paciente_psicomotor.$column!='' 
                                    $filtro_edad   
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    AND (";


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
        $sql1.=") 
        group by centros_internos.id_sector_comunal";




        $res1 = mysql_query($sql1);

        $rango .= "\n{ Rango:'".$estado_indidacor."'";
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];//nombre del sector comunal
            $id = $row1['id'];

            $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
            $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
            $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
            $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
            $porcentaje = number_format(($total_indicador*100/$total),0,'.','');


            $rango .= ",$id:$porcentaje";

            $series .="\n { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
        }
        $rango .="},";


    }else{
        if($sectores==true){
            //para todos los sectores internos
            $sql1 = "select centros_internos.nombre_centro_interno as nombre_base,count(*) as total,
                                  centros_internos.id_centro_interno as id
                                   $sql_column 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_psicomotor on persona.rut=paciente_psicomotor.rut  
                                    where paciente_psicomotor.$column!='' $filtro_edad
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
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


            $res1 = mysql_query($sql1);

            $rango .= "{ Rango:'".$estado_indidacor."' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $id = $row1['id'];
                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');


                $rango .= ",$id:$porcentaje";

                $series .="\n { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            }
            $rango .= "},";

        }else{


            $sql1 = "select sectores_centros_internos.nombre_sector_interno as nombre_base,
            centros_internos.nombre_centro_interno as nombre_centro,
            count(*) as total, sectores_centros_internos.id_sector_centro_interno as id
                                   $sql_column 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_psicomotor on persona.rut=paciente_psicomotor.rut  
                                    where paciente_psicomotor.$column!='' $filtro_edad $filtro_edad 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
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

            $rango .= "{ Rango:'".$estado_indidacor."' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_centro']."]";
                $id = $row1['id'];
                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');


                $rango .= ",$id:$porcentaje";

                $series .="\n { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            }
            $rango .= "},";

        }
    }
}

//echo $sql1;


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
            title: 'PSICOMOTOR - <?php echo $indicador; ?>',
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
                    },

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
        <strong></strong>
    </div>
    <div class="row">
        <div class="col l4 left-align">
            <label for="select_psicomotor">PSICOMOTOR</label>
            <select id="select_psicomotor" name="select_psicomotor" >
                <option><?php echo $psicomotor; ?></option>
                <option disabled>-----------------</option>
                <option>EV NEUROSENSORIAL</option>
                <option>RX PELVIS</option>
                <option>EEDP MENOR 12 MESES</option>
                <option>EEDP ENTRE 12 A 23 MESES</option>
                <option>TEPSI</option>
                <option>EDIMBURGO</option>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#select_psicomotor').jqxDropDownList({
                        width: '100%',
                        height: '25px'
                    });
                    $('#estados_psicomotor').jqxDropDownList({
                        width: '100%',
                        height: '25px'
                    });
                    $('#select_psicomotor').on('select', function (event) {
                        loadIndicador_Grafico_PSICOMOTOR();
                    });
                    $('#estados_psicomotor').on('select', function (event) {
                        loadIndicador_Grafico_PSICOMOTOR();
                    });

                });
                function loadIndicador_Grafico_PSICOMOTOR() {
                    var indicador = $("#select_psicomotor").val();
                    var estados_psicomotor = $("#estados_psicomotor").val();


                    $.post('php/graficos/barra/PSICOMOTOR.php',{
                        sector_comunal:sector_comunal,
                        centro_interno:centro_interno,
                        sector_interno:sector_interno,
                        indicador:indicador,
                        estados_psicomotor:estados_psicomotor,
                    },function(data){
                        $("#div_indicador_grafico").html(data);
                        //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                    });
                    updateHeadEscritorio();

                }
            </script>
        </div>
        <div class="col l4 left-center">
            <label for="estados_psicomotor">ESTADO</label>
            <select name="estados_psicomotor" id="estados_psicomotor">
                <option><?php echo $estado_indidacor; ?></option>
                <option disabled>-----------------</option>
                <?php
                $sql2 = "select * from paciente_psicomotor 
                            where $column!='' 
                            group by $column ";
                $res2 = mysql_query($sql2);
                while($row2 = mysql_fetch_array($res2)){
                    ?>
                    <option><?php echo $row2[$column]; ?></option>
                <?php
                }
                ?>
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
