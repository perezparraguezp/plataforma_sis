
<?php
include "../../php/config.php";
include '../../php/objetos/persona.php';
include '../../php/objetos/profesional.php';
$rut = str_replace('.','',$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

if($fecha_registro==''){
    $fecha_registro = date('Y-m-d');
}

$paciente = new persona($rut);
list($establecimiento,$sector_interno,$sector_comunal) = explode(":",$paciente->getEstablecimiento());
?>
<div class="row">
    <div class="col l1 center">
        <?php $imagen = $paciente->sexo=='F'?'mujer.png':'hombre.png'; ?>
        <img src="../../images/<?php echo $imagen; ?>" width="48" />
    </div>
    <div class="col l4">
        <div class="row">
            <strong><?php echo $paciente->nombre; ?></strong>
        </div>
        <div class="row">
            <?php echo $paciente->edad ?>
        </div>
        <div class="row">
            FN <?php echo fechaNormal($paciente->fecha_nacimiento); ?>
        </div>
        <div class="row">
            <?php echo $paciente->rut ?>
        </div>
    </div>
    <div class="col l1">
        <img src="../../images/centro_medico.png" width="48" />
    </div>
    <div class="col l4">
        <div class="row">
            <strong><?php echo $establecimiento; ?></strong>
        </div>
        <div class="row">
            <?php echo 'Sector: '.$sector_interno.' | '.$sector_comunal; ?>
        </div>
        <div class="row">
            <?php echo 'Ultimo Control: '.fechaNormal($paciente->getFechaUltimoControl()    ); ?>
        </div>
    </div>
</div>