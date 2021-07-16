<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php');
// Gantt hour example
include ("../src/jpgraph.php");
include ("../src/jpgraph_gantt.php");

$groesse= 1150;
if ($layout==780){$groesse= 780;}



$graph = new GanttGraph($groesse);
$graph->SetMarginColor('blue:1.7');
$graph->SetColor('white');

// Setup a horizontal grid
$graph->hgrid->Show();
$graph->hgrid->SetRowFillColor('darkblue@0.9');


// And to show that you can also add an icon we add "Tux"
$icon = new IconPlot('picture\logo.png',0.15,0.99,1,20);
$icon->SetAnchor('left','bottom');
$graph->Add($icon);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstarb = "SELECT * FROM arbeitsplatz WHERE arbeitsplatz.arb_id='$arbeitsplatztyp'";
$rstarb = mysql_query($query_rstarb, $qsdatenbank) or die(mysql_error());
$row_rstarb = mysql_fetch_assoc($rstarb);
$totalRows_rstarb = mysql_num_rows($rstarb);


$graph->SetBackgroundGradient('navy','white',GRAD_HOR,BGRAD_MARGIN);
$graph->scale->hour->SetBackgroundColor('lightyellow:1.5');
$graph->scale->hour->SetFont(FF_FONT1);
$graph->scale->day->SetBackgroundColor('lightyellow:1.5');
$graph->scale->day->SetFont(FF_FONT1,FS_BOLD);

$graph->title->Set("Plantafel für ".$row_rstarb['arb_name']." - ".$row_rstarb['arb_kostenstelle']);
$graph->title->SetColor('white');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);

$graph->ShowHeaders(GANTT_HDAY | GANTT_HHOUR); 
/* $graph->ShowHeaders(GANTT_HDAY );*/

$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$graph->scale->week->SetFont(FF_FONT1);
$graph->scale->hour->SetIntervall(8);

$graph->scale->hour->SetStyle(HOURSTYLE_HM24);
$graph->scale->day->SetStyle(DAYSTYLE_SHORTDAYDATE3);



if (($arbeitsplatztyp==0) or ($arbeitsplatztyp=="")){ ?>
keine Darstellung möglich. Arbeitsplatz auswählen. 
<?php
}


 
$datumselect=date("YW",gmmktime(0,0,0,substr($datum,5,2),substr($datum,8,2),substr($datum,0,4))); 


/* Wandle in Kalenderwoche um*/
/* echo $datumselect;  */

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM produktionsprog WHERE yearweek(produktionsprog.datum)='$datumselect'  AND  produktionsprog.typ = '$arbeitsplatztyp'  AND produktionsprog.lokz=0 ORDER BY produktionsprog.reihenfolge ASC";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);



function add_date($orgDate,$sec,$nextday){ 
  $cd = strtotime($orgDate); 
  $retDAY = date('Y-m-d H:m', mktime( date('H',$cd),date('m',$cd),date('s',$cd)+$sec,date('m',$cd),date('d',$cd),date('Y',$cd) )); 
  return $retDAY; 
} 
/* if (substr($datum,0,4)>=2009) { nur nötig wenn die Woche nicht am Mo. um 6.00 Uhr beginnen soll
	$startday=$datum;
} else { */
	$now = time();
	$num = date("w");
	if ($num == 0)
	{ $sub = 6; }
	else { $sub = ($num-1); }
	$WeekMon  = mktime(0, 0, 0, date("m", $now)  , date("d", $now)-$sub, date("Y", $now));    //monday week begin calculation
	$todayh = getdate($WeekMon); //monday week begin reconvert
	
	$d = $todayh[mday];
	$m = $todayh[mon];
	$y = $todayh[year];
	$startday="$y-$m-$d"." 6:00";
	
	$aktuellday=date('Y-m-d H:m',time());
/* } */

$i=0;
$lastday=substr($row_rst2['reihenfolge'],4,2);
$ende= $startday;
$firststart=$startday;

// Setup a vertical marker line 
$vline = new GanttVLine($firststart);
$vline->SetDayOffset(0);
$vline->title->Set($firststart);
$vline->title->SetFont(FF_FONT1,FS_BOLD,10);
$graph->Add($vline);




do{

if ($row_rstarb['art']=="Parallel"){
	$nextday1=0;
	$start=$firststart;

} else {
/* wenn am gleichen Tag beginnen soll */
	if (substr($row_rst2['reihenfolge'],4,2)==$lastday){
		$nextday1=0;$start=$ende;
	}else{/* wenn neuer Tag beginnen soll */
		$nextday1=1;
		$cd1= strtotime($firststart);
		$start=date ('Y-m-d H:m',  mktime( date('H',$cd1),date('m',$cd1),date('s',$cd1),date('m',$cd1),date('d',$cd1)+$nextday1,date('Y',$cd1) ));
		$firststart=$start;
	} 
	
} /* ende arbeitsplatzart */

/* Berechnung der Gutteilestückzahl */
$summeg=$row_rst2['mog1']+$row_rst2['mog2']+$row_rst2['mog3']+
			$row_rst2['dig1']+$row_rst2['dig2']+$row_rst2['dig3']+
			  $row_rst2['mig1']+$row_rst2['mig2']+$row_rst2['mig3']+
			  $row_rst2['dog1']+$row_rst2['dog2']+$row_rst2['dog3']+
			  $row_rst2['frg1']+$row_rst2['frg2']+$row_rst2['frg3']+
			  $row_rst2['sag1']+$row_rst2['sag2']+$row_rst2['sag3']+
			  $row_rst2['sog1']+$row_rst2['sog2']+$row_rst2['sog3'];


/* berechne Stückzahl aus min und max Bestand  */
$benoetigt=round($row_rst2['avis']-$row_rst2['stueck'],-1);
/* wenn bestand görßer als max.Meldebestand dann nicht einplanen */
if ($row_rst2['stueck']>$row_rst2['avis']) {$benoetigt=0; }
/* wenn bestand kleiner als min.Meldebestand + differenz  
if ($row_rst2['stueck']<$row_rst2['palettenL']) {$benoetigt=$benoetigt+$row_rst2['palettenL']; }
*/
/* sieh nach ob Auftrag eingeplant */
if ($benoetigt==0) {
	$aktivity[$i]=array(0, " nicht eingeplant ");
	}elseif ($summeg==0) {
	$aktivity[$i]=array(0, " ".$benoetigt." Stück ");
	} else{
	$aktivity[$i]=array(round($summeg/$benoetigt,2), (" ".(round($summeg/$benoetigt,2)*100)." %") );
}

if (($aktivity[$i][0])>1) {$aktivity[$i][0]=1;}

$ende = add_date($start,$row_rst2['zeit']* $benoetigt +(20*60),$nextday1);
$data[$i]=array($i,substr($row_rst2['reihenfolge'],4,2)."-".utf8_decode(substr($row_rst2['bezeichnung'],0,30)), $start,$ende);

// The constrains between the activities
$constrains[$i] = (array($i,$i+1,CONSTRAIN_ENDSTART));
/* wenn nicht benötigt dann Zeile überschreiben */
if ($benoetigt==0) {
	$i=$i;
	}else{
	$i=$i+1;
	}
$lastday=substr($row_rst2['reihenfolge'],4,2);

} while ($row_rst2 = mysql_fetch_assoc($rst2));

for($i=0; $i<count($data); ++$i) {
	$bar = new GanttBar($data[$i][0],$data[$i][1],$data[$i][2],$data[$i][3],substr($data[$i][2],11,5)." bis ".substr($data[$i][3],11,5)." ".$aktivity[$i][1],10);
	if( count($data[$i])>4 )
		$bar->title->SetFont($data[$i][4],$data[$i][5],$data[$i][6]);
	$bar->SetPattern(BAND_RDIAG,"yellow"); 
	$bar->SetFillColor("red");
	// Specify progress to 60%
	$bar->progress->Set($aktivity[$i][0]);
	$bar->progress->SetPattern(BAND_HVCROSS,"blue");
	
		
	
	$graph->Add($bar);
}

// Create a vertical line to emphasize the milestone
$aktuellday=date('Y-m-d H:m',time());
$vl = new GanttVLine($aktuellday,"heute","darkred");
$vl->SetDayOffset(0.0);	// Center the line in the day
$graph->Add($vl);



$graph->Stroke();




mysql_free_result($rst2);

mysql_free_result($rstarb);
?>
