<?php
include '../../../../php/config.php';
include '../../../../php/objetos/profesional.php';
include '../../../../php/objetos/persona.php';

$id_profesional = $_POST['id_profesional'];
$profesional = new profesional($id_profesional);
$persona = new persona($profesional->rut);
//echo $persona->email;

$titulo = 'CONTRASEÑA RESTABLECIDA';
$ASUNTO = 'EQUIPO EH-OPEN INFORMA';

list($rut,$dv) = explode("-",$persona->rut);
$sql = "update usuarios set clave='$rut' where rut='$persona->rut' limit 1";
mysql_query($sql);

$mensaje = 'SE HA RESTABLECIDO SU CONTRASEÑA, FAVOR INTENTARLO NUEVAMENTE <br />USUARIO: '.$persona->rut.'<br />CLAVE: '.$rut;

$mensaje .= '<hr /><br />EQUIPO EH-OPEN<BR />';
$remitente = 'soporte@eh-open.com';
$reply = 'soporte@eh-open.com';
//enviarMail($persona->email,$ASUNTO,$mensaje,'soporte@eh-open.com','soporte@eh-open.com',$titulo);
?>
<script type="text/javascript">
    $(function () {
        $.post('https://sis.eh-open.com/email.php',{
            remitente:'<?php echo $remitente; ?>',
            reply:'<?php echo $remitente; ?>',
            asunto:'<?php echo $ASUNTO; ?>',
            titulo:'<?php echo $titulo; ?>',
            mensaje:'<?php echo $mensaje; ?>',
            para:'<?php echo $persona->email; ?>',
        },function(data){
            alertaLateral(data);
        });
    });
</script>
