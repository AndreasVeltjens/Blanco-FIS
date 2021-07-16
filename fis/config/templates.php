<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-02-26
 * Time: 21:38
 */

$FIS_RETURN_TXT_TEMPLATE_CONTENT = array(
    "" => "",

);

$FIS_RETURN_DROPBOX_CONTENT = "
<li><a href=\"javascript:$('#fm_deliverycontent').val('ohne Deckel');\">        ohne Deckel</a></li>
<li class=\"divider\"></li>
<li><a href=\"javascript:$('#fm_deliverycontent').val('mit Kabel');\">          mit Kabel</a></li>
<li><a href=\"javascript:$('#fm_deliverycontent').val('mit BLT');\">            mit BLT</a></li>
<li><a href=\"javascript:$('#fm_deliverycontent').val('mit BLT und Kabel');\">  mit BLT und Kabel</a></li>

<li class=\"divider\"></li>
<li><a href=\"javascript:$('#fm_deliverycontent').val('nur Modul');\">          nur Modul</a></li>
";


$FIS_RETURN_TXT_TEMPLATE_REPAIR = array(
    "" => "",

);

$FIS_RETURN_DROPBOX_REPAIR = "
<li><a href=\"javascript:$('#fmnotes').val('nicht reparaturfähig (nicht reparabel, unreparabel)');\">
nicht reparaturfähig (nicht reparabel, unreparabel)</a></li>
<li class=\"divider\"></li>
<li><a href=\"javascript:$('#fmnotes').val('Inlet hebt sich aus dem Kragen, Wasser kann eindringen -Gefahr - VDE nicht zulässig! starke Gesbrauchspuren');\">
Inlet hebt sich aus dem Kragen, Wasser kann eindringen -Gefahr - VDE nicht zulässig! starke Gesbrauchspuren</a></li>

<li class=\"divider\"></li>
<li><a href=\"javascript:$('#fmnotes').val('Grundkörper beschädigt, Wasser kann eindringen -Gefahr- VDE nicht zulässig! strake Gebrauchsspuren');\">
Grundkörper beschädigt, Wasser kann eindringen -Gefahr- VDE nicht zulässig! strake Gebrauchsspuren</a></li>
";