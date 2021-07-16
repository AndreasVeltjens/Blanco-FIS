<?php require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_pie.php");
include ("../src/jpgraph_pie3d.php");

if (!isset($artikelid)){$artikelid=0;}
if ($artikelid==0){
$suchkrit="";}else{
$suchkrit=" and fmartikelid=$artikelid ";
} 

if (!isset($datum1)){$datum1=0;}
if ($datum1==0){
$suchkrit1="";}else{
$suchkrit1=" and fehlermeldungen.fm1datum>='$datum1' ";
} 
if (!isset($datum2)){$datum2=0;}
if ($datum2==0){
$suchkrit2="";}else{
$suchkrit2=" and fehlermeldungen.fm1datum<='$datum2' ";
} 

 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung, count(linkfehlerfehlermeldung.lffid) as anzahl, fehler.fname FROM artikeldaten, fehlermeldungen, linkfehlerfehlermeldung, fehler WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid $suchkrit1 $suchkrit2 $suchkrit and fehler.fid=linkfehlerfehlermeldung.fid and linkfehlerfehlermeldung.fmid=fehlermeldungen.fmid GROUP BY fehler.fid ORDER BY count(linkfehlerfehlermeldung.lffid) desc limit 0,100";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$i=0;
	do { 
		$i=$i+1;
		$d=intval($i/2);
		$gesamt=$gesamt+$row_rst2['anzahl'];
		$datay[$i-1]=round($row_rst2['anzahl'],1);
		$datax[$i-1]=utf8_decode(substr($row_rst2['fname'],0,30)." - ".round($row_rst2['anzahl'],0)." mal");
 } while ($row_rst2 = mysql_fetch_assoc($rst2)); 

$data = $datay;

$graph = new PieGraph(730,500,"auto");
$graph->SetShadow();
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->title->Set(("Fehlerhäufigkeit"));
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$p1 = new PiePlot3D($data);
$p1->SetSize(0.325);
$p1->SetCenter(0.325);
$p1->SetLegends($datax);

$graph->Add($p1);
$graph->Stroke();


?>