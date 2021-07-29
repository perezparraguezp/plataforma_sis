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
}else{
    $filtro_rut = "";
}

//filtro tope de edad

$filtro_tope = " and persona.edad_total<(10*12) ";

$i = 0;

//LME
if($indicador=='ANTROPOMETRIA' || $indicador==''){
    $fecha_actual = date('Y-m-d');
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope 
          and datediff(antropometria.fecha_sql,'$fecha_actual')>365 ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total<=6 and antropometria.LME='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'LME',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
    //score ira
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut 
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total <= (7)   
          and antropometria.SCORE_IRA='' ";
    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'SCORE_IRA < 3 MESES',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
    //DNI
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut 
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total <= (7)   
          and antropometria.SCORE_IRA='' ";
    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'SCORE_IRA < 3 MESES',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

    //PERIMETRO CRANEAL
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut 
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total <= (12*2)   
          and antropometria.perimetro_craneal='' ";
    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'PERIMETRO CRANEAL',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

//PRESION ARTERIAL
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut  
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=((12*3)+2) and persona.edad_total<((12*10)) 
          and antropometria.presion_arterial='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);


        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'PRESION ARTERIAL',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

//Perimetro Cintrua
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut  
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(12*5) and persona.edad_total<((12*10)) 
          and antropometria.PCINT='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);


        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'PCINT',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

//AGUDEZA VISUAL
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join antropometria on paciente_establecimiento.rut=antropometria.rut  
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(12*5) and persona.edad_total<((12*10)) 
          and antropometria.agudeza_visual='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);


        $customers[] = array(
            'link' => $paciente->rut,
            'rut' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'ANTROPOMETRIA',
            'indicador' => 'AGUDEZA VISUAL',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
}
//VACUNAS
if($indicador == 'VACUNAS' || $indicador==''){
    $sql = "select paciente_establecimiento.rut,paciente_establecimiento.id_establecimiento,
                2m,4m,6m,12m,18m,5anios  
                from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join vacunas_paciente on vacunas_paciente.rut=paciente_establecimiento.rut  
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);

        $paciente->calcularEdad();

        if($paciente->total_meses>=2){
            $v = $paciente->vacuna2M();
            if($v == 'NO'){

                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '2M',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );


                $i++;
            }
        }

        if($paciente->total_meses>=4){
            $v = $paciente->vacuna4M();
            if($v == 'NO'){

                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '4M',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }

        if($paciente->total_meses>=6){
            $v = $paciente->vacuna6M();
            if($v == 'NO'){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '6M',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }

        if($paciente->total_meses>=12){
            $v = $paciente->vacuna12M();
            if($v == 'NO'){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '12M',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }

        if($paciente->total_meses>=18){
            $v = $paciente->vacuna18M();
            if($v == 'NO'){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '18M',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }

        if($paciente->total_meses>=(5*12)){
            $v = $paciente->vacuna5Anios();
            if($v == 'NO'){
                $customers[] = array(
                    'rut' => $paciente->rut,
                    'link' => $paciente->rut,
                    'mail' => $paciente->email,'nombre' => $paciente->nombre,
                    'tipo' => 'VACUNAS',
                    'indicador' => '5 AÑOS',
                    'edad_actual' => $paciente->edad,
                    'contacto' => $paciente->telefono,
                    'establecimiento' => $paciente->getEstablecimiento()
                );
                $i++;
            }
        }



    }
}




//EEDP

if($indicador == 'PSICOMOTOR' || $indicador==''){
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_psicomotor on paciente_establecimiento.rut=paciente_psicomotor.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=10 and persona.edad_total<12 and paciente_psicomotor.eedp='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'PSICOMOTOR',
            'indicador' => 'EEDP < 12 Meses',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
//EEDP

    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_psicomotor on paciente_establecimiento.rut=paciente_psicomotor.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=21 and persona.edad_total<24 and paciente_psicomotor.eedp='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'PSICOMOTOR',
            'indicador' => 'EEDP < 24 Meses',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

//tepsi
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_psicomotor on paciente_establecimiento.rut=paciente_psicomotor.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(4*12)  and paciente_psicomotor.tepsi='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'PSICOMOTOR',
            'indicador' => 'TEPSI > 4 AÑOS',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

    //neurosensorial
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_psicomotor on paciente_establecimiento.rut=paciente_psicomotor.rut 
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(2) and persona.edad_total<=((12))  
          and paciente_psicomotor.ev_neurosensorial='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'PSICOMOTOR',
            'indicador' => 'EV NEUROSENSORIAL > 2 MESES',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }

//RX CADERA
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_psicomotor on paciente_establecimiento.rut=paciente_psicomotor.rut 
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(4) and persona.edad_total<=((12))  
          and paciente_psicomotor.rx_pelvis='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'PSICOMOTOR',
            'indicador' => 'RX PELVIS > 4 MESES',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
}

//DENTAL CERO
if($indicador == 'DENTAL' || $indicador==''){
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_dental on paciente_establecimiento.rut=paciente_dental.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(7) and persona.edad_total<=((12*5))  
          and paciente_dental.cero='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'DENTAL',
            'indicador' => 'CERO',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
//DENTAL GES
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                inner join paciente_dental on paciente_establecimiento.rut=paciente_dental.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total>=(6*12) and persona.edad_total<=((12*6)+3)  
          and paciente_dental.ges6='' ";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'DENTAL',
            'indicador' => 'GES6',
            'edad_actual' => $paciente->edad,
            'contacto' => $paciente->telefono,
            'establecimiento' => $paciente->getEstablecimiento()
        );
        $i++;

    }
}

if($indicador == 'CONTROL DE SALUD' || $indicador==''){
    //MAYOR A UN AÑO
    $sql = "select * from paciente_establecimiento
          where paciente_establecimiento.id_establecimiento='$id_establecimiento'
            AND paciente_establecimiento.m_infancia='SI' $filtro_rut
            group by paciente_establecimiento.rut";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){
        $paciente = new persona($row['rut']);
        if($paciente->edad_total<=(12*10)){
            //menores de 10 años
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


        }

    }

    //CITAS PENDIENTES
    $sql = "select * from paciente_establecimiento
                inner join persona on paciente_establecimiento.rut=persona.rut 
                inner join sectores_centros_internos on id_sector_centro_interno=id_sector
                inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                INNER JOIN agendamiento on paciente_establecimiento.rut=agendamiento.rut
          where paciente_establecimiento.id_establecimiento='$id_establecimiento' $filtro_rut $filtro_tope
          and persona.edad_total<=((12*10))  
          and (
            (anio_proximo_control<year(current_date()) )
            or 
            ( mes_proximo_control<=month(current_date()) and anio_proximo_control=year(current_date()))
          )
          and agendamiento.estado_control='PENDIENTE' group by persona.rut";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res)){

        $paciente = new persona($row['rut']);
        $customers[] = array(
            'rut' => $paciente->rut,
            'link' => $paciente->rut,
            'mail' => $paciente->email,'nombre' => $paciente->nombre,
            'tipo' => 'CONTROL DE SALUD',
            'indicador' => 'CITA PENDIENTE',
            'edad_actual' => $paciente->edad,
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
