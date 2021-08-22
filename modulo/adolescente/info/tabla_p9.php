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
    'persona.edad_total>=10*12 and persona.edad_total<=19*12', //entre 10 a 19 años todos
    'persona.edad_total>=10*12 and persona.edad_total<15*12', // de 10 a 14
    'persona.edad_total>=15*12 and persona.edad_total<=19*12', // de 16 a 19 años
    "persona.pueblo='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // PUEBLO
    "persona.migrante='SI' and persona.edad_total_dias>=10*12 and persona.edad_total_dias<=19*12", // MIGRANTE
];
$rango_grupales = [
    "TOTAL",
    "Adolescentes 10 a 14 años",
    "Adolescentes 15 a 19 años",
    "Adolescentes de Pueblos Originarios",
    "Adolescentes Migrantes",
];
$rango_sexos_text = [
    'AMBOS SEXOS', //menor 1 año
    'HOMBRES',//1 año
    'MUJERES',//2 año
];
$rango_sexos_text_sql = [
    "(persona.sexo='M' or persona.sexo='F') ", //ambos sexos
    "persona.sexo='M' ", //hombres
    "persona.sexo='F' ", //mujeres
];

$sexo = [
    "persona.sexo='M' ",
    "persona.sexo='F' "
];


//INDICE
$indice_ceod_sql = [
    '0%', //0
    '1%', //0
    '3%', //0
    '5%', //0
    '7%', //0
    '9%', // 9
];
$indice_ceod_label = [
    '0', //0
    '1 a 2', //0
    '3 a 4', //0
    '5 a 6', //0
    '7 a 8', //0
    '9 o Más', // 9
];
//dientes
$indice_dientes_sql = [
    '0%', //0
    '1 a%', //0
    '10 a%', //0
    '20 a%', //0
    '28 y%', //0
];
$indice_dientes_label = [
    '0', //0
    '1 a 9', //0
    '10 a 19', //0
    '20 a 27', //0
    '28 y Más', //0
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

<div class="card" id="todo_p9">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p9','REM A09')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />

    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: POBLACIÓN EN CONTROL DE SALUD INTEGRAL DE ADOLESCENTES, SEGÚN ESTADO NUTRICIONAL</header>
            </div>
        </div>
        <table id="table_seccion_d7" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    INDICADOR NUTRICIONAL Y PARÁMETROS DE MEDICIÓN
                </td>
                <?php
                foreach ($rango_grupales as $i => $item){
                    ?>
                    <td colspan="3"><?php echo $item; ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php

                foreach ($rango_grupales as $i => $item_grupal){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
                <?php

                ?>
            </tr>
            <tr>
                <td rowspan="1" colspan="2">TOTAL ADOLESCENTES EN CONTROL</td>
                <?php
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_grupales_sql as $i => $edad){
                    foreach ($rango_sexos_text_sql as $j => $sexo){
                        if($id_centro!=''){
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                        }else{
                            $sql = "select count(*) as total from persona 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                        }
                        $sql .= 'and '.$edad.' and '.$sexo;

                        $row = mysql_fetch_array(mysql_query($sql));
                        if($row){
                            $total = $row['total'];
                        }else{
                            $total = 0;
                        }
                        $fila.= '<td>'.$total.'</td>';//hombre
                    }
                }
                ?>
                <?php echo $fila; ?>
            </tr>
            <!--            SECCION C FILA 48 AL 53-->
            <tr>
                <td rowspan="8" colspan="1">INDICADOR IMC / EDAD</td>
                <?php
                $rango_atributo_sql = [
                    "imc='OBM' ",
                    "imc='OB' ",
                    "imc='SP' ",
                    "(imc='SP' OR imc='OB' OR imc='OBM')",
                    "imc='BP' ",
                    "(imc='DN' OR imc='DN2') ",
                    "(imc='DN' OR imc='DN2' OR imc='BP) ",
                    "imc='N' ",
                ];
                $rango_atributo_text = [
                    ">+ 3 D.E. (Obesidad Severa)",
                    "≥ +2.0 a+2,9 D.E. (Obesidad)",
                    "≥ +1.0 a+1,9 D.E.(Sobrepeso)",
                    "SUBTOTAL",
                    "≤ -1.0 a-1,9 D.E.(Bajo peso)",
                    "≤ -2.0 D.E. (Desnutrición)",
                    "SUBTOTAL",
                    "-0.9 a+ 0.9 D.E. (Peso Normal)",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut   
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>

            <tr>
                <td rowspan="7" colspan="1">INDICADOR TALLA/EDAD</td>
                <?php
                $rango_atributo_sql = [
                    "talla_edad='ALTA' ",
                    "talla_edad='NALTA' ",
                    "(talla_edad='NALTA' OR talla_edad='ALTA') ",
                    "talla_edad='NBAJA' ",
                    "talla_edad='BAJA' ",
                    "(talla_edad='BAJA' OR talla_edad='NBAJA') ",
                    "talla_edad='NORMAL' ",
                ];
                $rango_atributo_text = [
                    "≥+ 2.0 D.E.(Talla Alta)",
                    "+ 1.0 a+ 1.9 D.E. (Talla Normal Alta)",
                    "SUBTOTAL",
                    "- 1.0 a -1.9 D.E. (Talla Normal Baja)",
                    "< 2 D.E. (Talla Baja)",
                    "SUBTOTAL",
                    "-0.9 a+ 0.9 D.E. (Talla Normal)",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut   
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>

            <tr>
                <td rowspan="4" colspan="1">RANGOS PERCENTILARES PARA CIRCUNFERENCIA DE CINTURA</td>
                <?php
                $rango_atributo_sql = [
                    "cintura='NORMAL' ",
                    "cintura='RIOB' ",
                    "cintura='OBAD' ",
                    "(cintura='NORMAL' or cintura='OBAD' or cintura='RIOB') ",
                ];
                $rango_atributo_text = [
                    "NORMAL (p<75)",
                    "RIESGO DE OBESIDAD (p75<p<90)",
                    "OBESIDAD ABDOMINAL p>90)",
                    "TOTAL",

                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut   
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>

        </table>
    </section>

    <section id="seccion_b" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION B: POBLACIÓN EN CONTROL SALUD INTEGRAL DE ADOLESCENTES, SEGÚN EDUCACIÓN Y TRABAJO</header>
            </div>
        </div>
        <table id="table_seccion_b" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Estudio/trabajo
                </td>
                <?php
                foreach ($rango_grupales as $i => $item){
                    ?>
                    <td colspan="3"><?php echo $item; ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php

                foreach ($rango_grupales as $i => $item_grupal){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
                <?php

                ?>
            </tr>

            <!--            ESTUDIA	-->
            <tr>
                <?php
                $rango_atributo_sql = [
                    "educacion='ESTUDIANTE' ",
                    "educacion='DESERCION' ",
                    "educacion='TRABAJO_INFANTIL' ",
                    "educacion='TRABAJO_JUVENIL' ",
                    "educacion='PEOR' ",
                    "educacion='SERVICIO' ",

                ];
                $rango_atributo_text = [
                    "ESTUDIA",
                    "DESERCIÓN ESCOLAR",
                    "TRABAJO INFANTIL",
                    "TRABAJO JUVENIL",
                    "PEORES FORMAS DE TRABAJO INFANTIL",
                    "SERVICIO DOMESTICO NO REMUNERADO PELIGROSO	",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join paciente_adolescente on persona.rut=paciente_adolescente.rut   
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }

                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
        </table>
    </section>

    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: POBLACIÓN EN CONTROL SALUD INTEGRAL DE ADOLESCENTES, SEGÚN ÁREAS DE RIESGO</header>
            </div>
        </div>
        <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Áreas de Riesgo
                </td>
                <?php
                foreach ($rango_grupales as $i => $item){
                    ?>
                    <td colspan="3"><?php echo $item; ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php

                foreach ($rango_grupales as $i => $item_grupal){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
                <?php

                ?>
            </tr>

            <!--            ESTUDIA	-->
            <tr>
                <?php
                $rango_atributo_sql = [
                    "nombre_riesgo='SALUD SEXUAL Y REPRODUCTIVA' ",
                    "nombre_riesgo='IDEACION SUICIDA' ",
                    "nombre_riesgo='INTENTO SUICIDA' ",
                    "nombre_riesgo='CONSUMO ALCOHOL Y DROGAS' ",
                    "nombre_riesgo='NUTRICIONAL' ",
                    "nombre_riesgo='OTRO RIESGO' ",

                ];
                $rango_atributo_text = [
                    "SALUD SEXUAL Y REPRODUCTIVA",
                    "IDEACIÓN SUICIDA",
                    "INTENTO SUICIDA",
                    "CONSUMO ALCOHOL Y DROGAS",
                    "NUTRICIONAL",
                    "OTRO RIESGO",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join riesgo_adolescente on persona.rut=riesgo_adolescente.rut
                                  inner join tipo_riesgo_adolescente using(id_tipo_riesgo) 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join riesgo_adolescente on persona.rut=riesgo_adolescente.rut
                                  inner join tipo_riesgo_adolescente using(id_tipo_riesgo)    
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;

                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
        </table>
    </section>

    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION D:POBLACIÓN EN CONTROL SALUD INTEGRAL DE ADOLESCENTES, SEGÚN AMBITOS GINECO-UROLOGICO/SEXUALIDAD</header>
            </div>
        </div>
        <table id="table_seccion_d" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Áreas de Riesgo
                </td>
                <?php
                foreach ($rango_grupales as $i => $item){
                    ?>
                    <td colspan="3"><?php echo $item; ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php

                foreach ($rango_grupales as $i => $item_grupal){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
                <?php

                ?>
            </tr>

            <!--            ESTUDIA	-->
            <tr>
                <?php
                $rango_atributo_sql = [
                    "nombre_riesgo='ADOLESCENTE CON CONDUCTA POSTERGADORA' ",
                    "nombre_riesgo='ADOLESCENTE CON CONDUCTA ANTICIPADORA' ",
                    "nombre_riesgo='ADOLESCENTE CON CONDUCTA ACTIVA' ",
                    "nombre_riesgo='USO ACTUAL DE METODO ANTICONCEPTIVO' ",
                    "nombre_riesgo='USO ACTUAL DE DOBLE PROTECCION' ",
                    "trim(nombre_riesgo)=trim('ADOLESCENTE CON ANTECEDENTES DE UN PRIMER EMBARAZO') ",
                    "nombre_riesgo='ADOLESCENTE CON ANTECEDENTES DE MAS DE UN EMBARAZO' ",
                    "nombre_riesgo='ADOLESCENTE CON ANTECEDENTE DE ABORTO' ",
                    "nombre_riesgo='ADOLESCENTE QUE PRESENTA VIOLENCIA DE PAREJA/POLOLO' ",
                    "nombre_riesgo='ADOLESCENTE QUE PRESENTA O HA SIDO VICTIMA DE VIOLENCIA SEXUAL' ",

                ];
                $rango_atributo_text = [
                    "ADOLESCENTE CON CONDUCTA POSTERGADORA",
                    "ADOLESCENTE CON CONDUCTA ANTICIPADORA",
                    "ADOLESCENTE CON CONDUCTA ACTIVA",
                    "USO ACTUAL DE METODO ANTICONCEPTIVO",
                    "USO ACTUAL DE DOBLE PROTECCION",
                    "ADOLESCENTE CON ANTECEDENTES DE UN PRIMER EMBARAZO",
                    "ADOLESCENTE CON ANTECEDENTES DE MAS DE UN EMBARAZO",
                    "ADOLESCENTE CON ANTECEDENTE DE ABORTO",
                    "ADOLESCENTE QUE PRESENTA VIOLENCIA DE PAREJA/POLOLO",
                    "ADOLESCENTE QUE PRESENTA O HA SIDO VICTIMA DE VIOLENCIA SEXUAL",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                foreach ($rango_atributo_text as $atributo => $texto){
                    echo '<td>'.$texto.'</td>';
                    $fila = '';
                    $criterio = $rango_atributo_sql[$atributo];
                    foreach ($rango_grupales_sql as $i => $edad){
                        foreach ($rango_sexos_text_sql as $j => $sexo){
                            if($id_centro!=''){
                                $sql = "select count(*) as total from persona 
                                  inner join riesgo_adolescente on persona.rut=riesgo_adolescente.rut
                                  inner join tipo_riesgo_adolescente using(id_tipo_riesgo) 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where paciente_establecimiento.id_sector='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }else{
                                $sql = "select count(*) as total from persona
                                  inner join riesgo_adolescente on persona.rut=riesgo_adolescente.rut
                                  inner join tipo_riesgo_adolescente using(id_tipo_riesgo)    
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                            }
                            $sql .= 'and '.$edad.' and '.$sexo;

                            $sql .= 'and '.$criterio;


                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $fila.= '<td>'.$total.'</td>';//hombre
                        }
                    }
                    echo $fila.'</tr><tr>';
                }
                ?>
            </tr>
        </table>
    </section>

    <section id="seccion_e" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION E:POBLACIÓN ADOLESCENTE QUE RECIBE CONSEJERÍA</header>
            </div>
        </div>
        <table id="table_seccion_e" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="1" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    ACTIVIDAD Y AREA TEMATICA
                </td>
                <td colspan="1" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Grupos de Edad
                </td>
                <?php
                $rango_grupales = [
                    "Total de Adolescentes que reciben Consejería",
                    "Adolescentes de Pueblos Originarios",
                    "Adolescentes Migrantes",
                    "Espacios Amigables",
                ];

                $rango_grupales_sql = [
                    'persona.edad_total>=10*12 and persona.edad_total<15*12 ', //entre 10 a 19 años todos
                    "persona.edad_total>=15*12 and persona.edad_total<19*12", // de 10 a 14
                    "persona.edad_total>=15*12 and persona.edad_total<19*12", // de 10 a 14
                    "persona.edad_total>=15*12 and persona.edad_total<19*12", // de 10 a 14
                ];
                $filtros_columnas = [
                        '',
                        "and pueblo='SI'",
                        "and migrante='SI'",
                        "and amigable='SI'",
                ];
                $rango_grupales_text = [
                    '10-14 años', //entre 10 a 19 años todos
                    '15 - 19 años', // de 10 a 14
                ];

                foreach ($rango_grupales as $i => $item){
                    ?>
                    <td colspan="3"><?php echo $item; ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php

                foreach ($rango_grupales as $i => $item_grupal){
                    foreach ($rango_sexos_text as $j => $item){
                        echo '<td>'.$item.'</td>';
                    }
                }
                ?>
                <?php

                ?>
            </tr>

            <!--            ESTUDIA	-->
            <tr>
                <?php
                $rango_atributo_sql = [
                    "ACTIVIDAD FISICA",
                    "ALIMENTACION SALUDABLE",
                    "TABAQUISMO",
                    "CONSUMO DE DROGAS",
                    "SALUD SEXUAL REPRODUCTIVA",
                    "REGULACION DE FECUNDIDAD",
                    "PREVENCION VIH-ITS",

                ];
                $rango_atributo_text = [
                    "SALUD SEXUAL Y REPRODUCTIVA",
                    "IDEACIÓN SUICIDA",
                    "INTENTO SUICIDA",
                    "CONSUMO ALCOHOL Y DROGAS",
                    "NUTRICIONAL",
                    "OTRO RIESGO",
                ];
                $valor = 'TOTAL';
                $TOTAL[$valor]['MUJERES'] =0;
                $TOTAL[$valor]['HOMBRES'] =0;
                $TOTAL[$valor]['AMBOS'] =0;

                //primera columna
                foreach ($rango_atributo_sql as $atributo => $texto){
                    echo '<td rowspan="2">'.$texto.'</td>';
                    $criterio = "nombre_consejeria='".$rango_atributo_sql[$atributo]."' ";
                    //segunda columna
                    foreach ($rango_grupales_text as $i => $edad_label){
                        $fila = '';
                        echo '<td>'.$edad_label.'</td>';
                        $edad = $rango_grupales_sql[$i];//filtro edad

                        //comenzamos a recorrer las columnas
                        foreach ($rango_grupales as $c => $columna){

                            $filtro_columna = $filtros_columnas[$c];//filtro columna

                            foreach ($rango_sexos_text_sql as $j => $sexo){
                                if($id_centro!=''){
                                    $sql = "select count(*) as total from persona 
                                  inner join consejerias_adolescente on persona.rut=consejerias_adolescente.rut
                                  inner join tipo_consejerias_adolescente using(id_tipo_consejeria) 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                                }else{
                                    $sql = "select count(*) as total from persona
                                  inner join consejerias_adolescente on persona.rut=consejerias_adolescente.rut
                                  inner join tipo_consejerias_adolescente using(id_tipo_consejeria)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_adolescente='SI' and id_establecimiento='$id_establecimiento' ";
                                }
                                $sql .= 'and '.$edad.' and '.$sexo;

                                $sql .= 'and '.$criterio.$filtro_columna;



                                $row = mysql_fetch_array(mysql_query($sql));

                                if($row){
                                    $total = $row['total'];
                                }else{
                                    $total = 0;
                                }
                                $fila.= '<td>'.$total.'</td>';//hombre
                            }

                        }
                        echo $fila.'</tr><tr>';


                    }



                }

                ?>
            </tr>
        </table>
    </section>
</div>
