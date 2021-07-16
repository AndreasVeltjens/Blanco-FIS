<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.10.18
 * Time: 20:39
 */
class FisWarehouse
{
    public $bks=6101;

    public $we_id=0;
    public $wed_id=0;

    public $data=array();
    public $posdata=array();
    public $countdata=array();

    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $search="";
    public $limit=100;
    public $lokz=0;

    function __construct($i=0)
    {
        $this->we_id=$i;
        if ($i>0){
            $this->LoadData();
            $this->LoadPosData();
        }
        return $this;
    }
    function __toString(){
        return $this->we_id;
    }

    function LoadData(){
        //we_id	we_nummer	we_lieferant	we_stueckzahl	we_bezeichnung	we_pruefumfang	we_user	we_datum	we_frei	we_notes
        //we_vorlage	we_lokz Löschkennzeichen	we_pruefer
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fiswareneingang WHERE fiswareneingang.we_id = '".$this->we_id."'  LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;

        }else{
            $this->data=array();
            AddSessionMessage("warning","Kundendaten <code> idk $this->we_id </code> hat keine Datensätze.","Datenbankfehler");
        }
        return $this;
    }

    function LoadPosData()
    { $this->posdata = array();
        // wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status
        include("./connection.php");
        $sql = "SELECT * FROM fiswareneingangsdaten 
                WHERE fiswareneingangsdaten.we_id='".$this->we_id."'
                AND fiswareneingangsdaten.wed_lokz=0 
                ORDER BY fiswareneingangsdaten.wed_bezeichnung ASC, fiswareneingangsdaten.wed_grenze ASC LIMIT 0,100";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {

            AddSessionMessage("error", "Wareneingangsdaten  <code> " . $this->we_id. "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->posdata[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->posdata = array();
        }
        $mysqli->close();
          
    }

    function LoadPosCount()
    { $this->countdata = array();
        // wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status
        include("./connection.php");
        $sql = "SELECT * FROM fiswareneingangsdaten 
                WHERE fiswareneingangsdaten.we_id='".$this->we_id."'
                AND fiswareneingangsdaten.wed_lokz=0 
                AND fiswareneingangsdaten.wed_bezeichnung='Menge der Lieferung'
                ORDER BY fiswareneingangsdaten.wed_bezeichnung ASC, fiswareneingangsdaten.wed_grenze ASC LIMIT 0,10";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {

            AddSessionMessage("error", "Menge aus Lieferung  <code> " . $this->we_id. "</code> konnte nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->countdata[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->countdata = array();
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
                include("./connection.php");

                if ($_SESSION['fid_search_material']>0){
                    $s=" AND fiswareneingang.we_nummer like '%".GetArtikelName("number",$_SESSION['fid_search_material'],"")."%' ";
                }else{
                    $s="";
                }

                $sql = "SELECT * FROM fiswareneingang 
                        WHERE ( fiswareneingang.we_lieferant like '%" . utf8_decode($this->search) . "%'
                         OR fiswareneingang.we_bezeichnung like '%" . utf8_decode($this->search) . "%'
                          OR fiswareneingang.we_nummer like '%" . utf8_decode($this->search) . "%'
                           OR fiswareneingang.we_notes like '%" . utf8_decode($this->search) . "%'
                         ) AND fiswareneingang.werk='".$this->bks."'
                         AND fiswareneingang.we_lokz='".$this->lokz."'
                         $s
                         ORDER BY fiswareneingang.we_datum DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Wareneingang  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
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


    function ProvePosData($soll="soll",$tol="tolerance", $ist="ist"){
        $s=0;


        if (strlen($ist)>0){
            $soll=str_replace(",",".",$soll);
            $tol=str_replace(",",".",$tol);
            $ist=str_replace(",",".",$ist);

            if (doubleval($soll)>0 AND doubleval($tol)>0){

                $soll2=doubleval($soll)+(doubleval($tol) / 2);
                $soll1=doubleval($soll)-(doubleval($tol) / 2);

                if ($soll1 <= doubleval($ist) && doubleval($ist) <= $soll2){
                    $s=1;$info="Innerhalb der Toleranz";
                }else{
                    $s=2;$info="Außerhalb der Toleranz";
                }
            }else {
                if ($soll == $ist) {
                    $s = 1;
                    $info = "Ist = Sollwert";
                } else {
                    $s = 2;
                    $info = "Ist <> Sollwert";
                }
            }
        }else{
            $s=0;
        }
        return array($s,$info);
    }

    function ProveStatus(){
        //wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status
        $s=0;
        $x=0;

        if (count($this->posdata)>0){
            foreach ($this->posdata as $i=>$item){
                $y=$this->ProvePosData($item['wed_grenze'],$item['wed_toleranz'],$item['wed_wert'] );
                $x=$x+$y[0];
            }
            if (($x-($i+1))>0 or ($x-($i+1))<0){
                $s=2;
            }else{
                $s=1;
            }

        }else{
            $s=0;
        }
            return $s;
    }

    function GetStatus($s=""){
        $r=99;
        if ($s==""){
            $s=$this->ProveStatus();
        }

        if ($s==0){$r="<span class='label label-inverse'>Neuanlage</span>";}
        if ($s==1){$r="<span class='label label-success'>freigegeben</span>";}
        if ($s==2){$r="<span class='label label-danger'>gesperrt</span>";}
        // if ($s==3){$s="<span class='label label-warning'>Sonderfreigabe</span>";}
        return $r;
    }

    function GetPrinterActive(){
        $b=true;
        return $b;
    }

    function GetPrinterPageCount(){
        $n=intval($this->data['we_packstueck']);

        if (strlen($this->data['we_eti_count'])>0 && intval($this->data['we_eti_count'])>1){
            $n= intval($this->data['we_eti_count']);
        }

        if ($n<1){$n=1;}
        return $n;
    }

    function AllowPrintPackageCount(){
        if (strlen($this->data['we_eti_count'])>0 && intval($this->data['we_eti_count'])>1){
            return false;
        }else{
            return true;
        }
    }

    function PrintWarehouseEitkett(){
        if ($this->GetPrinterActive() ) {
            include("./print.warehouse.php");
        }
    }



    function GetPictureLink(){
        $mat=GetArtikelName("data",$this->data['we_nummer']);
        $m=new FisMaterial($mat);
        return $m->GetPictureLink();
    }
    function GetPicture(){
        $mat=GetArtikelName("data",$this->data['we_nummer']);
        $m=new FisMaterial($mat);
        return $m->GetPicture();
    }

    function PrintStatus(){
        $r=99;
        if ($s==""){
            $s=$this->ProveStatus();
        }

        if ($s==0){$r="in Prüfung";}
        if ($s==1){$r="  QS - frei";}
        if ($s==2){$r=" gesperrt";}
        return $r;
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


    function Add(){
        include ("./connection.php");

        $mat=GetArtikelName("data",intval($_REQUEST['we_nummer']));
        $m=new FisMaterial($mat);
        $_REQUEST['version']=$m->data['version'];

        $updateSQL = sprintf("INSERT INTO  fiswareneingang (we_lieferant,we_bezeichnung,we_nummer,version, we_datum, werk) 
                                    VALUES (%s, %s, %s, %s, %s, %s ) ",
            GetSQLValueString($_REQUEST['we_lieferant'], "text"),
            GetSQLValueString($_REQUEST['we_bezeichnung'], "text"),
            GetSQLValueString($_REQUEST['we_nummer'], "text"),
            GetSQLValueString($_REQUEST['version'], "text"),
            GetSQLValueString(date("Y-m-d",time()), "text"),
            GetSQLValueString($this->bks, "int")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Wareneingang  <code> ID" . $_REQUEST['we_nummer']. "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{

            AddSessionMessage("success", "Wareneingang  <code> ID" . $_REQUEST['we_nummer']. " </code> wurde neu angelegt.", "Wareneingang neu angelegt");
            $this->we_id=mysqli_insert_id($mysqli);
            $_GET['weid']=$this->we_id;
            $_SESSION['weid']=$this->we_id;

            $txt="Wareneingang von  <code> ID " . $this->we_id . "</code>  Material: <code> ($mat) - " . $_REQUEST['we_nummer']. " </code> Lieferant: <code> " . $_REQUEST['we_lieferant']. " </code> Version: <code> " . $_REQUEST['version']. " </code>  wurde gespeichert.";

            AddSessionMessage("success", $txt, "Benachrichtigung");
            $w=new FisWorkflow($txt,"weid",$this->we_id);
            $w->Add();
        }

    }

    function AddCounterPos(){
        include ("./connection.php");
        $c=$_REQUEST['we_stueckzahl'];

        $updateSQL="DELETE FROM `fiswareneingangsdaten` 
                    WHERE `fiswareneingangsdaten`.wed_bezeichnung='Menge der Lieferung'
                    AND `fiswareneingangsdaten`.`we_id`='$this->we_id' ";

        $mysqli->query($updateSQL);

        $updateSQL = sprintf("INSERT INTO  fiswareneingangsdaten (wed_bezeichnung,wed_grenze, wed_wert, we_id) 
                                    VALUES (%s, %s, %s, %s) ",
            GetSQLValueString(utf8_decode("Menge der Lieferung"), "text"),
            GetSQLValueString(utf8_decode($c), "text"),
            GetSQLValueString(utf8_decode($c), "text"),
            GetSQLValueString($this->we_id, "int")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Wareneingangsmenge  <code> ID" . $_REQUEST['we_nummer']. "</code> konnte nicht gespeichert werden.","Fehlermeldung");
        }else{

            $this->wed_id=mysqli_insert_id($mysqli);
            $txt=($i+1)." neue Mengenposition für  <code> ID " . $this->we_id . "</code>  wurde eingefügt.";
            AddSessionMessage("success", $txt, "Benachrichtigung");
            $w=new FisWorkflow($txt,"weid",$this->we_id);
            $w->Add();
        }
    }

    function AddPos(){
        include ("./connection.php");
        //wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status
        $updateSQL = sprintf("INSERT INTO  fiswareneingangsdaten (wed_bezeichnung,wed_grenze,wed_toleranz,wed_wert,wed_notes, we_id) 
                                    VALUES (%s, %s, %s, %s, %s ,%s) ",
            GetSQLValueString(utf8_decode($_REQUEST['wed_bezeichnung']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['wed_grenze']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['wed_toleranz']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['wed_wert']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['wed_notes']), "text"),
            GetSQLValueString($this->we_id, "int")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Wareneingang  <code> ID" . $_REQUEST['we_nummer']. "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{

            $this->wed_id=mysqli_insert_id($mysqli);

            $txt="Neue Position für  <code> ID " . $this->wed_id . "</code>  wurde eingefügt.";
            AddSessionMessage("success", $txt, "Benachrichtigung");
            $w=new FisWorkflow($txt,"weid",$this->we_id);
            $w->Add();
        }

    }

    function CopyPos(){
        if (count($this->posdata)){
            include("./connection.php");
            foreach ($this->posdata as $i=>$item){
                $updateSQL = sprintf("INSERT INTO  fiswareneingangsdaten (wed_bezeichnung,wed_grenze,wed_toleranz,wed_wert,wed_notes, we_id) 
                                    VALUES (%s, %s, %s, %s, %s ,%s) ",
                    GetSQLValueString(utf8_decode($item['wed_bezeichnung']), "text"),
                    GetSQLValueString(utf8_decode($item['wed_grenze']), "text"),
                    GetSQLValueString(utf8_decode($item['wed_toleranz']), "text"),
                    GetSQLValueString("", "text"),
                    GetSQLValueString(utf8_decode($item['wed_notes']), "text"),
                    GetSQLValueString($this->we_id, "int")
                );

                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {

                    AddSessionMessage("error","Wareneingang  <code> ID" . $_REQUEST['we_nummer']. "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
                }else{

                    $this->wed_id=mysqli_insert_id($mysqli);

                    $txt=($i+1)." neue Positionen für  <code> ID " . $this->we_id . "</code>  wurde(n) eingefügt.";
                    AddSessionMessage("success", $txt, "Benachrichtigung");
                    $w=new FisWorkflow($txt,"weid",$this->we_id);
                    $w->Add();
                }


            }
        }else{
            AddSessionMessage("error","Keine Positionen gefunden  <code> ID" . $this->we_id. "</code>","Kopierfehlermeldung");

        }

    }

    function SavePos(){
        // wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status

        if (count($this->posdata)){
            include("./connection.php");
            foreach ($this->posdata as $i=>$item){
                $s=$this->ProvePosData($item['wed_grenze'],$item['wed_toleranz'],$item['wed_wert']);

                $updateSQL = sprintf("
                        UPDATE fiswareneingangsdaten SET  wed_wert=%s, wed_status=%s,wed_lokz=%s
                        WHERE wed_id='".$item['wed_id']."' ",
                    GetSQLValueString(utf8_decode($_REQUEST['wed_wert'.$item['wed_id'] ]), "text"),
                    GetSQLValueString($s[0], "int"),
                    GetSQLValueString(utf8_decode($_REQUEST['wed_lokz'.$item['wed_id'] ]), "int")

                );

                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Änderung vom Wareneingang von  <code> ID" . $this->we_id . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");

                } else {
                    $txt = "Wareneingangspositionsdaten von  <code> ID" . $this->we_id . "</code> wurde gespeichert.";
                    AddSessionMessage("success", $txt, "Benachrichtigung");
                }
            }

            $w = new FisWorkflow($txt, "weid", $this->we_id);
            $w->Add();

        }
        $this->LoadPosCount();
        if (count($this->countdata)==0){
            $this->AddCounterPos();
        }
    }

    function Save(){

        if ($this->data['we_datum']==""){$this->data['we_datum']=date("Y-m-d",time());}
        if (isset($_POST['we_new'])){
            $this->data['we_datum']=date("Y-m-d",time());
            $this->Add();
            $this->CopyPos();
            AddSessionMessage("info", "Wareneingang kopiert  <code> ID" . $this->we_id . "</code> .", "Kopieren fertig.");

        }

        if (isset($_REQUEST['we_new_position'])){
            $this->AddPos();
        }

        if (isset($_REQUEST['we_pos_copy'])){
            $this->CopyPos();
        }

        if (isset($_POST['we_new']) or isset($_POST['we_save']) or isset($_REQUEST['sign_save'])){
            $this->SavePos();
            $this->LoadPosData();
            $s=$this->ProveStatus();
            $_REQUEST['we_frei']=$s[0];

            //we_id	we_nummer	we_lieferant	we_stueckzahl	we_bezeichnung	we_pruefumfang	we_user	we_datum	we_frei	we_notes
            //we_vorlage	we_lokz Löschkennzeichen	we_pruefer
            if ($_REQUEST['we_lokz']=="on"){$_REQUEST['we_lokz']=1;}else{$_REQUEST['we_lokz']=0;}
            if ($this->we_id >0) {
                include("./connection.php");
                $updateSQL = sprintf("
                    UPDATE fiswareneingang SET we_nummer=%s,we_lieferant=%s,we_stueckzahl=%s,we_pruefumfang=%s, we_bezeichnung=%s, 
                    we_packstueck=%s, we_eti_count=%s, version=%s, we_user=%s,we_datum=%s,we_frei=%s,we_notes=%s,
                    we_vorlage=%s,we_lokz=%s,we_pruefer=%s
                    WHERE we_id='$this->we_id' ",
                    GetSQLValueString(utf8_decode($_REQUEST['we_nummer']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_lieferant']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_stueckzahl']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_pruefumfang']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_bezeichnung']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_packstueck']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_eti_count']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['version']), "text"),
                    GetSQLValueString(utf8_decode($_SESSION['active_user']), "int"),
                    GetSQLValueString($this->data['we_datum'], "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_frei']), "int"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_notes']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_vorlage']), "text"),
                    GetSQLValueString(utf8_decode($_REQUEST['we_lokz']), "int"),
                    GetSQLValueString(utf8_decode($_SESSION['active_user']), "int")
                );

                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {
                    $_SESSION['zzz']=$updateSQL;
                    AddSessionMessage("error", "Änderung vom Wareneingang von  <code> ID" . $this->we_id . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");

                } else {
                    if ($_REQUEST['we_stueckzahl']<>$this->data['we_stueckzahl']){
                        $this->AddCounterPos();
                    }

                    $txt = "Wareneingangsdaten von  <code> ID" . $this->we_id . "</code> wurde gespeichert.";
                    AddSessionMessage("success", $txt, "Benachrichtigung");
                    $w = new FisWorkflow($txt, "weid", $this->we_id);
                    $w->Add();

                    $d = new FisDocument("Dokumente zum Wareneingang", "weid", $this->we_id);
                    $d->AddNewDocument();

                    //$s = new FisSignature("Unterschrift zum Wareneingang", "weid", $this->we_id);
                    //$s->AddNewSign();

                }
            }else{
                AddSessionMessage("error", "unbekannter Wareneingang von  <code> ID" . $this->we_id . "</code> konnte nicht durchgeführt werden.", "DB Fehlermeldung");

            }
        }
    }





    function ShowHtmlAllWarehouse($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Wareneingang Suchbegriff:(".$this->GetSearchString().") </h4>";

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
                            lengthMenu: [[20,10, 25, 50, -1], [20,10, 25, 50, \"All\"]]
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
                             <th>Wareneingang</th>
                             <th>Material</th>
                             <th>Bezeichnung</th>
                             <th>Lieferant</th>
                             <th>Menge</th>
                             <th>Prüfumfang</th>
                             <th>Bemerkungen</th>
                             <th>WEID</th>
                             <th>Status</th>
                             <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            foreach ($this->dataall as $d => $i) {
                if ($i['we_id']>0) {
                    $link="index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=38&weid=" . $i['we_id'] . "&materialgroup=&material=&version=&fid=";
                    $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> Bearbeiten </a>";
                    $we=new FisWarehouse($i['we_id']);
                    $h .= "<tr>
                        <td>" . ($i['we_datum']) . "</td>
                        <td><a href='$link' target='_self'> " . ($i['we_nummer']) . "</a></td>
                        <td><a href='$link' target='_self'>" . ($i['we_bezeichnung']) . "</a></td>
                        <td>" . ($i['we_lieferant']) . "</td>
                        <td>" . ($i['we_stueckzahl']) . "</td>
                        <td>" . ($i['we_pruefumfang']) . "</td>
                        <td>" . ($i['we_notes']) . "</td>
                        <td>" . ($i['we_id']) . "</td>
                        <td>" . $this->GetStatus($we->ProveStatus() ) . "</td>";
                    $h .= "  <td>" . "$btn" . "</td>";
                    $h .= "</tr>";
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Wareneingangsprüfungen gefunden.";
        }
        return $h;
    }


    function EditPropertyAsTable($new=false){
        $h="";

        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Wareneingang bearbeiten (".$this->we_id.")  -  ".$this->GetStatus()."</b></h4>";
        $h.="<div class=\"table-responsive\">";
        $h.="<table class=\"table \">
                          <tbody><tr><td>";

        $h.="<table class=\"table table-striped\">
                          <tbody><tr><td>";
        if (!$new) {
            $h .= asTextField("Wareneingangsdatum", "we_datum", ($this->data['we_datum']));
            $h .= "</td></tr><tr><td>";
        }
        $h.=asTextField("Material-Nr.","we_nummer",($this->data['we_nummer']));
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Bezeichnung","we_bezeichnung",($this->data['we_bezeichnung']));
        $h.="</td></tr><tr><td>";
        $h.=asTextField("gelieferte Version","version",($this->data['version']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Lieferant","we_lieferant",($this->data['we_lieferant']),2);
        $h.="</td></tr><tr><td>";
        if (!$new) {

            $h .= asSelectBox("diesen Wareneingang löschen ?", "we_lokz", GetYesNoList("select", $this->data['we_lokz']), 7, "");
        }
        $h.="</td></tr></tbody></table>";
        $h.="</td><td>";
        $h.="<table class=\"table table-striped\"><tbody><tr><td>";
        $h.=asTextField("gelieferte Menge (gesamt)","we_stueckzahl",($this->data['we_stueckzahl']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Packstücke der Liefermenge","we_packstueck",($this->data['we_packstueck']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Anzahl der Etiketten beim Ausdrucken","we_eti_count",($this->data['we_eti_count']),3,"");

        $h.="</td></tr><tr><td>";
        $h.=asTextField("Prüfumfang","we_pruefumfang",($this->data['we_pruefumfang']),7);
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Lieferschein-Nr.","we_notes",($this->data['we_notes']),9,7,"");
        $h.="</td></tr></tbody></table>";
        $h.="</td></tr></tbody></table></div>";


        return $h;
    }

    function GetPosList(){
        $this->LoadPosData();
        // wed_id	we_id	wed_lokz	wed_bezeichnung	wed_grenze	wed_toleranz	wed_wert	wed_notes	wed_status
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Pos</th>
                         <th>Bezeichnung</th>
                        <th>Grenzwert</th>
                        <th>Toleranz</th>
                        <th>Istwert</th>
                        <th> </th>
                        <th>Status</th>
                       <th>Bemerkungen</th>
                        <th>Löschen ?</th>
                      
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->posdata)>0){
            $old_x="";
            $old_y="";
            $x=0;
            $y=0;
            $z=0;
            foreach($this->posdata as $i=>$item){

                $s=$this->ProvePosData($item['wed_grenze'],$item['wed_toleranz'],$item['wed_wert']);
                if ($old_x<>$item['wed_bezeichnung']){
                    $x++;
                }
                if ($old_y<>$item['wed_grenze']){
                    $y++;
                    $z=0;
                }
                $z++;
                $h.="<tr><td>".($i+1)." - $x.$y.$z </td>";
                $h.="<td>".$item['wed_bezeichnung']."</td>";
                $h.="<td>".$item['wed_grenze']."</td>";
                $h.="<td>".$item['wed_toleranz']."</td>";
                $h.="<td>".asTextField("","wed_wert".$item['wed_id'],$item['wed_wert'],12,"")."</td>";
                $h.="<td>".$this->PosAction($item['wed_bezeichnung'],$item['wed_id'],$item['wed_grenze'])."</td>";
                $h.="<td>".$this->GetStatus($s[0])."</td>";
                $h.="<td>".$item['wed_notes']." ".$s[1]."</td>";
                $h.="<td>".asSelectBox("","wed_lokz".$item['wed_id'],GetYesNoList("select",$item['wed_lokz']),12,"")."</td></tr>";
                $old_x=$item['wed_bezeichnung'];
                $old_y=$item['wed_grenze'];
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='9'>keine Prüfpositionen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        $h.="</table></div>";
        return $h;
    }

    function PosAction($n="", $i, $soll){
        if (stripos($n,"maß") ==false ){
            $h="<input size='10' class=\"btn btn-sm btn-custom\" value='Übernehmen' onclick='Wed".$i."()'>";
            $_SESSION['functions'].="
                    function Wed".$i."() {
                       alert('Sollwert $soll wird übernommen');
                      var text = '".$soll."';
                      $(\"#wed_wert".$i."\").val( text );
                    }
               
            ";
            return $h;
        }else{
        return "";
        }

    }

    function EditPosPropertyAsTable(){
        $h="";

        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Pos erstellen </b></h4>";
        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                          <tbody><tr><td>";
        $h.=asTextField("Bezeichnung","wed_bezeichnung",($this->data['wed_bezeichnung']));
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Grenzwert","wed_grenze",($this->data['wed_grenze']));
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Toleranz","wed_toleranz",($this->data['wed_toleranz']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Istwert","wed_wert",($this->data['wed_wert']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Bemerkungen","wed_notes",($this->data['wed_notes']),4,7,"");

        $h.="</td></tr></tbody></table></div>";


        return $h;
    }
}