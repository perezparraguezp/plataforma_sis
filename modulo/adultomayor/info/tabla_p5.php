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


//rango de meses en dias
$rango_grupales_sql = [
    'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
    'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
    'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
    'persona.edad_total>=80*12 ', // MAYOR DE 80
    "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
    "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
];

$rango_sexos_text = [
    'HOMBRES',//HOMBRES
    'MUJERES',//MUJERES
];
$rango_sexos_text_sql = [
    "persona.sexo='M' ", //hombres
    "persona.sexo='F' ", //mujeres
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

<div class="card" id="todo_p5">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p5','REM P5')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />

    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: POBLACIÓN EN CONTROL POR CONDICIÓN DE FUNCIONALIDAD</header>
            </div>
        </div>
        <table id="table_seccion_a" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONDICIÓN DE FUNCIONALIDAD
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="8">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="2">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="2">POBLACION MIGRANTE</td>
            </tr>
            <tr>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 6 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php

            $INDICES = [
                "AUTOVALENTE SIN RIESGO",
                "AUTOVALENTE CON RIESGO",
                "RIESGO DEPENDENCIA",
                "SUBTOTAL (EFAM)",
                "DEPENDIENTE LEVE",
                "DEPENDIENTE MODERADO",
                "DEPENDIENTE GRAVE",
                "DEPENDIENTE TOTAL",
                "SUBTOTAL (INDICE BARTHEL)",
                "TOTAL PERSONAS MAYORES EN CONTROL",
            ];
            $filtro_sql = [
                "and funcionalidad='AUTOVALENTE SIN RIESGO'",
                "and funcionalidad='AUTOVALENTE CON RIESGO'",
                "and funcionalidad='RIESGO DEPENDENCIA'",
                "and (funcionalidad='RIESGO DEPENDENCIA' or funcionalidad='AUTOVALENTE CON RIESGO' or funcionalidad='AUTOVALENTE SIN RIESGO')",
                "and funcionalidad='DEPENDENCIA LEVE'",
                "and funcionalidad='DEPENDENCIA MODERADO'",
                "and funcionalidad='DEPENDENCIA GRAVE'",
                "and funcionalidad='DEPENDENCIA TOTAL'",
                "and (funcionalidad='DEPENDENCIA LEVE' or funcionalidad='DEPENDENCIA MODERADO' or funcionalidad='DEPENDENCIA GRAVE' or funcionalidad='DEPENDENCIA TOTAL')",
                "and funcionalidad!=''",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

    <section id="seccion_a1" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A.1: EXISTENCIA DE POBLACIÓN EN CONTROL EN PROGRAMA "MÁS ADULTOS MAYORES AUTOVALENTES" POR CONDICIÓN DE FUNCIONALIDAD</header>
            </div>
        </div>
        <table id="table_seccion_a1" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONDICIÓN DE FUNCIONALIDAD
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="10">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="2">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="2">POBLACION MIGRANTE</td>
            </tr>
            <tr>
                <td colspan="2">60 a 64 años</td>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 7 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php
            $rango_grupales_sql = [
                'persona.edad_total>=60*12 and persona.edad_total<=64*12', //entre 60 A 64
                'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
                'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
                'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
                'persona.edad_total>=80*12 ', // MAYOR DE 80
                "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
                "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
            ];

            $INDICES = [
                "AUTOVALENTE SIN RIESGO",
                "AUTOVALENTE CON RIESGO",
                "RIESGO DEPENDENCIA",
                "SUBTOTAL (EFAM)",
            ];
            $filtro_sql = [
                "and funcionalidad='AUTOVALENTE SIN RIESGO' and mas_adulto_mayor='SI' ",
                "and funcionalidad='AUTOVALENTE CON RIESGO' and mas_adulto_mayor='SI' ",
                "and funcionalidad='RIESGO DEPENDENCIA' and mas_adulto_mayor='SI' ",
                "and (funcionalidad='RIESGO DEPENDENCIA' or funcionalidad='AUTOVALENTE CON RIESGO' or funcionalidad='AUTOVALENTE SIN RIESGO')  and mas_adulto_mayor='SI' ",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

    <section id="seccion_b" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION B: POBLACIÓN BAJO CONTROL POR ESTADO NUTRICIONAL</header>
            </div>
        </div>
        <table id="table_seccion_b" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    ESTADO NUTRICIONAL
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="8">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="2">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="2">POBLACION MIGRANTE</td>
            </tr>
            <tr>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 6 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php

            $rango_grupales_sql = [
                'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
                'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
                'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
                'persona.edad_total>=80*12 ', // MAYOR DE 80
                "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
                "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
            ];

            $INDICES = [
                "BAJO PESO",
                "NORMAL",
                "SOBREPESO",
                "OBESO",
                "TOTAL",
            ];
            $filtro_sql = [
                "and imc='BP'",
                "and imc='N'",
                "and imc='SP'",
                "and imc='OB'",
                "and imc!=''",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: PERSONAS MAYORES CON SOSPECHA DE MALTRATO</header>
            </div>
        </div>
        <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONCEPTO
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="8">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="2">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="2">POBLACION MIGRANTE</td>
            </tr>
            <tr>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 6 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php

            $rango_grupales_sql = [
                'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
                'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
                'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
                'persona.edad_total>=80*12 ', // MAYOR DE 80
                "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
                "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
            ];

            $INDICES = [
                "PERSONAS CON SOSPECHA DE MALTRATO",
            ];
            $filtro_sql = [
                "and sospecha_maltrato='SI'",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION D: PERSONAS MAYORES EN ACTIVIDAD FÍSICA</header>
            </div>
        </div>
        <table id="table_seccion_d" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    CONCEPTO
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="8">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="2">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="2">POBLACION MIGRANTE</td>
            </tr>
            <tr>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 6 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php

            $rango_grupales_sql = [
                'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
                'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
                'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
                'persona.edad_total>=80*12 ', // MAYOR DE 80
                "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
                "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
            ];

            $INDICES = [
                "PERSONAS EN ACTIVIDAD FÍSICA",
            ];
            $filtro_sql = [
                "and actividad_fisica='SI'",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

    <section id="seccion_e" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION E: PERSONAS MAYORES CON RIESGO DE CAÍDAS</header>
            </div>
        </div>
        <table id="table_seccion_e" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    TIPO DE RIESGO
                </td>
                <td rowspan="2" colspan="3">TOTAL</td>
                <td colspan="8">GRUPO DE EDAD (en años) Y SEXO</td>
            </tr>
            <tr>
                <td colspan="2">65 a 69 años</td>
                <td colspan="2">70 a 74 años</td>
                <td colspan="2">75 a 79 años</td>
                <td colspan="2">80 y más años</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                for($i = 1 ; $i<= 4 ; $i++){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
            </tr>
            <?php

            $rango_grupales_sql = [
                'persona.edad_total>=65*12 and persona.edad_total<=69*12', //entre 65 A 69
                'persona.edad_total>=70*12 and persona.edad_total<74*12', // de 70 A 74
                'persona.edad_total>=75*12 and persona.edad_total<=79*12', // de 75 A 79
                'persona.edad_total>=80*12 ', // MAYOR DE 80

            ];

            $INDICES = [
                "TIMED UP AND GO - NORMAL",
                "TIMED UP AND GO - LEVE",
                "TIMED UP AND GO - ALTO",
                "ESTACIÓN UNIPODAL - NORMAL",
                "ESTACIÓN UNIPODAL - ALTERADO",
            ];
            $filtro_sql = [
                "and riesgo_caida='NORMAL'",
                "and riesgo_caida='LEVE'",
                "and riesgo_caida='ALTO'",
                "and estacion_unipodal='NORMAL'",
                "and estacion_unipodal='ALTERADO'",
            ];
            foreach ($INDICES AS $TR => $texto_fila){

                $TOTAL[$texto_fila]['MUJERES'] =0;
                $TOTAL[$texto_fila]['HOMBRES'] =0;
                $TOTAL[$texto_fila]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){

                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_adultomayor using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adulto_mayor='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo.$filtro_sql[$TR];
//                            echo $sql.'<br /><hr />';

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//total
                        $TOTAL[$texto_fila][$rango_sexos_text[$j]] = $TOTAL[$texto_fila][$rango_sexos_text[$j]] + $total;
                    }
                }
                $TOTAL[$texto_fila]['AMBOS'] = $TOTAL[$texto_fila]['HOMBRES'] + $TOTAL[$texto_fila]['MUJERES'];
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= '<td>'.$TOTAL[$texto_fila]['AMBOS'].'</td>
                                <td>'.$TOTAL[$texto_fila]['HOMBRES'].'</td>
                                <td>'.$TOTAL[$texto_fila]['MUJERES'].'</td>'.$fila;

                echo $fila_final.'</tr>';
            }

            ?>

        </table>
    </section>

</div>
