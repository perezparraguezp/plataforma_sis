<?php


class documento{
    public $id_respaldo,$titulo_sup,$titulo_inf;
    public $html,$id_empleado,$nombre_documento;
    public $folio,$numero_decreto,$anio_decreto,$fecha_decreto;
    public $tipo_documento,$detalle_documento;
    public $proceso_municipal,$sector_decreto,$rut_decreto;
    public $nombre_decreto,$texto_decreto,$sector_afectado,$rut_afectado;

    public $pdf;

    function __construct($titulo_sup,$titulo_inf,$nombre_documento){
        $this->titulo_sup=$titulo_sup;
        $this->titulo_inf=$titulo_inf;
        $this->nombre_documento=$nombre_documento;
        session_start();
        require_once('../../../Informes/config/lang/cat.php');
        require_once('../../../Informes/tcpdf.php');
        // Creamos Documento
        define ('PDF_PAGE_FORMAT', 'A4');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('SIS - EH-OPEN');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->SetKeywords('Documento Salud, PDF, SIS - EH-OPEN');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->titulo_sup, $this->titulo_inf, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('dejavusans', '', 14, '', true);
        $this->pdf = $pdf;

    }
    function paginaVertical($html){
        $this->pdf->AddPage('I', 'A4');
        $this->pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
    }
    function paginaHorizontal($html){
        $this->pdf->AddPage('L', 'A4');
        $this->pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
    }
    function imprimeDocumento(){
        $this->pdf->Output($this->nombre_documento.'.pdf', 'I');
    }

}
