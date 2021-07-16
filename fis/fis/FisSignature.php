<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 06.11.18
 * Time: 19:13
 */
class FisSignature
{
    public $bks=6101;
    public $id=0;
    public $data=array();
    public $type="";
    public $notes="";

    function __construct($notes="Unterschrift",$type="artikelid",$i=0)
    {
        if ($i>0 && strlen($type)>0){
            $this->id=$i;
            $this->type=$type;
        }
    }
    function __toString(){
        return $this->id;
    }

    function AddNewSign(){

        if (strlen($_POST['signSVG'])>=300){

            $us=new FisUser($_SESSION['active_user']);
            include ("./connection.php");

            $state=1;
            $sign=substr($_POST['signSVG'],14);

            $updateSQL = sprintf("
                INSERT INTO  fissign (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, sign) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($this->notes, "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['active_user'], "int"),
                GetSQLValueString($us->data['name'], "text"),
                GetSQLValueString($this->bks, "int"),
                GetSQLValueString($this->id, "int"),
                GetSQLValueString($this->type, "text"),
                GetSQLValueString($sign, "text")
            );
            $rst = $mysqli->query($updateSQL);

            if ($mysqli->error) {

                AddSessionMessage("warning","Unterschrift wurde nicht angelegt, da ein Fehler aufgetreten ist. <code> TYPE " . $this->type. "</code>","Fehlermeldung");
            }else{
                $txt="Unterschrift wurde ".$us->data['name']." gespeichert.";
                AddSessionMessage("success", $txt, "Unterschrift gespeichert");
                $w=new FisWorkflow($txt,$this->type,$this->id);
                $w->Add();
                //$this->ChangeLog("Dokument $filename wurde hochgeladen",1);
            }
        }else{
            AddSessionMessage("error","Unterschrift wurde nicht angelegt, nicht unterschrieben wurde. ","Fehlermeldung");

        }

    }


    function LoadDocumentData(){
        $this->data=array();
        if ($this->id > 0){
            include("./connection.php");
            $updateSQL="SELECT * FROM fissign
                        WHERE fissign.linkedid='".$this->id."' AND fissign.dobject='".$this->type."'
                        ORDER BY fissign.ddatum DESC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->data[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }

    function GetStatus($status){
        $s="unbekannter Status";
        switch ($status){
            case 1:{ $s="Unterschrift wurde gespeichert und digitalisiert"; break;}
        }
        return $s;
    }

    function GetDocumentList(){
        $this->LoadDocumentData();
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Unterschrift</th>
                       
                        <th>Status</th>
                        <th>Wer</th>
                      
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td>".$item['sign']."</td>";

                $h.="<td>".$this->GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Unterschriften vorhanden</td></tr>";
            $h.="</tbody>";
        }
        $h.="</table></div>";
        return $h;
    }


    function ShowHtmlSign(){
        $_SESSION['jsoneditor'].=$this->ScriptDocumentReady();
        $_SESSION['sign']=$this->Script();
        $_SESSION['CSS']=$this->LinkJS();
        $_SESSION['CSS'].=$this->StyleCSS();

        return $this->ShowDiv();
    }

    private function LinkJS(){

        $h="<script type=\"text/javascript\" src=\"../plugins/jsignature/libs/flashcanvas.js\"></script>";
        return $h;
    }

    private function StyleCSS(){

        $h= "<style>
                #signatureparent {
                    color:darkblue;
                    background-color:#f5f5f5;
                    max-width:100%;
                    padding:20px;
                }
                
                #signature {
                    border: 2px dotted black;
                    background-color:lightgrey;
                   
                }
            
              
                             
            </style>";
        return $h;

    }

    private function ShowDiv(){

        $h="<div><div id=\"content\" class='table-responsive'>
                <div id=\"signatureparent\">
                    <div>Bitte unterschrieben Sie hier und klicken anschließend auf Prüfen.</div>
                    <div id=\"signature\"></div></div>
            
                <div id=\"toolsButton\" class='pull-right'></div>
                <div id=\"tools\"></div>
                <div><div id=\"displayarea\"></div></div>
            </div>
               
            <div id=\"scrollgrabber\"></div>
            </div>
            ";

        return $h;
    }

    function DebugView(){
        $h="<div id=\"content\">
                <div id=\"signatureparent\">
                    <div>jSignature inherits colors from parent element. Text = Pen color. Background = Background. (This works even when Flash-based Canvas emulation is used.)</div>
                    <div id=\"signature\"></div></div>
            
                <div id=\"toolsButton\"></div>
                <div id=\"tools\"></div>
                <div><p>Display Area:</p><div id=\"displayarea\"></div></div>
            </div>
            ";
        return $h;
    }

    private function Script(){
        $h="<script src=\"../plugins/jsignature/libs/jquery.js\"></script>
                <script type=\"text/javascript\">
                jQuery.noConflict()
                </script>
                
                
                <script>
                
                (function($) {
                    var topics = {};
                    $.publish = function(topic, args) {
                        if (topics[topic]) {
                            var currentTopic = topics[topic],
                            args = args || {};
                    
                            for (var i = 0, j = currentTopic.length; i < j; i++) {
                                currentTopic[i].call($, args);
                            }
                        }
                    };
                    $.subscribe = function(topic, callback) {
                        if (!topics[topic]) {
                            topics[topic] = [];
                        }
                        topics[topic].push(callback);
                        return {
                            \"topic\": topic,
                            \"callback\": callback
                        };
                    };
                    $.unsubscribe = function(handle) {
                        var topic = handle.topic;
                        if (topics[topic]) {
                            var currentTopic = topics[topic];
                    
                            for (var i = 0, j = currentTopic.length; i < j; i++) {
                                if (currentTopic[i] === handle.callback) {
                                    currentTopic.splice(i, 1);
                                }
                            }
                        }
                    };
                })(jQuery);
                
                </script>
                <script src=\"../plugins/jsignature/libs/jSignature.min.noconflict.js\"></script>
                ";

        return $h;
    }

    private function ScriptDocumentReady(){
        $h="
            
<script>

    (function($){

            $(document).ready(function() {

                // This is the part where jSignature is initialized.
                var \$sigdiv = $(\"#signature\").jSignature({'UndoButton':true})
	
	// All the code below is just code driving the demo. 
	, \$tools = $('#tools')
	, \$toolsButton= $('#toolsButton')
	, \$extraarea = $('#displayarea')
	, pubsubprefix = 'jSignature.demo.'
	
	var export_plugins = \$sigdiv.jSignature('listPlugins','export')
	, chops = ['<span><b>Extract signature data as: </b></span><select id=\"formatselect\">','<option value=\"svg\">(select export format)</option>']
	, name
	for(var i in export_plugins){
		if (export_plugins.hasOwnProperty(i)){
			name = export_plugins[i]
			chops.push('<option value=\"' + name + '\">' + name + '</option>')
		}
	}
	chops.push('</select><span><b> or: </b></span>')
	
	$(chops.join('')).bind('change', function(e){
		if (e.target.value !== ''){
			var data = \$sigdiv.jSignature('getData', e.target.value)
			$.publish(pubsubprefix + 'formatchanged')
			if (typeof data === 'string'){
				$('textarea', \$tools).val(data)
			} else if($.isArray(data) && data.length === 2){
				$('textarea', \$tools).val(data.join(','))
				$.publish(pubsubprefix + data[0], data);
			} else {
				try {
					$('textarea', \$tools).val(JSON.stringify(data))
				} catch (ex) {
					$('textarea', \$tools).val('Not sure how to stringify this, likely binary, format.')
				}
			}
		}
	}).appendTo(\$tools)

	
	$('<input type=\"button\" value=\"Reset\">').bind('click', function(e){
		\$sigdiv.jSignature('reset')
	}).appendTo(\$tools)

    $('<button name=\"sign_save\" type=\"submit\"  class=\"btn btn-custom waves-effect waves-light\">Prüfen und Senden</button>').bind('click', function(e){
        	$('#tools').hide();
            var data = \$sigdiv.jSignature('getData','svg')
            $.publish(pubsubprefix + 'formatchanged')
            if (typeof data === 'string'){
                $('textarea', \$tools).val(data)
            } else if($.isArray(data) && data.length === 2){
                $('textarea', \$tools).val(data.join(','))
                $.publish(pubsubprefix + data[0], data);
            } else {
                try {
                   
                    $('textarea', \$tools).val(JSON.stringify(data))
                } catch (ex) {
                    $('textarea', \$tools).val('Not sure how to stringify this, likely binary, format.')
                }
            }
       

    }).appendTo(\$toolsButton)
	
	$('<div><textarea name=\"signSVG\" style=\"width:100%;height:12em;\"></textarea></div>').appendTo(\$tools)
	
	$.subscribe(pubsubprefix + 'formatchanged', function(){
		\$extraarea.html('')
	})

	$.subscribe(pubsubprefix + 'image/svg+xml', function(data) {

		try{
			var i = new Image()
			i.src = 'data:' + data[0] + ';base64,' + btoa( data[1] )
			$(i).appendTo(\$extraarea)
		} catch (ex) {

		}

		var message = [
			\"If you don't see an image immediately above, it means your browser is unable to display in-line (data-url-formatted) SVG.\"
			, \"This is NOT an issue with jSignature, as we can export proper SVG document regardless of browser's ability to display it.\"
			, \"Try this page in a modern browser to see the SVG on the page, or export data as plain SVG, save to disk as text file and view in any SVG-capabale viewer.\"
           ]
		$( \"<div>\" + message.join(\"<br/>\") + \"</div>\" ).appendTo( $extraarea )
	});

	$.subscribe(pubsubprefix + 'image/svg+xml;base64', function(data) {
		var i = new Image()
		i.src = 'data:' + data[0] + ',' + data[1]
		$(i).appendTo(\$extraarea)
		
		var message = [
			\"If you don't see an image immediately above, it means your browser is unable to display in-line (data-url-formatted) SVG.\"
			, \"This is NOT an issue with jSignature, as we can export proper SVG document regardless of browser's ability to display it.\"
			, \"Try this page in a modern browser to see the SVG on the page, or export data as plain SVG, save to disk as text file and view in any SVG-capabale viewer.\"
           ]
		$( \"<div>\" + message.join(\"<br/>\") + \"</div>\" ).appendTo( \$extraarea )
	});
	
	$.subscribe(pubsubprefix + 'image/png;base64', function(data) {
		var i = new Image()
		i.src = 'data:' + data[0] + ',' + data[1]
		$('<span><b>As you can see, one of the problems of \"image\" extraction (besides not working on some old Androids, elsewhere) is that it extracts A LOT OF DATA and includes all the decoration that is not part of the signature.</b></span>').appendTo(\$extraarea)
		$(i).appendTo(\$extraarea)
	});
	
	$.subscribe(pubsubprefix + 'image/jsignature;base30', function(data) {
		$('<span><b>This is a vector format not natively render-able by browsers. Format is a compressed \"movement coordinates arrays\" structure tuned for use server-side. The bonus of this format is its tiny storage footprint and ease of deriving rendering instructions in programmatic, iterative manner.</b></span>').appendTo(\$extraarea)
	});

	if (Modernizr.touch){
		$('#scrollgrabber').height($('#content').height())		
	}
	
})

$('#tools').hide();
	

})(jQuery)
</script>";

        return $h;
    }


}