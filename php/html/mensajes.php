<?php

include 'php/config.php';




$sql_e = "select count(*) as total,
       sum(persona.sexo='M') as hombres,sum(persona.sexo='M')*100/count(*) as porcentaje_hombres,
       sum(persona.sexo='F') as mujeres,sum(persona.sexo='F')*100/count(*) as porcentaje_mujeres,
       sum(persona.nanea='SI') as nanea,sum(persona.nanea='SI')*100/count(*) as porcentaje_nanea,
       sum(persona.pueblo='SI') as pueblo,sum(persona.pueblo='SI')*100/count(*) as porcentaje_pueblo,
       sum(persona.migrante='SI') as migrante,sum(persona.migrante='SI')*100/count(*) as porcentaje_migrante
      from persona 
      inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut ; ";


$row = mysql_fetch_array(mysql_query($sql_e))or die('error');


//DENTAL
$sql1 = "select
       sum(persona.edad_total<(12*6))as total_cero,
       sum(persona.edad_total<(12*6) and paciente_dental.cero='SI') as cobertura_cero,
       sum(persona.edad_total>=(12*6) and persona.edad_total<=(12*9))as total_ges,
       sum(persona.edad_total>=(12*6) and persona.edad_total<=(12*9) and paciente_dental.ges6='SI') as cobertura_ges
from persona
inner join paciente_dental on persona.rut=paciente_dental.rut; ";
$row1 = mysql_fetch_array(mysql_query($sql1))or die('error');

$total_cero = $row1['total_cero'];
$cobertura_cero = $row1['cobertura_cero'];

$total_ges = $row1['total_ges'];
$cobertura_ges = $row1['cobertura_ges'];

$porcentaje_cero    = $cobertura_cero*100/$total_cero;
$porcentaje_ges     = $cobertura_ges*100/$total_ges;

//LME
$sql2 = "select
       sum(persona.edad_total<=(8))as total_lme,
       sum(persona.edad_total<=(8) and antropometria.LME!='') as cobertura_total,
       sum(persona.edad_total<=(8) and antropometria.LME='LME') as cobertura_lme,
       sum(persona.edad_total<=(8) and antropometria.LME='SIN LME') as cobertura_sinlme
from persona
inner join antropometria on persona.rut=antropometria.rut;";
$row2 = mysql_fetch_array(mysql_query($sql2))or die('error');

$total_lme = $row2['total_lme'];
$cobertura_lme = $row2['cobertura_total'];
$lme = $row2['cobertura_lme'];

$porcentaje_lme = $cobertura_lme*100/$total_lme;


//DNI
$sql2 = "select
       sum(persona.edad_total<=(12*6))as total_dni,
       sum(persona.edad_total<=(12*6) and antropometria.DNI!='') as cobertura_total,
       sum(persona.edad_total<=(12*6) and antropometria.DNI='NORMAL') as dni_normal,
       sum(persona.edad_total<=(12*6) and antropometria.DNI='OB SEVERA') as dni_obsevera,
       sum(persona.edad_total<=(12*6) and antropometria.DNI='OBESIDAD') as dni_obesidad,
       sum(persona.edad_total<=(12*6) and antropometria.DNI='RI DESNUTRICION') as dni_rid,
       sum(persona.edad_total<=(12*6) and antropometria.DNI='SOBREPESO') as dni_sobrepeso
from persona
inner join antropometria on persona.rut=antropometria.rut;";
$row2 = mysql_fetch_array(mysql_query($sql2))or die('error');

$total_dni      = $row2['total_dni'];
$cobertura_dni  = $row2['cobertura_total'];

$dni_normalidad     = $row2['dni_normal'];
$dni_obsevera       = $row2['dni_obsevera'];
$dni_obesidad       = $row2['dni_obesidad'];
$dni_rid            = $row2['dni_rid'];
$dni_sobrepeso      = $row2['dni_sobrepeso'];


$porcentaje_dni = $cobertura_dni*100/$total_dni;

//EV NEUROSENSORIAL
$sql2 = "select
       count(*) as total_ev,
       sum(ev_neurosensorial!='') as cobertura_ev,
       sum(ev_neurosensorial='NORMAL') AS ev_normal,
       sum(ev_neurosensorial='ALTERADO') AS ev_alterado

from persona
inner join paciente_psicomotor on persona.rut=paciente_psicomotor.rut
where
    DATEDIFF(current_date(),persona.fecha_nacimiento)>=0
and DATEDIFF(current_date(),persona.fecha_nacimiento)<=90;";
$row2 = mysql_fetch_array(mysql_query($sql2))or die('error');

$total_ev      = $row2['total_ev'];
$cobertura_ev  = $row2['cobertura_ev']==''?0:$row2['cobertura_ev'];

$ev_normal      = $row2['ev_normal']==''?0:$row2['ev_normal'];
$ev_alterado    = $row2['ev_alterado']==''?0:$row2['ev_alterado'];



$porcentaje_ev = $cobertura_ev*100/$total_ev;







?>
<!--<script type="text/javascript">-->
<!--    $(function(){-->
<!--        var div = 'right-sidebar-nav';-->
<!--        $.post('php/ajax/select/estadistica_lateral.php',{-->
<!--        },function(data){-->
<!--            $("#"+div).html(data);-->
<!--        });-->
<!--    });-->
<!--</script>-->
<ul id="chat-out" class="side-nav rightside-navigation">
    <li class="li-hover">
        <ul class="chat-collapsible" data-collapsible="expandable">

            <li>
                <div class="collapsible-header teal white-text "><i class="mdi-editor-insert-chart"></i>ESTADISTICA GENERAL</div>
                <div class="collapsible-body recent-activity">
                    <div class="recent-activity-list chat-out-list row">
                        <div class="col s3 recent-activity-list-icon"><i class="mdi-social-people"></i>
                        </div>
                        <div class="col s9 recent-activity-list-text">
                            <a href="#">Pacientes Registrados <?php echo $row['total']; ?></a>
                            <p class="left-align">
                                <?php
                                echo 'HOMBRES '.number_format($row['porcentaje_hombres'],0).'%<br />';
                                echo 'MUJERES '.number_format($row['porcentaje_mujeres'],0).'%<br />';
                                ?>
                            </p>
                            <hr class="row" />
                            <p class="left-align">
                                <?php
                                echo 'NANEAS '.$row['nanea'].' ['.number_format($row['porcentaje_nanea'],0).'%]<br />';
                                echo 'P. ORIGINARIOS '.$row['pueblo'].' ['.number_format($row['porcentaje_pueblo'],0).'%]<br />';
                                echo 'P. MIGRANTE '.$row['migrante'].' ['.number_format($row['porcentaje_migrante'],0).'%]<br />';

                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header light-blue lighten-4 black-text "><i class="mdi-social-mood"></i>ESTADISTICA DENTAL</div>
                <div class="collapsible-body recent-activity">
                    <div class="recent-activity-list chat-out-list row">
                        <div class="col s3 recent-activity-list-icon">
                            <img src="images/odontologa.png" width="32" />
                        </div>
                        <div class="col s9 recent-activity-list-text">
                            <a href="#">COBERTURA DENTAL</a>
                            <p class="left-align">
                                <?php
                                echo 'CERO '.$cobertura_cero.'/'.$total_cero.' ['.number_format($porcentaje_cero,0).'%]<br />';
                                echo 'GES6 '.$cobertura_ges.'/'.$total_ges.' ['.number_format($porcentaje_ges,0).'%]<br />';

                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header orange lighten-3 black-text "><i class="mdi-navigation-arrow-forward"></i>ESTADISTICA LME</div>
                <div class="collapsible-body recent-activity">
                    <div class="recent-activity-list chat-out-list row">
                        <div class="col s3 recent-activity-list-icon">
                            <img src="images/lme.png" width="32" />
                        </div>
                        <div class="col s9 recent-activity-list-text">

                            <p class="left-align">
                                <?php
                                echo 'COBERTURA<br />'.$cobertura_lme.'/'.$total_lme.' ['.number_format($porcentaje_lme,0).'%]<br />';
                                echo 'LME<br />'.$lme.' ['.number_format(($lme*100/$cobertura_lme),0).'%]<br />';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header green lighten-2 black-text"><i class="mdi-navigation-arrow-forward"></i>ESTADISTICA DNI</div>
                <div class="collapsible-body recent-activity">
                    <div class="recent-activity-list chat-out-list row">
                        <div class="col s3 recent-activity-list-icon">
                            <img src="images/imc.png" width="32" />
                        </div>
                        <div class="col s9 recent-activity-list-text">
                            <a href="#" style="line-height: 1em;height: 20px;">MENORES 6 AÑOS</a>
                            <p class="left-align">
                                <?php
                                echo 'COBERTURA<br />'.$cobertura_dni.'/'.$total_dni.' ['.number_format($porcentaje_dni,0).'%]<br />';
                                echo 'NORMALIDAD<br />'.$dni_normalidad.' ['.number_format(($dni_normalidad*100/$cobertura_dni),0).'%]<br />';
                                echo 'OBESIDAD<br />'.$dni_obesidad.' ['.number_format(($dni_obesidad*100/$cobertura_dni),0).'%]<br />';
                                echo 'OB SEVERA<br />'.$dni_obsevera.' ['.number_format(($dni_obsevera*100/$cobertura_dni),0).'%]<br />';
                                echo 'RI DESNUTRICION<br />'.$dni_rid.' ['.number_format(($dni_rid*100/$cobertura_dni),0).'%]<br />';
                                echo 'SOBREPESO<br />'.$dni_sobrepeso.' ['.number_format(($dni_sobrepeso*100/$cobertura_dni),0).'%]<br />';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header deep-purple lighten-3 black-text"><i class="mdi-navigation-arrow-forward"></i> PSICOMOTOR</div>
                <div class="collapsible-body recent-activity">
                    <div class="recent-activity-list chat-out-list row">
                        <div class="col s3 recent-activity-list-icon">
                            <img src="images/ev1.png" width="32" />
                        </div>
                        <div class="col s9 recent-activity-list-text">
                            <a href="#" style="line-height: 1em;height: 20px;">EV. NEUROSENSORIAL</a>
                            <p class="left-align">
                                <?php
                                echo 'COBERTURA<br />'.$cobertura_ev.'/'.$total_ev.' ['.number_format($porcentaje_ev,0).'%]<br />';
                                echo 'NORMAL<br />'.$ev_normal.' ['.number_format(($ev_normal*100/$cobertura_ev),0).'%]<br />';
                                echo 'ALTERADO<br />'.$ev_alterado.' ['.number_format(($ev_alterado*100/$cobertura_ev),0).'%]<br />';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </li>
</ul>