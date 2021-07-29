<?php
#Include the connect.php file
include("../../../php/config.php");
include("../../../php/objetos/persona.php");



$id_establecimiento = $_SESSION['id_establecimiento'];

$indicador = $_GET['indicador'];

if($indicador!=''){

}
$rut = trim($_GET['rut']);
if($rut!=''){
    $filtro_rut =" and paciente_establecimiento.rut='$rut' ";
    $persona_filtro = new persona($rut);
}else{
    $filtro_rut = "";
}

//filtro tope de edad

$filtro_tope = " and persona.edad_total>(10*12) ";
//$filtro_tope = " ";

$i = 0;

//PARAMETROS
if($indicador=='PARAMETROS' || $indicador==''){
    $sql = "select *
                from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut   
                where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                and paciente_establecimiento.m_cardiovascular='SI' 
                $filtro_rut $filtro_tope";

    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $paciente->calcularEdad();

        $atributo = 'pa';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev,fecha_registro 
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => 'PA',
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }

//        $atributo = 'glicemia';
//        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev, fecha_registro
//              from historial_parametros_pscv
//              where indicador='$atributo'
//              and rut='$paciente->rut'
//
//              order by id_historial desc
//              limit 1";
//        $row1 = mysql_fetch_array(mysql_query($sql1));
//        if($row1){
//            $dias_ultimo_examen = $row1['ultima_ev'];
//            $fecha_ultima = $row1['fecha_registro'];
//            if($dias_ultimo_examen >= 365){
//                $customers[] = array(
//                    'rut' => $paciente->rut,
//                    'link' => $paciente->rut,
//                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                    'tipo' => 'PARAMETROS',
//                    'indicador' => strtoupper($atributo),
//                    'ultima_ev' => fechaNormal($fecha_ultima),
//                    'edad_actual' => $paciente->edad,
//                    'contacto' => $paciente->telefono,
//                    'establecimiento' => $paciente->getEstablecimiento()
//                );
//                $i++;
//            }
//        }else{
//            $customers[] = array(
//                'rut' => $paciente->rut,
//                'link' => $paciente->rut,
//                'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                'tipo' => 'PARAMETROS',
//                'indicador' => strtoupper($atributo),
//                'ultima_ev' => 'NUNCA',
//                'edad_actual' => $paciente->edad,
//                'contacto' => $paciente->telefono,
//                'establecimiento' => $paciente->getEstablecimiento()
//            );
//            $i++;
//        }
//        $atributo = 'ptgo';
//        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev , fecha_registro
//              from historial_parametros_pscv
//              where indicador='$atributo'
//              and rut='$paciente->rut'
//
//              order by id_historial desc
//              limit 1";
//        $row1 = mysql_fetch_array(mysql_query($sql1));
//        if($row1){
//            $dias_ultimo_examen = $row1['ultima_ev'];
//            $fecha_ultima = $row1['fecha_registro'];
//            if($dias_ultimo_examen >= 365){
//                $customers[] = array(
//                    'rut' => $paciente->rut,
//                    'link' => $paciente->rut,
//                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                    'tipo' => 'PARAMETROS',
//                    'indicador' => strtoupper($atributo),
//                    'ultima_ev' => fechaNormal($fecha_ultima),
//                    'edad_actual' => $paciente->edad,
//                    'contacto' => $paciente->telefono,
//                    'establecimiento' => $paciente->getEstablecimiento()
//                );
//                $i++;
//            }
//        }else{
//            $customers[] = array(
//                'rut' => $paciente->rut,
//                'link' => $paciente->rut,
//                'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                'tipo' => 'PARAMETROS',
//                'indicador' => strtoupper($atributo),
//                'ultima_ev' => 'NUNCA',
//                'edad_actual' => $paciente->edad,
//                'contacto' => $paciente->telefono,
//                'establecimiento' => $paciente->getEstablecimiento()
//            );
//            $i++;
//        }
//        $atributo = 'colt';
//        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev ,fecha_registro
//              from historial_parametros_pscv
//              where indicador='$atributo'
//              and rut='$paciente->rut'
//
//              order by id_historial desc
//              limit 1";
//        $row1 = mysql_fetch_array(mysql_query($sql1));
//        if($row1){
//            $dias_ultimo_examen = $row1['ultima_ev'];
//            $fecha_ultima = $row1['fecha_registro'];
//            if($dias_ultimo_examen >= 365){
//                $customers[] = array(
//                    'rut' => $paciente->rut,
//                    'link' => $paciente->rut,
//                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                    'tipo' => 'PARAMETROS',
//                    'indicador' => strtoupper($atributo),
//                    'ultima_ev' => fechaNormal($fecha_ultima),
//                    'edad_actual' => $paciente->edad,
//                    'contacto' => $paciente->telefono,
//                    'establecimiento' => $paciente->getEstablecimiento()
//                );
//                $i++;
//            }
//        }else{
//            $customers[] = array(
//                'rut' => $paciente->rut,
//                'link' => $paciente->rut,
//                'mail' => $paciente->email,'nombre' => $paciente->nombre,
//                'tipo' => 'PARAMETROS',
//                'indicador' => strtoupper($atributo),
//                'ultima_ev' => 'NUNCA',
//                'edad_actual' => $paciente->edad,
//                'contacto' => $paciente->telefono,
//                'establecimiento' => $paciente->getEstablecimiento()
//            );
//            $i++;
//        }
        $atributo = 'ekg';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev , fecha_registro 
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }
        $atributo = 'erc_vfg';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev , fecha_registro 
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }
        $atributo = 'imc';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev ,fecha_registro 
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }
        $atributo = 'ldl';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev ,fecha_registro
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }
        $atributo = 'rac';
        $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev, fecha_registro  
              from historial_parametros_pscv 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              
              order by id_historial desc
              limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1){
            $dias_ultimo_examen = $row1['ultima_ev'];
            $fecha_ultima = $row1['fecha_registro'];
            if($dias_ultimo_examen >= 365){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'PARAMETROS',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => fechaNormal($fecha_ultima),
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PARAMETROS',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'NUNCA',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }




    }
}
//DIABETES


if($indicador=='DIABETES' || $indicador==''){
    $sql = "select *
                from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut   
                where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                and paciente_establecimiento.m_cardiovascular='SI' 
                $filtro_rut $filtro_tope";

    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res)){
        $paciente = new persona($row['rut']);
        $paciente->calcularEdad();

        if($paciente->getIndicadorPSCV('patologia_dm')=='SI'){
            $atributo = 'hba1c';
            $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev, fecha_registro
              from historial_diabetes_mellitus 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              order by id_historial desc
              limit 1";
            $row1 = mysql_fetch_array(mysql_query($sql1));
            if($row1){
                $dias_ultimo_examen = $row1['ultima_ev'];
                $fecha_ultima = $row1['fecha_registro'];
                if($dias_ultimo_examen >= 120){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => fechaNormal($fecha_ultima),
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }

            }else{

                $fecha_ultima = 'NUNCA';
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'DIABETES',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => $fecha_ultima,
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }

            $atributo = 'fondo_ojo';
            $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev,fecha_registro  
              from historial_diabetes_mellitus 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              order by id_historial desc
              limit 1";
            $row1 = mysql_fetch_array(mysql_query($sql1));

            if($row1){
                $dias_ultimo_examen = $row1['ultima_ev'];
                $fecha_ultima = $row1['fecha_registro'];
                if($dias_ultimo_examen >= 365){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => fechaNormal($fecha_ultima),
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }

            }else{
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'DIABETES',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => 'NUNCA',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }


            $atributo = 'podologia';
            $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev ,fecha_registro 
              from historial_diabetes_mellitus 
              where indicador='$atributo' 
              and rut='$paciente->rut'
              and DATEDIFF(current_date(),fecha_registro)<=365 
              order by id_historial desc
              limit 1";
            $row1 = mysql_fetch_array(mysql_query($sql1));
            if($row1){
                $dias_ultimo_examen = $row1['ultima_ev'];
                $fecha_ultima = $row1['fecha_registro'];
                if($dias_ultimo_examen >= 365){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => fechaNormal($fecha_ultima),
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }
            }else{
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'DIABETES',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => 'NUNCA',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }

            $atributo = 'ev_pie';
            $sql1 = "select *,DATEDIFF(current_date(),fecha_registro) as ultima_ev 
              from historial_diabetes_mellitus 
              where indicador='$atributo' 
              and rut='$paciente->rut' 
              order by id_historial desc
              limit 1";
            $row1 = mysql_fetch_array(mysql_query($sql1));
            if($row1){
                $ev_pie = $row1['valor'];
                $ultima = $row1['ultima_ev'];
                if($ev_pie=='BAJO' && $ultima<=365){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => '[BAJO] '.$row1['ultima_ev'].' DIAS',
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }
                if($ev_pie=='MODERADO' && $ultima>180){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => '[MODERADO] '.$row1['ultima_ev'].' DIAS',
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }
                if($ev_pie=='ALTO' && $ultima>90){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => '[ALTO] '.$row1['ultima_ev'].' DIAS',
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }
                if($ev_pie=='MAXIMO' && $ultima>30){
                    $customers[] = array(
                        'rut' => $paciente->rut,
                        'link' => $paciente->rut,
                        'mail' => $paciente->email,'nombre' => $paciente->nombre,
                        'tipo' => 'DIABETES',
                        'indicador' => strtoupper($atributo),
                        'ultima_ev' => '[MAXIMO] '.$row1['ultima_ev'].' DIAS',
                        'edad_actual' => $paciente->edad,
                        'contacto' => $paciente->telefono,
                        'establecimiento' => $paciente->getEstablecimiento()
                    );
                    $i++;
                }

            }else{
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'DIABETES',
                    'indicador' => strtoupper($atributo),
                    'ultima_ev' => 'NUNCA',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }
    }

}

//PENDIENTES SIGGES
if($indicador=='PENDIENTES SIGGES' || $indicador==''){
    //SIGGES HTA
    $atributo = 'SIGGES HTA';
    $sql = "select *
                from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut   
                INNER JOIN paciente_pscv on paciente_establecimiento.rut=paciente_pscv.rut
                where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                and paciente_establecimiento.m_cardiovascular='SI' 
                and patologia_hta='SI' AND patologia_hta_sigges!='SI' 
                $filtro_rut $filtro_tope";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_array($res)){
        $paciente = new persona($row['rut']);
        $paciente->calcularEdad();
        if($paciente->getIndicadorPSCV('patologia_hta')=='SI'){
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PENDIENTES SIGGES',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'PENDIENTE',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }

    }
    //SIGGES DM
    $atributo = 'SIGGES DM';
    $sql = "select *
                from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut   
                INNER JOIN paciente_pscv on paciente_establecimiento.rut=paciente_pscv.rut
                where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                and paciente_establecimiento.m_cardiovascular='SI' 
                and patologia_dm='SI' AND patologia_dm_sigges!='SI' 
                $filtro_rut $filtro_tope";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_array($res)){
        $paciente = new persona($row['rut']);
        $paciente->calcularEdad();
        if($paciente->getIndicadorPSCV('patologia_dm')=='SI'){
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'PENDIENTES SIGGES',
                'indicador' => strtoupper($atributo),
                'ultima_ev' => 'PENDIENTE',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }

    }

}
if($indicador == 'CONTROL DE SALUD' || $indicador==''){
    //sin historial
//    $sql = "select * from paciente_establecimiento
//              left join historial_paciente using(rut)
//              where paciente_establecimiento.m_cardiovascular='SI'
//              and DATEDIFF(current_date(),fecha_registro)>365
//              and paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut
//
//              ";
//
//    $res = mysql_query($sql);
//    while($row = mysql_fetch_array($res)){
//        $paciente = new persona($row['rut']);
//        $customers[] = array(
//            'rut' => $paciente->rut,
//            'link' => $paciente->rut,
//            'mail' => $paciente->email,'nombre' => $paciente->nombre,
//            'tipo' => 'CONTROL DE SALUD',
//            'indicador' => 'MAYOR 1 AÑO',
//            'ultima_ev' => $paciente->getUltimaEval(),
//            'edad_actual' => $paciente->edad,
//            'contacto' => $paciente->telefono,
//            'establecimiento' => $paciente->getEstablecimiento()
//        );
//        $i++;
//    }
    //MAYOR A UN AÑO
    $sql = "select * from paciente_establecimiento
              where paciente_establecimiento.m_cardiovascular='SI'
              and paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut
              group by paciente_establecimiento.rut";

    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res)){
        $paciente = new persona($row['rut']);
        $paciente->getUltimaEval();
        if($paciente->ultimo_historial!='NUNCA'){
            //pacientes con historial
            $firstDate  = new DateTime($paciente->ultimo_historial);
            $secondDate = new DateTime(date('Y-m-d'));
            $intvl = $firstDate->diff($secondDate);
            if($intvl->days>365){
                //mayor a un año
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'CONTROL DE SALUD',
                    'indicador' => 'MAYOR A 1 AÑO',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }else{
            $customers[] = array(
                'rut' => $paciente->rut,
                'link' => $paciente->rut,
                'mail' => $paciente->email,'nombre' => $paciente->nombre,
                'tipo' => 'CONTROL DE SALUD',
                'indicador' => 'SIN REGISTROS',
                'edad_actual' => $paciente->edad,
                'contacto' => $paciente->telefono,
                'establecimiento' => $paciente->getEstablecimiento()
            );
            $i++;
        }
//
//        $customers[] = array(
//            'rut' => $paciente->rut,
//            'link' => $paciente->rut,
//            'mail' => $paciente->email,'nombre' => $paciente->nombre,
//            'tipo' => 'CONTROL DE SALUD',
//            'indicador' => 'MAYOR A 1 AÑO',
//            'ultima_ev' => $paciente->getUltimaEval(),
//            'edad_actual' => $paciente->edad,
//            'contacto' => $paciente->telefono,
//            'establecimiento' => $paciente->getEstablecimiento()
//        );
//        $i++;
    }

    //CITAS PENDIENTES
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                INNER JOIN agendamiento on paciente_establecimiento.rut=agendamiento.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut 
          and persona.edad_total>((12*10))  
          and (
            (anio_proximo_control<year(current_date()) )
            or 
            ( mes_proximo_control<=month(current_date()) and anio_proximo_control=year(current_date()))
          )
          and agendamiento.estado_control='PENDIENTE' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'CONTROL DE SALUD',
            'indicador' => 'PENDIENTES ['.$row['profesional'].']',
            'edad_actual' => $paciente->edad,
            'ultima_ev' => $paciente->getUltimaEval(),
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

}



if($i>0){
    $data[] = array(
        'TotalRows' => ''.$i,
        'Rows' => $customers
    );
    echo json_encode($data);
}

?>
