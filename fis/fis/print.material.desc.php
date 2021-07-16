<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 22.10.18
 * Time: 18:22
 */

include_once('./config.php');

// ini_set('memory_limit', '-1');

$wp = new FisWorkplace($_SESSION['wp']);
$artikel=new FisMaterial($_SESSION['material']);
$user=new FisUser($_SESSION['activeuser']);
// InsertNewVorgangInformation($id_vg,2); //Druckvoransichtansicht save this into archive.
// define('PDF_HEADER_LOGO','../resources/abwaehlen.gif');
// define('PDF_HEADER_LOGO_WIDTH',30);
// define('PDF_HEADER_STRING',"Test page");


$Quelle     =substr($artikel->data['supplier'],0,11);
$Lagerort   =substr($artikel->data['lagerplatz'],0,14);
$Inhalt     =substr($artikel->data['content'],0,11);
$Anzahl     =substr($artikel->data['count'],0,12);


define('PDF_HEADER_TITLE',"Article");
define('PDF_HEADER_LFD',$artikel->GetMaterial() );

//etikett format
$width = FIS_PRINT_DOKUSET_WIDTH;
$height = FIS_PRINT_DOKUSET_HEIGHT;

require_once('../tcpdf_min/tcpdf.php');
// Extend the TCPDF class to create custom Header and Footer
if (!class_exists("MYPDF")) {
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // Logo

        // $this->SetY(10);
        // Set font
        // $this->SetFont('helvetica', 'B',12 );
        // Title
        // $this->Cell(5, 15, PDF_HEADER_TITLE. " ".PDF_HEADER_LFD, 0, false, 'L', 0, '', 0, false, 'M', 'M');
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
$pageLayout = array($width, $height); //  or array($height, $width)
$pdf = new MYPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);

$pdf->AddPage();
// set document information
$pdf->SetCreator("FIS");
$pdf->SetAuthor("FIS" . $wp->wp);
$pdf->SetTitle("Lagerplatzlabel");
$pdf->SetSubject('Lager');
$pdf->SetKeywords('Lager');


$pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFooterMargin(0);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
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

$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false
);
$pdf->Rect(5,10,190,80,"DF","",array(255,255,255));
$pdf->Rect(5,10,10,80,"DF",array(0, 0,0), array(065,105,225));
$pdf->Rect(195,10,10,80,"DF",array(0, 0, 0), array(065,105,225));
$pdf->StarPolygon(200,20,5,3,1,180,"","DF",array(0,0,0),array(255,255,255));
$pdf->StarPolygon(10,20,5,3,1,180,"","DF",array(0,0,0),array(255,255,255));
$pdf->StarPolygon(200,80,5,3,1,0,"","DF",array(0,0,0),array(255,255,255));
$pdf->StarPolygon(10,80,5,3,1,0,"","DF",array(0,0,0),array(255,255,255));
$pdf->Rect(198,22.5,4,55,"DF",array(0,0,0),array(255,255,255));
$pdf->Rect(8,22.5,4,55,"DF",array(0,0,0),array(255, 255, 255));
$pdf->Rect(15,60,105,20,"DF",array(220, 220, 220), array(220, 220, 220));
$pdf->Line(15,60,120,60);
$pdf->Line(15,70,195,70);
$pdf->Line(15,80,120,80);
$pdf->Line(120,10,120,90);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 60);
$pdf->SetXY(20,20);
$pdf->MultiCell(100, 1,substr($artikel->GetMaterial() , 0,8),0,'C',1,0,"","",false);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetXY(20,50);
$pdf->MultiCell(100, 1,substr($artikel->GetMaterialName(),0,43),0,'C',1,0,"","",false);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetXY(20,70);
$pdf->Cell(20, 5,"Quelle",0,'L',1,0,"","",false);
$pdf->Line(40,70,40,90);
$pdf->SetXY(45,70);
$pdf->Cell(20, 5,"Lagerort",0,'L',1,0,"","",false);
$pdf->Line(70,70,70,90);
$pdf->SetXY(75,70);
$pdf->Cell(20, 5,"Inhalt",0,'L',1,0,"","",false);
$pdf->Line(95,70,95,90);
$pdf->SetXY(100,70);
$pdf->Cell(20, 5,"Anzahl",0,'L',1,0,"","",false);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(16,82);
$pdf->Cell(20, 5, $Quelle, 0, 'C', 1, 0, "", "", false);
$pdf->SetXY(41,82);
$pdf->Cell(20, 5, $Lagerort, 0, 'C', 1, 0, "", "", false);
$pdf->SetXY(70,82);
$pdf->Cell(20, 5, $Inhalt, 0, 'C', 1, 0, "", "", false);
$pdf->SetXY(95,82);
$pdf->Cell(20, 5, $Anzahl, 0, 'C', 1, 0, "", "", false);
//if (PDF_PAGE_SHOW_BARCODE2OF5I=="yes") {
// Interleaved 2 of 5
$pdf->setXY(145,70);
$pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
//$pdf->Rotate(90, 3, 40);

$pdf->write1DBarcode($artikel->GetMaterial() + 0, 'I25', 125, 72, '', 15, 0.5, $style, 'N');

$pdf->StopTransform();
$pdf->Ln(1);
// new style
$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false
);
//}
//if (PDF_PAGE_SHOWQRCODE=="yes") {
// QRCODE,H : QR-CODE Best error correction
$pdf->write2DBarcode($_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?action=viewfid&item=" . ($artikel->GetMaterial() + 0), 'QRCODE,H', 175, 72, 16, 16, $style, 'N');
//}
$pdf->SetXY(0,25);
$pdf->SetFont('helvetica', '', 10);

if (is_file("../config/etikett_logo_" . FIS_ID . ".png")) {
    $pdf->Image("../config/etikett_logo_" . FIS_ID . ".png", 165, 12, 28);
}

if (is_file($artikel->GetPictureLink())) {
    $pdf->Image($artikel->GetPictureLink(),122,24,45);
}


// move pointer to last page
$pdf->lastPage();

if ($_GET['asemail'] == 1) {
    $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/attachment.pdf', 'F');
    AddSessionMessage("success", "E-Mail Anhang Dokuset für <code> Material " . $artikel->GetMaterialDesc() . " </code> erstellt");

} elseif ($_GET['asview'] == 1) {
    $pdf->Output('Dokumentation-' . $artikel->material . '.pdf', 'I');
    AddSessionMessage("success", "Druckansicht Dokuset für <code> Material " . $artikel->GetMaterialDesc() . " </code> erstellt");
} else {
    $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("dokuset") . '/Dokumentation-' . $artikel->material . '-' . time() . '.pdf';
    $pdf->Output($link, 'F');
    $txt = "Lagerschild für <code> Material " . $artikel->GetMaterialDesc() . " </code> unter <code> $link </code> erstellt";

    AddSessionMessage("success", $txt);
}