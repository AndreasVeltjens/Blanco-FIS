<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.10.18
 * Time: 20:39
 */
class FisCustomer
{
    public $bks=6101;

    public $idk=0;
    public $data=array();
    public $debitorenid=0;

    public $suchbegriff = "";

    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $search="";
    public $limit=100;

    function __construct($i=0)
    {
        $this->idk=$i;
        if ($i>0){
            $this->LoadData();
        }
        return $this;
    }
    function __toString(){
        return $this->idk;
    }
    function LoadData(){

        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM kundendaten WHERE kundendaten.idk = '".$this->idk."'  LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;

        }else{
            $this->data=array();
            AddSessionMessage("warning","Kundendaten <code> idk $this->idk </code> hat keine Datensätze.","Datenbankfehler");
        }
        return $this;
    }

    function LoadDataByDebitorenNumber(){
        if ($this->debitorenid>0) {
            include("./connection.php");
            $this->data = array();
            $sql = "SELECT * FROM kundendaten WHERE kundendaten.kundenummer = '" . $this->debitorenid . "'  LIMIT 0,1";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                $this->data = $row_rst;

            } else {
                $this->data = array();
                AddSessionMessage("warning", "Kundendaten <code> Debitoren $this->debitorenid </code> hat keine Datensätze.", "Datenbankfehler");
            }
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
                $s = "";
                if (strlen($this->suchbegriff) > 0) {
                    $s = " AND kundendaten.suchbegriff like '%" . $this->suchbegriff . "%' ";
                }

                $sql = "SELECT * FROM kundendaten 
                        WHERE (kundendaten.firma like '%" . $this->search . "%'
                         OR kundendaten.kundenummer like '%" . $this->search . "%'
                         OR kundendaten.kdnummer2 like '%" . $this->search . "%'
                         OR kundendaten.strasse like '%" . $this->search . "%'
                         OR kundendaten.plz like '%" . $this->search . "%'
                         OR kundendaten.ort like '%" . $this->search . "%'
                         OR kundendaten.ansprechpartner like '%" . $this->search . "%'
                         )
                         $s 
                         ORDER BY kundendaten.firma DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Kundendaten  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
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

    function SetDebitorenID($s=0){
        $this->debitorenid=$s;
        if ($s>0){
            $this->LoadDataByDebitorenNumber();
        }
        return $this;
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

    function GetCustomer(){
        return utf8_decode($this->data['firma']);
    }
    function GetStreet(){
        return utf8_decode($this->data['strasse']);
    }
    function GetPLZ(){
        return utf8_decode($this->data['plz']);
    }
    function GetCity(){
        return utf8_decode($this->data['ort']);
    }
    function GetCountry(){
        return utf8_decode($this->data['land']);
    }

    function Add($s){

        if ($s==0){
            AddSessionMessage("error", "Kundendaten  <code> IDK" . $s. "</code> wurde nicht angelegt.", "Kundennummer fehlt.");

        }else{
            include ("./connection.php");
            $sql="SELECT kundendaten.idk FROM kundendaten WHERE kundendaten.kundenummer = '".$s."' LIMIT 0,1";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)==0){
                if ($_REQUEST['service'] == "on") {
                    $_REQUEST['service'] = 1;
                } else {
                    $_REQUEST['service'] = 0;
                }
                if ($_REQUEST['oem'] == "on") {
                    $_REQUEST['oem'] = 1;
                } else {
                    $_REQUEST['oem'] = 0;
                }
                //rueckid	sendungsid	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk

                $updateSQL = sprintf("INSERT INTO  kundendaten (kundenummer,kdnummer2,firma, strasse, plz,ort,land,ansprechpartner,telefon,faxnummer,
                    email,bemerkung,intern,service, oem, suchbegriff, bks) 
                                            VALUES (%s, %s, %s, %s, %s, %s,%s, %s,%s, %s, %s, %s,  %s, %s, %s, %s,%s)",
                    GetSQLValueString($_REQUEST['kundenummer'], "text"),
                    GetSQLValueString($_REQUEST['kdnummer2'], "text"),
                    GetSQLValueString($_REQUEST['firma'], "text"),
                    GetSQLValueString($_REQUEST['strasse'], "text"),
                    GetSQLValueString($_REQUEST['plz'], "text"),
                    GetSQLValueString($_REQUEST['ort'], "text"),
                    GetSQLValueString($_REQUEST['land'], "text"),
                    GetSQLValueString($_REQUEST['ansprechpartner'], "text"),
                    GetSQLValueString($_REQUEST['telefon'], "text"),
                    GetSQLValueString($_REQUEST['faxnummer'], "text"),
                    GetSQLValueString($_REQUEST['email'], "text"),
                    GetSQLValueString($_REQUEST['bemerkung'], "text"),
                    GetSQLValueString($_REQUEST['intern'], "text"),
                    GetSQLValueString($_REQUEST['service'], "text"),
                    GetSQLValueString($_REQUEST['oem'], "text"),
                    GetSQLValueString($_REQUEST['suchbegriff'], "text"),
                    GetSQLValueString($this->bks, "int")

                );

                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {
                    $_SESSION['zzz'] = $updateSQL;
                    AddSessionMessage("error","Kundendaten  <code> ID" . $s . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
                }else{

                    $this->idk=mysqli_insert_id($mysqli);

                    $txt="Kundendaten von  <code> ID" . $this->idk . "</code> wurde gespeichert.";
                    AddSessionMessage("success", $txt, "Benachrichtigung");
                    $w=new FisWorkflow($txt,"idk",$this->idk);
                    $w->Add();
                }


            }else{
                AddSessionMessage("warning", "Kundendaten  <code> ID" . $s. "</code> wurde nicht angelegt. Nummer existiert bereits ", "Kundendatenfehler");
            }
        }
    }
    function Save(){


        $s=$_REQUEST['kundenummer'];
        $s=intval($s)+0;

        if (isset($_POST['customer_save'])){ $this->idk=$_SESSION['idk'];}
        //rueckid	sendungsid	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk
        if ($this->idk >0){
            include ("./connection.php");
            if ($_REQUEST['service'] == "on") {
                $_REQUEST['service'] = 1;
            } else {
                $_REQUEST['service'] = 0;
            }
            if ($_REQUEST['oem'] == "on") {
                $_REQUEST['oem'] = 1;
            } else {
                $_REQUEST['oem'] = 0;
            }
            $updateSQL=sprintf("
                UPDATE kundendaten SET kundenummer=%s,kdnummer2=%s,firma=%s, strasse=%s, plz=%s,ort=%s,land=%s,ansprechpartner=%s,telefon=%s,faxnummer=%s,
                email=%s,bemerkung=%s,service=%s, oem=%s, intern=%s, suchbegriff=%s
                WHERE idk='$this->idk' ",
                GetSQLValueString($_REQUEST['kundenummer'], "text"),
                GetSQLValueString($_REQUEST['kdnummer2'], "text"),
                GetSQLValueString($_REQUEST['firma'], "text"),
                GetSQLValueString($_REQUEST['strasse'], "text"),
                GetSQLValueString($_REQUEST['plz'], "text"),
                GetSQLValueString($_REQUEST['ort'], "text"),
                GetSQLValueString($_REQUEST['land'], "text"),
                GetSQLValueString($_REQUEST['ansprechpartner'], "text"),
                GetSQLValueString($_REQUEST['telefon'], "text"),
                GetSQLValueString($_REQUEST['faxnummer'], "text"),
                GetSQLValueString($_REQUEST['email'], "text"),
                GetSQLValueString($_REQUEST['bemerkung'], "text"),
                GetSQLValueString($_REQUEST['service'], "int"),
                GetSQLValueString($_REQUEST['oem'], "int"),
                GetSQLValueString($_REQUEST['intern'], "text"),
                GetSQLValueString($_REQUEST['suchbegriff'], "text")

            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error","Änderung von Kundendaten von  <code> ID" . $this->idk . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

            }else{
                $_SESSION['sql'] = $updateSQL;
                $txt="Kundendaten von  <code> ID" . $this->idk . "</code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Benachrichtigung");
                $w=new FisWorkflow($txt,"idk",$this->idk);
                $w->Add();

                $d=new FisDocument("Dokumente zum Kunden","idk",$this->idk);
                $d->AddNewDocument();
            }
        }else{
            $this->Add($s); //check Wp in DB ?
        }

    }

    function GetReturnDeviceArray(){
        if (count($this->data)>0){
            $r=$this->data;
        }else{
            $r=array();
        }

        return $r;
    }

    function GetCustomerNumber(){
        return $this->data['kundenummer'];
    }
    function GetCustomerNumber2(){
        return $this->data['kdnummer2'];
    }
    function GetCustomerName(){
        return $this->data['firma'];
    }

    function ShowHtmlAllCustomers($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();
        if (strlen($this->suchbegriff) > 0) {
            $s = "Kundengruppe:" . $this->suchbegriff;
        } else {
            $s = "";
        }
        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Kundendaten Suchbegriff:(" . $this->GetSearchString() . ") $s </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            order: [[ 1, \"desc\" ]],
                    
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
                            lengthMenu: [[-1, 10,20, 25, 50, -1], [\"Alle\",10,20, 25, 50, \"Alle\"]]
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
                             <th>Kunden-Nr.</th>
                             <th>Referenz</th>
                             <th>Kunde</th>
                             <th>Anschrift</th>
                             <th>PLZ</th>
                             <th>Ort</th>
                             <th>Land</th>
                             <th>Ansprechpartner</th>
                             <th>Tel.</th>
                             <th>Fax</th>
                             <th>E-mail</th>
                             <th>Kundengruppe</th>
                             <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            foreach ($this->dataall as $d => $i) {
                if ($i['idk']>0) {
                    $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&idk=" . $i['idk'] . "&materialgroup=&material=&version=&fid=";
                    $btn = "<a class='btn btn-custom btn-sm' href=\"" . $link . "&action=28\" onclick='refresh_site()'> Bearbeiten </a>";
                    $btn1 = "<a class='btn btn-custom btn-sm' href=\"" . $link . "&action=22\" onclick='refresh_site()'> neue Rückholung </a>";
                    $btn2 = "<a class='btn btn-custom btn-sm' href=\"" . $link . "&action=24&fm_idk=" . $i['idk'] . "\" onclick='refresh_site()'> neue Fehlermeldung </a>";
                    $h .= "<tr>
                        <td><a href='$link&action=28'>" . utf8_decode($i['kundenummer']) . "</a></td>
                        <td><a href='$link&action=28'>" . utf8_decode($i['kdnummer2']) . "</a></td>
                        <td><a href='$link&action=28'>" . utf8_decode($i['firma']) . "</a></td>
                        <td>" . utf8_decode($i['strasse']) . "</td>
                        <td>" . utf8_decode($i['plz']) . "</td>
                        <td>" . utf8_decode($i['ort']) . "</td>
                        <td>" . utf8_decode($i['land']) . "</td>
                        <td>" . utf8_decode($i['ansprechpartner']) . "</td>
                        <td>" . utf8_decode($i['telefon']) . "</td>
                        <td>" . utf8_decode($i['faxnummer']) . "</td>
                        <td>" . utf8_decode($i['email']) . "</td>
                        <td>" . utf8_decode($i['suchbegriff']) . "</td>";
                    $h .= "  <td>" . "$btn.$btn1.$btn2" . "</td>";
                    $h .= "</tr>";
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
        if ($this->idk==0) {
            $this->data['kundenummer']="NEU";
            $this->data['kdnummer2']="Neu";
        }


        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Kundendaten bearbeiten </b></h4>";
        $h .= "<div class='col-lg-6 card-box'><div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr><td>";
        if ($this->idk>0){
            $h.=asTextField("ID","idk",$this->data['idk']);
            $h.="</td></tr><tr><td>";
        }


        $h.=asTextField("SAP-Kundenummer","kdnummer2",utf8_decode($this->data['kdnummer2']));
        $h.="</td></tr><tr><td>";
        $h.=asTextField("interne Kundennummer","kundenummer",utf8_decode($this->data['kundenummer']));
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Firma","firma",utf8_decode($this->data['firma']),3);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Straße","strasse",utf8_decode($this->data['strasse']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("PLZ","plz",utf8_decode($this->data['plz']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ort","ort",utf8_decode($this->data['ort']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Land","land",utf8_decode($this->data['land']),7,"");
        $h .= "</td></tr><tr><td>";
        $h .= asTextField("Kundengruppe", "suchbegriff", utf8_decode($this->data['suchbegriff']), 7, "");
        $h.="</td></tr><tr><td>";
        $h .= "</td></tr></tbody></table></div>";
        $h .= "</div>";
        $h .= "<div class='col-lg-6 card-box'><div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr><td>";
        $h.=asTextField("Ansprechpartner","ansprechpartner",utf8_decode($this->data['ansprechpartner']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Telefon","telefon",utf8_decode($this->data['telefon']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Fax","faxnummer",utf8_decode($this->data['faxnummer']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("E-Mail","email",utf8_decode($this->data['email']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Interne Bemerkungen","bemerkung",utf8_decode($this->data['bemerkung']),4,12,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Retourenabwicklung","intern",utf8_decode($this->data['intern']),4,12,"");
        $h .= "</td></tr><tr><td>";
        $h .= asSwitchery("Ansprechpartner auch als Servicepartner verwenden", "service", $this->data['service'], "00b19d", 3, "");
        $h .= "</td></tr><tr><td>";
        $h .= asSwitchery("Ansprechpartner mit seinem Logo verwenden", "oem", $this->data['oem'], "00b19d", 3, "");


        $h.="</td></tr></tbody></table></div>";
        $h .= "</div>";

        return $h;
    }
}