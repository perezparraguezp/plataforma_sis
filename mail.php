<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 3/5/20
 * Time: 3:36 PM
 */

$nombre         = $_POST['nombre'];
$institucion    = $_POST['intitucion'];
$ciudad         = $_POST['ciudad'];
$email          = $_POST['email'];
$mensaje          = $_POST['mensaje'];
$telefono          = $_POST['telefono'];

$software         = $_POST['software'];
$asesoria    = $_POST['asesoria'];
$capacitacion         = $_POST['capacitacion'];
$otros          = $_POST['otros'];

$por_telefono   = $_POST['por_telefono'];
$por_email      = $_POST['por_email'];

$temas = '<ul>
<li>'.$software.'</li>
<li>'.$capacitacion.'</li>
<li>'.$asesoria.'</li>
<li>'.$otros.'</li>
</ul>';

if($institucion!=''){
    $nombre_solicitante = strtoupper(trim($institucion));
}else{
    $nombre_solicitante = strtoupper(trim($nombre));
}

$mecanismo_contacto = '';
if($por_telefono){
    $mecanismo_contacto .= 'TELEFONICAMENTE AL '.$telefono.'<br />';
}
if($por_email){
    $mecanismo_contacto .= 'CORREO ELECTRONICO AL '.$email.'<br />';
}

$asunto = 'Solicitud WEB - '.$nombre_solicitante;

$fecha_mensaje = date('d-m-Y');

$mensaje_ehopen = '
        <h4>SOLICITUD WEB</h4>
        <p>Intentaron comunicarse con Eh-Open, favor responder según las especificaciones solicitadas</p>
        <p>Solicitante: '.$nombre.'</p>
        <p>Institucion: '.$institucion.'</p>
        <p>Teléfono: '.$telefono.'</p>
        <p>E-mail: '.$email.'</p>
        <p></p>
        <p>Temas Solicitados'.$temas.'</p>
        <p></p>
        <p>MENSAJE : '.$mensaje.'</p>
        <p></p>
        <p>MECANISMO DE CONTACTO: <strong>'.$mecanismo_contacto.'</strong></p>
        <p></p>
        <p>FECHA MENSAJE : '.$fecha_mensaje.'</p>
';


//$to = "rboggen@gmail.com";
$to = "perezparraguezp@gmail.com,rboggen@gmail.com,rboggen@eh-open.com,pperez@eh-open.com";
$subject = $asunto;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= 'Reply-To: '.$email. "\r\n" .
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$message = $mensaje_ehopen;

mail($to, $subject, $message, $headers);
?>
mensaje enviado con exito <a href="index.html">VOLVER</a>
