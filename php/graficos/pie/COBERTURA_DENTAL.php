<?php
include '../../config.php';

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);


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

$rango_cero = '';
$series_cero = '';

$rango_ges6 = '';
$series_ges6 = '';

$edad_cero = " persona.edad_total>=6 and persona.edad_total<(12*4) ";
$edad_ges6 = " persona.edad_total>=(12*6) and persona.edad_total<(12*7) ";


if($comunal==true){
    //para todos los sectores comunales

    //cero
    $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.cero)='SI') AS TOTAL_CERO,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' AND $edad_cero) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' AND $edad_cero) as mujeres 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_cero  
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    ";


    $row1= mysql_fetch_array(mysql_query($sql1));


    $total_pacientes_cero           = $row1['total'];
    $total_pacientes_cumplen_cero   = $row1['TOTAL_CERO'];
    $total_pacientes_cumplen_cero   = $row1['TOTAL_CERO'];
    $total_hombres   = $row1['hombres'];
    $total_mujeres   = $row1['mujeres'];

    $porcentaje_cumplen_cero        = number_format(($total_pacientes_cumplen_cero*100/$total_pacientes_cero),0,'.','');

    $rango_cero .= "{ Rango:'GENERAL',GENERAL: ".$porcentaje_cumplen_cero."},";
    $series_cero .=" { dataField: 'GENERAL', displayText: 'GENERAL',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$total_pacientes_cumplen_cero,hombres:$total_hombres,mujeres:$total_mujeres},";

    //ges6
    $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.ges6)='SI') AS TOTAL_GES6,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' AND $edad_ges6) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' AND $edad_ges6) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_ges6  
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    ";

    $row1= mysql_fetch_array(mysql_query($sql1));

    $total_pacientes_cero           = $row1['total'];
    $total_pacientes_cumplen_cero   = $row1['TOTAL_GES6'];
    $total_hombres   = $row1['hombres'];
    $total_mujeres   = $row1['mujeres'];

    $porcentaje_cumplen_cero        = number_format(($total_pacientes_cumplen_cero*100/$total_pacientes_cero),0,'.','');

    $rango_ges6 .= "{ Rango:'GENERAL',GENERAL: ".$porcentaje_cumplen_cero."},";
    $series_ges6 .=" { dataField: 'GENERAL', displayText: 'GENERAL',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$total_pacientes_cumplen_cero,hombres:$total_hombres,mujeres:$total_mujeres},";




}else{
    if($establecimientos==true){

        //para todos los establecimientos pero segun el sector comunal seleccionado
        $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.cero)='SI') AS TOTAL_CERO
                                    ,sector_comunal.nombre_sector_comunal as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' AND $edad_cero) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' AND $edad_cero) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_cero  
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
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
        //echo $sql1;

        $res1 = mysql_query($sql1);

        $rango_cero .= "{ Rango:'CERO' ";
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];
            $id = $row1['id'];
            $total_hombres   = $row1['hombres'];
            $total_mujeres   = $row1['mujeres'];

            $total_pacientes_cero           = $row1['total'];
            $total_pacientes_cumplen_cero   = $row1['TOTAL_CERO'];

            $porcentaje_cumplen_cero        = number_format(($total_pacientes_cumplen_cero*100/$total_pacientes_cero),0,'.','');

            $series_cero .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$total_pacientes_cumplen_cero,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango_cero .= ",$id:$porcentaje_cumplen_cero";
        }
        $rango_cero .= "},";


        //ges

        //para todos los establecimientos pero segun el sector comunal seleccionado
        $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.ges6)='SI') AS TOTAL_CERO
                                    ,sector_comunal.nombre_sector_comunal as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' AND $edad_ges6) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' AND $edad_ges6) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_ges6  
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
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
        //echo $sql1;

        $res1 = mysql_query($sql1);

        $rango_ges6 .= "{ Rango:'GES6' ";
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];
            $id = $row1['id'];
            $total_hombres   = $row1['hombres'];
            $total_mujeres   = $row1['mujeres'];

            $total_pacientes_ges6           = $row1['total'];
            $total_pacientes_cumplen_ges6   = $row1['TOTAL_CERO'];

            $porcentaje_cumplen_ges6        = number_format(($total_pacientes_cumplen_ges6*100/$total_pacientes_ges6),0,'.','');

            $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_ges6,total_indicador:$total_pacientes_cumplen_ges6,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango_ges6 .= ",$id:$porcentaje_cumplen_ges6";
        }
        $rango_ges6 .= "},";



    }else{
        if($sectores==true){
            //para todos los sectores internos
            $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.cero)='SI') AS total_indicador 
                                    ,centros_internos.nombre_centro_interno as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' AND $edad_cero) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' AND $edad_cero) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_cero  
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
            //echo $sql1;

            $res1 = mysql_query($sql1);

            $rango_cero .= "{ Rango:'CERO' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $id = $row1['id'];
                $total_hombres   = $row1['hombres'];
                $total_mujeres   = $row1['mujeres'];

                $total = $row1['total'];
                $total_indicador = $row1['total_indicador'];
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

                $series_cero .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_cero .= ",$id:$porcentaje";
            }
            $rango_cero .= "},";



            //ges6
            //para todos los sectores internos
            $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.ges6)='SI') AS total_indicador 
                                    ,centros_internos.nombre_centro_interno as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' AND $edad_ges6) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' AND $edad_ges6) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_ges6  
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
            //echo $sql1;

            $res1 = mysql_query($sql1);

            $rango_ges6 .= "{ Rango:'GES6' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $id = $row1['id'];
                $total_hombres   = $row1['hombres'];
                $total_mujeres   = $row1['mujeres'];
                $total = $row1['total'];
                $total_indicador = $row1['total_indicador'];
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

                $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_ges6 .= ",$id:$porcentaje";
            }
            $rango_ges6 .= "},";

        }else{


            $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.cero)='SI') AS total_indicador 
                                    ,concat(sectores_centros_internos.nombre_sector_interno,' [',centros_internos.nombre_centro_interno,'] ') as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' AND $edad_cero) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' AND $edad_cero) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_cero  
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

            $rango_cero .= "{ Rango:'CERO' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $nombre_establecimiento = $row1['nombre_establecimiento'];
                $id = $row1['id'];
                $total_hombres   = $row1['hombres'];
                $total_mujeres   = $row1['mujeres'];
                $total = $row1['total'];
                $total_indicador = $row1['total_indicador'];
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

                $series_cero .=" { dataField: '$id', displayText: '$nombre_base ',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_cero .= ",$id:$porcentaje";
            }
            $rango_cero .= "},";


            $sql1 = "select count(*) as total,sum(UPPER(paciente_dental.ges6)='SI') AS total_indicador 
                                    ,concat(sectores_centros_internos.nombre_sector_interno,' [',centros_internos.nombre_centro_interno,'] ') as nombre_base
                                    ,centros_internos.id_sector_comunal as id,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' AND $edad_ges6) as hombres,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' AND $edad_ges6) as mujeres
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where $edad_ges6  
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

            $rango_ges6 .= "{ Rango:'GES6' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $nombre_establecimiento = $row1['nombre_establecimiento'];
                $id = $row1['id'];
                $total_hombres   = $row1['hombres'];
                $total_mujeres   = $row1['mujeres'];
                $total = $row1['total'];
                $total_indicador = $row1['total_indicador'];
                $porcentaje = number_format(($total_indicador*100/$total),0,'.','');

                $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_ges6 .= ",$id:$porcentaje";
            }
            $rango_ges6 .= "},";

        }
    }
}



?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  data_cero = [
            <?php echo $rango_cero; ?>
        ];

        var  data_ges6 = [
            <?php echo $rango_ges6; ?>
        ];

        var toolTipCustomFormatFn = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = data_cero[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_indicador+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                 '</DIV>';
        };

        // prepare jqxChart settings
        var settings_cero = {
            title: 'CERO',
            description: "COBERTURA DENTAL",
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data_cero,
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
                        toolTipFormatFunction: toolTipCustomFormatFn,
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
                            <?php echo $series_cero; ?>
                        ]
                    }
                ]
        };
        var settings_ges6 = {
            title: 'GES6',
            description: "COBERTURA DENTAL",
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data_ges6,
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
                        toolTipFormatFunction: toolTipCustomFormatFn,
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
                            <?php echo $series_ges6; ?>
                        ]
                    }
                ]
        };

        // setup the chart
        $('#cobertura_cero').jqxChart(settings_cero);
        $('#cobertura_ges6').jqxChart(settings_ges6);

        function myEventHandler(event) {
            var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";

            //$('#eventText').html(eventData);
            alertaLateral(eventData);
        };
        $('#cobertura_cero').on('click', function (event) {
            if (event.args)
                myEventHandler(event);

        });
        $('#cobertura_ges6').on('click', function (event) {
            if (event.args)
                myEventHandler(event);

        });
    });
</script>
<div class="row">
    <div class="col l12 m12 s12">
        <div class="col l6 m12 s12">
            <div id='cobertura_cero' style=" height: 400px;width: 400px;"></div>
        </div>
        <div class="col l6 m12 s12">
            <div id='cobertura_ges6' style="height: 400px;width: 400px;"></div>
        </div>
    </div>
</div>
