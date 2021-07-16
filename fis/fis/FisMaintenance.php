<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 29.10.18
 * Time: 19:13
 */
class FisMaintenance
{

    public $bks=6101;
    public $pid=0;
    public $data=array();
    public $dataall=array();
    public $datafhm=array();

    public $allocfhm=array();

    public $datum1="0000-00-00";
    public $datum2="0000-00-00";

    public $search="";
    public $limit=100;
    public $lokz=0;

    function __construct($i)
    {
        if ($i>0){
            $this->pid=$i;
            $this->LoadData();
        }
    }

    function __toString(){
        return $this->pid;
    }


    function LoadData(){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fhm_pruef WHERE fhm_pruef.pid='$this->pid' ";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;
        }else{
            $this->data=array();
            AddSessionMessage("error","Wartung <code> PID $this->pid</code> nicht bekannt. Tabelle fhm_pruef nicht aktuell.","Datenbankfehler");
        }


    }
    function LoadAllData(){
        include ("./connection.php");

        $s="";

        if (strlen($this->search)>0){
            $s.="AND ( fhm_pruef.desc LIKE '%".$this->search."%' ) ";
        }

        if (($this->datum1)<>"0000-00-00"){
            $s.="AND ( fhm_pruef.nextdatum >='".$this->datum1."' ) ";
        }
        if (($this->datum2)<>"0000-00-00"){
            $s.="AND ( fhm_pruef.nextdatum <='".$this->datum2."' ) ";
        }

        if (($this->lokz)>0){
            $x="or  fhm_pruef.lokz='1' ";
        }

        if (($_SESSION['maintenancetype']>0 )){
            $s.="AND ( fhm_pruef.prueftype ='".$_SESSION['maintenancetype']."' ) ";
        }


        $this->dataall=array();

        $sql="SELECT * FROM fhm_pruef
          WHERE ( fhm_pruef.lokz='0' $x ) AND fhm_pruef.werk='".$this->bks."'
          $s
          ORDER BY fhm_pruef.nextdatum ASC LIMIT 0,".$this->limit;

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->dataall[]=$row_rst;
            }while( $row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();

           // AddSessionMessage("info","keine Wartungen zur Auswahl vorhanden.","Abfrageergebnis");
        }


    }

    function LoadAllocFhm(){
        include ("./connection.php");

        $s="";


        $this->allocfhm=array();

        $sql="SELECT fhm.id_fhm, fhm.name, fhm.id_fhm_gruppe FROM link_fhm_pid, fhm
          WHERE link_fhm_pid.id_fhm=fhm.id_fhm AND link_fhm_pid.pid='".$this->pid."'
          ORDER BY fhm.id_fhm_gruppe ASC, fhm.name ASC LIMIT 0,".$this->limit;

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->allocfhm[]=$row_rst;
            }while( $row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();

            AddSessionMessage("info","keine Equipments vorhanden oder zugeordnet.","Abfrageergebnis");
        }


    }


    function LoadAviableFHM(){
        // fid	fkurz	fname	fuser	lokz	farbeitsplatzgruppe	fkosten
        //  artikeldaten_entscheidung_link



        include("./connection.php");

        $sql="SELECT `fhm`.id_fhm, `fhm`.name, fhm.id_fhm_gruppe, link_fhm_pid.t 
              FROM `fhm` LEFT JOIN link_fhm_pid ON link_fhm_pid.id_fhm = `fhm`.id_fhm 
              AND link_fhm_pid.pid='$this->pid' WHERE `fhm`.lokz=0 AND `fhm`.werk='".$this->bks."'
              ORDER BY `fhm`.id_fhm_gruppe,`fhm`.name";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Zugeordnete Equipments  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->datafhm[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->datafhm= array();
        }
        $mysqli->close();
    }

    function DeleteAllFhm($pid){
        include ("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM link_fhm_pid WHERE link_fhm_pid.pid='%s'",
            GetSQLValueString($pid, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Equipmentzuordnung wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }
    function AddFhm($pid,$id_fhm){
        include ("./connection.php");
        $updateSQL = sprintf("
                INSERT INTO  link_fhm_pid (id_fhm,pid,t) VALUES (%s, %s,%s)",
            GetSQLValueString(utf8_decode($id_fhm), "int"),
            GetSQLValueString(utf8_decode($pid), "int"),
            time()
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Equipment wurde nicht zugeordnet, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }else{
            AddSessionMessage("success", "Equipment <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
        }
    }

    function SaveAllowedFhm()
    {
        $this->DeleteAllFhm($this->pid);
        if (count($_POST['my_multi_selectFHM'])) {

            foreach ($_POST['my_multi_selectFHM'] as $item) {
                $this->AddFhm($this->pid, $item);
                AddSessionMessage("success", "Equipment " . GetEquipmentName("text",$item) . " für Wartung $this->pid gespeichert", "Berechtigung");
            }
        }
    }

    function SaveFhm(){
        $this->Save();
        $this->SaveAllowedFhm();
    }

    function Add(){
        include("./connection.php");
        $updateSQL = sprintf("INSERT INTO `fhm_pruef` (`desc`, werk ) 
                                            VALUES (%s, %s)",
            GetSQLValueString($_REQUEST['desc0'],"text"),
            GetSQLValueString($this->bks,"int")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['zzz']=$updateSQL;
            AddSessionMessage("error", "Wartung  <code> PID " . $_REQUEST['desc'] . "</code> Anlegen konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        } else {

            $txt = "Wartung  <code> PID " . $_REQUEST['desc'] . "</code> wurde angelegt.";
            AddSessionMessage("success", $txt, "Wartung angelegt");
            $this->pid = mysqli_insert_id($mysqli);
            $w = new FisWorkflow($txt, "pid", $this->pid);
            $w->Add();

            $_SESSION['pid']=$this->pid;
            $_GET['pid']=$this->pid;
            $_REQUEST['pid']=$this->pid;

            $d=new FisDocument("Wartungsinfo","pid",$this->pid,$this->bks);
            $d->AddNewDocument();
        }
    }

    function Save(){
        include ("./connection.php");



        if (isset($_REQUEST['new_maintenance_add'])){
            $this->Add();
            $id=0;
            $_POST['lokz'.$id]=0;
        }else{
            $id=$this->pid;
        }

        if ($this->pid>0) {

            if ($_POST['lokz'.$id]=="on"){$_POST['lokz'.$id]=1;}else{$_POST['lokz'.$id]=0;}
            $updateSQL = sprintf("
                    UPDATE `fhm_pruef` SET 
                    `fhm_pruef`.desc=%s, `fhm_pruef`.lokz=%s, `fhm_pruef`.nextdatum=%s,`fhm_pruef`.datumremember=%s,
                    `fhm_pruef`.id_contact=%s, `fhm_pruef`.frequency=%s, fhm_pruef.prueftype=%s, `fhm_pruef`.notes=%s
                   
                    WHERE `fhm_pruef`.pid='" . $this->pid. "' ",
                GetSQLValueString(utf8_decode($_POST['desc'.$id]), "text"),
                GetSQLValueString(utf8_decode($_POST['lokz'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['nextdatum'.$id]), "text"),
                GetSQLValueString(utf8_decode($_POST['datumremember'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['id_contact'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['frequency'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['prueftype'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['notes'.$id]), "text")

            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Wartung <code> " . $_POST['desc'] . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
            } else {
                $txt = "Wartungsstammdaten <code> " . $_POST['desc'] . "</code> wurden gespeichert.";
                AddSessionMessage("success", $txt, "Update Wartungsdaten");
                $w = new FisWorkflow($txt, "pid", $this->pid);
                $w->Add();

            }
            $d = new FisDocument("Dokumente zur Wartung", "pid", $this->pid);
            $d->AddNewDocument();
        }else{
            AddSessionMessage("error", "Wartung PID ist unbekannt. Daten wurden nicht gespeichert.", "Fehlermeldung");
        }

    }

    function SaveEvent($datum=""){
        include ("./connection.php");
        $id=$this->pid;
        $contact = GetCustomerName("text", $_REQUEST['new_id_contact' . $id]);
        $datum=$_REQUEST['newdatum'.$id];

        $this->LoadAllocFhm();
        if (count($this->allocfhm)>0){
            $x=0;
            foreach ( $this->allocfhm as $i) {
                $fhm= new FisEquipment($i['id_fhm']);
                $fhm->AddEvent(2,"Wartung ".$this->data['desc']. " durch ".$contact,$this->pid,$datum);
                $x++;
            }



            $updateSQL = sprintf("
                    UPDATE `fhm_pruef` SET 
                    `fhm_pruef`.nextdatum=%s,`fhm_pruef`.datumremember=%s,
                    `fhm_pruef`.frequency=%s, `fhm_pruef`.notes=%s
                   
                    WHERE `fhm_pruef`.pid='" . $id. "' ",

                GetSQLValueString(utf8_decode($_POST['newnextdatum'.$id]), "text"),
                GetSQLValueString(utf8_decode($_POST['newdatumremember'.$id]), "int"),

                GetSQLValueString(utf8_decode($_POST['newfrequency'.$id]), "int"),
                GetSQLValueString(utf8_decode($_POST['notes'.$id].chr(13).$this->data['notes']), "text")

            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Wartung <code> " . $_POST['desc'] . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
            } else {


            $txt = "Wartungsarbeiten am <code> $datum </code> für <code> " . $x . " Equipments </code> durch <code> $contact </code> wurden gespeichert.";
            AddSessionMessage("success", $txt, "Wartungsdaten gespeichert");
            $w = new FisWorkflow($txt, "pid", $this->pid);
            $w->Add();
            }

        }else{
            AddSessionMessage("warning","Es wurden keine Equipments im Wartungsplan <code> ".$this->pid." </code> zugeordnet", "Abbruch");
        }
    }


    function GetNextNextDate(){
        $t=time()+($this->data['frequency']*60*60*24);
        return $t;
    }

    function GetNextDateRemember(){
        $t=strtotime($this->data['nextdatum'])-($this->data['datumremember']*60*60*24);
        return $t;
    }

    function GetNextDate(){
        $t=strtotime($this->data['nextdatum']);
        return $t;
    }

    function GetDurationDays($d=""){
        if ($d=="") {$d= $this->data['nextdatum'];}

        $t=round((strtotime($d)-time())/(60*60*24),0);
        if ($t<-1) {
            $t= "<span class='label label-danger'> " . abs($t) . " Tage überfällig </span>";
        }elseif ($t<0 && $t>=-1){
            $t="<span class='label label-danger'> seit gestern fällig </span>";
        }elseif ($t==0){
            $t="<span class='label label-success'> heute fällig </span>";


        }else{
            $t="<span class='label label-success'> noch ".$t." Tage </span>";
        }
        return $t;
    }


    function ShowPropertyAsTable(){
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="<td>Bezeichnung</td>";
        $h.="<td>".utf8_decode($this->data['desc'])."</td>";
        $h.="</tr><tr>";
        $h.="<td>Erinnerung</td>";
        $h.="<td>".GetMaintenanceTimeDurations("text",$this->data['datumremember'])."</td>";
        $h.="</tr><tr>";
        $h.="<td>Frequenz</td>";
        $h.="<td>".GetMaintenanceFrequency("text",$this->data['frequency'])."</td>";
        $h.="</tr><tr>";
        $h.="<td>Nächster Termin</td>";
        $h.="<td>".$this->data['nextdatum']."<br>";
        $h.="</tr><tr>";
        $h.="<td>Bemerkungen</td>";
        $h.="<td>".$this->data['notes']."</td>";
        $h.="</tr><tr>";
        $h.="</tr></tbody></table></div>";
        return $h;
    }



    function EditPropertyAsTable(){


        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="<td colspan='2'>".asTextField("Wartungsbezeichnung","desc".$this->pid,($this->data['desc']),9)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Nächster Termin","nextdatum".$this->pid,$this->data['nextdatum'],7,"")."</td>";
        $h.="<td>".asSelectBox("Erinnern","datumremember".$this->pid,GetMaintenanceTimeDurations("select",$this->data['datumremember']),7,9,"")."</td>";
        $h.="</tr><tr>";

        $h.="<td>".asSelectBox("Prüfintervall","frequency".$this->pid,GetMaintenanceFrequency("select",$this->data['frequency']),7)."</td>";
        $h.="<td>" . asSelectBox("Wartungsgruppe", "prueftype".$this->pid, GetMaintenanceTypes("select",$this->data['prueftype']),7 ) . "</td>";
        $h.="</tr><tr>";

        $h .= "<td colspan='2'>" . asSelect2Box("Durchführender", "id_contact" . $this->pid, GetCustomerName("select2service", $this->data['id_contact']), 9, "") . "</td>";

        $h.="</tr><tr>";
        $h.="<td colspan='2'>".asTextarea("Bemerkungen","notes".$this->pid,$this->data['notes'],3,9,"")."</td>";

        if ($this->pid>0) {
            $d=new FisDocument("Wartungsinfo","pid",$this->pid);
            $h.="</tr><tr>";
            $h .= "<td>" . asSwitchery("zum Löschen vormerken", "lokz".$this->pid, $this->data['lokz'], "FF0000", 5) . "</td><td></td>";
            $h .= "</tr><tr>";
            $h.="<td colspan='2'>".$d->ShowHtmlInputField()."</td>";
        }
        $h.="</tr></tbody></table></div>";

        $_SESSION['endscripts'].="
         \$('#nextdatum".$this->pid."').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
        ";
        return $h;
    }


    function NewEventAsTable(){
    $this->LoadAllocFhm();
    if (count($this->allocfhm) > 0) {
        $h = "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h .= "<td colspan='2'><b>Wartung: " . $this->data['desc'] . "</b></td>";
        $h .= "</tr><tr>";

        $h .= "<td colspan='2'><input type='hidden' name='new_pid' id='new_pid' value='" . $this->pid . "'></td>";

        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asTextField("durchgeführt am ", "newdatum" . $this->pid, date("Y-m-d", time()), 9) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asSelect2Box("Durchführender", "new_id_contact" . $this->pid, GetCustomerName("select2service", $this->data['id_contact']), 9) . "</td>";

        $h .= "</tr><tr>";
        $h .= "<td>" . asTextField("Nächster Termin", "newnextdatum" . $this->pid, date("Y-m-d", $this->GetNextNextDate()), 7, "") . "</td>";
        $h .= "<td>" . asSelectBox("Erinnern", "newdatumremember" . $this->pid, GetMaintenanceTimeDurations("select", $this->data['datumremember']), 7, 9, "") . "</td>";
        $h .= "</tr><tr>";

        $h .= "<td>" . asSelectBox("nächstes Prüfintervall", "newfrequency" . $this->pid, GetMaintenanceFrequency("select", $this->data['frequency']), 7, "") . "</td>";
        $h .= "<td></td>";

        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asTextarea("Bemerkungen", "notes" . $this->pid, "", 3, 9, "") . "</td>";


        $h .= "</tr></tbody></table>";
        $h .= $this->ShowTableAllocFhm(false);
        $h .= "</div>";

        $_SESSION['endscripts'] .= "
         \$('#newnextdatum" . $this->pid . "').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
         \$('#newdatum" . $this->pid . "').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: \"yyyy-mm-dd\",
           
        });
        ";
    }else{
        $h="keine Dokumentation möglich, da keine Equipment zugeordnet wurde.";
    }
        return $h;
    }

    function EditEquipments()
    {
        $h="";

        $txt=$this->ShowHtmlFHMList();
        $link = "index.php?sess=" . $_SESSION['sess'] ."&wp=" . $_SESSION['wp'] ."&action=64&pid=" . $this->pid;
        $btn = "<button id='maintenance_save_alloc' name='maintenance_save_alloc' type='submit' class='btn btn-custom pull-right' href=\"$link\" onclick='refresh_site()'> Speichern </button>";

        $h .= asModal(6001, $btn . "<br><hr>" . $txt, $btn, 100, "Equipments auswählen");
        $h .= "<a href=\"#custom-width-modal6001\" data-toggle=\"modal\" data-target=\"#custom-width-modal6001\" class=\"btn btn-custom waves-effect waves-light pull-right\" >Equipments zuordnen</a>
         ";
        return $h;
    }

    function ShowHtmlSelectedFHM(){
        $this->LoadAviableFHM();
        $h="";
        if (count($this->datafhm)){
            $x="";
            foreach ($this->datafhm as $i=>$u){
                if ($u['t']>0) {$s="selected"; }else{$s="";}
                if ($u['id_fhm_gruppe']<>$x) {
                    if ($x<>""){$h.="</optgroup>";}
                    $h .= "<optgroup label=\"" . GetEquipmentGroupName("text",$u['id_fhm_gruppe'] ). "\">";
                }
                $h .= sprintf("<option value='%s' %s> %s </option>", $u['id_fhm'], $s, utf8_decode($u['name']) . "(" . $u['id_fhm'] . ")");
                $x=$u['id_fhm_gruppe'];
            }
            $h.="</optgroup>";
        }
        return $h;
    }

    function ShowHtmlFHMList(){
        $h="<select multiple=\"\" class=\"multi-select\" id=\"my_multi_selectFHM\" name=\"my_multi_selectFHM[]\" data-selectable-optgroup=\"true\" >";
        $h.=$this->ShowHtmlSelectedFHM();
        $h.="</select>";

        $_SESSION['endscripts'].=" //advance multiselect start
        $('#my_multi_selectFHM').multiSelect({
            selectableHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            selectionHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            afterInit: function (ms) {
                var that = this,
                    \$selectableSearch = that.\$selectableUl.prev(),
                    \$selectionSearch = that.\$selectionUl.prev(),
                    selectableSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = \$selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.\$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = \$selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.\$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });";
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

    function ShowHtmlAllAsTable($status="1",$limit=100,$search="",$no_free_search_value_allowed=0,$showaction=true,$showsetfd=false){

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Wartungen Suchbegriff:(".$this->GetSearchString().") </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'] .= "
            
            var table  = $(\"#datatable" . $_SESSION['datatable'] . "\").DataTable({
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
                            0 !== $(\"#datatable" . $_SESSION['datatable'] . "\").length && table
                        }, TableManageButtons = function () {
                            \"use strict\";
                            return {
                                init: function () {
                                    handleDataTableButtons()
                                }
                            }
                        }();

     
            ";
            $_SESSION['script'] .= "
            $('#datatable" . $_SESSION['datatable'] . "').dataTable();
            ";

        $h.=$this->ShowTable();

            }else {
        $h .= "keine Wartungsarbeiten notwendig.";
        }
        return $h;
    }

    function ShowTable(){
        if (count($this->dataall) > 0) {
        $y=$_SESSION['datatable'];
        $h=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th width='15%'>Ansprechpartner</th>
                            <th width='15%'>Bezeichnung</th>
                              <th width='15%'>Prüfintervall</th>
                            <th width='15%'>nächster Prüftermin</th>
                             <th width='10%'>Bemerkungen</th>
                            <th width='25%'>Equipments</th>
                             <th width='5%'>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>";

                foreach ($this->dataall as $d => $i) {
                    $x=$i['pid'];
                    $link = "index.php?sess=" . $_SESSION['sess'] ."&wp=" . $_SESSION['wp'] ."&action=64&pid=" . $i['pid'];
                    $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> Bearbeiten </a>";
                    $equip = "<a class=\"btn btn-custom btn-sm\" href=\"#\" onclick=\"load_layout_equip_$y_$x()\"> Equipments anzeigen </a>";
                    $hide = "<a class= \"btn btn-custom btn-sm\" href=\"#\" onclick=\"unload_layout_equip_$y_$x()\"> Equipments ausblenden </a>";

                    $maintenance=" <a href=\"#custom-width-modal5002\" data-toggle=\"modal\" data-target=\"#custom-width-modal5002\" class=\"btn btn-custom btn-sm waves-effect waves-light\" onclick=\"load_layout_new_maintenance_$y_$x();\" >Neue Wartung</a>";

                    $load="<div id='layout_fhm_$y_$x'></div>";

                    $js .= "<script>
                      
                            function load_layout_equip_$y_$x(){   
                                 \$(\"#layout_fhm_$y_$x\").show();
                                \$(\"#layout_fhm_$y_$x\").html('Equipments werden geladen <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                                \$(\"#layout_fhm_$y_$x\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=maintenancefhm&pid=".$i['pid']."\" , function(responseTxt, statusTxt, xhr){
                                \$(\"#layout_fhm_$y_$x\").append('".$hide."');
                                load_layout_new_maintenance_$y_$x();
                            
                            });
                                };
    
                         function load_layout_new_maintenance_$y_$x(){ 
                              \$(\"#layout_new_maintenance\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=maintenancenew&pid=".$i['pid']."\" , function(responseTxt, statusTxt, xhr){
                                 
                                 
                                 \$('#newnextdatum" . $x . "').datepicker({
                                        autoclose: true,
                                        todayHighlight: true,
                                        format: \"yyyy-mm-dd\",
                                       
                                    });
                                 \$('#newdatum" . $x . "').datepicker({
                                    autoclose: true,
                                    todayHighlight: true,
                                    format: \"yyyy-mm-dd\",
                                   
                                });                 
                            });
                                };
                                
                            function unload_layout_equip_$y_$x(){   
                                \$(\"#layout_fhm_$y_$x\").hide();
                             };
                            </script>
                            ";


                    $h .= "<tr>
                                <td>" . GetCustomerName("text",$i['id_contact']) . "</td>
                                <td><a href='$link'>" . $i['desc'] . "</a></td>
                                
                                 <td>" . GetMaintenanceFrequency("text",$i['frequency']) . "</td>
                                
                                <td>" . $i['nextdatum'] . "</td>
                                <td>" . $this->GetDurationDays( $i['nextdatum']) . "</td>
                                <td>" . $load . "</td>
                                <td>" . $equip.$maintenance.$btn . "</td>
                            </tr>";
                }

                $h.="</tbody></table>";
            $h .= $js;

        }
    return $h;

    }

    function ShowHtmlAllocFhm($status="1",$limit=100,$search="",$no_free_search_value_allowed=0,$showaction=true,$showsetfd=false){

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->LoadAllocFhm();
        $h = "";
        //$h = "<h4 class=\"header-title m-t-0 m-b-30\">ausgewählte Equipments:(".$this->GetSearchString().") </h4>";

        if (count($this->allocfhm) > 0) {

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
            $_SESSION['script'].="
            $('#datatable".$_SESSION['datatable']."').dataTable();
            ";

           $h.= $this->ShowTableAllocFhm();
            }else {
        $h .= "keine Equipments zugeordnet.";
        }
        return $h;
        }


        function ShowTableAllocFhm($action=true){
            $h="";

            if (count($this->allocfhm) > 0) {
                $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                            <thead>
                                <tr>
                                    <th>Equipmentname</th>
                                    <th>Gruppe</th>";
                                      if ($action) {
                                          $h .= "<th>Aktion</th>";
                                      }
                               $h.="</tr>
                            </thead>
                            <tbody>";

                    foreach ($this->allocfhm as $d => $i) {
                        $link = "index.php?sess=" . $_SESSION['sess'] ."&wp=" . $_SESSION['wp'] ."&action=31&equip=" . $i['id_fhm'];
                        if ($action) {
                            $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> bearbeiten </a>";
                        }else{
                            $btn="";
                        }
                        $h .= "<tr>
                                <td>" . utf8_decode($i['name']) . "</td>
                               <td>" . GetEquipmentGroupName("text",$i['id_fhm_gruppe']) . "</td>";
                        if ($action) {
                            $h .= "<td>" . $btn . "</td>";
                        }

                                $h.="</tr>";
                    }

                    $h.="</tbody></table>";

        }
        return $h;
        }
}