<?php
include("../../../php/config.php");
include("../../../php/objetos/documento.php");
include("../../../php/objetos/persona.php");

list($rut,$nombre) = explode(" | ",$_POST['rut_paciente']);
$tipo = $_POST['tipo_informe'];

$persona = new persona($rut);
$mama = new persona($persona->rut_mama);
$papa = new persona($persona->rut_papa);

$documento = new documento('DEPTO DE SALUD','SISTEMA INFANTIL','DOCUMENTO');
$color = '#faffe3';
$html = '
<style type="text/css">
table{
    font-size: 0.7em;;
    line-height: 1.5em;
}
h5{
text-align: center;;
margin: 0px;
}
</style>
<h5>TARJETA CONTROL DE ACTIVIDADES DE SALUD</h5>
<table border="1" width="100%">
            <tr>
                <td style="width: 10%;background-color:'.$color.';">NOMBRE</td>
                <td style="width: 50%;">'.$persona->nombre.'</td>
                <td style="width: 10%;background-color:'.$color.';">SEXO</td>
                <td style="width: 5%;text-align: center;">'.$persona->sexo.'</td>
                <td style="width: 10%;text-align: right;background-color:'.$color.';">RUN</td>
                <td style="width: 15%;text-align: right;;">'.$persona->getRutFormato().'</td>
            </tr>
            <tr>
                <td style="width: 10%;background-color: '.$color.';">MADRE</td>
                <td style="width: 50%;">'.$mama->nombre.'</td>
                <td style="width: 10%;background-color:'.$color.';">EDAD</td>
                <td style="width: 5%;text-align: center;">'.$mama->getEdadEnAnios().'</td>
                <td style="width: 10%;text-align: right;background-color:'.$color.';">RUN</td>
                <td style="width: 15%;text-align: right;;">'.$mama->getRutFormato().'</td>
            </tr>
            <tr>
                <td style="width: 10%;background-color:'.$color.';">PADRE</td>
                <td style="width: 50%;">'.$papa->nombre.'</td>
                <td style="width: 10%;background-color:'.$color.';">EDAD</td>
                <td style="width: 5%;text-align: center;">'.$papa->getEdadEnAnios().'</td>
                <td style="width: 10%;text-align: right;background-color:'.$color.';">RUN</td>
                <td style="width: 15%;text-align: right;;">'.$papa->getRutFormato().'</td>
            </tr>
            <tr>
                <td style="width: 15%;background-color:'.$color.';">DOMICILIO NIÑO(A)</td>
                <td style="width: 35%;">'.$persona->direccion.'</td>
                <td style="width: 10%;background-color:'.$color.';">TELEFONO</td>
                <td style="width: 15%;text-align: center;">'.$persona->telefono.'</td>
                <td style="width: 10%;text-align: right;background-color:'.$color.';">COMUNA</td>
                <td style="width: 15%;text-align: right;;">'.$persona->comuna.'</td>
            </tr>
        </table>';
$html .='<table border="1" width="100%">
            <tr>
                <td colspan="2" style="width: 30%;text-align: center;background-color: #cbaa7b;">DATOS NACIMIENTO</td>                        
                <td colspan="3" style="width: 35%;text-align: center;background-color: #d7efff;">VACUNOGRAMA</td>            
                <td colspan="3" style="width: 35%;text-align: center;background-color: #ff898b;">Ri morir por Neumonía</td>            
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #cbaa7b;width: 15%;">FECHA</td>
                <td style="background-color: #cbaa7b;width: 15%;">'.fechaNormal($persona->fecha_nacimiento).'</td>
                
                <td style="background-color: #d7efff;width: 10%;">VACUNA</td>
                <td style="background-color: #d7efff;width: 15%;">FECHA</td>
                <td style="background-color: #d7efff;width: 10%;">EDAD</td>
                
                <td style="background-color: #ff898b;">FECHA</td>
                <td style="background-color: #ff898b;">EVALUACION</td>
                <td style="background-color: #ff898b;">EDAD</td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">SEM. GESTA</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">2M</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">PESO</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">4M</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">TALLA</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">6M</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">APGAR 1º - 2º</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">12M</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">EOA</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">18M</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">PER. CRANEAL</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">OTRAS</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">PKU</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">OTRAS</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
            <tr>
                <td style="background-color: #cbaa7b;width: 15%;">HC</td>
                <td style="background-color: #cbaa7b;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;text-align: right;">OTRAS</td>
                <td style="background-color: #d7efff;width: 15%;"></td>
                <td style="background-color: #d7efff;width: 10%;"></td>
                
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
                <td style="background-color: #ff898b;"></td>
            </tr>
        </table>';

$documento->paginaHorizontal($html);

$documento->imprimeDocumento();