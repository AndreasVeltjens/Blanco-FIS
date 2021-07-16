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
$u = new ifisUser($_REQUEST['userid']);


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
define('DATEN_Y',70);

define('PDF_HEADER_TITLE',"Beauftragung ");
define('PDF_HEADER_LFD',$u->data['userid']);
define('PDF_HEADER_FTYPE',"Beauftragung");



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
        $this->Cell(5, 15, PDF_HEADER_FTYPE, 0, false, 'L', 0, '', 0, false, 'M', 'M');
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
$pdf->SetSubject('Beauftragung');
$pdf->SetKeywords('Beauftragung');

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



if ($u->data['datumstapler']<>"0000-00-00") {
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


// Interleaved 2 of 5
    $pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
    $pdf->Rotate(90, 3, 40);
    $pdf->write1DBarcode($w->data['userid'], 'I25', 3, 40, '', 15, 0.4, $style, 'N');
    $pdf->StopTransform();
    $pdf->Ln(1);
// new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false
    );


if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
    $pdf->write2DBarcode('http://www.albafis.de/index.php?bks='.$_SESSION['classifiedDirectoryID'].'&action=viewbeauftragung&item=' . $u->userid, 'QRCODE,H', 18, 20, PDF_QRCODE_SIZE, PDF_QRCODE_SIZE, $style, 'N');
} else {
//headertxt

}

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(ADR_X, ADR_Y-4, "KVB Kunststoffverwertung Brandenburg GmbH");
    $pdf->Text(ADR_X, ADR_Y, $us->data['name']);
    $pdf->Text(ADR_X, ADR_Y + 3, htmlspecialchars_decode($us->data['anschrift']));
    $pdf->Text(ADR_X, ADR_Y + 6, $us->data['plz']);
    $pdf->Text(ADR_X + 10, ADR_Y + 6, $us->data['ort']);
    $pdf->Text(ADR_X, ADR_Y + 10, 'Telefon: ' . $us->data['telefon']);
    $pdf->Text(ADR_X, ADR_Y + 13, 'Fax:       ' . $us->data['fax']);
    $pdf->Text(ADR_X, ADR_Y + 16, 'E-Mail: ' . $us->data['email']);

    $pdf->Text(ADR_X, ADR_Y + 25, 'Druckdatum: ' . date('d.m.Y', time()));


    $pdf->SetAbsXY(BEZ_X, DATEN_Y);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->writeHTML("KVB Kunststoffverwertung Brandenburg GmbH - Seefichtenstraße 15 - 15890 Eisenhüttenstadt", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 5);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML(substr($u->GetUsername(), 0, 50), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML("- im Hause -", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 40);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->writeHTML("Schriftliche Beauftragung von Gabelstaplerfahrern", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 50);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("gemäß § 7 der Unfallverhütungsvorschrift \"Flurförderzeuge\" (BGV D27)", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 60);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("letzte Eignungsprüfung G25 vom ", false, false, false, false, '');
    $pdf->SetAbsXY(DATEN_X, DATEN_Y + 60);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML(date("d.m.Y", strtotime($u->GetDatum(2))), false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 80);

    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Sehr geehter " . $u->GetAnrede() . " " . $u->GetUsername() . ",", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 90);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("hiermit werden Sie aufgrund der Eignungsprüfung am " . date("d.m.Y", strtotime($u->data['datumg25']))
        . " und des vorgelegten Staplerscheins vom " . date("d.m.Y", strtotime($u->data['datumstapler']))
        . " mit dem selbsttätigen Führen von Gabelstaplern im innerbetrieblichen Verkehr beauftragt. Die Beauftragung gilt für alle diesel-, gas- bzw. elektrobetriebenen Gabelstapler.", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 120);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Diese Bauftragung ist unbefristet. Die Unterweisung erfolgte durch WAURY Fördertechnik.", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 125);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Die Betriebsanweisung Stapler/ Flurförderfahrzeuge hängt im Schichtleiterbüro aus.", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 140);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Eisenhüttenstadt, " . date("d.m.Y", time()), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 160);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 160);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 165);
    $pdf->writeHTML("Betriebsleiter", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 165);
    $pdf->writeHTML("Gabelstaplerfahrer", false, false, false, false, '');

} else{
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


// Interleaved 2 of 5
    $pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
    $pdf->Rotate(90, 3, 40);
    $pdf->write1DBarcode($w->data['userid'], 'I25', 3, 40, '', 15, 0.4, $style, 'N');
    $pdf->StopTransform();
    $pdf->Ln(1);
// new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false
    );


    if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('http://www.albafis.de/index.php?bks='.$_SESSION['classifiedDirectoryID'].'&action=viewbeauftragung&item=' . $u->userid, 'QRCODE,H', 18, 20, PDF_QRCODE_SIZE, PDF_QRCODE_SIZE, $style, 'N');
    } else {
//headertxt

    }

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(ADR_X, ADR_Y-4, "KVB Kunststoffverwertung Brandenburg GmbH");
    $pdf->Text(ADR_X, ADR_Y, $us->data['name']);
    $pdf->Text(ADR_X, ADR_Y + 3, htmlspecialchars_decode($us->data['anschrift']));
    $pdf->Text(ADR_X, ADR_Y + 6, $us->data['plz']);
    $pdf->Text(ADR_X + 10, ADR_Y + 6, $us->data['ort']);
    $pdf->Text(ADR_X, ADR_Y + 10, 'Telefon: ' . $us->data['telefon']);
    $pdf->Text(ADR_X, ADR_Y + 13, 'Fax:       ' . $us->data['fax']);
    $pdf->Text(ADR_X, ADR_Y + 16, 'E-Mail: ' . $us->data['email']);

    $pdf->Text(ADR_X, ADR_Y + 25, 'Druckdatum: ' . date('d.m.Y', time()));


    $pdf->SetAbsXY(BEZ_X, DATEN_Y);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->writeHTML("KVB Kunststoffverwertung Brandenburg GmbH - Seefichtenstraße 15 - 15890 Eisenhüttenstadt", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 5);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML(substr($u->GetUsername(), 0, 50), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML("- im Hause -", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 40);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->writeHTML("Es liegen keine Nachweise für eine Beauftragung vor.", false, false, false, false, '');

}


if ($u->data['datumhub']<>"0000-00-00") {
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


// Interleaved 2 of 5
    $pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
    $pdf->Rotate(90, 3, 40);
    $pdf->write1DBarcode($w->data['userid'], 'I25', 3, 40, '', 15, 0.4, $style, 'N');
    $pdf->StopTransform();
    $pdf->Ln(1);
// new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false
    );


    if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('http://www.albafis.de/index.php?bks='.$_SESSION['classifiedDirectoryID'].'&action=viewbeauftragung&item=' . $u->userid, 'QRCODE,H', 18, 20, PDF_QRCODE_SIZE, PDF_QRCODE_SIZE, $style, 'N');
    } else {
//headertxt

    }

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(ADR_X, ADR_Y-4, "KVB Kunststoffverwertung Brandenburg GmbH");
    $pdf->Text(ADR_X, ADR_Y, $us->data['name']);
    $pdf->Text(ADR_X, ADR_Y + 3, htmlspecialchars_decode($us->data['anschrift']));
    $pdf->Text(ADR_X, ADR_Y + 6, $us->data['plz']);
    $pdf->Text(ADR_X + 10, ADR_Y + 6, $us->data['ort']);
    $pdf->Text(ADR_X, ADR_Y + 10, 'Telefon: ' . $us->data['telefon']);
    $pdf->Text(ADR_X, ADR_Y + 13, 'Fax:       ' . $us->data['fax']);
    $pdf->Text(ADR_X, ADR_Y + 16, 'E-Mail: ' . $us->data['email']);

    $pdf->Text(ADR_X, ADR_Y + 25, 'Druckdatum: ' . date('d.m.Y', time()));


    $pdf->SetAbsXY(BEZ_X, DATEN_Y);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->writeHTML("KVB Kunststoffverwertung Brandenburg GmbH - Seefichtenstraße 15 - 15890 Eisenhüttenstadt", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 5);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML(substr($u->GetUsername(), 0, 50), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML("- im Hause -", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 40);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->writeHTML("Schriftliche Beauftragung zum Bedienen von Hubarbeitsbühnen", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 50);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("gemäß Kapitel 2.10, Abs. 2.1 der DGUV-Regel \"Betreiben von Arbeitsmitteln\" (DGUV-R 100-500, eh. BGR 500)
sowie DGUV Grundsatz 308-008 (eh. BGG 966) „Ausbildung und Beauftragung der Bediener von Hubarbeitsbühnen“", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 70);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("letzte Eignungsprüfung G25 vom ", false, false, false, false, '');
    $pdf->SetAbsXY(DATEN_X, DATEN_Y + 70);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML(date("d.m.Y", strtotime($u->GetDatum(3))), false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 90);

    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Sehr geehter " . $u->GetAnrede() . " " . $u->GetUsername() . ",", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 100);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("hiermit werden Sie aufgrund der Eignungsprüfung am " . date("d.m.Y", strtotime($u->data['datumg25']))
        . " und des vorgelegten Fahrausweises zum Bedienen von Hubarbeitsbühnen vom " . date("d.m.Y", strtotime($u->data['datumhub']))
        . " mit dem selbsttätigen Führen von Hubarbeitsbühnen im innerbetrieblichen Verkehr beauftragt. Die Beauftragung gilt für alle diesel-, gas- bzw. elektrobetriebenen Hubarbeitsbühnen. 
        
        Sie haben Ihre Befähigung zum Bedienen der Hubarbeitsbühne gemäß Kapitel 2.10, Abs. 2.1 \"Betreiben von Hebebühnen\" der DGUV-Regel \"Betreiben von Arbeitsmitteln\" (DGUV-R 100-500, eh. BGR 500) bzw. DGUV Grundsatz 308-008 (eh. BGG 966) „Ausbildung und Beauftragung der Bediener von Hubarbeitsbühnen“ gegenüber dem Unternehmer nachgewiesen.
        
        ", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 150);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Diese Bauftragung ist unbefristet. Die Unterweisung erfolgte durch _________________________.", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 155);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Die Betriebsanweisung Hubarbeitsbühnen hängt im Schichtleiterbüro aus.", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 170);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Eisenhüttenstadt, " . date("d.m.Y", time()), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 190);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 190);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 195);
    $pdf->writeHTML("Betriebsleiter", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 195);
    $pdf->writeHTML("Bediener Hubarbeitsbühnen", false, false, false, false, '');

}

if ($u->data['datumkran']<>"0000-00-00") {
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


// Interleaved 2 of 5
    $pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
    $pdf->Rotate(90, 3, 40);
    $pdf->write1DBarcode($w->data['userid'], 'I25', 3, 40, '', 15, 0.4, $style, 'N');
    $pdf->StopTransform();
    $pdf->Ln(1);
// new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false
    );


    if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('http://www.albafis.de/index.php?bks='.$_SESSION['classifiedDirectoryID'].'&action=viewbeauftragung&item=' . $u->userid, 'QRCODE,H', 18, 20, PDF_QRCODE_SIZE, PDF_QRCODE_SIZE, $style, 'N');
    } else {
//headertxt

    }

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Text(ADR_X, ADR_Y-4, "KVB Kunststoffverwertung Brandenburg GmbH");
    $pdf->Text(ADR_X, ADR_Y, $us->data['name']);
    $pdf->Text(ADR_X, ADR_Y + 3, htmlspecialchars_decode($us->data['anschrift']));
    $pdf->Text(ADR_X, ADR_Y + 6, $us->data['plz']);
    $pdf->Text(ADR_X + 10, ADR_Y + 6, $us->data['ort']);
    $pdf->Text(ADR_X, ADR_Y + 10, 'Telefon: ' . $us->data['telefon']);
    $pdf->Text(ADR_X, ADR_Y + 13, 'Fax:       ' . $us->data['fax']);
    $pdf->Text(ADR_X, ADR_Y + 16, 'E-Mail: ' . $us->data['email']);

    $pdf->Text(ADR_X, ADR_Y + 25, 'Druckdatum: ' . date('d.m.Y', time()));


    $pdf->SetAbsXY(BEZ_X, DATEN_Y);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->writeHTML("KVB Kunststoffverwertung Brandenburg GmbH - Seefichtenstraße 15 - 15890 Eisenhüttenstadt", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 5);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML(substr($u->GetUsername(), 0, 50), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML("- im Hause -", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 40);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->writeHTML("Fahrauftrag für kraftbetriebene Krane", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 50);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("gemäß BGV D6, § 29 Absatz 1", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 70);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML("letzte Eignungsprüfung G25 vom ", false, false, false, false, '');
    $pdf->SetAbsXY(DATEN_X, DATEN_Y + 70);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->writeHTML(date("d.m.Y", strtotime($u->GetDatum(3))), false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 90);

    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Sehr geehter " . $u->GetAnrede() . " " . $u->GetUsername() . ",", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 100);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("hiermit werden Sie aufgrund der Eignungsprüfung am " . date("d.m.Y", strtotime($u->data['datumg25']))
        . " und des vorgelegten Fahrausweises zum Bedienen von Kranlagen vom " . date("d.m.Y", strtotime($u->data['datumkran']))
        . " beauftragt, im Rahmen der betrieblichen notwendigen Transport-, Stapel- und Verladetätigkeiten, alle zugewiesenen kraft-, teilkraft-, flur- u. funkgesteuerte Krane zu führen.
        
        Sie wurden entsprechend den Grundlagen BetrSichV, BGV A1, D6, BGR 500 Kapitel 2.8, BGG 921 und VDI 2194 ausgebildet und haben durch Vorlage des Kranscheines die Befähigung nachgewiesen.
        ", false, false, false, false, '');


    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 150);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Diese Bauftragung ist unbefristet. Die Unterweisung erfolgte durch _________________________.", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 155);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Die Betriebsanweisung Krane hängt im Schichtleiterbüro aus.", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 170);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("Eisenhüttenstadt, " . date("d.m.Y", time()), false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 190);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 190);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->writeHTML("______________________ ", false, false, false, false, '');

    $pdf->SetAbsXY(BEZ_X, DATEN_Y + 195);
    $pdf->writeHTML("Betriebsleiter", false, false, false, false, '');
    $pdf->SetAbsXY(BEZ_X + 100, DATEN_Y + 195);
    $pdf->writeHTML("Bediener Krananlagen", false, false, false, false, '');

}


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