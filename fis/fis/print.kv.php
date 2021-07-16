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

    if (strlen($fmid->data['gwl'])==1) {

        $desc= "Maßnahme:         Gewährleistungsübernahme";
        $dokument="Gewährleistungsübernahme";
    }else{
        $desc= "Maßnahme:         Reparatur";
        $dokument="Kostenvoranschlag für eine Reparatur";
    }
    if (strlen($fmid->data['vkz'])==1) {
        $desc= "Zustand:            nicht reparabel";
        $dokument="Kostenvoranschlag für eine Reparatur";
    }


    $pageLayout = array($width, $height); //  or array($height, $width)
    $pdf = new MYPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);

// create new PDF document
// $pdf = new (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator("FIS");
    $pdf->SetAuthor("FIS" . $wp->wp);
    $pdf->SetTitle("Kostenvoranschlag ".$fmid->fmid);
    $pdf->SetSubject('KV');
    $pdf->SetKeywords('KV');


    $pdf->SetAutoPageBreak(false, 35);
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



for ($i=0; $i< $fmid->GetPrinterPageCountGLS(); $i++) {
        $pdf->AddPage();
        $pdf->SetHeaderMargin(20);
        $pdf->SetFooterMargin(15);
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
    //    $pdf->Rect(0, 0, $width, $height, 'DF', array(255, 255, 255), array(255, 255, 255));
    $pdf->SetFont('helvetica', 'B',11 );
    $pdf->Cell(20, 15, "          ".$dokument, 0, false, 'L', 0, '', 0, false, 'M', 'M');



//address
    $pdf->SetFont('helvetica', 'B',11 );


    $pdf->MultiCell(80, 10 + $t,substr($fmid->data['rechnung'], 0,100),0,"",false,3,20+$l,40+$t);
    $pdf->Text(20 + $l, 50 + $t,substr($fmid->data['ansprechpartner'], 0,40));
    $pdf->Text(20 + $l, 55 + $t,substr($fmid->data['rstrasse'], 0,40));
    $pdf->Text(20 + $l, 60 + $t,substr($fmid->data['rplz'], 0,40));
    $pdf->Text(32 + $l, 60 + $t,substr($fmid->data['rort'], 0,40));
    $pdf->Text(20 + $l, 65 + $t,substr($fmid->data['rland'], 0,40));

    $pdf->SetFont('helvetica', 'U', 7);
    $pdf->Text(20 + $l, 73 + $t,"Ihre Kontaktdaten:");
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(20 + $l, 77 + $t,"Tel-Nr.: ".substr($fmid->data['rtelefon'], 0,15));
    $pdf->Text(20 + $l, 82 + $t,"Fax-Nr.: ".substr($fmid->data['faxummer'], 0,15));
    $pdf->Text(60 + $l, 77 + $t,"E-Mail : ".substr($fmid->data['remail'], 0,40));
    $pdf->Text(60 + $l, 82 + $t,"Kundennummer : ".substr($fmid->data['debitorennummer'], 0,10)." ".substr($fmid->data['debitorennummer2'], 0,10));


//delivery
    $pdf->SetFont('helvetica', 'U', 7);
    $pdf->Text(20 + $l, 96 + $t,"Warenempfänger:");
    $pdf->SetFont('helvetica', '', 8);
    $pdf->MultiCell(42, 10 + $t,substr($fmid->data['lieferung'], 0,100),0,"",false,3,20+$l,100+$t);
    $pdf->Text(20 + $l, 108 + $t,substr($fmid->data['lansprechpartner'], 0,40));
    $pdf->Text(20 + $l, 112 + $t,substr($fmid->data['lstrasse'], 0,40));
    $pdf->Text(20 + $l, 116 + $t,substr($fmid->data['lplz'], 0,40));
    $pdf->Text(30 + $l, 116 + $t,substr($fmid->data['lort'], 0,40));
    $pdf->Text(20 + $l, 120 + $t,substr($fmid->data['lland'], 0,40));
//invoice
    $pdf->SetFont('helvetica', 'U', 7);
    $pdf->Text(60 + $l, 96 + $t,"Rechnungsempfänger:");
    $pdf->SetFont('helvetica', '', 8);
    $pdf->MultiCell(42, 10 + $t,substr($fmid->data['rechnung'], 0,100),0,"",false,3,60+$l,100+$t);
    $pdf->Text(60 + $l, 108 + $t,substr($fmid->data['ansprechpartner'], 0,40));
    $pdf->Text(60 + $l, 112 + $t,substr($fmid->data['rstrasse'], 0,40));
    $pdf->Text(60 + $l, 116 + $t,substr($fmid->data['rplz'], 0,40));
    $pdf->Text(70 + $l, 116 + $t,substr($fmid->data['rort'], 0,40));
    $pdf->Text(60 + $l, 120 + $t,substr($fmid->data['rland'], 0,40));

//invoice
    if (strlen($fmid->data['partner'])!==0) {
        $pdf->SetFont('helvetica', 'U', 7);
        $pdf->Text(105 + $l, 96 + $t, "Auftraggeber:");
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(55, 2 + $t, substr($fmid->data['partner'], 0, 35), 0, "", false, 2, 105 + $l, 100 + $t);
        $pdf->Text(105 + $l, 108 + $t, substr($fmid->data['pansprechpartner'], 0, 40));
        $pdf->Text(105 + $l, 112 + $t, substr($fmid->data['pstrasse'], 0, 40));
        $pdf->Text(105 + $l, 116 + $t, substr($fmid->data['pplz'], 0, 40));
        $pdf->Text(115 + $l, 116 + $t, substr($fmid->data['port'], 0, 40));
        $pdf->Text(105 + $l, 120 + $t, substr($fmid->data['pland'], 0, 40));
    }


    //invoice
    $pdf->SetFont('helvetica', 'U', 7);
    $pdf->Text(150 + $l, 96 + $t,"Ihre Auftragsfreigabe:");
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Line(150,110,200,110,array(0,0,0));
    $pdf->MultiCell(80, 1 + $t,"Ansprechpartner",0,"",false,3,150+$l,111+$t);

    $pdf->Line(150,125,200,125,array(0,0,0));
    $pdf->Text(150 + $l, 126 + $t,"rechtsverbindl. Unterschrift");




//Date



    $pdf->SetFont('helvetica', '', 8);

    $address=$wp->GetPrinterAddressFields();

//Address Signature
    $pdf->MultiCell(50, 20 + $t,FisTranslate($address['firma']),0,"",false,3,150+$l,30+$t);


    $pdf->Text(150 + $l, 37 + $t,FisTranslate(substr(($address['strasse']), 0,200)) );
    $pdf->Text(150 + $l, 40 + $t,FisTranslate(substr(($address['plz']), 0,40)) );
    $pdf->Text(161 + $l, 40 + $t,FisTranslate(substr(($address['ort']), 0,40)) );
    $pdf->Text(150 + $l, 43 + $t,FisTranslate(substr(($address['land']), 0,40)) );
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
    $pdf->Text(150 + $l, 85 + $t,FisTranslate(substr($address['ort'], 0,40)) .", ".date("d.m.Y",time()));







    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 130 + $t, "Gerätetyp:          ".GetArtikelName("text",$fmid->data['fmartikelid']));
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 135 + $t, "Seriennummer: ".substr($fmid->data['fmsn'], 0, 50));
    $pdf->SetFont('helvetica', 'N', 9);
    $pdf->Text(70 + $l, 135 + $t, "ursprüngliches Fertigungsdatum: ".substr($fmid->data['fmfd'], 0, 7));
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(20 + $l, 140 + $t, $desc);

    $pdf->ln(10);

    $pdf->SetFont('helvetica', 'N', 11);
    $pdf->MultiCell(180, 0 + $t,utf8_decode($fmid->data['anrede']).",",0, "", false, 1, 20 + $l);
    $pdf->ln(4);
    $pdf->MultiCell(180, 0 + $t,"nach eingehender Begutachtung Ihres Gerätes haben wir folgende Fehler festgestellt:",0, "", false, 1, 20 + $l);
    $pdf->ln(1);
    $fmid->LoadErrors();
    if (count($fmid->errordata)>0) {
        foreach ($fmid->errordata as $i=>$item){

            $pdf->SetFont('helvetica', 'N', 11);
            $pdf->MultiCell(180, 0 + $t,"- ".$item['fname'],0, "", false, 1, 20 + $l);
            $pdf->ln(1);
        }
    }

    if (strlen($fmid->data['fm2notes'])>2){
        $pdf->ln(2);
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->MultiCell(175, 0 + $t,($fmid->data['fm2notes']),0, "", false, 1, 20 + $l);

    }


    $pdf->ln(5);
    $pdf->SetFont('helvetica', 'N', 11);
    $pdf->MultiCell(180, 0 + $t,"Bitte kreuzen Sie nachfolgende Reparaturvarianten an und senden Sie diesen unterzeichneten Kostenvorschlag an o.g. Kontaktdaten innerhalb der nächsten 7 Tage zurück.:",0, "", false, 1, 20 + $l);
    $pdf->ln(5);

    $fmid->LoadSelectedVariants();
    if (count($fmid->slectedvariants)>0) {
        foreach ($fmid->slectedvariants as $i=>$item){
           if ($item['Kosten1']<=0) $item['Kosten1']="";
            if ($item['Kosten1']>0) $item['Kosten1']=$item['Kosten1']." €  zzgl. MwSt.";
        $pdf->SetFont('helvetica', 'N', 11);
        $pdf->MultiCell(5, 5 + $t,"",1,"", false, 0, 21 + $l);
        $pdf->MultiCell(50, 20 + $t,$item['varname'],0, "", false, 0, 27 + $l);
        $pdf->MultiCell(150, 10 + $t, $item['varbeschreibung'], 0, "", false, 1, 50 + $l);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->MultiCell(50, 10 + $t, $item['Kosten1'], 0, "", false, 0, 150 + $l);
        $pdf->ln(5);
        }
    }

    $pdf->ln(1);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->MultiCell(150, 1 + $t, "Mit freundlichen Grüßen", 0, "", false, 0, 20 + $l);

    $pdf->ln(10);

//Barcode

        //$pdf->StartTransform();
        //$pdf->Rotate(90, (20 + $l), (140 + $t));
    $pdf->write1DBarcode("0" . $fmid->fmid, 'I25', 19 + $l, 13 + $t, 20, 12, 0.5, $style, 'N');
        //$pdf->StopTransform();

        //$pdf->write2DBarcode("0" . $fmid->GetSerialnumber(), 'QRCODE,H', 85 + $l, 18 + $t, 3, 3, $style, 'N');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->Text(20 + $l, 277 + $t,FisTranslate($address['signatur']));
    $pdf->Text(20 + $l, 280 + $t,FisTranslate($address['HRB']));

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
        $d = new FisWorkflow($txt, "fmid", $fmid->fmid);
        $d->Add();
        AddSessionMessage("success", $txt);
    }


} else {
    AddSessionMessage("warning", "Kostenvoranschlag für <code> FMID " . $fmid->fmid . " </code> nicht erlaubt");
}
