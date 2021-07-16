<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 03.11.18
 * Time: 07:13
 */
class FisSettings
{
    public $wp=0;
    public $data=array();

    function __construct($i=0)
    {
        if ($i>0){
            $this->wp=$i;
        }else{
            $this->wp=$_SESSION['wp'];
        }
    }
    function __toString(){
        return $this->wp;
    }

    function GetTools()
    {
        $tools = new FisTool();
        return $tools->GetTools();
    }

    function GetPlans()
    {
        $tools = new FisPlan();
        return $tools->GetPlans();
    }

    function LoadData()
    {
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fissettings WHERE fissettings.wp = '".$this->wp."' AND fissettings.type='wp' LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=json_decode((utf8_decode($row_rst['settings'])),true);

        }else{
            $this->data=$this->wp;
            AddSessionMessage("warning","Werkzeug <code> WP $this->wp </code> hat keine Datensätze -> Default geladen.","Datenbankfehler");
        }


    }

    function GetFisSettings(){
        $this->LoadData();
        return $this->data;
    }

    function ShowHtmlFisSetup(){
        $h=$this->JsonScripts();
        $h.=$this->ShowHtml();
        $this->JsonEditor3x();
        $this->SetSessionScript3x();
        return $h;
    }

    function ShowHtmlWpSettings(){
        $h=$this->JsonScripts();
        $h.=$this->ShowHtmlWp();
        $this->JsonEditor1x();
        $this->SetSessionScript1x();
        return $h;
    }

    function ShowHtml(){

        $d= new FisDocument();
        $u=new FisUpdate();

        $h="<div class=\"col-sm-6\">
            <div class=\"card-box\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>FIS Grundeinstellungen ändern </b></h4><hr>";

        $h.="<button name=\"setTools\"  id=\"setTools\" class=\"btn btn-primary waves-effect waves-light\">Lade Werkzeuge</button>";
        $h.="<button name=\"setPlans\"  id=\"setPlans\" class=\"btn btn-primary waves-effect waves-light\">Lade Pläne</button>";
        $h.="<button name=\"setWps\"  id=\"setWp\" class=\"btn btn-primary waves-effect waves-light\">Lade Verwaltungseinstellungen</button>";

        $h.="<button name=\"getJson\"  id=\"getJson\" class=\"btn btn-success waves-effect waves-light\">Prüfen</button>";

         $h.="<form method='post'>";
                       

        $h.="<div id='jsoneditor'> </div> ";

        $h.="
            <hr><br><br>
             <button name=\"save_tool_defaultsettings\" id=\"save_tool_defaultsettings\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Werkzeuge auf Werkseinstellungen  zurücksetzen</button>
            <button name=\"new_tool_settings\"  id=\"new_tool_settings\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Änderungen an Werkzeugen speichern</button>
            
            <button name=\"save_plan_defaultsettings\" id=\"save_plan_defaultsettings\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Pläne auf Werkseinstellungen zurücksetzen</button>
            <button name=\"new_plan_settings\"  id=\"new_plan_settings\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Änderungen Pläne speichern</button>
           
            <button name=\"save_wp_defaultsettings\" id=\"save_wp_defaultsettings\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Verwaltung auf Werkseinstellungen zurücksetzen</button>    
            <button name=\"new_save_wpsettings\"  id=\"new_save_wpsettings\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Änderungen der Verwaltung speichern</button>
           ";
        $h.="<textarea id=\"settingsjson\" name=\"settings\" rows=\"3\">";
        $h.="</textarea>";

        $h.="</form></div></div>";




        $h.="<div class=\"col-sm-6\">
                    <div class=\"card-box\">
                    <form method='post'>
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>SAP Materialverzeichnis importieren</b></h4>";
        $h.="Das Materialverzeichnis erstellt eine Datentabelle für jeden Artikel. Die Reihenfolge der der Felder ist definiert:<br>
        <ul><li>1. Materialnummmer</li><li>2. Bezeichnung</li><li>3. EAN13 Handelcode</li></ul>";


        $h.=$d->ShowHtmlInputField();
        $h.="<hr><br><br>";

        $h.="<button name=\"save_import_sapproperties\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>SAP Daten importieren</button>
 
            </form></div></div>";
        return $h;
    }   //FISSetup


    function DBUpdate()
    {
        $h="<div class=\"col-sm-6\">
             <div class=\"card-box\">
                    <form method='post'>
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Einfaches Software-Update durchführen</b></h4>";
        $h.="ACHTUNG: Diese Funktion überschreiben bisherige Daten und nur bei der Erstmigration verwenden";
        $h .= " <hr>
             <br>Datenbankupdate<br>
             <button name=\"fis_update\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>FIS Update durchführen</button>
           ";
        $h .= "<hr>Test Upload Directories:<br>";
        $tempFolder = ini_get('upload_tmp_dir <br>');

        $h .= 'Your upload_tmp_dir directive has been set to: "' . $tempFolder . '"<br>';


        if (!is_dir($tempFolder)) {
            $h .= ($tempFolder . ' does not exist! <br>');
        } else {
            $h .= 'The directory "' . $tempFolder . '" does exist.<br>';
        }

        if (!is_writable($tempFolder)) {
            $h .= ($tempFolder . ' is not writable! <br>');
        } else {
            $h .= 'The directory "' . $tempFolder . '" is writable. All is good.<br>';
        }
        $modal = "<div id='viewphpinfo'></div>";
        $modal .= "<script>
            function Viewphpinfo(){
                 \$(\"#viewphpinfo\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                 \$(\"#viewphpinfo\").load(\"phpinfo.php?sess=" . $_SESSION['sess'] . "&datatype=phpinfo\" , function(responseTxt, statusTxt, xhr){
           
                toastr[\"success\"](\"PHP Einstellungen aktualisiert\", \"Einstellungen aktualisiert\");
                
                 });
                
            };
            
            </script>";

        $modal .= "<div class='col-lg-12'>";
        $modal .= "<a href='#' class='btn btn-custom btn-sm pull-right' onclick='Viewphpinfo();'>PHP Einstellungen aktualisieren</a>";
        $modal .= "</div>";

        $h .= asModal(4000, $modal, "", 90, "PHP Info");

        $h .= "  <a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-lg btn-custom waves-effect waves-light\" >PHP Einstellungen</a>";
        $h .= "</form></div></div>";
        return $h;
    }

    function ImportIfis9()
    {
        $h = "<div class=\"col-sm-6\">
             <div class=\"card-box\">
                    <form method='post'>
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Import aus FIS 9 und FIS 10 </b></h4>";
        $h .= "ACHTUNG: Diese Funktion überschreiben bisherige Daten und nur bei der Erstmigration verwenden. Es werden Stammdaten und Bewegungsdaten unwiderruflich geändert. Zwingend Datenbanksicherung durchführen.";

        $h.="<hr><br>Verschiebe Fehlermeldungen Dokumente und setzte Status auf abgerechnet<br>
             <button name=\"save_fis9_import_FMID_data\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Import FMID data</button>
             <button name=\"SetFinalFMIDfromFis9\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Fehlermeldungen abrechnen</button>
            
            <hr>
            <br>Lege Versionen an und verschiebe Artikeldaten Bilder und Dokumente <br>
             <button name=\"save_fis9_import_material_versionen\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Versionen importieren und Erstmuster anlegen</button>
             <button name=\"ImportArtikelPicturesfromFis9\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Bilder für Artikeldaten importieren</button>
            <hr>
            <br>Versionsdaten aktualisieren<br>
            <button name=\"UpdateVersionPrintSettings\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Druckeinstellungen und Logo und Icon importieren</button>
              
            
             <hr>
            <br>Equipment / Events - alle Ereignisse quittieren <br>
             <button name=\"SetEventfromFis9\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Ereignisse quttieren</button>
            
            <hr>
            </form></div></div>";
        return $h;
    }

    function ShowHtmlFISUpdate()
    {
        $h = $this->DBUpdate();

        $h.= $this->ShowHtmlWpDefaults();
        $h.= $this->ShowHtmlUpdateOrderNumbers();

        $h .= $this->ImportIfis9();

        $h.=$this->ShowHtmlProtocol();
        return $h;
    }

    function ShowHtmlWpPrintSettings(){
        $wp=new FisWorkplace($_SESSION['wp']);
        $h="<div class=\"col-sm-10\">
                <form method='post' action='".$wp->GetLink()."'>
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Druckerzuordnung für den Arbeitsplatz ".$wp->wp." ändern</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                             <th>bisheriger Drucker</th>
                                <th>Drucker</th>
                                 <th>Ausrichtung von Oben</th>
                                   <th>Ausrichtung von Links</th>
                               
                            </tr>
                            </thead>
                            <tbody>";

        if (isset($wp->data['printer']['einbau']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['einbau']['link'] . "</td>
                                <td>" . asSelectBox("Einbauetikett", "m", $wp->GetAvailablePrinterByType("einbau", "select", $wp->data['printer']['einbau']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "mtop", $wp->GetPrinterCorrection("einbau", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "mleft", $wp->GetPrinterCorrection("einbau", "left"), 7, "") . "</td>
                            </tr>";
        }

        if (isset($wp->data['printer']['typenschild']['link'])) {
            $h .= "    <tr>
                                <td>" . $wp->data['printer']['typenschild']['link'] . "</td>

                                <td>" . asSelectBox("Typenschild", "ts", $wp->GetAvailablePrinterByType("typenschild", "select", $wp->data['printer']['typenschild']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "tstop", $wp->GetPrinterCorrection("typenschild", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "tsleft", $wp->GetPrinterCorrection("typenschild", "left"), 7, "") . "</td>
                            </tr>";
        }
        if (isset($wp->data['printer']['verpackung']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['verpackung']['link'] . "</td>
                                <td>" . asSelectBox("Verpackungsetikett", "vp", $wp->GetAvailablePrinterByType("verpackung", "select", $wp->data['printer']['verpackung']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "vptop", $wp->GetPrinterCorrection("verpackung", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "vpleft", $wp->GetPrinterCorrection("verpackung", "left"), 7, "") . "</td>
                            </tr>";
        }
        if (isset($wp->data['printer']['dokuset']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['dokuset']['link'] . "</td>
                                <td>" . asSelectBox("Dokuset", "doku", $wp->GetAvailablePrinterByType("dokuset", "select", $wp->data['printer']['dokuset']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "dokutop", $wp->GetPrinterCorrection("dokuset", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "dokuleft", $wp->GetPrinterCorrection("dokuset", "left"), 7, "") . "</td>
                            </tr>";
        }
        if (isset($wp->data['printer']['fehlermeldung']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['fehlermeldung']['link'] . "</td>
                                <td>" . asSelectBox("Fehlermeldung", "fm", $wp->GetAvailablePrinterByType("fehlermeldung", "select", $wp->data['printer']['fehlermeldung']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "fmtop", $wp->GetPrinterCorrection("fehlermeldung", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "fmleft", $wp->GetPrinterCorrection("fehlermeldung", "left"), 7, "") . "</td>
                            </tr>";
        }
        if (isset($wp->data['printer']['wareneingang']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['wareneingang']['link'] . "</td>
                                <td>" . asSelectBox("Wareneingang", "we", $wp->GetAvailablePrinterByType("wareneingang", "select", $wp->data['printer']['wareneingang']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "wetop", $wp->GetPrinterCorrection("wareneingang", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "weleft", $wp->GetPrinterCorrection("wareneingang", "left"), 7, "") . "</td>
                            </tr>";
        }
        if (isset($wp->data['printer']['neutraletikett']['link'])) {
            $h .= "    <tr>
                              <td>" . $wp->data['printer']['neutraletikett']['link'] . "</td>
                                <td>" . asSelectBox("Neutraletikett", "we", $wp->GetAvailablePrinterByType("neutraletikett", "select", $wp->data['printer']['neutraletikett']['link']), 7, "") . "</td>
                                <td>" . asTextField("Abstand oben in mm", "wetop", $wp->GetPrinterCorrection("neutraletikett", "top"), 7, "") . "</td>              
                                <td>" . asTextField("Abstand Links in mm", "weleft", $wp->GetPrinterCorrection("neutraletikett", "left"), 7, "") . "</td>
                            </tr>";
        }
        $h .= "          </tbody>
                        </table>
                     
                
              </div></div></div>";

        $h.="<div class=\"col-lg-2\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h.="<a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="<hr><div class=\"form-group \">
            <button name=\"save_wp_printersettings\" onclick='refresh_site()' type=\"submit\" class=\"btn btn-primary waves-effect waves-light\"> Speichern </button>";
        $h.="</div>";
        $h.="<hr><div class=\"form-group \">";
        $h.="<button name=\"save_wp_defaultprintersettings\" onclick='refresh_site()' type=\"submit\" class=\"btn btn-error waves-effect waves-light\">Auf Werks-<br>einstellungen<br> zurücksetzen</button>";
        $h.="</div>";

        $h.="</div></div></form></div>";
        return $h;
    }


    function JsonScripts(){
        $h=" <link href=\"../plugins/jsoneditor/jsoneditor.css\" rel=\"stylesheet\" type=\"text/css\">
            <script src=\"../plugins/jsoneditor/jsoneditor.js\"></script>";
        return $h;
    }
    function JsonEditor3x(){

        $h="<script>
             // create the editor
              var container = document.getElementById('jsoneditor');
              var options = {};
              var editor = new JSONEditor(container, options);
              // set json
              document.getElementById('setPlans').onclick = function () {
                var json1 =";

        $h.=str_replace(",",",".chr(13),json_encode($this->GetPlans() ));

        $h.="
        ;
                editor.set(json1);
                $('#settingsjson').value='';
                $('#new_tool_settings').hide();
                $('#save_tool_defaultsettings').hide();
                $('#new_plan_settings').show();
                $('#save_plan_defaultsettings').show();
                $('#new_save_wpsettings').hide();
                $('#save_wp_defaultsettings').hide();
              };
              
                 document.getElementById('setWp').onclick = function () {
                var json3 =";

        $h.=str_replace(",",",".chr(13),json_encode($this->GetFisSettings() ));

        $h.="
        ;
                editor.set(json3);
                $('#settingsjson').value='';
                $('#new_tool_settings').hide();
                $('#save_tool_defaultsettings').hide();
                $('#new_plan_settings').hide();
                $('#save_plan_defaultsettings').hide();
                $('#new_save_wpsettings').show();
                $('#save_wp_defaultsettings').show();
              };
              
          document.getElementById('setTools').onclick = function () {
                var json2 =";

        $h.=str_replace(",",",".chr(13),json_encode($this->GetTools() ));

        $h.="
        ;
                editor.set(json2);
                $('#settingsjson').value='';
                $('#new_tool_settings').show();
                $('#save_tool_defaultsettings').show();
                $('#new_plan_settings').hide();
                $('#save_plan_defaultsettings').hide();
                 $('#new_save_wpsettings').hide();
                $('#save_wp_defaultsettings').hide();
              };
              // get json
              document.getElementById('getJson').onclick = function () {
                var json = editor.get();
                document.getElementById('settingsjson').value=JSON.stringify(json, null, 2);
                alert(JSON.stringify(json, null, 2));
              };
            </script>";
        $_SESSION['jsoneditor']=$h;

    }

    function SetSessionScript3x(){

            $_SESSION['endscripts'] = "
           
            $(\"#settingsjson\").value=\"\";
            $(\"#settingsjson\").hide();
            $(\"#new_tool_settings\").hide();
            $(\"#save_tool_defaultsettings\").hide();
            $(\"#new_plan_settings\").hide();
            $(\"#save_plan_defaultsettings\").hide();
            $('#new_save_wpsettings').hide();
            $('#save_wp_defaultsettings').hide();
        ";
    }

    function SetSessionScript1x(){
            $_SESSION['endscripts'] = "
           
            $(\"#settingsjson\").value=\"\";
            $(\"#settingsjson\").hide();
            $('#new_save_wpsettings').hide();
            $('#save_wp_defaultsettings').hide();
        ";

    }

    function JsonEditor1x(){

        $h="<script>
             // create the editor
              var container = document.getElementById('jsoneditor');
              var options = {};
              var editor = new JSONEditor(container, options);
              // set json
              document.getElementById('setWp').onclick = function () {
                var json1 =";

        $h.=str_replace(",",",".chr(13),json_encode($this->GetFisSettings() ));

        $h.="
        ;
                editor.set(json1);
                $('#settingsjson').value='';
              
                $('#new_save_wpsettings').show();
                $('#save_wp_defaultsettings').show();
              };
              
  
    
              // get json
              document.getElementById('getJson').onclick = function () {
                var json = editor.get();
                document.getElementById('settingsjson').value=JSON.stringify(json, null, 2);
                alert(JSON.stringify(json, null, 2));
              };
            </script>";
        $_SESSION['jsoneditor']=$h;

    }


    function ShowHtmlProtocol(){
        $h= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
                    <h4 class=\"header-title m-t-0 m-b-30\">Bearbeitungsprotokoll </h4>";
        $h.=SessionToasterMessage();
        $h.="</div></div>";
        return $h;
    }

    function ShowHtmlWp(){

        $h="<div class=\"col-sm-6\">
            <div class=\"card-box\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Einstellungen ändern </b></h4><hr>";
        $h.="<button name=\"setWp\"  id=\"setWp\" class=\"btn btn-primary waves-effect waves-light\">Lade Verwaltungseinstellungen</button>";
        $h.="<button name=\"getJson\"  id=\"getJson\" class=\"btn btn-success waves-effect waves-light\">Prüfen</button>";

        $h.="<form method='post'>";
        $h.="<div id='jsoneditor'> </div> ";
        $h.="<hr><br><br>
          
            <button name=\"save_wp_defaultsettings\" id=\"save_wp_defaultsettings\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Verwaltung auf Werkseinstellungen zurücksetzen</button>    
            <button name=\"new_save_wpsettings\"  id=\"new_save_wpsettings\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Änderungen der Verwaltung speichern</button>
           ";
        $h.="<textarea id=\"settingsjson\" name=\"settings\" rows=\"3\">";
        $h.="</textarea></form></div></div>";

        return $h;
    }

    function ShowHtmlGLSGenerator()
    {

        $_SESSION['gls_delivery'] = $_REQUEST['gls_delivery'];
        $_SESSION['gls_telefon'] = $_REQUEST['gls_telefon'];
        $_SESSION['gls_weight'] = $_REQUEST['gls_weight'];
        $h = "<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h .= "<h4 class=\"m-b-30 m-t-0 header-title\"><b>Versandlabel Generator</b></h4><hr>";

        $h .= "<form method='post'><div class=\"col-sm-12\">";
        $h .= "<table class=\"table table-striped\"><tbody><tr><td>";
        $h .= asTextarea("Lieferanschrift", "gls_delivery", $_SESSION['gls_delivery'], 10, 7);
        $h .= "</td></tr><tr><td>";
        $h .= asTextField("Telefonnummer", "gls_telefon", $_SESSION['gls_telefon'], 7, "");
        $h .= "</td></tr><tr><td>";
        $h .= asTextField("Gewicht in kg", "gls_weight", $_SESSION['gls_weight'], 7, "");
        $h .= "</td></tr></tbody></table>";
        $h .= "</div></div>
            <div class=\"card-box table-responsive\">";
        $h .= "<h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h .= "
          
            <a href=\"./index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-left\" onclick='refresh_site()'>zurück</a>    
            <button name=\"set_glsgenerator\"  id=\"set_glsgenerator\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='zmdi zmdi-file'></i> Pdf-Voransicht erstellen</button>
            <button name=\"print_glsgenerator\"  id=\"print_glsgenerator\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='fa fa-print'></i> Pdf drucken</button>
        
           ";
        $h .= "";
        $h .= "</form></div></div>";

        $h .= "<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h .= "<h4 class=\"m-b-30 m-t-0 header-title\"><b>Voransicht</b></h4><hr>";
        $h .= "<iframe id='iframeprint' width='100%' height='550'></iframe>";
        $h .= "</div></div>";

        $_SESSION['endscripts'] = "
        
        
        function print(url)
              {
                  var _this = this,
                      iframeId = 'iframeprint',
                      \$iframe = \$('iframe#iframeprint');
                  \$iframe.attr('src', url);
            
                 
              }
         print(\"./print.gls.php?sess=" . $_SESSION['sess'] . "&asview=1\");
         
         
        ";
        return $h;
    }

    function ShowHtmlBarcodeGenerator(){

        $_SESSION['bc_compare'] = $_REQUEST['bc_compare'];
        $_SESSION['bc_material']=$_REQUEST['bc_material'];
        $_SESSION['bc_version']=$_REQUEST['bc_version'];
        $_SESSION['bc_fsn1']=$_REQUEST['bc_fsn1'];
        $_SESSION['bc_fsn2']=$_REQUEST['bc_fsn2'];
        $_SESSION['bc_fsn3']=$_REQUEST['bc_fsn3'];

        if ($_SESSION['material']>0 && $_REQUEST['bc_material']==""){
            $m=new FisMaterial($_SESSION['material']);

            $_SESSION['bc_material']=$m->GetMaterial();
            $_SESSION['bc_version']=$m->GetSelectedVersion();

        }



        $h="<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Barcode Generator</b></h4><hr>";

        $h.="<form method='post'><div class=\"col-sm-12\">";
        $h .= "<table class=\"table table-striped\"><tbody><tr><td>";
        $h .= asSwitchery("ähnliches Material mit ausdrucken", "bc_compare", $_SESSION['bc_compare'], "00b19d", 3);
        $h .= "</td></tr><tr><td>";
        $h.=asTextField("Material","bc_material",$_SESSION['bc_material'],5,"");
        $h .= "</td></tr><tr><td>";
        $h.=asTextField("Version","bc_version",$_SESSION['bc_version'],5,"");
        if ($_SESSION['material']>0 ) {
            $m=new FisMaterial($_SESSION['material']);
            $r = explode(";", $m->data['merkmale']);
            $count = count($r);
            for ($i=0;$i<$count; $i++) {
                if (strlen($r[$i])>0){
                    $h .= "</td></tr><tr><td>";
                    $h .= asTextField($r[$i], "bc_fsn" . ($i + 1), $_SESSION['bc_fsn' . ($i + 1)], 5, "");
                }
            }
        }else{
            $h .= "</td></tr><tr><td>";
            $h .= asTextField("fsn1", "bc_fsn1", $_SESSION['bc_fsn1'], 5, "");
            $h .= "</td></tr><tr><td>";
            $h .= asTextField("fsn2", "bc_fsn2", $_SESSION['bc_fsn2'], 5, "");
            $h .= "</td></tr><tr><td>";
            $h .= asTextField("fsn3", "bc_fsn3", $_SESSION['bc_fsn3'], 5, "");
            $h .= "</td></tr><tr><td>";
            $h .= asTextField("fsn4", "bc_fsn4", $_SESSION['bc_fsn4'], 5, "");
        }
        $h .= "</td></tr></tbody></table>";
        $h.="</div></div>
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h.="
          
            <a href=\"./index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-left\" onclick='refresh_site()'>zurück</a>    
            <button name=\"set_barcodegenerator\"  id=\"set_barcodegenerator\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='zmdi zmdi-file'></i> Pdf-Voransicht erstellen</button>
            <button name=\"print_barcodegenerator\"  id=\"print_barcodegenerator\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='fa fa-print'></i> Pdf drucken</button>
        
           ";
        $h.="";
        $h.="</form></div></div>";

        $h.="<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Voransicht</b></h4><hr>";
        $h.="<iframe id='iframeprint' width='100%' height='550'></iframe>";
        $h.="</div></div>";

        $_SESSION['endscripts']="
        
        
        function print(url)
              {
                  var _this = this,
                      iframeId = 'iframeprint',
                      \$iframe = \$('iframe#iframeprint');
                  \$iframe.attr('src', url);
            
                 
              }
         print(\"./print.barcodegenerator.php?sess=".$_SESSION['sess']."&asview=1\");
         
         
        ";
        return $h;
    }


    function ShowHtmlEtikettGenerator(){

        $_SESSION['txt_size']=$_REQUEST['txt_size'];
        if ($_SESSION['txt_size']<=5){$_SESSION['txt_size']=80;}
        $_SESSION['etikett_txt']=$_REQUEST['etikett_txt'];
        $_SESSION['summernote']=1;
        $opt="<option value=\"\">Leere Vorlage</option>";
        $opt.="<option value=\"<h1>INVENTUR ".date('Y',time())."</h1><br><br><br>Material:_______________________________<br><br>Erfassungsmenge:_______________________<br><br>Unterschrift:_____________________________\">Inventuraufkleber</option>";

        $opt.="<option value=\"<h4>Sonder<h4>\">Sonder</option>";

        $opt.="<option value=\"<h4>BLT 160 K<h4>\">BLT 160 K</option>";
        $opt.="<option value=\"<h4>BLT 320 K<h4>\">BLT 320 K</option>";

        $opt.="<option value=\"<h4>BLT 320 ECO<h4>\">BLT 320 ECO</option>";
        $opt.="<option value=\"<h4>BLT 320 ECO-C<h4>\">BLT 320 ECO-C</option>";

        $opt.="<option value=\"<h4>BLT 320 KB<h4>\">BLT 320 KB</option>";
        $opt.="<option value=\"<h4>BLT 320 KB R <h4>\">BLT 320 KB R</option>";

        $opt.="<option value=\"<h4>BLT 420 KBUH<h4>\">BLT 420 KBUH</option>";
        $opt.="<option value=\"<h4>BLT 420 KB R UH<h4>\">BLT 420 KB R UH</option>";

        $opt.="<option value=\"<h4>BLT 620 KBUH<h4>\">BLT 620 KBUH</option>";
        $opt.="<option value=\"<h4>BLT 620 KB R UH<h4>\">BLT 620 KB R UH</option>";


        $h="<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Etikett Generator</b></h4><hr>";

        $h.="<form method='post'><div class=\"col-sm-12\">";




        $h.="<textarea id=\"etikett_txt\" name=\"etikett_txt\" class=\"summernote\">".$_SESSION['etikett_txt']."</textarea>";


        $h.="<div>";
        $h.=asSelectBox("Vorlagen","txt_templates",$opt,7,"","onChange=\"Load_template()\" ");
        $h.=asTextField("Textgröße","txt_size",$_SESSION['txt_size'],3);
        $h.="</div>";

        $h.="</div></div>
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h.="
          
            <a href=\"./index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-left\" onclick='refresh_site()'>zurück</a>    
            <button name=\"set_neutraletikett\"  id=\"set_neutraletikett\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='zmdi zmdi-file'></i> Pdf-Voransicht erstellen</button>
            <button name=\"print_neutraletikett\"  id=\"print_neutraletikett\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" onclick='refresh_site()'><i class='fa fa-print'></i> Pdf drucken</button>
        
           ";
        $h.="";
        $h.="</form></div></div>";

        $h.="<div class=\"col-sm-6\">
            <div class=\"card-box table-responsive\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Voransicht</b></h4><hr>";
        $h.="<iframe id='iframeprint' width='100%' height='550'></iframe>";
        $h.="</div></div>";

        $_SESSION['functions'].="
                $('#etikett_txt').summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: true                 // set focus to editable area after initializing summernote
                });
             
             
                function Load_template(){
                    var txt=$(\"#txt_templates\").val();
                   
                   $('#etikett_txt').summernote('code', txt);
                  
                }
                ";


        $_SESSION['endscripts'].="
        
        
        function print(url)
              {
                  var _this = this,
                      iframeId = 'iframeprint',
                      \$iframe = \$('iframe#iframeprint');
                  \$iframe.attr('src', url);
            
                 
              }
         print(\"./print.etikett.php?sess=".$_SESSION['sess']."&asview=1\");
         
         
        ";
        return $h;
    }
    function PrintEtikettGenerator(){
        include("print.etikett.php");
    }


    function PrintBarcodeGenerator(){
        include("print.barcodegenerator.php");
    }

    function PrintGLSGenerator()
    {
        include("print.gls.php");
    }

    function ShowHtmlUpdateOrderNumbers(){

        $h="<div class=\"col-sm-6\">
            <div class=\"card-box\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Auftragsabrechnung für Nacharbeitsaufträge ändern </b></h4><hr>";

        $h.="<form method='post'>";
        $h.=asTextField("alte Auftragsnummer","order_old",$_SESSION['order_old']);
        $h.=asTextField("neue Auftragsnummer","order_new",$_SESSION['order_new']);

        $h.="<hr><br><br>
          
            <button name=\"UpdatePCNAOrder\" id=\"UpdatePCNAOrder\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Auftragsnummer für PCNA aktualiseren</button>    
            <button name=\"UpdatePCNAGWLOrder\"  id=\"UpdatePCNAGWLOrder\"  type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Auftragsnummer für PCNA für Gewährleistung aktualiseren</button>
           ";
        $h.="</form></div></div>";

        return $h;
    }


    function ShowHtmlWpDefaults(){
        $h="<div class=\"col-sm-6\">
            <div class=\"card-box\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Arbeitsplätze anlegen und überschreiben (Workplace)</b></h4><hr>";

        $h.="<form method='post'>";
        $h.="<button name=\"save_allwp_defaultsettings\" id=\"save_allwp_defaultsettings\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>WP zurücksetzen</button>";
        $h.="<button name=\"setWpFolder\"  id=\"setWpFolder\" class=\"btn btn-primary waves-effect waves-light\">WP Ordner anlegen</button>";
        $h .= "<button name=\"CreateWorkplacePrinter\"  id=\"CreateWorkplacePrinter\" class=\"btn btn-primary waves-effect waves-light\">Ordner für WP Drucker und Media Upload anlegen</button>";
        $h.="</form></div></div>";

        return $h;

    }
}