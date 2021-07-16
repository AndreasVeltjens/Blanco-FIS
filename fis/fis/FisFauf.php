<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-02-18
 * Time: 21:24
 */

class FisFauf
{

    public $faufid = 0;
    public $data = array();
    public $dataall = array();

    public $faufstate = 0;
    public $fauftype = 0;
    public $limit = 100;
    public $lokz = 0;
    public $datum1 = "0000-00-00";
    public $datum2 = "0000-00-00";
    public $search = "";

    function __construct($i)
    {
        $this->faufid = $i;
        if ($i > 0) {
            $this->LoadData();
        }
    }

    function LoadData()
    {
        include("./connection.php");
        $this->data = array();
        $sql = "SELECT * FROM fertigungsauftrag WHERE fertigungsauftrag.faufid='$this->faufid' ";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data = $row_rst;

            if (strlen($this->data['profil']) == 0) $this->data['profil'] = "{}";
            if (strlen($this->data['config']) == 0) $this->data['config'] = "{}";
            if (strlen($this->data['process']) == 0) $this->data['process'] = "{}";

        } else {
            $this->data = array();
            AddSessionMessage("error", "Fertigungsauftrag <code> FAUF $this->faufid</code> nicht bekannt.", "Datenbankfehler");
        }


    }

    function LoadAllData()
    {
        include("./connection.php");
        $s = "";
        if ($this->faufstate > 0) {
            $s .= "AND fertigungsauftrag.faufstate='" . $this->faufstate . "' ";
        }
        if ($this->fauftype > 0) {
            $s .= "AND fertigungsauftrag.fauftype>'" . $this->fauftype . "' ";
        }
        if ($this->lokz == 0) {
            $s .= "AND fertigungsauftrag.lokz='0' ";
        }
        if (strlen($this->search) > 0) {
            $s .= " AND fertigungsauftrag.faufid='" . $this->search . "' ";
        }


        $this->dataall = array();

        $sql = "SELECT * FROM fertigungsauftrag LEFT JOIN artikeldaten ON
               fertigungsauftrag.artikelid = artikeldaten.artikelid
               WHERE fertigungsauftrag.faufid>0
              
              $s
              
              ORDER BY fertigungsauftrag.faufid DESC LIMIT 0," . $this->limit;

        //$_SESSION['sq']=$sql;
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->dataall[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->data = array();
            AddSessionMessage("info", "keine Fertigungsaufträge zur Auswahl vorhanden.", "Abfrageergebnis");
        }
    }

    function __toString()
    {
        return $this->faufid;
    }


    function LoadMaterialProcessData()
    {
        $h = "";

        $v = new FisVersion($this->data['version'], $this->data['artikelid']);
        include("./connection.php");
        $updateSQL = sprintf("
                UPDATE fertigungsauftrag SET config=%s,process=%s,profil=%s
                WHERE faufid='$this->faufid' ",
            GetSQLValueString(utf8_decode($v->data['configprofil']), "text"),
            GetSQLValueString(utf8_decode($v->data['process']), "text"),
            GetSQLValueString(utf8_decode($v->data['print']), "text")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['sql'] = $updateSQL;
            AddSessionMessage("error", "Laden der Standard- Fertigungsauftrageinstellungen von  <code> ID" . $this->faufid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");

        } else {

            $txt = "Standard-Fertigungsauftrageinstellungen von  <code> ID" . $this->faufid . "</code> wurde geladen.";
            AddSessionMessage("success", $txt, "Benachrichtigung");
            $w = new FisWorkflow($txt, "faufid", $this->faufid);
            $w->Add();
        }

        return $h;
    }


    function Save()
    {

        if (isset($_REQUEST['new_fauf_add'])) {
            $this->Add();
        } else {
            if ($_REQUEST['lokz' . $this->faufid] == "on") {
                $_REQUEST['lokz' . $this->faufid] = 1;
            } else {
                $_REQUEST['lokz' . $this->faufid] = 0;
            }
            include("./connection.php");
            $updateSQL = sprintf("
                UPDATE fertigungsauftrag 
                SET artikelid=%s,version=%s,datum1=%s, datum2=%s, faufnotes=%s,
                fauftype=%s,faufstate=%s,config=%s,process=%s,profil=%s,lokz=%s,
                customerid=%s, orderid=%s, orderpos=%s
              
                WHERE faufid='$this->faufid' ",
                GetSQLValueString($_REQUEST['artikelid' . $this->faufid], "int"),
                GetSQLValueString($_REQUEST['version' . $this->faufid], "text"),
                GetSQLValueString(strtotime($_REQUEST['datum1' . $this->faufid]), "int"),
                GetSQLValueString(strtotime($_REQUEST['datum2' . $this->faufid]), "int"),
                GetSQLValueString($_REQUEST['faufnotes' . $this->faufid], "text"),
                GetSQLValueString($_REQUEST['fauftype' . $this->faufid], "int"),
                GetSQLValueString($_REQUEST['faufstate' . $this->faufid], "int"),
                GetSQLValueString(utf8_decode($_REQUEST['config' . $this->faufid]), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['process' . $this->faufid]), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['profil' . $this->faufid]), "text"),
                GetSQLValueString($_REQUEST['lokz' . $this->faufid], "int"),
                GetSQLValueString(utf8_decode($_REQUEST['customerid' . $this->faufid]), "int"),
                GetSQLValueString(utf8_decode($_REQUEST['orderid' . $this->faufid]), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['orderpos' . $this->faufid]), "text")
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['sql'] = $updateSQL;
                AddSessionMessage("error", "Änderung vom Fertigungsauftrag von  <code> ID" . $this->faufid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");

            } else {

                $txt = "Fertigungsauftrag von  <code> ID" . $this->faufid . "</code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Benachrichtigung");
                $w = new FisWorkflow($txt, "faufid", $this->faufid);
                $w->Add();

                $d = new FisDocument("Dokumente zum Fertigungsauftrag", "faufid", $this->faufid);
                $d->AddNewDocument();
            }


        }



    }

    function Add()
    {
        include("./connection.php");
        $updateSQL = sprintf("INSERT INTO  fertigungsauftrag (version,artikelid,datum1,datum2) 
                                                VALUES (%s, %s,%s, %s)",

            GetSQLValueString($_REQUEST['version0'], "text"),
            GetSQLValueString($_REQUEST['artikelid0'], "int"),
            GetSQLValueString(strtotime($_REQUEST['datum10']), "int"),
            GetSQLValueString(strtotime($_REQUEST['datum20']), "int")

        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['sql'] = $updateSQL;
            AddSessionMessage("error", "Fertigungsauftrag  <code> " . $_REQUEST['version0'] . "</code> und ArtikelID <code> " . $_REQUEST['artikelid0'] . "</code> konnte nicht angelegt werden.", "Fehlermeldung DB-Error");
        } else {

            $txt = "Fertigungsauftrag  <code> " . $_REQUEST['version0'] . "</code> wurde mit Vorlage <code> " . $_SESSION['version'] . " </code>  für Material " . GetArtikelName("text", $_REQUEST['artikelid0']) . "angelegt.";
            AddSessionMessage("success", $txt, "Fertigungsauftrag gespeichert");
            $this->faufid = mysqli_insert_id($mysqli);
            $_SESSION['faufid'] = $this->faufid;
            $_GET['faufid'] = $this->faufid;

            $w = new FisWorkflow($txt, "faufid", $this->faufid);
            $w->Add();
        }
    }

    function GetSearchString()
    {
        $s = utf8_decode($this->search);
        return $s;
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

    function SetNoFreeValueAllowed($s = 0)
    {
        $this->no_free_search_value_allowed = $s;
        return $this;
    }

    function ShowHtmlAllAsTable($status = "1", $limit = 50, $search = "", $no_free_search_value_allowed = 0)
    {
        $this->dataall = array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->LoadAllData();

        $h = asModal(1000, "<div id='preview'></div>", "", 60, "Vorschau");
        $h .= "<h4 class=\"header-title m-t-0 m-b-30\">Fertigungsaufträge Suchbegriff:(" . $this->search . ") </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'] .= "
            
            var table  = $(\"#datatable" . $_SESSION['datatable'] . "\").DataTable({
                            
                    
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
                            0 !== $(\"#datatable" . $_SESSION['datatable'] . "\").length && table
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

            $h .= " <table id=\"datatable" . $_SESSION['datatable'] . "\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>Fertigungsauftrag</th>
                            <th>Material</th>
                            <th>Nummer</th>
                            <th>Version</th>
                            <th>Status</th>
                           
                            <th>Auftragstyp</th>
                            <th>Start</th>
                            <th>Ende</th>
                             <th>L</th>
                             <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";

            foreach ($this->dataall as $d => $i) {
                if ($i['faufid'] > 0) {

                    $link = "<a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=72&faufid=" . $i['faufid'] . "&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'>Fertigungsauftrag</a>";

                    $h .= "<tr>
                            <td>" . $i['faufid'] . "</td>
                            <td>" . GetArtikelName("text", $i['artikelid']) . "</td>
                            <td>" . $i['Nummer'] . "</td>
                            <td>" . $i['version'] . "</td>
                            <td>" . GetFaufStates("text", $i['faufstate']) . "</td>
                            <td>" . GetFaufType("text", $i['fauftype']) . "</td>
                            <td>" . date("Y-m-d", $i['datum1']) . "</td>
                            <td>" . date("Y-m-d", $i['datum2']) . "</td>
                             <td>" . $i['lokz'] . "</td>
                            ";

                    $h .= " <td>" . "$link" . "</td>";
                    $h .= "</tr>";

                }
            }
            $h .= "</tbody></table>";
        } else {
            $h .= "keine Ereignisdaten zur Auswahl gefunden.";
        }
        return $h;
    }

    function SetSearchString($s = "")
    {
        $this->search = utf8_encode($s);
        return $this;
    }



    function EditPropertyAsTable()
    {

        $h = "<div class=\"table-responsive\"><input type='hidden' name='faufid' id='faufid' value='" . $this->faufid . "'>
                <table class=\"table table-striped\">
                  <tbody><tr>";

        $h .= "<td colspan='2'>" . asSelect2Box("Artikel/Material", "artikelid" . $this->faufid, GetArtikelName("select2", $this->data['artikelid']), 9) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td colspan='2' >" . asTextField("Version", "version" . $this->faufid, $this->data['version'], 9) . "</td>";
        $h .= "</tr><tr>";

        $h .= "<td width='60%'>" . asTextField("Kundenauftrag", "orderid" . $this->faufid, $this->data['orderid'], 7) . "</td>";
        $h .= "<td>" . asTextField("Pos.", "orderpos" . $this->faufid, $this->data['orderpos'], 7) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td colspan='2' >" . asSelect2Box("Kunde", "customerid" . $this->faufid, GetCustomerName("select2", $this->data['customerid']), 9) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td width='60%'>" . asSelectBox("Status", "faufstate" . $this->faufid, GetFaufStates("select", $this->data['faufstate']), 7) . "</td>";
        $h .= "<td >" . asSelectBox("Typ", "fauftype" . $this->faufid, GetFaufType("select", $this->data['fauftype']), 8) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td width='60%' >" . asTextField("Start", "datum1" . $this->faufid, date("Y-m-d", $this->data['datum1']), 7) . "</td>";
        $h .= "<td >" . asTextField("Ende", "datum2" . $this->faufid, date("Y-m-d", $this->data['datum2']), 8) . "</td>";


        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asTextarea("Bemerkungen", "faufnotes" . $this->faufid, $this->data['faufnotes'], 3, 9, "") . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td colspan='2'>" . asSwitchery("Löschenvormerkung setzen", "lokz" . $this->faufid, $this->data['lokz'], "FF0000", 7, "") . "</td>";
        $h .= "</tr><tr>";


        if ($this->faufid > 0) {
            $h .= "</tr><tr>";
            $h .= "<td colspan='2'>";
            $d = new FisDocument("Dokument zum Fertigungsauftrag", "faufid", $this->faufid, FIS_ID);
            $h .= $d->ShowHtmlInputField();
            $h .= "</td>";
        }

        $h .= "</tr></tbody></table>";

        $h .= "</div>";

        $_SESSION['endscripts'] .= "
             \$('#datum1" . $this->faufid . "').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: \"yyyy-mm-dd\",
                   
                });
             \$('#datum2" . $this->faufid . "').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
            ";

        return $h;
    }

    function ConfigAsTable()
    {
        $v = new FisVersion($this->data['version'], $this->data['artikelid']);
        $_SESSION['material'] = $this->data['artikelid'];
        $v->GetProcessasTable();
        return $v->GetWorkplan();
    }

    function ProfilAsTable()
    {

        try {
            if (is_array(json_encode($this->data['profil']))) {

                if (count(json_decode($this->data['profil'])) > 0) {
                    $h = print_r(json_decode($this->data['profil']), true);
                    $h = "Konfiguration gefunden";
                } else {
                    $h = "keine Konfiguration gefunden";
                }
            } else {
                $h = "keine Konfiguration fehlerhaft";
            }
        } catch (Exception $e) {
            $h = "keine Konfiguration gültig " . $e;
        }

        return $h;
    }

    function PlanCostsAsTable()
    {
        $h = "keine Plankosten gefunden";
        return $h;
    }

    function ActualCostsAsTable()
    {
        $h = "keine Ist-Kosten gefunden";
        return $h;
    }


    function GetProcessList()
    {
        $h = asJSLoader("div_process", "Arbeitsplan laden...", "data.php", "datatype=process&material=" . $this->data['artikelid'] . "&version=" . urlencode($this->data['version']));
        return $h;
    }


    function EditProcess()
    {
        $h = "<a name='process'></a>" . asTextarea("Arbeitsplan und Stückliste", "process" . $this->faufid, $this->data['process'], 3, 7, "", " onChange=\"SetJson3();\" ")
            . "<a href='#process' class='btn btn-success btn-sm' onclick='ProveProcessSettings();'>Arbeitsplaneinstellungen prüfen</a><div id='jsoneditor3'></div>
        ";
        return $h;
    }

    function EditConfig()
    {
        $h = "<a name='configprofil'></a>" . asTextarea("Konfiguration", "config" . $this->faufid, $this->data['config'], 3, 7, "", " onChange=\"SetJson2();\" ")
            . "<a href='#configprofil' class='btn btn-success btn-sm' onclick='ProveConfigSettings();'>Konfigurationseinstellungen prüfen</a><div id='jsoneditor2'></div>
        ";
        return $h;
    }

    function EditProfil()
    {
        $h = "<a name='print'></a>" . asTextarea("Druckereinstellungen", "profil" . $this->faufid, $this->data['profil'], 3, 7, "", " onChange=\"SetJson1();\" ")
            . "<a href='#print' class='btn btn-success btn-sm' onclick='ProvePrintSettings();'>Druckereinstellungen prüfen</a><div id='jsoneditor1' ></div>
        ";
        return $h;
    }

    function SetFaufDefaults()
    {
        $this->data['datum1'] = time() + (1 * 24 * 60 * 60);
        $this->data['datum2'] = time() + (2 * 24 * 60 * 60);
        $this->data['fauftype'] = 1;
        $this->data['faufstate'] = 1;
        $this->faufid = 0;
        return $this;
    }

    function JsonEditor()
    {

        $h = "<script>
             // create the editor
              var container1 = document.getElementById('jsoneditor1');
              var options1 = {};
              var editor1 = new JSONEditor(container1, options1);
              // set json
             
                var json1 =";
        $h .= str_replace(",", "," . chr(13), ($this->data['profil']));
        $h .= "
        ;
        editor1.set(json1);
        
              var container2 = document.getElementById('jsoneditor2');
              var options2 = {};
              var editor2 = new JSONEditor(container2, options2);
             
             
              var json2 =";
        $h .= str_replace(",", "," . chr(13), json_encode(json_decode($this->data['config'], true)));
        $h .= "
        ;
        editor2.set(json2);
        
        var container3 = document.getElementById('jsoneditor3');
              var options3 = {};
              var editor3 = new JSONEditor(container3, options3);
             
             
              var json3 =";
        $h .= str_replace(",", "," . chr(13), json_encode(json_decode($this->data['process'], true)));
        $h .= "
        ;
        editor3.set(json3);
      
        function ProvePrintSettings() {
                var json = editor1.get();
                document.getElementById('profil" . $this->faufid . "').value=JSON.stringify(json, null, 2);
                alert(JSON.stringify(json, null, 2));
              };
              
         function ProveConfigSettings() {
                var jsonx2 = editor2.get();
                document.getElementById('config" . $this->faufid . "').value=JSON.stringify(jsonx2, null, 2);
                alert(JSON.stringify(jsonx2, null, 2));
              };
              
          function ProveProcessSettings() {
            var jsonx3 = editor3.get();
            document.getElementById('process" . $this->faufid . "').value=JSON.stringify(jsonx3, null, 2);
            alert(JSON.stringify(jsonx3, null, 2));
          };
          
         function ProveJsonSettings() {
            var json = editor1.get();
            document.getElementById('profil" . $this->faufid . "').value=JSON.stringify(json, null, 2);
            alert('Druckeinstellungen \\n' + JSON.stringify(json, null, 2));
            
            var jsonx2 = editor2.get();
            document.getElementById('config" . $this->faufid . "').value=JSON.stringify(jsonx2, null, 2);
            alert('Konfigurationsprofil \\n' + JSON.stringify(jsonx2, null, 2));
            
             var jsonx3 = editor3.get();
            document.getElementById('process" . $this->faufid . "').value=JSON.stringify(jsonx3, null, 2);
            alert('Arbeitsplan und Stückliste \\n'+JSON.stringify(jsonx3, null, 2));
          };
          
    function IsValidJSONString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                alert('Fehler in Datenformat. JSON-Format enthält einen Fehler.');
                return false;
            }   
                alert('Daten wurden in den Editor übernommen.');
                return true;
        }
              
      function SetJson1(){
        var str=$('#profil" . $this->faufid . "').val(); 
          if (IsValidJSONString(str)){
            editor3.set(JSON.parse(str) );
            };
      };
      function SetJson2(){
        var str=$('#config" . $this->faufid . "').val(); 
          if (IsValidJSONString(str)){
            editor3.set(JSON.parse(str) );
            };
      };
      function SetJson3(){
        var str=$('#process" . $this->faufid . "').val(); 
          if (IsValidJSONString(str)){
            editor3.set(JSON.parse(str) );
            };
      };
     
     
              
         function EditWithoutEditor(){
          $(\"#profil" . $this->faufid . "\").show();
            $(\"#config" . $this->faufid . "\").show();
             $(\"#process" . $this->faufid . "\").show();
         };
         
         function EditWithEditor(){
          $(\"#profil" . $this->faufid . "\").hide();
            $(\"#config" . $this->faufid . "\").hide();
             $(\"#process" . $this->faufid . "\").hide();
         };
            </script>";
        $_SESSION['jsoneditor'] = $h;

    }

    function SetSessionScript()
    {
        $_SESSION['endscripts'] = "
           EditWithEditor(); 
        ";

    }

    function JsonScripts()
    {
        $h = " <link href=\"../plugins/jsoneditor/jsoneditor.css\" rel=\"stylesheet\" type=\"text/css\">
            <script src=\"../plugins/jsoneditor/jsoneditor.js\"></script>";
        return $h;
    }
}

