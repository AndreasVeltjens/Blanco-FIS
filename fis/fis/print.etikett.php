<?php

/**
 * Created by PhpStorm.
 * User: Andreas Veltjens lok
 * Date: 29.04.2016
 * Time: 17:31
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

if (!class_exists("FisFid")) {
    include_once('./config.php');
}
if (!class_exists("TCPDF")) {
    require_once('../tcpdf_min/tcpdf.php');
}


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
$width = FIS_PRINT_NEUTRAL_WIDTH;
$height = FIS_PRINT_NEUTRAL_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("neutraletikett", "top") + 0;
    $l = $wp->GetPrinterCorrection("neutraletikett", "left") + 0;


    if (!class_exists("MYPDF")) {
        class MYPDF extends TCPDF
        {

            /**
             * //Page header
             * public function Header() {
             * // Logo
             * if (is_file(HEADER_LOGO_filename)) {
             * $this->Image(HEADER_LOGO_filename,HEADER_LOGO_positionx,HEADER_LOGO_positiony,HEADER_LOGO_sizex,HEADER_LOGO_sizey);
             * }
             * $this->SetY(10);
             * // Set font
             * $this->SetFont('helvetica', 'B',12 );
             * // Title
             * $this->Cell(5, 15, PDF_HEADER_FTYPE, 0, false, 'L', 0, '', 0, false, 'M', 'M');
             * }
             */
            // Page footer
            public function Footer() {
                $this->Rect(0, 0, 1, 100, 'DF', array(255, 255, 255), array(255, 255, 255));


            }
        }

    }




        $pageLayout = array($width, $height); //  or array($height, $width)
        $pdf = new MYPDF('l', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator("FIS");
        $pdf->SetAuthor("FIS" . $wp->wp);
        $pdf->SetTitle("Universal-Etikett");
        $pdf->SetSubject('Universal-Etikett');
        $pdf->SetKeywords('Universal-Etikett');


        $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        $pdf->AddPage();
        $pdf->SetHeaderMargin(1);
        $pdf->SetFooterMargin(1);
        $pdf->SetMargins(2, 0, 0);


// define barcode style
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 10,
            'stretchtext' => 2
        );


// Rect
    $pdf->Rect(2, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
// Text
$pdf->SetFont('helvetica', '', $_SESSION['txt_size']);
    $pdf->SetAbsXY(0,0);
    $pdf->writeHTML($_SESSION['etikett_txt'], true, false, true, false, '');


// move pointer to last page
    $pdf->lastPage();

    if ($_GET['asemail'] == 1) {
        $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
        AddSessionMessage("success", "E-Mail Anhang Neutraletikett erstellt");

    } elseif ($_GET['asview'] == 1) {
        $pdf->Output('Einbauetikett-' . time() . '.pdf', 'I');
        AddSessionMessage("success", "Druckansicht Neutraletikett für erstellt");
    } else {
        $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("neutraletikett") . '/Neutraletikett-'.time().'.pdf';
        $pdf->Output($link, 'F');
        $txt = "Neutraletikett unter <code> $link </code> erstellt";
        AddSessionMessage("success",$txt,"Etikett erstellt");
        $pdf->close();
    }

