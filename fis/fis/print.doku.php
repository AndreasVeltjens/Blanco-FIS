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

$fid = new FisFid($_SESSION['fid']);

//is print allowed in this version ?
if ($fid->GetPrinterActive("dokuset")) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_DOKUSET_WIDTH;
    $height = FIS_PRINT_DOKUSET_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("dokuset", "top") + 0;
    $l = $wp->GetPrinterCorrection("dokuset", "left") + 0;

// Extend the TCPDF class to create custom Header and Footer

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
         *
         * // Page footer
         * public function Footer() {
         * // Position at 15 mm from bottom
         * if (is_file(FOOTER_LOGO_filename)) {
         * $this->Image(FOOTER_LOGO_filename,FOOTER_LOGO_positionx,FOOTER_LOGO_positiony,FOOTER_LOGO_sizex,FOOTER_LOGO_sizey);
         * }
         *
         *
         * $this->SetFont('helvetica', '', 8);
         * // Page number
         * $this->Text(180,280, "Seite ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(),false,false,true,0,0,'L' );
         *
         *
         * }
         */
    }

}



        $pageLayout = array($width, $height); //  or array($height, $width)
        $pdf = new MYPDF('l', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator("FIS");
    $pdf->SetAuthor("FIS" . $wp->wp);
        $pdf->SetTitle("Montage");
        $pdf->SetSubject('Mounting');
        $pdf->SetKeywords('Mounting');


        $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


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
            'fontsize' => 2,
            'stretchtext' => 4
        );


// Rect
        $pdf->Rect(0, 0, 100, 35, 'DF', array(), array(255, 255, 255));
// Line
        $pdf->Line(0, 9 + $t, 100, 9 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));


    $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 4 + $o + $l, 3.5 + $t, 15.5, 4.5);


            $pdf->SetFont('helvetica', '', 4.5);

    $pdf->Text(19 + $o + $l, 3.2 + $t, FIS_COMPANY_SERIAL_TXT1);
            $pdf->Text(19 + $o + $l, 4.8 + $t, "Flehinger Str.56 75638 Oberderdingen");
            $pdf->Text(19 + $o + $l, 6.6 + $t, "Made in Germany");


            $pdf->SetFont('helvetica', '', 8);
            //Item No.
            $pdf->Text(3 + $o + $l, 10 + $t, "Item No.");
            if (count($fid->conf) > 0) {
                $pdf->Text(14 + $o + $l, 10 + $t, $fid->GetMaterialNumber() . "-" . $fid->GetArtikelPlug());
            } else {
                $pdf->Text(14 + $o + $l, 10 + $t, $fid->GetMaterialNumber() . "-" . substr($fid->GetVersion(), 8, 4));
            }

            $pdf->SetFont('helvetica', '', 8);
            //Bezeichnung
            $pdf->Text(3 + $o + $l, 14 + $t, $fid->GetMaterialName());


            $pdf->Rect(30 + $o + $l, 10.9 + $t, 4, 2, '', array(), array(0, 0, 0));
            $pdf->SetFont('helvetica', '', 4);
            $pdf->Text(30 + $o + $l, 11 + $t, "SN");

            //SN
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(35 + $o + $l, 10 + $t, $fid->GetSerialnumber());

            //factory icon
            $x = 30 + $o + $l;
            $y = 17 + $t;

            $pdf->Polygon(array(0 + $x, 0 + $y, 4 + $x, 0 + $y, 4 + $x, -3 + $y, 3 + $x, -3 + $y, 3 + $x, -2 + $y,
                3 + $x, -2 + $y, 3 + $x, -1.5 + $y,
                2.5 + $x, -2 + $y, 2 + $x, -1.5 + $y,
                1.5 + $x, -2 + $y, 1 + $x, -1.5 + $y,
                0.5 + $x, -2 + $y, 0 + $x, -1.5 + $y,
            ), '', array('width' => 0.2, 'color' => array(0, 0, 0)), array(0, 0, 0));


            //FD Datum
            $pdf->Text(35 + $o + $l, 14 + $t, $fid->GetFDatum());


        $pdf->SetFont('helvetica', '', 7);
//Leistungsangaben
        $pdf->Text(4 + $l, 18 + $t, $fid->GetPowerDesc());

//Leistungsangaben
        $pdf->Text(4 + $l + $o, 18 + $t, $fid->GetMaxLoad());


//ICONS
        if ($fid->GetIconInfo()) {
            $pdf->Image("../picture/info.png", 6 + $l, 22 + $t, 4, 4);
        }
        if ($fid->GetIconInfo()) {
            $pdf->Image("../picture/ce.png", 11 + $l, 22 + $t, 3, 4);
        }
        if ($fid->GetIconIPX4()) {
            $pdf->SetFont('helvetica', 'B', 15);
            $pdf->Text(14.1 + $l, 20.7 + $t, "IPX4");
        }
        if ($fid->GetIconIPX6()) {
            $pdf->SetFont('helvetica', 'B', 15);
            $pdf->Text(14.1 + $l, 20.7 + $t, "IPX6");
        }
        if ($fid->GetIconVDE()) {
            $pdf->Image("../picture/vde.png", 28 + $l, 22 + $t, 4, 4);
        }
        if ($fid->GetIconVDEGS()) {
            $pdf->Image("../picture/vdegs.png", 27 + $l, 22 + $t, 6, 4);
        }
        if ($fid->GetIconEAC()) {
            $pdf->Image("../picture/eac.png", 33 + $l, 22 + $t, 4, 4);
        }
        if ($fid->GetIconWEEE()) {
            $pdf->Image("../picture/weee.png", 39 + $l, 22 + $t, 3.5, 4);
        }

//Barcode
        $pdf->write1DBarcode("0" . $fid->fid, 'I25', 52 + $l, 21 + $t, '', 8, 0.3, $style, 'N');
        $pdf->write2DBarcode("0" . $fid->fid, 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');


// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Dokuset für <code> SN " . $fid->GetSerialnumber() . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Dokumentation-' . $fid->fid . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Dokuset für <code> SN " . $fid->GetSerialnumber() . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("dokuset") . '/Dokumentation-' . $fid->fid . '-'.time().'.pdf';
            $pdf->Output($link, 'F');
            $txt = "Dokumentation für <code> SN " . $fid->GetSerialnumber()  . " </code> unter <code> $link </code> erstellt";
            $fid->SaveHistory($txt);
            AddSessionMessage("success", $txt);
        }


} else {
        AddSessionMessage("warning", "Dokumentation für <code> SN " . $fid->GetSerialnumber()  . " </code> nicht erlaubt");
}
