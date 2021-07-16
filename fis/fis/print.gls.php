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
if ($_GET['fmid'] == "") $_GET['fmid'] = 0;
$fmid = new FisReturn($_GET['fmid']);

//is print allowed in this version ?
if ($fmid->GetPrinterActive()) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_GLS_WIDTH;
    $height = FIS_PRINT_GLS_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("gls", "top") + 0;
    $l = $wp->GetPrinterCorrection("gls", "left") + 0;


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
                $this->Rect(0, 0, 1, 140, 'DF', array(255, 255, 255), array(255, 255, 255));


            }
        }

    }




    $pageLayout = array($width, $height); //  or array($height, $width)
    $pdf = new MYPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator("FIS");
    $pdf->SetAuthor("FIS" . $wp->wp);
    $pdf->SetTitle("GLS Versandlabel");
    $pdf->SetSubject('GLS');
    $pdf->SetKeywords('GLS');


    $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



for ($i=0; $i< $fmid->GetPrinterPageCountGLS(); $i++) {
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


    $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 10 + $l, 190 + $t, 25);

//Delivery
    $pdf->SetFont('helvetica', 'B', 10);


    if ($_REQUEST['fmid'] > 0) {


        $pdf->MultiCell(70, 20 + $t,substr($fmid->data['lieferung'], 0,100),0,"",false,3,70+$l,20+$t);
        $pdf->Text(70 + $l, 30 + $t,substr($fmid->data['lansprechpartner'], 0,40));
        $pdf->Text(70 + $l, 35 + $t,substr($fmid->data['lstrasse'], 0,40));
        $pdf->Text(70 + $l, 40 + $t,substr($fmid->data['lplz'], 0,40));
        $pdf->Text(81 + $l, 40 + $t,substr($fmid->data['lort'], 0,40));
        $pdf->Text(70 + $l, 45 + $t,substr($fmid->data['lland'], 0,40));
    } else {
        $pdf->MultiCell(70, 20 + $t, $_SESSION['gls_delivery'], 0, "", false, 3, 70 + $l, 20 + $t);
    }
    $pdf->SetFont('helvetica', '', 10);
    if ($_REQUEST['fmid'] > 0) {
        $pdf->Text(70 + $l, 56 + $t, "Tel.: " . substr($fmid->data['ltelefon'], 0, 15));
        $pdf->Text(115 + $l, 56 + $t, "FMID: " . substr($fmid->fmid, 0, 40));
    } else {
        $pdf->Text(70 + $l, 56 + $t, "Tel.: " . $_SESSION['gls_telefon']);
    }


//Count
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(56 + $l, 68 + $t, "1");
//Weight
    $pdf->SetFont('helvetica', '', 10);
    if ($_REQUEST['fmid'] > 0) {
        $pdf->Text(60 + $l, 79 + $t, round(intval($fmid->data['gewichtvorher']), 0) . " kg");
    } else {
        $pdf->Text(60 + $l, 79 + $t, $_SESSION['gls_weight'] . " kg");
    }
//Weight
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Text(52 + $l, 90 + $t, date("d.m.Y", time()));


    $pdf->SetFont('helvetica', '', 8);

    $address=$wp->GetPrinterAddressFields();

//Address
    $pdf->MultiCell(50, 20 + $t, FisTranslate($address['firma']), 0, "", false, 3, 79 + $l, 70 + $t);
    $pdf->Text(79 + $l, 77 + $t, substr(FisTranslate($address['strasse']), 0, 40));
    $pdf->Text(79 + $l, 80 + $t, substr(FisTranslate($address['plz']), 0, 40));
    $pdf->Text(90 + $l, 80 + $t, substr(FisTranslate($address['ort']), 0, 40));
    $pdf->Text(79 + $l, 83 + $t, substr(FisTranslate($address['land']), 0, 40));

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(79 + $l, 87 + $t,"Fehlermeldenummer ".substr($fmid->fmid, 0,40));
    $pdf->Text(79 + $l, 90 + $t, GetArtikelName("text",$fmid->data['fmartikelid']));


//Delivery
    $pdf->SetFont('helvetica', 'B', 10);
    if ($_REQUEST['fmid'] > 0) {
        $pdf->MultiCell(70, 20 + $t, substr($fmid->data['lieferung'], 0, 100), 0, "", false, 3, 70 + $l, 125 + $t);
        $pdf->Text(70 + $l, 135 + $t, substr($fmid->data['lansprechpartner'], 0, 40));
        $pdf->Text(70 + $l, 140 + $t, substr($fmid->data['lstrasse'], 0, 40));
        $pdf->Text(70 + $l, 145 + $t, substr($fmid->data['lplz'], 0, 40));
        $pdf->Text(81 + $l, 145 + $t, substr($fmid->data['lort'], 0, 40));
        $pdf->Text(70 + $l, 150 + $t, substr($fmid->data['lland'], 0, 40));

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Text(70 + $l, 150 + $t, substr($fmid->data['ltelefon'], 0, 40));
    } else {
        $pdf->MultiCell(70, 20 + $t, $_SESSION['gls_delivery'], 0, "", false, 3, 70 + $l, 120 + $t);
    }

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(70 + $l, 165 + $t, "WE-Datum:       " . substr($fmid->data['fmdatum'],0,16) );

    $pdf->Text(70 + $l, 170 + $t, "Versand-Datum: " . date("Y-m-d H:i", time()) );
    $pdf->SetFont('helvetica', 'B', 16);
    if ($_REQUEST['fmid'] > 0) {
        $pdf->Text(70 + $l, 175 + $t, "FMID: " . $fmid->fmid);
    } else {
        $pdf->Text(70 + $l, 175 + $t, "UNIVERAL-Etikett");
    }

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(70 + $l, 185 + $t, GetArtikelName("text",$fmid->data['fmartikelid']));

    $pdf->SetFont('helvetica', 'N', 14);
    $pdf->Text(70 + $l, 190 + $t, "SN: ".substr($fmid->data['fmsn'], 0, 50));



//Barcode

        //$pdf->StartTransform();
        //$pdf->Rotate(90, (20 + $l), (140 + $t));
    $pdf->write1DBarcode("0" . $fmid->fmid, 'I25', 5 + $l, 170 + $t, 35, 20, 3.0, $style, 'N');
        //$pdf->StopTransform();

        //$pdf->write2DBarcode("0" . $fmid->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');

    } //pagecounter

// move pointer to last page
    $pdf->lastPage();

    if ($_GET['asemail'] == 1) {
        $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
        AddSessionMessage("success", "E-Mail Anhang GLS-Versandlabel für <code> WEID " . $fmid->fmid . " </code> erstellt");

    } elseif ($_GET['asview'] == 1) {
        $pdf->Output('Einbauetikett-' . $fmid->fmid . '.pdf', 'I');
        AddSessionMessage("success", "Druckansicht GLS-Versandlabel für <code> WEID " . $fmid->fmid . " </code> erstellt");
    } else {
        $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("einbau") . '/Einbauetikett-' . $fmid->fmid . '.pdf';
        $pdf->Output($link, 'F');
        $txt = "GLS-Versandlabel für <code> FMID " . $fmid->fmid . " </code> unter <code> $link </code> erstellt";
        $d = new FisWorkflow($txt, "fmid", $fmid->fmid);
        $d->Add();
        AddSessionMessage("success", $txt);
    }


} else {
    AddSessionMessage("warning", "GLS-Versandlabel für <code> FMID " . $fmid->fmid . " </code> nicht erlaubt");
}
