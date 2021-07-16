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
$u=new FisUser($_SESSION['active_user']);
//is print allowed in this version ?
if ($fmid->GetPrinterActive()) {


    $wp = new FisWorkplace($_SESSION['wp']);

//etikett format
    $width = FIS_PRINT_DOKUSET_WIDTH;
    $height = FIS_PRINT_DOKUSET_HEIGHT;

//position correction of each printer at an workplace
    $t = $wp->GetPrinterCorrection("kv", "top") + 0;
    $l = $wp->GetPrinterCorrection("kv", "left") + 0;


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
             $this->Cell(20, 15, "          Prüfbericht", 0, false, 'L', 0, '', 0, false, 'M', 'M');

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


    $dokument="Prüfbericht";


    $pageLayout = array($width, $height); //  or array($height, $width)
    $pdf = new MYPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator("FIS");
    $pdf->SetAuthor("FIS" . $wp->wp);
    $pdf->SetTitle("Prüfbericht");
    $pdf->SetSubject('P');
    $pdf->SetKeywords('P');


    $pdf->SetAutoPageBreak(true, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



for ($i=0; $i< $fmid->GetPrinterPageCountGLS(); $i++) {
        $pdf->AddPage();
        $pdf->SetHeaderMargin(20);
        $pdf->SetFooterMargin(15);
        $pdf->SetMargins(20, 20, 20);


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

//Barcode

    //$pdf->StartTransform();
    //$pdf->Rotate(90, (20 + $l), (140 + $t));
    $pdf->write1DBarcode("0" . $fmid->fmid, 'I25', 19 + $l, 13 + $t, 20, 12, 0.5, $style, 'N');
    //$pdf->StopTransform();

    //$pdf->write2DBarcode("0" . $fmid->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');



// Rect
    //    $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
    $pdf->SetFont('helvetica', 'B',11 );



//address
    $pdf->SetFont('helvetica', 'B',11 );



//Date



    $pdf->SetFont('helvetica', '', 8);

    $address=$wp->GetPrinterAddressFields();

//Address Signature
    $pdf->MultiCell(50, 20 + $t,($address['firma']),0,"",false,3,150+$l,30+$t);


    $pdf->Text(150 + $l, 37 + $t,substr(($address['strasse']), 0,200));
    $pdf->Text(150 + $l, 40 + $t,substr(($address['plz']), 0,40));
    $pdf->Text(161 + $l, 40 + $t,substr(($address['ort']), 0,40));
    $pdf->Text(150 + $l, 43 + $t,substr(($address['land']), 0,40));
    $pdf->SetFont('helvetica', 'U', 7);
    $pdf->Text(150 + $l, 50 + $t,"Ihr Ansprechpartner:");
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(150 + $l, 53 + $t,$u->GetUsername() );
    $pdf->Text(150 + $l, 56 + $t,"Tel ".$u->GetTelefon() );
    $pdf->Text(150 + $l, 59 + $t,"Fax ".$u->GetFax() );
    $pdf->Text(150 + $l, 62 + $t,"E-Mail: ".$u->GetEMail() );

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Text(150 + $l, 69 + $t,"FMID: ".substr($fmid->fmid, 0,40));
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(150 + $l, 75 + $t, GetArtikelName("text",$fmid->data['fmartikelid']));


    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(150 + $l, 80 + $t, "SN: ".substr($fmid->data['fmsn'], 0, 50));

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(150 + $l, 85 + $t,substr($address['ort'], 0,40).", ".date("d.m.Y",time()));







    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 130 + $t, "Gerätetyp:          ".GetArtikelName("text",$fmid->data['fmartikelid']));
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 135 + $t, "Seriennummer: ".substr($fmid->data['fmsn'], 0, 50));
    $pdf->SetFont('helvetica', 'N', 9);
    $pdf->Text(70 + $l, 135 + $t, "ursprüngliches Fertigungsdatum: ".substr($fmid->data['fmfd'], 0, 7));
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 140 + $t, $desc);

    $pdf->ln(10);

    $pruefdaten=new FisDatalog($_GET['udid'],0,$_SESSION['equip']);
    $pruefdaten->SetLimit(10);
    $pruefdaten->eventtype="temp";


    $pdf->SetFont('helvetica', '', 7);
    $pdf->writeHTML($pruefdaten->GetPdfDiagrammData("data1",70),true,false,false,true,"L");



    $pdf->ln(1);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->MultiCell(150, 1 + $t, "Mit freundlichen Grüßen", 0, "", false, 0, 20 + $l);

    $pdf->ln(10);


    $pdf->SetFont('helvetica', '', 7);
    $pdf->Text(20 + $l, 277 + $t,($address['signatur']));
    $pdf->Text(20 + $l, 280 + $t,($address['HRB']));

    } //pagecounter

// move pointer to last page
    $pdf->lastPage();

    if ($_GET['asemail'] == 1) {
        $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
        AddSessionMessage("success", "E-Mail Anhang Kostenvoranschlag für <code> WEID " . $fmid->fmid . " </code> erstellt");

    } elseif ($_GET['asview'] == 1) {
        $pdf->Output('Kostenvoranschlag-' . $fmid->fmid . '.pdf', 'I');
        AddSessionMessage("success", "Druckansicht Kostenvoranschlag für <code> WEID " . $fmid->fmid . " </code> erstellt");
    } else {
        $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("kv") . '/Kostenvoranschlag-' . $fmid->fmid . '.pdf';
        $pdf->Output($link, 'F');
        $txt = "Kostenvoranschlag für <code> FMID " . $fmid->fmid . " </code> unter <code> $link </code> erstellt";
        $fmid->SaveHistory($txt);
        AddSessionMessage("success", $txt);
    }


} else {
    AddSessionMessage("warning", "Kostenvoranschlag für <code> FMID " . $fmid->fmid . " </code> nicht erlaubt");
}
