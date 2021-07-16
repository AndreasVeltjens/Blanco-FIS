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

if (!class_exists("FisReturn")) {
    include_once('./config.php');
}
if (!class_exists("TCPDF")) {
    require_once('../tcpdf_min/tcpdf.php');
}

$fmid = new FisReturn($_SESSION['fmid']);

//is print allowed in this version ?
if ($fmid->GetPrinterActive()) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_RETURN_WIDTH;
    $height = FIS_PRINT_RETURN_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("wareneingang", "top") + 0;
    $l = $wp->GetPrinterCorrection("wareneingang", "left") + 0;


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
        $pdf->SetTitle("Fehlermeldung");
        $pdf->SetSubject('Errormessage');
        $pdf->SetKeywords('Errormessage');


        $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



 for ($i=0; $i< $fmid->GetPrinterPageCount(); $i++) {
     $pdf->AddPage();
     $pdf->SetHeaderMargin(0);
     $pdf->SetFooterMargin(0);
     $pdf->SetMargins(0, 0, 0);


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
     $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
// Line
     $pdf->Line(0 + $l, 11 + $t, 80 + $l, 11 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
     $pdf->Line(0 + $l, 28 + $t, 80 + $l, 28 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
     $pdf->Line(80 + $l, 50 + $t, 80 + $l, 1 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));


     $pdf->Image("../config/logo_" . FIS_ID . ".png", 1 + $l, 1 + $t, 25);


     $pdf->SetFont('helvetica', '', 10);
     $pdf->Text(28 + $l, 4 + $t, "WE-Datum: " . substr($fmid->data['fmdatum'],0,10) );

     $pdf->SetFont('helvetica', 'B', 16);
     $pdf->Text(3 + $l, 12 + $t, "FMID: ".$fmid->fmid);

   

     $pdf->SetFont('helvetica', '', 12);
     $pdf->Text(3 + $l, 20 + $t, GetArtikelName("text",$fmid->data['fmartikelid']));

     $pdf->SetFont('helvetica', 'N', 16);
     $pdf->Text(3 + $l, 28 + $t, "SN: ".substr($fmid->data['fmsn'], 0, 50));


     $pdf->SetFont('helvetica', '', 8);
     $pdf->Text(3 + $l, 33 + $t, substr($fmid->data['fm12notes'], 0,40));
     $pdf->Text(3 + $l, 38 + $t,"Maßnahmen:   ".substr($fmid->data['fmnotes'], 0,40));
     $pdf->Text(3 + $l, 42 + $t,"Rechnung an:  " .substr($fmid->data['rechnung'], 0,40));
     $pdf->Text(3 + $l, 45 + $t,"Lieferung an: ".substr($fmid->data['leieferung'], 0,40));
    


//Barcode
     $pdf->Rect(80+$l, 0, $width, $height, 'DF', array(), array(255, 255, 255));

     $pdf->StartTransform();
     $pdf->Rotate(90, (80 + $l), (45 + $t));
     $pdf->write1DBarcode("0" . $fmid->fmid, 'I25', 80 + $l, 45 + $t, 35, 20, 3.0, $style, 'N');
     $pdf->StopTransform();

     //$pdf->write2DBarcode("0" . $fmid->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');

 } //pagecounter

// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Fehlermeldungetikett für <code> WEID " . $fmid->fmid . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Einbauetikett-' . $fmid->fmid . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Fehlermeldungetikett für <code> WEID " . $fmid->fmid . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("einbau") . '/Einbauetikett-' . $fmid->fmid . '.pdf';
            $pdf->Output($link, 'F');
            $txt = "Fehlermeldungetikett für <code> WEID " . $fmid->fmid . " </code> unter <code> $link </code> erstellt";
            $fmid->SaveHistory($txt);
            AddSessionMessage("success", $txt);
        }


} else {
        AddSessionMessage("warning", "Fehlermeldungetikett für <code> FMID " . $fmid->fmid . " </code> nicht erlaubt");
}
