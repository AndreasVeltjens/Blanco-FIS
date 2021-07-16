<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.12.18
 * Time: 20:47
 */
class FisGauge
{

    public $gaugeid=0;
    public $value=0;
    public $height=250;
    public $width=250;
    public $unit="g";
    private $prop=array();

    function __construct($i)
    {
        $this->gaugeid=$i;
    }

    function __toString()
    {
        echo $this->gaugeid;
    }

    function SetGaugeValue($v){
        $this->value=$v;
    }
    function SetGaugeUnit($u){
        $this->unit=$u;
    }
    function SetGaugeHeight($h){
        $this->height=$h;
    }
    function SetGaugeWidth($w){
        $this->width=$w;
    }

    function SetGaugeProperties($prop){
        $this->prop=$prop;
    }

    function GetHtml(){
        $h = "<div id=\"foo" . $this->gaugeid . "\" class=\"gauge\"></div>";
        $h .= "<div class='fa fa-2x' id=\"preview-textfield" . $this->gaugeid . "\" onclick=\"SetGauge" . $this->gaugeid . "()\"> </div>";
        $h.="<div class='fa fa-2x' id=\"unit".$this->gaugeid."\"> ".$this->unit."</div>";

        $h .= "<canvas width=\"" . $this->width . "\" height=\"" . $this->height . "\" id=\"canvas-preview" . $this->gaugeid . "\" style=\"width:98%\"></canvas>";


        $this->SetSessionVars();
        return $h;
    }

    function SetSessionVars(){

        $_SESSION['gauge'].="<script>
        
      

        function SetGauge" . $this->gaugeid . "(){
          
        
            demoGauge = new Gauge(document.getElementById(\"canvas-preview" . $this->gaugeid . "\"));
                
                        var opts" . $this->gaugeid . " = " . json_encode($this->prop) . ";
                        
                        demoGauge.setOptions(opts" . $this->gaugeid . ");
                        demoGauge.setTextField(document.getElementById(\"preview-textfield" . $this->gaugeid . "\"));
                        demoGauge.minValue = 250;
                        demoGauge.maxValue = 750;
                        demoGauge.set(" . $this->value . ");
                       
                      
        };

        demoGauge = new Gauge(document.getElementById(\"canvas-preview" . $this->gaugeid . "\"));
                demoGauge.width=250;
                demoGauge.height=250;
                var opts" . $this->gaugeid . " = " . json_encode($this->prop) . ";
                
                demoGauge.setOptions(opts" . $this->gaugeid . ");
                demoGauge.setTextField(document.getElementById(\"preview-textfield" . $this->gaugeid . "\"));
                demoGauge.minValue = 250;
                demoGauge.maxValue = 750;
                demoGauge.set(" . $this->value . ");
               
               
            </script>
            ";

    }
}