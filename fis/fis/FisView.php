<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 13.10.18
 * Time: 19:30
 */
class FisView
{
    public $script=array();

    function ShowKnop($value,$size=100,$text="Text"){


        return "<div class=\"text-center\">
                    <div class=\"p-20\">
                        <input data-plugin=\"knob\" data-width=\"$size\" data-height=\"$size\" data-linecap=round
                               data-fgColor=\"#64b5f6\" value=\"$value\" data-skin=\"tron\" data-angleOffset=\"180\"
                               data-readOnly=true data-thickness=\".2\"/>
                        <h5 class=\"text-muted\">$text</h5>
                    </div>
                </div><!-- end col-->";
    }

    function CircliChart($value,$size=24,$text="Text"){
        $this->script[]=array(
                "scripts"=>" <!-- KNOB JS -->
        <!--[if IE]>
        <script type=\"text/javascript\" src=\"../layout-2_blue/assets/plugins/jquery-knob/excanvas.js\"></script>
        <![endif]-->
        <script src=\"../layout-2_blue/assets/plugins/jquery-knob/jquery.knob.js\"></script>

        <!-- Circliful -->
        <script src=\"../layout-2_blue/assets/plugins/jquery-circliful/js/jquery.circliful.min.js\"></script>",

                "css"=>"
                 <!-- Circlifull chart css -->
        <link href=\"../layout-2_blue/assets/plugins/jquery-circliful/css/jquery.circliful.css\" rel=\"stylesheet\" type=\"text/css\"/>
        ");

        return " 
                <div data-plugin=\"circliful\" class=\"circliful-chart m-b-30\" data-dimension=\"180\"
                     data-text=\"$value\" data-info=\"$text\" data-width=\"30\" data-fontsize=\"24\"
                     data-percent=\"$value\" data-fgcolor=\"#64b5f6\" data-bgcolor=\"#eeeeee\"></div>
                                       
                ";
    }

    function WidgetFlotChart($text,$height=320,$col=6,$udid=0){

        $this->script[]=array(
          "scripts"=>"<!-- Flot chart js -->
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.time.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.tooltip.min.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.resize.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.pie.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.selection.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.stack.js\"></script>
        <script src=\"../layout-2_blue/assets/plugins/flot-chart/jquery.flot.crosshair.js\"></script>



        <!-- flot init -->
        <script src=\"./flot.init.php?sess=".$_SESSION['sess']."\"></script>",
            "css"=>"",

        );

        $h="<div class=\"col-lg-$col\">
                <div class=\"card-box\">
                    <h4 class=\"header-title m-t-0 m-b-30\">$text</h4>
                  
                        <div id=\"LineDiagamm\" class=\"flot-chart\" style=\"height: ".$height."px;\">
                        </div>
  
                </div>
            </div>";
        return $h;
    }


    function GetCSS()
    {
        $h="";
        if (count($this->script)>0){
            foreach ($this->script as $i=>$s){
                $h.=$s['css'];
            }
        }
        return $h;
    }

    function GetScripts() {
        $h="";
        if (count($this->script)>0){
            foreach ($this->script as $i=>$s){
                $h.=$s['scripts'];
            }
        }

        return $h;
    }
}
