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

if (!class_exists("FisWarehouse")) {
    include_once('./config.php');
}
if (!class_exists("TCPDF")) {
    require_once('../tcpdf_min/tcpdf.php');
}

$we = new FisWarehouse($_SESSION['weid']);

//is print allowed in this version ?
if ($we->GetPrinterActive()) {


 $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_WAREHOUSE_WIDTH;
    $height = FIS_PRINT_WAREHOUSE_HEIGHT;

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
        $pdf->SetTitle("Wareneingang");
        $pdf->SetSubject('Warehouse');
        $pdf->SetKeywords('Warehouse');


        $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



 for ($i=0; $i< $we->GetPrinterPageCount(); $i++) {
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
     $pdf->Line(0 + $l, 11 + $t, 68 + $l, 11 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
     $pdf->Line(0 + $l, 30 + $t, 68 + $l, 30 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
     $pdf->Line(68 + $l, 50 + $t, 68 + $l, 1 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));

     $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 2 + $o + $l, 1 + $t, 23);


 //    $pdf->Image($we->GetPictureLink(), 50 +$l, 14 + $t, 13);

     $pdf->SetFont('helvetica', '', 4.5);

     //$pdf->Text(19 + $o + $l, 3.2 + $t, "Wareneingang");
     //$pdf->Text(19 + $o + $l, 4.8 + $t, "Flehinger Str.56 75638 Oberderdingen");
     //$pdf->Text(19 + $o + $l, 6.6 + $t, "Made in Germany");


     $pdf->SetFont('helvetica', '', 10);
//Beschriftung
     $pdf->Text(26 + $l, 4 + $t, "WE-Datum: " . $we->data['we_datum']);
     $pdf->SetFont('helvetica', 'B', 18);
     $pdf->Text(1 + $l, 12 + $t, $we->data['we_nummer']);

     $pdf->SetFont('helvetica', 'B', 12);
     $pdf->Text(1 + $o + $l, 20 + $t, substr($we->data['we_bezeichnung'],0,30) );
     $pdf->Text(1 + $o + $l, 25 + $t, substr($we->data['we_bezeichnung'],30,66) );


     $pdf->SetFont('helvetica', '', 10);
     $pdf->Text(1 + $l, 30 + $t, substr($we->data['we_lieferant'], 0, 36));

     $pdf->Text(1 + $l, 35 + $t, substr($we->data['we_notes'], 0,36));

     $pdf->Text(1 + $l, 45 + $t, substr($we->data['version'], 0,36));

     if ($we->AllowPrintPackageCount()) {
         $pdf->SetFont('helvetica', '', 9);
         $pdf->Text(20 + $l, 45 + $t, "Packstück: " . ($i + 1) . " / " . substr($we->data['we_packstueck'], 0, 20));
     }

     $pdf->Rect(68+$l, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
     $pdf->SetFont('helvetica', '', 6);
     $pdf->Text(70 + $l, 2 + $t, "Entscheidung am " . date("Y-m-d",time() ));

     $pdf->SetFont('helvetica', 'B', 24);
     $pdf->StartTransform();
     $pdf->Rotate(90, (68 + $l), (50 + $t));
     $pdf->Text(68 + $l, 50 + $t, substr( $we->PrintStatus(),0,20 ) );
     $pdf->StopTransform();


//Barcode


     $pdf->StartTransform();
     $pdf->Rotate(90, (80 + $l), (45 + $t));
     $pdf->write1DBarcode("0" . $we->we_id, 'I25', 80 + $l, 45 + $t, 35, 20, 3.0, $style, 'N');
     $pdf->StopTransform();

     //$pdf->write2DBarcode("0" . $we->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');

 } //pagecounter

// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Wareneingangsettikett für <code> WEID " . $we->we_id . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Einbauetikett-' . $we->we_id . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Wareneingangsettikett für <code> WEID " . $we->we_id . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] .FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("wareneingang") . '/Wareneingang-' . $we->we_id . '.pdf';
            $pdf->Output($link, 'F');
            $txt = "Wareneingangsettikett für <code> WEID " . $we->we_id . " </code> unter <code> $link </code> erstellt";
            $w = new FisWorkflow($txt,"weid",$we->we_id);
            $w->Add();
            AddSessionMessage("success", $txt);
        }


} else {
        AddSessionMessage("warning", "Wareneingangsettikett für <code> WEID " . $we->we_id . " </code> nicht erlaubt");
}
