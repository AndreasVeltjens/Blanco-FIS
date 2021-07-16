<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.10.18
 * Time: 21:29
 */
class FisPlan
{
    public $bks=6101;

    public $plan=array(
        1=>array(
            "desc"=>"Handarbeitsplatz",
            "plan"=>array(
                1=>array("type"=>"checkbox","pruef"=>"Funktions- und Zustandsprüfung","name"=>"i.O.","desc"=>"Überprüfung aller Betriebsmittel am Arbeitsplatz hinsichtlich Zustand und Vollständigkeit"),

                4=>array("type"=>"checkbox","pruef"=>"Reinigung","name"=>"durchgeführt","desc"=>"Etikettendrucker Typenschild "),
                5=>array("type"=>"checkbox","pruef"=>"Reinigung","name"=>"durchgeführt","desc"=>"Etikettendrucker Seriennummer "),
                6=>array("type"=>"checkbox","pruef"=>"Reinigung","name"=>"durchgeführt","desc"=>"Drucker Dokuset "),
                99=>array("type"=>"textarea","pruef"=>"Kommentar","name"=>"","desc"=>"Sonstiges"),
            )
        ),
        2=>array(
            "desc"=>"Schäumen",
            "plan"=>array(
                0=>array("type"=>"checkbox","pruef"=>"Funktions- und Zustandsprüfung","name"=>"i.O.","desc"=>"Überprüfung aller Betriebsmittel am Arbeitsplatz hinsichtlich Zustand und Vollständigkeit"),

                1=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Druckluftanschluß"),
                2=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Elektroanschluß "),
                3=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"FIS Rückmeldung "),
                4=>array("type"=>"checkbox","pruef"=>"Spaltfilter drehen","name"=>"i.O.","desc"=>"Wartung Polyol Spaltfilter"),
                5=>array("type"=>"checkbox","pruef"=>"Spaltfilter drehen","name"=>"i.O.","desc"=>"Wartung Isocyanat Spaltfilter"),
                6=>array("type"=>"checkbox","pruef"=>"Polyol IBC","name"=>"i.O.","desc"=>"Wechsel IBC Polyol - (Rührgerät umgehangen)"),
                7=>array("type"=>"checkbox","pruef"=>"Isocyanat IBC","name"=>"i.O.","desc"=>"Wechsel IBC Isocyanat"),
                99=>array("type"=>"textarea","pruef"=>"Kommentar","name"=>"","desc"=>"Sonstiges"),
            )
        ),
        3=>array(
            "desc"=>"Thermoformen",
            "plan"=>array(
                0=>array("type"=>"checkbox","pruef"=>"Funktions- und Zustandsprüfung","name"=>"i.O.","desc"=>"Überprüfung aller Betriebsmittel am Arbeitsplatz hinsichtlich Zustand und Vollständigkeit"),

                1=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Druckluftanschluß"),
                2=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Elektroanschluß "),
                3=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Wasseranschluß "),
                4=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Lichtschrankentest vor Produktionsstart "),
                5=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"FIS Rückmeldung "),
                99=>array("type"=>"textarea","pruef"=>"Kommentar","name"=>"","desc"=>"Sonstiges"),
            )
        ),
        4=>array(
            "desc"=>"Werkzeugwechsel",
            "plan"=>array(
                0=>array("type"=>"checkbox","pruef"=>"Funktions- und Zustandsprüfung","name"=>"i.O.","desc"=>"Überprüfung aller Betriebsmittel am Arbeitsplatz hinsichtlich Zustand und Vollständigkeit"),

                1=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Temperierung angeschlossen"),
                2=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Oberstempel zentriert "),
                3=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Programmeinstellungen geladen "),
                99=>array("type"=>"textarea","pruef"=>"Kommentar","name"=>"","desc"=>"Sonstiges"),

            )
        ),
        6=>array(
            "desc"=>"Schweißen / mechanische Bearbeitung",
            "plan"=>array(
                0=>array("type"=>"checkbox","pruef"=>"Funktions- und Zustandsprüfung","name"=>"i.O.","desc"=>"Überprüfung aller Betriebsmittel am Arbeitsplatz hinsichtlich Zustand und Vollständigkeit"),

                1=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Führungen gereingt"),
                2=>array("type"=>"checkbox","pruef"=>"Sicht- und Funktionsprüfung","name"=>"i.O.","desc"=>"Elektroanschluß "),
                3=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"Schutzeinrichtungen überprüft "),
                4=>array("type"=>"checkbox","pruef"=>"Funktionsprüfung","name"=>"i.O.","desc"=>"FIS Rückmeldung "),
                99=>array("type"=>"textarea","pruef"=>"Kommentar","name"=>"","desc"=>"Sonstiges"),
            )
        ),
    );

    public $planid=0;
    public $data=array();

    function __construct($i=0)
    {
        $this->planid=$i;
        if ($i>0){
            $this->LoadData();
        }
    }
    function __toString(){
        return $this->planid;
    }
    function LoadData(){

        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fissettings WHERE fissettings.wp = '".$this->planid."' AND fissettings.type='plan' LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=json_decode(utf8_decode($row_rst['settings']),true);

        }else{
            $this->data=$this->plan;
            AddSessionMessage("warning","Werkzeug <code> PLANID $this->planid </code> hat keine Datensätze -> Default geladen.","Datenbankfehler");
        }

    }


    function Add($s){
        include ("./connection.php");
        $sql="SELECT * FROM fissettings WHERE fissettings.wp = '".$this->planid."' AND fissettings.`type`='plan' LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)==0){

            $updateSQL = sprintf("INSERT INTO  fissettings (wp, bks, settings,`type`) VALUES (%s, %s, %s, %s)",
                GetSQLValueString($this->planid, "int"),
                GetSQLValueString($this->bks, "int"),
                GetSQLValueString($s,"text"),
                GetSQLValueString("plan","text")
                );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error","Automatisches Laden von Planlisten  <code> PLANID" . $this->planid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Planlisten  <code> PLANID" . $this->planid . "</code> wurde neu angelegt.", "Create Settings");

            }
        }
    }
    function SavePlanSettings(){
        include ("./connection.php");
        $s="";
        $txt="";
        if (strlen($_REQUEST['settings'])>2) {
            $settings = $_REQUEST['settings'];
            $r = str_replace("," . chr(13), ",", $settings);
            $s = str_replace("\\/", "/", $r);
            $s = str_replace("//", "/", $s);
            $txt="Tool Settings";
        }

        //Load Default Settings
        if (isset($_REQUEST['save_plan_defaultsettings'])) {
            $s=json_encode($this->plan);
            $txt="Default Tool Settings";
        }

        $s=utf8_encode($s);
        $this->Add($s); //check Wp in DB ?

        if (count(json_decode($s,true))>0){
            $updateSQL=sprintf("
                UPDATE fissettings SET fissettings.settings=%s 
                WHERE fissettings.wp='$this->plan' AND fissettings.`type`='plan' ",
                GetSQLValueString($s,"text"));
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error","Änderung von Einstellungen von  <code> PLANID" . $this->planid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

            }else{
                AddSessionMessage("success", "$txt von <code> PLANID" . $this->planid . "</code> wurde gespeichert.", "Update Settings");
            }
        }else{
            AddSessionMessage("error","JSON-Datenformat der Einstellung <code> PLANID" . $this->planid . "</code> sind fehlerhaft.","Abbruch");
        }
    }

    function GetPlans(){
        if (count($this->data)>0){
            $r=$this->data;
        }else{
            $r=$this->plan;
        }

        return $r;
    }

    function ShowHtmlPlanConfirm($i=array(),$d=0,$color="00b19d",$p=true){
        $h="";
        switch ($i['type']){
            case "":{

                $h .="<tr>
                                <td> <h3>
                                 <div class=\"checkbox checkbox-success checkbox-lg\">
                                       
                                        <input name=\"checkbox$d\" id=\"checkbox$d\" type=\"checkbox\" >
                                        <label for=\"checkbox$d\">
                                           ".$i['pruef']." ".$i['name']."
                                        </label>
                                        
                                    </div> </h3>                             
                                </td>
                                <td colspan='2'><h3>".$i['desc']."</h3></td>
                                <td>".$i['info']."</td>
                            </tr>";
                break;

            }
            case "checkbox":{
                $h.="<tr>
                        <td><h3>".$i['pruef']."</h3></td>
                       <td> <h3>";

                $h .= asSwitchery($i['desc'], "checkbox" . $d, "off", $color, 1, "");
                $h.="</h3> </td>";

                if ($p) {$h.="<td>".$i['name']."</td>";}else{$h.="<td> nicht ".$i['name']." / fehlerhaft</td>";}


                $h.="<td>".$i['info']."</td>
                            </tr>";

                break;
            }
            case "textarea":{
                $h.="<tr><td colspan='3'> <h3>";

                $h.=asTextarea($i['desc'],"checkbox".$d,"",5,10,"");

                $h.="</h3>                             
                                </td>
                              
                                <td>".$i['info']."</td>
                            </tr>";
                break;
            }

            case "textfield":{
                $h.="<tr>
                <td><h3>".$i['pruef']."</h3></td>
                <td colspan='2'> <h3>";

                $h.=asTextField($i['desc'],"checkbox".$d,"",5,10,"");

                $h.="</h3>                             
                                </td>
                                <td><h3>".$i['desc']."</h3></td>
                                <td>".$i['info']."</td>
                            </tr>";
                break;
            }
            default :{
                $h="kein Prüfschritt angelegt.";
            }
        }

        return $h;
    }

}