<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 15.11.18
 * Time: 16:31
 */

function AddSessionMessage($type,$text,$header="Benachrichtigung"){
    $_SESSION['e'][]=array('type'=>$type,'text'=>$text,'header'=>$header);
}
function SessionToasterMessage(){
    $e= $_SESSION['e'];
    $h="";
    if (count($e)>0) {
        foreach ($e as $item) {
            if (strlen($item['type'])>0) {
                if ($item['type']=="error") $item['type']="danger";

                $h.="<div class='alert alert-".$item['type']."'><b>".$item['header']."</b> - ";
                $h.=$item['text']."</div>";
            }
        }
        return $h;
    }
}

class FisToaster
{
    public $e=array();


    function AddSessionMessage($type,$text,$header="Benachrichtigung"){
        $this->e[]=array('type'=>$type,'text'=>$text,'header'=>$header);

        $_SESSION['e'][]=array('type'=>$type,'text'=>$text,'header'=>$header);
    }



    function SessionToaster(){
        $this->e=$_SESSION['e'];
        $n=array();
        if (count($this->e)>0) {
            foreach ($this->e as $item) {
                if (strlen($item['type'])>0) {
                    $n[] = "toastr["
                        . json_encode($item['type'])
                        . "]("
                        . json_encode($item['text'])
                        . ","
                        . json_encode($item['header'])
                        . ");";
                }
            }
            return implode("\n",$n);
        }
        $this->e=array();
    }


    function GetNotifications(){
        $this->e=$_SESSION['e'];

        $h="<li class=\"list-group-item\">
                <a href=\"#\" class=\"user-list-item\">
                   <div class=\"avatar\">
                        <i class=\"fa fa-check-circle\"> </i>
                    </div>
                    <div class=\"user-desc\">
                        <span class=\"name\">%s</span>
                        <span class=\"desc\">%s</span>
                        <span class=\"time\">%s</span>
                    </div>
                </a>
            </li>";
        $n="";
        if (count($this->e)>0) {
            foreach ($this->e as $item) {
                if (strlen($item['type'])>0) {
                    $n[] = sprintf($h,$item['header'],$item['text'],$item['type']);
                }
            }
            return implode("\n",$n);
        }else{
            $n="";
        }
    }

    function ClearNotification(){
        $_SESSION['e']=array();
    }
}