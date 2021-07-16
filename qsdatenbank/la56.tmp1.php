<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");

if (!isset($artikelid)){$artikelid=0;}
if ($artikelid==0){
$suchkrit="";}else{
$suchkrit=" and fmartikelid=$artikelid ";
}


/* limt Anzahl der Jahre */
if ($location==611405){
$limit = 12;   
}else{ 
$limit = 36;
}  

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fehlermeldungen.fm5datum,\"%m/%y\") as fehlerdatum, 
count(fehlermeldungen.fmid) as anzahl,avg(datediff(fehlermeldungen.fm5datum,fehlermeldungen.fm3datum)) as mittelwert 
FROM artikeldaten, fehlermeldungen WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid $suchkrit  
GROUP BY date_format(fehlermeldungen.fm5datum,\"%Y %M\") ORDER BY fehlermeldungen.fm5datum desc limit 0,$limit";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fehlermeldungen.fm5datum,\"%m/%y\") as fehlerdatum, 
count(fehlermeldungen.fmid) as anzahl,avg(datediff(fehlermeldungen.fm5datum,fehlermeldungen.fm3datum)) as mittelwert, 
entscheidung.entid, entscheidung.lokz, entscheidung.gvwl FROM artikeldaten, fehlermeldungen, entscheidung 
WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid $suchkrit and fehlermeldungen.fm6=1 AND entscheidung.gvwl=1 AND entscheidung.entid =fehlermeldungen.fm2variante 
GROUP BY date_format(fehlermeldungen.fm5datum,\"%Y %M\") ORDER BY fehlermeldungen.fm5datum desc limit 0,$limit";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$i=$totalRows_rst2-1;

do{
$mittelwertkorr=abs(round($row_rst2['mittelwert'],1));
if ($mittelwertkorr>100){$mittelwertkorr=0;}
$l1datay[$i]=$mittelwertkorr;
$ziel[$i]=5;
$datax[$i]=$row_rst2['fehlerdatum'];
$i=$i-1;
} while ($row_rst2 = mysql_fetch_assoc($rst2) or $i=0);

$i=$totalRows_rst3-1;

do{
$mittelwertkorr=abs(round($row_rst3['mittelwert'],1));
if ($mittelwertkorr>80){$mittelwertkorr=0;}
$l2datay[$i]=$mittelwertkorr;
$i=$i-1;
} while ($row_rst3 = mysql_fetch_assoc($rst3) or $i=0);

/* $datax=$gDateLocale->GetShortMonth(); */

// Create the graph. 
if ($location==611405){
$graph = new Graph(350,300,"auto");   
}else{ 
$graph = new Graph(980,260,"auto");
}   
 // Set a nice summer (in Stockholm) image
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->SetScale("textlin");
$graph->SetMargin(50,50,50,50);
$graph->SetShadow();
$graph->xaxis->SetTickLabels($datax);
// Adjust the position of the legend box
$graph->legend->Pos(0.10,0.01);
// Create the linear error plot
$cplot=new BarPlot($l1datay);
$cplot->SetFillColor("blue");
$cplot->SetLegend(("Durchlaufzeit aller Retourenabwicklungen"));
// Display the values on top of each bar
$cplot->SetShadow();
$cplot->value->SetFormat("   %2.0f",70);
$cplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$cplot->value->SetColor("blue");
$cplot->value->Show();
// Add the plots to t'he graph
$gbarplot = new GroupBarPlot(array($cplot));
$gbarplot->SetWidth(0.8);
$graph->Add($gbarplot);

// Create the linear error plot
$bplot=new BarPlot($l2datay);
$bplot->SetFillColor("orange");
$bplot->SetLegend(("Durchlaufzeit bei Gewährleistung"));
// Display the values on top of each bar
$bplot->SetShadow();
$bplot->value->SetFormat("   %2.0f",70);
$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetColor("orange");
$bplot->value->Show();
// Add the plots to t'he graph
$bbarplot = new GroupBarPlot(array($bplot));
$bbarplot->SetWidth(0.3);
$graph->Add($bbarplot);

// Create the linear Ziel plot
$zplot=new LinePlot($ziel);
$zplot->SetColor("red");
$zplot->SetWeight(2);
$zplot->SetLegend("Zielvorgabe");

$graph->Add($zplot);

$graph->title->Set(utf8_decode($row_rst3['name']));
/* $graph->xaxis->title->Set( utf8_decode("Monat")); */
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( ("durchschn. Durchlaufzeit in Tage"));

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