<?php

include_once ('../apps/class.phpmailer.php');

class ifisMailer
{
    private $mail=array();
    private $att=array();

    function __construct($m=array())
    {
        $this->mail=$m;
        $this->SetDefaultFooter();
        $this->SetDefaultBody();
        $this->SetDefaultFooterPicture();
        $this->SetSignatur("",true);
    }

    function SetFROM($data){
        $this->mail['FROM']=$data;
    }

    function SetName($data){
        $this->mail['name']=$data;
    }
    function SetCC($data){
        $this->mail['CC']=$data;
    }
    function SetBCC($data){
        $this->mail['BCC']=$data;
    }

    function SetSubject($data){
        $this->mail['subject']=$data;
    }

    function SetDefaultFooter(){
        $this->SetFooter(utf8_encode("ALBA RECYCLING GmbH - Werk Eisenhüttenstadt"));
    }
    function SetDefaultBody()
    {
        $this->SetContent(utf8_encode("Mit feundlichen Grüßen"));
    }
    function SetDefaultFooterPicture(){

        $this->att[]=array(
            'path' => "../media/logo1mini.png",
            'name' => "logo.png",
            'encoding' => "base64",
            'app' => "application/octet-stream"
        );
    }

    function SetFooter($data){
        $this->mail['footer']=$data;
    }

    function SetAttachment($att=array()){
        $this->att[]=$att;
    }

    function SetContent($data, $delete=false){
        $this->mail['content'] = $data;
    }

    function SetSignatur($data="", $delete=false){
        if (strlen($data)==0){
            $u= new ifisUser($_SESSION['userid']);

            $this->mail['signatur'] =utf8_encode($u->data['name']).chr(10)
                .utf8_encode($u->data['firma']).chr(10)
                .utf8_encode($u->data['anschrift']).chr(10)
                .$u->data['plz']." ".utf8_encode($u->data['ort']).chr(10);

            $this->mail['name'] =utf8_decode(utf8_decode($u->data['name']));
            $this->mail['FROM'] =$u->data['email'];
        }else {
            $this->mail['signatur'] = $data;
        }
    }

    function ValidateMail(){
        if (strlen($this->mail['CC'])>0){
            $validastate=true;
        }else{
            $validastate=false;
        }

        if (strlen($this->mail['subject'])==0){
            $validastate=false;
        }
        if (strlen($this->mail['content'])==0){
            $validastate=false;
        }

        if (!$validastate){
            AddSessionMessage("warning", "Eingaben sind unvollständig.");
        }
        return $validastate;
    }


    function GetCC(){
        return $this->mail['CC'];
    }
    function GetBCC(){
        return $this->mail['BCC'];
    }
    function GetSubject(){
        return $this->mail['subject'];
    }
    function GetFirstContent(){
        return  $this->mail['body'].chr(10).chr(10)
        .$this->mail['content'].chr(10)
        .$this->mail['signatur'].chr(10)
        .$this->mail['footer'].chr(10);
    }

    function GetPreviewContent(){
        $html="<HTML></HTML><meta charset=\"utf-8\"><head><title>E-Mail</title></head><BODY>";
        $html.=utf8_decode(utf8_decode(nl2br($this->mail['content'])));
        $html.="</BODY></HTML>";
        return $html;
    }

    function GetContent(){
        return $this->mail['content'];
    }

    function GetAttachment($index){
        return array(
            'path'      =>$this->att[$index]['path'],
            'name'      =>$this->att[$index]['name'],
            'encoding'  =>$this->att[$index]['encoding'],
            'app'       =>$this->att[$index]['app']
        );
    }

    function SendifisMail(){
        if ($this->ValidateMail()){
            $mail=new PHPMailer();
            $mail->AddReplyTo($this->mail['FROM'],$this->mail['name']);
            $mail->SetFrom("automatic@albafis.de",$this->mail['name']);
            $mail->AddAddress($this->mail['CC']);

            $mail->Subject=utf8_decode($this->mail['subject']);
            $mail->AltBody="To view the message, please use an HTML compatible email viewer.";

            $mail->MsgHTML(($this->GetPreviewContent()) );
            if (count($this->att)>0){
                foreach ($this->att as $i=>$item){
                    $attr=$this->GetAttachment($i);
                    $mail->AddAttachment($attr['path'],$attr['name'],$attr['encoding'],$attr['app']);
                }
            }
            if (!$mail->Send()){
                AddSessionMessage("danger", "E-Mail ".$_POST['cc']." wurde abgebrochen.");
            }else{
                AddSessionMessage("success", "E-Mail ".$_POST['cc']." gesendet.");
            }
        }
    }
}