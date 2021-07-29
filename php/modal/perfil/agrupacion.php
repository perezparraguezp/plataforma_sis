<?php
include "../../config.php";
include "../../objetos/agrupacion.php";
include "../../objetos/persona.php";
$id_agrupacion = $_POST['id'];
$a = new agrupacion($id_agrupacion);
$presidente = new persona($a->presidente);
$tesorero = new persona($a->tesorero);
$secretario = new persona($a->secretario);

?>
<div class="modal-content">
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="row col l12" style="margin-bottom: 0px;">
            <div class="col l8"><?php echo $a->nombre_agrupacion; ?></div>
        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            <div class="row">
                <i class="mdi-notification-event-available tiny"></i>Desde: <?php echo fechaNormal($a->desde); ?> |
                <i class="mdi-notification-event-busy tiny"></i>Hasta: <?php echo fechaNormal($a->hasta); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col l4">
            <?php echo $presidente->html_cardPersona('Presidente: '.$a->nombre_agrupacion) ?>
        </div>
        <div class="col l4">
            <?php echo $tesorero->html_cardPersona('Tesorero: '.$a->nombre_agrupacion) ?>
        </div>
        <div class="col l4">
            <?php echo $secretario->html_cardPersona('Secretario: '.$a->nombre_agrupacion) ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
