<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.11.18
 * Time: 13:49
 */
class FisDatalog
{

    public $bks=6101;

    public $udid=0;
    public $viewid=1;
    public $equip=0;

    public $importdata="";
    public $idata=array();
    public $type="net";
    public $eventtype="";
    public $data=array();
    public $settings=array();
    public $layout=array();
    private $rowcount=0;
    public $e="";
    public $dataset=1;

    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $search="";
    public $limit=10000;
    public $interval=10;
    public $lokz=0;
    public $latesttime=0;

    function __construct($udid=0,$viewid=1, $equip=0)
    {
        $this->equip=$equip;
        $this->udid=$udid;
        $this->viewid=$viewid;

        if ($udid>0){
            $this->LoadData();
            $this->LoadSettings();
        }

        return $this;
    }
    function __toString(){
        return $this->udid;
    }
    function LoadData(){

        include ("./connection.php");
        $this->data=array();
        if ($this->equip>0){ $s=" AND fhm.id_fhm='".$this->equip."'";}else{$s="";}
        $sql="SELECT * FROM fhm WHERE fhm.udid = '".$this->udid."' $s  LIMIT 0,1";



        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;

        }else{
            $this->data=array();
            AddSessionMessage("warning","Equipmentdaten <code> UDID $this->udid </code> hat keine Datensätze.","DB Datenbankfehler");
        }
        return $this;
    }

    function LoadSettings(){
        if (strlen ($this->data['settings'])>10) {
            $this->settings = json_decode($this->data['settings'], true);
            $this->layout = json_decode($this->data['layout'], true);

        }else{
            $this->settings = array();
            $this->layout=array();
        }
    }

    function GetDiagrammSettings($type="mooris"){
        switch ($type){
            case "mooris":{
                $this->SetLimit(   $this->layout['diagramms']['mooris'][$this->viewid]['limit']);
                $this->SetInterval($this->layout['diagramms']['mooris'][$this->viewid]['interval']);
                break;
            }
            case "flot":{
                $this->SetLimit(   $this->layout['diagramms']['flot'][$this->viewid]['limit']);
                $this->SetInterval($this->layout['diagramms']['flot'][$this->viewid]['intervall']);
                break;
            }
        }
    }

    function LoadAllData()
    {
        $s="";
        if (strlen($this->eventtype)>0) $s=" AND fisdatalog.dtype='$this->eventtype' ";

        if ($this->latesttime>0){ $s.=" AND fisdatalog.datum>='".$this->latesttime."' ";}

        //interval unit is hours
        $timestamp=time()-($this->interval  * 60 *60);
        //$this->limit=60*60 * $this->interval ;

        include("./connection.php");
        $sql = "SELECT * FROM fisdatalog 
                WHERE fisdatalog.udid ='" . $this->udid ."' 
                AND fisdatalog.datum >'$timestamp' 
                $s
                ORDER BY fisdatalog.datum ASC LIMIT 0," . $this->limit;

        if ($this->interval==0){
            $sql = "SELECT * FROM fisdatalog 
                WHERE fisdatalog.udid ='" . $this->udid ."' 
               
                $s
                ORDER BY fisdatalog.datum DESC LIMIT 0," . $this->limit;
        }

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {

            AddSessionMessage("error", "Datenloggerdaten  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $this->rowcount=mysqli_affected_rows($mysqli);
            $row_rst = $rst->fetch_assoc();
            do {
                $this->dataall[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->dataall[] = array();
            $this->rowcount=0;
        }
        $mysqli->close();



    }

    function Add(){
        include ("./connection.php");

        $updateSQL = sprintf("INSERT INTO  fisdatalog (udid,datum,dtype, data1, data2, data3, data4, data5, data6, data7, data8, cpu) 
                                    VALUES (%s, %s, %s,  %s, %s, %s, %s, %s, %s, %s, %s, %s) ",
            GetSQLValueString($this->udid, "int"),
            GetSQLValueString(time(), "int"),
            GetSQLValueString($this->type, "text"),
            GetSQLValueString($this->idata[1], "text"),
            GetSQLValueString($this->idata[2], "text"),
            GetSQLValueString($this->idata[3], "text"),
            GetSQLValueString($this->idata[4], "text"),
            GetSQLValueString($this->idata[5], "text"),
            GetSQLValueString($this->idata[6], "text"),
            GetSQLValueString($this->idata[7], "text"),
            GetSQLValueString($this->idata[8], "text"),
            GetSQLValueString($this->idata[0], "text")

        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            return $updateSQL;
        }else{
            return $updateSQL;
        }

    }

    function SetSearchString($s=""){
        $this->search=utf8_encode($s);
        return $this;
    }

    function GetSearchString(){
        $s=utf8_decode($this->search);
        return $s;
    }
    function SetLimit($l=10000){
        if  (intval($l) <10001){
            $this->limit=intval($l);
            if ($this->limit <=10){
                $this->limit=10;
            }
        }else{
            $this->limit=10000;
        }
        return $this;
    }

    function SetInterval($l=10000){
        // in minutes
        if  (intval($l) <10001){
            $this->interval=intval($l);
            if ($this->interval <=10){
                $this->interval=10;
            }
        }else{

            $this->interval=10000;
        }

        return $this;
    }

    function SetDataSet($i){
        $this->dataset=$i;
        $_SESSION['diagrammdataset']=$i;
    }

    function GetDiagrammFunctions(){
        $f="";
        if (count($this->settings)>0){
            foreach ($this->settings as $i=>$item){
                $str="";
                foreach ($item as $n){ $str.=" / ".$n['name'];}

            $f.="<a href=\"./index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=26&equip=".$_SESSION['equip']
                ."&diagrammdataset=".$i."\" 
                 target=\"_self\"  class=\"btn btn-custom waves-effect waves-light \" >
                 <i class='fa fa-area-chart'></i> (".utf8_decode(substr($str,2)).")</a>";
            }
        }
        return $f;
    }

    function GetDiagrammDesc(){
        $str="";

        if (count($this->settings[$this->dataset])>0){
            foreach ($this->settings[$this->dataset] as $n){ $str.=" / ".$n['name'];}
        }
        return utf8_decode(substr($str,2));
    }
    function GetLimit(){
        return $this->limit;
    }

    function SetNoFreeValueAllowed($s=0){
        $this->no_free_search_value_allowed=$s;
        return $this;
    }


    function ImportNet(){
        $r=explode(";",$this->importdata);
        if (count($r)>0){
            for ($i=1; $i<9; $i++){
                $this->idata[$i]=$r[$i];
            }
        }
        return $this->Add();
    }
    function ImportTemp(){
        $r=explode(";",$this->importdata);
        if (count($r)>0){
            for ($i=0; $i<9; $i++){
                $this->idata[$i]=$r[$i];
            }
        }
        return $this->Add();
    }
    function ImportSystem(){
        $r=explode(";",$this->importdata);
        if (count($r)>0){
            for ($i=1; $i<9; $i++){
                $this->idata[$i]=$r[$i];
            }
        }
        return $this->Add();
    }

    function ImportData($type,$data){

        $this->importdata=$data;
        $this->type=$type;
        $s="";
        switch ($type){
            case "temp":{
                $s=$this->ImportTemp();
                break;
            }

            case "system":{
                $s=$this->ImportSystem();
                break;
            }

            case "net":{
                $s=$this->ImportNet();

                break;
            }
            default:{
                $s="error: unkown data type";
            }
        }

        return "UDID:".$this->udid." Import state:".$s.chr(13);
    }

    //Diagramm View


    function LoadDefaults(){
        /**
         * var uploads = [[0, 9], [1, 8], [2, 5], [3, 8], [4, 5], [5, 14], [6, 10],[7, 8], [8, 5], [9, 14], [10, 10]];
        var downloads = [[0, 5], [1, 12], [2, 4], [3, 3], [4, 12], [5, 11], [6, 14],[7, 12], [8, 8], [9, 4], [10, 8]];
        var plabels = ["Revenue", "Sales"];
        var pcolors = ['#ddd', '#86C3F4'];
        var borderColor = '#f5f5f5';
        var bgColor = '#fff';
         */


    }

    function GetDatalogValue($type,$value){
        switch ($type){
            case "temp":{
                $v=sprintf("%01.2f",(($value-27300)/1000))." °C";
                break;
            }
            default:{
                $v=$value;break;
            }
        }

        return $v;
    }

    function GetDiagrammValue($type,$value){
        switch ($type){
            case "temp":{
                $v=round( (($value-27300)/1000),2);
                break;
            }
            default:{
                $v=$value;break;
            }
        }

        return $v;
    }

    function GetCalibrate($field,$timestamp,$value){
        $v=-999999;
        if (count($this->settings[$this->dataset][$field]['calibration'])>0){
            foreach ($this->settings[$this->dataset][$field]['calibration'] as $i=>$item) {
                $gain=1;
                $offset=0;
                if (isset($this->settings[$this->dataset][$field]['calibration'][$i]['gain'])) $gain=$this->settings[$this->dataset][$field]['calibration'][$i]['gain'];
                if (isset($this->settings[$this->dataset][$field]['calibration'][$i]['offset'])) $offset=$this->settings[$this->dataset][$field]['calibration'][$i]['offset'];

                if ($i<$timestamp) {
                    $v = $value * $gain + ($offset*1000);
                }
            }
        }else{
            $v=$value;
        }
        return $v;
    }

    function GetFlotDiagrammData()
    {
        $r=array();
        //$this->GetDiagrammSettings("flot");
        $this->LoadAllData();
        if (count($this->dataall)>0) {
            $x=0;
            $y=0;
            $scale=$this->rowcount/12;
            foreach ($this->dataall as $e => $i) {

                $d="";


                if ($y >= $scale or $y==0) {
                    $y=1;
                    //$d="-".round((($this->rowcount*$this->interval/6/60/24)-($x*$this->interval/6/60/24)),1)."d";
                    if ($this->rowcount>=144) {
                        $d = date("d.m. H:i", $i['datum']);
                    }else {
                        $d=date("H:i",$i['datum']);
                    }

                }else{
                    $d="";
                }
                $x=$i['datum'];
                $y++;

                $r['ticks'][]=array($x, $d);
                $r['difference'][] =array($x, abs(
                    $this->GetDiagrammValue(
                        $i['dtype'],

                        $this->GetCalibrate(
                            $this->settings[$this->dataset]['difference']['differencedata'][0],
                            $i['datum'],
                            $i[$this->settings[$this->dataset]['difference']['differencedata'][0]]
                        )

                    )
                    -
                    $this->GetDiagrammValue(
                        $i['dtype'],
                        $this->GetCalibrate(
                            $this->settings[$this->dataset]['difference']['differencedata'][1],
                            $i['datum'],
                            $i[$this->settings[$this->dataset]['difference']['differencedata'][1]]
                        )
                    )
                                                )
                                        );
                $r['tickscombine'][] =array($x, $d );


                $r['data1'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data1",$i['datum'],$i["data1"])) );
                $r['data2'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data2",$i['datum'],$i["data2"])) );
                $r['data3'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data3",$i['datum'],$i["data3"])) );
                $r['data4'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data4",$i['datum'],$i["data4"])) );
                $r['data5'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data5",$i['datum'],$i["data5"])) );
                $r['data6'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data6",$i['datum'],$i["data6"])) );
                $r['data7'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data7",$i['datum'],$i["data7"])) );
                $r['data8'][] =array($x, $this->GetDiagrammValue($i['dtype'],$this->GetCalibrate("data8",$i['datum'],$i["data8"])) );
                $r['cpu'][]=array($x, $this->GetDiagrammValue($i['dtype'],$i['cpu']) );
            }
        }
        return $r;
    }


    function GetMorrisDiagrammData($data1="data1",$data2="70")
    {
        $r=array();
        $this->GetDiagrammSettings("mooris");
        $this->LoadAllData();
        if (count($this->dataall)>0) {
            $x=0;

            $y=0;
            foreach ($this->dataall as $e => $i) {
                $x++;
                $y++;
                $d="";


                $r[]=(array("y"=>date("Y-m-d H:i",$i['datum']),
                    "a"=>$this->GetDiagrammValue($i['dtype'], $this->GetCalibrate($data1,$i['datum'],$i[$data1])) ,
                    "b"=>$data2));

            }
        }
        return $r;
    }

    function GetLatestDiagrammData($data1="data1",$data2="70")
    {
        $r=array();

        $this->LoadAllData();
        if (count($this->dataall)>0) {
            $x=0;

            $y=0;
            foreach ($this->dataall as $e => $i) {
                $x++;
                $y++;
                $d="";


                $r[]=(array("y"=>date("Y-m-d H:i",$i['datum']),
                    "a"=>$this->GetDiagrammValue($i['dtype'], $this->GetCalibrate($data1,$i['datum'],$i[$data1])) ,
                    "b"=>$data2));

            }
        }
        return $r;
    }

    function GetPdfDiagrammData($data1="data1",$data2=70){
        $h=$this->GetMorrisDiagrammData($data1,$data2);
        return print_r($h,true);
    }


    function GetDiagrammValueCount(){
        return $this->rowcount;
    }

    function GetFlotDiagrammColumnsCount(){
        return count($this->settings[$this->dataset]);
    }

    function GetFlotDiagrammLabel(){
        $r=array();
        if (count($this->settings[$this->dataset])>0){
            foreach ($this->settings[$this->dataset] as $i=>$item) {
                $r[] = utf8_decode($item['name'])." (".$this->GetDiagrammValueCount()." Werte)";

            }
        }
        return $r;
    }
    function GetFlotDiagrammColor(){
        $r=array();
        if (count($this->settings[$this->dataset])>0){
            foreach ($this->settings[$this->dataset] as $i=>$item) {
                $r[] = $item['color'];
            }
        }
        return $r;
    }


    function GetFlotDiagrammValueColumns(){
        $r=array();
        if (count($this->settings[$this->dataset])>0){
            foreach ($this->settings[$this->dataset] as $i=>$item) {
                $r[] = $item['columns'];
            }
        }
        return $r;
    }

    function GetScripts(){
        $_SESSION['flot']="
        <!-- Flot chart js -->
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.time.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.tooltip.min.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.resize.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.pie.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.selection.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.stack.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.crosshair.js\"></script>
        <!-- flot init -->
        <script src=\"./flot.init.php?sess=".$_SESSION['sess']."\"></script>";
    }

    function GetWidgetFlotChartwithoutCardbox($text,$height=320,$col=6,$width=100){
        $this->GetScripts();
        $interval=$_SESSION['diagramm'][$_SESSION['diagrammdataset']][$this->viewid]['interval'];

        if ($interval==""){$interval=$this->interval;}

        $h="<div class=\"col-lg-$col\">
               
                    <h4 class=\"header-title m-t-0 m-b-30\">$text (letzte ".$interval." h)</h4>
                        <div id=\"LineDiagamm".$this->viewid."\" class=\"flot-chart\" style=\"width: ".$width."%;height: ".$height."px;\"></div>
              
            </div>";
        return $h;
    }


    function GetWidgetFlotChart($text,$height=320,$col=6,$width=100){
        $this->GetScripts();
        $interval=$_SESSION['diagramm'][$_SESSION['diagrammdataset']][$this->viewid]['interval'];

        if ($interval==""){$interval=$this->interval;}

        $h="<div class=\"col-lg-$col\">
                <div class=\"card-box\">
                    <h4 class=\"header-title m-t-0 m-b-30\">$text (letzte ".$interval." h)</h4>
                        <div id=\"LineDiagamm".$this->viewid."\" class=\"flot-chart\" style=\"width: ".$width."%;height: ".$height."px;\"></div>
                </div>
            </div>";
        return $h;
    }

    function ShowHtmlEventListLogs(){
        $this->LoadAllData();
        if (count($this->dataall)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Datalog (Type: $type)</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>D1</th>
                                <th>D2</th>
                                <th>D3</th>
                                <th>D4</th>
                                <th>D5</th>
                                <th>D6</th>
                                <th>D7</th>
                                <th>D8</th>
                                <th>CPU</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->dataall as $e => $i) {

                $h .= " <tr>
                            <td>".date("Y-m-d H:i",$i['datum'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data1",$i['datum'],$i["data1"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data2",$i['datum'],$i["data2"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data3",$i['datum'],$i["data3"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data4",$i['datum'],$i["data4"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data5",$i['datum'],$i["data5"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data6",$i['datum'],$i["data6"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data7",$i['datum'],$i["data7"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$this->GetCalibrate("data8",$i['datum'],$i["data8"]))."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['cpu'])."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Datenlog</h4>";
            $h.="<div class='text-center'><span class=\"label label-default\">Keine Daten dokumentiert</span></div>";
        }
        return $h;
    }


}