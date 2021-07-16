<?php require_once('Connections/qsdatenbank.php');
// Gantt hour example
include ("../src/jpgraph.php");
include ("../src/jpgraph_gantt.php");

$graph = new GanttGraph(1150);
$graph->SetMarginColor('blue:1.7');
$graph->SetColor('white');

// Setup a horizontal grid
$graph->hgrid->Show();
$graph->hgrid->SetRowFillColor('darkblue@0.9');


// And to show that you can also add an icon we add "Tux"
$icon = new IconPlot('picture\logo.png',0.15,0.95,1,30);
$icon->SetAnchor('left','bottom');
$graph->Add($icon);


$graph->SetBackgroundGradient('navy','white',GRAD_HOR,BGRAD_MARGIN);
$graph->scale->hour->SetBackgroundColor('lightyellow:1.5');
$graph->scale->hour->SetFont(FF_FONT1);
$graph->scale->day->SetBackgroundColor('lightyellow:1.5');
$graph->scale->day->SetFont(FF_FONT1,FS_BOLD);

$graph->title->Set("Plantafel für GEISS DU 1300 T5");
$graph->title->SetColor('white');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);

$graph->ShowHeaders(GANTT_HDAY | GANTT_HHOUR);

$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$graph->scale->week->SetFont(FF_FONT1);
$graph->scale->hour->SetIntervall(4);

$graph->scale->hour->SetStyle(HOURSTYLE_HM24);
$graph->scale->day->SetStyle(DAYSTYLE_SHORTDAYDATE3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM auslieferungen WHERE auslieferungen.typ = 5 ORDER BY auslieferungen.paletten asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


function add_date($orgDate,$sec,$nextday){ 
  $cd = strtotime($orgDate); 
  $retDAY = date('Y-m-d H:m', mktime( date('H',$cd),date('m',$cd),date('s',$cd)+$sec,date('m',$cd),date('d',$cd),date('Y',$cd) )); 
  return $retDAY; 
} 

if (substr($datum,0,4)>=2009) {
	$startday=$datum;
} else {
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
}

$i=0;
$lastday=substr($row_rst2['paletten'],4,2);
$ende= $startday;
$firststart=$startday;

// Setup a vertical marker line 
$vline = new GanttVLine($firststart);
$vline->SetDayOffset(0);
$vline->title->Set($firststart);
$vline->title->SetFont(FF_FONT1,FS_BOLD,10);
$graph->Add($vline);




do{

if (substr($row_rst2['paletten'],4,2)==$lastday){$nextday1=0;$start=$ende;
}else{
$nextday1=1;
$cd1= strtotime($firststart);
$start=date ('Y-m-d H:m',  mktime( date('H',$cd1),date('m',$cd1),date('s',$cd1),date('m',$cd1),date('d',$cd1)+$nextday1,date('Y',$cd1) ));
$firststart=$start;
} 
if ($row_rst2['stueck']==0) {
$aktivity[$i]=array(0, " nicht eingeplant ");
}elseif ($row_rst2['avis']==0) {
$aktivity[$i]=array(0, " offen ");
} else{


$aktivity[$i]=array(round($row_rst2['avis']/$row_rst2['stueck'],2), (" ".(round($row_rst2['avis']/$row_rst2['stueck'],2)*100)." %") );
}

if (($aktivity[$i][0])>1) {$aktivity[$i][0]=1;}

$ende = add_date($start,$row_rst2['zeit']*$row_rst2['stueck'],$nextday1);
$data[$i]=array($i,substr($row_rst2['paletten'],4,2)."-".$row_rst2['bezeichnung'], $start,$ende);

// The constrains between the activities
$constrains[$i] = (array($i,$i+1,CONSTRAIN_ENDSTART));

$i=$i+1;
$lastday=substr($row_rst2['paletten'],4,2);

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



$graph->Stroke();



?>


