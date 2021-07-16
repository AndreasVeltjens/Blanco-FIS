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

if (isset($_REQUEST['quantity'])){
    $quantity=$_REQUEST['quantity'];
}else{
    $quantity="1 ST";
}

//is print allowed in this version ?
if ($fid->GetPrinterActive("verpackung")) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_PACKAGE_WIDTH;
    $height = FIS_PRINT_PACKAGE_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("verpackung", "top") + 0;
    $l = $wp->GetPrinterCorrection("verpackung", "left") + 0;

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

    $count=$fid->GetPrintCount("verpackung");
    for($i=0; $i<$count; $i++){
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
            'fontsize' => 8,
            'stretchtext' => 1.5
        );

// Rect
        $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
// Line

    if ($fid->GetIcon("verpackung","logo")) {
        $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 2 + $o + $l, 1 + $t, 23);
    }

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(26 + $o + $l, 0.5 + $t, "Item No.:");

    $pdf->SetFont('helvetica', 'B', 16);
    if (count($fid->conf) > 0) {
        $pdf->Text(26 + $o + $l, 3.5 + $t, sprintf("%'.08d\n",$fid->GetMaterialNumber()) );
    } else {
        $pdf->Text(26 + $o + $l, 3.5 + $t, sprintf("%'.08d\n",$fid->GetMaterialNumber()) );
    }
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Text(26 + $o + $l, 11 + $t, substr($fid->GetMaterialName(),0,30) );
    $pdf->Text(26 + $o + $l, 15 + $t, substr($fid->GetMaterialName(),30,66) );

    $pdf->Line(27, 21 + $t, 100, 21 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));


    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(78 + $o + $l, 21 + $t, "Menge:");

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(80+ $o + $l, 25 + $t, $quantity);

    $pdf->Line(1, 33 + $t, 25, 33 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    $pdf->Line(27, 33 + $t, 100, 33 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));


    //ICONS
        $startx=1;
        $starty=35;
        $offsetLinie=0;




 /**
    if ($fid->GetIconInfo()) {
        $pdf->Image("../picture/info.png", $startx + $l, $starty + $t, 5); //6
        $startx=$startx+6;
    }
    if ($fid->GetIconCE()) {
        $pdf->Image("../picture/ce.png", $startx + $l, $starty + $t, 5); //5
        $startx=$startx+5;
    }
    if ($fid->GetIconIPX4()) {
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX4");
        $startx=$startx+13.9;   //x 3.1  y -1.3
    }
    if ($fid->GetIconIPX6()) {
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX6");
        $startx=$startx+13.9; //x 3.1  y -1.3
    }
    if ($startx>20){ $startx=1; $starty=$starty+6; $offsetLinie=4;}

    if ($fid->GetIconVDE()) {
        $pdf->Image("../picture/vde.png", $startx + $l, $starty + $t, 5);   //x 13.9
        $startx=$startx+6;
    }
    if ($fid->GetIconVDEGS()) {
        $pdf->Image("../picture/vdegs.png", $startx + $l, $starty + $t, 5);  //12.9
        $startx=$startx+6;
    }


    if ($fid->GetIconEAC()) {
        $pdf->Image("../picture/eac.png", $startx + $l, $starty + $t, 5);   //x+6
        $startx=$startx+6;
    }


    if ($fid->GetIconWEEE()) {
        $pdf->Image("../picture/weee.png", $startx + $l, $starty + $t, 3.8 ); //x+6
        $startx=$startx+6;
    }
    */


        if ($fid->GetIcon("verpackung","info")) {
            $pdf->Image("../picture/info.png", $startx + $l, $starty + $t, 5); //6
            $startx=$startx+6;
        }
        if ($fid->GetIcon("verpackung","ce") ) {
            $pdf->Image("../picture/ce.png", $startx + $l, $starty + $t, 5); //5
            $startx=$startx+5;
        }
        if ($fid->GetIcon("verpackung","ipx4")) {
            $pdf->SetFont('helvetica', 'B', 15);
            $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX4");
            $startx=$startx+13.9;   //x 3.1  y -1.3
        }
        if ($fid->GetIcon("verpackung","ipx6")) {
            $pdf->SetFont('helvetica', 'B', 15);
            $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX6");
            $startx=$startx+13.9; //x 3.1  y -1.3
        }
        if ($startx>20){ $startx=1; $starty=$starty+6; $offsetLinie=4;}

        if ($fid->GetIcon("verpackung","vde")) {
            $pdf->Image("../picture/vde.png", $startx + $l, $starty + $t, 5);   //x 13.9
            $startx=$startx+6;
        }
        if ($fid->GetIcon("verpackung","vdegs")) {
            $pdf->Image("../picture/vdegs.png", $startx + $l, $starty + $t, 5);  //12.9
            $startx=$startx+6;
        }


        if ($fid->GetIcon("verpackung","eac")) {
            $pdf->Image("../picture/eac.png", $startx + $l, $starty + $t, 5);   //x+6
            $startx=$startx+6;
        }


        if ($fid->GetIcon("verpackung","weee")) {
        $pdf->Image("../picture/weee.png", $startx + $l, $starty + $t, 3.8 ); //x+6
        $startx=$startx+6;
        }



    $pdf->Line(1, 44 +$offsetLinie + $t, 25, 44+$offsetLinie + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));
    // $pdf->Line(26, 44 + $t, 100, 44 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(26 + $o + $l, 35 + $t, "Werk:");

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(34+ $o + $l, 35 + $t, "0001");

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(40 + $o + $l, 35 + $t, "Lagerort:");

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(49+ $o + $l, 35 + $t, "H011");


    if (count($fid->conf) > 0) {

        $pdf->SetFont('helvetica', '', 6);

        $startx=26;
        if (strlen($fid->GetArtikelColor()) >0) {
            $pdf->Text($startx + $o + $l, 38.5 + $t, "Farbe:");
            $pdf->SetFont('helvetica', 'B', 6);
            $pdf->Text($startx+8 + $o + $l, 38.5 + $t, $fid->GetArtikelColor());
            $startx=$startx+18;
        }
        $pdf->SetFont('helvetica', '', 6);
        if (strlen($fid->GetArtikelPlug()) >0) {
            $pdf->Text($startx + $o + $l, 38.5 + $t, "Ausführung:");
            $pdf->SetFont('helvetica', 'B', 6);
            $pdf->Text($startx+12 + $o + $l, 38.5 + $t, $fid->GetArtikelPlug());
            $startx=$startx+20;
        }
        $pdf->SetFont('helvetica', 'B', 6);
        if (strlen($fid->GetArtikelLogo())>0) {
            $pdf->Text($startx + $o + $l, 38.5 + $t, "+ LOGO");
            $startx=$startx+25;
        }
    }

    // Serial number
    if ($quantity=="1 ST") {
        $pdf->SetFont('helvetica', '', 6);
        $pdf->Text(26 + $o + $l, 42 + $t, "SN-Nr.:");
        $pdf->SetFont('helvetica', '', 10);
        //$pdf->Text(60 + $o + $l, 41 + $t, $fid->GetSerialnumber());
        $pdf->write1DBarcode($fid->GetSerialnumber(), 'I25', 29 + $l, 39 + $t, '', 14, 0.7, $style, 'N');
    }

    $pdf->SetFont('helvetica', '', 6);
    $pdf->Text(78 + $o + $l, 35 + $t, "Fauf-Endtermin:");
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(76 + $o + $l, 38 + $t, $fid->GetFDatum());


//Barcode 'CODE 39', 'C39'
    //echo $fid->GetEAN13();

    $pdf->write1DBarcode($fid->GetEAN13(), 'EAN13', 70 + $l, 0 + $t, '', 12, 5, $style);

        //$pdf->write2DBarcode("0" . $fid->fid, 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');

    } // page count

// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Verpackungsetikett für <code> SN " . $fid->GetSerialnumber() . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Verpackung-' . $fid->fid . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Verpackungsetikett für <code> SN " . $fid->GetSerialnumber() . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("verpackung") . '/Verpackung-' . $fid->fid . '-'.time().'.pdf';
            $pdf->Output($link, 'F');
            $txt = "Verpackungsetikett für <code> SN " . $fid->GetSerialnumber() . " </code> unter <code> $link </code> erstellt";
            $fid->SaveHistory($txt);
            AddSessionMessage("success", $txt);
        }


} else {
        AddSessionMessage("warning", "Verpackung für <code> SN " . $fid->GetSerialnumber()  . " </code> nicht erlaubt");
}
