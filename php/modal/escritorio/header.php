<?php
include '../../config.php';
session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);
$filtro = '';

$base_sql = "";

    $sql = "select count(*) as total,sum(persona.sexo='M') as total_hombres,sum(persona.sexo='F') as total_mujeres,
                    sum(upper(pueblo)='SI') as pueblo,sum(upper(nanea)!='NO') AS nanea,sum(upper(migrante)!='NO') as migrante
                    from persona
                    inner join paciente_establecimiento using (rut)
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                    where paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
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

$sql = $sql.$filtro;
$base_sql = $base_sql.$filtro;
//echo $sql;

$row = mysql_fetch_array(mysql_query($sql));

if($row){
    $total_pacientes = $row['total'];
    $total_hombres = $row['total_hombres'];
    $total_mujeres = $row['total_mujeres'];
    $total_migrantes = $row['migrante'];
    $total_pueblos = $row['pueblo'];
    $total_naneas = $row['nanea'];
}else{
    $total_pacientes = 0;
    $total_hombres = 0;
    $total_mujeres = 0;
    $total_migrantes = 0;
    $total_pueblos = 0;
    $total_naneas = 0;
}

//INDICADOR

$indicador = $_POST['indicador'];
switch ($indicador){
    case 'NORMAL':{
        $sql1 = "select count(*) as total
                ,SUM(DNI='NORMAL' and persona.sexo='F') AS NORMALIDAD_F
                ,SUM(DNI='NORMAL' and persona.sexo='M') AS NORMALIDAD_M
                ,SUM(DNI='NORMAL') AS NORMALIDAD_GENERAL "."   from persona
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on persona.rut=antropometria.rut  
                where persona.edad_total<(6*12) and paciente_establecimiento.id_establecimiento='$id_establecimiento' ".$filtro;
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $total_pacientes_indicador = $row1['total']; //solo considera pacientes que les han tomado este indicador
            $total_general_indicador = $row1['NORMALIDAD_GENERAL'];
            $total_indice_hombres = $row1['NORMALIDAD_M'];
            $total_indice_mujeres = $row1['NORMALIDAD_F'];

            $porcentaje_indicador = number_format(($total_general_indicador*100/$total_pacientes_indicador),2,','.'');
        }

    }
}


?>
<style type="text/css">
    #head_filtro_info .card{
        font-size: 0.8em;
        line-height: 1em;
        padding-top: 2px;
        padding-bottom: 2px;
    }
</style>
<div class="row card-stats" id="head_filtro_info">
    <div class="col l2 s6 m4">
        <div class="card green black-text center-align">
            Pacientes Registrados <br /><?php echo $total_pacientes.'<br />(100%)'; ?>
        </div>
    </div>
    <div class="col l2 s6 m4">
        <div class="card light-blue lighten-3 black-text center-align">
            HOMBRES<br /><?php echo $total_hombres."<br />(".number_format(($total_hombres*100/$total_pacientes),2,',','')."%)"; ?>
        </div>
    </div>
    <div class="col l2 s6 m4">
        <div class="card pink accent-1 black-text center-align">
            MUJERES<br /><?php echo $total_mujeres."<br />(".number_format(($total_mujeres*100/$total_pacientes),2,',','')."%)"; ?>
        </div>
    </div>
    <div class="col l2 s6 m4">
        <div class="card orange lighten-1 black-text center-align">
            PRUEBLOS ORIGINARIOS<br /><?php echo $total_pueblos."<br />(".number_format(($total_pueblos*100/$total_pacientes),2,',','')."%)"; ?>
        </div>
    </div>
    <div class="col l2 s6 m4">
        <div class="card brown lighten-2 black-text center-align">
            POBLACION MIGRANTE<br /><?php echo $total_migrantes."<br />(".number_format(($total_migrantes*100/$total_pacientes),2,',','')."%)"; ?>
        </div>
    </div>
    <div class="col l2 s6 m4">
        <div class="card red lighten-1 black-text center-align">
            <span>PACIENTES NANEA<br /><?php echo $total_naneas."<br />(".number_format(($total_naneas*100/$total_pacientes),2,',','')."%)"; ?></span>
        </div>
    </div>
</div>

