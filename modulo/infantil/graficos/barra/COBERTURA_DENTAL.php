<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

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

$edad_cero = " and persona.edad_total>=6  ";
$edad_ges6 = " and persona.edad_total>=(12*6)  ";

$sql_total = "select COUNT(*) as total from paciente_establecimiento 
              inner join persona using(rut) where m_infancia='SI' $edad_cero; ";
$row_total = mysql_fetch_array(mysql_query($sql_total));
if($row_total){
    $general_cero = $row_total['total'];

}
$sql_total = "select COUNT(*) as total from paciente_establecimiento 
              inner join persona using(rut) where m_infancia='SI' $edad_ges6; ";
$row_total = mysql_fetch_array(mysql_query($sql_total));
if($row_total){
    $general_ges6 = $row_total['total'];
}
$sql_json = "select * from persona inner join paciente_establecimiento using(rut) 
              where m_infancia='SI'";
$res_json = mysql_query($sql_json);
$coma = 0;
$json = '';
while($row_json = mysql_fetch_array($res_json)){
    $persona = new persona($row_json['rut']);

    if($persona->total_meses>6){
        //cero
        $sql11 = "select * from paciente_dental where rut='$persona->rut' limit 1";
        $row11 = mysql_fetch_array(mysql_query($sql11));
        if($row11){
            $cero = $row11['cero'];
            if($cero==''){
                $indicador = 'CERO [PENDIENTE]';
            }else{
                if($cero=='NO'){
                    $indicador = 'CERO [PENDIENTE]';
                }else{
                    $indicador = 'CERO ['.$cero.']';
                }
            }
        }else{
            $indicador = 'CERO [PENDIENTE]';
        }

        if($coma>0){
            $json .= ',';
        }
        $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","CONTACTO":"'.$persona->getContacto().'","NOMBRE":"'.limpiaCadena($persona->nombre).'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$indicador.'","EDAD":"'.$persona->edad_total.'"}';
        $coma++;
    }


    if($persona->total_meses>(6*12)){
        //ges6
        $sql11 = "select * from paciente_dental where rut='$persona->rut' limit 1";
        $row11 = mysql_fetch_array(mysql_query($sql11));
        if($row11){
            $ges6 = $row11['ges6'];
            if($ges6==''){
                $indicador = 'GES6 [PENDIENTE]';
            }else{
                if($ges6=='NO'){
                    $indicador = 'GES6 [PENDIENTE]';
                }else{
                    $indicador = 'GES6 ['.$ges6.']';
                }
            }
        }else{
            $indicador = 'GES6 [PENDIENTE]';
        }
        if($coma>0){
            $json .= ',';
        }

        $json .= '{"RUT":"'.$persona->rut.'","CONTACTO":"'.$persona->getContacto().'","NOMBRE":"'.limpiaCadena($persona->nombre).'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$indicador.'","EDAD":"'.$persona->edad_total.'"}';
        $coma++;

    }

}


if($comunal==true){
    //para todos los sectores comunales

    //cero
    $sql1 = "select 
                                    sum(persona.rut!='' $edad_cero) AS TOTAL_PACIENTES_CERO,
                                    sum(UPPER(paciente_dental.cero)='SI' $edad_cero) AS COBERTURA_CERO,
                                    sum(persona.rut!='' $edad_ges6) AS TOTAL_PACIENTES_GES6,
                                    sum(UPPER(paciente_dental.ges6)='SI' $edad_ges6) AS COBERTURA_GES6,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' $edad_cero) as hombres_cero,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' $edad_cero) as mujeres_cero,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' $edad_ges6) as hombres_ges6,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' $edad_ges6) as mujeres_ges6 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and paciente_establecimiento.m_infancia='SI' 
                                
                                    ";

    $row1= mysql_fetch_array(mysql_query($sql1));

    $total_pacientes_cero           = $row1['TOTAL_PACIENTES_CERO']!='' ?$row1['TOTAL_PACIENTES_CERO']:0;
    $total_pacientes_ges6           = $row1['TOTAL_PACIENTES_GES6']!='' ?$row1['TOTAL_PACIENTES_GES6']:0;

    $cobertura_cero   = $row1['COBERTURA_CERO']!='' ?$row1['COBERTURA_CERO']:0;;
    $cobertura_ges6   = $row1['COBERTURA_GES6']!='' ?$row1['COBERTURA_GES6']:0;;

    $hombres_cero   = $row1['hombres_cero']!='' ?$row1['hombres_cero']:0;;
    $hombres_ges6   = $row1['hombres_ges6']!='' ?$row1['hombres_ges6']:0;;

    $mujeres_cero   = $row1['mujeres_cero']!='' ?$row1['mujeres_cero']:0;;
    $mujeres_ges6   = $row1['mujeres_ges6']!='' ?$row1['mujeres_ges6']:0;;

    $porcentaje_cero        = number_format(($cobertura_cero*100/$total_pacientes_cero),0,'.','');
    $porcentaje_ges6        = number_format(($cobertura_ges6*100/$total_pacientes_ges6),0,'.','');

    $rango_cero .= "{ Rango:'GENERAL',GENERAL: ".$porcentaje_cero."},";
    $series_cero .=" { dataField: 'GENERAL', displayText: 'GENERAL',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$cobertura_cero,hombres:$hombres_cero,mujeres:$mujeres_cero,general_cero:$general_cero},";

    $rango_ges6 .= "{ Rango:'GENERAL',GENERAL: ".$porcentaje_ges6."},";
    $series_ges6 .=" { dataField: 'GENERAL', displayText: 'GENERAL',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_ges6,total_indicador:$cobertura_ges6,hombres:$hombres_ges6,mujeres:$mujeres_ges6,general_cero:$general_ges6},";






}else{
    if($establecimientos==true){
        //para todos los establecimientos pero segun el sector comunal seleccionado
        $sql1 = "select sector_comunal.nombre_sector_comunal as nombre_base,
                        sector_comunal.id_sector_comunal as id,
                        sum(persona.rut!='' $edad_cero) AS TOTAL_PACIENTES_CERO,
                                    sum(UPPER(paciente_dental.cero)='SI' $edad_cero) AS COBERTURA_CERO,
                                    sum(persona.rut!='' $edad_ges6) AS TOTAL_PACIENTES_GES6,
                                    sum(UPPER(paciente_dental.ges6)='SI' $edad_ges6) AS COBERTURA_GES6,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' $edad_cero) as hombres_cero,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' $edad_cero) as mujeres_cero,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' $edad_ges6) as hombres_ges6,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' $edad_ges6) as mujeres_ges6
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on paciente_dental.rut=persona.rut
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'  
                                    and paciente_establecimiento.m_infancia='SI' 
                                    
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

//        echo $sql1;
        $res1 = mysql_query($sql1);

        $rango_cero .= "{ Rango:'CERO' ";
        $rango_ges6 .= "{ Rango:'GES6' ";
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];
            $id = $row1['id']; //sector

            $total_pacientes_cero           = $row1['TOTAL_PACIENTES_CERO']!='' ?$row1['TOTAL_PACIENTES_CERO']:0;
            $total_pacientes_ges6           = $row1['TOTAL_PACIENTES_GES6']!='' ?$row1['TOTAL_PACIENTES_GES6']:0;

            $cobertura_cero   = $row1['COBERTURA_CERO']!='' ?$row1['COBERTURA_CERO']:0;;
            $cobertura_ges6   = $row1['COBERTURA_GES6']!='' ?$row1['COBERTURA_GES6']:0;;

            $hombres_cero   = $row1['hombres_cero']!='' ?$row1['hombres_cero']:0;;
            $hombres_ges6   = $row1['hombres_ges6']!='' ?$row1['hombres_ges6']:0;;

            $mujeres_cero   = $row1['mujeres_cero']!='' ?$row1['mujeres_cero']:0;;
            $mujeres_ges6   = $row1['mujeres_ges6']!='' ?$row1['mujeres_ges6']:0;;

            $porcentaje_cero        = number_format(($cobertura_cero*100/$total_pacientes_cero),0,'.','');
            $porcentaje_ges6        = number_format(($cobertura_ges6*100/$total_pacientes_ges6),0,'.','');


            $series_cero .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$cobertura_cero,hombres:$hombres_cero,mujeres:$mujeres_cero,general_cero:$general_cero},";
            $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_ges6,total_indicador:$cobertura_ges6,hombres:$hombres_ges6,mujeres:$mujeres_ges6,general_cero:$general_ges6},";
            $rango_cero .= ",$id:$porcentaje_cero";
            $rango_ges6 .= ",$id:$porcentaje_ges6";
        }
        $rango_cero .= "},";
        $rango_ges6 .= "},";





    }else{
        if($sectores==true){
            //para todos los sectores internos
            $sql1 = "select  centros_internos.nombre_centro_interno as nombre_base
                                    ,centros_internos.id_centro_interno as id,
                                    sum(persona.rut!='' $edad_cero) AS TOTAL_PACIENTES_CERO,
                                    sum(UPPER(paciente_dental.cero)='SI' $edad_cero) AS COBERTURA_CERO,
                                    sum(persona.rut!='' $edad_ges6) AS TOTAL_PACIENTES_GES6,
                                    sum(UPPER(paciente_dental.ges6)='SI' $edad_ges6) AS COBERTURA_GES6,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' $edad_cero) as hombres_cero,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' $edad_cero) as mujeres_cero,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' $edad_ges6) as hombres_ges6,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' $edad_ges6) as mujeres_ges6
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and paciente_establecimiento.m_infancia='SI' 
                                    $edad_cero   
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
//            echo $sql1;

            $res1 = mysql_query($sql1);

            $rango_cero .= "{ Rango:'CERO' ";
            $rango_ges6 .= "{ Rango:'GES6' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $id = $row1['id']; //sector

                $total_pacientes_cero           = $row1['TOTAL_PACIENTES_CERO']!='' ?$row1['TOTAL_PACIENTES_CERO']:0;
                $total_pacientes_ges6           = $row1['TOTAL_PACIENTES_GES6']!='' ?$row1['TOTAL_PACIENTES_GES6']:0;

                $cobertura_cero   = $row1['COBERTURA_CERO']!='' ?$row1['COBERTURA_CERO']:0;;
                $cobertura_ges6   = $row1['COBERTURA_GES6']!='' ?$row1['COBERTURA_GES6']:0;;

                $hombres_cero   = $row1['hombres_cero']!='' ?$row1['hombres_cero']:0;;
                $hombres_ges6   = $row1['hombres_ges6']!='' ?$row1['hombres_ges6']:0;;

                $mujeres_cero   = $row1['mujeres_cero']!='' ?$row1['mujeres_cero']:0;;
                $mujeres_ges6   = $row1['mujeres_ges6']!='' ?$row1['mujeres_ges6']:0;;

                $porcentaje_cero        = number_format(($cobertura_cero*100/$total_pacientes_cero),0,'.','');
                $porcentaje_ges6        = number_format(($cobertura_ges6*100/$total_pacientes_ges6),0,'.','');


                $series_cero .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$cobertura_cero,hombres:$hombres_cero,mujeres:$mujeres_cero,general_cero:$general_cero},";
                $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_ges6,total_indicador:$cobertura_ges6,hombres:$hombres_ges6,mujeres:$mujeres_ges6,general_cero:$general_ges6},";
                $rango_cero .= ",$id:$porcentaje_cero";
                $rango_ges6 .= ",$id:$porcentaje_ges6";
            }
            $rango_cero .= "},";
            $rango_ges6 .= "},";




        }else{


            $sql1 = "select centros_internos.nombre_centro_interno as nombre_base
                                    ,sectores_centros_internos.id_sector_centro_interno as id,
                                    sum(persona.rut!='' $edad_cero) AS TOTAL_PACIENTES_CERO,
                                    sum(UPPER(paciente_dental.cero)='SI' $edad_cero) AS COBERTURA_CERO,
                                    sum(persona.rut!='' $edad_ges6) AS TOTAL_PACIENTES_GES6,
                                    sum(UPPER(paciente_dental.ges6)='SI' $edad_ges6) AS COBERTURA_GES6,
                                    SUM(persona.sexo='M' AND paciente_dental.cero='SI' $edad_cero) as hombres_cero,
                                    sum(persona.sexo='F' AND paciente_dental.cero='SI' $edad_cero) as mujeres_cero,
                                    SUM(persona.sexo='M' AND paciente_dental.ges6='SI' $edad_ges6) as hombres_ges6,
                                    sum(persona.sexo='F' AND paciente_dental.ges6='SI' $edad_ges6) as mujeres_ges6
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_dental on persona.rut=paciente_dental.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and paciente_establecimiento.m_infancia='SI'  
                                    $edad_cero   
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
            $rango_ges6 .= "{ Rango:'GES6' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base'];
                $id = $row1['id']; //sector

                $total_pacientes_cero           = $row1['TOTAL_PACIENTES_CERO']!='' ?$row1['TOTAL_PACIENTES_CERO']:0;
                $total_pacientes_ges6           = $row1['TOTAL_PACIENTES_GES6']!='' ?$row1['TOTAL_PACIENTES_GES6']:0;

                $cobertura_cero   = $row1['COBERTURA_CERO']!='' ?$row1['COBERTURA_CERO']:0;;
                $cobertura_ges6   = $row1['COBERTURA_GES6']!='' ?$row1['COBERTURA_GES6']:0;;

                $hombres_cero   = $row1['hombres_cero']!='' ?$row1['hombres_cero']:0;;
                $hombres_ges6   = $row1['hombres_ges6']!='' ?$row1['hombres_ges6']:0;;

                $mujeres_cero   = $row1['mujeres_cero']!='' ?$row1['mujeres_cero']:0;;
                $mujeres_ges6   = $row1['mujeres_ges6']!='' ?$row1['mujeres_ges6']:0;;

                $porcentaje_cero        = number_format(($cobertura_cero*100/$total_pacientes_cero),0,'.','');
                $porcentaje_ges6        = number_format(($cobertura_ges6*100/$total_pacientes_ges6),0,'.','');


                $series_cero .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_cero,total_indicador:$cobertura_cero,hombres:$hombres_cero,mujeres:$mujeres_cero,general_cero:$general_cero},";
                $series_ges6 .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes_ges6,total_indicador:$cobertura_ges6,hombres:$hombres_ges6,mujeres:$mujeres_ges6,general_cero:$general_ges6},";
                $rango_cero .= ",$id:$porcentaje_cero";
                $rango_ges6 .= ",$id:$porcentaje_ges6";
            }
            $rango_cero .= "},";
            $rango_ges6 .= "},";



        }
    }
}
//echo $sql1;


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

        var tooltips_cero = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = data_cero[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_indicador+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                'GENERAL: <b>' +serie.general_cero+'</b><br />'+
                '</DIV>';
        };
        var tooltips_ges6 = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = data_cero[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_indicador+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                'GENERAL: <b>' +serie.general_ges6+'</b><br />'+
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
                        toolTipFormatFunction: tooltips_cero,
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
                        toolTipFormatFunction: tooltips_ges6,
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

        // function myEventHandler(event) {
        //     var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";
        //
        //     //$('#eventText').html(eventData);
        //     alertaLateral(eventData);
        // };
        // $('#cobertura_cero').on('click', function (event) {
        //     if (event.args)
        //         myEventHandler(event);
        //
        // });
        // $('#cobertura_ges6').on('click', function (event) {
        //     if (event.args)
        //         myEventHandler(event);
        //
        // });



        //GRID
        var data = '[<?php echo $json; ?>]';
        var source =
            {
                datatype: "json",
                datafields: [

                    { name: 'IR', type: 'string' },
                    { name: 'RUT', type: 'string' },
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'CONTACTO', type: 'string' },
                    { name: 'EDAD', type: 'string' },
                    { name: 'COMUNAL', type: 'string' },
                    { name: 'ESTABLECIMIENTO', type: 'string' },
                    { name: 'SECTOR_INTERNO', type: 'string' },
                    { name: 'INDICADOR', type: 'string' },

                ],
                localdata: data
            };
        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ''+
                '<a onclick="loadMenu_Infantil(\'menu_1\',\'registro_tarjetero\',\''+value+'\')"  style="color: white;" >' +
                '<i class="mdi-hardware-keyboard-return"></i> IR' +
                '</a>';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "eh-open_principal white-text cursor_cell_link center";

        }
        var cellEdadAnios = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            var anios = parseInt(value/12);
            var meses = value%12;
            if(anios===0){
                return  "<div style='padding-left: 10px;'>"+meses+" Meses</div>";
            }else{
                return  "<div style='padding-left: 10px;'>"+anios + " Años "+meses+" Meses</div>";
            }

        }

        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#table_grid").jqxGrid(
            {
                width: '98%',
                height:400,
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                editable: true,
                statusbarheight: 30,
                showaggregates: true,
                theme: 'eh-open',
                selectionmode: 'multiplecellsextended',
                columns: [
                    { text: 'IR', dataField: 'IR',
                        cellclassname:cellIrClass,
                        cellsrenderer:cellLinkRegistroTarjetero,
                        cellsalign: 'center', width: 100 },
                    { text: 'RUT', dataField: 'RUT', cellsalign: 'right', width: 150 },
                    { text: 'NOMBRE COMPLETO', dataField: 'NOMBRE' ,
                        width: 350,
                        aggregates: ['count'],aggregatesrenderer: function (aggregates, column, element, summaryData) {
                            var renderstring = "<div  style='float: left; width: 100%; height: 100%;'>";
                            $.each(aggregates, function (key, value) {
                                var name = 'Total Pacientes';
                                renderstring += '<div style="; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                            });
                            renderstring += "</div>";
                            return renderstring;
                        }},
                    { text: 'EDAD', dataField: 'EDAD', cellsalign: 'left', width: 250,cellsrenderer:cellEdadAnios},
                    { text: 'CONTACTO', dataField: 'CONTACTO', cellsalign: 'left', width: 250},
                    { text: 'INDICADOR', dataField: 'INDICADOR', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'S. COMUNAL', dataField: 'COMUNAL', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'ESTABLECIMIENTO', dataField: 'ESTABLECIMIENTO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'SECTOR_INTERNO', dataField: 'SECTOR_INTERNO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },

                ]
            });
        $("#excelExport").click(function () {

            $("#table_grid").jqxGrid('exportdata', 'xls', 'grid', true,null,true, 'excel/save-file.php');

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
<style type="text/css">
    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 800px) { /* Aquí van los estilos */
        #tabla_grilla{
            display: none;;
        }
        button{
            display: none;
        }
    }
    a{
        border: none;
        text-decoration: none;
    }
    a:hover{
        background-color: #438eb9;
    }

</style>
<div class="card-panel" id="tabla_grilla">
    <div class="row">
        <div class="col l6 m12 s12">
            <button class="btn" id="excelExport" >
                <i class="mdi-action-open-in-new left"></i>
                EXPORTAR EXCEL
            </button>
        </div>
    </div>
    <div class="row">
        <div id="table_grid"></div>
    </div>
</div>
