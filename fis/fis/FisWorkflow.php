<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 22.10.18
 * Time: 11:29
 */
class FisWorkflow
{

    public $notes="";
    public $type="";
    public $id=0;
    public $bks=6101;
    public $workflow=array();

    function __construct($desc="Text",$type="fid",$id=0,$bks=6101)
    {
        $this->notes=$desc;
        $this->type=$type;
        $this->id=$id;
        $this->bks=$bks;
        $this->LoadWorkflowData();

    }

    function __toString(){
        return $this->id."";
    }
    

    function Add(){
        
        if (strlen($this->notes)>=1 && $this->id>0){
            $us=new FisUser($_SESSION['active_user']);
            include ("./connection.php");

            $state=1;


                    $updateSQL = sprintf("
                        INSERT INTO  fisworkflow (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString($this->notes, "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['active_user'], "int"),
                        GetSQLValueString($us->data['name'], "text"),
                        GetSQLValueString($this->bks, "int"),
                        GetSQLValueString($this->id, "int"),
                        GetSQLValueString($this->type, "text")
                       
                );


                $rst = $mysqli->query($updateSQL);

                if ($mysqli->error) {

                    //$_SESSION['ZZZ']=$updateSQL;
                    AddSessionMessage("warning","Workflowstatus wurde nicht angelegt, da ein Fehler aufgetreten ist.  <code> " . $this->notes. "</code> <code> " . $this->type. "</code>","Fehlermeldung");
                }
            
            }
    }

    

    function LoadWorkflowData(){
        $this->workflow=array();
        if ($this->id > 0){
            include("./connection.php");
            $updateSQL="SELECT * FROM fisworkflow
                        WHERE fisworkflow.linkedid='".$this->id."' AND fisworkflow.dobject='".$this->type."'
                        ORDER BY fisworkflow.ddatum DESC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Worflow wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->workflow[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    

    function GetLatestWorkflow(){
        $l="";
        if (count($this->workflow)>0){
            $l=$this->workflow[0]['dnotes'];
        }else{
            $l="kein Status vorhanden";
        }
        return $l;
    }


    function GetStatus($status){
        $s="unbekannter Status";
        switch ($status){
            case 0:{ $s="Fehlerhaft"; break;}
            case 1:{ $s="Erfolgreich"; break;}
        }
        return $s;
    }

 
    function GetWorkflowList(){

        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Bemerkungen</th>
                        <th>Status</th>
                        <th>Wer</th>
                      
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->workflow)>0){
            foreach($this->workflow as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td>".utf8_decode($item['dnotes'])."</td>";
                $h.="<td>".$this->GetStatus($item['dstate'])."</td>";
                $h .= "<td>" . utf8_decode($item['username']) . "</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Statusinformationen vorhanden</td></tr>";
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