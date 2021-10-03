<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];


$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);




list($atributo,$tabla,$tabla_historial) = explode("|",$_POST['atributo']);

if($atributo=='patologia_dm'){
    if($_POST['edad']){
        list($rango_edad,$estado) = explode("#",$_POST['edad']);
    }else{
        list($rango_edad,$estado) = explode("#",'<(80*12)#< 7%');

    }
}else{
    if($atributo=='patologia_hta'){
        if($_POST['edad']){
            list($rango_edad,$estado) = explode("#",$_POST['edad']);
        }else{
            list($rango_edad,$estado) = explode("#",">(12*15) #%/90 MMHG%");

        }

    }
}


$filtro_edad = '';
if($rango_edad!=''){
    if(strpos($rango_edad,'@')){
        $rango_edad_array = explode("@",$rango_edad);
        foreach ($rango_edad_array as $aa => $rango_edad){
            if($rango_edad!=''){
                $filtro_edad .= ' and persona.edad_total '.$rango_edad;
                if($atributo=='patologia_dm'){
                    $estado_sql = "'> %'";
                }else{
                    $estado_sql = "like '%/90 MMHG%'";
                }

            }
        }
    }else{
        $filtro_edad = ' and persona.edad_total '.$rango_edad;
        $estado_sql = "like '$estado' ";
    }
}else{
    $filtro_edad = '';
}




$TITULO_GRAFICO = 'COMPENSACIÓN';

$filtro = '';

$sql_column = " sum($atributo='SI' and  TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)<365) as total_vigente,
                sum($atributo='SI' and  TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)>=365) as total_no_vigente,
                sum($atributo='SI' and sexo='M' and TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)<365) as hombres_vigentes,
                sum($atributo='SI' and sexo='F' and TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)<365) as mujeres_vigentes,
                sum($atributo='SI' and sexo='M' and TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)>365) as hombres_no_vigentes,
                sum($atributo='SI' and sexo='F' and TIMESTAMPDIFF(DAY,$tabla_historial.fecha_registro,CURRENT_DATE)>365) as mujeres_no_vigentes ";



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
$json = '';
$json_coma = 0;


if($comunal==true){
    if($atributo=='patologia_dm'){
        $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and persona.rut!='' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and patologia_dm='SI' 
                                    $filtro_edad  
                                     ";
    }else{
        if($atributo=='patologia_hta'){
            $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and persona.rut!='' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and patologia_hta='SI' 
                                    $filtro_edad  
                                     ";
        }
    }




    $res_0  = mysql_query($sql_0);
    $total_pacientes = 0;
    $total_pendiente = 0;
    $total_indicador = 0;
    $total_vigente = 0;
    $hombres_pendientes = 0;
    $mujeres_pendientes = 0;
    $hombres = 0;
    $mujeres = 0;
    while ($row_0 = mysql_fetch_array($res_0)){
        $persona = new persona($row_0['rut']);
        if($json_coma>0){
            $json.=',';
        }
        if($atributo=='patologia_dm'){
            $sql_1 = "select *,valor as valor_json from historial_diabetes_mellitus
                        inner join persona on historial_diabetes_mellitus.rut=persona.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and historial_diabetes_mellitus.fecha_registro is not null
                  and indicador like 'hba1c%' AND valor $estado_sql
                  and TIMESTAMPDIFF(DAY,fecha_registro,CURRENT_DATE)<=365 
                  limit 1";

        }else{
            $sql_1 = "select *,valor as valor_json from historial_parametros_pscv
                        inner join persona on historial_parametros_pscv.rut=persona.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and indicador='pa' and valor $estado_sql
                  and historial_parametros_pscv.fecha_registro is not null 
                  and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<=365 
                  order by id_historial desc 
                  limit 1";
//            echo $sql_1;
        }
        //conocemos si tiene evaluacion durante el ultimo año
        $row_1 = mysql_fetch_array(mysql_query($sql_1));
        $fecha_json = '';
        if($row_1){
            //califica segun edad indicador y valor
            $valor = $estado;
            $valor_json = $row_1['valor_json'];
            $fecha_json = $row_1['fecha_registro'];
            if($fecha_json==''){
                $valor_json = 'PENDIENTE';
            }else{
                $total_indicador++;
                if($persona->sexo=='M'){
                    $hombres++;
                }else{
                    $mujeres++;
                }
                $estado_json = 'VIGENTE';
                $valor_json = $row_1['valor_json'];
            }
        }else{

            $estado_json = 'PENDIENTE';
            $valor_json = '';
        }

        $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$atributo.'","FECHA":"'.$fecha_json.'","VALOR":"'.$valor_json.'","ESTADO":"'.$estado_json.'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
        $total_pacientes++;
        $json_coma++;
    }


    $porcentaje_indicador = number_format(($total_indicador*100/$total_pacientes),0,'.','');

    $rango .= "\n{ Rango:'GENERAL',GENERAL: ".$porcentaje_indicador."},";
    $series .=" \n{ dataField: 'GENERAL', displayText: '$estado',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total_pacientes,total_indicador:$total_indicador,hombres:$hombres,mujeres:$mujeres},";


}else{
    if($establecimientos==true){

        if($atributo=='patologia_dm'){
            $sql1 = "select sector_comunal.nombre_sector_comunal as nombre_base,
                        sector_comunal.id_sector_comunal as id 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and patologia_dm='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad  
                                    AND (";
        }else{
            if($atributo=='patologia_hta'){
                $sql1 = "select sector_comunal.nombre_sector_comunal as nombre_base,
                        sector_comunal.id_sector_comunal as id 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and patologia_hta='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad  
                                    AND (";

            }
        }

        //para todos los establecimientos pero segun el sector comunal seleccionado


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
        $rango .= "{ Rango:'".str_replace("_"," ",$atributo)."' ";
        $dato = 0;
        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base']; // columna
            $id = trim($row1['id']); // id_sector_comunal

            //total pacientes
            if($atributo=='patologia_dm'){
                $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and persona.rut!='' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and patologia_dm='SI' 
                                    and sector_comunal.id_sector_comunal='$id' 
                                    $filtro_edad ";
            }else{
                if($atributo=='patologia_hta'){
                    $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and persona.rut!='' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and patologia_hta='SI' 
                                    and sector_comunal.id_sector_comunal='$id' 
                                    $filtro_edad ";

                }
            }

            $total_pacientes = 0;
            $total_indicador = 0;

            $hombres = 0;
            $mujeres = 0;

            $res_0  = mysql_query($sql_0);


            while ($row_0 = mysql_fetch_array($res_0)){
                $persona = new persona($row_0['rut']);
                if($json_coma>0){
                    $json.=',';
                }

                if($atributo=='patologia_dm'){
                    $sql_1 = "select *,valor as valor_json from historial_diabetes_mellitus
                        inner join persona on historial_diabetes_mellitus.rut=persona.rut
                        inner join paciente_pscv on persona.rut=paciente_pscv.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and historial_diabetes_mellitus.fecha_registro is not null 
                  and indicador like 'hba1c%' AND valor $estado_sql
                  and TIMESTAMPDIFF(DAY,fecha_registro,CURRENT_DATE)<365 
                  limit 1";
                }else{
                    $sql_1 = "select *,valor as valor_json from historial_parametros_pscv
                        inner join persona on historial_parametros_pscv.rut=persona.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and indicador='pa' and valor $estado_sql
                  and historial_parametros_pscv.fecha_registro is not null 
                  and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<=365 
                  order by id_historial desc 
                  limit 1";
                }
                $row_1 = mysql_fetch_array(mysql_query($sql_1));
                $fecha_json = '';
                $valor_json = '';
                $estado_json = '';
                if($row_1){
                    //califica segun edad indicador y valor
                    $valor = $estado;
                    $valor_json = $row_1['valor_json'];
                    $fecha_json = $row_1['fecha_registro'];
                    if($fecha_json==''){
                        $valor_json = '';
                        $estado_json = 'PENDIENTE';
                    }else{
                        $total_indicador++;
                        if($persona->sexo=='M'){
                            $hombres++;
                        }else{
                            $mujeres++;
                        }
                        $estado_json = 'VIGENTE';
                        $valor_json = $row_1['valor_json'];
                    }
                }else{
                    $valor_json = '';
                    $estado_json = 'PENDIENTE';
                }
                //preparamos JSON
                $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$atributo.'","FECHA":"'.$fecha_json.'","VALOR":"'.$valor_json.'","ESTADO":"'.$estado_json.'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
                $total_pacientes++;
                $json_coma++;
            }
            $porcentaje_indicador = number_format(($total_indicador*100/$total_pacientes),0,'.','');

            $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total_pacientes,total_indicador:$total_indicador,hombres:$hombres,mujeres:$mujeres},";
            $rango .= ", $id:$porcentaje_indicador";

        }
        $rango .= "},";


    }else{
        if($sectores==true){
            //para todos los centros interno
            if($atributo=='patologia_dm'){
                $sql1 = "select 
                                    centros_internos.nombre_centro_interno as nombre_base,
                                    sector_comunal.nombre_sector_comunal as nombre_establecimiento,
                                    centros_internos.id_centro_interno as id
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal       
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and patologia_dm='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad  
                                    and (";
            }else{
                if($atributo=='patologia_hta'){
                    $sql1 = "select 
                                    centros_internos.nombre_centro_interno as nombre_base,
                                    sector_comunal.nombre_sector_comunal as nombre_establecimiento,
                                    centros_internos.id_centro_interno as id
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal       
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' and patologia_hta='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad  
                                    and (";

                }
            }

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

            $rango .= "{ Rango:'".str_replace("_"," ",$atributo)."' ";

            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]"; // columna
                $id = trim($row1['id']); // id_sector_comunal
                //total pacientes
                if($atributo=='patologia_dm'){
                    $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and persona.rut!='' 
                                    and patologia_dm='SI' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    and centros_internos.id_centro_interno='$id' 
                                    $filtro_edad ";
                }else{
                    if($atributo=='patologia_hta'){
                        $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and persona.rut!='' 
                                    and patologia_hta='SI' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    and centros_internos.id_centro_interno='$id' 
                                    $filtro_edad ";

                    }
                }

                $res_0  = mysql_query($sql_0);

                $total_pacientes = 0;
                $total_indicador = 0;
                $hombres = 0;
                $mujeres = 0;
                while ($row_0 = mysql_fetch_array($res_0)){
                    $persona = new persona($row_0['rut']);
                    if($json_coma>0){
                        $json.=',';
                    }

                    if($atributo=='patologia_dm'){
                        $sql_1 = "select *,valor as valor_json from historial_diabetes_mellitus
                                            inner join persona on historial_diabetes_mellitus.rut=persona.rut
                                            inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                      where persona.rut='$persona->rut' $filtro_edad
                                      and historial_diabetes_mellitus.fecha_registro is not null 
                                      and indicador like 'hba1c%' AND valor $estado_sql
                                      and TIMESTAMPDIFF(DAY,fecha_registro,CURRENT_DATE)<365 
                                      limit 1";

                    }else{
                        $sql_1 = "select *,valor as valor_json from historial_parametros_pscv
                        inner join persona on historial_parametros_pscv.rut=persona.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and indicador='pa' and valor $estado_sql
                  and historial_parametros_pscv.fecha_registro is not null 
                  and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<=365 
                  order by id_historial desc 
                  limit 1";
                    }
                    $row_1 = mysql_fetch_array(mysql_query($sql_1));
                    $fecha_json = '';
                    if($row_1){
                        //califica segun edad indicador y valor
                        $valor = $estado;
                        $valor_json = $row_1['valor_json'];
                        $fecha_json = $row_1['fecha_registro'];
                        if($fecha_json==''){
                            $valor_json = '';
                            $valor_json = 'PENDIENTE';
                        }else{
                            $total_indicador++;
                            if($persona->sexo=='M'){
                                $hombres++;
                            }else{
                                $mujeres++;
                            }
                            $estado_json = 'VIGENTE';
                            $valor_json = $row_1['valor_json'];
                        }
                    }else{
                        $valor_json = '';
                        $estado_json = 'PENDIENTE';
                    }

                    //preparamos JSON
                    $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$atributo.'","FECHA":"'.$fecha_json.'","VALOR":"'.$valor_json.'","ESTADO":"'.$estado_json.'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
                    $total_pacientes++;
                    $json_coma++;
                }


                $porcentaje_indicador = number_format(($total_indicador*100/$total_pacientes),0,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total_pacientes,total_indicador:$total_indicador,hombres:$hombres,mujeres:$mujeres},";
                $rango .= ", $id:$porcentaje_indicador";

            }
            $rango .= "},";





        }else{
            //para todos los sectores internos seleccionados
            if($atributo=='patologia_dm'){
                $sql1 = "select 
                                    sectores_centros_internos.nombre_sector_interno as nombre_base,
                                    sectores_centros_internos.id_sector_centro_interno as id,
                                    centros_internos.nombre_centro_interno as nombre_establecimiento,
                                    persona.rut as rut
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut 
                                    where m_cardiovascular='SI' and patologia_dm='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad 
                                    and (";
            }else{
                if($atributo=='patologia_hta'){
                    $sql1 = "select 
                                    sectores_centros_internos.nombre_sector_interno as nombre_base,
                                    sectores_centros_internos.id_sector_centro_interno as id,
                                    centros_internos.nombre_centro_interno as nombre_establecimiento,
                                    persona.rut as rut
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut 
                                    where m_cardiovascular='SI' and patologia_hta='SI'
                                    AND paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad 
                                    and (";

                }
            }

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

            $rango .= "{ Rango:'".str_replace("_"," ",$atributo)."' ";
            while($row1 = mysql_fetch_array($res1)){

                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]";
                $id = trim($row1['id']); // id_sector_comunal
                //total pacientes
                if($atributo=='patologia_dm'){
                    $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and persona.rut!='' 
                                    and patologia_dm='SI' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    and sectores_centros_internos.id_sector_centro_interno='$id' 
                                    $filtro_edad ";
                }else{
                    if($atributo=='patologia_hta'){
                        $sql_0 = "select *  from persona 
                                    inner join paciente_establecimiento using(rut) 
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                    where m_cardiovascular='SI' 
                                    and persona.rut!='' 
                                    and patologia_hta='SI' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    and sectores_centros_internos.id_sector_centro_interno='$id' 
                                    $filtro_edad ";

                    }
                }
                $res_0  = mysql_query($sql_0);

                $total_pacientes = 0;
                $total_indicador = 0;
                $hombres = 0;
                $mujeres = 0;

                while ($row_0 = mysql_fetch_array($res_0)){
                    $persona = new persona($row_0['rut']);
                    if($atributo=='patologia_dm'){
                        $sql_1 = "select *,valor as valor_json from historial_diabetes_mellitus
                                            inner join persona on historial_diabetes_mellitus.rut=persona.rut
                                            inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                      where persona.rut='$persona->rut' $filtro_edad
                                      and historial_diabetes_mellitus.fecha_registro is not null 
                                      and indicador like 'hba1c%' AND valor $estado_sql
                                      and TIMESTAMPDIFF(DAY,fecha_registro,CURRENT_DATE)<365 
                                      limit 1";

                    }else{
                        $sql_1 = "select *,valor as valor_json from historial_parametros_pscv
                        inner join persona on historial_parametros_pscv.rut=persona.rut
                  where persona.rut='$persona->rut' $filtro_edad
                  and indicador='pa' and valor $estado_sql
                  and historial_parametros_pscv.fecha_registro is not null 
                  and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<=365 
                  order by id_historial desc 
                  limit 1";
                    }

                    $row_1 = mysql_fetch_array(mysql_query($sql_1));
                    $fecha_json = '';
                    if($row_1){
                        //califica segun edad indicador y valor
                        $valor = $estado;
                        $valor_json = $row_1['valor_json'];
                        $fecha_json = $row_1['fecha_registro'];
                        if($fecha_json==''){
                            $valor_json = '';
                            $estado_json = 'PENDIENTE';
                        }else{
                            $total_indicador++;
                            if($persona->sexo=='M'){
                                $hombres++;
                            }else{
                                $mujeres++;
                            }
                            $estado_json = 'VIGENTE';
                            $valor_json = $row_1['valor_json'];
                        }
                    }else{
                        $valor_json = '';
                        $estado_json = 'PENDIENTE';
                    }


                    $total_pacientes++;
                    //preparamos JSON
                    if($json_coma>0){
                        $json.=',';
                    }
                    $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$atributo.'","FECHA":"'.$fecha_json.'","VALOR":"'.$valor_json.'","ESTADO":"'.$estado_json.'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
                    $json_coma++;
                }

                $porcentaje_indicador = number_format(($total_indicador*100/$total_pacientes),0,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total_pacientes,total_indicador:$total_indicador,hombres:$hombres,mujeres:$mujeres},";
                $rango .= ", $id:$porcentaje_indicador";

            }
            $rango .= "},";


        }
    }
}

$estado = $estado=='' ? 'PENDIENTE':$estado;

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
        var setting = {
            title: 'COMPENSACIÓN',
            description: '<?php echo strtoupper(str_replace("_"," ", $atributo)); ?>',
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
        $('#pscv_cobertura').jqxChart(setting);

        function myEventHandler(event) {
            var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";

            //$('#eventText').html(eventData);
            alertaLateral(eventData);
        };



        //grid
        var data = '[<?php echo $json; ?>]';
        var source =
            {
                datatype: "json",
                datafields: [

                    { name: 'IR', type: 'string' },
                    { name: 'RUT', type: 'string' },
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'EDAD', type: 'string' },
                    { name: 'anios', type: 'string' },
                    { name: 'meses', type: 'string' },
                    { name: 'dias', type: 'string' },
                    { name: 'COMUNAL', type: 'string' },
                    { name: 'ESTABLECIMIENTO', type: 'string' },
                    { name: 'ESTADO', type: 'string' },
                    { name: 'SECTOR_INTERNO', type: 'string' },
                    { name: 'CONTACTO', type: 'string' },
                    { name: 'FECHA', type: 'string' },
                    { name: 'INDICADOR', type: 'string' },
                    { name: 'VALOR', type: 'string' },

                ],
                localdata: data
            };
        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<i onclick="loadMenu_CardioVascular(\'menu_1\',\'registro_atencion\',\''+value+'\')" class="mdi-hardware-keyboard-return"></i> IR';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "eh-open_principal white-text cursor_cell_link center";

        }

        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#table_grid").jqxGrid(
            {
                width: '95%',
                height:400,
                theme: 'eh-open',
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                statusbarheight: 30,
                showaggregates: true,
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
                    { text: 'AÑO', datafield: 'anios', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'MES', datafield: 'meses', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'DIA', datafield: 'dias', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: '<?php echo $TITULO_GRAFICO; ?>', dataField: 'INDICADOR', cellsalign: 'center', width: 250,filtertype: 'checkedlist' },
                    { text: 'ESTADO', dataField: 'ESTADO', cellsalign: 'center', width: 150,filtertype: 'checkedlist' },
                    { text: 'VALOR', dataField: 'VALOR', cellsalign: 'center', width: 150,filtertype: 'checkedlist' },
                    { text: 'FECHA', dataField: 'FECHA', cellsalign: 'center', width: 110},
                    { text: 'S. COMUNAL', dataField: 'COMUNAL', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'ESTABLECIMIENTO', dataField: 'ESTABLECIMIENTO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'SECTOR_INTERNO', dataField: 'SECTOR_INTERNO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'CONTACTO', dataField: 'CONTACTO', cellsalign: 'left', width: 250},

                ]
            });
        $("#excelExport").click(function () {
            $("#table_grid").jqxGrid('exportdata', 'xls', 'Comensacion', true,null,true, 'excel/save-file.php');
        });
        $("#print").click(function () {
            var content = $('#pscv_cobertura')[0].outerHTML;
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

        $('#edad').jqxDropDownList({
            width: '100%',
            height: '25px'
        });
        $('#edad').on('select', function (event) {
            loadGraficoCompensacion();
        });
    });
    function loadGraficoCompensacion() {
        $.post('graficos/barra/compensacion.php',
        $("#form_compensacion").serialize(),function(data){
                $("#header_graficos").html(data);
            });
    }

</script>
<style type="text/css">
    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 800px) { /* Aquí van los estilos */
        #tabla_grilla{
            display: none;;
        }
    }
</style>
<div id="div_imprimir">
    <form class="card-panel" id="form_compensacion">
        <input type="hidden" name="atributo" value="<?php echo str_replace('>','',$_POST['atributo']); ?>" />
        <input type="hidden" name="sector_comunal" value="<?php echo $_POST['sector_comunal']; ?>" />
        <input type="hidden" name="centro_interno" value="<?php echo $_POST['centro_interno']; ?>" />
        <input type="hidden" name="sector_interno" value="<?php echo $_POST['sector_interno']; ?>" />
        <input type="hidden" name="estado" value="<?php echo $_POST['estado']; ?>" />
        <div class="row right-align">
            <div class="col l8 m8 s8">
                <select name="edad" id="edad">
                    <?php
                    if($atributo=='patologia_dm'){
                        if($rango_edad=='<(80*12)'){
                            ?>
                            <option value="<(80*12)#< 7%" selected>15 a 79 AÑOS</option>
                            <option value=">=(80*12)#< 8%">DESDE 80 AÑOS</option>
                            <option value=">(15*12)#>= 9%">HBA1C >= 9%</option>
                            <option value=">(12*15)#<%">TODOS COMPENSADOS</option>
                            <?php
                        }else{
                            if($rango_edad=='>=(80*12)'){
                                ?>
                                <option value="<(80*12)#< 7%" >15 a 79 AÑOS</option>
                                <option value=">=(80*12)#< 8%" selected>DESDE 80 AÑOS</option>
                                <option value=">(15*12)#>= 9%">HBA1C >= 9%</option>
                                <option value=">(12*15)#<%">TODOS COMPENSADOS</option>
                                <?php
                            }else{
                                if($rango_edad=='>(15*12)'){
                                    ?>
                                    <option value="<(80*12)#< 7%" >15 a 79 AÑOS</option>
                                    <option value=">=(80*12)#< 8%">DESDE 80 AÑOS</option>
                                    <option value=">(15*12)#>= 9%" selected>HBA1C >= 9%</option>
                                    <option value=">(12*15)#<%">TODOS COMPENSADOS</option>
                                    <?php
                                }else{
                                    ?>
                                    <option value="<(80*12)#< 7%" >15 a 79 AÑOS</option>
                                    <option value=">=(80*12)#< 8%">DESDE 80 AÑOS</option>
                                    <option value=">(15*12)#>= 9%" >HBA1C >= 9%</option>
                                    <option value=">(12*15) #<%" selected>TODOS COMPENSADOS</option>
                                    <?php
                                }

                            }

                        }
                    }else{
                        if($rango_edad=='<(80*12)'){
                            ?>
                            <option value="<(80*12)#<140/90 MMHG" selected >15 a 79 AÑOS</option>
                            <option value=">=(80*12)#<150/90 MMHG" >DESDE 80 AÑOS</option>
                            <option value=">(15*12)#>=160/100 MMHG">PA >= 160/100 MMHG</option>
                            <option value=">(12*15)#%/90 MMHG%">TODOS COMPENSADOS</option>
                            <?php
                        }else{
                            if($rango_edad=='>=(80*12)'){
                                ?>
                                <option value="<(80*12)#<140/90 MMHG" >15 a 79 AÑOS</option>
                                <option value=">=(80*12)#<150/90 MMHG" selected>DESDE 80 AÑOS</option>
                                <option value=">(15*12)#>=160/100 MMHG">PA >= 160/100 MMHG</option>
                                <option value=">(12*15)#%/90 MMHG%">TODOS COMPENSADOS</option>
                                <?php
                            }else{
                                if($rango_edad=='>(15*12)'){
                                    ?>
                                    <option value="<(80*12)#<140/90 MMHG" >15 a 79 AÑOS</option>
                                    <option value=">=(80*12)#<150/90 MMHG" >DESDE 80 AÑOS</option>
                                    <option value=">(15*12)#>=160/100 MMHG" selected>PA >= 160/100 MMHG</option>
                                    <option value=">(12*15)#%/90 MMHG%">TODOS COMPENSADOS</option>
                                    <?php
                                }else{
                                    ?>
                                    <option value="<(80*12)#<140/90 MMHG" >15 a 79 AÑOS</option>
                                    <option value=">=(80*12)#<150/90 MMHG" >DESDE 80 AÑOS</option>
                                    <option value=">(15*12)#>=160/100 MMHG">PA >= 160/100 MMHG</option>
                                    <option value=">(12*15)#%/90 MMHG%" selected>TODOS COMPENSADOS</option>
                                    <?php
                                }

                            }

                        }
                    }

                    ?>



                </select>
            </div>
            <div class="col l4 m4 s4">

            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id='pscv_cobertura' style="width: 100%;height: 500px;"></div>
            </div>
            <div class="col l12 m12 s12">
                <button class="btn" id="print">
                    <i class="mdi-action-print left"></i>
                    IMPRIMIR GRAFICO
                </button>
            </div>
        </div>
    </form>
    <div class="card-panel" style="display: none;">
        <div class="row">
            <div class="col l4 m4 s12">
                <label for="desde">DESDE</label>
                <input type="date" name="desde" id="desde" value="<?php echo (date('Y')-1).'-'.date('m').'-'.date('d'); ?>" />
            </div>
            <div class="col l4 m4 s12">
                <label for="hasta">HASTA</label>
                <input type="date" name="hasta" id="hasta" value="<?php echo date('Y-m-d'); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id='pscv_tiempo' style="width: 100%;height: 500px;"></div>
            </div>
        </div>

    </div>
    <div class="card-panel" id="tabla_grilla">
        <div class="row">
            <div class="col l6 m12 s12">
                <button class="btn" id="print_grid">
                    <i class="mdi-action-print left"></i>
                    IMPRIMIR TABLA
                </button>
                <button class="btn" id="excelExport" >
                    <i class="mdi-action-open-in-new left"></i>
                    EXPORTAR EXCEL
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id="table_grid"></div>
            </div>
        </div>
    </div>
</div>
