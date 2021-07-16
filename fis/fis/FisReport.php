<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 29.10.18
 * Time: 19:13
 */
class FisReport
{

    public $bks = 6101;
    public $rid = 0;
    public $data = array();
    public $dataall = array();
    public $datamaterial = array();
    public $canvas = 0;
    public $allocmaterial = array();
    public $id = 0;

    public $datum1 = "";
    public $datum2 = "";

    public $search = "";
    public $limit = 100;
    public $lokz = 0;

    function __construct($i)
    {
        if ($i > 0) {
            $this->rid = $i;
            $this->LoadData();
        }
    }

    function LoadData()
    {
        include("./connection.php");
        $this->data = array();
        $sql = "SELECT * FROM fisreport WHERE fisreport.rid='$this->rid' ";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data = $row_rst;
        } else {
            $this->data = array();
            AddSessionMessage("error", "Report <code> RID $this->rid</code> nicht bekannt. Tabelle fisreport nicht aktuell.", "Datenbankfehler");
        }


    }

    function __toString()
    {
        return $this->rid;
    }

    function SaveFhm()
    {
        $this->Save();
        $this->SaveAllowedFhm();
    }

    function Save()
    {
        include("./connection.php");


        if (isset($_REQUEST['new_report_add'])) {
            $this->Add();
            $id = 0;
            $_POST['lokz' . $id] = 0;
        } else {
            $id = $this->rid;
        }

        if ($this->rid > 0) {

            if ($_POST['lokz' . $id] == "on") {
                $_POST['lokz' . $id] = 1;
            } else {
                $_POST['lokz' . $id] = 0;
            }
            $updateSQL = sprintf("
                    UPDATE `fisreport` SET 
                    `fisreport`.desc=%s, `fisreport`.lokz=%s, `fisreport`.nextdatum=%s,`fisreport`.datumremember=%s,
                   `fisreport`.frequency=%s, fisreport.reporttype=%s, `fisreport`.notes=%s
                   
                    WHERE `fisreport`.rid='" . $this->rid . "' ",
                GetSQLValueString(utf8_decode($_POST['desc' . $id]), "text"),
                GetSQLValueString(utf8_decode($_POST['lokz' . $id]), "int"),
                GetSQLValueString(utf8_decode($_POST['nextdatum' . $id]), "text"),
                GetSQLValueString(utf8_decode($_POST['datumremember' . $id]), "int"),

                GetSQLValueString(utf8_decode($_POST['frequency' . $id]), "int"),
                GetSQLValueString(utf8_decode($_POST['reporttype' . $id]), "int"),
                GetSQLValueString(utf8_decode($_POST['notes' . $id]), "text")

            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Report <code> " . $_POST['desc'] . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
            } else {
                $txt = "Reportstammdaten <code> " . $_POST['desc'] . "</code> wurden gespeichert.";
                AddSessionMessage("success", $txt, "Update Reportdaten");
                $w = new FisWorkflow($txt, "rid", $this->rid);
                $w->Add();

            }
            $d = new FisDocument("Dokumente zum Report", "rid", $this->rid);
            $d->AddNewDocument();
        } else {
            AddSessionMessage("error", "Report RID ist unbekannt. Daten wurden nicht gespeichert.", "Fehlermeldung");
        }

    }

    function Add()
    {
        include("./connection.php");
        $updateSQL = sprintf("INSERT INTO `fisreport` (`desc`, werk ) 
                                            VALUES (%s, %s)",
            GetSQLValueString($_REQUEST['desc0'], "text"),
            GetSQLValueString($this->bks, "int")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['zzz'] = $updateSQL;
            AddSessionMessage("error", "Report  <code> rid " . $_REQUEST['desc'] . "</code> Anlegen konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        } else {

            $txt = "Report  <code> rid " . $_REQUEST['desc'] . "</code> wurde angelegt.";
            AddSessionMessage("success", $txt, "Report angelegt");
            $this->rid = mysqli_insert_id($mysqli);
            $w = new FisWorkflow($txt, "rid", $this->rid);
            $w->Add();

            $_SESSION['rid'] = $this->rid;
            $_GET['rid'] = $this->rid;
            $_REQUEST['rid'] = $this->rid;

            $d = new FisDocument("Reportinfo", "rid", $this->rid, $this->bks);
            $d->AddNewDocument();
        }
    }

    function SaveAllowedFhm()
    {
        $this->DeleteAllFhm($this->rid);
        if (count($_POST['my_multi_selectMaterial'])) {

            foreach ($_POST['my_multi_selectMaterial'] as $item) {
                $this->AddFhm($this->rid, $item);
                AddSessionMessage("success", "Artikel " . GetArtikelName("text", $item) . " für Report RID $this->rid gespeichert", "Zuordnung gespeichert");
            }
        }
    }

    function DeleteAllFhm($rid)
    {
        include("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM link_artikelid_rid WHERE link_artikelid_rid.rid='%s'",
            GetSQLValueString($rid, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error", "Equipmentzuordnung wurde nicht gelöscht, da ein Fehler aufgetreten ist.", "Fehlermeldung");
        }
    }

    function AddFhm($rid, $artikelid)
    {
        include("./connection.php");
        $updateSQL = sprintf("
                INSERT INTO  link_artikelid_rid (artikelid,rid,t) VALUES (%s, %s,%s)",
            GetSQLValueString(utf8_decode($artikelid), "int"),
            GetSQLValueString(utf8_decode($rid), "int"),
            time()
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Equipment wurde nicht zugeordnet, da ein Fehler aufgetreten ist.", "Fehlermeldung");
        } else {
            AddSessionMessage("success", "Material <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
        }
    }

    function SaveEvent($datum = "")
    {
        include("./connection.php");
        $id = $this->rid;
        $datum = $_REQUEST['newdatum' . $id];

        $this->Loadallocmaterial();
        if (count($this->allocmaterial) > 0) {
            $x = 0;
            foreach ($this->allocmaterial as $i) {
                // $fhm= new FisEquipment($i['id_fhm']);
                // $fhm->AddEvent(2,"Report ".$this->data['desc'],$this->rid,$datum);
                $x++;
            }


            $updateSQL = sprintf("
                    UPDATE `fisreport` SET 
                    `fisreport`.nextdatum=%s,`fisreport`.datumremember=%s,
                    `fisreport`.frequency=%s, `fisreport`.notes=%s
                   
                    WHERE `fisreport`.rid='" . $id . "' ",

                GetSQLValueString(utf8_decode($_POST['newnextdatum' . $id]), "text"),
                GetSQLValueString(utf8_decode($_POST['newdatumremember' . $id]), "int"),

                GetSQLValueString(utf8_decode($_POST['newfrequency' . $id]), "int"),
                GetSQLValueString(utf8_decode($_POST['notes' . $id] . chr(13) . $this->data['notes']), "text")

            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Report <code> " . $_POST['desc'] . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
            } else {


                $txt = "Report am <code> $datum </code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Reportdaten gespeichert");
                $w = new FisWorkflow($txt, "rid", $this->rid);
                $w->Add();
            }

        } else {
            AddSessionMessage("warning", "Es wurden keine Artikel im Report <code> " . $this->rid . " </code> zugeordnet", "Abbruch");
        }
    }

    function Loadallocmaterial()
    {
        include("./connection.php");

        $s = "";


        $this->allocmaterial = array();

        $sql = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer, artikeldaten.Gruppe FROM link_artikelid_rid, artikeldaten
          WHERE link_artikelid_rid.artikelid=artikeldaten.artikelid AND link_artikelid_rid.rid='" . $this->rid . "'
          ORDER BY artikeldaten.Gruppe ASC, artikeldaten.Bezeichnung ASC LIMIT 0," . $this->limit;

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            //$_SESSION['zzz']=$sql;
            AddSessionMessage("error", "Zugeordnete Arikel  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->allocmaterial[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->data = array();

            // AddSessionMessage("info", "keine Artikel vorhanden oder zugeordnet.", "Abfrageergebnis");
        }


    }

    function GetNextDateRemember()
    {
        $t = strtotime($this->data['nextdatum']) - ($this->data['datumremember'] * 60 * 60 * 24);
        return $t;
    }

    function GetNextDate()
    {
        $t = strtotime($this->data['nextdatum']);
        return $t;
    }

    function ShowPropertyAsTable()
    {
        $h = "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h .= "<td>Bezeichnung</td>";
        $h .= "<td>" . utf8_decode($this->data['desc']) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td>Erinnerung</td>";
        $h .= "<td>" . GetMaintenanceTimeDurations("text", $this->data['datumremember']) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td>Frequenz</td>";
        $h .= "<td>" . GetMaintenanceFrequency("text", $this->data['frequency']) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td>Nächster Termin</td>";
        $h .= "<td>" . $this->data['nextdatum'] . "<br>";
        $h .= "</tr><tr>";
        $h .= "<td>Bemerkungen</td>";
        $h .= "<td>" . $this->data['notes'] . "</td>";
        $h .= "</tr><tr>";
        $h .= "</tr></tbody></table></div>";
        return $h;
    }

    function EditPropertyAsTable()
    {


        $h = "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h .= "<td colspan='2'>" . asTextField("Auswertegruppe", "desc" . $this->rid, ($this->data['desc']), 9) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td>" . asTextField("Nächster Termin", "nextdatum" . $this->rid, $this->data['nextdatum'], 7, "") . "</td>";
        $h .= "<td>" . asSelectBox("Erinnern", "datumremember" . $this->rid, GetMaintenanceTimeDurations("select", $this->data['datumremember']), 7, 9, "") . "</td>";
        $h .= "</tr><tr>";

        $h .= "<td>" . asSelectBox("Reportintervall", "frequency" . $this->rid, GetReportFrequency("select", $this->data['frequency']), 7) . "</td>";
        $h .= "<td>" . asSelectBox("Reportgruppe", "reporttype" . $this->rid, GetReportTypes("select", $this->data['reporttype']), 7) . "</td>";

        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asTextarea("Bemerkungen", "notes" . $this->rid, $this->data['notes'], 3, 9, "") . "</td>";

        if ($this->rid > 0) {
            $d = new FisDocument("Reportinfo", "rid", $this->rid);
            $h .= "</tr><tr>";
            $h .= "<td>" . asSwitchery("zum Löschen vormerken", "lokz" . $this->rid, $this->data['lokz'], "FF0000", 5) . "</td><td></td>";
            $h .= "</tr><tr>";
            $h .= "<td colspan='2'>" . $d->ShowHtmlInputField() . "</td>";
        }
        $h .= "</tr></tbody></table></div>";

        $_SESSION['endscripts'] .= "
         \$('#nextdatum" . $this->rid . "').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
        ";
        return $h;
    }

    function NewEventAsTable()
    {
        $this->Loadallocmaterial();
        if (count($this->allocmaterial) > 0) {
            $h = "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
            $h .= "<td colspan='2'><b>Report: " . $this->data['desc'] . "</b></td>";
            $h .= "</tr><tr>";

            $h .= "<td colspan='2'><input type='hidden' name='new_rid' id='new_rid' value='" . $this->rid . "'></td>";

            $h .= "</tr><tr>";
            $h .= "<td colspan='2'>" . asTextField("durchgeführt am ", "newdatum" . $this->rid, date("Y-m-d", time()), 9) . "</td>";

            $h .= "</tr><tr>";
            $h .= "<td>" . asTextField("Nächster Termin", "newnextdatum" . $this->rid, date("Y-m-d", $this->GetNextNextDate()), 7, "") . "</td>";
            $h .= "<td>" . asSelectBox("Erinnern", "newdatumremember" . $this->rid, GetMaintenanceTimeDurations("select", $this->data['datumremember']), 7, 9, "") . "</td>";
            $h .= "</tr><tr>";

            $h .= "<td>" . asSelectBox("nächstes Reportintervall", "newfrequency" . $this->rid, GetReportFrequency("select", $this->data['frequency']), 7, "") . "</td>";
            $h .= "<td></td>";

            $h .= "</tr><tr>";
            $h .= "<td colspan='2'>" . asTextarea("Bemerkungen", "notes" . $this->rid, "", 3, 9, "") . "</td>";


            $h .= "</tr></tbody></table>";
            $h .= $this->ShowTableallocmaterial(false);
            $h .= "</div>";

            $_SESSION['endscripts'] .= "
         \$('#newnextdatum" . $this->rid . "').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
         \$('#newdatum" . $this->rid . "').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: \"yyyy-mm-dd\",
           
        });
        ";
        } else {
            $h = "keine Dokumentation möglich, da keine Artikel zugeordnet wurde.";
        }
        return $h;
    }

    function GetNextNextDate()
    {
        $t = time() + ($this->data['frequency'] * 60 * 60 * 24);
        return $t;
    }

    function ShowTableallocmaterial($action = true)
    {
        $h = "";

        if (count($this->allocmaterial) > 0) {
            $h .= " <table id=\"datatable" . $_SESSION['datatable'] . "\" class=\"table table-striped dt-responsive nowrap\">
                            <thead>
                                <tr>
                                  <th>Nummer</th>
                                    <th>Bezeichnung</th>
                                    <th>Gruppe</th>";
            if ($action) {
                $h .= "<th>Aktion</th>";
            }
            $h .= "</tr>
                            </thead>
                            <tbody>";

            foreach ($this->allocmaterial as $d => $i) {
                $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15&material=" . $i['artikelid'];
                if ($action) {
                    $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> anzeigen </a>";
                } else {
                    $btn = "";
                }
                $h .= "<tr>
                                <td>" . utf8_decode($i['Nummer']) . "</td>
                                <td>" . ($i['Bezeichnung']) . "</td>
                               <td>" . ($i['Gruppe']) . "</td>";
                if ($action) {
                    $h .= "<td>" . $btn . "</td>";
                }

                $h .= "</tr>";
            }

            $h .= "</tbody></table>";

        }
        return $h;
    }

    function EditArtikel()
    {
        $h = "";

        $txt = $this->ShowHtmlFHMList();
        $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=66&rid=" . $this->rid;
        $btn = "<button id='report_save_alloc' name='report_save_alloc' type='submit' class='btn btn-custom pull-right' href=\"$link\" onclick='refresh_site()'> Speichern </button>";

        $h .= asModal(6001, $btn . "<br><hr>" . $txt, $btn, 100, "Artikel auswählen");
        $h .= "<a href=\"#custom-width-modal6001\" data-toggle=\"modal\" data-target=\"#custom-width-modal6001\" class=\"btn btn-custom waves-effect waves-light pull-right\" >Artikel zuordnen</a>
         ";
        return $h;
    }

    function ShowHtmlFHMList()
    {
        $h = "<select multiple=\"\" class=\"multi-select\" id=\"my_multi_selectMaterial\" name=\"my_multi_selectMaterial[]\" data-selectable-optgroup=\"true\" >";
        $h .= $this->ShowHtmlSelectedMaterial();
        $h .= "</select>";

        $_SESSION['endscripts'] .= " //advance multiselect start
        $('#my_multi_selectMaterial').multiSelect({
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

    function ShowHtmlSelectedMaterial()
    {
        $this->LoadAviableFHM();
        $h = "";
        if (count($this->datamaterial)) {
            $x = "";
            foreach ($this->datamaterial as $i => $u) {
                if ($u['t'] > 0) {
                    $s = "selected";
                } else {
                    $s = "";
                }
                if ($u['Gruppe'] <> $x) {
                    if ($x <> "") {
                        $h .= "</optgroup>";
                    }
                    $h .= "<optgroup label=\"" . $u['Gruppe'] . "\">";
                }
                $h .= sprintf("<option value='%s' %s> %s </option>", $u['artikelid'], $s, utf8_decode($u['Bezeichnung']) . "(" . $u['Nummer'] . ")");
                $x = $u['Gruppe'];
            }
            $h .= "</optgroup>";
        }
        return $h;
    }

    function LoadAviableFHM()
    {
        // fid	fkurz	fname	fuser	lokz	farbeitsplatzgruppe	fkosten
        //  artikeldaten_entscheidung_link


        include("./connection.php");

        $sql = "SELECT `artikeldaten`.artikelid, `artikeldaten`.Bezeichnung, artikeldaten.Gruppe, link_artikelid_rid.t 
              FROM `artikeldaten` LEFT JOIN link_artikelid_rid ON link_artikelid_rid.artikelid = `artikeldaten`.artikelid 
              AND link_artikelid_rid.rid='$this->rid' WHERE `artikeldaten`.aktiviert=1 AND `artikeldaten`.bks='" . $this->bks . "'
              ORDER BY `artikeldaten`.Gruppe,`artikeldaten`.Bezeichnung";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            $_SESSION['zzz'] = $sql;
            AddSessionMessage("error", "Zugeordnete Arikel  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->datamaterial[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->datafhm = array();
        }
        $mysqli->close();
    }

    function GetLimit()
    {
        return $this->limit;
    }

    function SetLimit($l = 100)
    {
        if (intval($l) < 501) {
            $this->limit = intval($l);
            if ($this->limit <= 5) {
                $this->limit = 100;
            }
        } else {
            $this->limit = 100;
        }
        return $this;
    }

    function ShowHtmlAllAsTable($status = "1", $limit = 100, $search = "", $no_free_search_value_allowed = 0, $showaction = true, $showsetfd = false)
    {

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Auswertungsgruppen Suchbegriff:(" . $this->GetSearchString() . ") </h4>";

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

            $h .= $this->ShowTable();

        } else {
            $h .= "keine Auswertegruppen gefunden";
        }
        return $h;
    }

    function SetSearchString($s = "")
    {
        $this->search = utf8_encode($s);
        return $this;
    }

    function SetNoFreeValueAllowed($s = 0)
    {
        $this->no_free_search_value_allowed = $s;
        return $this;
    }

    function LoadAllData()
    {
        include("./connection.php");

        $s = "";

        if (strlen($this->search) > 0) {
            $s .= "AND ( fisreport.desc LIKE '%" . $this->search . "%' ) ";
        }

        if (($this->datum1) <> "0000-00-00") {
            $s .= "AND ( fisreport.nextdatum >='" . $this->datum1 . "' ) ";
        }
        if (($this->datum2) <> "0000-00-00") {
            $s .= "AND ( fisreport.nextdatum <='" . $this->datum2 . "' ) ";
        }

        if (($this->lokz) > 0) {
            $x = "or  fisreport.lokz='1' ";
        }

        if (($_SESSION['reporttype'] > 0)) {
            $s .= "AND ( fisreport.reporttype ='" . $_SESSION['reporttype'] . "' ) ";
        }


        $this->dataall = array();

        $sql = "SELECT * FROM fisreport
          WHERE ( fisreport.lokz='0' $x ) AND fisreport.werk='" . $this->bks . "'
          $s
          ORDER BY fisreport.nextdatum ASC LIMIT 0," . $this->limit;

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->dataall[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->data = array();

            // AddSessionMessage("info","keine Wartungen zur Auswahl vorhanden.","Abfrageergebnis");
        }


    }

    function GetSearchString()
    {
        $s = utf8_decode($this->search);
        return $s;
    }

    function ShowTable()
    {
        if (count($this->dataall) > 0) {
            $y = $_SESSION['datatable'];
            $h = " <table id=\"datatable" . $_SESSION['datatable'] . "\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                          
                            <th width='20%'>Auswertegruppe</th>
                              <th width='15%'>Auswerteintervall</th>
                            <th width='15%'>nächster Termin</th>
                             <th width='10%'>Bemerkungen</th>
                            <th width='35%'>Artikel</th>
                             <th width='5%'>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($this->dataall as $d => $i) {
                $x = $i['rid'];
                $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=69&rid=" . $i['rid'];
                $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> Bearbeiten </a>";
                $equip = "<a class=\"btn btn-custom btn-sm\" href=\"#\" onclick=\"load_layout_equip_$y_$x()\"> Artikel anzeigen </a>";
                $hide = "<a class= \"btn btn-custom btn-sm\" href=\"#\" onclick=\"unload_layout_equip_$y_$x()\"> Artikel ausblenden </a>";

                $maintenance = " <a href=\"#custom-width-modal5002\" data-toggle=\"modal\" data-target=\"#custom-width-modal5002\" class=\"btn btn-custom btn-sm waves-effect waves-light\" onclick=\"load_layout_new_maintenance_$y_$x();\" >Neue Auswertung</a>";

                $load = "<div id='layout_fhm_$y_$x'></div>";

                $js .= "<script>
                      
                            function load_layout_equip_$y_$x(){   
                                 \$(\"#layout_fhm_$y_$x\").show();
                                \$(\"#layout_fhm_$y_$x\").html('Artikel werden geladen <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                                \$(\"#layout_fhm_$y_$x\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=reportmaterial&rid=" . $i['rid'] . "\" , function(responseTxt, statusTxt, xhr){
                                \$(\"#layout_fhm_$y_$x\").append('" . $hide . "');
                                load_layout_new_maintenance_$y_$x();
                            
                            });
                                };
    
                         function load_layout_new_maintenance_$y_$x(){ 
                              \$(\"#layout_new_maintenance\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=reportmaterialnew&rid=" . $i['rid'] . "\" , function(responseTxt, statusTxt, xhr){
                                 
                                 
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


                $h .= "<tr> <td><a href='$link'>" . $i['desc'] . "</a></td>
                                
                                 <td>" . GetReportFrequency("text", $i['frequency']) . "</td>
                                
                                <td>" . $i['nextdatum'] . "</td>
                                <td>" . $this->GetDurationDays($i['nextdatum']) . "</td>
                                <td>" . $load . "</td>
                                <td>" . $equip . $maintenance . $btn . "</td>
                            </tr>";
            }

            $h .= "</tbody></table>";
            $h .= $js;

        }
        return $h;

    }

    function GetDurationDays($d = "")
    {
        if ($d == "") {
            $d = $this->data['nextdatum'];
        }

        $t = round((strtotime($d) - time()) / (60 * 60 * 24), 0);
        if ($t < -1) {
            $t = "<span class='label label-danger'> " . abs($t) . " Tage überfällig </span>";
        } elseif ($t < 0 && $t >= -1) {
            $t = "<span class='label label-danger'> seit gestern fällig </span>";
        } elseif ($t == 0) {
            $t = "<span class='label label-success'> heute fällig </span>";


        } else {
            $t = "<span class='label label-success'> noch " . $t . " Tage </span>";
        }
        return $t;
    }

    function ShowHtmlallocmaterial($status = "1", $limit = 100, $search = "", $no_free_search_value_allowed = 0, $showaction = true, $showsetfd = false)
    {

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->Loadallocmaterial();
        $h = "";
        //$h = "<h4 class=\"header-title m-t-0 m-b-30\">ausgewählte Equipments:(".$this->GetSearchString().") </h4>";

        if (count($this->allocmaterial) > 0) {

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

            $h .= $this->ShowTableallocmaterial();
        } else {
            $h .= "keine Material zugeordnet.";
        }
        return $h;
    }


    function DiagrammCountFID($fidtype = 99)
    {
        $this->series = array();
        $_SESSION['report_count']++;
        $_SESSION['report'] = 1;
        $size = $_SESSION['report_sizeheight'];
        $sum_anzahl = 0;
        include("./connection.php");
        $d = "fidtype" . $fidtype;

        if ($fidtype < 99) {
            $fidtypesql = " AND fertigungsmeldungen.fidtype='" . $fidtype . "' ";
            $header = "Menge " . GetFidType("text", $fidtype);
        } else {
            $fidtypesql = "";
            $header = "Gesamt produzierte Menge ";
        }
        $StartDate = @strtotime($this->datum1);
        $StopDate = @strtotime($this->datum2);

        $format = GetReportFormat("data", $_SESSION['report_format']);

        if (strlen($format) == 0) $format = "%Y-%m";
        if ($_SESSION['report_format'] == 0) $this->label = echoMonth($StartDate, $StopDate);
        if ($_SESSION['report_format'] == 1) $this->label = echoDate($StartDate, $StopDate);

        $this->Loadallocmaterial();

        if ($_SESSION['report_material'] > 0) {
            $txt = GetArtikelName("text", $_SESSION['report_material']);
            $data = array();
            $this->series = array();


            foreach ($this->label as $datum) {

                $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE fertigungsmeldungen.fartikelid = " . $_SESSION['report_material'] . " 
                AND fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
                $fidtypesql
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }
                $data[] = $anzahl;
            }

            $this->series[] = array("name" => GetArtikelName("text", $_SESSION['report_material']), "data" => $data);
        } elseif ($this->allocmaterial > 0 && $this->rid > 0) {
            $txt = "Gruppe " . $this->data['desc'];
            $this->series = array();
            foreach ($this->allocmaterial as $i => $item) {
                $data = array();

                foreach ($this->label as $datum) {

                    $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE fertigungsmeldungen.fartikelid = " . $item['artikelid'] . " 
                AND fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
                $fidtypesql
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $anzahl = $row_rst['anzahl'];
                        $sum_anzahl = $sum_anzahl + $anzahl;
                    } else {
                        $anzahl = 0;
                    }
                    $data[] = $anzahl;
                }

                $this->series[] = array("name" => GetArtikelName("text", $item['artikelid']), "data" => $data);
            }

        } else {
            $txt = "(alle Artikel)";
            $data = array();
            $this->series = array();
            foreach ($this->label as $datum) {
                $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE 
                fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
                $fidtypesql
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }
                $data[] = $anzahl;
            }

            $this->series[] = array("name" => "alle Fertigungsmeldungen", "data" => $data);
        }


        $h = "
        <script>
        
        //Stacked bar chart

        var chart" . $this->id . " = new Chartist.Bar('#stacked-bar-chart_fids_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "', {
          labels: " . json_encode($this->label) . ",
          series: " . json_encode($this->series) . "
        }, {
            height:'" . ($size) . "px',
          stackBars: true,
          axisY: {
            labelInterpolationFnc: function(value) {
              return (value) + 'St.';
            }
          },
          plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.legend()
          ]
        }).on('draw', function(data) {
          if(data.type === 'bar') {
            data.element.attr({
              style: 'stroke-width: 10px'
            });
          }
        });
        
        </script>
        ";

        $z = "<h4>$header - $txt " . $this->datum1 . " bis " . $this->datum2 . "</h4>";


        $x = "<div class='card-box table-responsive' style='height: " . ($size + 200) . "px'>";
        $x .= "<div>";
        $x .= $z;
        $x .= "<a href=\"#custom-width-modal" . $this->id . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\" >Diagrammdaten</a>";
        $x .= "<button type=\"button\" id=\"rastercanvas" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\">Bild erstellen</button>";
        $x .= "<br><hr></div>";
        $h .= "<div id=\"stacked-bar-chart_fids_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "\" class=\"ct-chart ct-chart-bar\" style=\"height: " . $size . "px\"></div>";
        $x .= $h;
        $x .= $this->GetChromPlugIn();
        $z .= "Es wurden insgesamt $sum_anzahl dokumentierte Fertigungsmeldungen im Zeitraum " . $this->datum1 . " bis " . $this->datum2 . " analysiert";
        if (count($this->label) > 0) {
            $i = 0;
            $z .= "<br><table class='table table-striped'>";
            foreach ($this->label as $i => $itemx) {
                $z .= "<tr><td>";
                $z .= $itemx;

                if (count($this->series) > 0) {

                    foreach ($this->series as $y => $itemy) {
                        $z .= "</td><td>";
                        $z .= $itemy['name'];
                        $z .= "</td><td>";
                        $z .= $itemy['data'][$i];

                    }
                    $i++;
                }
                $z .= "</td></tr>";
            }

            $z .= "</table>";
        }
        $x .= asModal($this->id, $z, "", 80, "Diagrammdaten");
        $x .= "<canvas id=\"canvas" . $this->id . "\"></canvas>";
        $x .= "</div>";
        return $x;
    }

    function DiagrammFailureQuantity($gwl = true)
    {

        $sum_anzahl = 0;
        $this->series = array();
        $_SESSION['report'] = 1;
        $_SESSION['report_count']++;
        $size = $_SESSION['report_sizeheight'];
        include("./connection.php");

        $this->Loadallocmaterial();

        if ($_SESSION['report_limit'] > 0) {
            $limitsql = "LIMIT 0," . $_SESSION['report_limit'];
            $l = GetReportLimit("text", $_SESSION['report_limit']);
        } else {
            $limitsql = "";
            $l = "";
        }

        if ($gwl) {
            $gwlsql = " AND fehlermeldungen.gwl>0";
            $d = "gwl";
        } else {
            $gwlsql = "";
            $d = "";
        }
        $data = array();
        $label = array();

        if ($_SESSION['report_material'] > 0) {
            $txt = GetArtikelName("text", $_SESSION['report_material']);
            $all = 1;
            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl 
                    FROM linkfehlerfehlermeldung, fehlermeldungen
                    WHERE linkfehlerfehlermeldung.fmid=fehlermeldungen.fmid 
                    AND fehlermeldungen.fmartikelid ='" . $_SESSION['report_material'] . "'
                    AND fehlermeldungen.fm0 =1
                    AND fehlermeldungen.lokz =0
                    AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                    AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                    $gwlsql
                    ORDER BY anzahl DESC 
                    $limitsql
                    ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                $all = $row_rst['anzahl'];

            }
            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl, fehler.fname 
                    FROM linkfehlerfehlermeldung, `fehler`, fehlermeldungen
                    WHERE linkfehlerfehlermeldung.fid=fehler.fid 
                    AND linkfehlerfehlermeldung.fmid=fehlermeldungen.fmid
                    AND fehlermeldungen.fm0 =1
                    AND fehlermeldungen.lokz =0
                    AND fehlermeldungen.fmartikelid ='" . $_SESSION['report_material'] . "'
                    AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                    AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                    $gwlsql
                    GROUP BY fehler.fid 
                    ORDER BY anzahl DESC
                    $limitsql
                    ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                do {
                    if ($row_rst['anzahl'] > 0 && $all > 0) {
                        $anzahl = $row_rst['anzahl'];
                        $data[] = $anzahl;

                        $p = $anzahl / $all * 100;
                        if ($p < 1) {
                            $desc = round($p, 3) . " % " . $row_rst['fname'] . "($anzahl x)";
                        } else {
                            $desc = round($p, 1) . " % " . $row_rst['fname'] . "($anzahl x)";
                        }
                        $sum_anzahl = $sum_anzahl + $anzahl;
                        $label[] = $desc;
                    } else {
                        $data[] = 0;
                        $label[] = "keine Fehler dokumentiert";
                    }
                } while ($row_rst = $rst->fetch_assoc());
            } else {
                $data[] = 0;
                $label[] = "keine Fehler dokumentiert";
            }
        } elseif ($this->allocmaterial > 0 && $this->rid > 0) {
            $txt = "Gruppe " . $this->data['desc'];

            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl 
                    FROM linkfehlerfehlermeldung,  link_artikelid_rid, fehlermeldungen
                    WHERE linkfehlerfehlermeldung.fmid = fehlermeldungen.fmid 
                    AND link_artikelid_rid.artikelid=fehlermeldungen.fmartikelid
                    AND link_artikelid_rid.rid='" . $this->rid . "'
                    AND fehlermeldungen.fm0 =1
                    AND fehlermeldungen.lokz =0
                    AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                    AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                    $gwlsql
                    ORDER BY anzahl DESC
                    $limitsql
                    ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                $all = $row_rst['anzahl'];

            }
            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl, fehler.fname 
                    FROM linkfehlerfehlermeldung, `fehler`, link_artikelid_rid,fehlermeldungen
                    WHERE linkfehlerfehlermeldung.fid=fehler.fid 
                    AND linkfehlerfehlermeldung.fmid = fehlermeldungen.fmid 
                    AND link_artikelid_rid.artikelid=fehlermeldungen.fmartikelid
                    AND link_artikelid_rid.rid='" . $this->rid . "'
                    AND fehlermeldungen.fm0 =1
                    AND fehlermeldungen.lokz =0
                    AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                    AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                    $gwlsql
                    GROUP BY fehler.fid 
                    ORDER BY anzahl DESC
                    $limitsql
                    ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                do {
                    if ($row_rst['anzahl'] > 0 && $all > 0) {
                        $anzahl = $row_rst['anzahl'];
                        $data[] = $anzahl;

                        $p = $anzahl / $all * 100;
                        if ($p < 1) {
                            $desc = round($p, 3) . " % " . $row_rst['fname'] . "($anzahl x)";
                        } else {
                            $desc = round($p, 1) . " % " . $row_rst['fname'] . "($anzahl x)";
                        }
                        $sum_anzahl = $sum_anzahl + $anzahl;
                        $label[] = $desc;
                    }
                } while ($row_rst = $rst->fetch_assoc());
            } else {
                $data[] = 0;
                $label[] = "keine Fehler dokumentiert";
            }


        } else {

            $all = 1;
            $txt = "alle Artikel";

            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl 
                        FROM linkfehlerfehlermeldung,  fehlermeldungen 
                        WHERE linkfehlerfehlermeldung.fmid = fehlermeldungen.fmid 
                        AND fehlermeldungen.fm0 =1
                        AND fehlermeldungen.lokz =0
                        AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                        AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                        $gwlsql
                        ORDER BY anzahl DESC
                        $limitsql
                        ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                $all = $row_rst['anzahl'];

            }

            $sql = "SELECT count(linkfehlerfehlermeldung.fid) as anzahl, fehler.fname 
                    FROM linkfehlerfehlermeldung, `fehler`, fehlermeldungen 
                    WHERE linkfehlerfehlermeldung.fid =fehler.fid 
                    AND linkfehlerfehlermeldung.fmid = fehlermeldungen.fmid 
                    AND fehlermeldungen.fm1datum >='" . $this->datum1 . "'
                    AND fehlermeldungen.fm1datum <='" . $this->datum2 . "'
                    $gwlsql
                    GROUP BY fehler.fid 
                    ORDER BY anzahl DESC
                    $limitsql
                    ";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                do {
                    if ($row_rst['anzahl'] > 0 && $all > 0) {

                        $anzahl = $row_rst['anzahl'];
                        $data[] = $anzahl;
                        $p = $anzahl / $all * 100;
                        if ($p < 1) {
                            $desc = round($p, 3) . " % " . $row_rst['fname'] . "($anzahl x)";
                        } else {
                            $desc = round($p, 1) . " % " . $row_rst['fname'] . "($anzahl x)";
                        }
                        $sum_anzahl = $sum_anzahl + $anzahl;
                        $label[] = $desc;
                    }
                } while ($row_rst = $rst->fetch_assoc());
            } else {
                $data[] = 0;
                $label[] = "keine Fehler dokumentiert";
            }


        }

        $h = "
        
        <script>
        
        //Pie chart with custom labels

        var data = {
          labels: " . json_encode($label) . ",
          series: " . json_encode($data) . "
        };
        
        var options = {
          labelInterpolationFnc: function(value) {
            return value[0] 
          },
          height:'" . ($size) . "px',
          plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.legend()
          ]
        };
        
        var responsiveOptions = [
          ['screen and (min-width: 800px)', {
            chartPadding: 10,
            labelOffset: 10,
            labelDirection: 'explode',
            labelInterpolationFnc: function(value) {
              return value ;
            }
          }],
          ['screen and (min-width: 1024px)', {
            labelOffset: 80,
            chartPadding: 50
          }]
        ];
        
         var chart" . $this->id . " = new Chartist.Pie('#pie-chart_quantity" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "', data, options, responsiveOptions);

        
        </script>
        ";

        if ($gwl) {
            $z = "<h4>$l Fehlerhäufigkeit innerhalb Gewährleistung " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        } else {
            $z = "<h4>$l Fehlerhäufigkeit " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        }


        $x = "<div class='card-box table-responsive' style='height: " . ($size + 200) . "px'>";
        $x .= "<div>";
        $x .= $z;
        $x .= "<a href=\"#custom-width-modal" . $this->id . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\" >Diagrammdaten</a>";
        $x .= "<button type=\"button\" id=\"rastercanvas" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\">Bild erstellen</button>";
        $x .= "<br><hr></div>";
        $x .= "<div id=\"pie-chart_quantity" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "\" class=\"ct-chart ct-slice-pie\" style=\"height: " . $size . "px\"></div>";
        $x .= $h;
        $x .= $this->GetChromPlugIn();
        $z .= "Es wurden insgesamt $sum_anzahl dokumentierte Fehler im Zeitraum " . $this->datum1 . " bis " . $this->datum2 . " analysiert";
        if (count($label) > 0) {

            $z .= "<br><table class='table table-striped'>";
            foreach ($label as $i => $itemx) {
                $z .= "<tr><td>";
                $z .= $itemx;
                $z .= "</td><td>";
                $z .= $data[$i] . " x aufgetreten";
                $z .= "</td></tr>";
            }

            $z .= "</table>";
        }

        $x .= asModal($this->id, $z, "", 80, "Diagrammdaten");
        $x .= "<canvas id=\"canvas" . $this->id . "\"></canvas>";

        $x .= "</div>";
        return $x;
    }

    function DiagrammQReturnCount($gwl = true)
    {
        $_SESSION['report'] = 1;
        $_SESSION['report_count']++;
        $size = $_SESSION['report_sizeheight'];
        $sum_anzahl = 0;
        $this->series = array();

        $this->Loadallocmaterial();

        if ($gwl) {
            $gwlsql = " AND fehlermeldungen.gwl>0";
            $d = "gwl";
        } else {
            $gwlsql = "";
            $d = "";
        }
        include("./connection.php");

        $StartDate = @strtotime($this->datum1);
        $StopDate = @strtotime($this->datum2);

        $format = GetReportFormat("data", $_SESSION['report_format']);
        if ($_SESSION['report_format'] == 0) $this->label = echoMonth($StartDate, $StopDate);
        if ($_SESSION['report_format'] == 1) $this->label = echoDate($StartDate, $StopDate);

        $this->Loadallocmaterial();

        if ($_SESSION['report_material'] > 0) {
            $txt = GetArtikelName("text", $_SESSION['report_material']);
            $data = array();
            $this->series = array();

            foreach ($this->label as $datum) {

                $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $_SESSION['report_material'] . "'
                AND fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }
                $data[] = $anzahl;


            }

            $this->series[] = array("name" => GetArtikelName("text", $_SESSION['report_material']), "data" => $data);

        } elseif ($this->allocmaterial > 0 && $this->rid > 0) {
            $txt = "Gruppe " . $this->data['desc'];
            $this->series = array();
            foreach ($this->allocmaterial as $i => $item) {
                $data = array();

                foreach ($this->label as $datum) {

                    $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $item['artikelid'] . "'
                AND fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $anzahl = $row_rst['anzahl'];
                        $sum_anzahl = $sum_anzahl + $anzahl;
                    } else {
                        $anzahl = 0;
                    }
                    $data[] = $anzahl;


                }

                $this->series[] = array("name" => GetArtikelName("text", $item['artikelid']), "data" => $data);


            }

        } else {
            $data = array();
            $datagwl = array();
            $txt = "alle Artikel";
            foreach ($this->label as $datum) {
                $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE 
                fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }


                $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE 
                fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                AND fehlermeldungen.gwl>1
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahlgwl = $row_rst['anzahl'];
                    $sum_anzahlgwl = $sum_anzahlgwl + $anzahlgwl;
                } else {
                    $anzahlgwl = 0;
                }

                $data[] = $anzahl;
                $datagwl[] = $anzahlgwl;
            }

            $this->series =
                array(
                    array("name" => "alle Fehlermeldungen (" . $sum_anzahl . ")", "data" => $data),
                    array("name" => "Gewährleistung (" . $sum_anzahlgwl . ")", "data" => $datagwl)
                );
        }


        $h = "
        <script>
        
        //Stacked bar chart

        var chart" . $this->id . " = new Chartist.Bar('#stacked-bar-chart_fmids_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "', {
          labels: " . json_encode($this->label) . ",
          series: " . json_encode($this->series) . "
        }, {
          height:'" . ($size) . "px',
          stackBars: true,
          axisY: {
            labelInterpolationFnc: function(value) {
              return (value) + 'x';
            }
          },
          plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.legend()
          ]
        }).on('draw', function(data) {
          if(data.type === 'bar') {
            data.element.attr({
              style: 'stroke-width: 10px'
            });
          }
        });
        
        </script>
        ";

        $x = "<div class='card-box table-responsive' style='height: " . ($size + 300) . "px'>";
        if ($gwl) {
            $z = "<h4>Retoureneingang innerhalb Gewährleistung " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        } else {
            $z = "<h4>Retoureneingang " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        }

        $x .= "<div>";
        $x .= $z;


        $x .= "<a href=\"#custom-width-modal" . $this->id . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\" >Diagrammdaten</a>";
        $x .= "<button type=\"button\" id=\"rastercanvas" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\">Bild erstellen</button>";
        $x .= "<br><hr></div>";
        $h .= "<div id='stacked-bar-chart_fmids_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "' class=\"ct-chart ct-chart-bar\" style='height: " . $size . "px'></div>";
        $x .= $h;
        $x .= $this->GetChromPlugIn();

        $z .= "Es wurden insgesamt $sum_anzahl dokumentierte Retouren im Zeitraum " . $this->datum1 . " bis " . $this->datum2 . " analysiert";
        if (count($this->label) > 0) {
            $i = 0;
            $z .= "<br><table class='table table-striped'>";
            foreach ($this->label as $i => $itemx) {
                $z .= "<tr><td>";
                $z .= $itemx;

                if (count($this->series) > 0) {

                    foreach ($this->series as $y => $itemy) {
                        $z .= "</td><td>";
                        $z .= $itemy['name'];
                        $z .= "</td><td>";
                        $z .= $itemy['data'][$i];

                    }
                    $i++;
                }
                $z .= "</td></tr>";
            }

            $z .= "</table>";
        }


        $x .= asModal($this->id, $z, "", 80, "Diagrammdaten");
        $x .= "<canvas id=\"canvas" . $this->id . "\"></canvas>";

        $x .= "</div>";
        return $x;
    }

    function DiagrammQReturnTimelinie($gwl, $var = 0)
    {
        $_SESSION['report'] = 1;
        $_SESSION['report_count']++;
        $size = $_SESSION['report_sizeheight'];
        $sum_anzahl = 0;
        $this->series = array();

        $this->Loadallocmaterial();

        if ($gwl) {
            $gwlsql = " AND fehlermeldungen.gwl>0";
            $d = "gwl";
        } else {
            $gwlsql = "";
            $d = "";
        }


        switch ($var) {
            case 0:
                {
                    $datumsql = " DATEDIFF(fehlermeldungen.fm3datum ,fehlermeldungen.fm1datum )";
                    $gwlsql .= " AND fehlermeldungen.fm1=1";
                    $gwlsql .= " AND fehlermeldungen.fm2=1";
                    $gwlsql .= " AND fehlermeldungen.fm3=1";
                    $headertxt = "Kostenklärungszeit";
                    break;
                }
            case 1:
                {
                    $datumsql = " DATEDIFF(fehlermeldungen.fm5datum ,fehlermeldungen.fm3datum )";
                    $gwlsql .= " AND fehlermeldungen.fm1=1";
                    $gwlsql .= " AND fehlermeldungen.fm2=1";
                    $gwlsql .= " AND fehlermeldungen.fm3=1";
                    $gwlsql .= " AND fehlermeldungen.fm4=1";

                    $headertxt = "Reparaturzeit";
                    break;
                }
            case 2:
                {
                    $datumsql = " DATEDIFF(fehlermeldungen.fm5datum ,fehlermeldungen.fm1datum )";
                    $gwlsql .= " AND fehlermeldungen.fm1=1";
                    $gwlsql .= " AND fehlermeldungen.fm2=1";
                    $gwlsql .= " AND fehlermeldungen.fm3=1";
                    $gwlsql .= " AND fehlermeldungen.fm4=1";

                    $headertxt = "Gesamtbearbeitungszeit";
                    break;
                }
        }


        include("./connection.php");

        $StartDate = @strtotime($this->datum1);
        $StopDate = @strtotime($this->datum2);

        $format = GetReportFormat("data", $_SESSION['report_format']);
        if ($_SESSION['report_format'] == 0) $this->label = echoMonth($StartDate, $StopDate);
        if ($_SESSION['report_format'] == 1) $this->label = echoDate($StartDate, $StopDate);

        $this->Loadallocmaterial();

        if ($_SESSION['report_material'] > 0) {
            $txt = GetArtikelName("text", $_SESSION['report_material']);
            $data = array();
            $this->series = array();

            foreach ($this->label as $datum) {

                $sql = "SELECT AVG( $datumsql ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $_SESSION['report_material'] . "'
                AND fehlermeldungen.fm0 =1
             
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = round($row_rst['anzahl'], 1);
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }
                $data[] = $anzahl;


            }

            $this->series[] = array("name" => GetArtikelName("text", $_SESSION['report_material']), "data" => $data);

        } elseif ($this->allocmaterial > 0 && $this->rid > 0) {
            $txt = "Gruppe " . $this->data['desc'];
            $this->series = array();
            foreach ($this->allocmaterial as $i => $item) {
                $data = array();

                foreach ($this->label as $datum) {

                    $sql = "SELECT AVG( $datumsql ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $item['artikelid'] . "'
                AND fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $anzahl = round($row_rst['anzahl'], 1);
                        $sum_anzahl = $sum_anzahl + $anzahl;
                    } else {
                        $anzahl = 0;
                    }

                    $data[] = abs($anzahl);

                }

                $this->series[] = array("name" => GetArtikelName("text", $item['artikelid']), "data" => $data);


            }

        } else {
            $data = array();
            $datagwl = array();
            $txt = "alle Artikel";
            foreach ($this->label as $datum) {
                $sql = "SELECT AVG( $datumsql ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE 
                fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = round($row_rst['anzahl'], 1);
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }


                $sql = "SELECT AVG( $datumsql ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE 
                fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fm1datum,  '" . $format . "' ) = '" . $datum . "'
                AND fehlermeldungen.gwl>1
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahlgwl = round($row_rst['anzahl'], 1);
                    $sum_anzahlgwl = $sum_anzahlgwl + $anzahlgwl;
                } else {
                    $anzahlgwl = 0;
                }

                $data[] = abs($anzahl);
                $datagwl[] = abs($anzahlgwl);
            }

            $this->series =
                array(
                    array("name" => "alle Fehlermeldungen (" . $sum_anzahl . ")", "data" => $data),
                    array("name" => "Gewährleistung (" . $sum_anzahlgwl . ")", "data" => $datagwl)
                );
        }


        $h = "
        <script>
        
        //Stacked bar chart

        var chart" . $this->id . " = new Chartist.Bar('#stacked-bar-chart_fmidtimeline_" . $d . "_" . $this->rid . "_" . $this->id . "', {
          labels: " . json_encode($this->label) . ",
          series: " . json_encode($this->series) . "
        }, {
          height:'" . ($size) . "px',
          stackBars: true,
          axisY: {
            labelInterpolationFnc: function(value) {
              return (value) + ' Tage';
            }
          },
          plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.legend()
          ]
        }).on('draw', function(data) {
          if(data.type === 'bar') {
            data.element.attr({
              style: 'stroke-width: 10px'
            });
          }
        });
        
        </script>
        ";

        $x = "<div class='card-box table-responsive' style='height: " . ($size + 300) . "px'>";
        if ($gwl) {
            $z = "<h4>" . $headertxt . " nur  Gewährleistungsfälle " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        } else {
            $z = "<h4>" . $headertxt . " " . $txt . " " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        }

        $x .= "<div>";
        $x .= $z;


        $x .= "<a href=\"#custom-width-modal" . $this->id . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\" >Diagrammdaten</a>";
        $x .= "<button type=\"button\" id=\"rastercanvas" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\">Bild erstellen</button>";
        $x .= "<br><hr></div>";
        $h .= "<div id='stacked-bar-chart_fmidtimeline_" . $d . "_" . $this->rid . "_" . $this->id . "' class=\"ct-chart ct-chart-bar\" style='height: " . $size . "px'></div>";
        $x .= $h;
        $x .= $this->GetChromPlugIn();

        $z .= "Es wurden insgesamt $sum_anzahl dokumentierte Retouren im Zeitraum " . $this->datum1 . " bis " . $this->datum2 . " analysiert";
        if (count($this->label) > 0) {
            $i = 0;
            $z .= "<br><table class='table table-striped'>";
            foreach ($this->label as $i => $itemx) {
                $z .= "<tr><td>";
                $z .= $itemx;

                if (count($this->series) > 0) {

                    foreach ($this->series as $y => $itemy) {
                        $z .= "</td><td>";
                        $z .= $itemy['name'];
                        $z .= "</td><td>";
                        $z .= $itemy['data'][$i];

                    }
                    $i++;
                }
                $z .= "</td></tr>";
            }

            $z .= "</table>";
        }


        $x .= asModal($this->id, $z, "", 80, "Diagrammdaten");
        $x .= "<canvas id=\"canvas" . $this->id . "\"></canvas>";

        $x .= "</div>";
        return $x;
    }

    function DiagrammQuailty($gwl = true)
    {


        $this->series = array();
        $old = "";
        $_SESSION['report'] = 1;
        $_SESSION['report_count']++;
        $size = $_SESSION['report_sizeheight'];
        $sum_anzahl = 0;
        $sum_anzahl_d = 0;
        $anzahl = 0;
        $anzahl_d = 0;
        include("./connection.php");



        if ($gwl) {
            $gwlsql = " AND fehlermeldungen.gwl>0";
            $d = "gwl";
            $desc = "(bezogen auf Gewährleistungsfehlermeldungen)";
        } else {
            $gwlsql = "";
            $d = "";
            $desc = "(bezogen auf alle Fehlermeldungen)";
        }

        $StartDate = @strtotime($this->datum1);
        $StopDate = @strtotime($this->datum2);

        $format = GetReportFormat("data", $_SESSION['report_format']);

        if (strlen($format) == 0) $format = "%Y-%m";
        if ($_SESSION['report_format'] == 0) $this->label = echoMonth($StartDate, $StopDate);
        if ($_SESSION['report_format'] == 1) $this->label = echoDate($StartDate, $StopDate);

        $this->Loadallocmaterial();

        if ($_SESSION['report_material'] > 0) {
            $txt = GetArtikelName("text", $_SESSION['report_material']);
            $data = array();
            $this->series = array();


            foreach ($this->label as $datum) {

                $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE fertigungsmeldungen.fartikelid = " . $_SESSION['report_material'] . " 
                AND fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
              
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 1;
                }



                $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $_SESSION['report_material'] . "'
                AND fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) = '" . $datum . "'
               
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl_d = $row_rst['anzahl'];
                    $sum_anzahl_d = $sum_anzahl_d + $anzahl_d;
                } else {
                    $anzahl_d = 0;
                }

                if ($anzahl <= 0) $anzahl = 1;
                $qlage = 100 - ($anzahl_d / $anzahl * 100);
                if ($qlage < 0) $qlage = 0;


                if ($old == "") {
                    $trend[] = $qlage;
                } else {
                    $trend[] = round(($old + $qlage) / 2, 4);
                }

                $data[] = round($qlage, 6);
                $old = $qlage;
            }


        } elseif ($this->allocmaterial > 0 && $this->rid > 0) {
            $txt = "Gruppe " . $this->data['desc'];
            $this->series = array();
            foreach ($this->allocmaterial as $i => $item) {
                $data = array();

                foreach ($this->label as $datum) {

                    $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE fertigungsmeldungen.fartikelid = " . $item['artikelid'] . " 
                AND fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
                $fidtypesql
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $anzahl = $row_rst['anzahl'];
                        $sum_anzahl = $sum_anzahl + $anzahl;
                        $sum_a = $sum_a + $anzahl;
                    } else {
                        $anzahl = 0;
                    }


                    $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE fehlermeldungen.fmartikelid = '" . $item['artikelid'] . "'
                AND fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $anzahl_d = $row_rst['anzahl'];
                        $sum_anzahl_d = $sum_anzahl_d + $anzahl_d;
                        $sum_a_d = $sum_a_d + $anzahl_d;
                    } else {
                        $anzahl_d = 0;
                    }


                } //material
                if ($sum_a <= 0) $sum_a = 1;

                $qlage = 100 - ($sum_a_d / $sum_a * 100);
                if ($qlage < 0) $qlage = 0;


                if ($old == "") {
                    $trend[] = $qlage;
                } else {
                    $trend[] = round(($old + $qlage) / 2, 2);
                }

                $data[] = round($qlage, 2);
                $old = $qlage;

            } //timelinie


        } else {
            $txt = "(alle Artikel)";
            $data = array();
            $trend = array();
            $average = array();
            $this->series = array();
            foreach ($this->label as $datum) {
                $sql = "SELECT COUNT( fertigungsmeldungen.fid ) AS anzahl,
                DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) AS MONTH 
                FROM  fertigungsmeldungen
                WHERE 
                fertigungsmeldungen.ffrei =1
                AND fertigungsmeldungen.lokz =0
                $fidtypesql
                AND DATE_FORMAT( fertigungsmeldungen.fdatum,  '" . $format . "' ) = '" . $datum . "'
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl = $row_rst['anzahl'];
                    $sum_anzahl = $sum_anzahl + $anzahl;
                } else {
                    $anzahl = 0;
                }
                if ($anzahl == 0) $anzahl = 1;

                $sql = "SELECT COUNT( fehlermeldungen.fmid ) AS anzahl,
                DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) AS MONTH 
                FROM  fehlermeldungen
                WHERE 
                fehlermeldungen.fm0 =1
                AND fehlermeldungen.lokz =0
                AND DATE_FORMAT( fehlermeldungen.fmfd,  '" . $format . "' ) = '" . $datum . "'
                $gwlsql
                
                GROUP BY MONTH 
                LIMIT 0 , 1;";

                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $anzahl_d = $row_rst['anzahl'];
                    $sum_anzahl_d = $sum_anzahl_d + $anzahl_d;
                } else {
                    $anzahl_d = 0;
                }

                $qlage = 100 - ($anzahl_d / $anzahl * 100);
                if ($qlage < 0) $qlage = 0;


                if ($old == "") {
                    $trend[] = $qlage;
                } else {
                    $trend[] = round(($old + $qlage) / 2, 2);
                }

                $data[] = round($qlage, 2);
                $old = $qlage;
            }


        }


        if ($sum_anzahl == 0) $sum_anzahl = 1;
        $qlage = round((100 - ($sum_anzahl_d / $sum_anzahl) * 100), 2);
        if ($qlage < 0) $qlage = 0;

        $regparam = linear_regression_date($this->label, $data);
        for ($i = 0; $i <= (count($data) + 1); $i++) {
            $average[] = $qlage;
            $regression[] = $regparam['slope'] * $i + $regparam['intercept'];
        }


        $this->series = array(
            array("name" => "Q-Lage Mittelwert im Zeitraum: $qlage", "data" => $average),
            array("name" => "Q-Lage", "data" => $data),
            array("name" => "Trend", "data" => $trend),
            array("name" => "erwartete Q-Lage:" . round($regression[($i - 1)], 1) . " lin. Regression y = " . round($regparam['slope'], 3) . " x + " . round($regparam['intercept'], 1), "data" => $regression),
        );


        $h = "
        <script>
        
       var chart" . $this->id . " = new Chartist.Line('#stacked-bar-chart_q_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "', {
          labels: " . json_encode($this->label) . ",
          series: " . json_encode($this->series) . "
  
        }, {
          
         
          height: '" . ($size) . "px',
          lineSmooth: Chartist.Interpolation.simple({
            divisor: 4
          }),
          fullWidth: true,
          chartPadding: {
            right: 0
          },
          high:100,
          low: 0,
          showArea: true,
          plugins: [
            Chartist.plugins.tooltip(),
            Chartist.plugins.legend()
          ]
        }); 
       
        </script>
        ";


        $z = "";

        $x = "<div class='card-box table-responsive' style='height: " . ($size + 300) . "px'>";
        $z = "<h4>Qualitäts-Lage $desc - $txt - " . $this->datum1 . " bis " . $this->datum2 . "</h4>";
        $x .= $z;
        $x .= $h;
        $x .= $this->GetChromPlugIn();
        $x .= "<div>";
        $x .= " <a href=\"#custom-width-modal" . $this->id . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\" >Diagrammdaten</a>";
        $x .= "<button type=\"button\" id=\"rastercanvas" . $this->id . "\" class=\"btn btn-sm btn-custom waves-effect waves-light pull-right\">Bild erstellen</button>";
        $x .= "<br><hr></div>";
        $x .= "<div id=\"stacked-bar-chart_q_" . $d . "_" . $this->rid . "_" . $_SESSION['report_count'] . "\" class=\"ct-chart ct-chart-bar\" style=\"height: " . ($size * 1.5) . "px\"></div>";

        $z .= "Q-Lage ist das Verhältnis von gesamt produzierter Mengen ($sum_anzahl) zu Retourenmenge ($sum_anzahl_d) innerhalb der Gewährleistung im Zeitraum " . $this->datum1 . " bis " . $this->datum2 . " analysiert";
        $z .= "<br>Durchschnitts- Q-Lage : " . $qlage;

        if (count($this->label) > 0) {
            $i = 0;
            $z .= "<br><table class='table table-striped'>";
            foreach ($this->label as $i => $itemx) {
                $z .= "<tr><td>";
                $z .= $itemx;

                if (count($this->series) > 0) {

                    foreach ($this->series as $y => $itemy) {
                        $z .= "</td><td>";
                        $z .= $itemy['name'];
                        $z .= "</td><td>";
                        $z .= $itemy['data'][$i];

                    }
                    $i++;
                }
                $z .= "</td></tr>";
            }

            $z .= "</table>";
        }

        $x .= asModal($this->id, $z, "", 80, "Diagrammdaten");

        $x .= "<canvas id=\"canvas" . $this->id . "\"></canvas>";

        $x .= "</div>";
        return $x;
    }


    function GetChromPlugIn()
    {

        $h = "<script>   
        chart" . $this->id . ".on('created', function(data) {
            inlineCSStoSVG" . $this->id . "(chart" . $this->id . ".container.id);
        });
        document.getElementById(\"rastercanvas" . $this->id . "\").addEventListener('click', function() {
    exportToCanvas" . $this->id . "(chart" . $this->id . ".container.id);
  });


function inlineCSStoSVG" . $this->id . "(id) {
  var nodes = document.querySelectorAll(\"#\" + id + \" *\");
  for (var i = 0; i < nodes.length; ++i) {
    var elemCSS = window.getComputedStyle(nodes[i], null);
    nodes[i].removeAttribute('xmlns');
    nodes[i].style.fill = elemCSS.fill;
    nodes[i].style.fillOpacity = elemCSS.fillOpacity;
    nodes[i].style.stroke = elemCSS.stroke;
    nodes[i].style.strokeLinecap = elemCSS.strokeLinecap;
    nodes[i].style.strokeDasharray = elemCSS.strokeDasharray;
    nodes[i].style.strokeWidth = elemCSS.strokeWidth;
    nodes[i].style.fontSize = elemCSS.fontSize;
    nodes[i].style.fontFamily = elemCSS.fontFamily;
    //Solution to embbed HTML in foreignObject https://stackoverflow.com/a/37124551
    if (nodes[i].nodeName === \"SPAN\") {
      nodes[i].setAttribute(\"xmlns\", \"http://www.w3.org/1999/xhtml\");
    }
  }
}

function exportToCanvas" . $this->id . "(id) {

  var svgElem = document.querySelector(\"#\" + id + \" svg\");
  svgElem.setAttribute(\"xmlns\", \"http://www.w3.org/2000/svg\");

  var canvas = document.getElementById('canvas" . $this->id . "');
  var ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  ctx.canvas.height = svgElem.clientHeight;
  ctx.canvas.width = svgElem.clientWidth;

  var DOMURL = window.URL || window.webkitURL || window;
  var img = new Image();
  img.crossOrigin = \"Anonymous\";
  var blob = undefined;
 
  try {
    blob = new Blob([svgElem.outerHTML], {
      type: \"image/svg+xml;charset=utf-8\"
    });
  }
  catch (e) {
    if (e.name == \"InvalidStateError\") {
      var bb = new MSBlobBuilder();
      bb.append(svgElem.outerHTML);
      blob = bb.getBlob(\"image/svg+xml;charset=utf-8\");
    }
    else {
      throw e; 
    }
  }
  var url = DOMURL.createObjectURL(blob);
  img.onload = function() {
    ctx.drawImage(img, 0, 0);
    DOMURL.revokeObjectURL(url);
  }
  img.src = url;

}
        
        </script>
        ";

        return $h;
    }
}