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
if ($fid->GetPrinterActive("einbau")) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_MOUNTING_WIDTH;
    $height = FIS_PRINT_MOUNTING_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("einbau", "top") + 0;
    $l = $wp->GetPrinterCorrection("einbau", "left") + 0;


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
            'fontsize' => 10,
            'stretchtext' => 2
        );


// Rect
        $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
// Line
        $pdf->Line(0, 11 + $t, 100, 11 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));

    if ($fid->GetIcon("verpackung","logo")) {
        $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 4 + $o + $l, 3.5 + $t, 15.5, 4.5);


        $pdf->SetFont('helvetica', '', 4.5);

        $pdf->Text(19 + $o + $l, 3.2 + $t, FIS_COMPANY_SERIAL_TXT1);
        $pdf->Text(19 + $o + $l, 4.8 + $t, FIS_COMPANY_SERIAL_TXT2);
        $pdf->Text(19 + $o + $l, 6.6 + $t, FIS_COMPANY_SERIAL_TXT3);
    }



            $pdf->SetFont('helvetica', 'B', 9);
            //Bezeichnung
            $pdf->Text(3 + $o + $l, 11 + $t,  substr($fid->GetMaterialNumber()."-".$fid->GetMaterialName() ,0,35) );

            $pdf->SetFont('helvetica', '', 8);
           // $pdf->Text(50 + $o + $l, 45 + $t, "Prüfergebnis:");
            $pdf->Circle(73,47,3);

for ($i=0; $i<2; $i++){
            //SN
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(54 + $o + $l, 7 + $t, $fid->GetSerialnumber());

            $pdf->Rect(50 + $o + $l, 7.9 + $t, 4, 2, '', array(), array(0, 0, 0));
            $pdf->SetFont('helvetica', '', 4);
            $pdf->Text(50 + $o + $l, 8 + $t, "SN");


    //factory icon
            $x = 50 + $o + $l;
            $y = 6 + $t;

            $pdf->Polygon(array(0 + $x, 0 + $y, 4 + $x, 0 + $y, 4 + $x, -3 + $y, 3 + $x, -3 + $y, 3 + $x, -2 + $y,
                3 + $x, -2 + $y, 3 + $x, -1.5 + $y,
                2.5 + $x, -2 + $y, 2 + $x, -1.5 + $y,
                1.5 + $x, -2 + $y, 1 + $x, -1.5 + $y,
                0.5 + $x, -2 + $y, 0 + $x, -1.5 + $y,
            ), '', array('width' => 0.2, 'color' => array(0, 0, 0)), array(0, 0, 0));


            //FD Datum
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(54 + $o + $l, 3 + $t, $fid->GetFDatum());
$o=30;
}

        $pdf->SetFont('helvetica', '', 10);
//Beschriftung
    $pdf->Text(3 + $l, 15 + $t, $fid->GetPropertyDesc(1));
    if ($fid->data['fsn1']=="") {
        $pdf->Line(20 + $l, 20 + $t, 60 + $l, 20 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    }else{
        $pdf->Text(25 + $l, 15 + $t, $fid->data['fsn1']);
    }

    $pdf->Text(3 + $l, 25 + $t, $fid->GetPropertyDesc(2));
    if ($fid->data['fsn2']=="") {
        $pdf->Line(20 + $l, 30 + $t, 60 + $l, 30 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    }else{
        $pdf->Text(25 + $l, 25 + $t, $fid->data['fsn2']);
    }

    $pdf->Text(3 + $l, 35 + $t, $fid->GetPropertyDesc(3));
    if ($fid->data['fsn3']=="") {
        $pdf->Line(20 + $l, 40 + $t, 60 + $l, 40 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    }else{
        $pdf->Text(25 + $l, 35 + $t, $fid->data['fsn3']);
    }
/***
    $pdf->Text(3 + $l, 45 + $t, $fid->GetPropertyDesc(4));
    if ($fid->data['fsn4']=="") {
        $pdf->Line(20 + $l, 50 + $t, 60 + $l, 50 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    }else{
        $pdf->Text(25 + $l, 45 + $t, $fid->data['fsn4']);
    }

*/
    $pdf->Line(79+$l, 0+ $t, 79+$l, 51 + $t, array('width' => 0.1, 'color' => array(0, 0, 0)));

    $pdf->write1DBarcode($fid->GetSerialnumber(), 'I25', 1 + $l, 39 + $t, '', 14, 0.7, $style, 'N');


//Barcode
    /**
        $pdf->StartTransform();
        $pdf->Rotate(90,(60+$l),(45+$t));
        $pdf->write1DBarcode( $fid->GetSerialnumber()+0, 'I25', 60 + $l, 45 + $t, 35, 20, 0.5, $style, 'N');
        $pdf->StopTransform();
*/
        $pdf->StartTransform();
        $pdf->Rotate(90,(80+$l),(45+$t));
        $pdf->write1DBarcode($fid->GetSerialnumber()+0, 'I25', 80 + $l, 45 + $t, 35 ,20, 3.0, $style,'N');
        $pdf->StopTransform();

        //$pdf->write2DBarcode("0" . $fid->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');


// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Einbauetikett für <code> SN " .$fid->GetSerialnumber()  . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Einbauetikett-' . $fid->fid . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Einbauetikett für <code> SN " . $fid->GetSerialnumber()  . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("einbau") . '/Einbauetikett-' . $fid->fid . '-'.time().'.pdf';
            $pdf->Output($link, 'F');
            $txt = "Einbauetikett für <code> SN " . $fid->GetSerialnumber()  . " </code> unter <code> $link </code> erstellt";
            $fid->SaveHistory($txt);
            AddSessionMessage("success", $txt);
            $pdf->close();
        }


} else {
        AddSessionMessage("warning", "Einbauetikett für <code> SN " .$fid->GetSerialnumber()  . " </code> nicht erlaubt");
}
