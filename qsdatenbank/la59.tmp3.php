<?php 
require_once('Connections/qsdatenbank.php');
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
$query_rst3 = "SELECT artikeldaten.artikelid , artikeldaten.bezeichnung,date_format(fehlermeldungen.fmfd,\"%y\") as fehlerdatum, count(fehlermeldungen.fmid )as reklamierteanzahl , (avg(datediff(fehlermeldungen.fm3datum , fehlermeldungen.fmfd ))/30 )as mittelwertlebensdauer , entscheidung.entid , entscheidung.lokz , entscheidung.gvwl FROM artikeldaten , fehlermeldungen , entscheidung WHERE artikeldaten.artikelid =fehlermeldungen.fmartikelid AND entscheidung.entid =fehlermeldungen.fm2variante $suchkrit1 $suchkrit2 $suchkrit GROUP BY artikeldaten.artikelid order by reklamierteanzahl desc";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


$i=0;
	do { 
		$i=$i+1;
		$d=intval($i/2);
		$gesamt=$gesamt+$row_rst2['reklamierteanzahl'];
		$datay[$i-1]=round($row_rst3['reklamierteanzahl'],1);
		$datax[$i-1]=utf8_decode(substr($row_rst3['bezeichnung'],0,30)." - ".round($row_rst3['mittelwertlebensdauer'],0)." Monate - ".$row_rst3['reklamierteanzahl']." St.");
 } while ($row_rst3 = mysql_fetch_assoc($rst3)); 

$data = $datay;

$graph = new PieGraph(730,500,"auto");
$graph->SetShadow();
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->title->Set(("Anzahl Retouren/ Durchschnittslebensdauer pro Typ im Zeitraum"));
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$p1 = new PiePlot3D($data);
$p1->SetSize(0.325);
$p1->SetCenter(0.225);
$p1->SetLegends($datax);

$graph->Add($p1);
$graph->Stroke();
?>