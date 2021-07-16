<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.10.18
 * Time: 21:35
 */
class FisEvent
{
    public $event=0;
    public $data=array();
    public $dataall=array();
    public $eventtype="";
    public $group="";
    public $eventtypes=array();
    public $eventstate=0;
    public $lokz=0;
    public $limit=100;
    public $search="";


    function __construct($i)
    {
        $this->event=$i;
        if ($i>0){
            $this->LoadData();
            $this->LoadEventTypes();

        }
    }
    function __toString(){
        return $this->event;
    }
    function LoadData(){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhme='$this->event' ";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;
        }else{
            $this->data=array();
            AddSessionMessage("error","Equipment <code> EVENT $this->event</code> nicht bekannt.","Datenbankfehler");
        }


    }
    function LoadAllData(){
        include ("./connection.php");
        $s="";
        if ($this->eventstate==1){
            $s.="AND fhm_ereignis.id_ref_fhme='0' ";
        }
        if ($this->eventstate==2){
            $s.="AND fhm_ereignis.id_ref_fhme>'0' ";
        }
        if ($this->eventtype>0){
            $s.="AND fhm_ereignis.id_fhmea='".$this->eventtype."' ";
        }
        if ($this->group>0){
            $s.="AND fhm.id_fhm_gruppe='".$this->group."' ";
        }

        if (strlen($this->search)>0){
            $s.="AND ( fhm_ereignis.beschreibung LIKE '%".$this->search."%'  OR fhm.name LIKE '%".$this->search."%' )";
        }


        $this->dataall=array();

        $sql="SELECT fhm_ereignis.id_fhme,fhm_ereignis.id_fhm FROM fhm_ereignis,fhm 
              WHERE fhm_ereignis.id_fhm = fhm.id_fhm 
              AND fhm_ereignis.lokz='".$this->lokz."'  
              $s
              ORDER BY fhm_ereignis.datum DESC LIMIT 0,".$this->limit;

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->dataall[]=$row_rst;
            }while( $row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
            AddSessionMessage("info","keine Events zur Auswahl vorhanden.","Abfrageergebnis");
        }


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

    function GetName(){
        return $this->GetDesc();
    }

    function GetType(){
        return $this->ListEventTypes("text",$this->data['id_fhmea']);
    }

    function GetState($functions=true,$wp=0,$data=false){
        $d=array();
        if ($wp==0) $wp=$_SESSION['wp'];
        if ($this->data['id_ref_fhme'] == 0){
            $s="";
            if ($_SESSION['active_user']>0){
                $s = "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->data['id_fhm'] . "&event=" . $this->event . "&action=10\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Ereigbnis in Arbeit. Zum Anzeigen des Ereignisses hier klicken\" data-original-title=\"Ereigbnis in Arbeit. Zum Anzeigen des Ereignisses hier klicken\">";
            }
            if ($this->data['id_fhmea'] == 6) {
                $s .= "<span class=\"label label-danger\">offen</span>";
                $d['fail']=false;
            }else{
                $s .= "<span class=\"label label-danger\">offen</span>";
                $d['maintenance']=false;
            }
            $d['maintenance']=false;
            if ($_SESSION['active_user']>0){
                $s.="</a>";
            }


            if ($functions && $_SESSION['active_user']>0) {
                $s .= "<div class=\"btn-group m-b-10 pull-right\">
                            <div class=\"btn-group\">
                                <button type=\"button\" class=\"btn btn-default dropdown-toggle waves-effect\" data-toggle=\"dropdown\" aria-expanded=\"false\"> Funktionen <span class=\"caret\"></span> </button>
                                <ul class=\"dropdown-menu\">";
                $s .= "<li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->data['id_fhm'] . "&event=" . $this->event . "&action=10\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Satus offen. Öffnen Sie die Rückmeldung zum Ereignis\" data-original-title=\"Satus offen. Öffnen Sie die Rückmeldung zum Ereignis\">
                        <i class=\"zmdi zmdi-notifications-active\"></i> Rückmeldung auf Event</a></li>";

                $s .= "</ul><div></div>";
            }

        } else{
            if ($_SESSION['active_user']>0) {
                $s = "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $wp . "&equip=" . $this->data['id_fhm'] . "&event=" . $this->event . "&action=11\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Ereignis erledigt. Zum Anzeigen des Ereignis hier klicken\" data-original-title=\"Ereignis erledigt. Zum Anzeigen des Ereignis hier klicken\">";
            }
            if ($this->data['id_fhmea'] == 6){
                $s.="<span class=\"label label-success\">erl.</span>";
                $d['fail']=true;
            }else {
                $s.="<span class=\"label label-success\"> <i class='fa fa-check'> </i> </span>";
                $d['maintenance']=true;
            }
            if ($_SESSION['active_user']>0) {
                $s .= "</a>";
            }
        }

        if ($data){
            return $d;
        }else{
            return $s;
        }

    }

    function GetDate(){
        return $this->data['datum'];
    }

    function GetRefEvent(){
        if ($this->data['id_ref_fhme']==$this->event) {
            return 0;
        }else {
            return $this->data['id_ref_fhme'];
        }
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

    function GetDesc(){
        $h="";
        if (is_array(json_decode($this->data['beschreibung'],true))) {
            $value = json_decode($this->data['beschreibung'], true);
            switch ($this->data['id_fhmea']){

                case 2 :{
                    foreach ($value as $i=>$item) {
                        if ($i=="text"){
                            $h .=  FisTranslate($item) . "<br>";
                        }else{
                            $h .= FisTranslate($i) . ":" . FisTranslate($item) . "<br>";
                        }
                    }
                    break;
                }

                case 4 :{
                    $h .= "Kalibrierung:" . FisTranslate($value['programm']) . "<br>Gewicht=" . FisTranslate($value['weight']) . " g";
                    break;
                }

                case 6 :{
                    foreach ($value as $i=>$item) {
                        if ($i=="text"){
                            $h .=  FisTranslate($item) . "<br>";
                        }else{
                            $h .= FisTranslate($i) . ":" . FisTranslate($item) . "<br>";
                        }
                    }
                    break;
                }
                case 101 :{
                    $h.="Ordnungsrad=".$value['result']." ";
                    break;
                }
                case 102 :{
                    $h.="Werkzeug:".FisTranslate($value['tool']);
                    break;
                }

                case 104 :{
                    $h.=FisTranslate($value['parameter'])."<br>ALT:".FisTranslate($value['old_value'])."<br>NEU:".FisTranslate($value['new_value'])."";
                    break;
                }
                case 103 :{
                    $h.=substr(FisTranslate($value['idea']),0,100);
                    break;
                }

                case 105 :{
                    $h.="Werkzeug:".FisTranslate($value['tool'])."<br>Gewicht=".FisTranslate($value['weight'])." kg";
                    break;
                }

                default: {
                    substr(FisTranslate($this->data['beschreibung']),0,80);
                    break;
                }
            }

        }else{
            $h=substr(FisTranslate($this->data['beschreibung']),0,80);
        }
        return $h;
    }

    function GetResult(){
        $h="";
        if (is_array(json_decode($this->data['beschreibung'],true))) {
            if ($this->data['id_fhmea']==101) {
                $value = json_decode($this->data['beschreibung'], true);
                $h .=GetHtmlProgressbar($this->GetType(),$value['result']);
            }
            if ($this->data['id_fhmea']==102) {
                $value = json_decode($this->data['beschreibung'], true);
                $h .= GetHtmlProgressbar(str_replace(",",".",$value['tool']),$value['weight'] );
            }


        }
        return $h;
    }

    function GetEventUsername(){
        $u=new FisUser($this->data['user']);
        if (is_array(json_decode($this->data['beschreibung'],true))) {
            $value = json_decode($this->data['beschreibung'], true);
            if ($value['hide_user']=="on") {
                $h="ausgeblendet";
            }else{
                $h=$u->GetUsername();
            }
        }else{
        $h=$u->GetUsername();
        }
        return $h;
    }

    function ShowEvent($allowfunction=false){


        $h="<div class=\"row\">
                <div class=\"col-lg-12\">
                    <b>".$this->GetType()."</b><br>";
         $h.=$this->GetResult();

        $h.=$this->GetName()."<br><hr>
                     Datum: ".$this->GetDate()." bearbeitet: ".$this->GetEventUsername()."
                    <br>
                     Status: ".$this->GetState($allowfunction)."<br>
                    <hr>
                 </div>
            </div>";
        return $h;
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
            if ($this->limit <=5){
                $this->limit=100;
            }
        }else{
            $this->limit=100;
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


    function ShowHtmlAllAsTable($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->LoadAllData();

        $h=asModal(1000,"<div id='preview'></div>","",60,"Vorschau");
        $h.= "<h4 class=\"header-title m-t-0 m-b-30\">Ereignisliste Suchbegriff:(".$this->search.") </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            
                    
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
                            lengthMenu: [[50,10, 25, 50, -1], [50,10, 25, 50, \"All\"]]
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

           function ShowPreview(pic){
                $('#preview').html('<img src=\"'+pic+'\" width=\"100%\">');
             };
            ";

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>Ereignis</th>
                            <th>Datum</th>
                            <th>Beschreibung</th>
                            <th>Equipment</th>
                            
                          
                            <th>Status und Aktion</th>
                            <th>erstellt von</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";

            foreach ($this->dataall as $d => $i) {
                if ($i['id_fhm']>0) {
                    $fhm= new FisEquipment($i['id_fhm']);
                    $e= new FisEVent($i['id_fhme']);

                    $link = "<a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=31&equip=" . $i['id_fhm'] . "&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'>Equipment</a>";



                    $h .= "<tr>
                        <td>" . $e->GetType() . "</td>
                        <td>" . $e->GetDate() . "</td>
                        <td>" . $e->GetDesc() . "</td>
                        <td>" . $fhm->GetEqipmentName() . "</td>
                        
                        <td>" . $e->GetState(true,0). "</td>
                        <td>" . $e->GetEventUsername() . "</td>

                       ";
                    $h .= "  <td>" . "$link" . "</td>";
                    $h .= "</tr>";

                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Ereignisdaten zur Auswahl gefunden.";
        }
        return $h;
    }
}