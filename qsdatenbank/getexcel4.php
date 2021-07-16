<?php require_once('Connections/qsdatenbank.php'); 

if (isset($artikelid)){
	if ($artikelid>0){$artikel="and fertigungsmeldungen.fartikelid=".$artikelid;}
}


	if ($timeline==1){
		$timeline=" \"%Y %u \" ";	/* Wöchentlich */
	} else {
		$timeline=" \"%Y %M\" "; /* monatlich */
	}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fertigungsmeldungen.fdatum,$timeline) as fehlerdatum, count(fertigungsmeldungen.fid) as anzahl 
FROM artikeldaten, fertigungsmeldungen 
WHERE artikeldaten.artikelid=fertigungsmeldungen.fartikelid $artikel  AND fertigungsmeldungen.ffrei =1  
GROUP BY date_format(fertigungsmeldungen.fdatum,$timeline) ORDER BY fertigungsmeldungen.fdatum desc limit 0,200";
$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
$row_rst = mysql_fetch_assoc($rst);
$totalRows_rst = mysql_num_rows($rst);

include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send("Auswertung $artikelid".".xls"); 
$format =& $xls->addFormat(); 
$format->setBold(); 
$format->setColor("blue"); 
$sheet =& $xls->addWorksheet("$artikelid"."XLS"); 
/* Überschrift */ 
$sheet->write(0, 0, "Geraetebezeichnung", $format);
$sheet->write(0, 1, $row_rst['bezeichnung'], $format);
$sheet->write(2, 0, "Zeitraum", $format);
$sheet->write(2, 1, "Anzahl", $format); 
/* ende Überschrift */ 
$i=2;
if ($totalRows_rst > 0) { // Show if recordset not empty 
	do { 
	$i=$i+1; 
	$sheet->writeString($i, 0, $row_rst['fehlerdatum'], 0); 
	$sheet->writeString($i, 1, $row_rst['anzahl'], 0); 
	} while ($row_rst = mysql_fetch_assoc($rst)); 
} // Show if recordset not empty $xls->close(); exit; 

$xls->close();
exit;
?>