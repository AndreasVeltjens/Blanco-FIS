<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.10.18
 * Time: 21:29
 */
class FisTool
{
    public $bks=6101;

    public $tool=array(
        1=>array(
            1=>array("id"=>1,"name"=>"BLT 620 KBUH Deckel 2-fach"),
            2=>array("id"=>2,"name"=>"BLT 420 KBUH Deckel 2-fach"),
            3=>array("id"=>3,"name"=>"BLT Deckel 60H 2-fach"),

            4=>array("id"=>4,"name"=>"BLT 620 Innenteil"),
            5=>array("id"=>5,"name"=>"BLT 420 Innenteil"),
            6=>array("id"=>6,"name"=>"BLT 320 K Innenteil"),
            7=>array("id"=>7,"name"=>"BLT 320 KB Innenteil"),
            8=>array("id"=>8,"name"=>"BLT 320 ECO Innenteil"),

            9=>array("id"=>9,"name"=>"BLT 320 K Außenteil"),
            10=>array("id"=>10,"name"=>"BLT 320 KB Außenteil"),
            11=>array("id"=>11,"name"=>"BLT 320 ECO Außenteil"),
            12=>array("id"=>12,"name"=>"BLT 420 Außenteil"),
            13=>array("id"=>13,"name"=>"BLT 620 Außenteil"),

        ),
        2=>array(
            1=>array("id"=>1,"name"=>"BLT 620 GK Außenteil")

        ),
        3=>array(
            1=>array("id"=>1,"name"=>"Twinsheet BLT Deckel"),
            2=>array("id"=>2,"name"=>"Twinsheet BLT ECO Deckel")

        ),
        4=>array(
            1=>array("id"=>1,"name"=>"BLT Deckel 60 H"),
            2=>array("id"=>2,"name"=>"BLT ECO Deckel")

        ),
        5=>array(
            1=>array("id"=>1,"name"=>"BLT 320 K GK"),
            2=>array("id"=>2,"name"=>"BLT 320 KB GK")

        ),
        6=>array(
            1=>array("id"=>1,"name"=>"BLT 420 GK"),
            2=>array("id"=>2,"name"=>"BLT 620 GK")

        ),
        7=>array(
            1=>array("id"=>1,"name"=>"Twinsheet BLT Deckel"),
            2=>array("id"=>2,"name"=>"Twinsheet BLT ECO Deckel")

        ),
        8=>array(
            1=>array("id"=>1,"name"=>"Twinsheet BLT Deckel"),
            2=>array("id"=>2,"name"=>"Twinsheet BLT ECO Deckel")

        ),
        9=>array(
            1=>array("id"=>1,"name"=>"BLT 620 KBUH Grundkörper"),
            2=>array("id"=>2,"name"=>"BLT 420 KBUH Grundkörper"),
            3=>array("id"=>3,"name"=>"BLT 620 K Grundkörper"),
            4=>array("id"=>4,"name"=>"BLT 420 K Grundkörper"),

        ),
        10=>array(
            1=>array("id"=>1,"name"=>"BLT 320 K Grundkörper"),
            2=>array("id"=>2,"name"=>"BLT 320 KB Grundkörper"),
            3=>array("id"=>3,"name"=>"BLT 320 ECO Grundkörper"),
            4=>array("id"=>4,"name"=>"BLT 160 K Grundkörper"),

        ),
        11=>array(
            1=>array("id"=>1,"name"=>"BLT 620 KBUH Deckel"),
            2=>array("id"=>2,"name"=>"BLT 420 KBUH Deckel"),
            3=>array("id"=>3,"name"=>"BLT 320 K Deckel"),
            4=>array("id"=>4,"name"=>"BLT 620 KUS Deckel"),
            5=>array("id"=>5,"name"=>"BLT 620 KUF Deckel"),
            6=>array("id"=>6,"name"=>"BLT 320 ECO Deckel"),
            7=>array("id"=>7,"name"=>"BLT 320 KB Deckel")
        ),
    );

    public $fhmid=0;
    public $data=array();

    function __construct($i=0)
    {
        $this->fmid=$i;
        $this->LoadData();
    }
    function __toString(){
        return $this->fhmid;
    }

    function LoadData(){

        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fissettings WHERE fissettings.wp = 0 AND fissettings.type='tool' LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=json_decode(utf8_decode($row_rst['settings']),true);

        }else{
            $this->data=$this->tool;
            AddSessionMessage("warning","Werkzeug <code> FHMID $this->fmid </code> hat keine Datensätze -> Default geladen.","Datenbankfehler");
        }

    }
    function AddTool($s){
        include ("./connection.php");
        $sql="SELECT * FROM fissettings WHERE fissettings.wp = 0 AND fissettings.`type`='tool' LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)==0){

            $updateSQL = sprintf("INSERT INTO  fissettings (wp, bks, settings,`type`) VALUES (%s, %s, %s, %s)",
                GetSQLValueString($this->fhmid, "int"),
                GetSQLValueString($this->bks, "int"),
                GetSQLValueString($s,"text"),
                GetSQLValueString("tool","text")
                );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error","Automatisches Laden von Werkzeuglisten  <code> WP" . $this->fhmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Werkzeuglisten  <code> FMID" . $this->fmid . "</code> wurde neu angelegt.", "Create Settings");

            }
        }
    }
    function SaveToolSettings(){
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
        if (isset($_REQUEST['save_tool_defaultsettings'])) {
            $s=json_encode($this->tool);
            $txt="Default Tool Settings";
        }

        $s=utf8_encode($s);

        $this->AddTool($s); //check Wp in DB ?

        if (count(json_decode($s,true))>0){
            $updateSQL=sprintf("
                UPDATE fissettings SET fissettings.settings=%s 
                WHERE fissettings.wp=0 AND fissettings.`type`='tool' ",
                GetSQLValueString($s,"text"));
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error","Änderung von Einstellungen von  <code> FMID" . $this->fmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

            }else{
                AddSessionMessage("success", "$txt von <code> FMID" . $this->fmid . "</code> wurde gespeichert.", "Update Settings");
            }
        }else{
            AddSessionMessage("error","JSON-Datenformat der Einstellung <code> FMID" . $this->fmid . "</code> sind fehlerhaft.","Abbruch");
        }
    }

    function GetTools(){
        if (count($this->data)>0){
            $r=$this->data;
        }else{
            $r=$this->tool;
        }



        return $r;
    }

}