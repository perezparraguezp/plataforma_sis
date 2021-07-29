<?php
include "../../config.php";
include '../../objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$paciente = new persona($rut);

$vacunas = array(2,4,6,12,18);

for ($v = 0 ; $v < count($vacunas) ; $v++){

    if($vacunas[$v] < $paciente->total_meses){
        ?>
        <div class="row">
            <div class="col l8">VACUNA <?php echo $vacunas[$v]; ?> MESES</div>
            <?php
            if($vacunas[$v]==2){
                $valor = $paciente->vacuna2M();
            }else{
                if($vacunas[$v]==4){
                    $valor = $paciente->vacuna4M();
                }else{
                    if($vacunas[$v]==6){
                        $valor = $paciente->vacuna6M();
                    }else{
                        if($vacunas[$v]==12){
                            $valor = $paciente->vacuna12M();
                        }else{
                            if($vacunas[$v]==18){
                                $valor = $paciente->vacuna18M();
                            }
                        }
                    }
                }
            }
            ?>
            <div class="col l4 center center-align" style="font-weight: bold"><?php echo $valor; ?></div>
        </div>
        <?php
    }
}

?>

