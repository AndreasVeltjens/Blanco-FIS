<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");

if (isset($artikelid)){
if ($artikelid>0){
$artikel="and fertigungsmeldungen.fartikelid=".$artikelid;
$suchkrit="and fehlermeldungen.fmartikelid=".$artikelid;
}
}

if ($_GET['limit']=="" or !isset($_GET['limit']) ){ $limit=24;} else {$limit=$_GET['limit'];}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fertigungsmeldungen.fdatum,\"%m/%y\") as fehlerdatum, count(fertigungsmeldungen.fid) as anzahl FROM artikeldaten, fertigungsmeldungen WHERE artikeldaten.artikelid=fertigungsmeldungen.fartikelid $artikel  AND fertigungsmeldungen.ffrei =1  GROUP BY date_format(fertigungsmeldungen.fdatum,\"%m/%y\") ORDER BY fertigungsmeldungen.fdatum desc limit 0,$limit";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fehlermeldungen.fmfd,\"%m/%y\") as fehlerdatum, count(fehlermeldungen.fmid) as anzahl,avg(datediff(fehlermeldungen.fmfd,fehlermeldungen.fm3datum)) as mittelwert, entscheidung.entid, entscheidung.lokz, entscheidung.gvwl FROM artikeldaten, fehlermeldungen, entscheidung WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid $suchkrit  AND entscheidung.gvwl=1 AND entscheidung.entid =fehlermeldungen.fm2variante GROUP BY date_format(fehlermeldungen.fmfd,\"%Y %M\") ORDER BY fehlermeldungen.fmfd desc limit 0,$limit";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


$i=$totalRows_rst2-1;

do{

$l1datay[$i]=round($row_rst2['anzahl'],1);
$datax[$i]=$row_rst2['fehlerdatum'];



$ix=$totalRows_rst3-1;
$dddatay[$i]= 0;
				do{
				if (substr($row_rst2['fehlerdatum'],0,5)==(substr($row_rst3['fehlerdatum'],0,5))) {
						$dddatay[$i]=round($row_rst3['anzahl'],1);
										
						}
				
				$ix=$ix-1;
				
				} while ($row_rst3 = mysql_fetch_assoc($rst3) or $ix=0);
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
  $i=$i-1;
} while ($row_rst2 = mysql_fetch_assoc($rst2) or $i=0);




/* $datax=$gDateLocale->GetShortMonth(); */

// Create the graph. 
$graph = new Graph(980,500,"auto");    
 // Set a nice summer (in Stockholm) image
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->SetScale("textlin");
$graph->SetMargin(50,50,50,50);
$graph->SetShadow();
$graph->xaxis->SetTickLabels($datax);
// Adjust the position of the legend box
$graph->legend->Pos(0.05,0.01);
$graph->legend->SetFont(FF_ARIAL);
// Create the linear error plot
$cplot=new BarPlot($l1datay);
$cplot->SetFillColor("blue");
$cplot->SetLegend(("Anzahl der Fertigungsmeldungen pro Monat"));
// Display the values on top of each bar
$cplot->SetShadow();
$cplot->value->SetFormat("   %2.0f",70);
$cplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$cplot->value->SetColor("blue");
$cplot->value->Show();

// Create the linear error plot
$dplot=new BarPlot($dddatay);
$dplot->SetFillColor("red");
$dplot->SetLegend(("Anzahl der Gewährleistungsretouren"));
// Display the values on top of each bar
$dplot->SetShadow();
$dplot->value->SetFormat("   %2.0f",70);
$dplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$dplot->value->SetColor("red");
$dplot->value->Show();



// Add the plots to t'he graph

$gbarplot = new GroupBarPlot(array($cplot));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);

$dbarplot = new GroupBarPlot(array($dplot));
$dbarplot->SetWidth(0.6);
$graph->Add($dbarplot);

$graph->title->Set(utf8_decode($row_rst3['name']));
/* $graph->xaxis->title->Set( utf8_decode("Monat")); */
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( utf8_decode("Anzahl der Fertigungsmeldung"));

$graph->title->SetFont(FF_ARIAL,FS_BOLD);
$graph->yaxis->title->SetFont(FF_ARIAL);
$graph->xaxis->title->SetFont(FF_ARIAL);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(60);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);


// Display the graph
$graph->Stroke();
?>