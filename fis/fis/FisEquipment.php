<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 07.10.18
 * Time: 10:29
 */
class FisEquipment
{
    public $plan=array();
    public $bks=6101;


    public $group=array(
1=>array("name"=>"Reparatur"),
2=>array("name"=>"Wartung"),
3=>array("name"=>"Instandsetzung"),
4=>array("name"=>"Kalibrierung"),
5=>array("name"=>"Neuanschaffung"),
/**
6 Stoerung
7 Verschrottung
8 Stilllegung
9 Umlagerung
10 Sonstiges
12 Einlagerung

100 Remotedata
101 Ordnungsgrad
102 Werkzeugwechsel
103 Verbesserung
104 Parameteränderung
105 Produktprüfung
**/
);

   

    public $equip=0;
    public $udid=0;
    public $data=array();
    public $events=array();
    public $settings=array();
    public $layout=array();

    public $users=array();
    public $eventtypes=array();
    public $logs=array();
    public $pplan=1;
    public $www=1;
    public $pduration=36000;
    public $degreeoforder=1;

    public $limit=100;
    public $search="";
    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $id_fhm_gruppe=0;
    public $lokz=0;
    public $eventtype=0;

    public  $wartung=false;
    public  $fail=false;


    function __construct($i=0)
    {
        $this->equip=$i;
        if ($i>0){
            $this->LoadData();
            $this->LoadEvent();
            $this->LoadUsers();
            $this->LoadSettings();
            $plans=new FisPlan();
            $this->plan=$plans->GetPlans();

        }
    }
    function __toString(){
        return $this->equip;
    }
    function LoadData(){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fhm WHERE fhm.id_fhm='$this->equip' ";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
                $this->data=$row_rst;
                $this->udid=$row_rst['udid'];
        }else{
            $this->data=array();
            AddSessionMessage("error","Equipment <code> FHMID $this->equip</code> nicht bekannt.","Datenbankfehler");
        }
        $mysqli->close();

    }
    function LoadEventTypes(){
        include ("./connection.php");

        $sql="SELECT * FROM fhm_ereignisart WHERE fhm_ereignisart.lokz='0'ORDER BY fhm_ereignisart.name ASC ";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->eventtypes[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->eventypes=array();
            AddSessionMessage("error","Ereignisarten <code> IDFHMEA </code> nicht bekannt.","Datenbankfehler");
        }


    }

    function LoadSettings(){
        if (strlen ($this->data['settings'])>10) {
            $this->settings = json_decode($this->data['settings'], true);

        }else{
            $this->settings = array();

        }

        if (strlen ($this->data['layout'])>10) {
            $this->layout = json_decode($this->data['layout'], true);

        }else{
            $this->layout=array();
        }
    }


    function Add($s){
        //id_fhm	id_fhm_gruppe	name	beschreibung	lokz	still	user	lastmod	baujahr	Inventarnummer	lagerort	
        //history	ortsveraend	gewicht	wbz	werk	timeyn	ortsfest	
        //artikelid	sn	suchgruppierung	bks	network	kdnummer2	kundenummer	passwort	ansprechpartner	anrede	email	homepage

        if ($s==""){
            AddSessionMessage("error", "Equipment  <code> FHMID " . $s. "</code> wurde nicht angelegt.", "Bezeichnung fehlt.");

        }else {
            include("./connection.php");
            $sql = "SELECT fhm.id_fhm FROM fhm WHERE fhm.name = '" . $s . "' LIMIT 0,1";
            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) == 0) {

                //equip	sendungFHMID	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk

                $updateSQL = sprintf("INSERT INTO  fhm (`name`,id_fhm_gruppe, beschreibung, lokz,still,`user`,baujahr,
                    Inventarnummer,lagerort,sn, udid, settings, layout, werk) 
                                            VALUES (%s, %s, %s, %s,%s,  %s, %s, %s, %s, %s, %s, %s, %s, %s)",

                    GetSQLValueString($_REQUEST['name'], "text"),
                    GetSQLValueString($_REQUEST['id_fhm_gruppe'], "int"),
                    GetSQLValueString($_REQUEST['beschreibung'], "text"),
                    GetSQLValueString($_REQUEST['lokz'], "int"),
                    GetSQLValueString($_REQUEST['still'], "int"),
                    GetSQLValueString($_SESSION['active_user'], "int"),
                    GetSQLValueString($_REQUEST['baujahr'], "text"),
                    GetSQLValueString($_REQUEST['Inventarnummer'], "text"),
                    GetSQLValueString($_REQUEST['lagerort'], "text"),
                    GetSQLValueString($_REQUEST['sn'], "text"),
                    GetSQLValueString($_REQUEST['udid'], "text"),
                    GetSQLValueString($_REQUEST['settings'], "text"),
                    GetSQLValueString($_REQUEST['layout'], "text"),
                    GetSQLValueString($this->bks, "int")

                );


                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {
                   
                    AddSessionMessage("error", "Equipment  <code> FHMID " . $s . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
                } else {
                   
                    $txt = "Equipment  <code> FHMID" . $s . "</code> wurde angelegt.";
                    AddSessionMessage("success", $txt, "Equipment gespeichert");
                    $this->equip = mysqli_insert_id($mysqli);
                    $_SESSION['equip'] = $this->equip;
                    $_GET['equip'] = $this->equip;
                    $w = new FisWorkflow($txt, "equip", $this->equip);
                    $w->Add();

                    $this->AddEvent(5,$txt);

                }

            } else {
                AddSessionMessage("error", "Equipment  <code> FHMID " . $s . "</code> wurde nicht angelegt, weil die Bezeichnung bereits verwendet wurde.", "Bezeichnung fehlerhaft.");

            }
        }
    }
    
    
    function Save(){
        $s=$_REQUEST['name'];

        if (isset($_REQUEST['fhm_save'])){$this->equip=$_SESSION['equip'];}
        //equip	sendungFHMID	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk
        if ($this->equip >0){
            include ("./connection.php");
            $updateSQL=sprintf("
                UPDATE fhm SET `name`=%s, id_fhm_gruppe=%s,	beschreibung=%s, lokz=%s,	still=%s, `user`=%s,baujahr=%s,	
                Inventarnummer=%s,	lagerort=%s, sn=%s, udid=%s, settings=%s, layout=%s
                WHERE id_fhm='$this->equip' ",
                GetSQLValueString($_REQUEST['name'], "text"),
                GetSQLValueString($_REQUEST['id_fhm_gruppe'], "int"),
                GetSQLValueString($_REQUEST['beschreibung'], "text"),
                GetSQLValueString($_REQUEST['lokz'], "int"),
                GetSQLValueString($_REQUEST['still'], "int"),
                GetSQLValueString($_SESSION['active_user'], "int"),
                GetSQLValueString($_REQUEST['baujahr'], "text"),
                GetSQLValueString($_REQUEST['Inventarnummer'], "text"),
                GetSQLValueString($_REQUEST['lagerort'], "text"),
                GetSQLValueString($_REQUEST['sn'], "text"),
                GetSQLValueString($_REQUEST['udid'], "int"),
                GetSQLValueString($_REQUEST['settings'], "text"),
                GetSQLValueString($_REQUEST['layout'], "text")
            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
               
                AddSessionMessage("error","Änderung von Equipment von  <code> FHMID " . $this->equip . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

            }else{
               
                $txt="Equipment  <code> FHMID " . $this->equip . " </code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Equipment gespeichert");
                $w=new FisWorkflow($txt,"equip",$this->equip);
                $w->Add();

                $d=new FisDocument("Dokumente zum Equipment","equip",$this->equip);
                $d->AddNewDocument();
            }
        }else{
            $this->Add($s); //check Wp in DB ?
        }

    }

    function AddEquipmentEvent(){
        $i=$this->AddEvent($_REQUEST['id_fhmea'],$_REQUEST['notes']);
        if ($i>0) {
            $d = new FisDocument("Dokumente zum Event", "event", $i);
            $d->AddNewDocument();
        }
        return $this;
    }
    
    
    function ListEventTypes($type,$value){
        $this->LoadEventTypes();
        $h="";
        if ($type=="data"){
            return $this->eventtypes;
        }else {
            switch ($type) {
                case "select": {
                    foreach ($this->eventtypes as $index => $item) {
                        if ($value == $item['id_fhmea']) {
                            $s = "selected";
                        } else {
                            $s = "";
                        }
                        $h .= "<option value=\"" . $item['id_fhmea'] . "\" $s>" . $item['name'] . "</option>";
                    }
                    break;
                }
                case "text": {
                    foreach ($this->eventtypes as $index => $item) {
                        if ($value == $item['id_fhmea']) {
                            $h = $item['name'];
                        }
                    }
                    break;
                }
            }
            return $h;
        }
    }

    function ListTools($type,$value){
        $tools=new FisTool();
        $fhm_tool=$tools->GetTools();
        $h="";
        if ($type=="data"){
            return $fhm_tool[$this->www];
        }else {
            switch ($type) {
                case "select": {
                    if (count($fhm_tool[$this->www])>0){
                        foreach ($fhm_tool[$this->www] as $index => $item) {
                            if ($value == $item['id']) {
                                $s = "selected";
                            } else {
                                $s = "";
                            }
                        $h .= "<option value=\"" . utf8_decode($item['name']) . "\" $s>" .utf8_decode($item['name']) . "</option>";
                        }
                    }
                    break;
                }
                case "text": {
                    if (count($fhm_tool[$this->www])>0){
                    foreach ($fhm_tool[$this->www] as $index => $item) {
                        if ($value == $item['id']) {
                            $h =utf8_decode($item['name']);
                        }
                    }
                    }
                    break;
                }
            }
            return $h;
        }
    }

    function LoadEvent($eventtype=0,$order="DESC",$limit=100){

        $s="";
        if ($eventtype>0) $s=" AND fhm_ereignis.id_fhmea=$eventtype";
        if ($this->pduration>0 && $eventtype==2) $s.=" AND fhm_ereignis.timestamp >'".(time()-$this->pduration)."' ";
        if ($this->pduration > 0 && $eventtype == 4) $s .= " AND fhm_ereignis.timestamp >'" . (time() - $this->pduration) . "' ";

        $this->events=array();

        $sql="SELECT * FROM fhm_ereignis 
              WHERE fhm_ereignis.id_fhm='$this->equip' $s 
              ORDER BY fhm_ereignis.datum $order, fhm_ereignis.id_fhme $order LIMIT 0,$limit";
        include ("./connection.php");
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->events[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->events=array();
            //AddSessionMessage("error","Ereignisse <code> $eventtype </code> nicht bekannt.","Datenbankfehler");

        }
        $mysqli->close();

    }

    function LoadLogs($eventtype="temp",$order="DESC",$limit=100){
        include ("./connection.php");
        $s="";
        $this->logs=array();
        if (strlen($eventtype)>0) $s=" AND fisdatalog.dtype='$eventtype' ";

        $sql="SELECT * FROM fisdatalog 
              WHERE fisdatalog.udid='$this->udid' $s 
              ORDER BY fisdatalog.datum $order LIMIT 0,$limit";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->logs[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->logs=array();

        }
        $mysqli->close();
    }

    function LoadAllData()
    {
        if ($this->limit > 0 ) {
            if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
                $this->dataall = array();
                return $this;
            } else {
                $s="";
                if ($this->id_fhm_gruppe > 0){
                    $s=" AND fhm.id_fhm_gruppe='".$this->id_fhm_gruppe."'";
                }


                    $s.=" AND fhm.lokz='".$this->lokz."'";

                include("./connection.php");
                $sql = "SELECT * FROM fhm 
                        WHERE (fhm.name like '%" . $this->search . "%'
                        OR fhm.sn like '%" . $this->search . "%'
                        OR fhm.beschreibung like '%" . $this->search . "%'
                        OR fhm.Inventarnummer like '%" . $this->search . "%'
                        OR fhm.lagerort like '%" . $this->search . "%'
                         ) 
                         $s
                         AND fhm.invisible=0
                         ORDER BY fhm.name DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Equipmentdaten  <code>" . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
                }
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {
                        $this->dataall[] = $row_rst;
                    } while ($row_rst = $rst->fetch_assoc());

                }else{
                    $this->dataall[] = array();
                }
                $mysqli->close();
            }

        }
    }

    function AddEvent($type,$desc,$testdevice=0,$datum=""){
        if ($testdevice=="" or $testdevice<=0){ $testdevice=0;}
        include ("./connection.php");
        if ($datum=="") $datum=date("Y-m-d",time());
        $updateSQL = sprintf("INSERT INTO  fhm_ereignis (id_fhm,id_fhmea,datum,`timestamp`, beschreibung,`user`,id_p) VALUES (%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString(utf8_decode($this->equip), "int"),
            GetSQLValueString(utf8_decode($type), "int"),
            GetSQLValueString($datum,"date"),
            GetSQLValueString(time(),"int"),
            GetSQLValueString(utf8_decode($desc), "text"),
            GetSQLValueString($_SESSION['active_user'], "int"),
            $testdevice
        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
           
            AddSessionMessage("error","Event wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }else{
            $i=$mysqli->insert_id;
            AddSessionMessage("success", "Event <code> " . $i . "</code> wurde gespeichert.", "Neu anlegen");
            if ($type<=5) $this->SaveEventReferenz($i,$i);

            if ($type==100) $this->SaveEventReferenz($i,$i);
            if ($type==101) $this->SaveEventReferenz($i,$i);
            if ($type==102) $this->SaveEventReferenz($i,$i);

            if ($type==105) $this->SaveEventReferenz($i,$i);
            return $i;
        }
        $mysqli->close();
    }

    function SaveEventReferenz($event,$ref){
        include ("./connection.php");
        $updateSQL=sprintf("
            UPDATE fhm_ereignis SET fhm_ereignis.id_ref_fhme=%s 
            WHERE fhm_ereignis.id_fhme='$event' ",
            $ref
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
           
            AddSessionMessage("error","Rückmeldung auf Event <code> " . $ref . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{
            AddSessionMessage("success", "Rückmeldung auf Event <code> " . $ref . "</code> wurde gespeichert.", "Update Event");
        }
    }



    function LoadUsers(){
        $this->users=$_SESSION['logon_user'];
    }

    function AddNewEvent(){


        switch ($_GET['action']){
            case 1:{
                $t['text']="Wartung durchgeführt";

                if (count($this->plan[$this->pplan]['plan']) > 0) {
                    AddSessionMessage("success", "Wartung <code>PLAN-ID: ".$this->pplan." </code> durchgeführt.","Wartung");
                    foreach ($this->plan[$this->pplan]['plan'] as $d => $i) {

                        if (isset($_POST['checkbox'.$d])) {
                            $t[ $i['desc'] ]=$i['name'];
                            if (strlen ($_POST['checkbox'.$d])>1){
                                $t[ $i['desc'] ] =$i['name'].":".$_POST['checkbox'.$d];
                            }
                        }
                    }
                }else{
                    AddSessionMessage("error", "Wartung <code>PLAN-ID: ".$this->pplan." </code> an diesem Arbeitsplatz nicht vorhanden.","Wartung");
                }
                $this->AddEvent(2,json_encode($t));
                break;
            }
            case 2:{
                $t['text']="Störung";
                if (count($this->plan[$this->pplan]['plan']) > 0) {
                    foreach ($this->plan[$this->pplan]['plan'] as $d => $i) {
                        if (isset($_POST['checkbox'.$d])) {
                            $t[ $i['desc'] ]=" nicht ".$i['name'];
                            if (strlen ($_POST['checkbox'.$d])>1){
                                $t[ $i['desc'] ] =$i['name'].":".$_POST['checkbox'.$d];
                            }
                        }
                    }
                }
                $this->AddEvent(6,json_encode($t));
                break;
            }

            case 3:{
                $t=array(
                    "text"=>"Idee",
                    "idea"=>$_POST['new_data'],
                    "hide_user"=>$_POST['checkbox1'],
                );
                $this->AddEvent(103,json_encode($t));
                break;
            }



            case 8:{
                $t=array(
                    "text"=>"Parameteränderung",
                    "parameter"=>$_POST['parameter'],
                    "old_value"=>$_POST['m1'],
                    "new_value"=>$_POST['m2'],
                    "desc"=>$_POST['new_data']
                );

                $this->AddEvent(104,json_encode($t));
                break;
            }
            case 9:{
                $t=array(
                    "text"=>"Parameteränderung",
                    "tool"=>$_POST['m1'],
                    "desc"=>$_POST['new_data']
                );
                $this->AddEvent(102,json_encode($t));
                break;
            }
            case 10:{
                $ref=$this->AddEvent($_POST['m'],$_POST['new_data'],0,$_POST['m1']);
                $this->SaveEventReferenz($_POST['m2'],$ref);
                break;
            }

            case 12:{
                $t=array(
                    "text"=>"Produktprüfung",
                    "tool"=>$_POST['m1'],
                    "weight"=>$_POST['m2']
                );
                $this->AddEvent(105,json_encode($t));
                break;
            }
            case 13:{
                $t=array(
                    "text"=>"Kalibrierung",
                    "programm"=>$_POST['m1'],
                    "weight"=>$_POST['m2']
                );
                $this->AddEvent(4,json_encode($t));
                break;
            }
        }

    }


    function GetEqipmentName(){
        return utf8_decode($this->data['name']);
    }

    function GetUDID(){
        return $this->data['udid'];
    }

    function SetSearchString($s=""){
        $this->search=utf8_encode($s);
        return $this;
    }

    function GetSearchString(){
        $s=utf8_decode($this->search);
        return $s;
    }
    function SetLimit($l=100){
        if  (intval($l) <501){
            $this->limit=intval($l);
            if ($this->limit <=10){
                $this->limit=100;
            }
        }else{
            $this->limit=500;
        }
        return $this;
    }

    function GetLimit(){
        return $this->limit;
    }

    function SetNoFreeValueAllowed($s=0){
        $this->no_free_search_value_allowed=$s;
        return $this;
    }

    function GetStatus(){
        $s="";
        if ($this->data['lokz']) {
            $s = "gelöscht";
        } else {
            if ($this->data['still']) {
                $s = "stillgelegt";
            } else {
                $s = "<i class='fa fa-check'> </i> "
                    . $this->ShowHtmlEventListMaintenanceSmall()
                    . "<i class='fa fa-warning'> </i> "
                    . $this->ShowHtmlEventListFailSmall();
        }}

        return $s;
    }

    function GetIntervall($i=0){
        if ($i>0){$this->pduration=$i;}
        $h="";
        if ($this->pduration< (24*60*60*2)){$h="täglich";}
        if ($this->pduration>=(24*60*60*2)){$h="alle 2 Tage";}
        if ($this->pduration>=(24*60*60*3)){$h="alle 3 Tage";}
        if ($this->pduration>=(24*60*60*4)){$h="alle 4 Tage";}
        if ($this->pduration>=(24*60*60*5)){$h="alle 5 Tage";}
        if ($this->pduration>=(24*60*60*6)){$h="alle 6 Tage";}
        if ($this->pduration>=(24*60*60*7)){$h="wöchentlich";}
        if ($this->pduration>=(24*60*60*7*2)){$h="alle 2 Wochen";}
        if ($this->pduration>=(24*60*60*31)){$h="monatlich";}
        if ($this->pduration>=(24*60*60*31*3)){$h="quartalsweise";}
        if ($this->pduration>=(24*60*60*365)){$h="jährlich";}

        return $h;
    }

    function ShowHtmlEventSaveMaintenance($i=1){
        $h="<div class=\"row\">
                <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b> Wartungsarbeiten dokumentieren (".$this->plan[$this->pplan]['desc'].")</b></h4>
                        <form method='post' enctype='multipart/form-data'>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Arbeitsschritt</th>
                                <th>Was</th>
                                <th>Ergebnis</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->plan[$this->pplan]['plan']) > 0) {
            foreach ($this->plan[$this->pplan]['plan'] as $d => $i) {

                $p=new FisPlan(0);
                $h.=$p->ShowHtmlPlanConfirm($i,$d);

            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="<button name=\"new_maintenance\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
                                Neu anlegen
                            </button>			
						 </form>
					</div>
                </div>
             </div>";

        return $h;
    }


    function ShowHtmlEventSaveFail(){
        $h="<div class=\"row\">
                <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Störmeldung anlegen (".$this->plan[$this->pplan]['desc'].")</b></h4>
                        <form method='post' enctype='multipart/form-data'>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Prüfschritt</th>
                                <th>Was</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->plan[$this->pplan]['plan']) > 0) {
            foreach ($this->plan[$this->pplan]['plan'] as $d => $i) {
                $p=new FisPlan(0);
                $h.=$p->ShowHtmlPlanConfirm($i,$d,"FF0000",false);
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

                            $h.="<button name=\"new_fail\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
                                Neu anlegen
                            </button>			
						 </form>
					</div>
                </div>
             </div>";
        return $h;
    }


    function ShowHtmlEventSaveImprovement(){
        $h="<div class=\"row\">
                <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Beschreiben Sie die Verbesserung / Idee</b></h4>
                        <form method='post' enctype='multipart/form-data'>
                            <div class=\"checkbox checkbox-danger m-b-15\">
                                        <input name=\"checkbox1\"  id=\"checkbox1\" type=\"checkbox\">
                                        <label for=\"checkbox1\">
                                            Benutzernamen verbergen (Inkognito)
                                        </label>
                                    </div>  
                            <textarea id=\"new_data\" name=\"new_data\" class=\"form-control\" rows='15'>Verbesserung von
                            </textarea><hr>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="                    <button name=\"new_improvement\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
                                Neu anlegen
                            </button>			
						 </form>
					</div>
                </div>
             </div>";


        /**  $_SESSION['script']="
                $('.summernote').summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: true                 // set focus to editable area after initializing summernote
                });

               
                ";
         */
        return $h;
    }

    function ShowHtmlEventSaveConfirm(){
        return "ShowHtmlEventSaveConfirm";
    }

    function ShowHtmlEventSaveParameterChange(){
        $h="
        <form method='post' enctype='multipart/form-data'>
            <div class=\"row\">
                <div class=\"col-sm-6\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Parameteränderung</b></h4>
                         <div class=\"form-group\">
                                <label for=\"parameter\" class=\"col-sm-4 control-label\">Was wurde geändert</label>
                                <div class=\"col-sm-7\">
                                    <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"parameter\" id=\"parameter\" placeholder=\"Parameter eingeben\" value=\"\">
                                </div>
                            </div>
                            <hr>
                           <div class=\"form-group\">
                                <label for=\"m1\" class=\"col-sm-4 control-label\">Alter Wert</label>
                                <div class=\"col-sm-7\">
                                    <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"m1\" id=\"m1\" placeholder=\"alter Wert\" value=\"\">
                                </div>
                            </div>
                             <div class=\"form-group\">
                                <label for=\"m2\" class=\"col-sm-4 control-label\">Neuer Wert</label>
                                <div class=\"col-sm-7\">
                                    <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"m2\" id=\"m2\" placeholder=\"Neuer Wert\" value=\"\">
                                </div>
                            </div>
                            <br><br><hr> <br><br><hr>
                        </div>
                    </div>
                    <div class=\"col-sm-6\">
                        <div class=\"card-box\">
                            <textarea id=\"new_data\" name=\"new_data\" class=\"form-control\" rows='15'>Grund:
</textarea><hr>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="                    <button name=\"new_parameterchange\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
                                Änderung speichern
                            </button>			
						
					</div>
                </div>
             </div> 
          </form>";


        /**  $_SESSION['script']="
                $('.summernote').summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: true                 // set focus to editable area after initializing summernote
                });

               
                ";
         */

        return $h;
    }

    function ShowHtmlEventSaveWWW(){
        $h="
        <form method='post' enctype='multipart/form-data'>
            <div class=\"row\">
                <div class=\"col-sm-6\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Werkzeugwechsel</b></h4>
                           <div class=\"form-group\">
                                <label for=\"m1\" class=\"col-sm-4 control-label\">Werkzeugeinbau</label>
                                <div class=\"col-sm-7\">
                                    <select  class=\"form-control select2\" name=\"m1\" id=\"m1\">";
        $h.=$this->ListTools("select",0);
         $h.="                      </select>
                                </div>
                            </div>";


         $h.="                   <br><br><hr>
                        </div>
                    </div>
                    <div class=\"col-sm-6\">
                        <div class=\"card-box\">
                        <div class=\"form-group\">
                        <label for=\"new_data\" class=\"col-sm-4 control-label\">Notizen / Bemerkungen</label>
                            <textarea id=\"new_data\" name=\"new_data\" class=\"form-control\" rows='10'></textarea>
                            </div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="<button name=\"new_www\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
            Werkzeugwechsel speichern
        </button>			
						
					</div>
                </div>
             </div> 
          </form>";

        /**
        $_SESSION['script']="
                $('.summernote').summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: true                 // set focus to editable area after initializing summernote
                });

               
                ";
         */

        return $h;
    }

    function ShowHtmlEventSaveProve(){
        $h="
        <form method='post' enctype='multipart/form-data'>
            <div class=\"row\">
                <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Produktprüfung</b></h4>
                           <div class=\"form-group\">
                                <label for=\"m1\" class=\"col-sm-4 control-label\">Produkt</label>
                                <div class=\"col-sm-7\">
                                    <select  class=\"form-control select2\" name=\"m1\" id=\"m1\">";
        $h.=$this->ListTools("select",0);
        $h.="                      </select>
                      <p></p>
                                </div>
                            </div>
                           <div class=\"form-group\">
                          <label for=\"m2\" class=\"col-sm-4 control-label\">Gewicht kg</label>
                          <div class=\"col-sm-7\">
                          <p></p>
                            <input id=\"m2\" name=\"m2\" class=\"form-control\" placeholder='Gewicht eingeben' value=''>
                            </div><hr>
                          </div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <p></p>
            <hr>
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="<button name=\"new_prove\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\">
            Prüfung speichern
        </button>			
						
					</div>
                </div>
             </div> 
          </form>";



        return $h;
    }

    function ShowHtmlEventSaveCalibration(){
        $h="
        <form method='post' enctype='multipart/form-data'>
            <div class=\"row\">
                <div class=\"col-sm-12\" id='change'>
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Kalibrierung</b></h4>
                           <div class=\"form-group\">
                                <label for=\"m1\" class=\"col-sm-4 control-label\">Produkt</label>
                                <div class=\"col-sm-7\">";
        $h .= asSelectBox("", "m1", GetFhmCalibrationText("select", ""), 12);


        $h .= "                      <p></p>
                                </div>
                            </div>
                           <div class=\"form-group\">
                          <label for=\"m2\" class=\"col-sm-4 control-label\">Gewicht in g</label>
                          <div class=\"col-sm-7\">
                          <p></p>
                            <input id=\"m2\" name=\"m2\" class=\"form-control\" placeholder='Gewicht eingeben' value=''>
                            </div><hr>
                          </div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <p></p>
            <hr>
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";

        $h.="<button name=\"new_calibration\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-primary waves-effect waves-light pull-right\">
            Kalibrierung speichern
        </button>			
						
					</div>
                </div>
             </div> 
          </form>";



        return $h;
    }

    function ShowHtmlEvent(){
        $event= new FisEvent($_GET['event']);

        $h="<div class=\"row\">
             <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Ereignis anzeigen</b></h4>";

        $h.=$event->ShowEvent();

        if ($event->GetRefEvent()>0){
            $refevent= new FisEvent($event->GetRefEvent());
            $h.=$refevent->ShowEvent();
        }
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";
        $h.="<a class='btn btn-custom pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=5&equip=".$this->equip."&materialgroup=&material=\"> weitere Ereignisse / Historie anzeigen </a>";


        $h.="</div></div> </div></div>";
        return $h;
    }

    function ShowHtmlEventSaveEvent(){
        $event= new FisEvent($_GET['event']);
        $_SESSION['summernote']=1;
        $h="
        <form method='post' enctype='multipart/form-data'>
            <div class=\"row\">
             <div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Rückmeldung auf Ergeignis</b></h4>
                        <div class=\"col-sm-6\">
                            
                            ".$event->ShowEvent()."
                            
                            <div class=\"row\">
                                <div class=\"col-lg-6\">
                                    <label>Typ</label>
                                    <select class=\"form-control select2\" name=\"m\" id=\"m\" >";
                                    $h.=$this->ListEventTypes("select",1);
                                    $h.="</select>
                          
                                </div>
                                <div class=\"col-lg-6\">
                              
                                    <label>Datum</label>
                                    <input class=\"form-control\" name='m1' id=\"m1\" value='".date("Y-m-d",time())."'>
                                     <input type='hidden' name='m2' id=\"m2\" value='".$_GET['event']."'>
                                </div><!-- end col -->
                             </div> 
                         </div>  
                      
                         <div class=\"col-sm-6\">";
                        $h.=asTextarea("Bemerkungen","new_data","",20,12,"");

                           
		$h.="</div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";


        $h.="<div class='pull-right'> 
                <button name=\"new_event\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\">
                                Änderung speichern
                            </button>
					 </div>
			    </div>
					
                </div>
             </div> ";

         $h.="</form>";


        /**
         *
         *
         *  <textarea id="new_data" name="new_data" class="summernote">

        </textarea>


        $('.summernote').summernote({
        height: 350,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: true                 // set focus to editable area after initializing summernote
        });
         *
         */

        $_SESSION['script']="
                
                jQuery('#m1').datepicker({
                    format: \"yyyy-mm-dd\",
                    todayHighlight: true,
                    
                });
                $(\".select2\").select2({
                
                 });
                ";

        return $h;
    }

    function ShowHtmlTimeline() {
        if ($_REQUEST['fhm_search_eventtype']=="0"){$_REQUEST['fhm_search_eventtype']=1;}

        $js="onChange=\"Javascript: form.submit()\"";
        $this->LoadEvent($_REQUEST['fhm_search_eventtype']);
        $h=" <div class=\"row\"><div class=\"col-sm-12 \"><div class='card-box table-responsive'>";
        $h .= asSelect2Box("Ereignisart", "fhm_search_eventtype", GetEventTypeName("select2", $_REQUEST['fhm_search_eventtype']),9,"",$js);

        $h.="</div></div></div>";
        $h.=" <div class=\"row\">
                            <div class=\"col-sm-12\">
                                <div class=\"timeline\">
                                    <article class=\"timeline-item alt\">
                                        <div class=\"text-right\">
                                            <div class=\"time-show first\">
                                                <a href=\"#\" class=\"btn btn-primary w-lg\">Today</a>
                                            </div>
                                        </div>
                                    </article>";

        if (count($this->events)>0){
            foreach ($this->events as $i=>$e){
                if ($i%2) { $alt="alt";} else {$alt="";}


                $event= new FisEvent($e['id_fhme']);

                $h.="<article class=\"timeline-item $alt\">
                      <div class=\"timeline-desk\">
                        <div class=\"panel\">
                            <div class=\"panel-body\">
                                <span class=\"arrow-alt\"></span>
                                <span class=\"timeline-icon\"></span>
                                <h4 class=\"text-custom\">".$event->GetType()."</h4>
                                <p class=\"timeline-date text-muted\"><small>".$event->GetDate()."</small></p>
                                <p>".$event->ShowEvent(true)."</p>
                               
                            </div>
                        </div>
                    </div>
                </article>";
    
            }
        }

        $h.="</div></div></div>";
        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=\"> Abbrechen </a>";
        $h.="</div></div>";
        return $h;
    }

    function ShowHtmlSettings(){
        $h="<div class=\"row\">";
        $wp= new FisWorkplace($_SESSION['wp']);
        $h.=$wp->ShowHtmlWpProperties();
        $h.="<div class=\"col-sm-3\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Equipment</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Paramter</th>
                                <th>Wert</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->data) > 0) {
            foreach ($this->data as $d => $i) {
                $h .= "    <tr>
                                <td>".$d."</td>
                                <td>".$i."</td>
                                <td></td>
                            </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>
                   </div>
                </div>";

    $h.="
                <div class=\"col-sm-3\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>angemeldete Benutzer</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Benutzer</th>
                                <th>Wert</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->users) > 0) {
            foreach ($this->users as $d => $i) {
                $u=new FisUser($d);
                $h .= "    <tr>
                                <td>".$d."</td>
                                <td>".$u->GetUsername()."</td>
                                <td></td>
                            </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>
                </div>
         </div>";

$h.="
            <div class=\"col-sm-3\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Pläne</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Prüfschitt</th>
                                <th>Wert</th>
                                <th>Erwartungsbereich</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->plan[$this->pplan]['plan']) > 0) {
            foreach ($this->plan[$this->pplan]['plan'] as $d => $i) {
                $h .= "    <tr>
                               <td>".$i['desc']."</td>
                                <td>".$i['info']."</td>
                                <td>".$i['name']."</td>
                            </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>";


        $h.="</div>
         </div>
         </div>";



        return $h;
    }

    function ShowHtmlSaveEvent($i){
        //$i=action
        $h="";
        switch ($i){
            case 1:{
                $h=$this->ShowHtmlEventSaveMaintenance();
                break;
            }
            case 2:{
                $h=$this->ShowHtmlEventSaveFail();
                break;
            }
            case 3:{
                $h=$this->ShowHtmlEventSaveImprovement();
                break;
            }
            case 4:{
                $h=$this->ShowHtmlEventSaveConfirm();
                break;
            }

            case 5:{
                $h=$this->ShowHtmlTimeline();
                break;
            }
            case 6:{
                $h=$this->ShowHtmlSettings();
                break;
            }
            case 8:{
                $h=$this->ShowHtmlEventSaveParameterChange();
                break;
            }
        }
        return $h;
    }


    function ShowHtmlEventList($i){
        // Startseite Widget#s
        $h="";
        switch ($i){
            case 1:{
                $h=$this->ShowHtmlEventListMaintenance();
                break;
            }
            case 2:{
                $h=$this->ShowHtmlEventListFail();
                break;
            }
            case 3:{
                $h=$this->ShowHtmlEventListImprovement();
                break;
            }
            case 7:{
                $h=$this->ShowHtmlEventDegreeOfOrder("knop");
                break;
            }
            case 8:{
                $h=$this->ShowHtmlEventListParameter();
                break;
            }
            case 9:{
                $h=$this->ShowHtmlEventListWWW();
                break;
            }
            case 12:{
                $h=$this->ShowHtmlEventListProductProvement();
                break;
            }
            case 13:{
                $h=$this->ShowHtmlEventListCalibration();
                break;
            }
            case 41:{
                $h=$this->ShowHtmlEventDatalogDiagramm();
                break;
            }

        }
        return $h;
    }



    function ShowHtmlEventDegreeOfOrder($type="knop"){
        $h="";
        switch($type){
            case "knop":{
                $this->LoadEvent(101,"DESC",1);
                if (count($this->events)>0){
                    foreach ($this->events as $i=>$item){
                        $value=json_decode($item['beschreibung'],true);
                        $h.=GetHtmlProgressbar("Ordnungsgrad",$value['result']);



                    }

                }
                break;
            }
        }

        return $h;
    }
    function ShowHtmlEventListMaintenance(){
        $this->LoadEvent(2,"DESC",2);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\"> Wartungen (".$this->GetIntervall().") </h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Wartung</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {

                $event=new FisEvent($i['id_fhme']);
                $h .= "               <tr>
                                <td>".$event->GetDesc()."</td>
                                <td>".$event->GetDate()."</td>
                                <td>".$event->GetState(false)."</td>
                                <td>".$event->GetEventUsername()."</td>
                            </tr>";
            }


            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Wartungen (".$this->GetIntervall().")</h4>";
            $h .= "<div class='text-center'><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&equip=" . $this->equip . "&action=1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Routine-Wartung fehlt. hier klicken um die Wartung zu dokumentieren\" data-original-title=\"Routine-Wartung fehlt. hier klicken um die Wartung zu dokumentieren\">
            <span class=\"label label-danger fa-2x\">Keine Wartung</span> 
            <br><br>  
            <span class=\"label label-danger fa-2x\"> dokumentiert ! </span>
            </a></div>";
        }
        return $h;
    }

    function ShowHtmlEventListMaintenanceSmall($wp=0){
        if ($wp==0) $wp=$_SESSION['wp'];
        $this->LoadEvent(2,"DESC",1);
        $h="";
        if (count($this->events)>0) {
            foreach ($this->events as $e => $i) {
                $event=new FisEvent($i['id_fhme']);
                $h .=$event->GetState(false,$wp)."";
            }

        }else{
            $h .= "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->equip . "&action=1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Routine-Wartung fehlt. hier klicken um die Wartung zu dokumentieren\" data-original-title=\"Routine-Wartung fehlt. hier klicken um die Wartung zu dokumentieren\">
            <span class='label label-danger'> <i class=\"fa fa-warning\"> </i></span> </a>";
            $this->wartung=false;
        }

        return $h;
    }

    function ShowHtmlEventListCalibrationSmall($wp = 0)
    {
        if ($wp == 0) $wp = $_SESSION['wp'];
        $this->LoadEvent(4, "DESC", 1);
        $h = "";
        if (count($this->events) > 0) {
            foreach ($this->events as $e => $i) {
                $event = new FisEvent($i['id_fhme']);
                $h .= $event->GetState(false, $wp) . "";
            }

        } else {
            $h .= "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->equip . "&action=13\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Kalibrierung fehlt. Zum Anzeigen der Kalibrierungsplans hier klicken\" data-original-title=\"Kalibrierung fehlt. Zum Anzeigen der Kalibrierungsplans hier klicken\">
            <span class='label label-danger'> <i class=\"fa fa-eye\"> </i></span> </a>";
            $this->wartung = false;
        }

        return $h;
    }

    function ShowHtmlLatestDatalog(){
        $h="";
        if ($this->GetLatestValue()<>"error"){
            $h .= "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->equip . "&action=26\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Zum Anzeigen der Datenloggerdaten hier klicken\" data-original-title=\"Zum Anzeigen der Datenloggerdaten hier klicken\">
            <span class=\"label label-inverse fa-1x\">".$this->GetLatestValue()."</span> </a>";
        } else{

            //http://albafis.de/fis/index.php?sess=b785eb33f63c6cd207e4310fe6f71ed3&wp=611401&equip=1320&action=5
            $h .= "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&equip=" . $this->equip . "&action=5\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"History verfügbar. Zum Anzeigen der Ereignishistorie hier klicken\" data-original-title=\"History verfügbar. Zum Anzeigen der Ereignishistorie hier klicken\">
            <span class=\"label label-inverse fa-1x\"><i class='zmdi zmdi-time-countdown'></i> </span> </a>";
        }
        return $h;
    }

    function ShowHtmlEventListFail(){
        $this->LoadEvent(6,"DESC",2);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Störungen</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Störung</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {
                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }


            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Störungen</h4>";
            $h.="<div class='text-center'><span class=\"label label-success\">Keine Störungen dokumentiert</span></div>";
        }
        return $h;
    }

    function ShowHtmlEventListFailSmall($wp=0){
        if ($wp==0) $wp=$_SESSION['wp'];
        $this->LoadEvent(6,"DESC",1);
        $h="";
        if (count($this->events)>0) {


            foreach ($this->events as $e => $i) {

                $event=new FisEvent($i['id_fhme']);
                $h .=$event->GetState(false,$wp)."<br>";
            }

        }else{
            $h.="<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=".$wp."&equip=".$this->equip."&action=1\">
            <span class='label label-success'> <i class=\"fa fa-check\"> </i></span> 
            </a>";
        }
        return $h;
    }

    function ShowHtmlEventListImprovement(){
        $this->LoadEvent(103);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Verbesserungen</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Idee</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {
                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Verbesserung</h4>";
            $h.="<div class='text-center'><span class=\"label label-default\">Keine Verbesserungen dokumentiert</span></div>";
        }
        return $h;
    }

    function ShowHtmlEventListWWW(){
        $this->LoadEvent(102);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Werkzeugwechsel</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Werkzeug</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {

                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Werkzeugwechsel</h4>";
            $h.="<div class='text-center'><span class=\"label label-default\">Keine Werkzeugwechsel dokumentiert</span></div>";
        }
        return $h;
    }
    function ShowHtmlEventListParameter(){
        $this->LoadEvent(104);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Parameteränderung</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Was</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {

                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Parameteränderung</h4>";
            $h.="<div class='text-center'><span class=\"label label-default\">Keine Parameteränderung dokumentiert</span></div>";
        }
        return $h;
    }
    function ShowHtmlEventListProductProvement(){
        $this->LoadEvent(105);
        if (count($this->events)>0) {
            $h = "<hr><h4 class=\"header-title m-t-0 m-b-30\">Produktprüfung</h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Was</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {

                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Produktprüfung</h4>";
            $h.="<div class='text-center'><span class=\"label label-default\">Keine Produktprüfung dokumentiert</span></div>";
        }
        return $h;
    }

    function ShowHtmlEventListCalibration($gauge = true)
    {

        if ($gauge) {
            $h = $this->ShowHtmlEventLatestCalibration();
        } else {
            $h = "";
        }
        $this->LoadEvent(4);
        if (count($this->events)>0) {
            $h.= "<hr><h4 class=\"header-title m-t-0 m-b-30\">Kalibrierung (".$this->GetIntervall().") </h4>

                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Was</th>
                                <th>Wann</th>
                                <th>Status</th>
                                <th>Wer</th>
                            </tr>
                            </thead>
                            <tbody>";

            foreach ($this->events as $e => $i) {

                $event = new FisEvent($i['id_fhme']);
                $h .= " <tr>
                            <td>".$event->GetDesc()."</td>
                            <td>".$event->GetDate()."</td>
                            <td>".$event->GetState(false)."</td>
                            <td>".$event->GetEventUsername()."</td>
                        </tr>";
            }

            $h .= "                 </tbody>
                        </table>
                     </div>";
        }else{
            $h="<hr><h4 class=\"header-title m-t-0 m-b-30\">Kalibrierung (".$this->GetIntervall().")</h4>";
            $h .= "<div class='text-center'><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&equip=" . $this->equip . "&action=13\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Kalibrierung fehlt. Zum Anzeigen der Kalibrierungsplans hier klicken\" data-original-title=\"Kalibrierung fehlt. Zum Anzeigen der Kalibrierungsplans hier klicken\">
            <span class=\"label label-danger fa-2x\">Kalibrierung</span> 
            <br><br>  
            <span class=\"label label-danger fa-2x\"> durchführen! </span>
            </a></div>";
        }
        return $h;
    }

    function ShowHtmlEventLatestCalibration(){
        $this->LoadEvent(4);
        $h="<hr>";
        if (count($this->events)>0){
            $event=new FisEvent($this->events[0]['id_fhme']);
            $r=json_decode($event->data['beschreibung'],true);

            $gauge= new FisGauge($this->equip);

            $gauge->SetGaugeProperties($this->layout);
            $gauge->SetGaugeValue($r['weight'] );

            $gauge->SetGaugeHeight(250);
            $gauge->SetGaugeWidth(250);
            $h.= $gauge->GetHtml();
        }
        return $h;
}


    function ShowHtmlEventDatalogDiagramm(){

        $settings=json_decode($this->data['settings'],true);
        $layout=json_decode($this->data['layout'],true);
        $color=array('#ddd','#42a5f5');

        $d = new FisDatalog($this->GetUDID());
        //$d->SetInterval($layout['interval']);
        $d->limit=$layout['limit'];
        $d->interval=0;
        $data=array();
        foreach ( $settings as $i=>$s) {
            $data[$i] = $d->GetMorrisDiagrammData($s['data1']['columns'],65);
            $label[$i] = $s['data1']['name'];
            $color[$i] = $s['data1']['color'];


        }

        $diagramm=new FisMorrisChart();

        $diagramm->SetDiagramm($this->equip,$data[1],$color);
        $diagramm->GetMorrisFunction();
        $link="<a href=\"./index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=26&equip=".$this->equip."&diagrammdataset=1\" 
                        target=\"_self\"  class=\"btn btn-inverse btn-sm waves-effect waves-light pull-right\" >weitere Details anzeigen</a>";


        $h="<div class=\"text-center\">
               $link
                <ul class=\"list-inline chart-detail-list\">
                    <li>
                        <h5><i class=\"fa fa-circle m-r-5\" style=\"color: #42a5f5;\"></i>Vorgabe</h5>
                    </li>
                    <li>
                        <h5><i class=\"fa fa-circle m-r-5\" style=\"color: ".$color[1].";\"></i>Istwert (".$label[1].")</h5>
                    </li>
                </ul>
            </div>
            <div id=\"morris-line-".$this->equip."\" style=\"height: 300px;\"></div>";

        $h .= "<div>" . $this->GetLatestValue("time") . "<span class='fa fa-5x'>" . $this->GetLatestValue("temp") . "</span></div>";
        return $h;
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

    function ShowHtmlEventListLogs($type="temp"){
        $this->LoadLogs($type,"DESC",10);
        if (count($this->logs)>0) {
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

            foreach ($this->logs as $e => $i) {

                $h .= " <tr>
                            <td>".date("Y-m-d H:i",$i['datum'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data1'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data2'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data3'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data4'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data5'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data6'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data7'])."</td>
                            <td>".$this->GetDatalogValue($i['dtype'],$i['data8'])."</td>
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

    function ShowHtmlAllAsTable($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Equipmentdaten Suchbegriff:(".$this->GetSearchString().") </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            order: [[ 0, \"desc\" ]],
                    
                            language: {
                                \"lengthMenu\": \"Zeige _MENU_ Datensätze pro Seite\",
                                \"zeroRecords\": \"Nichts gefunden - sorry\",
                                \"info\": \"Zeige Seite _PAGE_ von _PAGES_\",
                                \"infoEmpty\": \"Keine Treffer gefunden\",
                                \"infoFiltered\": \"(gefiltert von _MAX_ Datensätzen)\"
                            },
                            dom: \"<lengthMenu> Bfrtip\",
                            buttons: [{extend: \"copy\", className: \"btn-sm\"}, {extend: \"csv\", className: \"btn-sm\"}, {
                                extend: \"excel\",
                                className: \"btn-sm\"
                            }, {extend: \"pdf\", className: \"btn-sm\"}, {extend: \"print\", className: \"btn-sm\"}],
                            responsive: !0,
                            lengthMenu: [[-1,20,10, 25, 50, -1], [\"alle\",20,10, 25, 50, ]]
                        });
                    
                        var handleDataTableButtons = function () {
                            \"use strict\";
                            0 !== $(\"#datatable".$_SESSION['datatable']."\").length && table
                        }, TableManageButtons = function () {
                            \"use strict\";
                            return {
                                init: function () {
                                    handleDataTableButtons()
                                }
                            }
                        }();

     
            ";

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                             <th>Name</th>
                             <th>Inventarnummer</th>
                             <th>FHM</th>
                             <th>SN</th>
                             <th>Lagerort</th>
                             <th>Ansprechpartner</th>
                             
                             <th>Tel.</th>
                             <th>Fax</th>
                             <th>E-mail</th>
                             <th>Wartung - Störung</th>
                             <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            foreach ($this->dataall as $d => $i) {
                if ($i['id_fhm']>0) {

                    $link = "<a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=31&equip=" . $i['id_fhm'] . "&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Bearbeiten </a>";

                    $h .= "<tr>
                        <td>" . utf8_decode($i['name']) . "</td>
                        <td>" . utf8_decode($i['Inventarnummer']) . "</td>
                        <td>" . utf8_decode($i['id_fhm']) . "</td>
                        <td>" . utf8_decode($i['sn']) . "</td>
                        <td>" . utf8_decode($i['lagerort']) . "</td>
                        <td>" . utf8_decode($i['Ansprechpartner']) . "</td>
                        <td>" . utf8_decode($i['telefon']) . "</td>
                        <td>" . utf8_decode($i['faxnummer']) . "</td>
                        <td>" . utf8_decode($i['email']) . "</td>
                        <td>" . asJSLoader("layout_status_equip_" . $i['id_fhm'], "Status ", "data.php", "datatype=equipment&equipment=" . $i['id_fhm']) . "</td>
                       ";
                    $h .= "  <td>" . "$link" . "</td>";
                    $h .= "</tr>";
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Eqipments gefunden.";
        }
        return $h;
    }

    function GetLatestValue($type="temp"){
        $settings=json_decode($this->data['settings'],true);
        $d = new FisDatalog($this->GetUDID());
        $h="error";
        $d->limit=1;
        $d->interval=0;
        $d->latesttime=time()-(60*60);
        $data=array();
        if (count($settings)>0) {
            foreach ($settings as $i => $s) {
                $data[$i] = $d->GetLatestDiagrammData($s['data1']['columns'], 65);
                //$data[$i]['plabels'] = $d->GetFlotDiagrammLabel();
                //$data[$i]['pcolors'] = $d->GetFlotDiagrammColor();
            }
            if ($type == "temp") {
                return sprintf("%0.1f °C", $data[1][0]['a']);
            } else {
                return sprintf("%s", $data[1][0]['y']);
            }
        }else{
            return $h;
        }

    }

    function EditPropertyAsTable(){

        //id_fhm	id_fhm_gruppe	name	beschreibung	lokz	still	user	lastmod	baujahr	Inventarnummer	lagerort
        //history	ortsveraend	gewicht	wbz	werk	timeyn	ortsfest
        //artikelid	sn	suchgruppierung	bks	network	kdnummer2	kundenummer	passwort	ansprechpartner	anrede	email	homepage

        $h="";
        if ($this->equip==0) {
            $this->data['name']="Neuer Equipmentname";

        }
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Equipment bearbeiten (".$this->equip.")</b></h4>";
        $h.="<div class=\"table-responsive\">
            <table class=\"table table-striped\">
              <tbody><tr><td>";

        $h.=asTextField("Equipmentname","name",utf8_decode($this->data['name']));
        $h.="</td></tr><tr><td>";

        $h .= asSelect2Box("Equipmentgruppe", "id_fhm_gruppe", GetEquipmentGroupName("select2", $this->data['id_fhm_gruppe']));
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Beschreibung","beschreibung",utf8_decode($this->data['beschreibung']),4,7,"");
        $h.="</td></tr><tr><td>";

        $h .= asSelectBox("Löschen ?", "lokz", GetYesNoList("select", $this->data['lokz']));
        $h.="</td></tr><tr><td>";
        $h .= asSelectBox("Stillgelegt ?", "still", GetYesNoList("select", $this->data['still']));
        $h.="</td></tr><tr><td>";

        $h.=asTextField("Baujahr","baujahr",utf8_decode($this->data['baujahr']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Seriennummer","sn",utf8_decode($this->data['sn']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Inventarnummer","Inventarnummer",utf8_decode($this->data['Inventarnummer']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Lagerort","lagerort",utf8_decode($this->data['lagerort']),7,"");
        $h.="</td></tr><tr><td>";
        $h.="<hr>";
        $h.="</td></tr><tr><td>";
        $h.=asTextField("UDID","udid",utf8_decode($this->data['udid']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Layout der Visualisierung","layout",utf8_decode($this->data['layout']),4,7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Settings","settings",utf8_decode($this->data['settings']),4,7,"");

        $h.="</td></tr></tbody></table></div>";



            return $h;
        }

    function EditEventAsTable(){

        //id_fhm	id_fhm_gruppe	name	beschreibung	lokz	still	user	lastmod	baujahr	Inventarnummer	lagerort
        //history	ortsveraend	gewicht	wbz	werk	timeyn	ortsfest
        //artikelid	sn	suchgruppierung	bks	network	kdnummer2	kundenummer	passwort	ansprechpartner	anrede	email	homepage

        $h="";
        $d=new FisDocument("UPLOAD","event");
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Ereignis bearbeiten </b></h4>";
        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr><td>";

        $h .= asSelect2Box("Ereignisart", "id_fhmea", GetEventTypeName("select2", $this->data['id_fhmea']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Beschreibung","notes",utf8_decode($this->data['notes']),4,7,"");
        $h.="</td></tr><tr><td>";
        $h.=$d->ShowHtmlInputField();
        $h.="</td></tr></tbody></table></div>";



        return $h;
    }
}