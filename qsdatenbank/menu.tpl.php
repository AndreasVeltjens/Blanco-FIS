<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$userright= $row_rst1['recht']; /* Definiton Variable f¸r Funktion GetUserright */
$url_user=$row_rst1['id'];
/* Definition Menu Color */
$str1="<li>";
$str2="</li>";
$pic="<img src=\"picture/";
$pict=".gif\" border=\"0\" align=\"absmiddle\">";


/* aktives Menu */
$str3="<li><span>";
$str4=" <img src=\"picture/b_edit.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"></span></li>";
/* Zeige aktives Menu */
$a=0;


if ($vis>=1) {


}
else /* Visulalisierung != 1 */
{


$Modul[$a]=array("fismonitor","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Fismonitor",0,"fismonitor.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("etiprogramm","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Spezialprogramme",0,"etiprogramm.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
$a=$a+1;
$Modul[$a]=array("wasistlos","<img src=\"picture/anforderung_24x24.png\" width=\"24\" height=\"24\" align=\"absmiddle\" border=\"0\"> Was ist los",0,"wasistlos.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
$a=$a+1;
$Modul[$a]=array("tracking","<img src=\"picture/ic_conn_16x16.png\" width=\"24\" height=\"24\" align=\"absmiddle\" border=\"0\"> FID Tracking",0,"tracking.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
$a=$a+1;
$Modul[$a]=array("infomat1","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Produktberater",0,"infomat1.php?url_user=15");
$a=$a+1;
$Modul[$a]=array("kundenlog","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Kundenanmeldung/abmeldung",0,"kundenlogin.php?url_user=$url_user");
$a=$a+1;

	if ($url_kundenummer>0){ 
	$Modul[$a]=array("kundenstart","<img src=\"picture/b_home.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Start",0,"kundenstart.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
	$a=$a+1;
	$Modul[$a]=array("la5","Retourenabwicklung",1,"la5.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
	$a=$a+1;
	$Modul[$a]=array("infomat1","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Produktberater",0,"infomat1.php?url_user=$url_user&url_kundenummer=$url_kundenummer");
	$a=$a+1;
	}
$Modul[$a]=array("log","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> anmelden/abmelden",0,"login.php?url_user=$url_user");
$a=$a+1;


$Modul[$a]=array("passwd","<img src=\"picture/s_process.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Eigene Einstellungen bearbeiten",0,"passwd.php?url_user=$url_user");
$a=$a+1;
if ($row_rst1['id']<>""){
	$Modul[$a]=array("w1","<img src=\"picture/b_tipp.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> KVP Meldungen anlegen",0,"w1.php?url_user=$url_user&ursache=".$la."&url_param=".str_replace("&","-",$editFormAction)."&url_kundenummer=$url_kundenummer");
$a=$a+1;



if (substr($la,0,1)=="w"){
$Modul[$a]=array("w3","Q-Kreis",2,"w3.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("w2","Einzelmeldung Aktivit&auml;ten",3,"w2.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("w12","Einzelmeldung bearbeiten",4,"w12.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("w13","Einzelmeldung anlegen",4,"w13.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("w14","Einzelmeldung anzeigen",4,"w14.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
}
$Modul[$a]=array("w4","Schicht&uuml;bergabe",2,"w4.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("w5","Taschenrechner",2,"Javascript:openTaschenrechner()");
$a=$a+1;
$Modul[$a]=array("w6","Produktsuche",2,"Javascript:openProduktsuche('&url_user=$url_user','','','')");
$a=$a+1;
$Modul[$a]=array("w7","Kunden- und Lieferantensuche",2,"Javascript:openKundensuche('&url_user=$url_user','','','')");
$a=$a+1;

	if ($url_kundenummer>0){ 
	$Modul[$a]=array("kundenstart","<img src=\"picture/b_home.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Start",0,"kundenstart.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
	
	$Modul[$a]=array("kd1","<img src=\"picture/exam.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Eigene Daten bearbeiten",1,"kd1.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
	$Modul[$a]=array("kd2","<img src=\"picture/ratgeber.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Anwender&uuml;bersicht",1,"kd2.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
if (substr($la,0,3)=="kd2"){
		$Modul[$a]=array("kd21"," neuen Anwender anlegen",2,"kd2.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	 	$a=$a+1;
		$Modul[$a]=array("kd22"," Anwender bearbeiten",3,"kd2.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	    $a=$a+1;
	}
	
	
	$Modul[$a]=array("kd3","<img src=\"picture/orderlist_icon.gif\" width=\"15\" height=\"19\" align=\"absmiddle\" border=\"0\"> Verbindungs&uuml;bersicht ",1,"kd3.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
	$Modul[$a]=array("la5","<img src=\"picture/ico_bearbeiten.gif\" width=\"16\" height=\"16\" border=\"0\"> Retourenabwicklung",1,"la5.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
	$Modul[$a]=array("kd4","<img src=\"picture/orderlist_icon.gif\" width=\"15\" height=\"19\" align=\"absmiddle\" border=\"0\"> Vorgangs&uuml;bersicht",1,"la621.php?url_user=$url_user&url_kundenummer=$url_kundenummer&url_idk=$url_idk");
	$a=$a+1;
	
	}
























$Modul[$a]=array("start","<img src=\"picture/b_home.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Start",0,"start.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("mi1","<img src=\"picture/iconMessage_16x16.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Mitteilungen pflegen",1,"mi1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="mi1"){
$Modul[$a]=array("mi12","Mitteilung bearbeiten ",3,"mi12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("mi10","Aktuelles ",2,"mi10.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("mi11","Willkommen ",2,"mi11.php?url_user=$url_user");
$a=$a+1;
}


$Modul[$a]=array("co11","Fertigungsr&uuml;ckmeldung erfassen",1,"co11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("co12","Arbeitsplatzsicht",3,"co12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la17","WW melden",4,"la17.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("co14","Sammelr&uuml;ckmeldung",2,"co14.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("vl411","GEISS DU 1100 T8",4,"vl411.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl412","GEISS DU 1500 T6",4,"vl412.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("co13","Materialsicht",3,"co13.php?url_user=$url_user");
$a=$a+1;
/* 
$Modul[$a]=array("vl413","Fertigungsmeldungen GEISS DU 1300 T5",1,"vl413.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl414","Fertigungsmeldungen Schwei&szlig;en",1,"vl414.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("vl415","Fertigungsmeldungen Sch&auml;umen&Entgraten",1,"vl415.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl2","Fertigungsmeldungen Montage",1,"vl2.php?url_user=$url_user");
$a=$a+1; */


$Modul[$a]=array("vl1","Fertigungsmeldungen Versand",1,"vl1.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl10","<img src=\"picture/orderstatus.gif\" alt=\"Produktionsplan\" width=\"22\" height=\"12\" border=\"0\"> &Uuml;bersicht Produktionsplan ",2,"vl10.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl11","<img src=\"picture/orderstatusersatzteile.png\" alt=\"TKD Lagerplanung\" width=\"22\" height=\"12\" border=\"0\"> &Uuml;bersicht TKD Lagerplanung ",2,"vl11.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("co15","<img src=\"picture/orderlist_icon.gif\" alt=\"Produktionsprogramm\" width=\"22\" height=\"12\" border=\"0\"> PPP Produktionsprogramm planen",1,"co15.php?url_user=$url_user");
$a=$a+1;


$Modul[$a]=array("vl41","Produktionsplanung GEISS DU 1100 T8",2,"vl41.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl42","Produktionsplanung GEISS DU 1500 T6",2,"vl42.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl43","Produktionsplanung GEISS DU 1300 T5",2,"vl43.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl44","Produktionsplanung Schwei&szlig;en",2,"vl44.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl45","Produktionsplanung Sch&auml;umen&Entgraten",2,"vl45.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl4","Auslieferung planen",2,"vl4.php?url_user=$url_user");
$a=$a+1;





$Modul[$a]=array("vl3","AA, PA, PP, mitgelt. Unterlagen",1,"vl3.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="vl3"){
$Modul[$a]=array("vl31","Dokumente/ Etikett anlegen",2,"vl31.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl32","Dokumente/ Etikett bearbeiten",2,"vl31.php?url_user=$url_user");
$a=$a+1;
}



$Modul[$a]=array("la1","<img src=\"picture/eo.gif\" width=\"15\" height=\"15\" border=\"0\"> Fertigungsmeldungen Fertigware",1,"la1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la1"){
$Modul[$a]=array("la11","Einzelmeldung Liste",2,"la11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la12","Einzelmeldung bearbeiten",3,"la12.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la13","Einzelmeldung anlegen",3,"la13.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la14","Einzelmeldung anzeigen",3,"la14.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la15","Chargenfertigung anlegen",3,"la15.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
}


$Modul[$a]=array("vl50","<img src=\"picture/pickreturn.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> R&uuml;ckholung anlegen",1,"vl50.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vl52","R&uuml;ckholung bearbeiten",3,"vl52.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("qmb4","<img src=\"picture/ico_bearbeiten.gif\" width=\"16\" height=\"16\" border=\"0\"> Retourenabwicklung",1,"qmb4.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="qmb"){
$Modul[$a]=array("qmb1","Mitteilungen pflegen",2,"qmb1.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("qmb12","Mitteilung bearbeiten ",3,"qmb2.php?url_user=$url_user");
$a=$a+1;

}

$Modul[$a]=array("infomat1","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" align=\"absmiddle\" border=\"0\"> Produktberater",0,"infomat1.php?url_user=$url_user");
$a=$a+1;
$qmb="qmb4";
$Modul[$a]=array("la4","<img src=\"picture/ico_bearbeiten.gif\" width=\"16\" height=\"16\" border=\"0\"> Q Fehlermeldung alle",1,"la4.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("la4q1","<img src=\"picture/kostenvoranschlag.gif\" border=\"0\"> Q1 Fehlermeldung Debitoren",2,"la4q1.php?url_user=$url_user&url_fmart=1");
$a=$a+1;
$Modul[$a]=array("la4q2","<img src=\"picture/gutschrift.gif\" border=\"0\"> Q2 Fehlermeldung Kreditoren",2,"la4q2.php?url_user=$url_user&url_fmart=2");
$a=$a+1;
$Modul[$a]=array("la4q3","<img src=\"picture/rechnung.gif\" border=\"0\"> Q3 Fehlermeldung Eigen",2,"la4q3.php?url_user=$url_user&url_fmart=3");
$a=$a+1;


if (substr($la,0,3)=="la4"){
$Modul[$a]=array("la4qmb10","Aktuelles ",2,"la4qmb10.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la40","Einzelbearbeitung anlegen",2,"la40.php?url_user=$url_user");
$a=$a+1;

if (substr($la,0,4)=="la40"){
	$Modul[$a]=array("la401","Fehleraufnahme erweitert",3,"la401.php?url_user=$url_user&url_fmid=$url_fmid");
	$a=$a+1;
	$Modul[$a]=array("la402","Kontaktdaten anlegen",3,"la402.php?url_user=$url_user&url_fmid=$url_fmid");
	$a=$a+1;
	$Modul[$a]=array("la403","Schutzleiterwiderstand",3,"la403.php?url_user=$url_user&url_fmid=$url_fmid");
	$a=$a+1;
}

$Modul[$a]=array("la41","Fehleraufnahme",3,"la41.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;

$Modul[$a]=array("la42","Kl&auml;rung und Kosten",3,"la42.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
	$Modul[$a]=array("la422","Position bearbeiten",4,"la422.php?url_user=$url_user&url_fmid=$url_fmid");
	$a=$a+1;
$Modul[$a]=array("la43","Reparaturfreigabe",3,"la43.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la44","Endpr&uuml;fung - VDE",3,"la44.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;

$Modul[$a]=array("la45","Kommissionierung",3,"la45.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la46","Warenausgang melden",3,"la46.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la48","Zahlungseingang melden",3,"la48.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la49","Q-Auswertung bearbeiten",3,"la49.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;

$Modul[$a]=array("la41s","Q- Fehleraufnahme",2,"la41s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la41bs","Q- Kontrollmodus 1",2,"la41bs.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la432s","Q- Kontrollmodus 2",2,"la432s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;


$Modul[$a]=array("la41cs","Q- Kontrollmodus alle",2,"la41cs.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la42s","Q- Kl&auml;rung und Kosten",2,"la42s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la43s","Q- Reparaturfreigabe",2,"la43s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;



$Modul[$a]=array("la431s","Q- SAP PCNA Freigabe",2,"la431s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la44s","Q- Endpr&uuml;fung - VDE",2,"la44s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la442s","Eigene Reparaturliste",2,"la442s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la45s","Q- Kommissionierung",2,"la45s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la46s","Q- Warenausgang",2,"la46s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la48s","Q- Rechnungspr&uuml;fung",2,"la48s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la481s","Q- Rechnungspr&uuml;fung alle",2,"la481s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la471s","Q- offene R&uuml;ckmeldung",2,"la471s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la47s","Q -alle R&uuml;ckmeldungen ",2,"la47s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;

$Modul[$a]=array("la49s","Q- Q-Auswertungen offen",2,"la49s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;
$Modul[$a]=array("la491s","Q- Q-Auswertungen alle",2,"la491s.php?url_user=$url_user&url_fmid=$url_fmid");
$a=$a+1;


} /* ende retourenbearbeitung */





$Modul[$a]=array("la6","<img src=\"picture/anschreiben.gif\" width=\"18\" height=\"18\" border=\"0\"> Ansprechpartner",1,"la6.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la6"){
$Modul[$a]=array("la61","Ansprechpartner anlegen",2,"la61.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la62","Ansprechpartner bearbeiten",3,"la62.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la621","Ansprechpartner Vorg&auml;nge",3,"la621.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la631","Vorgang anlegen",3,"la631.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la632","Vorgang bearbeiten",3,"la632.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la633","Vorgang ansehen",3,"la633.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la6322","Position bearbeiten",4,"la6322.php?url_user=$url_user");
$a=$a+1;




$Modul[$a]=array("la64","Vorgangsliste alle zeigen",1,"la64.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la65","Auftragsliste",2,"la65.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la66","Auslieferliste",2,"la66.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la67","Fakturenliste",2,"la67.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la68","Zahlungsliste",2,"la68.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la69","Bestellliste",2,"la69.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la69la","Buchhaltungsliste",2,"la69.php?url_user=$url_user");
$a=$a+1;
}
/* branchenbuchfunktionen */
$Modul[$a]=array("bbs6","BBS Kundendaten",1,"bbs6.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="bbs6"){
$Modul[$a]=array("bbs61","Kundendaten anlegen",2,"bbs61.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("bbs62","Kundendaten bearbeiten",3,"bbs61.php?url_user=$url_user");
$a=$a+1;
}
/*  Web-Access-Softwarefunktionen */
$Modul[$a]=array("was6","WAS Kundendaten",1,"was6.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="was6"){
$Modul[$a]=array("was61","WAS Kundendaten anlegen",2,"was61.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("was62","WAS Kundendaten bearbeiten",3,"was61.php?url_user=$url_user");
$a=$a+1;
}
/* Kundendaten Ansprechpartner */
$Modul[$a]=array("la2","<img src=\"picture/memory.gif\" width=\"16\" height=\"16\" border=\"0\">  Materialdaten",1,"la2.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la2"){
$Modul[$a]=array("la202","<img src=\"picture/memory.gif\" width=\"16\" height=\"16\" border=\"0\">  Materialdaten bearbeiten",3,"la202.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la21","Artikelversionen",3,"la21.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la22","Version bearbeiten",4,"la22.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la23","Fehlerzuordnung bearbeiten",2,"la23.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la24","Fehlerliste",2,"la24.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
$Modul[$a]=array("la25","Baugruppenst&uuml;cklisten f&uuml;r Reparatur",2,"la25.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
}

$Modul[$a]=array("la3","<img src=\"picture/icon_tray_view_close.gif\" width=\"20\" height=\"14\" border=\"0\">Prozesse und Varianten",1,"la3.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la3"){
$Modul[$a]=array("la31","Prozesse anlegen",2,"la31.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la32","Prozesse bearbeiten",3,"la32.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la33","Variantenliste",3,"la33.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la34","Varianten anlegen",4,"la34.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("la35","Varianten bearbeiten",4,"la35.php?url_user=$url_user");
$a=$a+1;
}

$Modul[$a]=array("k1","<img src=\"picture/orderlist_icon.gif\" width=\"15\" height=\"19\" border=\"0\">  Zeichnungsverteilung",1,"k1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,2)=="k1"){
$Modul[$a]=array("k11","Zeichnung anlegen",2,"k11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("k12","Zeichnung bearbeiten",3,"k12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("k2","Ablagenliste",3,"k2.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("k21","Ablage anlegen",4,"k21.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("k22","Ablage bearbeiten",4,"k22.php?url_user=$url_user");
$a=$a+1;
}

$Modul[$a]=array("p1","<img src=\"picture/icon_ueberweisungsvorlage.gif\" width=\"15\" height=\"20\" border=\"0\">   Projektdatenbank",1,"p1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,1)=="p"){
$Modul[$a]=array("p15","Projektliste",2,"p15.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p11","Projekt anlegen",2,"p11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p212","Projekt bearbeiten",3,"p212.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p2","Workflow",3,"p2.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p21","Workflow anlegen",4,"p21.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p22","Workflow bearbeiten",4,"p22.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("p23","Artikelversionen",4,"p23.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la21","Artikelversionen Fertigung",2,"la21.php?url_user=$url_user&url_artikelid=$url_artikelid");
$a=$a+1;
}



$Modul[$a]=array("la7","<img src=\"picture/RunatServer.gif\" width=\"18\" height=\"18\" border=\"0\"> SPC Pr&uuml;fungen-Regelkarten",1,"la7.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la7"){
$Modul[$a]=array("la70","SPC Pr&uuml;fungen-Regelkarten Ansicht",1,"la70.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la71","SPC Pr&uuml;fung anlegen",2,"la71.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la72","Pr&uuml;fung bearbeiten",3,"la72.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la73","Pr&uuml;fung Auswertung",3,"la73.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("la74","Messwerte pflegen",3,"la74.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la75","Messwerte &auml;ndern",4,"la75.php?url_user=$url_user");
$a=$a+1;
}

$Modul[$a]=array("we1","<img src=\"picture/RunatServer.gif\" width=\"18\" height=\"18\" border=\"0\"> Wareneingangspr&uuml;fungen",1,"we1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="we1"){
$Modul[$a]=array("we11","Wareneingangspr&uuml;fung bearbeiten",2,"we11.php?url_user=$url_user");
$a=$a+1;
}


$Modul[$a]=array("fhm1","<img src=\"picture/b_tblops.png\" width=\"16\" height=\"16\" border=\"0\"> &Uuml;bersicht FHM",1,"fhm1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,4)=="fhm1"){
$Modul[$a]=array("fhm11","FHM anlegen",2,"fhm11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm12","FHM bearbeiten",3,"fhm12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm121","FHM Ereignisliste",3,"fhm121.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm122","FHM Ereignis bearbeiten",4,"fhm122.php?url_user=$url_user");
$a=$a+1;
}


$Modul[$a]=array("fhm2","FHM Pr&uuml;fung definieren",2,"fhm2.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,4)=="fhm2"){
$Modul[$a]=array("fhm21","FHM Pr&uuml;fung anlegen",3,"fhm21.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm22","FHM Pr&uuml;fung bearbeiten",3,"fhm22.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm221","FHM Pr&uuml;fungschritt neu",4,"fhm221.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm222","FHM Pr&uuml;fungschritt ",4,"fhm222.php?url_user=$url_user");
$a=$a+1;
}

$Modul[$a]=array("fhm3","FHM Pr&uuml;fungliste",2,"fhm3.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm6","FHM Servicepartner",2,"fhm6.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("fhm51","Ortsver&auml;nderliche Ger&auml;te",2,"fhm51.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm54","ortsfeste Ger&auml;te",2,"fhm54.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm52","Ger&auml;te zur Zeitumstellung",2,"fhm52.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm53","<img src=\"picture/0d1e0a6a3e.gif\" width=\"16\" height=\"16\" border=\"0\"> aktuelle St&ouml;rungsliste",2,"fhm53.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm4","Messmittelverwaltung MMV",1,"fhm4.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm41","Messmittelverwaltung Kalibrierung",2,"fhm41.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm55","<img src=\"picture/iconRelistBlu_16x16.gif\" width=\"16\" height=\"16\" border=\"0\"> Servicebeh&auml;lter",1,"fhm55.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm552","<img src=\"picture/iconRelistBlu_16x16.gif\" width=\"16\" height=\"16\" border=\"0\"> Servicebeh&auml;lter-verf&uuml;gbar",1,"fhm552.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("fhm551","<img src=\"picture/iconRelistBlu_16x16.gif\" width=\"16\" height=\"16\" border=\"0\"> Servicebeh&auml;lter-Tauschpool",1,"fhm551.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("la99","<img src=\"picture/s_rights.png\" width=\"16\" height=\"16\" border=\"0\"> Benutzerliste",1,"la99.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="la9"){
$Modul[$a]=array("la93","Benutzer anlegen",2,"la93.php?url_user=$url_user&url_usid=$url_usid");
$a=$a+1;
$Modul[$a]=array("la94","Benutzer bearbeiten",2,"la94.php?url_user=$url_user&url_usid=$url_usid");
}
$a=$a+1;
$Modul[$a]=array("la98","Rollen und Profile",1,"la98.php?url_user=$url_user");
$a=$a+1;
if ((substr($la,0,3)=="la9")or (substr($la,0,3)=="org")){
$Modul[$a]=array("la96","Profile anlegen",2,"la96.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la97","Profile bearbeiten",2,"la97.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("la95","Rechte zuweisen",3,"la95.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("org1","Organisations&uuml;bersicht",2,"org1.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("org2","Org. bearbeiten",2,"org2.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("org3","Vis-Org zuweisen",3,"org3.php?url_user=$url_user");
$a=$a+1;

}

$a=$a+1;
$Modul[$a]=array("hr1","Personalschulung - Bewertung",1,"hr1.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="hr1"){

$Modul[$a]=array("hr12","Bewertung bearbeiten",3,"hr12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("hr13","Bewertungmatrix",2,"hr13.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("hr22","Schulung bearbeiten",3,"hr22.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("hr23","Schulungsmatrix",2,"hr23.php?url_user=$url_user");
$a=$a+1;

$Modul[$a]=array("hr3","Personalakte",2,"hr3.php?url_user=$url_user");
$a=$a+1;
}



$Modul[$a]=array("vis1","Visualisierung alle Werke",1,"vis1.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis10","Visualisierung Benutzersicht",1,"vis10.php?url_user=$url_user");
$a=$a+1;
if (substr($la,0,3)=="vis"){
$Modul[$a]=array("vis11","Visualisierung anlegen",2,"vis11.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis12","Visualisierung bearbeiten",3,"vis12.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis14","Vis-Daten bearbeiten",4,"vis14.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis2","Visualisierung anzeigen",3,"vis2.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis3","<img src=\"picture/Cfchart.gif\" width=\"18\" height=\"18\" border=\"0\"> Visualisierungjobs",2,"vis3.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis31","Visualisierungjob anlegen",3,"vis31.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis32","Vis-job bearbeiten",3,"vis32.php?url_user=$url_user");
$a=$a+1;
$Modul[$a]=array("vis34","Vis-job-Step bearbeiten",4,"vis34.php?url_user=$url_user");
$a=$a+1;
}

include("menu_infosystem.php");
} /* Ende if rst1[id]<>"" */
}/* end nicht Visualisierung vis==1 */
$i=0;
 /* echo $yn; */


?>
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" >
<tr>
<td>
<ul id="Navigation">
  <li><a href="#Hauptmenu einblenden"><img src="picture/add.gif" width="15" height="16" border="0" align="absmiddle"> 
    Hauptmenu</a> 
    <ul>
      <?php  
 
do{
	$strModul = $Modul[$i][1];
	$strHeadline = $Modul[$i][1];
	$Modulname = $Modul[$i][0];
if ( ((substr($Modulname,0,3)<>"la9") and 
(substr($Modulname,0,3)<>"la5") and 
(substr($Modulname,0,3)<>"vis") and 
(substr($Modulname,0,3)<>"org") and 
(substr($Modulname,0,1)<>"w") or 
(substr($Modulname,0,2)=="wa") or 
(substr($Modulname,0,2)=="we") and
(substr($Modulname,0,2)<>"hr") )and
(substr($Modulname,0,2)<>"fh")){
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstgetuserright = "SELECT userright.urid, userright.upid, userright.la, userright.lokz FROM userright WHERE userright.upid='$userright' AND userright.la='$Modulname' AND userright.lokz=0";
$rstgetuserright = mysql_query($query_rstgetuserright, $qsdatenbank) or die(mysql_error());
$row_rstgetuserright = mysql_fetch_assoc($rstgetuserright);
$totalRows_rstgetuserright = mysql_num_rows($rstgetuserright);

mysql_free_result($rstgetuserright);
	
if ($totalRows_rstgetuserright>0 ){
	if ($la==$Modul[$i][0] ){
	$d=$i;/* Merker aktuelles Modul */
	
		if ($vis>0){ /* automatische weiterleitung vis jobs */
				$str=$str1;
				if ($aktiv_url_id_kz>0){
					$aktiv_url_id_kz1="&url_id_kz=".$aktiv_url_id_kz;	
								if ($aktiv_url_id_kz1==$Modul[$i][4]){
									$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
						
								}else{
									$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/										
								}/* wenn gleiche KZ */
						}else{
							$str=$str1;	
							$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
						
						} /* wenn aktive IDKZ */
				}else{ 
				
				$str=$str1;
				$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
				}
	}else{/* keine automatische Visualisierung */
	$str=$str1;
	
	
		if ($Modul[$i][2]<=2){
		$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/
	
	}else{
		$str=$str.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str2;/* passiv Menuzeile */
		}
	}
	echo $str;
	}
	} /* ende auﬂer la9 */
 	$i= $i+1;
  } while ($i<120); 
  ?>
      <li><a href="#unten"> - </a></li>
    </ul>
  </li>
  <li><a href="#Benutzereinstellungen"><img src="picture/b_kunden.gif" width="16" height="16" border="0" align="absmiddle"> 
    Benutzereinstellungen</a> 
    <ul>
		      <?php  
	 $i=0;
			do{
				$strModul = $Modul[$i][1];
				$strHeadline = $Modul[$i][1];
				$Modulname = $Modul[$i][0];
			if ((substr($Modulname,0,6)=="passwd") or (substr($Modulname,0,1)=="w") or (substr($Modulname,0,3)=="log") or (substr($Modulname,0,5)=="vis10")){
				
				mysql_select_db($database_qsdatenbank, $qsdatenbank);
			$query_rstgetuserright = "SELECT userright.urid, userright.upid, userright.la, userright.lokz FROM userright WHERE userright.upid='$userright' AND userright.la='$Modulname' AND userright.lokz=0";
			$rstgetuserright = mysql_query($query_rstgetuserright, $qsdatenbank) or die(mysql_error());
			$row_rstgetuserright = mysql_fetch_assoc($rstgetuserright);
			$totalRows_rstgetuserright = mysql_num_rows($rstgetuserright);
			
			mysql_free_result($rstgetuserright);
				
			if ($totalRows_rstgetuserright>0 ){
				if ($la==$Modul[$i][0] ){
				$d=$i;/* Merker aktuelles Modul */
				
					if ($vis>0){ /* automatische weiterleitung vis jobs */
							$str=$str1;
							if ($aktiv_url_id_kz>0){
								$aktiv_url_id_kz1="&url_id_kz=".$aktiv_url_id_kz;	
											if ($aktiv_url_id_kz1==$Modul[$i][4]){
												$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
											}else{
												$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/										
											}/* wenn gleiche KZ */
									}else{
										$str=$str1;	
										$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
									} /* wenn aktive IDKZ */
							}else{ 
							
							$str=$str1;
							$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
							}
				}else{/* keine automatische Visualisierung */
				$str=$str1;
				
				
					if ($Modul[$i][2]<=2){
					$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/
				
				}else{
					$str=$str.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str2;/* passiv Menuzeile */
					}
				}
				echo $str;
				}
				} /* ende auﬂer la9 */
				$i= $i+1;
			  } while ($i<120); 
  ?>

            <li><a href="#unten">-</a></li>
      <li><a href="login.php"><img src="picture/error.gif" border="0"> abmelden</a></li>
            <li><a href="#unten">-</a></li>
    </ul>
  </li>
  <li><a href="#Info"><img src="picture/b_docs.png" width="16" height="16" border="0" align="absmiddle"> 
    Infosystem</a>
	<ul>
     		      <?php  
	 $i=0;
			do{
				$strModul = $Modul[$i][1];
				$strHeadline = $Modul[$i][1];
				$Modulname = $Modul[$i][0];
			if (  (substr($Modulname,0,3)=="la5") or (substr($Modulname,0,2)=="fh") or (substr($Modulname,0,1)=="q")){
				
				mysql_select_db($database_qsdatenbank, $qsdatenbank);
			$query_rstgetuserright = "SELECT userright.urid, userright.upid, userright.la, userright.lokz FROM userright WHERE userright.upid='$userright' AND userright.la='$Modulname' AND userright.lokz=0";
			$rstgetuserright = mysql_query($query_rstgetuserright, $qsdatenbank) or die(mysql_error());
			$row_rstgetuserright = mysql_fetch_assoc($rstgetuserright);
			$totalRows_rstgetuserright = mysql_num_rows($rstgetuserright);
			
			mysql_free_result($rstgetuserright);
				
			if ($totalRows_rstgetuserright>0 ){
				if ($la==$Modul[$i][0] ){
				$d=$i;/* Merker aktuelles Modul */
				
					if ($vis>0){ /* automatische weiterleitung vis jobs */
							$str=$str1;
							if ($aktiv_url_id_kz>0){
								$aktiv_url_id_kz1="&url_id_kz=".$aktiv_url_id_kz;	
											if ($aktiv_url_id_kz1==$Modul[$i][4]){
												$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
											}else{
												$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/										
											}/* wenn gleiche KZ */
									}else{
										$str=$str1;	
										$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
									} /* wenn aktive IDKZ */
							}else{ 
							
							$str=$str1;
							$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
							}
				}else{/* keine automatische Visualisierung */
				$str=$str1;
				
				
					if ($Modul[$i][2]<=2){
					$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/
				
				}else{
					$str=$str.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str2;/* passiv Menuzeile */
					}
				}
				echo $str;
				}
				} /* ende auﬂer la9 */
				$i= $i+1;
			  } while ($i<120); 
  ?>

            <li><a href="#unten">-</a></li>
    </ul>
	</li>
  <li><a href="#Info"><img src="picture/icon_produktdatenbank.gif" width="26" height="16" border="0" align="absmiddle"> 
    Systemeinstellungen</a>
	<ul>
     		      <?php  
	 $i=0;
			do{
				$strModul = $Modul[$i][1];
				$strHeadline = $Modul[$i][1];
				$Modulname = $Modul[$i][0];
			if ((substr($Modulname,0,3)=="la9") or (substr($Modulname,0,3)=="org") or (substr($Modulname,0,3)=="vis") or (substr($Modulname,0,2)=="hr")){
				
				mysql_select_db($database_qsdatenbank, $qsdatenbank);
			$query_rstgetuserright = "SELECT userright.urid, userright.upid, userright.la, userright.lokz FROM userright WHERE userright.upid='$userright' AND userright.la='$Modulname' AND userright.lokz=0";
			$rstgetuserright = mysql_query($query_rstgetuserright, $qsdatenbank) or die(mysql_error());
			$row_rstgetuserright = mysql_fetch_assoc($rstgetuserright);
			$totalRows_rstgetuserright = mysql_num_rows($rstgetuserright);
			
			mysql_free_result($rstgetuserright);
				
			if ($totalRows_rstgetuserright>0 ){
				if ($la==$Modul[$i][0] ){
				$d=$i;/* Merker aktuelles Modul */
				
					if ($vis>0){ /* automatische weiterleitung vis jobs */
							$str=$str1;
							if ($aktiv_url_id_kz>0){
								$aktiv_url_id_kz1="&url_id_kz=".$aktiv_url_id_kz;	
											if ($aktiv_url_id_kz1==$Modul[$i][4]){
												$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
											}else{
												$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/										
											}/* wenn gleiche KZ */
									}else{
										$str=$str1;	
										$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
									
									} /* wenn aktive IDKZ */
							}else{ 
							
							$str=$str1;
							$str=$str3.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str4;/* aktive Menuzeile */
							}
				}else{/* keine automatische Visualisierung */
				$str=$str1;
				
				
					if ($Modul[$i][2]<=2){
					$str=$str."<a href=\"".$Modul[$i][3]."\">".$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul)."</a>".$str2;/* passiv Menuzeile mit Link*/
				
				}else{
					$str=$str.$berecht.$pic.utf8_decode($Modul[$i][2]).$pict.utf8_decode($strModul).$str2;/* passiv Menuzeile */
					}
				}
				echo $str;
				}
				} /* ende auﬂer la9 */
				$i= $i+1;
			  } while ($i<120); 
  ?>

            <li><a href="#unten">-</a></li>
    </ul>
	
	
	</li>
	
</ul>
      <a href="Javascript:OpenHelpWindow(100)"><img src="picture/b_docs.png" alt="&Ouml;ffne die Hilfeseite" border="0"></a>
	  <a href="start.php?url_user=<?php echo $url_user ?>" border="0"><img src="picture/b_home.png" alt="Zur Startseite wechseln" width="16" height="16" border=0 align="absmiddle"></a>
	  	  
	  </td>
</tr>
</table>
<table width="950" border="0" cellpadding="0" cellspacing="1" >
<tr>    
  <td> 
  <table width="950" border="0" cellpadding="0" cellspacing="1"> 
    <tr>
   <td>
