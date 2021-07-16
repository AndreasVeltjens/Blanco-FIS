<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 06.10.18
 * Time: 13:45
 */
include("./config.php");
include("./configfunction.php");

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="refresh" content="<?php echo $wp->AutoLogoff(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.85">
    <meta name="description" content="CRM, CMS, FIS by ifis Fertigungssysteme">
    <meta name="author" content="FIS">

    <link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

    <title><?php
        if (isset($_REQUEST['fmstate']) && $_REQUEST['fmstate'] > 0) {
            echo GetFMState("text",$_REQUEST['fmstate']);
        }else{
            echo $wp->GetTitle();
        }
        ?></title>

    <!-- Jquery filer css -->
    <link href="../layout-2_blue/assets/plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
    <link href="../layout-2_blue/assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet"

    <!-- DataTables -->
    <link href="../layout-2_blue/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="../layout-2_blue/assets/plugins/summernote/summernote.css" rel="stylesheet" />

     <!-- ION Slider -->
    <link href="../layout-2_blue/assets/plugins/ion-rangeslider/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
    <link href="../layout-2_blue/assets/plugins/ion-rangeslider/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css"/>

    <!-- Notification css (Toastr) -->
    <link href="../layout-2_blue/assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />


    <!--Chartist Chart CSS -->
    <link rel="stylesheet" href="../layout-2_blue/assets/plugins/chartist/dist/chartist.min.css">
    <link rel="stylesheet" href="../layout-2_blue/assets/plugins/chartist/dist/chartist-plugin-legend.css">

    <!-- advanced-->
    <link href="../layout-2_blue/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../layout-2_blue/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
    <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../layout-2_blue/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../layout-2_blue/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <link href="../layout-2_blue/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../layout-2_blue/assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- standard -->


    <link href="../layout-2_blue/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <?php echo $_SESSION['view']->GetCSS(); ?>
    <?php echo $_SESSION['CSS']; ?>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="../layout-2_blue/assets/js/modernizr.min.js"></script>


</head>

<body class="fixed">

<header>

    <?php echo $wp->ShowHtmlLogonDialogs(); ?>

    <div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Fenster schließen</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Benutzerwechsel</h4>

                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                    <?php echo $wp->ShowHtmlAllowedUser(); ?>
                    <hr>
                    <p>Wählen Sie einen Benutzernamen für den Arbeitsplatz <code><?php echo $_SESSION['wp']; ?></code> aus.</p>

                        automatisch Inaktiv nach <?php echo round(($_SESSION['autoinactive'] / 60), 0) ?> min<br>
                        automatisches Abmelden nach <?php echo round(($_SESSION['autologout'] / 60), 0); ?> min <br>
                        Bildschirmaktualisierung nach <?php echo round(($_SESSION['autologoff'] / 60), 0); ?> min
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
               </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="custom-width-modal3" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Fenster schließen</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Benachrichtigungen</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                    <?php echo SessionToasterMessage(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="custom-width-modal4" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Fenster schließen
                    </button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Software-Support</h4>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <form id="support-form" method="post">
                            <?php echo asTextarea("Wie können wir Ihnen helfen?", "support-text", "", 10, 10, ""); ?>
                            <?php echo asTextarea("Session-Log", "session-log", print_r($_SESSION, true), 10, 10, "");
                            ?>
                            Es werden einige Daten Ihrer Websession mit gesendet.
                            <a href="#" onclick="ShowSessionLog"> <i class="fa fa-eye"> </i> Daten anzeigen</a>
                            <script>
                                function ShowSessionLog() {
                                    $('#session-log').show();
                                }

                            </script>
                            <button name="send_support_by_email" type="submit"
                                    class="btn btn-custom waves-effect pull-right">Supportanfrage Senden
                            </button>
                        </form>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="custom-width-modal5" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Fenster schließen
                    </button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Ihre Funktionen</h4>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <?php

                        if ($_SESSION['active_user'] > 0) {
                            if ($_SESSION['active_user_right'] > 1) { ?>


                                <div class="col-lg-4">
                                    <b>Fehlermeldungen</b>
                                    <p></p>

                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=22"
                                       alt="Rückholungen" onclick="refresh_site()">
                                        <i class="fa fa-refresh"> </i> Rückholungen anzeigen
                                    </a>

                                    <?php if ($_SESSION['rueckid'] > 0) { ?>

                                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=25&rueckid=<?php echo $_SESSION['rueckid'] ?>"
                                           alt="letzte Fehlermeldung bearbeiten" onclick="refresh_site()">
                                            <i class="fa fa-edit"> </i> letzte Rückholung...
                                        </a>

                                    <? } ?>
                                    <hr>

                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=27"
                                       alt="Kundendaten" onclick="refresh_site()">
                                        <i class="fa fa-list-ol"> </i> Kundendaten anzeigen
                                    </a>

                                    <?php if ($_SESSION['idk'] > 0) { ?>

                                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=28&idk=<?php echo $_SESSION['idk'] ?>"
                                           alt="letzte Kundendaten bearbeiten" onclick="refresh_site()">
                                            <i class="fa fa-edit"> </i> letzte Kundendaten...
                                        </a>

                                    <? } ?>
                                    <hr>
                                    <p></p>


                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=24&fmid=<?php echo $_SESSION['fmid'] ?>"
                                       alt="neue Fehlermeldung anlegen" onclick="refresh_site()">
                                        <i class="fa fa-plus"> </i> neue Fehlermeldung
                                    </a>

                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=23"
                                       alt="Retouren" onclick="refresh_site()">
                                        <i class="fa fa-list"> </i> alle Fehlermeldungen
                                    </a>
                                    <?php if ($_SESSION['fmid'] > 0) { ?>
                                        <br>
                                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=25&fmid=<?php echo $_SESSION['fmid'] ?>"
                                           alt="letzte Fehlermeldung bearbeiten" onclick="refresh_site()">
                                            <i class="fa fa-edit"> </i> letzte
                                            Fehlermeldung...(<?php echo $_SESSION['fmid']; ?>)
                                        </a>

                                    <? } ?>
                                    <hr>
                                    <b>Statusansichten Fehlermeldungen</b>

                                    <?php

                                    if (count(GetFMState("data", ""))) {
                                        foreach (GetFMState("data", "") as $i => $item) {
                                            If ($_REQUEST['fmstate'] == $i) {
                                                $color = "btn-success";
                                            } else {
                                                $color = "custom";
                                            }
                                            $h .= "<br><a class='btn $color btn-sm' target='_self' 
                                                href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=23&fmstate=$i&materialgroup=&material=&version=&fid=\" 
                                                onclick='refresh_site()'>
                                                <i class='" . $item['icon'] . "'> </i>  " . $item['desc'] . " </a>";
                                        }
                                    }
                                    echo $h; ?>
                                    <hr>
                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=46"
                                       alt="Retouren" onclick="refresh_site()">
                                        <i class="fa fa-flag"> </i> SAP Rückmeldungen
                                    </a>

                                </div>

                            <?php } ?>

                            <?php if ($_SESSION['active_user_right'] > 1) { ?>

                                <div class="col-lg-4">
                                    <b>Ansicht</b>
                                    <p></p>
                                    <br>
                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=0&action=0"
                                       onclick="refresh_site()">
                                        <i class="fa fa-home"> </i> Starteite <?php echo $wp->GetTitle(); ?>
                                    </a>
                                    <br>
                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=0&action=0"
                                       alt="FIS-Grundeinstellungen" onclick="refresh_site()">
                                        <i class="fa fa-tv"> </i> alle Arbeitsplätze
                                    </a>

                                    <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=99"
                                       alt="FIS-Grundeinstellungen" onclick="refresh_site()">
                                        <i class="zmdi zmdi-group"> </i> Arbeitsplatzgruppe anzeigen
                                    </a>
                                    <hr>

                                    <?php if ($_SESSION['active_user_right'] > 4) { ?>
                                        <b>Einstellungen</b><p></p>
                                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=21"
                                           alt="FIS-Grundeinstellungen" onclick="refresh_site()">
                                            <i class="fa fa-gears"> </i> Grundeinstellungen
                                        </a>
                                        <br>
                                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=<?php echo $_SESSION['wp'] ?>&action=6"
                                           alt="Arbeitsplatzeinstellungen" onclick="refresh_site()">
                                            <i class="fa fa-gear"> </i> Arbeitsplatzeinstellungen
                                        </a>

                                        <hr>

                                    <?php }
                                    echo "<b>Funktionen</b><p></p>";
                                    echo $wp->GetWpFunctions("listfunction");

                                    ?>
                                    <br>
                                </div>
                                <?php
                                if ($_SESSION['active_user_right'] > 4) { ?>
                                    <div class="col-lg-4">
                                        <b>Erweitert</b>
                                        <p></p>

                                        <br>
                                        <a href="#custom-width-modal-bug" data-toggle="modal"
                                           data-target="#custom-width-modal-bug"><i class="fa fa-list-ol"></i>
                                            Session-Info anzeigen </a>
                                        <br>

                                        <?php

                                        echo $wp->GetWpFunctions("listerweitert", "btn");

                                        ?>

                                    </div>
                                <?php } ?>


                            <?php }

                        }// active_user?>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="<?php echo $wp->GetLink(); ?>&action=0&materialgroup=&material=&fid=&version=&fmid=&sn=&equip=" class="logo">
                    <img src="../config/logo_<?php echo FIS_ID ?>.png" alt="logo" style="height: 58px;"
                         data-toggle="tooltip" data-placement="bottom" title=""
                         data-original-title="zurück zum Arbeitsplatz">

              </a>
            </div>
        </div>

        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div>


                    <form method="post" id="searchform" action="<?php echo $wp->GetLink(); ?>&action=20&materialgroup=&material=&fid=&version=&fmid=&sn=&equip=" role="search" class="navbar-left app-search pull-left hidden-xs" >
                        <input type="text" name="search" id="search"  placeholder="Suche..." class="form-control">
                        <a href="javascript:$('#searchform').submit()"><i class="fa fa-search"></i></a>
                    </form>
                    <?php if ($_SESSION['active_user'] > 0) { ?>
                        <ul class="nav navbar-nav navbar-right pull-left">
                            <li>
                                <a href="#custom-width-modal5" data-toggle="modal" data-target="#custom-width-modal5"><i
                                            class="zmdi zmdi-edit"></i> Ihre Funktionen</a>
                            </li>
                        </ul>


                    <?php }

                   if ($_SESSION['wp']>=0){ ?>


                    <ul class="nav navbar-nav navbar-right pull-right">

                        <?php  if ($_SESSION['active_user']>0) {?>
                            <?php if (count($_SESSION['e']) > 0) { ?>
                                <li>
                                    <a href="#custom-width-modal3" data-toggle="modal" data-target="#custom-width-modal3"><i class="zmdi zmdi-notifications-active"></i> </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="./index.php?sess=<?php echo $_SESSION['sess'] ?>&action=19&wp=<?php echo $_SESSION['wp'] ?>"
                                   class="logo" data-toggle="tooltip" data-placement="bottom" title=""
                                   data-original-title="Drucker und Druckeinstellungen anzeigen">
                                    <i class="zmdi zmdi-print"> </i><span></span>
                                </a>
                            </li>
                            <li>
                                <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&action=99&wp=<?php echo $_SESSION['wp'] ?>"
                                   data-toggle="tooltip" data-placement="bottom" title=""
                                   data-original-title="Arbeitsplatzgruppe anzeigen">
                                    <i class="zmdi zmdi-group"> </i>
                                </a>
                            </li>

                            <li>
                                <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>&wp=0" data-toggle="tooltip"
                                   data-placement="bottom" title="" data-original-title="alle Arbeitsplätze">
                                     <i class="fa fa-tv"> </i>
                                </a>
                            </li>
                        <?php } ?>

                            <li>
                                <a href="#custom-width-modal2" data-toggle="modal" data-target="#custom-width-modal2">
                                    <i class="fa fa-users"> </i> Benutzer </a>
                            </li>


                       <?php echo $wp->ShowUsers(); ?>


                    </ul>

                   <?php } ?>

                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <!-- Top Bar End -->

</header>
<!-- Begin page -->
<div class="wrapper">
    <div class="container">

        <!-- Page-Title -->
        <? echo $wp->ShowHtmlPageTitel(); ?>
        <div class="row" >
            <?php echo ShowHtmlWaitingAfterClick(); ?>

        </div>
        <div class="row" id="change">
            <? echo $wp->ShowHtmlWorkplace(); ?>
        </div>


        <footer class="footer">
            2016-<?php echo date("Y",time())." ".FIS_COMPANY ." - ".$_SERVER['SERVER_NAME'] ." SW-Version ".FIS_SW_VERSION; ?>
            <a href="#custom-width-modal4" data-toggle="modal" data-target="#custom-width-modal4"><i
                        class="zmdi zmdi-mail-reply"></i> Support </a>
        </footer>

    </div>

</div> <!-- container -->

<?php if ($_SESSION['active_user_right'] > 2) {?>
    <div id="custom-width-modal-bug" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Fenster schließen</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Debugmodus</h4>

                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="20"><?php echo print_r($_SESSION,true); ?></textarea>
                    <br>
                    automatisch Inaktiv nach <?php echo $_SESSION['autoinactive']; ?> s<br>
                    automatisches Abmelden nach <?php echo $_SESSION['autologout']; ?> s<br>
                    Bildschirmaktualisierung nach <?php echo $_SESSION['autologoff']; ?> s
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php } ?>

<!-- END wrapper -->

<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="../layout-2_blue/assets/js/jquery.min.js"></script>
<script src="../layout-2_blue/assets/js/bootstrap.min.js"></script>
<script src="../layout-2_blue/assets/js/detect.js"></script>
<script src="../layout-2_blue/assets/js/fastclick.js"></script>
<script src="../layout-2_blue/assets/js/jquery.slimscroll.js"></script>
<script src="../layout-2_blue/assets/js/jquery.blockUI.js"></script>
<script src="../layout-2_blue/assets/js/waves.js"></script>
<script src="../layout-2_blue/assets/js/wow.min.js"></script>
<script src="../layout-2_blue/assets/js/jquery.nicescroll.js"></script>
<script src="../layout-2_blue/assets/js/jquery.scrollTo.min.js"></script>

<script type="text/javascript" src="../layout-2_blue/assets/plugins/multiselect/js/jquery.multi-select.js"></script>

<!-- Jquery filer js -->
<script src="../layout-2_blue/assets/plugins/jquery.filer/js/jquery.filer.min.js"></script>

<?php if (($_SESSION['datatable'])>=0){ ?>

<!-- Datatables-->
<script src="../layout-2_blue/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/jszip.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/dataTables.keyTable.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="../layout-2_blue/assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<?php } ?>

<!-- Plugins-->

<script src="../layout-2_blue/assets/plugins/switchery/switchery.min.js"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="../layout-2_blue/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="../layout-2_blue/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
<script src="../layout-2_blue/assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
<script src="../layout-2_blue/assets/plugins/moment/moment.js"></script>
<script src="../layout-2_blue/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="../layout-2_blue/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../layout-2_blue/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="../layout-2_blue/assets/plugins/ion-rangeslider/ion.rangeSlider.min.js"></script>
<script src="../plugins/gauge/gauge.js"></script>

<!-- Datatable init js -->
<script>
 <?php echo $_SESSION['script_datatable']; ?>
</script>

<?php echo $_SESSION['morris']; ?>
<?php echo $_SESSION['morrisfunction']; ?>
<?php echo $_SESSION['flot']; ?>
<!-- Toastr js -->
<script src="../layout-2_blue/assets/plugins/toastr/toastr.min.js"></script>

<?php if ($_SESSION['summernote']>0){ ?>
<!-- Summernote js-->
<script src="../layout-2_blue/assets/plugins/summernote/summernote.min.js"></script>
<?php } ?>

<?php echo $_SESSION['view']->GetScripts(); ?>

<?php echo $_SESSION['sign']; ?>

<?php if ($_SESSION['report'] == 1) { ?>
    <!--Chartist Chart-->
    <script src="../layout-2_blue/assets/plugins/chartist/dist/chartist.min.js"></script>
    <script src="../layout-2_blue/assets/plugins/chartist/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../layout-2_blue/assets/plugins/chartist/dist/chartist-plugin-legend.js"></script>

<?php } ?>

<!-- App js -->
<script src="../layout-2_blue/assets/js/jquery.core.js"></script>
<script src="../layout-2_blue/assets/js/jquery.app.js"></script>

<?php echo $_SESSION['gauge']; ?>




<script type="text/javascript">


    <?php echo $_SESSION['functions']; ?>

        function refresh_site() {
            try {
                $(function () {
                    $('#form_edit').parsley().on('field:validated', function () {
                        var ok = $('.parsley-error').length === 0;
                        if (!ok) {
                            alert('Pflichteingaben fehlen. Bitte ergänzen.');
                        }
                    })
                        .on('form:submit', function () {
                            $("#progress").show();
                            $("#change").hide();
                        });
                });


            } catch (err) {
                //alert('Pflichteingaben fehlen. Bitte ergänzen.' + err);
                $("#progress").hide();
                $("#change").show();

            }


        }

        function reset_site() {
            $("#progress").hide();
            $("#change").show();
        }

    $(document).ready(function() {

        <?php echo $_SESSION['script']; ?>
        <?php echo $_SESSION['range']; ?>


        toastr.options = {
            "closeButton": true,
            "debug": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        <?php echo $toaster->SessionToaster();

        $_SESSION['e']=array(); ?>

        'use-strict';

       
        



        //Example 1
        $("#filer_input1").filer({
                limit: null,
                maxSize: null,
                extensions: null,
            changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag & Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn btn btn-custom waves-effect waves-light">Datei auswählen</a></div></div>',
                showThumbs: true,
                theme: "dragdropbox",
                templates: {
                    box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                    item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
                    itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
                    progressBar: '<div class="bar"></div>',
                    itemAppendToEnd: false,
                    removeConfirmation: true,
                    _selectors: {
                        list: '.jFiler-items-list',
                        item: '.jFiler-item',
                        progressBar: '.bar',
                        remove: '.jFiler-item-trash-action'
                    }
                },
                dragDrop: {
                    dragEnter: null,
                    dragLeave: null,
                    drop: null,
                },
                uploadFile: {
                    url: "./upload.php?active_user=<?php echo $_SESSION['active_user']; ?>",
                    data: null,
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    beforeSend: function(){},
                    success: function(data, el){
                        var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    },
                    error: function(el){
                        var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    },
                    statusCode: null,
                    onProgress: null,
                    onComplete: null
                },
            <?php // echo $_SESSION['LoadUploadData']; ?>

            addMore: false,
            clipBoardPaste: true,
            excludeName: null,
            beforeRender: null,
            afterRender: null,
            beforeShow: null,
            beforeSelect: null,
            onSelect: null,
            afterShow: null,
            onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            var file = file.name;
                $.post('./remove_file.php?active_user=<?php echo $_SESSION['active_user']; ?>', {file: file});
            },
            onEmpty: null,
            options: null,
            captions: {
                button: "Datei wählen",
                feedback: "Dateien zum Hochladen auswählen",
                feedback2: "Diese Dateien wurden gewählt",
                drop: "Ziehen Sie die Dateien hier hinein, um Sie hochzuladen",
                removeConfirmation: "Sie Sie sich sich, dass die diese Datei löschen wollen?",
                errors: {
                    filesLimit: "Es sind nur {{fi-limit}} Dateien gleichzeitig möglich.",
                    filesType: "Es dürfen nur Bilddateien hochgeladen werden.",
                    filesSize: "{{fi-name}} diese Datei ist zu groß! Erlaubte Dateigröße {{fi-maxSize}} MB.",
                    filesSizeAll: "Die Summe der Dateigrößen ist zu groß! Max. Upload-Größe {{fi-maxSize}} MB."
                }
            }

        });


        $("#progress").hide();
        $("#change").show();
        $("#session-log").hide();

        <?php echo $_SESSION['loadlayout']; ?>
        <?php echo $_SESSION['endscripts']; ?>
        <?php echo $_SESSION['focus']; ?>
    } );




</script>

<?php echo $_SESSION['jsoneditor'] ?>

</body>
</html>
<?php
//clear session variable
$toaster->ClearNotification();
?>