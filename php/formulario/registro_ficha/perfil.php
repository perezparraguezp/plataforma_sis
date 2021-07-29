<?php
include '../../config.php';
include '../../objetos/persona.php';
$rut = $_POST['rut'];
$paciente = new persona($rut);
?>
<div class="row">
    <div class="col l2">RUT</div>
    <div class="col l4"><strong><?php echo $paciente->getRutFormato(); ?></strong></div>
    <div class="col l2">NOMBRE</div>
    <div class="col l4">
        <input type="text"
               name="nombre"
               style="clear: both;"
               value="<?php echo $paciente->nombre; ?>" />
    </div>
</div>
