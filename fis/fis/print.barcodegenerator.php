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

if (!class_exists("FisMaterial")) {
    include_once('./config.php');
}
if (!class_exists("TCPDF")) {
    require_once('../tcpdf_min/tcpdf.php');
}

if (GetArtikelName("isFisArtikel", $_SESSION['bc_material'])) {
    $m = new FisMaterial(GetArtikelName("searchbymaterial", $_SESSION['bc_material']));
} else {
    $m = new FisMaterial($_SESSION['material']);
}
    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
$width = FIS_PRINT_DOKUSET_WIDTH;
$height = FIS_PRINT_DOKUSET_WIDTH;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("bc", "top") + 0;
    $l = $wp->GetPrinterCorrection("bc", "left") + 0;


    if (!class_exists("MYPDF")) {
        class MYPDF extends TCPDF
        {


             //Page header
              public function Header() {
              // Logo
                  $this->Image("../config/etikett_logo_" . FIS_ID . ".png", 151, 8, 35);

             $this->SetY(10);
             // Set font
             $this->SetFont('helvetica', 'B',12 );

             }

            // Page footer
            public function Footer() {

                $this->SetY(-15);
                // Set font
                $this->SetFont('helvetica', '', 8);
                // Page number
                $this->Text(180,280,"Seite ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(),false,false,true,0,0,'L' );



            }
        }

    }


    $desc= "";
    $dokument="Barcode-Generator für eine Fertigungsrückmeldung";


    $pageLayout = array($width, $height); //  or array($height, $width)
    $pdf = new MYPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator("FIS");
    $pdf->SetAuthor("FIS" . $wp->wp);
    $pdf->SetTitle("Barcode Generator");
    $pdf->SetSubject('BG');
    $pdf->SetKeywords('BG');


    $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



    $pdf->AddPage();
    $pdf->SetHeaderMargin(20);
    $pdf->SetFooterMargin(15);
    $pdf->SetMargins(20, 0, 0);




// -----------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 10);

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
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);



// Rect
    //    $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
    $pdf->SetFont('helvetica', 'B',11 );
    $pdf->Cell(20, 15, "          ".$dokument, 0, false, 'L', 0, '', 0, false, 'M', 'M');


// define barcode style

$pdf->Ln();
$pdf->Cell(0, 0, 'Material: '.$m->GetMaterialDesc(), 0, 1);
$pdf->SetXY(30,30);
// Barcode
$pdf->Ln();

$pdf->Ln();
// CODE 39 + CHECKSUM
$pdf->Cell(0, 0, 'Material', 0, 1);
$pdf->write1DBarcode($_SESSION['bc_material'], 'C39', '', '', '', 18, 0.4, $style, 'N');


if (GetArtikelName("isFisArtikel", $_SESSION['bc_material']) && $_SESSION['bc_compare'] == "on") {

    $material = new FisMaterial(GetArtikelName("searchbymaterial", $_SESSION['bc_material']));
    $material->group = $material->data['Gruppe'];
    $material->LoadAllData();
    if (count($material->dataall) > 0) {
        foreach ($material->dataall as $i => $mat) {
            $x += 0;
            $y += 32;
            if (stripos($mat['Bezeichnung'], "rot") > 0) {
                $pdf->SetTextColor(255, 0, 0);
            } elseif (stripos($mat['Bezeichnung'], "blau") > 0) {
                $pdf->SetTextColor(0, 0, 255);
            } elseif (stripos($mat['Bezeichnung'], "grün") > 0) {
                $pdf->SetTextColor(0, 255, 0);
            } elseif (stripos($mat['Bezeichnung'], "gelb") > 0) {
                $pdf->SetTextColor(255, 255, 0);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $pdf->SetFont('helvetica', 'B', 11);

            $pdf->Text(130 + $x, 0 + $y, 'Material: ' . $mat['Bezeichnung'], 0, 1);

            // CODE 39 + CHECKSUM
            $pdf->write1DBarcode($mat['Nummer'], 'C39', 130 + $x, 5 + $y, '', 18, 0.4, $style, 'N');
        }
    }
}
$x = 0;
$y = 30;
$pdf->SetTextColor(0, 0, 0);
$x += 0;
$y += 32;
$pdf->Text(20 + $x, 10 + $y, 'Version', 0, 1);
$pdf->write1DBarcode($_SESSION['bc_version'], 'C39', 20 + $x, 20 + $y, '', 18, 0.4, $style, 'N');

$r=explode(";",$m->data['merkmale']);
$count=count($r);

for ($i=0;$i<$count; $i++) {
    $x += 0;
    $y += 32;
    $pdf->Text(20 + $x, 10 + $y, $r[($i)], 0, 1);
    $pdf->write1DBarcode($_SESSION['bc_fsn' . ($i + 1)], 'C39', 20 + $x, 20 + $y, '', 18, 0.4, $style, 'N');
}

// move pointer to last page
    $pdf->lastPage();

    if ($_GET['asemail'] == 1) {
        $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
        AddSessionMessage("success", "E-Mail Anhang Barcode-Generator für <code> Material " . $_SESSION['bc_material'] . " </code> erstellt");

    } elseif ($_REQUEST['asview'] == 1) {
        $pdf->Output('Barcode-' . $fmid->fmid . '.pdf', 'I');
        AddSessionMessage("success", "Druckansicht Barcode-Generator für <code> Material " . $_SESSION['bc_material'] . " </code> erstellt");
    } else {
        $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("bg") . '/barcodegenerator-' . $_SESSION['bc_material'] . '.pdf';
        $pdf->Output($link, 'F');
        $txt = "Barcode-Generator für <code> Material " . $_SESSION['bc_material'] . " </code> unter <code> $link </code> erstellt";

        AddSessionMessage("success", $txt);
    }

