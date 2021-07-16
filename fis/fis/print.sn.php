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
if ($fid->GetPrinterActive("typenschild")) {

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

$wp=new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_SN_WIDTH;
    $height = FIS_PRINT_SN_HEIGHT;

//position correction of each printer at an workplace
$t=$wp->GetPrinterCorrection("typenschild","top")+0;
$l=$wp->GetPrinterCorrection("typenschild","left")+0;



        $pageLayout = array($width, $height); //  or array($height, $width)
        $pdf = new MYPDF('l', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator("FIS");
        $pdf->SetAuthor("FIS" . $wp->wp);
        $pdf->SetTitle("Typenschild");
        $pdf->SetSubject('Typenschild');
        $pdf->SetKeywords('Typenschild');


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
            $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
// Line
            $pdf->Line(0, 9 + $t, 100, 9 + $t, array('width' => 0.2, 'color' => array(0, 0, 0)));

        $o = 0;
        for ($i = 0; $i < 2; $i++) {

            if ($fid->GetIcon("typenschild","logo")) {
                $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 4 + $o + $l, 3.5 + $t, 15.2);
            }

            $pdf->SetFont('helvetica', '', 4.5);
            if ($fid->GetIcon("typenschild","logo")) {
                $pdf->Text(19 + $o + $l, 3.2 + $t, FIS_COMPANY_SERIAL_TXT1);
                $pdf->Text(19 + $o + $l, 4.8 + $t, FIS_COMPANY_SERIAL_TXT2);
                $pdf->Text(19 + $o + $l, 6.6 + $t, FIS_COMPANY_SERIAL_TXT3);
            }

            $pdf->SetFont('helvetica', '', 8);
            //Item No.
            $pdf->Text(3 + $o + $l, 10 + $t, "Item No.:");
            if (count($fid->conf) > 0) {
                $pdf->Text(15 + $o + $l, 10 + $t, $fid->GetMaterialNumber() );
            } else {
                $pdf->Text(15 + $o + $l, 10 + $t, $fid->GetMaterialNumber() );
            }

            $pdf->SetFont('helvetica', '', 8);
            //Bezeichnung
            $pdf->Text(3 + $o + $l, 14 + $t, substr($fid->GetMaterialName(),0,19));


            $pdf->Rect(32 + $o + $l, 10.9 + $t, 4, 2, '', array(), array(0, 0, 0));
            $pdf->SetFont('helvetica', '', 4);
            $pdf->Text(32 + $o + $l, 11 + $t, "SN");

            //SN
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(36 + $o + $l, 10 + $t, $fid->GetSerialnumber());

            //factory icon
            $x = 32 + $o + $l;
            $y = 17 + $t;

            $pdf->Polygon(array(0 + $x, 0 + $y, 4 + $x, 0 + $y, 4 + $x, -3 + $y, 3 + $x, -3 + $y, 3 + $x, -2 + $y,
                3 + $x, -2 + $y, 3 + $x, -1.5 + $y,
                2.5 + $x, -2 + $y, 2 + $x, -1.5 + $y,
                1.5 + $x, -2 + $y, 1 + $x, -1.5 + $y,
                0.5 + $x, -2 + $y, 0 + $x, -1.5 + $y,
            ), '', array('width' => 0.2, 'color' => array(0, 0, 0)), array(0, 0, 0));


            //FD Datum
            $pdf->Text(36 + $o + $l, 14 + $t, $fid->GetFDatum("Y-m") );
            $o = 49;
        }




//ICONS
    $startx=5;
    $starty=22;
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


    if ($fid->GetIconVDE()) {
        $pdf->Image("../picture/vde.png", $startx + $l, $starty + $t, 5);   //x 13.9
        $startx=$startx+6;
    }
    if ($fid->GetIconVDEGS()) {
        $startx= $startx-1;
        $pdf->Image("../picture/vdegs.png", $startx + $l, $starty + $t-0.75, 5);  //12.9
        $startx=$startx+6;
    }
    if ($fid->GetIconEAC()) {
        $pdf->Image("../picture/eac.png", $startx + $l, $starty + $t, 4.0);   //x+6
        $startx=$startx+5;
    }
    if ($fid->GetIconWEEE()) {
        $pdf->Image("../picture/weee.png", $startx + $l, $starty + $t, 2.9 ); //x+6
        $startx=$startx+6;
    }
*/

    if ($fid->GetIcon("typenschild","info")) {
        $pdf->Image("../picture/info.png", $startx + $l, $starty + $t, 5); //6
        $startx=$startx+6;
    }
    if ($fid->GetIcon("typenschild","ce")) {
        $pdf->Image("../picture/ce.png", $startx + $l, $starty + $t, 5); //5
        $startx=$startx+5;
    }
    if ($fid->GetIcon("typenschild","ipx4")) {
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX4");
        $startx=$startx+13.9;   //x 3.1  y -1.3
    }
    if ($fid->GetIcon("typenschild","ipx6")) {
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->Text($startx + $l, ($starty-1.4) + $t, "IPX6");
        $startx=$startx+13.9; //x 3.1  y -1.3
    }


    if ($fid->GetIcon("typenschild","vde")) {
        $pdf->Image("../picture/vde.png", $startx + $l, $starty + $t, 5);   //x 13.9
        $startx=$startx+6;
    }
    if ($fid->GetIcon("typenschild","vdegs")) {
        $startx= $startx-1;
        $pdf->Image("../picture/vdegs.png", $startx + $l, $starty + $t-0.75, 5);  //12.9
        $startx=$startx+6;
    }
    if ($fid->GetIcon("typenschild","eac")) {
        $pdf->Image("../picture/eac.png", $startx + $l, $starty + $t, 4.0);   //x+6
        $startx=$startx+5;
    }
    if ($fid->GetIcon("typenschild","weee")) {
        $pdf->Image("../picture/weee.png", $startx + $l, $starty + $t, 2.9 ); //x+6
        $startx=$startx+6;
    }

    $pdf->SetFont('helvetica', '', 7);
//Leistungsangaben
    $pdf->Text(4 + $l, 18 + $t, $fid->GetPowerDesc());

//Leistungsangaben
    $pdf->Text(4 + $l + $o, 18 + $t, $fid->GetMaxLoad());

//Barcode
        $pdf->write1DBarcode("0".$fid->fid, 'I25', 52 + $l, 21 + $t, '', 8, 0.3, $style, 'N');
        $pdf->write2DBarcode("0" . $fid->fid, 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');


// move pointer to last page
        $pdf->lastPage();

        if ($_GET['asemail'] == 1) {
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
            AddSessionMessage("success", "E-Mail Anhang Typenschild für <code> SN " . $fid->GetSerialnumber()  . " </code> erstellt");

        } elseif ($_GET['asview'] == 1) {
            $pdf->Output('Typenschild-' . $fid->fid . '.pdf', 'I');
            AddSessionMessage("success", "Druckansicht Typenschild für <code> SN " . $fid->GetSerialnumber()  . " </code> erstellt");
        } else {
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("typenschild") . '/Typenschild-' . $fid->fid . '-'.time().'.pdf';
            $pdf->Output($link, 'F');
            $txt = "Typenschild für <code> SN " .$fid->GetSerialnumber() . " </code> unter <code> $link </code> erstellt";
            $fid->SaveHistory($txt);
            AddSessionMessage("success", $txt);
        }

} else{
    AddSessionMessage("warning", "Typenschild für <code> SN ".$fid->GetSerialnumber() ." </code> nicht erlaubt");
}