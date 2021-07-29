<?php

include '../../../php/config.php';


$sql_e = "select count(*) as total,
       sum(persona.sexo='M') as hombres,sum(persona.sexo='M')*100/count(*) as porcentaje_hombres,
       sum(persona.sexo='F') as mujeres,sum(persona.sexo='F')*100/count(*) as porcentaje_mujeres,
       sum(persona.nanea='SI') as nanea,sum(persona.nanea='SI')*100/count(*) as porcentaje_nanea,
       sum(persona.pueblo='SI') as pueblo,sum(persona.pueblo='SI')*100/count(*) as porcentaje_pueblo,
       sum(persona.migrante='SI') as migrante,sum(persona.migrante='SI')*100/count(*) as porcentaje_migrante
      from persona 
      inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut 
       where m_cardiovascular='SI'; ";
$row = mysql_fetch_array(mysql_query($sql_e))or die('error');
$total_pacientes = $row['total'];
//PATOLOGIAS

$sql_e = "select count(*) as total,
      sum(patologia_hta='SI') AS HTA,
      sum(patologia_hta='SI' and patologia_hta_sigges='SI') AS HTA_SIGGES,
      sum(patologia_dlp='SI') AS DLP,
      sum(patologia_dm='SI') AS DM,
      sum(patologia_dm='SI' and patologia_dm_sigges='SI') AS DM_SIGGES,
      sum(factor_riesgo_tabaquismo='SI') AS TABAQUISMO,
       sum(factor_riesgo_iam='SI') AS IAM,
       sum(factor_riesgo_enf_cv='SI') AS ENF_CV,
       sum(postrado='SI') AS POSTRADOS,
       sum(hemodialisis='SI') AS HEMODIALISIS,
       sum(tratamiento_aas='SI') AS AAS,
       sum(tratamiento_ieeca='SI') AS IEECA,
       sum(tratamiento_estatina='SI') AS ESTATINA,
       sum(tratamiento_araii='SI') AS ARAII
      from paciente_establecimiento
      INNER JOIN paciente_pscv on paciente_pscv.rut=paciente_establecimiento.rut
       where m_cardiovascular='SI'; ";
$row_e = mysql_fetch_array(mysql_query($sql_e))or die('error');

$hta = $row_e['HTA']*100/$total_pacientes;
$hta_siges = $row_e['HTA_SIGGES']*100/$row_e['HTA'];

$dm = $row_e['DM']*100/$total_pacientes;
$dm_siges = $row_e['DM_SIGGES']*100/$row_e['DM'];

$dlp = $row_e['DLP']*100/$total_pacientes;

$tabaquismo = $row_e['TABAQUISMO']*100/$total_pacientes;
$iam = $row_e['IAM']*100/$total_pacientes;
$enf_cv = $row_e['ENF_CV']*100/$total_pacientes;
$postrados = $row_e['POSTRADOS']*100/$total_pacientes;
$hemodialisis = $row_e['HEMODIALISIS']*100/$total_pacientes;

$AAS = $row_e['AAS']*100/$total_pacientes;
$IEECA = $row_e['IEECA']*100/$total_pacientes;
$ESTATINA = $row_e['ESTATINA']*100/$total_pacientes;
$ARAII = $row_e['ARAII']*100/$total_pacientes;

?>


<li class="li-hover">
    <ul class="chat-collapsible" data-collapsible="expandable">
        <li>
            <div class="collapsible-header teal white-text active"><i class="mdi-editor-insert-chart"></i>ESTADISTICA GENERAL</div>
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
                        <?php
                        echo 'POSTRADOS '.number_format($postrados,0).'%<br />';
                        echo 'HEMODIALISIS '.number_format($hemodialisis,0).'%<br />';
                        ?>

                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header purple lighten-4 black-text "><i class="mdi-action-accessibility"></i>PATOLOGIAS</div>
            <div class="collapsible-body recent-activity">
                <div class="recent-activity-list chat-out-list row">
                    <div class="col s3 recent-activity-list-icon">
                        <img src="images/icono_patologia.png" width="98%" />
                    </div>
                    <div class="col s9 recent-activity-list-text">

                        <p class="left-align">
                            <?php
                            echo 'HTA '.number_format($hta,0).'% [SIGES: '.number_format($hta_siges,0).'%]<br />';
                            echo 'DM '.number_format($dm,0).'% [SIGES: '.number_format($dm_siges,0).'%]<br />';
                            echo 'DLP '.number_format($dlp,0).'% <br />';

                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header lime accent-1 black-text "><i class="mdi-alert-warning red-text"></i>RIESGOS</div>
            <div class="collapsible-body recent-activity">
                <div class="recent-activity-list chat-out-list row">
                    <div class="col s3 recent-activity-list-icon">
                        <img src="images/icono_riesgos.png" width="100%" />
                    </div>
                    <div class="col s9 recent-activity-list-text">

                        <p class="left-align">
                            <?php
                            echo 'TABAQUISMO '.number_format($tabaquismo,0).'%<br />';
                            echo 'IAM '.number_format($iam,0).'%<br />';
                            echo 'ENF. CV. '.number_format($enf_cv,0).'%<br />';

                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header light-green lighten-2 black-text "><i class="mdi-maps-local-hospital blue-text"></i>TRATAMIENTOS</div>
            <div class="collapsible-body recent-activity">
                <div class="recent-activity-list chat-out-list row">
                    <div class="col s3 recent-activity-list-icon">
                        <img src="images/icono_tratamiento.png" width="100%" />
                    </div>
                    <div class="col s9 recent-activity-list-text">

                        <p class="left-align">
                            <?php
                            echo 'AAS '.number_format($AAS,0).'%<br />';
                            echo 'IEECA '.number_format($IEECA,0).'%<br />';
                            echo 'ESTATINA '.number_format($ESTATINA,0).'%<br />';
                            echo 'ARA II '.number_format($ARAII,0).'%<br />';

                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </li>

    </ul>
</li>
