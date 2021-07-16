<?php require_once('Connections/qsdatenbank.php'); 
include('fis.functions.php');
$la="la4";

$_POST['selectland']=$_GET['selectland'];
$_POST['selectorder']=$_GET['selectorder'];
$_POST['zeigefehler']=$_GET['zeigefehler'];
$_POST['addon']=$_GET['addon'];
$_POST["suchtext"]=$_GET["suchtext"];
$_POST['selectartikelid']=$_GET['selectartikelid'];

if (isset($_POST['selectartikelid']) && intval($_POST['selectartikelid'])>0) {
	$artikelid =" AND fehlermeldungen.fmartikelid=".$_POST['selectartikelid']." ";
}else{
	$artikelid="";
}


if (isset($_POST['selectorder'])) {
	if ($_POST['selectorder']==1){
		$order=" fehlermeldungen.fmartikelid, fehlermeldungen.fmsn, fehlermeldungen.fmid ASC";
	}
	elseif ($_POST['selectorder']==2){
		$order=" fehlermeldungen.fmartikelid ASC, fehlermeldungen.fmsn DESC, fehlermeldungen.fmid DESC";
	} else {
		$order=" fehlermeldungen.fmid DESC";
	}
}else{
	$order=" fehlermeldungen.fmid DESC";
}

$suchtext="WHERE (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." 
							OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  "." OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."')".$artikelid;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst = "SELECT * FROM fehlermeldungen $suchtext ORDER BY $order LIMIT 0, 100" ;
$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
$row_rst = mysql_fetch_assoc($rst);
$totalRows_rst = mysql_num_rows($rst);



include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send("Fehlermeldungszusammenfassung.xls"); 

$format =& $xls->addFormat(); 
$format->setBold();
$format->setColor("blue"); 

$format1=& $xls->addFormat();
$format1->setBold();
$format1->setColor("black"); 

$sheet =& $xls->addWorksheet("Fehlermeldungen"); 
/* Überschrift */ 
$sheet->write(0, 0, "Fehlermeldungsübersicht", $format);
$sheet->write(2, 0, "Suchbegriff", $format);
$sheet->write(2 ,1,	$_POST['suchtext'],0);

$sheet->write(2, 2, "Artikel", $format);
$sheet->write(2 ,3,	GetArtikelData($_POST['selectartikelid']),0);

$sheet->write(1, 0, "Datum", $format);
$sheet->write(1, 1, date("Y-m-d H:i",time()), 0);
$sheet->write(3, 0, "Fehlermeldenummer", $format);
$sheet->write(3, 1, "Gerät", $format); 
$sheet->write(3, 2, "Seriennummer", $format);
$sheet->write(3, 3, "SN-Ersatzgerät", $format);
$sheet->write(3, 4, "Kunde/ Händler", $format);
$sheet->write(3, 5, "Bemerkungen", $format);
$sheet->write(3, 6, "Kommision", $format);
$sheet->write(3, 7, "Debitor", $format);
$sheet->write(3, 8, "Debitor", $format);

$sheet->write(3, 9, "Fertigungsdatum", $format);
$sheet->write(3, 10, "Eingangsdatum", $format);
$sheet->write(3, 11, "Kosten", $format);
$sheet->write(3, 12, "Entscheidungsvariante", $format);
$sheet->write(3, 13, "Lebensdauer Gerät", $format);


/* Überschrift */ 
$i=4;
if ($totalRows_rst > 0) { // Show if recordset not empty 
	do { 
	$i++;
	$sheet->writeString($i, 0, utf8_decode($row_rst['fmid']), $format1); 
	$sheet->writeString($i, 1, utf8_decode(GetArtikelData($row_rst['fmartikelid'])), $format1); 
	$sheet->writeString($i, 2, utf8_decode($row_rst['fmsn']), $format1); 
	$sheet->writeString($i, 3, utf8_decode($row_rst['fmsn2']), 0); 
	
	$sheet->writeString($i, 4, utf8_decode($row_rst['fm1notes']), 0); 
	$sheet->writeString($i, 5, utf8_decode($row_rst['fm11notes']), 0); 
	$sheet->writeString($i, 6, utf8_decode($row_rst['fm12notes']), 0); 
	$sheet->writeString($i, 7, utf8_decode($row_rst['debitorennummer']), 0); 
	$sheet->writeString($i, 8, utf8_decode($row_rst['debitorennummer2']), 0); 
	$sheet->writeString($i, 9, date("d.m.Y",strtotime($row_rst['fmfd'])), $format1); 
	$sheet->writeString($i, 10, date("d.m.Y",strtotime($row_rst['fm1datum']) ), $format1); 
	$sheet->writeString($i, 11, utf8_decode(GetProzessCosts($row_rst['fmid'])), 0);
	$sheet->writeString($i, 12, utf8_decode(GetProzessInfo($row_rst['fmid'])), 0); 
	$sheet->writeString($i, 13, sprintf("%01.0f",(strtotime($row_rst['fmdatum'])-strtotime($row_rst['fmfd']))  / (60*60*24*30) )." Monate", $format1); 
	$sheet->writeString($i, 14, utf8_decode(GetDeviceLiveTime($row_rst['fmid'])), 0); 
	
	/* Show Error list*/
	 if ($_POST['zeigefehler']==1 ){
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rstz = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid 
		FROM linkfehlerfehlermeldung, fehler 
		WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$row_rst[fmid]' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
		$rstz = mysql_query($query_rstz, $qsdatenbank) or die(mysql_error());
		$row_rstz = mysql_fetch_assoc($rstz);
		$totalRows_rstz = mysql_num_rows($rstz);
		/* Error list data*/
		 if ($totalRows_rstz>0) {
			do {
				$i++;
				$sheet->writeString($i, 1, utf8_decode($row_rstz['fkurz']), 0);
				$sheet->writeString($i, 2, utf8_decode($row_rstz['fname']), 0);
			}while($row_rstz = mysql_fetch_assoc($rstz));
		}else{
			$i++;
			$sheet->writeString($i, 1, "keine Fehler dokumentiert", 0);
		}
	}
	
		/* Show Addon data list*/
	 if ($_POST['addon']==1 ){
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rstz = "SELECT * FROM fehlermeldungenadddata WHERE fehlermeldungenadddata.fdd_fmid='$row_rst[fmid]' AND fehlermeldungenadddata.fdd_lokz=0 ORDER BY fehlermeldungenadddata.fdd_group,fehlermeldungenadddata.fdd_date ";
		$rstz = mysql_query($query_rstz, $qsdatenbank) or die(mysql_error());
		$row_rstz = mysql_fetch_assoc($rstz);
		$totalRows_rstz = mysql_num_rows($rstz);
		/* Error list data*/
		 if ($totalRows_rstz>0) {
			do {
				$i++;
				$sheet->writeString($i, 1, utf8_decode(GetFehlermeldungAddonDataGroup("text",$row_rstz['fdd_group']) ), 0);
				$sheet->writeString($i, 2, utf8_decode(GetFehlermeldungAddonDataClass("text",$row_rstz['fdd_class']) ), 0);
				$sheet->writeString($i, 3, utf8_decode($row_rstz['fdd_value'] ), 0);
				$sheet->writeString($i, 4, utf8_decode(GetFehlermeldungAddonDataClass("unit",$row_rstz['fdd_class']) ), 0);
				$sheet->writeString($i, 5, utf8_decode(GetFehlermeldungAddonDataClass("allowed",$row_rstz['fdd_class']) ), 0);
				$sheet->writeString($i, 6, utf8_decode($row_rstz['fdd_date'] ), 0);
				$sheet->writeString($i, 7, utf8_decode(GetUserName($row_rstz['fdd_user']) ), 0);
				
				
				
			}while($row_rstz = mysql_fetch_assoc($rstz));
		}else{
			$i++;
			$sheet->writeString($i, 1, "keine weiteren Daten dokumentiert", 0);
		}
	}
	
	} while ($row_rst = mysql_fetch_assoc($rst));
} else {

$sheet->writeString(4, 0, "keine Daten gefunden", 0); 
}

$xls->close();
exit;
?>