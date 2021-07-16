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
include_once('../apps/function.php');
include_once ('../apps/session.php');

$e= new ifisNotification();
$us=new ifisUser($_SESSION['userid']);
$uall=new ifisAllUser();
$w = new ifisWarenausgang($_REQUEST['waid']);


define('PDF_QRCODE_SIZE','30');
define('PDF_PAGE_SHOWQRCODE',"yes");

define('HEADER_LOGO_filename',"../media/logo1.png");
define('HEADER_LOGO_positionx',140);
define('HEADER_LOGO_positiony',10);
define('HEADER_LOGO_sizex',"50");
define('HEADER_LOGO_sizey',"18");

define('ADR_X',140);
define('ADR_Y',40);

define('BEZ_X',20);
define('DATEN_X',80);
define('DATEN_Y',80);

define('PDF_HEADER_TITLE',"Lieferung ");
define('PDF_HEADER_LFD',"$w->waid");

require_once('../tcpdf_min/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {


    //Page header
    public function Header() {
        // Logo
        if (is_file(HEADER_LOGO_filename)) {
            $this->Image(HEADER_LOGO_filename,HEADER_LOGO_positionx,HEADER_LOGO_positiony,HEADER_LOGO_sizex,HEADER_LOGO_sizey);
        }
        $this->SetY(10);
        // Set font
        $this->SetFont('helvetica', 'B',12 );
        // Title
        $this->Cell(5, 15, PDF_HEADER_TITLE. " ".PDF_HEADER_LFD, 0, false, 'L', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        if (is_file(FOOTER_LOGO_filename)) {
            $this->Image(FOOTER_LOGO_filename,FOOTER_LOGO_positionx,FOOTER_LOGO_positiony,FOOTER_LOGO_sizex,FOOTER_LOGO_sizey);
        }


        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Text(180,280, "Seite ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(),false,false,true,0,0,'L' );


    }

}



// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle(PDF_HEADER_TITLE);
$pdf->SetSubject('Protokoll');
$pdf->SetKeywords('Protokoll');

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);



$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------



// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING,array(0,0,0), array(0,0,0));
// $pdf->setFooterData(array(0,0,0), array(0,0,0));




$pdf->AddPage();

$pdf->SetFooterMargin(0);

$pdf->SetFont('helvetica', '', 10);

$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(0,0,0,0);
$pdf->SetFillColor(255, 255, 255);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)



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



$kundenreferenz.= (183).": \n";

$datuminfo=utf8_decode($mandant['city']).", ".date("d.m.Y",strtotime("2001-01-01"));





if (PDF_PAGE_SHOW_BARCODE2OF5I=="yes") {

// Interleaved 2 of 5
    $pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
    $pdf->Rotate(90, 3, 40);
    $pdf->write1DBarcode($vorgang['lfd_nr'], 'I25', 3, 40, '', 15, 0.4, $style, 'N');
    $pdf->StopTransform();
    $pdf->Ln(1);
// new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false
    );
}

if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
    $pdf->write2DBarcode('http://www.albafis.de/index.php?bks='.$_SESSION['classifiedDirectoryID'].'&action=viewvorgang&item=' . $w->wid, 'QRCODE,H', 18, 20, PDF_QRCODE_SIZE, PDF_QRCODE_SIZE, $style, 'N');
} else {
//headertxt

}

$pdf->SetFont('helvetica', '', 8);
$pdf->Text(ADR_X,ADR_Y,$us->data['name']);
$pdf->Text(ADR_X,ADR_Y+3,htmlspecialchars_decode($us->data['anschrift']) );
$pdf->Text(ADR_X,ADR_Y+6,$us->data['plz']);
$pdf->Text(ADR_X+10,ADR_Y+6,$us->data['ort']);
$pdf->Text(ADR_X,ADR_Y+10,'Telefon: '.$us->data['telefon']);
$pdf->Text(ADR_X,ADR_Y+13,'Fax:       '.$us->data['fax']);
$pdf->Text(ADR_X,ADR_Y+16,'E-Mail: '.$us->data['email']);

$pdf->Text(ADR_X,ADR_Y+25,'Druckdatum: '.date('d.m.Y',time()));


$pdf->SetAbsXY(BEZ_X,DATEN_Y);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML("Datum", false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML(substr($w->data['wdatum'],0,16), false, false, false, false, '');

$pdf->SetAbsXY(BEZ_X,DATEN_Y+10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML("Warenausgang", false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+10);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['wdesc'], false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+15);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['wdelivery'], false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+20);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['wcustomer'], false, false, false, false, '');


$pdf->SetAbsXY(DATEN_X,DATEN_Y+25);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['auftragsnummer'], false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+30);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['lieferscheinnumer'], false, false, false, false, '');



$pdf->SetAbsXY(BEZ_X,DATEN_Y+45);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML("für", false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+45);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($w->data['wemail'], false, false, false, false, '');

$pdf->SetAbsXY(BEZ_X,DATEN_Y+50);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->writeHTML("bearbeitet von ", false, false, false, false, '');

$pdf->SetAbsXY(DATEN_X,DATEN_Y+50);
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($uall->GetListUsers("name",$w->data['wuser']), false, false, false, false, '');


// move pointer to last page
$pdf->lastPage();

if ($AsEmail){
    $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/attachment.pdf', 'F');
    $w->ChangeLog("per E-Mail versendet",1);
}elseif (strlen($printpath)>0){
    $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/cloudprint/'.$printpath.'/'.PDF_HEADER_TITLE . '-' . PDF_HEADER_LFD . '.pdf', 'F');
    $w->ChangeLog("Clouddruck erstellt",1);

}else {
    $pdf->Output(PDF_HEADER_TITLE . '-' . PDF_HEADER_LFD . '.pdf', 'I');
    $w->ChangeLog("Druckvoransicht erstellt",1);
}