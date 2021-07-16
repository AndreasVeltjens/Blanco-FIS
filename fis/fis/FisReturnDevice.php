<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.10.18
 * Time: 20:39
 */
class FisReturnDevice
{
    public $bks=6101;

    public $rueckid=0;
    public $data=array();
    public $sendungsid=0;
    public $idk=0;

    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $search="";
    public $limit = 50;

    function __construct($i=0)
    {
        $this->rueckid=$i;
        if ($i>0){
            $this->LoadData();
        }
        return $this;
    }

    function __toString(){
        return $this->rueckid;
    }
    function LoadData(){

        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM rueckholung 
              WHERE rueckholung.rueckid = '".$this->rueckid."' 
              LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;

        }else{
            $this->data=array();
            AddSessionMessage("warning","Keine Rückholungen <code> RUECKID $this->rueckid </code> hat keine Datensätze.","Datenbankfehler");
        }
        return $this;
    }

    function LoadAllData()
    {

        if ($this->limit > 0 ) {
            if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
                $this->dataall = array();
                return $this;
            } else {
                include("./connection.php");


                if ($this->idk >0){ $s=" AND rueckholung.idk='".$this->idk."' ";}
                else{
                    $s="";
                }
                $sql = "SELECT rueckholung.rueckid, rueckholung.sendungsid, rueckholung.datum, rueckholung.idk,rueckholung.sonstiges 
                          FROM rueckholung 
                          WHERE rueckholung.sendungsid like '%" . $this->search . "%'   
                          $s
                          AND rueckholung.lokz=0 
                          ORDER BY rueckholung.datum DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {
                   
                    AddSessionMessage("error", "Rückholungen  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
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

    function LoadDataByDelivery()
    {
        include("./connection.php");

        // sendungsid: 1789 0185 0464
        $idk="";
        $sql = "SELECT rueckholung.idk
                  FROM rueckholung 
                  WHERE rueckholung.sendungsid like '%" . substr(trim($this->search), 0, 11) . "%' 
                  AND rueckholung.lokz=0 
                  ORDER BY rueckholung.datum DESC LIMIT 0,1";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {

            AddSessionMessage("error", "Rückholungen  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $idk = $row_rst['idk'];
            } while ($row_rst = $rst->fetch_assoc());
        }
        $mysqli->close();
         return $idk;
    }

    function SetCustomer($c=0){
        $this->idk=$c;
        return $this;
    }
    function SetSearchString($s=""){
        $this->search=$s;
        return $this;
    }
    function SetLimit($l=100){
        if (intval($l)<101) {
            $this->limit = intval($l);
            if ($this->limit == 0) {
                $this->limit = 50;
            }
        }else{
            AddSessionMessage("warning","Das Limit ist auf 100 beschränkt.","Warnung");

        }
        return $this;
    }


    function SetNoFreeValueAllowed($s=0){
        $this->no_free_search_value_allowed=$s;
        return $this;
    }


    function Add($s){

        if ($s==0){
            AddSessionMessage("error", "Rückholung  <code> SID" . $s. "</code> wurde nicht angelegt.", "Sendungsnummer fehlt.");

        }else{
            include ("./connection.php");
            $sql="SELECT rueckholung.rueckid FROM rueckholung WHERE rueckholung.sendungsid = '".$s."' LIMIT 0,1";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)==0){

                //rueckid	sendungsid	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk

                $updateSQL = sprintf("INSERT INTO  rueckholung (sendungsid, sendungstext, datum,idk,`user`,packstuecke,gewicht,sonstiges,werk) 
                                            VALUES (%s, %s, %s, %s,%s, %s, %s, %s, %s)",
                    GetSQLValueString($s, "int"),
                    GetSQLValueString($_REQUEST['sendungstext'], "text"),
                    GetSQLValueString($_REQUEST['datum'], "text"),
                    GetSQLValueString($_REQUEST['idk0'], "int"),
                    GetSQLValueString($_SESSION['active_user'], "int"),
                    GetSQLValueString($_REQUEST['packstuecke'], "text"),
                    GetSQLValueString($_REQUEST['gewicht'], "text"),
                    GetSQLValueString($_REQUEST['sonstiges'], "text"),
                    GetSQLValueString($this->bks, "int")

                );


                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {
                    
                    AddSessionMessage("error","Rückholung  <code> SID" . $s . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
                }else{
                    
                    $txt="Rückholung  <code> SID" . $s . "</code> wurde angelegt.";
                    AddSessionMessage("success", $txt, "Rückholung gespeichert");
                    $this->rueckid=mysqli_insert_id($mysqli);
                    $w=new FisWorkflow($txt,"rueckid",$this->rueckid);
                    $w->Add();
                    $w->type="idk";
                    $w->id=$_REQUEST['idk'];
                    $w->Add();
                }

            }else{
                AddSessionMessage("error", "Rückholung  <code> SID" . $s. "</code> wurde nicht angelegt, weil die Sendungsnummer bereits verwendet wurde.", "Sendungsnummer fehlt.");

            }
        }
    }
    function Save(){


        $s=substr($_REQUEST['sendungstext'],0,40);
        $s=preg_replace("/[^0-9]/","",$s)+0;

        if (isset($_REQUEST['re_save'])){$this->rueckid=$_SESSION['rueckid'];}
        //rueckid	sendungsid	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk
        if ($this->rueckid >0){
            include ("./connection.php");
            $updateSQL=sprintf("
                UPDATE rueckholung SET sendungsid=%s,	sendungstext=%s, datum=%s,	idk=%s,	lokz=%s,packstuecke=%s,	gewicht=%s,	sonstiges=%s 
                WHERE rueckid='$this->rueckid' ",
                GetSQLValueString($_REQUEST['sendungsid'], "int"),
                GetSQLValueString($_REQUEST['sendungstext'], "text"),
                "now()",
                GetSQLValueString($_REQUEST['idk'.$this->rueckid], "int"),
                GetSQLValueString($_REQUEST['lokz'], "int"),
                GetSQLValueString($_REQUEST['packstuecke'], "text"),
                GetSQLValueString($_REQUEST['gewicht'], "text"),
                GetSQLValueString($_REQUEST['sonstiges'], "text")
                );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                
                AddSessionMessage("error","Änderung von Rückholung von  <code> SID " . $this->rueckid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

            }else{
                
                $txt="Rückholung  <code> SID " . $this->rueckid . " </code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Rückholung gespeichert");
                $w=new FisWorkflow($txt,"rueckid",$this->rueckid);
                $w->Add();
            }
        }else{
            $this->Add($s); //check Wp in DB ?
        }

    }

    function GetLimit(){
        return $this->limit;
    }
    function GetReturnDeviceArray(){
        if (count($this->data)>0){
            $r=$this->data;
        }else{
            $r=array();
        }

        return $r;
    }

    function GetDeliveryNumber(){
        return $this->data['sendungsid'];
    }
    function GetCustomerNumber(){
        return $this->data['customer'];
    }
    function GetCustomerName(){
        return $this->data['customer'];
    }


    function GetCustomerNumberByDeliveryNumber($search=0){
        $this->SetSearchString($search);
        $idk=$this->LoadDataByDelivery();
        return $idk;
    }


    function ShowHtmlAllReturnDevices($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->SetSearchString($search);

        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Rückholungen Suchbegriff:(".$this->search.") </h4>";

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
                            lengthMenu: [[-1,20,10, 25, 50], [\"All\", 20,10, 25, 50]]
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

            $h.="<table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                             <th width='10%'>Datum</th>
                             <th width='20%'>Sendung</th>
                             
                             <th width='50%'>Retourenstatusübersichtsverlauf</th>
                             <th width='15%'>Kunde</th>
                             <th width='5%'>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($this->dataall as $d => $i) {
                if ($i['rueckid']>0) {
                    $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=29&rueckid=" . $i['rueckid'] . "&idk=" . $i['idk'] . "&materialgroup=&material=&version=&fid=";
                    $btn = "<a class=\"btn btn-custom btn-sm\" href=\"$link\" onclick='refresh_site()'> Bearbeiten </a>";

                    $h .= "<tr>
                        <td><a href='$link'>" . $i['datum'] . "</a></td>
                        <td><a href='$link'>" . $i['sendungsid'] . " " . $i['sonstiges'] . "</a></td>  ";




                    $h.="<td>";
                    $h .= "<div id='rueckid_layout_$d' width='50%'></div>";
                    $h.="<script>
           
                    function load_rueckid_layout_$d(){   
                        $(\"#rueckid_layout_$d\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
                       const urlParams = new URLSearchParams(window.location.search)
                        \$(\"#rueckid_layout_$d\").load(\"data.php?\"+  urlParams  + \"&datatype=fmid&sendungsid=" . $i['sendungsid'] . "&datum=" . $i['datum'] . "\" , function(responseTxt, statusTxt, xhr){
                       
                       });
                    
                     }
                    </script>
                    
                    ";
                    $_SESSION['endscripts'].="
                    load_rueckid_layout_$d();
                    ";

                    $h.="</td>";
                    $h .= "<td>" . GetCustomerName("text", $i['idk']) . "</td>";
                    $h .= "<td>" . $btn . "</td>";
                    $h.="</tr>";


                unset($fm);
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Rückholungen gefunden.";
        }
        return $h;
    }


    function EditPropertyAsTable(){
        $h="";
        if ($this->rueckid==0) {
            $this->data['sonstiges']="nichts";
            $this->data['packstuecke']="1 Paket";
            $this->data['datum']=date("Y-m-d",time());
            $this->data['gewicht']="bis 30 kg";
            $this->data['idk']=$_SESSION['idk'];
        }
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Rückholung bearbeiten </b></h4>";
        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr><td>";
        if ($this->rueckid>0){
        $h.=asTextField("Sendungs-ID","sendungsid",$this->data['sendungsid']);
            $h.="</td></tr><tr><td>";
        }
        $h.= asSelect2Box("Kunde", "idk".$this->rueckid, GetCustomerName("select2", $this->data['idk']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Hier Text rein kopieren","sendungstext",$this->data['sendungstext']);
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Sonstiges","sonstiges",$this->data['sonstiges'],2,7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Datum","datum",$this->data['datum']);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Packstücke","packstuecke",$this->data['packstuecke']);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Gewicht","gewicht",$this->data['gewicht']);
        $h.="</td></tr></tbody></table></div>";



        return $h;
    }
}