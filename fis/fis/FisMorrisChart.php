<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 04.12.18
 * Time: 23:16
 */
class FisMorrisChart
{
    public $d=array();
    public $color=array();

    function __construct($t="LineChart")
    {

        $this->SetCSS();
        $this->SetScripts();

    }

    function SetDiagramm($id,$data=array(),$color=array() ){
        $this->d[$id]=$data;
        $this->color[$id]=$color;
    }

    function LineChart(){
        $h=" //creates line chart
        MorrisCharts.prototype.createLineChart = function(element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          fillOpacity: opacity,
          pointFillColors: Pfillcolor,
          pointStrokeColors: Pstockcolor,
          behaveLikeLine: true,
          gridLineColor: '#eef0f2',
          hideHover: 'auto',
          lineWidth: '3px',
          pointSize: 0,
          preUnits: '',
          postUnits: '°C',
          resize: true, //defaulted to true
          lineColors: lineColors
        });
    },
    ";
        return $h;
    }

    function SetLineChartData($id,$color1='#ddd', $color2='#42a5f5'){
        $data=$this->d[$id];
        $h=" //create line chart
        var \$data  = ".json_encode($data).";
        this.createLineChart('morris-line-$id', \$data, 'y', ['a', 'b'], ['Series A', 'Series B'],['0.1'],['#ffffff'],['#999999'], ['$color1','$color2' ]);
";
        return $h;
    }

    function Script(){
        $h="
        !function($) {
        \"use strict\";
        var MorrisCharts = function() {};";
        return $h;

    }

    function Script2(){
        return " MorrisCharts.prototype.init = function() {";
    }

    function Script3(){
        $h="},
            //init
            $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts
        }(window.jQuery),
        
        //initializing 
        function($) {
            \"use strict\";
            $.MorrisCharts.init();
        }(window.jQuery);";
        return $h;
    }

    function GetMorrisFunction(){
        $a=array();
        $h="<script>";
        $h.=$this->Script();

        $h.=$this->LineChart();

        $h.=$this->Script2();

        foreach ($this->d as $id=>$item) {
            $h .= $this->SetLineChartData($id,$this->color[$id][1]);
        }

        $h.=$this->Script3();
        $h.="</script>";

        $_SESSION['morrisfunction'].= $h;
    }



    function SetScripts(){
        $_SESSION['morris']="
        <script src=\"../layout-2_blue/assets/plugins/morris/morris.min.js\"></script>
		<script src=\"../layout-2_blue/assets/plugins/raphael/raphael-min.js\"></script>
        ";
    }


    function SetCSS(){
        $_SESSION['css']=" 
        <!--Morris Chart CSS -->
        <link rel=\"stylesheet\" href=\"../layout-2_blue/assets/plugins/morris/morris.css\">";
    }
}