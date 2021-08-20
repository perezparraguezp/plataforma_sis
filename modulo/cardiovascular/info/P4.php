<?php
include "../../../php/config.php";
include '../../../php/objetos/mysql.php';


$mysql = new mysql($_SESSION['id_usuario']);

$sql_1 = "UPDATE persona set edad_total_dias=TIMESTAMPDIFF(DAY, fecha_nacimiento, current_date) 
            where fecha_update_dias!=CURRENT_DATE() ";


$id_establecimiento = $_SESSION['id_establecimiento'];

$id_centro = $_POST['id'];
if($id_centro!=''){
    $filtro_centro = " and id_centro_interno='$id_centro' ";
    $sql0 = "select * from centros_internos 
                              WHERE id_centro_interno='$id_centro' limit 1";
    $row0 = mysql_fetch_array(mysql_query($sql0));
    $nombre_centro = $row0['nombre_centro_interno'];
}else{
    $nombre_centro = 'TODOS LOS CENTROS';
}
$sexo = [
    "persona.sexo='M' ",
    "persona.sexo='F' "
];
//rango de meses en dias
$rango_seccion_a = [
    'persona.edad_total>(14*12) and persona.edad_total<=(19*12)', //menor 15 a 19 años
    'persona.edad_total>(19*12) and persona.edad_total<=(24*12)', //menor 20 a 24 años
    'persona.edad_total>(24*12) and persona.edad_total<=(29*12)', //menor 25 a 29 años
    'persona.edad_total>(29*12) and persona.edad_total<=(34*12)', //menor 30 a 34 años
    'persona.edad_total>(34*12) and persona.edad_total<=(39*12)', //menor 35 a 39 años
    'persona.edad_total>(39*12) and persona.edad_total<=(44*12)', //menor 40 a 44 años
    'persona.edad_total>(44*12) and persona.edad_total<=(49*12)', //menor 45 a 44 años
    'persona.edad_total>(49*12) and persona.edad_total<=(54*12)', //menor 50 a 54 años
    'persona.edad_total>(54*12) and persona.edad_total<=(59*12)', //menor 55 a 59 años
    'persona.edad_total>(59*12) and persona.edad_total<=(64*12)', //menor 60 a 64 años
    'persona.edad_total>(64*12) and persona.edad_total<=(69*12)', //menor 65 a 69 años
    'persona.edad_total>(69*12) and persona.edad_total<=(74*12)', //menor 70 a 74 años
    'persona.edad_total>(74*12) and persona.edad_total<=(79*12)', //menor 75 a 79 años
    'persona.edad_total>(79*12) ', //mayor o igual 80
    "persona.edad_total>=(14*12) and persona.pueblo='SI' ",//PUEBLOS ORIGINARIOS
    "persona.edad_total>=(14*12) and persona.migrante='SI' ",//MIGRANTES
    "persona.edad_total>=(14*12) and paciente_pscv.patologia_dm='SI' ",//DIABETICOS
];
$rango_seccion_a_texto = [
    '15 a 19 años', //menor 15 a 19 años
    '20 a 24 años', //menor 20 a 24 años
    '25 a 29 años', //menor 25 a 29 años
    '30 a 34 años', //menor 30 a 34 años
    '35 a 39 años', //menor 35 a 39 años
    '40 a 44 años', //menor 40 a 44 años
    '45 a 49 años', //menor 45 a 44 años
    '50 a 54 años', //menor 50 a 54 años
    '55 a 59 años', //menor 55 a 59 años
    '60 a 64 años', //menor 60 a 64 años
    '65 a 69 años', //menor 65 a 69 años
    '70 a 74 años', //menor 70 a 74 años
    '75 a 79 años', //menor 75 a 79 años
    'Más de 80 años', //mayor o igual 80
    "",//PUEBLOS ORIGINARIOS
    "",//MIGRANTES
    ""//DIABETICOS
];



?>
<style type="text/css">
    table, tr, td {
        padding: 10px;
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.8em;
        text-align: center;
    }
    section{
        padding-top: 10px;
        padding-left: 10px;
    }
    header{
        font-weight: bold;;
    }
</style>

<form action="../../exportar/table.php" method="post" target="_blank" id="formExport">
    <input type="hidden" id="data_to_send" name="data_to_send" />
    <input type="hidden" id="file" name="file" value="archivo" />
</form>
<div class="row">
    <div class="col l8 m12 s12">
        <div class="col l12">
            <label>CENTRO MEDICO
                <select class="browser-default"
                        name="centro_interno"
                        id="centro_interno"
                        onchange="loadP4()" >
                    <option value="">TODOS</option>
                    <?php
                    $sql0 = "select * from centros_internos 
                              order by nombre_centro_interno ";
                    $res0 = mysql_query($sql0);
                    while($row0 = mysql_fetch_array($res0)){
                        if($id_centro==$row0['id_centro_interno']){
                            ?>
                            <option selected value="<?php echo $row0['id_centro_interno']; ?>"><?php echo $row0['nombre_centro_interno']; ?></option>
                            <?php
                        }else{
                            ?>
                            <option value="<?php echo $row0['id_centro_interno']; ?>"><?php echo $row0['nombre_centro_interno']; ?></option>
                            <?php
                        }

                    }
                    ?>
                </select>
            </label>
        </div>
    </div>
    <div class="col l4 m12 s12 right-align">
        <div class="col l12">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p4','P4')" />
        </div>
    </div>
</div>
<div class="card" id="todo_p4">
    <div class="row">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />
    <!--    SECCION A-->
    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: PROGRAMA SALUD CARDIOVASCULAR (PSCV)</header>
            </div>
        </div>
        <table id="table_seccion_a" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONCEPTO
                </td>
                <td rowspan="2" colspan="3" style="text-align: center;">
                    TOTAL
                </td>
                <td colspan="28" style="text-align: center;">
                    GRUPOS DE EDAD (EN AÑOS Y SEXO)
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    PUEBLOS ORIGINARIOS
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    POBLACION MIGRANTE
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    PACIENTES DIABETICOS
                </td>
            </tr>
            <tr>
                <td colspan="2">15 a 19 años</td>
                <td colspan="2">20 a 24 años</td>
                <td colspan="2">25 a 29 años</td>
                <td colspan="2">30 a 34 años</td>
                <td colspan="2">35 a 29 años</td>
                <td colspan="2">40 a 44 años</td>
                <td colspan="2">45 a 49 años</td>
                <td colspan="2">50 a 54 años</td>
                <td colspan="2">55 a 59 años</td>
                <td colspan="2">60 a 64 años</td>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 años y más</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td colspan="2">TOTAL</td>
            </tr>
            <tr>
                <td colspan="2">NUMERO DE PERSONAS EN PSCV</td>
                <?php
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_PSCV[$valor]['HOMBRES'] = 0;
                $PACIENTE_PSCV[$valor]['MUJERES'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    if($id_centro!=''){
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                    from persona, (
                    select historial_pscv.rut from historial_pscv
                    inner join paciente_establecimiento using (rut)
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                    inner join persona on paciente_establecimiento.rut=persona.rut
                    where m_cardiovascular='SI'
                    AND indicador='riesgo_cv'
                    and sectores_centros_internos.id_centro_interno='$id_centro'
                    and $rango
                    group by historial_pscv.rut
                    order by historial_pscv.id_historial desc
                        ) as personas
                    where persona.rut=personas.rut;";
                    }else{
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                    from persona, (
                    select historial_pscv.rut from historial_pscv
                    inner join paciente_establecimiento using (rut)
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                    inner join persona on paciente_establecimiento.rut=persona.rut
                    where m_cardiovascular='SI'
                    AND indicador='riesgo_cv'
                    and $rango
                    group by historial_pscv.rut
                    order by historial_pscv.id_historial desc
                        ) as personas
                    where persona.rut=personas.rut;";
                    }

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total_hombres'];
                        $total_mujeres = $row['total_mujeres'];
                    }else{
                        $total_hombres = 0;
                        $total_mujeres = 0;
                    }


                    if($i != 14 && $i!= 15){
                        $PACIENTE_PSCV[$valor]['HOMBRES'] = $PACIENTE_PSCV[$valor]['HOMBRES'] + $total_hombres;
                        $PACIENTE_PSCV[$valor]['MUJERES'] = $PACIENTE_PSCV[$valor]['MUJERES'] + $total_mujeres;
                        $PACIENTE_PSCV[$valor]['AMBOS'] = $PACIENTE_PSCV[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                    }

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }

                ?>
                <td><?php echo $PACIENTE_PSCV[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_PSCV[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_PSCV[$valor]['MUJERES']; ?></td>
                <?php
                echo $fila;
                ?>
            </tr>


            <tr>
                <td rowspan="3">CLASIFICACIÓN DEL RIESGO CARDIOVASCULAR</td>
                <?php
                $valores_cv = ['ALTO','MODERADO','BAJO'];
                foreach ($valores_cv as $i_fila => $valor){
                    echo '<td>'.$valor.'</td>';
                    $PACIENTE_PSCV[$valor]['HOMBRES'] = 0;
                    $PACIENTE_PSCV[$valor]['MUJERES'] = 0;
                    $total_hombres = 0;
                    $total_mujeres = 0;
                    $fila = '';
                    foreach ($rango_seccion_a as $i => $rango){
                        if($i<16){
                            if($id_centro!=''){
                                $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from paciente_pscv 
                                            inner join persona using(rut), (
                                        select historial_pscv.rut from historial_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        AND indicador='riesgo_cv'   
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_pscv.rut
                                        order by historial_pscv.id_historial desc
                                            ) as personas
                                        where paciente_pscv.rut=personas.rut 
                                          and paciente_pscv.riesgo_cv='$valor';";
                            }else{
                                $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from paciente_pscv inner join persona using(rut), (
                                        select historial_pscv.rut from historial_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        AND indicador='riesgo_cv'
                                        and $rango
                                        group by historial_pscv.rut
                                        order by historial_pscv.id_historial desc
                                            ) as personas
                                        where paciente_pscv.rut=personas.rut and paciente_pscv.riesgo_cv='$valor';";
                            }

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total_hombres = $row['total_hombres'];
                                $total_mujeres = $row['total_mujeres'];
                            }else{
                                $total_hombres = 0;
                                $total_mujeres = 0;
                            }


                            if($i != 14 && $i!= 15){
                                $PACIENTE_PSCV[$valor]['HOMBRES'] = $PACIENTE_PSCV[$valor]['HOMBRES'] + $total_hombres;
                                $PACIENTE_PSCV[$valor]['MUJERES'] = $PACIENTE_PSCV[$valor]['MUJERES'] + $total_mujeres;
                                $PACIENTE_PSCV[$valor]['AMBOS'] = $PACIENTE_PSCV[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                            }

                            $fila.= '<td>'.$total_hombres.'</td>';//hombre
                            $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                        }else{
                            $fila.= '<td colspan="2" style="background-color: grey"></td>';//hombre
                        }

                    }
                    ?>
                    <td><?php echo $PACIENTE_PSCV[$valor]['AMBOS'] ?></td>
                    <td><?php echo $PACIENTE_PSCV[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $PACIENTE_PSCV[$valor]['MUJERES']; ?></td>
                    <?php
                    echo $fila.'</tr><tr>';;

                }//fin valores alto bajo moderado
                ?>
            </tr>

            <tr>
                <td rowspan="6">PERSONAS BAJO CONTROL SEGUN PATOLOGIA Y FACTORES DE RIESGO (EXISTENCIA)</td>
                <?php
                $sql1 = "select * from paciente_pscv where riesgo_cv!='' group by riesgo_cv;";
                $res1 = mysql_query($sql1);
                $array_factores_name = ['HIPERTENSOS','DIABETICOS','DISLIPEDEMICOS','TABAQUISMO > 55 AÑOS','ANTECEDENTES DE INFARTO AGUDO AL MIOCARDIO (IAM)','ANTECEDENTES DE ENF. CEREBRO VASCULAR'];
                $array_sql_factores = [
                    'patologia_hta',
                    'patologia_dm',
                    'patologia_dlp',
                    'factor_riesgo_tabaquismo',
                    'factor_riesgo_iam',
                    'factor_riesgo_enf_cv',
                ];
                foreach($array_factores_name as $i => $factor){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'paciente_pscv';
                    $indicador = $array_sql_factores[$i];
                    $valor = 'SI'; // valor group SI
                    $PATOLOGIA[$indicador]['MUJERES'] =0;
                    $RIESGO_CV[$indicador]['HOMBRES'] =0;
                    $RIESGO_CV[$indicador]['AMBOS'] =0;
                    $fila = '';
                    echo '<td>'.$factor.'</td>';
                    foreach ($rango_seccion_a as $i => $rango){
                        if($i<16){
                            if($id_centro!=''){
                                $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from paciente_pscv inner join persona using(rut), (
                                        select historial_pscv.rut from historial_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'  
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_pscv.rut
                                        order by historial_pscv.id_historial desc
                                            ) as personas
                                        where paciente_pscv.rut=personas.rut 
                                          and paciente_pscv.$indicador='SI';";
                            }else{
                                $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from paciente_pscv inner join persona using(rut), (
                                        select historial_pscv.rut from historial_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and $rango
                                        group by historial_pscv.rut
                                        order by historial_pscv.id_historial desc
                                            ) as personas
                                        where paciente_pscv.rut=personas.rut 
                                          and paciente_pscv.$indicador='SI';";
                            }

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total_hombres = $row['total_hombres'];
                                $total_mujeres = $row['total_mujeres'];
                            }else{
                                $total_hombres = 0;
                                $total_mujeres = 0;
                            }

//                        $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
//                        $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                            if($i != 14 && $i!= 15){
                                $PATOLOGIA[$indicador]['HOMBRES'] = $PATOLOGIA[$indicador]['HOMBRES'] + $total_hombres;
                                $PATOLOGIA[$indicador]['MUJERES'] = $PATOLOGIA[$indicador]['MUJERES'] + $total_mujeres;
                                $PATOLOGIA[$indicador]['AMBOS'] = $PATOLOGIA[$indicador]['AMBOS'] + $total_mujeres + $total_hombres;
                            }

                            $fila.= '<td>'.$total_hombres.'</td>';//hombre
                            $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                        }else{
                            $fila.= '<td colspan="2" style="background-color: grey"></td>';//hombre

                        }


                    }
                    ?>
                    <td><?php echo $PATOLOGIA[$indicador]['AMBOS'] ?></td>
                    <td><?php echo $PATOLOGIA[$indicador]['HOMBRES'] ?></td>
                    <td><?php echo $PATOLOGIA[$indicador]['MUJERES']; ?></td>
                    <?php
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>

            <tr>
                <td rowspan="9">DETECCIÓN Y PREVENCION DE LA PROGRESION DE LA ENFERMEDAD RENAL CRÓNICA (ERC).</td>
                <?php
                $sql1 = "select * from parametros_pscv 
                            where erc_vfg!='S/ERC' and erc_vfg!='' 
                            group by erc_vfg 
                            order by
                                     erc_vfg like '%SIN%' desc,
                                     erc_vfg like '%G1%' desc,
                                     erc_vfg like '%G3A%' desc,
                                     erc_vfg like '%G3B%' desc,
                                     erc_vfg like '%G4%' desc,
                                     erc_vfg like '%G5%' desc;";
                $res1 = mysql_query($sql1);
                $valores_d = Array();
                $erc_array = ['SIN','GI','G3A','G3B','G4','G5'];
                $erc_array_label = ['SIN ENFERMEDAD RENAL (S/ERC)',
                    'ETAPA G1 Y G2 (VFG >= 60 ML/MIN)',
                    'ETAPA G3A (VFG >= 45 A 59 ML/MIN)',
                    'ETAPA G3B (VFG >= 30 A 44 ML/MIN)',
                    'ETAPA G4 (VFG >= 15 A 29 ML/MIN)',
                    'ETAPA G5 (VFG <15 ML/MIN)'];
                foreach ($erc_array as $indice => $valor){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'parametros_pscv';
                    $indicador = 'erc_vfg';

                    array_push($valores_d,$valor);
                    $ERC_VFG[$valor]['MUJERES'] =0;
                    $ERC_VFG[$valor]['HOMBRES'] =0;
                    $ERC_VFG[$valor]['AMBOS'] =0;
                    $fila = '';
                    echo '<td>'.trim($erc_array_label[$indice]).'</td>';
                    foreach ($rango_seccion_a as $i => $rango){
                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                        where m_cardiovascular='SI'  
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.erc_vfg like '%$valor%';";
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.erc_vfg like '%$valor%';";
                        }

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }

                        if($i != 14 && $i!= 15){
                            $ERC_VFG[$valor]['HOMBRES'] = $ERC_VFG[$valor]['HOMBRES'] + $total_hombres;
                            $ERC_VFG[$valor]['MUJERES'] = $ERC_VFG[$valor]['MUJERES'] + $total_mujeres;
                            $ERC_VFG[$valor]['AMBOS'] = $ERC_VFG[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        if($i==16){
                            $fila.= '<td colspan="2" style="text-align: center;">'.($total_hombres+$total_mujeres).'</td>';//hombre
                        }else{
                            $fila.= '<td>'.$total_hombres.'</td>';//hombre
                            $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                        }


                    }
                    ?>
                    <td><?php echo $ERC_VFG[$valor]['AMBOS'] ?></td>
                    <td><?php echo $ERC_VFG[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $ERC_VFG[$valor]['MUJERES']; ?></td>
                    <?php
                    echo $fila.'</tr><tr>';

                }
                ?>
                <td>TOTAL ERC</td>
                <?php
                $sql1 = "select COUNT(*) from parametros_pscv 
                            where erc_vfg!='S/ERC' and erc_vfg!='' ";
                $res1 = mysql_query($sql1);
                $valores_d = Array();
                while($row1 = mysql_fetch_array($res1)){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'parametros_pscv';
                    $indicador = 'erc_vfg';
                    $valor = 'TOTAL'; // valor group by tabla
                    array_push($valores_d,$valor);
                    $ERC_VFG[$valor]['MUJERES'] =0;
                    $ERC_VFG[$valor]['HOMBRES'] =0;
                    $ERC_VFG[$valor]['AMBOS'] =0;
                    $fila = '';

                    foreach ($rango_seccion_a as $i => $rango){
                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                        where m_cardiovascular='SI'  
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.erc_vfg='$valor';";
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        inner join paciente_pscv on paciente_pscv.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.erc_vfg='$valor';";
                        }

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }

                        if($i != 14 && $i!= 15){
                            $ERC_VFG[$valor]['HOMBRES'] = $ERC_VFG[$valor]['HOMBRES'] + $total_hombres;
                            $ERC_VFG[$valor]['MUJERES'] = $ERC_VFG[$valor]['MUJERES'] + $total_mujeres;
                            $ERC_VFG[$valor]['AMBOS'] = $ERC_VFG[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        if($i==16){
                            $fila.= '<td colspan="2" style="text-align: center;">'.($total_hombres+$total_mujeres).'</td>';//hombre
                        }else{
                            $fila.= '<td>'.$total_hombres.'</td>';//hombre
                            $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                        }


                    }
                    ?>
                    <td><?php echo $ERC_VFG[$valor]['AMBOS'] ?></td>
                    <td><?php echo $ERC_VFG[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $ERC_VFG[$valor]['MUJERES']; ?></td>
                    <?php
                    echo $fila.'</tr><tr>';

                }
                ?>
            </tr>

        </table>
    </section>

    <?php
    //ELIMINAMOS ULTIMO DATO DE ARRAY RANGO A
    unset($rango_seccion_a[16]);
    ?>
    <!--    SECCION B-->
    <section id="seccion_b" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION B: METAS DE COMPENSACIÓN</header>
            </div>
        </div>
        <table id="table_seccion_b" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONCEPTO
                </td>
                <td rowspan="2" colspan="3" style="text-align: center;">
                    TOTAL
                </td>
                <td colspan="28" style="text-align: center;">
                    GRUPOS DE EDAD (EN AÑOS Y SEXO)
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    PUEBLOS ORIGINARIOS
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    POBLACION MIGRANTE
                </td>
            </tr>
            <tr>
                <?php
                $rango_seccion_b_text = [
                    '15 a 19 años',
                    '20 a 24 años',
                    '25 a 29 años',
                    '30 a 34 años',
                    '35 a 39 años',
                    '40 a 44 años',
                    '45 a 49 años',
                    '50 a 54 años',
                    '55 a 59 años',
                    '60 a 64 años',
                    '65 a 69 años',
                    '70 a 74 años',
                    '75 a 79 años',
                    '80 años y más',
                ];
                ?>
                <?php
                foreach ($rango_seccion_b_text as $i => $item){
                    echo '<td colspan="2">'.$item.'</td>';
                }
                ?>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

                <?php
                foreach ($rango_seccion_b_text as $i => $item){
                    echo '<td>HOMBRES</td>
                          <td>MUJERES</td>';
                }
                ?>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

            </tr>

            <tr>
                <td rowspan="2">PERSONAS BAJO CONTROL POR HIPERTENSION</td>
                <?php
                $filas = Array('PA < 140/90 mmHg','PA < 150/90 mmHg');
                $valor_indicador_sql = Array('%<%140/90%','%150%');
                $dias = 365;
                $sql1 = "select * from parametros_pscv where riesgo_cv!='' group by riesgo_cv;";
                $res1 = mysql_query($sql1);
                foreach ($filas as $f => $valor_text){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'parametros_pscv';
                    $columna = 'pa';
                    $valor = $valor_indicador_sql[$f];


                    $PA[$valor]['MUJERES'] =0;
                    $PA[$valor]['HOMBRES'] =0;
                    $PA[$valor]['AMBOS'] =0;
                    $fila = '';
                    echo '<td>'.$valor_text.'</td>';
                    foreach ($rango_seccion_a as $i => $rango){

                        if($valor=='%<%140/90%'){
                            if($i==13){
                                $fila.= '<td style="background-color: grey;"></td>';//hombre
                                $fila.= '<td style="background-color: grey;"></td>';//mujer
                            }else{
                                if($id_centro!=''){
                                    $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv
                                            inner join paciente_pscv using(rut)
                                            inner join persona using(rut), (
                                        select historial_parametros_pscv.rut 
                                        from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'  
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut
                                          and patologia_hta='SI'
                                          and parametros_pscv.pa like '$valor';";
                                }else{
                                    $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv
                                            inner join paciente_pscv using(rut)
                                            inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut
                                          and patologia_hta='SI'
                                          and parametros_pscv.pa like '$valor';";
                                }

                                $row = mysql_fetch_array(mysql_query($sql));
                                if($row){
                                    $total_hombres = $row['total_hombres'];
                                    $total_mujeres = $row['total_mujeres'];
                                }else{
                                    $total_hombres = 0;
                                    $total_mujeres = 0;
                                }

                                if($i != 14 && $i!= 15){
                                    $PA[$valor]['HOMBRES'] = $PA[$valor]['HOMBRES'] + $total_hombres;
                                    $PA[$valor]['MUJERES'] = $PA[$valor]['MUJERES'] + $total_mujeres;
                                    $PA[$valor]['AMBOS'] = $PA[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                                }

                                $fila.= '<td>'.$total_hombres.'</td>';//hombre
                                $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                            }
                        }else{
                            if($i>=13){
                                if($id_centro!=''){
                                    $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv
                                            inner join paciente_pscv using(rut)
                                            inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'  
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and sectores_centros_internos.id_centro_interno='$id_centro'
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.pa like '$valor';";
                                }else{
                                    $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from parametros_pscv
                                            inner join paciente_pscv using(rut)
                                            inner join persona using(rut), (
                                        select historial_parametros_pscv.rut from historial_parametros_pscv
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and TIMESTAMPDIFF(DAY,historial_parametros_pscv.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_parametros_pscv.rut
                                        order by historial_parametros_pscv.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut 
                                          and parametros_pscv.pa like '$valor';";
                                }
//                                echo $sql;

                                $row = mysql_fetch_array(mysql_query($sql));
                                if($row){
                                    $total_hombres = $row['total_hombres'];
                                    $total_mujeres = $row['total_mujeres'];
                                }else{
                                    $total_hombres = 0;
                                    $total_mujeres = 0;
                                }

                                if($i != 14 && $i!= 15){
                                    $PA[$valor]['HOMBRES'] = $PA[$valor]['HOMBRES'] + $total_hombres;
                                    $PA[$valor]['MUJERES'] = $PA[$valor]['MUJERES'] + $total_mujeres;
                                    $PA[$valor]['AMBOS'] = $PA[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                                }

                                $fila.= '<td>'.$total_hombres.'</td>';//hombre
                                $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                            }else{
                                $fila.= '<td style="background-color: grey;"></td>';//hombre
                                $fila.= '<td style="background-color: grey;"></td>';//mujer
                            }
                        }
                    }

                    ?>
                    <td><?php echo $PA[$valor]['AMBOS'] ?></td>
                    <td><?php echo $PA[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $PA[$valor]['MUJERES']; ?></td>
                    <?php
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>

            <tr>
                <td rowspan="3">PERSONAS BAJO CONTROL POR DIABETES MELLITUS</td>
                <?php
                $filas = Array('HbA1C<7% '
                ,'HbA1C<8%'
                ,'HbA1C<7% - PA < 140/90mmHg y Colesterol LDL < 100 mg/dl');
                $filtro = Array("and hba1c like '< 7%' ",
                    "and hba1c like '< 8%' ",
                    "and hba1c like '< 7%' and parametros_pscv.pa like '%140/90%' and ldl like '%<100%' ",
                );
                $dias = 365;

                foreach ($filas as $f => $valor_text){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'pscv_diabetes_mellitus';
                    $columna = 'id_establecimiento';
                    $valor_sql = '1';
                    $valor = $valor_text;

                    $DM[$valor]['MUJERES'] =0;
                    $DM[$valor]['HOMBRES'] =0;
                    $DM[$valor]['AMBOS'] =0;
                    $fila = '';
                    $filtro_fila = $filtro[$f];
                    echo '<td>'.$valor_text.'</td>';
                    foreach ($rango_seccion_a as $i => $rango){


                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from pscv_diabetes_mellitus
                                               inner join persona using(rut)
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and sectores_centros_internos.id_centro_interno='$id_centro' 
                                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_diabetes_mellitus.rut
                                        order by historial_diabetes_mellitus.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from pscv_diabetes_mellitus
                                               inner join persona using(rut)
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                                        and $rango
                                        group by historial_diabetes_mellitus.rut
                                        order by historial_diabetes_mellitus.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }


                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $DM[$valor]['HOMBRES'] = $DM[$valor]['HOMBRES'] + $total_hombres;
                            $DM[$valor]['MUJERES'] = $DM[$valor]['MUJERES'] + $total_mujeres;
                            $DM[$valor]['AMBOS'] = $DM[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }
                    ?>
                    <td><?php echo $DM[$valor]['AMBOS'] ?></td>
                    <td><?php echo $DM[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $DM[$valor]['MUJERES']; ?></td>
                    <?php

                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
            <tr>
                <td>PERSONAS CON RCV ALTO</td>
                <?php
                $filas = Array('COLESTEROL LDL < 100 mg/dL');
                $filtro = Array(
                    "and ldl like '%<100%' and riesgo_cv='ALTO' "
                );
                $dias = 365;

                foreach ($filas as $f => $valor_text){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'pscv_diabetes_mellitus';
                    $columna = 'id_establecimiento';
                    $valor_sql = '1';
                    $valor = $valor_text;

                    $DM[$valor]['MUJERES'] =0;
                    $DM[$valor]['HOMBRES'] =0;
                    $DM[$valor]['AMBOS'] =0;
                    $fila = '';
                    $filtro_fila = $filtro[$f];
                    echo '<td>'.$valor_text.'</td>';
                    foreach ($rango_seccion_a as $i => $rango){


                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from persona 
                                                inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and sectores_centros_internos.id_centro_interno='$id_centro' 
                                        and $rango
                                        group by historial_diabetes_mellitus.rut
                                        order by historial_diabetes_mellitus.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from persona 
                                               inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                                        inner join paciente_establecimiento using (rut)
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and $rango
                                        group by historial_diabetes_mellitus.rut
                                        order by historial_diabetes_mellitus.id_historial desc
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }


                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $DM[$valor]['HOMBRES'] = $DM[$valor]['HOMBRES'] + $total_hombres;
                            $DM[$valor]['MUJERES'] = $DM[$valor]['MUJERES'] + $total_mujeres;
                            $DM[$valor]['AMBOS'] = $DM[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }

//                    echo $sql;

                    ?>
                    <td><?php echo $DM[$valor]['AMBOS'] ?></td>
                    <td><?php echo $DM[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $DM[$valor]['MUJERES']; ?></td>
                    <?php

                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
            <tr>
                <td rowspan="2">PERSONAS BAJO CONTROL con antecedentes Enfermedad Cardiovascular (ECV)</td>
                <?php
                $filas = Array('En tratamiento con Antiagregantes plaquetarios','En tratamiento con Estatina');
                $filtro = Array(
                    "and (factor_riesgo_enf_cv='SI' or factor_riesgo_iam='SI') and (tratamiento_aas='SI' or tratamiento_clo='SI')",
                    "and (factor_riesgo_enf_cv='SI' or factor_riesgo_iam='SI') and tratamiento_estatina='SI'",
                );
                $dias = 365;

                foreach ($filas as $f => $valor_text){
                    //parametros
                    $thph = 0;
                    $thpm = 0;
                    $thm = 0;
                    $tmm = 0;
                    $tabla = 'pscv_diabetes_mellitus';
                    $columna = 'id_establecimiento';
                    $valor_sql = '1';
                    $valor = $valor_text;

                    $DM[$valor]['MUJERES'] =0;
                    $DM[$valor]['HOMBRES'] =0;
                    $DM[$valor]['AMBOS'] =0;
                    $fila = '';
                    $filtro_fila = $filtro[$f];
                    echo '<td>'.$valor_text.'</td>';
                    foreach ($rango_seccion_a as $i => $rango){


                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from pscv_diabetes_mellitus
                                               inner join persona using(rut)    
                                               inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select paciente_establecimiento.rut from paciente_establecimiento
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and sectores_centros_internos.id_centro_interno='$id_centro' 
                                        and $rango
                                        group by paciente_establecimiento.rut
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                       sum(persona.sexo='M') as total_hombres
                                        from pscv_diabetes_mellitus
                                               inner join persona using(rut)
                                               inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                               inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                                        select paciente_establecimiento.rut from paciente_establecimiento
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                        inner join persona on paciente_establecimiento.rut=persona.rut
                                        where m_cardiovascular='SI'
                                        and $rango
                                        group by paciente_establecimiento.rut
                                            ) as personas
                                        where parametros_pscv.rut=personas.rut ".$filtro[$f];
                        }


                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $DM[$valor]['HOMBRES'] = $DM[$valor]['HOMBRES'] + $total_hombres;
                            $DM[$valor]['MUJERES'] = $DM[$valor]['MUJERES'] + $total_mujeres;
                            $DM[$valor]['AMBOS'] = $DM[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }
                    ?>
                    <td><?php echo $DM[$valor]['AMBOS'] ?></td>
                    <td><?php echo $DM[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $DM[$valor]['MUJERES']; ?></td>
                    <?php

                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
        </table>
    </section>
    <!--    SECCION C-->
    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: VARIABLES DE SEGUIMIENTO DEL PSCV AL CORTE</header>
            </div>
        </div>
        <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    VARIABLES
                </td>
                <td rowspan="2" colspan="3" style="text-align: center;">
                    TOTAL
                </td>
                <td colspan="28" style="text-align: center;">
                    GRUPOS DE EDAD (EN AÑOS Y SEXO)
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    PUEBLOS ORIGINARIOS
                </td>
                <td colspan="2" rowspan="2" style="text-align: center;">
                    POBLACION MIGRANTE
                </td>
            </tr>
            <tr>
                <td colspan="2">15 a 19 años</td>
                <td colspan="2">20 a 24 años</td>
                <td colspan="2">25 a 29 años</td>
                <td colspan="2">30 a 34 años</td>
                <td colspan="2">35 a 29 años</td>
                <td colspan="2">40 a 44 años</td>
                <td colspan="2">45 a 49 años</td>
                <td colspan="2">50 a 54 años</td>
                <td colspan="2">55 a 59 años</td>
                <td colspan="2">60 a 64 años</td>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 años y más</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>
            </tr>

            <tr><td colspan="2"><strong>PERSONAS CON DIABETES  EN PSCV</strong></td></tr>
            <?php

            $VARIABLES_C = [
                'CON RAZON ALBÚMINA CREATININA (RAC),VIGENTE'
                ,'CON VELOCIDAD DE FILTRACIÓN GLOMERULAR (VFG), VIGENTE'
                ,'CON FONDO DE OJO, VIGENTE'
                ,'CON ATENCIÓN PODOLÓGICA VIGENTE'
                ,'CON ECG VIGENTE'
                ,'EN TRATAMIENTO CON INSULINA'
                ,'EN TRATAMIENTO CON INSULINA QUE LOGRA META CON  HbA1C SEGÚN EDAD'
                ,'HbA1C >= 9 %'
                ,'CON ERC ETAPA 3B O SUPERIOR Y EN TRATAMIENTO CON IECA O ARA II.'
                ,'CON UN EXÁMEN DE COLESTEROL LDL VIGENTE.'
            ];

            $filtro_c = [
                "AND rac!='' AND patologia_dm='SI'"
                ,"AND erc_vfg!='' AND patologia_dm='SI'"
                ,"AND fondo_ojo!='' AND patologia_dm='SI'"
                ,"AND podologia!='' AND patologia_dm='SI'"
                ,"AND TIMESTAMPDIFF(DAY,ekg,CURRENT_DATE)<365 AND patologia_dm='SI'"
                ,"AND nph!='' AND patologia_dm='SI'"
                ,"AND patologia_dm='SI' AND nph!='' and ( (persona.edad_total<80*12 and hba1c='< 7%') or (persona.edad_total>=80*12 and hba1c='< 8%')) "
                ,"AND patologia_dm='SI' AND nph!='' and  hba1c='>= 9%' "
                ,"AND patologia_dm='SI' erc_vfg not like '%G1%G2%' AND erc_vfg!='SIN ENFERMEDAD RENAL (S/ERC)' and erc_vfg!='' and erc_vfg!='S/ERC'"
                ,"AND patologia_dm='SI' AND ldl!=''"
            ];
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>
                        <td colspan="2">'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    if($id_centro!=''){
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut)
                         inner join pscv_diabetes_mellitus using(rut)   
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and sectores_centros_internos.id_centro_interno='$id_centro'
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                        if($filtro_interno == "AND patologia_dm='SI' AND nph!='' and  hba1c='>= 9%' "){


                        }
                    }else{
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut)
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                    }

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total_hombres'];
                        $total_mujeres = $row['total_mujeres'];
                    }else{
                        $total_hombres = 0;
                        $total_mujeres = 0;
                    }


                    if($i != 14 && $i!= 15){
                        $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                        $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                        $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                    }

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }

                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }

            //doble columna

            $VARIABLES_C = ['Riesgo bajo'
                ,'Riesgo moderado'
                ,'Riesgo alto'
                ,'Riesgo máximo'
            ];

            $filtro_c = ["AND patologia_dm='SI' and ev_pie='BAJO' "
                ,"AND patologia_dm='SI' and ev_pie='MODERADO' "
                ,"AND patologia_dm='SI' and ev_pie='ALTO' "
                ,"AND patologia_dm='SI' and ev_pie='MAXIMO' "

            ];
            $c = 0;
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>';
                if($c==0){
                    echo '<td rowspan="4">CON *EVALUACIÓN VIGENTE DEL PIE SEGÚN PAUTA DE ESTIMACION DEL RIESGO DE ULCERACION EN PERSONAS CON DIABETES</td>';
                    $c++;
                }
                echo '<td>'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    if($id_centro!=''){
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut) 
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and sectores_centros_internos.id_centro_interno='$id_centro'
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                    }else{
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut)
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                    }

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total_hombres'];
                        $total_mujeres = $row['total_mujeres'];
                    }else{
                        $total_hombres = 0;
                        $total_mujeres = 0;
                    }


                    if($i != 14 && $i!= 15){
                        $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                        $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                        $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                    }

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
//                echo $sql.'<br />';
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }

            //TIPO CURACION

            $VARIABLES_C = ['Curación Convencional'
                ,'Curación Avanzada'
            ];

            $filtro_c = ["AND patologia_dm='SI' and ulceras like '%CONVEN%' "
                ,"AND patologia_dm='SI' and ulceras like '%AVANZA%' "

            ];
            $c = 0;
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>';
                if($c==0){
                    echo '<td rowspan="2">CON ÚLCERAS ACTIVAS DE PIE TRATADAS CON CURACIÓN </td>';
                    $c++;
                }
                echo '<td>'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    if($id_centro!=''){
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut) 
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and sectores_centros_internos.id_centro_interno='$id_centro'
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                    }else{
                        $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut)
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                    }

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total_hombres'];
                        $total_mujeres = $row['total_mujeres'];
                    }else{
                        $total_hombres = 0;
                        $total_mujeres = 0;
                    }


                    if($i != 14 && $i!= 15){
                        $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                        $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                        $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                    }

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
//                echo $sql.'<br />';
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }

            //columna diabeticos

            $VARIABLES_C = ['CON AMPUTACIÓN POR PIE DIABÉTICO'
                ,'CON DIAGNOSTICO ASOCIADO DE HIPERTENSION ARTERIAL'
                ,'ANTECEDENTE DE ATAQUE CEREBRO VASCULAR'
                ,'ANTECEDENTES DE INFARTO AGUDO AL MIOCARDIO'

            ];

            $filtro_c = ["AND patologia_dm='SI' and amputacion='SI'"
                ,"AND patologia_dm='SI' AND patologia_hta='SI' "
                ,"AND factor_riesgo_enf_cv='SI' and patologia_dm='SI' "
                ,"AND factor_riesgo_iam='SI' and patologia_dm='SI'  "

            ];
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>
                        <td colspan="2">'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                $ejecutar = true;
                foreach ($rango_seccion_a as $i => $rango){

                    if($i>=10 && $i<=13){
                        if($texto=='SOBREPESO: IMC entre 25 y 29.9 <65' || $texto=='OBESIDAD IMC igual o Mayor a 30KG/M2 <65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }else{
                        if($texto=='SOBREPESO: IMC entre 28 y 31.9 >65' || $texto=='**OBESIDAD: IMC igual o Mayor a 32KG/M2 >65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }
                    if($i==14 || $i==15){
                        $ejecutar = true;
                    }
                    if($ejecutar==false){
                        $fila.= '<td style="background-color: grey;"></td>';//hombre
                        $fila.= '<td style="background-color: grey;"></td>';//mujer
                    }else{
                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut) 
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and sectores_centros_internos.id_centro_interno='$id_centro'
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                                           sum(persona.sexo='M') as total_hombres
                        from persona inner join paciente_pscv using(rut)
                            inner join pscv_diabetes_mellitus using(rut)
                           inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                        select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        inner join persona on paciente_establecimiento.rut=persona.rut
                        where m_cardiovascular='SI'
                        and $rango
                        group by historial_diabetes_mellitus.rut
                        order by historial_diabetes_mellitus.id_historial desc
                            ) as personas
                        where persona.rut=personas.rut 
                        $filtro_interno;";
                        }

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                            $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                            $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }

                }

                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }
            ?>
            <tr><td colspan="2"><strong>PERSONAS CON HIPERTENSION EN PSCV</strong></td></tr>

            <!-- HIPERTENSOS -->
            <?php

            $VARIABLES_C = [
                'CON RAZON ALBÚMINA CREATININA (RAC),VIGENTE'
                ,'CON PRESIÓN ARTERIAL igual o Mayor 160/100 mmHg'
                ,'CON VELOCIDAD DE FILTRACION GLOMERULAR VIGENTE(VFG)'
                ,'TODAS LAS PERSONAS EN PSCV'
                ,'SOBREPESO: IMC entre 25 y 29.9 <65'
                ,'SOBREPESO: IMC entre 28 y 31.9 >65'
                ,'OBESIDAD IMC igual o Mayor a 30KG/M2 <65'
                ,'**OBESIDAD: IMC igual o Mayor a 32KG/M2 >65'
            ];

            $filtro_c = [
                "AND patologia_hta='SI' and rac!='' "
                ,"AND patologia_hta='SI' and pa like '%>=160%%'"
                ,"AND patologia_hta='SI' and erc_vfg!='' "
                ,""
                ,"and imc='SP'"
                ,"and imc='SP'"
                ,"and imc='OB'"
                ,"and imc='OB'"

            ];
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>
                <td colspan="2">'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                $ejecutar = true;
                foreach ($rango_seccion_a as $i => $rango){

                    if($i>=10 && $i<=13){
                        if($texto=='SOBREPESO: IMC entre 25 y 29.9 <65' || $texto=='OBESIDAD IMC igual o Mayor a 30KG/M2 <65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }else{
                        if($texto=='SOBREPESO: IMC entre 28 y 31.9 >65' || $texto=='**OBESIDAD: IMC igual o Mayor a 32KG/M2 >65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }
                    if($i==14 || $i==15){
                        $ejecutar = true;
                    }
                    if($ejecutar==false){
                        $fila.= '<td style="background-color: grey;"></td>';//hombre
                        $fila.= '<td style="background-color: grey;"></td>';//mujer
                    }else{
                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                sum(persona.sexo='M') as total_hombres
                from persona inner join paciente_pscv using(rut)
                inner join pscv_diabetes_mellitus using(rut)
                inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join persona on paciente_establecimiento.rut=persona.rut
                where m_cardiovascular='SI'
                and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                and sectores_centros_internos.id_centro_interno='$id_centro'
                and $rango
                group by historial_diabetes_mellitus.rut
                order by historial_diabetes_mellitus.id_historial desc
                ) as personas
                where persona.rut=personas.rut
                $filtro_interno;";
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                sum(persona.sexo='M') as total_hombres
                from persona inner join paciente_pscv using(rut)
                inner join pscv_diabetes_mellitus using(rut)
                inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join persona on paciente_establecimiento.rut=persona.rut
                where m_cardiovascular='SI'
                and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                and $rango
                group by historial_diabetes_mellitus.rut
                order by historial_diabetes_mellitus.id_historial desc
                ) as personas
                where persona.rut=personas.rut
                $filtro_interno;";
                        }

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                            $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                            $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }

                }

                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }
            ?>

            <!-- todos los pacientes -->
            <tr><td colspan="2"><strong>TODAS LAS PERSONAS EN PSCV</strong></td> </tr>
            <?php
            //columna doble

            $VARIABLES_C = [

                'SOBREPESO: IMC entre 25 y 29.9 <65'
                ,'SOBREPESO: IMC entre 28 y 31.9 >65'
                ,'OBESIDAD IMC igual o Mayor a 30KG/M2 <65'
                ,'**OBESIDAD: IMC igual o Mayor a 32KG/M2 >65'
            ];

            $filtro_c = [
                "and imc='SP'"
                ,"and imc='SP'"
                ,"and imc='OB'"
                ,"and imc='OB'"

            ];
            foreach ($VARIABLES_C as $fila_f => $texto){
                echo '<tr>
                <td colspan="2">'.$texto.'</td>';

                $filtro_interno = $filtro_c[$fila_f];
                $tabla = 'paciente_establecimiento';
                $indicador = 'm_cardiovascular';
                $valor = 'riesgo_cv';
                $PACIENTE_DB[$valor]['HOMBRES'] = 0;
                $PACIENTE_DB[$valor]['MUJERES'] = 0;
                $PACIENTE_DB[$valor]['AMBOS'] = 0;
                $total_hombres = 0;
                $total_mujeres = 0;
                $fila = '';
                $ejecutar = true;
                foreach ($rango_seccion_a as $i => $rango){

                    if($i>=10 && $i<=13){
                        if($texto=='SOBREPESO: IMC entre 25 y 29.9 <65' || $texto=='OBESIDAD IMC igual o Mayor a 30KG/M2 <65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }else{
                        if($texto=='SOBREPESO: IMC entre 28 y 31.9 >65' || $texto=='**OBESIDAD: IMC igual o Mayor a 32KG/M2 >65'){
                            $ejecutar = false;
                        }else{
                            $ejecutar = true;
                        }
                    }
                    if($i==14 || $i==15){
                        $ejecutar = true;
                    }
                    if($ejecutar==false){
                        $fila.= '<td style="background-color: grey;"></td>';//hombre
                        $fila.= '<td style="background-color: grey;"></td>';//mujer
                    }else{
                        if($id_centro!=''){
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                sum(persona.sexo='M') as total_hombres
                from persona inner join paciente_pscv using(rut)
                inner join pscv_diabetes_mellitus using(rut)
                inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join persona on paciente_establecimiento.rut=persona.rut
                where m_cardiovascular='SI'
                and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                and sectores_centros_internos.id_centro_interno='$id_centro'
                and $rango
                group by historial_diabetes_mellitus.rut
                order by historial_diabetes_mellitus.id_historial desc
                ) as personas
                where persona.rut=personas.rut
                $filtro_interno;";
                        }else{
                            $sql = "SELECT sum(persona.sexo='F') as total_mujeres,
                sum(persona.sexo='M') as total_hombres
                from persona inner join paciente_pscv using(rut)
                inner join pscv_diabetes_mellitus using(rut)
                inner join parametros_pscv on persona.rut=parametros_pscv.rut, (
                select historial_diabetes_mellitus.rut from historial_diabetes_mellitus
                inner join paciente_establecimiento using (rut)
                inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join persona on paciente_establecimiento.rut=persona.rut
                where m_cardiovascular='SI'
                and TIMESTAMPDIFF(DAY,historial_diabetes_mellitus.fecha_registro,CURRENT_DATE)<365
                and $rango
                group by historial_diabetes_mellitus.rut
                order by historial_diabetes_mellitus.id_historial desc
                ) as personas
                where persona.rut=personas.rut
                $filtro_interno;";
                        }

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total_hombres = $row['total_hombres'];
                            $total_mujeres = $row['total_mujeres'];
                        }else{
                            $total_hombres = 0;
                            $total_mujeres = 0;
                        }


                        if($i != 14 && $i!= 15){
                            $PACIENTE_DB[$valor]['HOMBRES'] = $PACIENTE_DB[$valor]['HOMBRES'] + $total_hombres;
                            $PACIENTE_DB[$valor]['MUJERES'] = $PACIENTE_DB[$valor]['MUJERES'] + $total_mujeres;
                            $PACIENTE_DB[$valor]['AMBOS'] = $PACIENTE_DB[$valor]['AMBOS'] + $total_mujeres + $total_hombres;
                        }

                        $fila.= '<td>'.$total_hombres.'</td>';//hombre
                        $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                    }

                }

                ?>
                <td><?php echo $PACIENTE_DB[$valor]['AMBOS'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PACIENTE_DB[$valor]['MUJERES']; ?></td>
                <?php
                echo $fila;
                ?>
                <?php
                echo '</tr>';
            }
            ?>

        </table>
    </section>

</div>