<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 22.10.18
 * Time: 11:29
 */
class FisDocument
{

    public $notes="";
    public $type="";
    public $id=0;
    public $bks=6101;
    public $documents=array();
    private $latest=array();

    function __construct($desc="UPLOAD",$type="FID",$id=0,$bks=6101)
    {
        $this->notes=$desc;
        $this->type=$type;
        $this->id=$id;
        $this->bks=$bks;
        $this->LoadDocumentData();

    }

    function __toString(){
        return $this->id;
    }

    function ShowHtmlInputField(){
        $s=" <div class=\"form-group\">
                <div class=\"form-group clearfix\">
                    <div class=\"col-sm-12 padding-left-0 padding-right-0\">
                        <input type=\"file\" name=\"files[]\" id=\"filer_input1\" multiple=\"multiple\">
                    </div>
                </div>
            </div>";

        $_SESSION['script'].=$this->GetScript();
        $_SESSION['LoadUploadData']=$this->GetDocumentFiles().",";
        return $s;
    }

    function CheckDirectories(){

        if (is_dir("../".$this->type)) {
        } else {
            mkdir ("../".$this->type, 0777);
            AddSessionMessage("success", "Verzeichnis  <code> ../".$this->type. "</code> wurde hochgeladen und gespeichert.", "Speicherort angelegt");
        }
        if (!is_dir("../tmpupload")) {
            mkdir("../tmpupload", 0777);
        }

        chmod("../tmpupload",0777);

        return $this;
    }

    function AddNewDocument(){

        $this->CheckDirectories();

        if (strlen($this->notes)>=1 && $this->id>0){
            $us=new FisUser($_SESSION['active_user']);
            include ("./connection.php");

            $state=1;

            $folder = "../tmpupload/" . $us->userid . "/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                //AddSessionMessage("success", "Dokument  <code> " .$file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../".$this->type."/".time(),$file);
                //AddSessionMessage("info", "Dokument in <code> " .$filename. "</code> umbenannt.", "Dokumentupload");

                if (file_exists($file)) {
                    rename($file,$filename);

                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString($this->notes, "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['active_user'], "int"),
                        GetSQLValueString(($us->data['name']), "text"),
                        GetSQLValueString($this->bks, "int"),
                        GetSQLValueString($this->id, "int"),
                        GetSQLValueString($this->type, "text"),
                        GetSQLValueString($filename, "text")
                );
                $rst = $mysqli->query($updateSQL);

                if ($mysqli->error) {

                    AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.  <code> " . $filename. "</code> <code> " . $file. "</code> <code> " . $this->type. "</code>","Fehlermeldung");
                }else{
                    $txt="Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.";
                    AddSessionMessage("success", $txt, "Dokumentupload");
                    $w=new FisWorkflow($txt,$this->type,$this->id);
                    $w->Add();
                    //$this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                }
            }
            }
        }

    }

    function AddDocumentLink($filename=""){
        $us=new FisUser($_SESSION['active_user']);
        $state=1;
        include ("./connection.php");

        if (strlen($filename)>0){
            $updateSQL = sprintf("
                            INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($this->notes, "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['active_user'], "int"),
                GetSQLValueString($us->data['name'], "text"),
                GetSQLValueString($this->bks, "int"),
                GetSQLValueString($this->id, "int"),
                GetSQLValueString($this->type, "text"),
                GetSQLValueString($filename, "text")
            );
            $rst = $mysqli->query($updateSQL);

            if ($mysqli->error) {

                AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.  <code> " . $filename. "</code> <code> " . $file. "</code> <code> " . $this->type. "</code>","Fehlermeldung");
            }else{
                $txt="Dokumentlink  <code> " . $filename. "</code> erstellt gespeichert.";
                AddSessionMessage("success", $txt, "Dokumentupload");
                $w=new FisWorkflow($txt,$this->type,$this->id);
                $w->Add();
            }
        }

    }

    function SaveNotes($text)
    {
        include("./connection.php");
        $updateSQL = sprintf("
            UPDATE dokumente SET dokumente.dnotes=%s 
            WHERE dokumente.linkedid='" . $this->id . "' ",
            GetSQLValueString($text, "text")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error", "Beschreibung <code> " . $text . "</code> konnte nicht durchgeführt werden.", "DB Fehlermeldung");
        }
    }


    function LoadDocumentData(){
        $this->documents=array();
        if ($this->id > 0){
            include("./connection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->id."' AND dokumente.dobject='".$this->type."'
                        ORDER BY dokumente.ddatum DESC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }

    function LoadDocumentLatestData($type="doc"){
        $this->latest=array();
        if ($this->id > 0){
            include("./connection.php");
            $s="";
            switch ($type){
                case "doc":
                    {
                        $s = "";
                        break;
                    }
                case "pdf":
                    {
                        $s .= "OR dokumente.documentlink like '%pdf%'";
                        break;
                    }
                case "pic":{
                    $s="AND ( dokumente.documentlink like '%png%'";
                    $s.="OR dokumente.documentlink like '%gif%'";
                    $s.="OR dokumente.documentlink like '%jpeg%'";
                    $s.="OR dokumente.documentlink like '%jpg%' )";

                    break;}
            }
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->id."' AND dokumente.dobject='".$this->type."'
                        $s
                        ORDER BY dokumente.ddatum DESC LIMIT 0,1";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->latest= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }

    function GetDocumentFiles(){
        $r=array();
        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../$this->type/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }

    function GetLatestDocumentLink($documenttype = "pdf")
    {
        $this->LoadDocumentLatestData($documenttype);
        if (count($this->latest)>0){
            return $this->latest['documentlink'];
        }else{
            return "";
        }
    }

    function GetLatestPictureLink(){
        $this->LoadDocumentLatestData("pic");
        if (count($this->latest)>0){
            return $this->latest['documentlink'];
        }else{
            return "";
        }
    }


    function GetStatus($status){
        $s="unbekannter Status";
        switch ($status){
            case 1:{ $s="Erfolgreicher Upload"; break;}
        }
        return $s;
    }

    function GetDocumentLink($item){
        $h="<img src=' ".$item['documentlink']."' class='img thumb-lg'>";
        return $h;
    }

    function GetDocumentOpen($item){
        $h="";
        if (stristr($item['documentlink'],".pdf")===false){
            $h="<a href='".$item['documentlink']."' target='_blank'> <img src=' ".$item['documentlink']."' class='img thumb-lg'> </a>";
        }else{

            $h="<a href='".$item['documentlink']."' target='_blank'> Dokument anzeigen </a>";
        }

        return $h;
    }
    function GetDocumentList(){

        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Dokument</th>
                        <th>Bemerkungen</th>
                        <th>Status</th>
                        <th>Wer</th>
                      
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td>".$this->GetDocumentOpen($item)."</td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".$this->GetStatus($item['dstate'])."</td>";
                $h .= "<td>" . utf8_decode($item['username']) . "</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        $h.="</table></div>";
        return $h;
    }


    function GetScript(){
        $s="";

        return $s;
    }
}