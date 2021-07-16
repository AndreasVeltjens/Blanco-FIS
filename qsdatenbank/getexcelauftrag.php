<?php require_once('Connections/qsdatenbank.php'); 

if (!isset($url_auftrag)>0 ){echo "Datenbankfehler. Auftragsnummer nicht vorhanden. Kein Download möglich.";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT komponenten.kompnr, sum(komponenten.anzahl) as summe, komponenten.lagerort, komponenten_vorlage.kompname , komponenten_vorlage.beschaffungsart 
FROM fehlermeldungen, komponenten
LEFT JOIN komponenten_vorlage ON komponenten_vorlage.kompnr=komponenten.kompnr
WHERE komponenten.lokz=0 AND komponenten.lagerort<>'Sond' AND fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm4=1 AND fehlermeldungen.sappcna<>0 and fehlermeldungen.fm7 < 2 and fehlermeldungen.fmid=komponenten.id_fm and fehlermeldungen.sappcna='$url_auftrag' group by komponenten.kompnr order by komponenten_vorlage.beschaffungsart";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2= "SELECT komponenten.kompnr, sum(komponenten.anzahl) as summe, komponenten.lagerort FROM komponenten, fehlermeldungen  WHERE komponenten.lokz=0 AND komponenten.lagerort<>'Sond' AND fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm4=1 AND fehlermeldungen.sappcna<>0 and fehlermeldungen.fm7 < 2 and fehlermeldungen.fmid=komponenten.id_fm and fehlermeldungen.sappcna='$url_auftrag' group by komponenten.kompnr ";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_kstzeit = "SELECT  sum(fm4notes) as summearbeit, count(fmid) as anzahl FROM fehlermeldungen  WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm4=1 AND fehlermeldungen.sappcna<>0 and fehlermeldungen.fm7 < 2 and  fehlermeldungen.sappcna='$url_auftrag' ";
$rst_kstzeit= mysql_query($query_rst_kstzeit, $qsdatenbank) or die(mysql_error());
$row_rst_kstzeit = mysql_fetch_assoc($rst_kstzeit);
$totalRows_rst_kstzeit = mysql_num_rows($rst_kstzeit);

include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send($url_auftrag." Sammelrückmeldung".".xls"); 
$format =& $xls->addFormat(); 
$format->setBold(); 
$format->setColor("blue"); 

$format2 =& $xls->addFormat(); 
$format2->setBold(); 
$format2->setColor("red"); 

$sheet =& $xls->addWorksheet($row_rst2['sappcna']."XLS"); 
/* Überschrift */ 
$sheet->write(0, 0, "Material", $format);
 $sheet->write(0, 1, "Anzahl", $format); 
 $sheet->write(0, 2, "Einheit", $format);
 $sheet->write(0, 3, "Werk", $format);
 $sheet->write(0, 4, "Lagerort", $format);
 $sheet->write(0, 5, "Komponentenname", $format); 
  $sheet->write(0, 6, "Beschaffungsart", $format); /* ende 
Überschrift */ 
$i=0;
 if ($totalRows_rst1 > 0) { // Show if recordset not empty 
do { 
$i=$i+1; 
$sheet->writeString($i, 0, $row_rst1['kompnr'], 0); 
$sheet->writeString($i, 1, $row_rst1['summe'], 0); 
$sheet->writeString($i, 2, "ST",0); 
$sheet->writeString($i, 3, "6101", 0); 
$sheet->writeString($i, 4, $row_rst1['lagerort'], 0); 
	if ($row_rst1['kompname']==""){
		$sheet->writeString($i, 5, "Materialtext nicht angelegt eventuell Eingabefehler!", $format2);
	}else {
		$sheet->writeString($i, 5, $row_rst1['kompname'], 0); 
		}
$sheet->writeString($i, 6, $row_rst1['beschaffungsart'], 0); 

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst1 = mysql_fetch_assoc($rst1)); 
} // Show if recordset not empty $xls->close(); exit; 
$i=$i+2;
 if ($totalRows_rst2 > 0) { // Show if recordset not empty 
do { 
$i=$i+1; 
$sheet->writeString($i, 0, $row_rst2['kompnr'], 0); 
$sheet->writeString($i, 1, $row_rst2['summe'], 0); 
$sheet->writeString($i, 2, "ST",0); 
$sheet->writeString($i, 3, "6101", 0); 
$sheet->writeString($i, 4, $row_rst2['lagerort'], 0); 
$sheet->writeString($i, 5, $row_rst2['kompname'], 0); 
$sheet->writeString($i, 6, $row_rst2['beschaffungsart'], 0); 

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst2 = mysql_fetch_assoc($rst2)); 
} // Show if recordset not empty $xls->close(); exit; 


$sheet->write($i+2, 0, "Arbeitszeit", $format);
$sheet->writeString($i+2, 1,$row_rst_kstzeit['summearbeit'], 0);
$sheet->writeString($i+2, 2,"Minuten", 0);
$sheet->writeString($i+3, 1,$row_rst_kstzeit['summearbeit']/60, 0);
$sheet->writeString($i+3, 2,"Stunden", 0);




$xls->close();
	exit;
?>