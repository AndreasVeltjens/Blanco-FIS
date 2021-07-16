<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 16.10.18
 * Time: 14:08
 */
class FisMaterial
{
    public $material = 0;
    public $data = array();
    public $version = array();
    public $versionid = 0;
    public $activeversion = array();
    public $conf = array();

    public $limit = 500;
    public $search = "";
    public $no_free_search_value_allowed = 0;
    public $dataall = array();
    public $kontierung = "";
    public $group = "";
    public $productfamily = "";


    function __construct($i = 0)
    {
        $this->material = $i;
        if ($i > 0) {
            $this->LoadData();
            $this->LoadVersionData();
            $this->LoadActivVersionData();
            $this->CheckActiveVersion();
            //$this->CheckVersionsData();
            $this->conf = json_decode($this->activeversion['configprofil'], true);

        }
    }

    function __toString()
    {
        return $this->material;
    }

    function CheckActiveVersion()
    {
        if ($this->data['version'] == "" or count($this->version) == 0) {
            AddSessionMessage("info", "aktive Version  <code> " . $this->GetMaterialName() . "</code> darf nicht leer sein.", "Info");
            if (count($this->version) > 0) {
                $this->SaveVersionAsActive($this->version[0]['version']);
            } else {
                $this->AddVersion("Version 0", "Erstmusterfreigabe");
                $this->LoadVersionData();
                $this->SaveVersionAsActive($this->version[0]['version']);
            }
        }
        $this->LoadData();
        $this->LoadVersionData();
        $this->LoadActivVersionData();
    }

    function FindImportSAPEAN13($number = 0)
    {

        if ($number == 0) {
            $number = intval($this->data['Nummer']);
        }
        include("./connection.php");
        $sql = "SELECT * FROM fissapean13 WHERE fissapean13.material='" . $number . "' ";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row = $rst->fetch_assoc();
            $this->data['ean13'] = $row['ean13'];
            return true;
        } else {
            return false;
        }

    }

    function GetNextSN()
    {
        include("./connection.php");
        $this->newsn = 0;
        $sql = "SELECT * FROM fertigungsmeldungen 
              WHERE fertigungsmeldungen.fartikelid='" . $this->material . "' 
              AND fertigungsmeldungen.fsn <=900000 ORDER BY (fertigungsmeldungen.fsn)+0 DESC LIMIT 0,1
        ";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $newsn = $row_rst['fsn'] + 1;
        } else {
            $newsn = 1;
            AddSessionMessage("info", "FID <code> FID $this->fid</code> Erstmusterprüfung erforderlich.", "Erstmuster");
        }
        return $newsn;
    }

    function GetLastFSNxValues()
    {
        include("./connection.php");
        $this->newsn = 0;
        $sql = "SELECT * FROM fertigungsmeldungen 
              WHERE fertigungsmeldungen.fartikelid='" . $this->material . "' 
              AND fertigungsmeldungen.fsn <=900000 ORDER BY (fertigungsmeldungen.fsn)+0 DESC LIMIT 0,1
        ";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $r = $row_rst;
        } else {
            $r = array();
            AddSessionMessage("info", "Keine Datenübernahme aus vorheriger Seriennumer möglich.", "Eingabehilfe");
        }
        return $r;
    }


    function CheckVersionsData()
    {
        if (count($this->version) > 0) {
            foreach ($this->version as $i => $version) {

                $v = new FisVersion($version['version'], $this->material);
                $v->data['materialnummer'] = $this->data['Nummer'];
                $v->data['kundenmaterialnummer'] = $this->data['Zeichnungsnummer'];

                $v->data['powerdesc'] = $this->data['leistungsangabe'];

                $v->data['maxload'] = $this->data['traglast'];


                $p = $this->GetPrinterSettings();

                $p['dokuset']['count'] = $this->data['dokuset'];
                $p['typenschild']['count'] = $this->data['anzahl_etikett_typenschild'];
                $p['verpackung']['count'] = $this->data['anzahl_etikett_verpackung'];
                $v->data['print'] = json_encode($p);

                $v->data['Datum'] = date('Y-m-d', time());
                $v->data['beschreibung'] = str_replace("Ã", "", $v->data['beschreibung']);

                if (strlen($this->data['pruefzeichen']) > 0) {
                    $v->data['pruefzeichen'] = $this->data['pruefzeichen'];
                }

                $v->data['pruefzeichen'] = "info" . str_replace("elektrog", "weee", $v->data['pruefzeichen']);
                $v->data['pruefzeichen'] = str_replace("kein", "", $v->data['pruefzeichen']);

                $v->SaveVersion(false);
                $txt = "Materialdaten, Prüfzeichen und Druckeinstellung für <code> " . $v->data['version'] . " </code> wurde automatisch überschrieben.";

                $w = new FisWorkflow($txt, "versionid", $v->versionid);
                $w->Add();
            }
            AddSessionMessage("info", " insgesamt $i Versionen aktualisiert", "Update Versionsdaten");
        }
    }


    function LoadData()
    {
        include("./connection.php");
        $this->data = array();
        $sql = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$this->material' ";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data = $row_rst;
        } else {
            $this->data = array();
            AddSessionMessage("error", "Material <code> ARTICLEID $this->material</code> nicht bekannt.", "Datenbankfehler");
        }
    }

    function LoadAllData()
    {
        if ($this->limit > 0) {
            if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
                $this->dataall = array();
                return $this;
            } else {
                $s = "";
                if (strlen($this->kontierung) > 0 AND ($this->kontierung <> "0")) {
                    $s = "AND artikeldaten.Kontierung='" . $this->kontierung . "'";
                }
                if ($_SESSION['material_search_lokz'] <> 1) {
                    $s .= "AND artikeldaten.aktiviert='1'";
                }

                if (strlen($this->group) > 0) {
                    $s .= " AND artikeldaten.Gruppe='" . $this->group . "'";
                }

                if (strlen($this->productfamily) > 0) {
                    $s .= " AND artikeldaten.productfamily='" . $this->productfamily . "'";
                }
                include("./connection.php");
                $sql = "SELECT * FROM artikeldaten 
                        WHERE (artikeldaten.Bezeichnung like '%" . $this->search . "%'
                        OR artikeldaten.Nummer like '%" . $this->search . "%'
                        OR artikeldaten.Beschreibung like '%" . $this->search . "%'
                         ) 
                         $s
            
                         ORDER BY artikeldaten.Kontierung ASC ,artikeldaten.Gruppe ASC , artikeldaten.Bezeichnung DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Materialdaten  <code>" . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
                }
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {
                        $this->dataall[] = $row_rst;
                    } while ($row_rst = $rst->fetch_assoc());

                } else {
                    $this->dataall[] = array();
                }
                $mysqli->close();
            }

        }
    }

    function LoadActivVersionData($v = "")
    {
        include("./connection.php");
        $this->activeversion = array();
        if ($v == "") $v = $this->data['version'];

        $sql = "SELECT * FROM artikelversionen 
              WHERE artikelversionen.artikelid='" . $this->material . "' AND  artikelversionen.version = '" . $v . "' LIMIT 0,1";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->activeversion = $row_rst;
            $this->versionid = $row_rst['id'];
        } else {
            $this->activeversion = array();
            // AddSessionMessage("error","aktive Version <code> ArtikelID $this->material - ".$this->data['version']."</code> nicht bekannt.","Datenbankfehler");
        }


    }

    function LoadVersionData()
    {
        include("./connection.php");
        $this->version = array();
        $sql = "SELECT * FROM artikelversionen 
              WHERE artikelversionen.artikelid='" . $this->material . "' 
              ORDER BY artikelversionen.Datum DESC LIMIT 0,100";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->version[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->version = array();
            AddSessionMessage("error", "Artikelversionen <code> Material-ID $this->material</code> nicht bekannt oder keine Version angelegt.", "Datenbankfehler");
        }


    }

    function GetVersionList($type = "select", $value = "")
    {
        $h = "";
        switch ($type) {
            case "select":
                {
                    include("./connection.php");
                    $sql = "SELECT * FROM artikelversionen 
              WHERE artikelversionen.artikelid='" . $this->material . "' ORDER BY artikelversionen.Datum DESC LIMIT 0,100";
                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        do {

                            if ($value == $row_rst['version']) {
                                $s = "selected";
                            } else {
                                $s = "";
                            }
                            $h .= "<option value=\"" . $row_rst['version'] . "\" $s>" . $row_rst['version'] . "</option>";

                        } while ($row_rst = $rst->fetch_assoc());
                    }
                    break;
                }
            case "select2":
                {
                    include("./connection.php");
                    $sql = "SELECT * FROM artikelversionen 
              WHERE artikelversionen.artikelid='" . $this->material . "' ORDER BY artikelversionen.Datum DESC LIMIT 0,100";
                    $rst = $mysqli->query($sql);
                    $ok = false;
                    $h .= "<option value=\"\">unbekannt</option>";
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        do {

                            if ($value == $row_rst['version']) {
                                $s = "selected";
                                $ok = true;
                            } else {
                                $s = "";
                            }
                            $h .= "<option value=\"" . $row_rst['version'] . "\" $s>" . $row_rst['version'] . "</option>";

                        } while ($row_rst = $rst->fetch_assoc());
                        if (!$ok) {
                            $s = "selected";
                        } else {
                            $s = "";
                        }
                        $h .= "<option value=\"" . $value . "\" $s>" . $value . "</option>";
                    }
                    break;
                }
            case "text":
                {
                    include("./connection.php");
                    $sql = "SELECT * FROM artikelversionen 
              WHERE artikelversionen.artikelid='" . $this->material . "' AND artikelversionen.version='$value' LIMIT 0,100";
                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $row_rst = $rst->fetch_assoc();
                        $h = $row_rst['version'];
                    }
                    break;
                }

        }
        return $h;
    }

    function AddMaterial($s = "Neues Material")
    {
        include("./connection.php");

        $updateSQL = sprintf("INSERT INTO  artikeldaten (bezeichnung, Nummer) 
                                            VALUES (%s,%s)",
            GetSQLValueString(utf8_decode($s), "text"),
            GetSQLValueString(utf8_decode($_POST['Nummer']), "text")

        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Material  <code> ID " . $s . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        } else {

            $txt = "Material  <code> ID " . $s . "</code> wurde angelegt.";
            AddSessionMessage("success", $txt, "Equipment gespeichert");
            $this->material = mysqli_insert_id($mysqli);
            $w = new FisWorkflow($txt, "artikelid", $this->material);
            $w->Add();
            $_SESSION['material'] = $this->material;

        }


    }

    function SaveMaterial()
    {
        include("./connection.php");

        if (isset($_REQUEST['material_new'])) {
            $this->AddMaterial($_POST['Beschreibung']);
        }

        if (strlen($_POST['Nummer']) == 0 or $_POST['Nummer'] == 0) {
            $_POST['Nummer'] = 100000 + $this->material;
        }

        $updateSQL = sprintf("
            UPDATE artikeldaten SET artikeldaten.Nummer=%s, artikeldaten.Bezeichnung=%s, artikeldaten.Beschreibung=%s,
            artikeldaten.stuli=%s, artikeldaten.version=%s, artikeldaten.merkmale=%s, artikeldaten.Gruppe=%s,
            artikeldaten.Kontierung=%s, artikeldaten.productfamily=%s, artikeldaten.Zeichnungsnummer=%s, artikeldaten.aktiviert=%s,
            artikeldaten.aktivrep=%s,artikeldaten.pcna=%s,artikeldaten.pcnagwl=%s,
            artikeldaten.lagerplatz=%s, supplier=%s, content=%s, `count`=%s , Einzelpreis=%s
            WHERE artikeldaten.artikelid='" . $this->material . "' ",
            GetSQLValueString(utf8_decode($_POST['Nummer']), "text"),
            GetSQLValueString(utf8_decode($_POST['Bezeichnung']), "text"),
            GetSQLValueString(utf8_decode($_POST['Beschreibung']), "text"),
            GetSQLValueString($_POST['stuli'], "int"),
            GetSQLValueString(utf8_decode($_POST['version']), "text"),
            GetSQLValueString(utf8_decode($_POST['merkmale']), "text"),
            GetSQLValueString(utf8_decode($_POST['Gruppe']), "text"),
            GetSQLValueString(utf8_decode($_POST['Kontierung']), "text"),
            GetSQLValueString(utf8_decode($_POST['productfamily']), "text"),
            GetSQLValueString(utf8_decode($_POST['Zeichnungsnummer']), "text"),
            GetSQLValueString($_POST['aktiviert'], "int"),
            GetSQLValueString($_POST['aktivrep'], "int"),
            GetSQLValueString($_POST['pcna'], "int"),
            GetSQLValueString($_POST['pcnagwl'], "int"),
            GetSQLValueString(utf8_decode($_POST['lagerplatz']), "text"),
            GetSQLValueString(utf8_decode($_POST['supplier']), "text"),
            GetSQLValueString(utf8_decode($_POST['content']), "text"),
            GetSQLValueString(utf8_decode($_POST['count']), "text"),
            GetSQLValueString(utf8_decode($_POST['Einzelpreis']), "text")

        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Material <code> " . $_POST['Nummer'] . "- " . $_POST['Bezeichnung'] . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
        } else {
            $txt = "Material <code> " . $_POST['Nummer'] . "- " . $_POST['Bezeichnung'] . "</code> wurde gespeichert.";
            AddSessionMessage("success", $txt, "Update Materialdaten");
            $w = new FisWorkflow($txt, "artikelid", $this->material);
            $w->Add();

            if (isset($_REQUEST['material_new'])) {
                $this->AddVersion("Version 0", "Erstmusterfreigabe");
                $this->LoadVersionData();
                $this->SaveVersionAsActive($this->version[0]['version']);
            }
        }
        $d = new FisDocument("Dokumente zum Material", "artikelid", $this->material);
        $d->AddNewDocument();


    }

    function SaveEAN13Code($material, $ean13)
    {
        include("./connection.php");
        if ($this->FindImportSAPEAN13($material) == 0) {
            $updateSQL = sprintf("
                        INSERT INTO  fissapean13 (material, materialdesc, ean13) VALUES (%s,%s,%s)",

                GetSQLValueString($material, "int"),
                GetSQLValueString($this->GetMaterialDesc(), "text"),
                GetSQLValueString($ean13, "text")
            );

        } else {
            $updateSQL = sprintf("
                        UPDATE  fissapean13 SET materialdesc=%s, ean13=%s WHERE 
                                fissapean13.material =%s",

                GetSQLValueString($this->GetMaterialDesc(), "text"),
                GetSQLValueString($ean13, "text"),
                GetSQLValueString($material, "int")
            );
        }
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
        } else {

            $txt = "EAN13-Code <code> " . $material . "- " . $ean13 . "</code> wurde aktualisiert.";
            AddSessionMessage("success", $txt, "Update Materialdaten");
            $w = new FisWorkflow($txt, "artikelid", $this->material);
            $w->Add();
        }
    }


    function ImportFis9data()
    {
        if ($this->data['anzahlp'] == 1) {
            $this->data['merkmale'] = "Heizmodul;";
        }
        if ($this->data['anzahlp'] == 2) {
            $this->data['merkmale'] = "Regler;Heizung;";
        }
        if ($this->data['anzahlp'] == 3) {
            $this->data['merkmale'] = "Regler;Heizung;Lüfter;";
        }

        $s = stripos($this->data['Bezeichnung'], "grün");
        if ($s !== false) {
            $this->data['productfamily'] = "grün";
        }

        $s = stripos($this->data['Bezeichnung'], "rot");
        if ($s !== false) {
            $this->data['productfamily'] = "rot";
        }

        $s = stripos($this->data['Bezeichnung'], "gelb");
        if ($s !== false) {
            $this->data['productfamily'] = "gelb";
        }

        $s = stripos($this->data['Bezeichnung'], "blau");
        if ($s !== false) {
            $this->data['productfamily'] = "blau";
        }

        $s = stripos($this->data['Bezeichnung'], "RAL5000");
        if ($s !== false) {
            $this->data['productfamily'] = "blau";
        }

        $s = stripos($this->data['Bezeichnung'], "grau");
        if ($s !== false) {
            $this->data['productfamily'] = "grau";
        }

        include("./connection.php");


        $updateSQL = sprintf("
            UPDATE artikeldaten SET artikeldaten.merkmale=%s, artikeldaten.productfamily=%s
            WHERE artikeldaten.artikelid='" . $this->material . "' ",

            GetSQLValueString(utf8_decode($this->data['merkmale']), "text"),
            GetSQLValueString(utf8_decode($this->data['productfamily']), "text")

        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Material <code> " . $_POST['Nummer'] . "- " . $_POST['Bezeichnung'] . "</code> konnte nicht konvertiert gespeichert werden.", "Fehlermeldung");
        } else {
            $txt = "Stammdaten für Material <code> " . $_POST['Nummer'] . "- " . $_POST['Bezeichnung'] . "</code> wurde aus FIS9 konvertiert.";
            AddSessionMessage("success", $txt, "Update Materialdaten");
            $w = new FisWorkflow($txt, "artikelid", $this->material);
            $w->Add();
        }
        $this->CheckActiveVersion();
        $this->CheckVersionsData();

    }

    function AddVersion($s, $txt = "Erstmusterfreigabe")
    {
        if (strlen($s) == 0 or $this->material == 0) {
            AddSessionMessage("error", "Version  <code> " . $s . "</code> wurde nicht angelegt,da Material " . $this->material . " oder Versionsname $s nicht bekannt", "Bezeichnung fehlt.");

        } else {
            include("./connection.php");
            $sql = "SELECT artikelversionen.id FROM artikelversionen 
                    WHERE artikelversionen.version = '" . $s . "' AND artikelversionen.artikelid='$this->material' LIMIT 0,1";

            $rst = $mysqli->query($sql);
            if (mysqli_affected_rows($mysqli) == 0) {
                $materialnumber = GetArtikelName("number", $this->material);

                if ($materialnumber == "") {
                    $materialnumber = 100000 + $this->material;
                }

                $updateSQL = sprintf("INSERT INTO  artikelversionen (beschreibung, artikelid, version, Datum, materialnummer) 
                                            VALUES (%s,%s,%s, %s,%s)",
                    GetSQLValueString($txt, "text"),
                    GetSQLValueString($this->material, "int"),
                    GetSQLValueString($s, "text"),
                    GetSQLValueString(date('Y-m-d', time()), "text"),
                    GetSQLValueString($materialnumber, "text")
                );


                $rst = $mysqli->query($updateSQL);
                if ($mysqli->error) {
                    $_SESSION['zzz'] = $updateSQL;
                    AddSessionMessage("error", "Version  <code> ID " . $s . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
                } else {

                    $txt = "Version  <code> ID " . $s . "</code> wurde angelegt.";
                    AddSessionMessage("success", $txt, "Equipment gespeichert");
                    $this->versionid = mysqli_insert_id($mysqli);
                    $w = new FisWorkflow($txt, "versionid", $this->versionid);
                    $w->Add();
                }

            } else {
                AddSessionMessage("error", "Version  <code> ID " . $s . "</code> wurde nicht angelegt, weil die Bezeichnung bereits verwendet wurde.", "Bezeichnung fehlerhaft.");

            }
        }
    }

    function ChangeVersionName($n)
    {
        $version = new FisVersion($_SESSION['version'], $this->material);
        $version->SaveNewVersionName($n);
        include("./connection.php");
        $updateSQL = sprintf("
            UPDATE fertigungsmeldungen SET fertigungsmeldungen.version=%s 
            WHERE fertigungsmeldungen.fartikelid='" . $this->material . "' 
            AND fertigungsmeldungen.version='" . $_SESSION['version'] . "' ",
            GetSQLValueString($n, "text")

        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error", "Freigabeversion  <code> " . $n . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
        } else {
            $anzahl = mysqli_affected_rows($mysqli);
            AddSessionMessage("info", "Version wurde bei $anzahl Fertigungsdaten auf  <code> " . $n . "</code> geändert.", "Fehlermeldung");

        }

        if ($this->data['version'] == $_SESSION['version']) {
            $this->SaveVersionAsActive($n);
        }
    }

    function DeleteVersionName($n, $state = 1)
    {
        if ($this->data['version'] == $n) {
            AddSessionMessage("error", "Freigabeversion  <code> " . $n . "</code> konnte nicht gelöscht werden, da diese Version noch aktiv ist.", "Fehlermeldung");
            return false;
        } else {
            include("./connection.php");
            $updateSQL = sprintf("
            UPDATE artikelversionen SET artikelversionen.lokz=%s 
            WHERE artikelversionen.artikelid='" . $this->material . "' 
            AND artikelversionen.version='" . $n . "'",
                GetSQLValueString($state, "int")

            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Freigabeversion  <code> " . $n . "</code> kann nicht geändert werden.", "Fehlermeldung");
            } else {
                if ($state == 1) {
                    $txt = "Artikelversion <code> " . $n . " </code> wurde gelöscht.";
                } else {
                    $txt = "Bei Artikelversion <code> " . $n . " </code> wurde die Löschvormerkung zurückgenommen.";
                }
                AddSessionMessage("success", $txt, "Update Versionsdaten");
                $w = new FisWorkflow($txt, "artikelid", $this->material);
                $w->Add();
                $v = new FisVersion($n, $this->material);
                $w = new FisWorkflow($txt, "versionid", $v->versionid);
                $w->Add();
            }
        }

    }

    function SaveVersionAsActive($n){
        if (strlen($n)>0) {
            include("./connection.php");
            $updateSQL = sprintf("
            UPDATE artikeldaten SET artikeldaten.version=%s 
            WHERE artikeldaten.artikelid='" . $this->material . "' ",
                GetSQLValueString($n, "text")

            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Freigabeversion  <code> " . $n . "</code> konnte nicht gespeichert werden.", "Fehlermeldung");
            } else {
                $txt = "Freigabeversion <code> " . $n . " </code> wurde als aktive Version gespeichert.";
                AddSessionMessage("success", $txt, "Update Materialdaten");
                $w = new FisWorkflow($txt, "artikelid", $this->material);
                $w->Add();
                $v = new FisVersion($n, $this->material);
                $w = new FisWorkflow($txt, "versionid", $v->versionid);
                $w->Add();
            }
        }else{
            AddSessionMessage("info", "Version  <code> " . $n . "</code> darf nicht leer sein.", "Warnung");
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



    function GetPrinterSettings(){
        if (count(json_decode($this->activeversion['print'],true))>0){
            $p=json_decode($this->activeversion['print'],true);
        }else{
            $p=GetPrinterDefaults();
            $this->activeversion=$p;
        }
        return $p;
    }

    function GetMaterialName(){
        return $this->data['Bezeichnung'];
    }

    function GetPrice()
    {
        $p = $this->data['Einzelpreis'];
        str_replace(",", ".", $p);
        if (strlen($p) == 0) {
            $p = 0;
        }
        return $p;
    }


    function GetEAN13(){
        $this->FindImportSAPEAN13();
        return $this->data['ean13'];
    }

    function GetMaterialDesc(){
        return $this->data['Beschreibung'];
    }

    function GetHints(){
        return $this->data['hinweis'];
    }

    function GetDrawingNumber(){
        return $this->data['Zeichnungsnummer'];
    }
    function GetGroup(){
        return $this->data['Gruppe'];
    }

    function GetProductFamily(){
        return $this->data['productfamily'];
    }

    function GetKontierung(){
        return $this->data['Kontierung'];
    }

    function GetProveDescList(){
        return str_replace(";","<br>",$this->data['Zeichnungsnummer']);
    }
    function GetMaterial(){
        if (count(json_decode($this->activeversion['config'],true))>0){
            $n="Konfiguration";
        }else{
            $n= $this->activeversion['materialnummer'];
        }
        if (strlen($n)==0){ $n= $this->data['materialnummer']; }
        return $n;
    }


    function GetVersion(){
        return $this->activeversion['version'];
    }



    function GetCustomMaterial(){
        if ($this->activeversion['kundenmaterialnummer']==""){
            return $this->activeversion['materialnummer'];
        }else{
            return $this->activeversion['kundenmaterialnummer'];
        }
    }

    function GetStuli(){
        $h="";
        if (intval($this->data['stuli'])>0){
            $h=GetArtikelName("text",$this->data['stuli']);
        }else{
            $h="keine Komponentenstückliste vorhanden";
        }
        return $h;
    }

    function GetStatus($type="text"){
        $t="";
        if ($this->data['aktiviert']==1){
            if ($type=="text") $t="FIS Rückmeldung aktiv";
            if ($type=="data") $t=1;
        }else{
            if ($type=="text") $t="Material wird nicht mehr für FIS Rückmeldungen verwendet";
            if ($type=="data") $t=0;
        }
        return $t;
    }
    function GetRepStatus($type="text"){
        $t="";
        if ($this->data['aktivrep']==1){
            if ($type=="text") $t="Rep Rückmeldung aktiv";
            if ($type=="data") $t=1;
        }else{
            if ($type=="text") $t="Material wird nicht mehr für Reparaturrückmeldungen verwendet";
            if ($type=="data") $t=0;
        }
        return $t;
    }


    function ShowHtmlPicture(){

        $h="<img src=\"".$this->GetPictureLink()."\" alt=\"image\" class=\"img-responsive thumb-lg\"/>";
        return $h;
    }

    function ShowHtmlLinkAllVersion($link){
        $h="";
        if (count($this->version) > 0) {
            $h = "
            <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default dropdown-toggle waves-effect\" data-toggle=\"dropdown\" aria-expanded=\"false\"> andere Version <span class=\"caret\"></span> </button>
                <ul class=\"dropdown-menu\">";

                foreach ($this->version as $d => $i) {
                    $h .= "<li><a href='$link&material=" . $this->material . "&version=" . $i['version'] . "'>" . $i['version'] . "</a></li>";
                }
                $h.="<li class='divider'></li>";
            $h .= "<li> <a href='$link&material=" . $this->material . "&version=&action=15'><i class='fa fa-gear'> </i> Material Einstellungen anzeigen</a></li>";

            $h .= "</ul></div>";
        }
        return $h;
    }

    function GetSelectedVersion(){
        $v=$this->activeversion['version'];
        if (strlen($_SESSION['version'])>0){
            $v=$_SESSION['version'];
            $this->LoadActivVersionData($v);
        }
        return $v;
    }

    function IsCharge(){
        $r=explode(";",$this->data['merkmale']);
        $count=count($r);
        if ($count<=1) {
            return true;
                }else{
            return false;
        }
    }

    function ShowHtmlInputFields($identifier = "")
    {
        $r=explode(";",$this->data['merkmale']);
        $count=count($r);
        $h="";

        if ($_SESSION['sn']>0){
            $h .= " 
                    <div class=\"form-group\">
                            <label for=\"sn\" class=\"col-sm-4 control-label\">Seriennummer</label>
                            <div class=\"col-sm-7\">
                                <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"sn\" id=\"sn\" placeholder=\"SN\" value=\"".$_SESSION['sn']."\">
                            </div>
                        </div>
                       ";
        }



        if ($count>1) {
            $h .= "<table class='table table-striped'><tbody>";
            for ($i = 0; $i < ($count - 1); $i++) {

                $index = $i + 1;
                $h.="<tr><td><div class=\"form-group\">
                            <label for=\"" . $identifier . "fsn$i\" class=\"col-sm-4 control-label\">" . $r[$i] . "</label>
                            <div class=\"col-sm-7\">
                                <input  type=\"text\" parsley-type=\"text\" class=\"form-control input-lg\" name=\"" . $identifier . "fsn$index\" id=\"" . $identifier . "fsn$index\" placeholder=\"" . $r[$i] . "\" value=\"\">
                            </div>
                        </div></td></tr>";

                if ($i==1){ $_SESSION['focus']="$('#fsn$i').focus();";}
            }
            $h.="</tbody></table>";
        }else{
            $h = " <div class=\"form-group input-lg\">
                        <label class=\"col-sm-6 control-label\">Chargenmenge:</label>
                        <div class=\"col-sm-12\">
                        <input class=\"form-control input-lg\" id=\"count\" type=\"text\" value=\"1\" name=\"count\" />
                    </div>
                    </div>";
            $_SESSION['endscripts'].="
                $(\"input[name='count']\").TouchSpin({
                min: 1,
                max: 25,
                step: 1,
                decimals: 0,
                boostat: 1,
                maxboostedstep: 1,
                buttondown_class: \"btn btn-danger btn-lg\",
                buttonup_class: \"btn btn-custom btn-lg\",
                postfix: 'Stück'
            });
            ";
            $_SESSION['focus']="$('#count').focus();";
        }
        $h.="<hr>";
        /**
        $h .= "<div class=\"form-group\">
                <label for=\"rfid\" class=\"col-sm-4 control-label\">RFID</label>
                <div class=\"col-sm-7\">
                    <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"rfid\" id=\"rfid\" placeholder=\"Scannen\" value=\"\">
                </div>
            </div>";
        $h .= "<div class=\"form-group\">
                <label for=\"sn\" class=\"col-sm-4 control-label\">Seriennummer</label>
                <div class=\"col-sm-7\">
                    <input  type=\"text\" required parsley-type=\"text\" class=\"form-control\" name=\"sn\" id=\"sn\" placeholder=\"SN Manuell vergaben\" value=\"\">
                </div>
            </div>";
        */

        return $h;
    }


    function ShowHtmlActivConfig(){
        if (is_array(json_decode($this->activeversion['kundenmaterialnummer'],true))){
            return json_decode($this->activeversion['kundenmaterialnummer'],true);
        }
    }

    function GetPictureLink($view="thumbnail"){

        $d=new FisDocument($this->material,"artikelid",$this->material);
        $l=$d->GetLatestPictureLink();

        if (strlen($l)<4) {
            $l = "../config/logo_no_picture_" . FIS_ID . ".png";
        }


        $t='../thumbnail/artikelid/' . pathinfo($l, PATHINFO_FILENAME) . '.' . pathinfo($l, PATHINFO_EXTENSION);


        if (!is_dir('../thumbnail/artikelid')){
            mkdir('../thumbnail/artikelid',0777);
            chmod('../thumbnail/artikelid',777);
        }

        if (is_file(substr($l,3)) && !is_file($t) ) {
           new FisThumbnail(substr($l,3), '../thumbnail/artikelid/', 80, 80);
        }

        if (is_file($t) && $view=="thumbnail"){
            return  $t;
        }else{
            return  $l;
        }

    }

    function GetPicture($width=0){

        if ($width==0) {
            return "<img src=\"".$this->GetPictureLink() . "\" class=\"thumb-lg\" alt=\"Material\">";
        }else{
            return "<img src=\"".$this->GetPictureLink() ."\" width=\"$width\" alt=\"Material\">";
        }
    }



    function ShowHtmlIcons(){
        $v=new FisVersion(0,0);
        $v->LoadDataById($this->versionid);
        $h=$v->ShowHtmlIcons();
        return $h;
    }

    function ShowHtmlIcons2($type="typenschild"){
        $v=new FisVersion(0,0);
        $v->LoadDataById($this->versionid);
        $h=$v->ShowHtmlIcons2($type);
        return $h;
    }

    function GetPowerDesc(){
        return $this->activeversion['powerdesc'];
    }

    function GetMaxLoad(){
        return $this->activeversion['traglast'];
    }



    function GetIconInfo(){
        if (stripos($this->activeversion['pruefzeichen'],"info")!==false) {
            return true;
        }else{
            return false;
        }
    }
    function GetIconCE(){
        if (stripos($this->activeversion['pruefzeichen'],"ce")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX4(){
        if (stripos($this->activeversion['pruefzeichen'],"ipx4")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX6(){
        if (stripos($this->activeversion['pruefzeichen'],"ipx6")!==false) {
            return true;
        }else{
            return false;
        }
    }



    function GetIconWEEE(){
        if (stripos($this->activeversion['pruefzeichen'],"weee")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconEAC(){
        if (stripos($this->activeversion['pruefzeichen'],"eac")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDE(){
        if (stripos($this->activeversion['pruefzeichen'],"vde")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDEGS(){
        if (stripos($this->activeversion['pruefzeichen'],"vdgs")!==false) {
            return true;
        }else{
            return false;
        }
    }



    //Config


    function LoadVersionConfig($p)
    {
        $this->conf=json_decode($p,JSON_OBJECT_AS_ARRAY);
    }
    function LoadDefaults(){
        $this->conf=json_decode($this->activeversion['configprofil'],true);
    }
    function LoadConfigProfil($p){
        $this->conf=json_decode($p);
    }
    function SaveConfigProfilasJson(){
        return json_encode($this->conf);
    }
    function ShowProfilStatus($view=0){
        if (is_array($this->conf)){
            $h="Konfiguration wurde erfolgreich geladen.<br>";
        }else{
            $h="Artikel ist nicht konfiguriert.<br>";
        }
        if ($view==0) {
            $h .= str_replace("Array", "<br>", print_r($this->conf, true));
        }
        return $h;
    }
    function ShowConfig(){
        $h="";
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                $h.=$item."<br>";
                if (is_array($k) && count($k)>0){
                    foreach ($k as $y=>$x){
                        $h.="|- ".$y."<br>";
                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s=>$z){
                                $h.="--|- ".$s."<br>";
                                foreach ($z as $z1=>$z2){$h.="-----|---".$z2." ".$z1."<br>";}
                                $h.="<br>";
                            }
                        }
                    }
                }
            }
        }
        return $h;
    }
    function ShowControlFields(){
        $h="";
        $select1=true;
        $select2=true;
        $select3=true;
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                $h.="<b>".$item."</b><br>";
                if (is_array($k) && count($k)>0){
                    $select1=true;
                    $select3=true;
                    foreach ($k as $y=>$x){
                        $h.="<table><tr>";
                        $h.="<td>";
                        $h.=$this->ShowAsRadioOption($item,$x,$y,$select1);
                        $select1=false;
                        $h.="</td>";
                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s=>$z){
                                //$h.=$this->ShowAsRadioOption($s,$y,$x);
                                foreach ($z as $z1=>$z2){
                                    $h.="<td>";
                                    $h.=$this->ShowAsRadioOption($s,$z2,$z1,$select3);
                                    $select3=false;
                                    $h.="</td>";
                                }
                            }
                            $h.="</tr>";
                        }
                        $h.="</table><hr>";
                    }
                    $h.="<hr>";
                }
            }
        }
        return $h;
    }
    function ShowAsRadioOption($name,$k,$value,$s){
        $select="";
        if ($s){$select="checked";}
        $h="<label>
            <input type=\"radio\" name=\"$name\" value=\"$value\" $select> $value ($k)
            <img src=\"../picture/update2/$value.png\" width=\"50\" height=\"50\" align=\"absmiddle\"></label>
           ";
        return $h;
    }
    function FindArtikelNummer($Color,$Plug,$Logo){
        if ($Logo=="yes"){
            return ($this->conf['Logo']['yes']);
        }else {
            if ($Plug=="") {
                if ($Color=="grey" && isset($this->conf['Color']['grey'])) return ($this->conf['Color']['grey']);
                if ($Color=="blue" && isset($this->conf['Color']['blue'])) return ($this->conf['Color']['blue']);
                if ($Color=="red" && isset($this->conf['Color']['red'])) return ($this->conf['Color']['red']);
                if ($Color=="green" && isset($this->conf['Color']['green'])) return ($this->conf['Color']['green']);
                if ($Color=="yellow" && isset($this->conf['Color']['yellow'])) return ($this->conf['Color']['yellow']);
            }else{
                if ($Color=="grey") {
                    if ($Plug=="DE" && isset($this->conf['Color']['grey']['Plug']['DE'])) return ($this->conf['Color']['grey']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['grey']['Plug']['CEE230V'])) return ($this->conf['Color']['grey']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['grey']['Plug']['GB'])) return ($this->conf['Color']['grey']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['grey']['Plug']['AUS'])) return ($this->conf['Color']['grey']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['grey']['Plug']['DK'])) return ($this->conf['Color']['grey']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['grey']['Plug']['CH'])) return ($this->conf['Color']['grey']['Plug']['CH']);
                }
                if ($Color=="blue") {
                    if ($Plug=="DE" && isset($this->conf['Color']['blue']['Plug']['DE'])) return ($this->conf['Color']['blue']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['blue']['Plug']['CEE230V'])) return ($this->conf['Color']['blue']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['blue']['Plug']['GB'])) return ($this->conf['Color']['blue']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['blue']['Plug']['AUS'])) return ($this->conf['Color']['blue']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['blue']['Plug']['DK'])) return ($this->conf['Color']['blue']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['blue']['Plug']['CH'])) return ($this->conf['Color']['blue']['Plug']['CH']);
                }
                if ($Color=="red") {
                    if ($Plug=="DE" && isset($this->conf['Color']['red']['Plug']['DE'])) return ($this->conf['Color']['red']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['red']['Plug']['CEE230V'])) return ($this->conf['Color']['red']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['red']['Plug']['GB'])) return ($this->conf['Color']['red']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['red']['Plug']['AUS'])) return ($this->conf['Color']['red']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['red']['Plug']['DK'])) return ($this->conf['Color']['red']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['red']['Plug']['CH'])) return ($this->conf['Color']['red']['Plug']['CH']);
                }
                if ($Color=="green") {
                    if ($Plug=="DE" && isset($this->conf['Color']['green']['Plug']['DE'])) return ($this->conf['Color']['green']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['green']['Plug']['CEE230V'])) return ($this->conf['Color']['green']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['green']['Plug']['GB'])) return ($this->conf['Color']['green']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['green']['Plug']['AUS'])) return ($this->conf['Color']['green']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['green']['Plug']['DK'])) return ($this->conf['Color']['green']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['green']['Plug']['CH'])) return ($this->conf['Color']['green']['Plug']['CH']);
                }
                if ($Color=="yellow") {
                    if ($Plug=="DE" && isset($this->conf['Color']['yellow']['Plug']['DE'])) return ($this->conf['Color']['yellow']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['yellow']['Plug']['CEE230V'])) return ($this->conf['Color']['yellow']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['yellow']['Plug']['GB'])) return ($this->conf['Color']['yellow']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['yellow']['Plug']['AUS'])) return ($this->conf['Color']['yellow']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['yellow']['Plug']['DK'])) return ($this->conf['Color']['yellow']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['yellow']['Plug']['CH'])) return ($this->conf['Color']['yellow']['Plug']['CH']);
                }
            }
        }
    }
    function FindInConfig($ar){
        $r=array_search($ar,$this->conf);
        return $r;
    }
    function GetJson(){
        return json_encode($this->conf);
    }

    function ShowPropertyAsTable(){
        $h=asModal(1000,"<div id='preview'></div>","",60,"Vorschau");

        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="</tr><tr>";
        $h.="<td>Bild</td>";
        $h.="<td>
        <script> 
        function ShowPreview(pic){
            $('#preview').html('<img src=\"'+pic+'\" width=\"100%\">');
         };
        </script>
           <a href=\"#custom-width-modal1000\" data-toggle=\"modal\" data-target=\"#custom-width-modal1000\" 
           onclick=\"ShowPreview('".$this->GetPictureLink("review")."');\" >
                 
        ".$this->GetPicture()."</a></td>";
        $h.="</tr><tr>";
        $h.="<td>Materal Nr</td>";
        $h.="<td>".$this->GetMaterial()."</td>";
        $h.="</tr><tr>";
        $h.="<td>EAN</td>";
        $h.="<td>".$this->GetEAN13()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Kundenmaterial Nr</td>";
        $h.="<td>".$this->GetCustomMaterial()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Bezeichnung</td>";
        $h.="<td>".$this->GetMaterialName()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Beschreibung</td>";
        $h.="<td>".$this->GetMaterialDesc()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Zeichnungsnummer</td>";
        $h.="<td>".$this->GetDrawingNumber()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Kontierung</td>";
        $h.="<td>".$this->GetKontierung()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Warengruppe</td>";
        $h.="<td>".$this->GetGroup()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Produktfamilie</td>";
        $h.="<td>".$this->GetProductFamily()."</td>";
        $h.="</tr><tr>";

        $h.="<td>Prüfmerkmale</td>";
        $h.="<td>".$this->GetProveDescList()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Bauteil-Rückverfolgung</td>";
        $h.="<td>".$this->GetStuli()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Konfigurationsstatus</td>";
        $h.="<td>".$this->ShowProfilStatus()."</td>";
        $h.="</tr><tr>";
        $h.="<td>FIS- Status</td>";
        $h.="<td>".$this->GetStatus("text")."</td>";
        $h.="</tr><tr>";
        $h.="<td>Rep- Status</td>";
        $h.="<td>".$this->GetRepStatus("text")."</td>";
        $h.="</tr><tr>";

        $h.="<td>zuletzt erstellte Version</td>";
        $h.="<td>".$this->version[0]['version']."</td>";
        $h.="</tr><tr>";

        $h.="</tr></tbody></table></div>";
        return $h;
    }

    /**
     * @return string
     */
    function EditPropertyAsTable(){
        $d=new FisDocument("Materialbild","artikelid",$this->material);

        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";

        if ($this->material == 0) {
            $this->data['Nummer'] == 0;
        }

        $h.="<td>".asTextField("Materialnummer","Nummer",$this->data['Nummer'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Bezeichnung","Bezeichnung",$this->data['Bezeichnung'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Beschreibung","Beschreibung",$this->data['Beschreibung'],3,7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Zeichnungsnummer","Zeichnungsnummer",$this->data['Zeichnungsnummer'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Kontierung","Kontierung",$this->data['Kontierung'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Warengruppe","Gruppe",$this->data['Gruppe'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Produktfamilie","productfamily",$this->data['productfamily'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Prüfmerkmale","merkmale",$this->data['merkmale'],3,7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asSelect2Box("Bauteil-Rückverfolgung mit","stuli",GetArtikelName("select2",$this->data['stuli']),7,"")."</td>";
        $h.="</tr><tr>";

        if ($this->material > 0) {
            $h .= "<td>" . asSelectBox("aktive Version", "version", $this->GetVersionList("select", $this->data['version']), 7) . "</td>";
            $h .= "</tr><tr>";
        }
        $h.="<td>".asSelectBox("FIS Fertigungsrückmeldung","aktiviert",GetYesNoList("select",$this->data['aktiviert']),7)."</td>";
        $h.="</tr><tr>";
        $h .= "<td>" . asSelectBox("kein Schüttgut", "aktivrep", GetYesNoList("select", $this->data['aktivrep']), 7) . "</td>";
        $h.="</tr><tr>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Auftragsnummer bei Reparatur","pcna",$this->data['pcna'],5)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Auftragsnummer bei Gewährleistung","pcnagwl",$this->data['pcnagwl'],5)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Standard-Lagerort","lagerplatz",$this->data['lagerplatz'],3,7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Standard-Lieferant","supplier",$this->data['supplier'],3,7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Standard-Inhalt","content",$this->data['content'],3,7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextarea("Standard-Anzahl","count",$this->data['count'],3,7,"")."</td>";
        $h.="</tr><tr>";
        $h .= "<td>" . asTextField("Bewertungspreis", "Einzelpreis", $this->data['Einzelpreis'], 5) . "</td>";
        $h .= "</tr><tr>";

        $h.="<td>".$d->ShowHtmlInputField()."</td>";

        $h.="</tr></tbody></table></div>";
        return $h;
    }




    function ShowHtmlAllAsTable($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();

        $h=asModal(1000,"<div id='preview'></div>","",60,"Vorschau");
        $h.= "<h4 class=\"header-title m-t-0 m-b-30\">Materialdaten Suchbegriff:(".$this->GetSearchString().") </h4>";

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
                        <th>Bild</th>
                         <th>Nummer</th>
                         <th>Bezeichnung</th>
                         <th>Produktkennzeichnung</th>
                         <th>Verpackung</th>
                         <th>Kontierung</th>
                         <th>Warengruppe</th>
                         <th>Produktfamilie</th>
                         <th>aktive Version</th>
                         <th>FIS</th>
                         <th>S</th>
                         <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            $old_group="";
            $old_productfamily="";
            $g="";
            $p="";
            foreach ($this->dataall as $d => $i) {
                if ($i['artikelid']>0) {
                    $e= new FisMaterial($i['artikelid']);
                    $v= new FisVersion($e->data['version'],$i['artikelid']);

                    $edit="index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15&equip=&materialgroup=&material=" . $i['artikelid'] . "&version=&fid=";
                    $link = "<a class='btn btn-custom btn-sm' href=\"$edit\" onclick='refresh_site()'> Bearbeiten </a>";

                    if ($old_group<>$i['Gruppe']) {
                        $g= "<span class='label label-info'> " . $i['Gruppe'] . " </span>";
                    }else{
                        $g=$i['Gruppe'];
                    }
                    // if ($old_productfamily<>$i['productfamily']){

                        $l="";
                        switch ($i['productfamily']){
                            case "grau":{ $l="label-inverse"; break;}
                            case "blau":{ $l="label-primary"; break;}
                            case "grün":{ $l="label-success"; break;}
                            case "rot":{ $l="label-danger"; break;}
                            case "gelb":{ $l="label-warning"; break;}
                        }
                        $p="<span class='label $l'> ".$i['productfamily']." </span>";
                   // }else{
                    //    $p=$i['productfamily'];
                   // }



                    $h .= "<tr>
                        <td><a href=\"#custom-width-modal1000\" data-toggle=\"modal\" data-target=\"#custom-width-modal1000\" 
                           onclick=\"ShowPreview('".$e->GetPictureLink("preview")."');\" >           
                        ".$e->GetPicture()."</a></td>
                        <td><a href='$edit' target='_self'>" . $e->GetMaterial() . "</a></td>
                        <td>" . ($i['Bezeichnung']) . "</td>
                          <td>" .$v->ShowHtmlIcons2()  . "</td>
                            <td>" .$v->ShowHtmlIcons2("verpackung")  . "<br>".$v->GetEAN13()."</td>
                        <td>" . ($i['Kontierung']) . "</td>
                        <td>" . $g . "</td>
                        <td>" . $p . "</td>
                         <td>" . ($i['version']) . "</td>
                        <td>" . GetYesNoList("text",$i['aktiviert']) . "</td>
                        <td>" . GetYesNoList("text",$i['aktivrep']) . "</td>

                       ";
                    $h .= "  <td>" . "$link" . "</td>";
                    $h .= "</tr>";
                    $old_group=$i['Gruppe'];
                   // $old_productfamily=$i['productfamily'];
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Materialdaten gefunden.";
        }
        return $h;
    }


    function EditVersionAsTable(){


        $h="";
        $d=new FisDocument("UPLOAD","versionsid");
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Version bearbeiten </b></h4>";
        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr><td>";

        $h .= asSelect2Box("Versionsart", "id_fhmea", GetEventTypeName("select2", $this->data['id_fhmea']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextarea("Beschreibung","notes",utf8_decode($this->data['notes']),4,7,"");
        $h.="</td></tr><tr><td>";
        $h.=$d->ShowHtmlInputField();
        $h.="</td></tr></tbody></table></div>";



        return $h;
    }

    function ShowVersionenAsTable($btn = true)
    {
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Version</th>
                        <th>Beschreibung</th>
                        <th>ab Seriennummer</th>
                        <th>Datum</th>
                        <th>Typenschild</th>
                        <th>Verpackung</th>
                        <th>Leistungsangaben</th>
                        <th>Traglast</th>";
        if ($btn) {
            $h .= "<th>Aktion</th>";
        }
        $h .= "
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->version) > 0) {
            foreach ($this->version as $d => $i) {
                $version=new FisVersion($i['version'],$this->material);

                $edit = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=17&equip=&materialgroup=&material=" . $i['artikelid'] . "&version=" . $version->GetVersionName() . "&fid=";
                $link = "<a class='btn btn-custom btn-sm' href=\"$edit\" onclick='refresh_site()'> Bearbeiten </a>";

                if ($this->data['version'] == $i['version']) {
                    $versionname = "<span class='label label-success'>" . $version->GetVersionName() . "</span><br><span class='label label-success'>aktiv</span>";
                } elseif ($version->data['lokz'] == 1) {
                    $versionname = "<span class='label label-danger'>" . $version->GetVersionName() . "</span><br><span class='label label-danger'>gelöscht</span>";
                } else {
                    $versionname = $version->GetVersionName();
                }
                $h .= "    <tr>
                             <td>" . $versionname . "</td>
                             <td>".$version->GetVersionDesc()."</td>
                             <td>".$version->GetGuiltySerialNumber()."</td>
                             <td>".$version->GetVersionDatum()."</td>
                             <td>".$version->ShowHtmlIcons2()."</td>
                             <td>".$version->ShowHtmlIcons2("verpackung")."</td>
                             <td>".$version->GetPowerDesc()."</td>
                             <td>" . $version->GetMaxLoad() . "</td>";
                if ($btn) {
                    $h .= "<td>$link</td>";
                }
                $h .= "
                        </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>";
        return $h;
    }


    function ShowHtmlUpdateEAN13(){


        $h="";
        $h.="gespeicherte EAN Codes: ".$this->GetEAN13()."<br><br>";
        $h.=asTextField("neuer EAN13 Code","new_material_ean13","",7);
        $h.=asTextField("Material-Nr.","new_material_number",$this->GetMaterial(),7);
        $h.="<br><br>Hinweis: bei Artikeln, die durch Konfiguration neue Materialnummern bekommen, muss für jede bewertete Variante eine eigene EAN-Nr. angelegt werden.";
        $h .= "<br><br>Die EAN-Codes werden in einer separaten Tabelle gespeichert. Mit Aktualsieren können Sie bestehende EAN-Code überschreiben bzw. neue ergänzen.";


        return $h;
    }

    function ShowHtmlPrintSettings()
    {
        $version = new FisVersion($this->data['version'], $this->material);
        return $version->ShowPrinterDefaults();
    }

    function PrintMaterialDesc()
    {

        if (!isset($_SESSION['material']) or $_SESSION['material'] == 0) {
            AddSessionMessage("error", "Material-Beschriftung wurde nicht angelegt, da ein Fehler PrintMaterialDesc aufgetreten ist.", "Druckfehler");
        } else {
            include('./print.material.desc.php');
            $txt = "Lagerplatz-Etikett 1-mal gedruckt.";
            AddSessionMessage("info", $txt, "Druckinfo");
        }

    }
}