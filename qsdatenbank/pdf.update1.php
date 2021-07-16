<?php
/**
 * Created by PhpStorm.
 * User: Andreas Veltjens lok
 * Date: 29.12.2016
 * Time: 16:21
 */

//update to add new pictures to serial print pages

define(IFIS_PICTURE_PATH,"./picture/update1/");
define(IFIS_PICTURE_LOGO,"LogoTS2017.jpg");
define(IFIS_PICTURE_WORK,"LogoTS2017_work.jpg");
define(IFIS_PICTURE_SN,"LogoTS2017_sn.jpg");


define(IFIS_PICTURE_INFO,"LogoTS2017_info.jpg");
define(IFIS_PICTURE_CE,"LogoTS2017_ce.jpg");
define(IFIS_PICTURE_GOST,"LogoTS2017_gost.jpg");
define(IFIS_PICTURE_WEEE,"LogoTS2017_weee.jpg");
define(IFIS_PICTURE_LOAD,"LogoTS2017_load.jpg");
define(IFIS_PICTURE_IP,"LogoTS2017_ip.jpg");
define(IFIS_PICTURE_X4,"LogoTS2017_x4.jpg");
define(IFIS_PICTURE_X6,"LogoTS2017_x6.jpg");
define(IFIS_PICTURE_VDE,"LogoTS2017_vde.jpg");

define(IFIS_PICTURE_EAC,"LogoTS2017_eac.jpg");


$offset_x=0;


$pdf->Text(1+$offset,26+$offsety,IFIS_PICTURE_PATH.IFIS_PICTURE_LOGO);

$pdf->Line(1+$offset,10+$offsety,50+$offset,10+$offsety);

$pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_LOGO, 1 + $offset+$offset_x, 3.5 + $offsety, 48, 5.5, "", "");
$pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_SN, 30 + $offset+$offset_x, 11 + $offsety, 4, 4, "", "");
$pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_WORK, 30 + $offset+$offset_x, 15 + $offsety, 4, 4, "", "");

$pdf->SetFont('Helvetica','',8);

$conf=new IfisFid($row_rst21['configprofil']);
if (strlen($conf->GetArtikelNummer()) >0 ){
    $pdf->Text(1 + $offset, 14 + $offsety, "Item No. " . $conf->GetArtikelNummer() . "-" .$conf->GetArtikelPlug());
}else {
    $pdf->Text(1 + $offset, 14 + $offsety, "Item No. " . $row_rst4['Nummer'] . "-" . str_replace("Version ", "", $row_rst21['version']));
}
$pdf->SetFont('Helvetica','',8);
$pdf->Text(1+$offset,18+$offsety,substr(utf8_decode($row_rst4['Bezeichnung']),0,21));


$pdf->SetFont('Helvetica','',8);
$pdf->Text(35+$offset,14+$offsety,"".$url_nummer);/* SN */
$pdf->SetFont('Helvetica','',8);
if ($rep==1) {
    $pdf->Text(35+$offset,18+$offsety,"R-".date("m-Y",time()) );
} else {
    $pdf->Text(35+$offset,18+$offsety,date("m-Y",time()) );
}

// nur auf elektrischen Bauteil
if ($offset<50){
	
	if (is_file(IFIS_PICTURE_PATH.IFIS_PICTURE_INFO)){
		$pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_INFO, 1 + $offset+$offset_x, 24 + $offsety, 4, 4, "", "");
		$offset_x+=5;
	}


    $pdf->Text(1+$offset,22+$offsety,$row_rst4['leistungsangabe']);

   // $pdf->Text(1+$offset,28+$offsety,$row_rst4['pruefzeichen'])
    if (strpos($row_rst4['pruefzeichen'],"elektro")>0) {
       if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_CE)) {
             $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_CE, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
             $offset_x += 5;
         }
    }
	
	/* soll nicht mehr 12/2017
    if (strpos($row_rst4['pruefzeichen'],"gost")>0) {

            if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_GOST)) {
                $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_GOST, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
                $offset_x += 5;
            }
    }

*/
    if ((strpos($row_rst4['pruefzeichen'],"x4")>0) or (strpos($row_rst4['pruefzeichen'],"x6")>0)) {

        if (is_file(IFIS_PICTURE_PATH.IFIS_PICTURE_IP)) {
        $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_IP, 1 + $offset+$offset_x, 24 + $offsety, 4, 4, "", "");
        $offset_x+=5;

            if (strpos($row_rst4['pruefzeichen'],"x4")>0) {
                if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_X4)) {
                    $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_X4, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
                    $offset_x += 5;
                }
            }
            if (strpos($row_rst4['pruefzeichen'],"x6")>0) {
                if (is_file(IFIS_PICTURE_PATH.IFIS_PICTURE_X6)) {
                    $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_X6, 1 + $offset+$offset_x, 24 + $offsety, 4, 4, "", "");
                    $offset_x+=5;
                }
            }

        }
    }
    if (strpos($row_rst4['pruefzeichen'],"vde")>0) {
        if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_VDE)) {
            $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_VDE, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
            $offset_x += 5;
        }
    }
	/* ab 01/2018 soll EAC Russian TÜV Symbol angezeigt werden*/
	if (strpos($row_rst4['pruefzeichen'],"eac")>0) {
        if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_EAC)) {
            $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_EAC, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
            $offset_x += 5;
        }
    }

    if (strpos($row_rst4['pruefzeichen'],"elektro")>0) {
        if (is_file(IFIS_PICTURE_PATH . IFIS_PICTURE_WEEE)) {
            $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_WEEE, 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
            $offset_x += 5;
        }
    }
	
}

/* soll nicht mehr 12/2017
    if (strlen($row_rst4['traglast'])>0) {
        if (is_file(IFIS_PICTURE_PATH.IFIS_PICTURE_LOAD)) {
            $pdf->Image(IFIS_PICTURE_PATH . IFIS_PICTURE_LOAD, 1 + $offset+$offset_x, 24 + $offsety, 4, 4, "", "");
            $offset_x+=5;
            $pdf->SetFont('Helvetica','',7);
            $pdf->Text(1 + $offset+$offset_x, 24 + $offsety+2,str_replace("max","",$row_rst4['traglast']));
            $pdf->Text(3 + $offset+$offset_x, 24 + $offsety+4,  "max.");
        }
    }
	*/