<?php
/* $Id: header.inc.php,v 2.31.2.1 2005/09/05 22:09:08 lem9 Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:

if (empty($GLOBALS['is_header_sent'])) {

    /**
     * Gets a core script and starts output buffering work
     */
    require_once('./libraries/common.lib.php');
    require_once('./libraries/ob.lib.php');
    if ($GLOBALS['cfg']['OBGzip']) {
        $GLOBALS['ob_mode'] = PMA_outBufferModeGet();
        if ($GLOBALS['ob_mode']) {
            PMA_outBufferPre($GLOBALS['ob_mode']);
        }
    }

    // garvin: For re-usability, moved http-headers and stylesheets
    // to a seperate file. It can now be included by header.inc.php,
    // queryframe.php, querywindow.php.

    require_once('./libraries/header_http.inc.php');
    require_once('./libraries/header_meta_style.inc.php');
    /* replaced 2004-05-05 by Michael Keck (mkkeck)
    $title     = '';
    if (isset($GLOBALS['db'])) {
        $title .= str_replace('\'', '\\\'', $GLOBALS['db']);
    }
    if (isset($GLOBALS['table'])) {
        $title .= (empty($title) ? '' : '.') . str_replace('\'', '\\\'', $GLOBALS['table']);
    }
    if (!empty($GLOBALS['cfg']['Server']) && isset($GLOBALS['cfg']['Server']['host'])) {
        $title .= (empty($title) ? 'phpMyAdmin ' : ' ')
               . sprintf($GLOBALS['strRunning'], (empty($GLOBALS['cfg']['Server']['verbose']) ? str_replace('\'', '\\\'', $GLOBALS['cfg']['Server']['host']) : str_replace('\'', '\\\'', $GLOBALS['cfg']['Server']['verbose'])));
    }
    $title     .= (empty($title) ? '' : ' - ') . 'phpMyAdmin ' . PMA_VERSION;
    */
    /* the new one
     * 2004-05-05: replaced by Michael Keck (mkkeck)
     */
    $title     = 'xxx';
    if ($cfg['ShowHttpHostTitle']) {
        $title .= (empty($GLOBALS['cfg']['SetHttpHostTitle']) && isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $GLOBALS['cfg']['SetHttpHostTitle']) . ' / ';
    }
    if (!empty($GLOBALS['cfg']['Server']) && isset($GLOBALS['cfg']['Server']['host'])) {
        $title.=str_replace('\'', '\\\'', $GLOBALS['cfg']['Server']['host']);
    }
    if (isset($GLOBALS['db'])) {
        $title .= ' / ' . str_replace('\'', '\\\'', $GLOBALS['db']);
    }
    if (isset($GLOBALS['table'])) {
        $title .= (empty($title) ? '' : ' ') . ' / ' . str_replace('\'', '\\\'', $GLOBALS['table']);
    }
    $title .= ' | phpMyAdmin ' . PMA_VERSION;
    ?>