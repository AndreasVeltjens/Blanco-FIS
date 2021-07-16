<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 11:57
 */
?>


<!-- sample modal content -->

<div id="custom-width-modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="custom-width-modalLabel">Abmelden</h4>
            </div>
            <div class="modal-body">
                <h4>Abmelden</h4>
                <p>Bitte bestätigen Sie das Verlassen der Anwendung.</p>
                <hr>
                <h4>Tipp: Mitarbeiterwechsel</h4>
                <p>Nutzen Sie immer Ihren eigenen Benutzernamen, wenn Sie Buchungsvorgänge durchführen. Damit werden Vorgänge nachvollziehbarer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                <a href="../index.php?logout=0" target="_self" type="button" class="btn btn-danger waves-effect waves-light">Abmelden</a>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- sample modal content -->

<div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="custom-width-modalLabel">Benutzerwechsel</h4>
            </div>
            <div class="modal-body">
                <h4>Abmelden</h4>
                <p>Bitte bestätigen Sie das Verlassen der Anwendung.</p>
                <hr>
                <?php  $_SESSION['x']; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
                <a href="./userchange.php?sess=<?php echo $_REQUEST['sess']; ?>" target="_self" type="button" class="btn btn-danger waves-effect waves-light">Abmelden</a>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->

                    <span id="sidebar-menu">
                        <ul>
                            <li class="text-muted menu-title"><a href="userchange.php?sess=<?php echo $_REQUEST['sess']; ?>"> <i class="ti-user m-r-5"></i>  <?php echo $us->data['name']; ?></a></li>

                            <li class="has_sub">
                                <a href="index.php?sess=<?php echo $_REQUEST['sess']; ?>" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i> <span> Startseite </span> </a>
                            </li>
                            <?php if ($us->data['userright']>=4){ ?>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-filter-list"></i> <span> Warenwirtschaft </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="wareneingang.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Wareneingangsliste  </span> <?php echo $ws->GetCountWareneingangToday(); ?></a>   </li>
                                    <li><a href="warenausgang.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span>Warenausgangsliste </span><?php echo $wa->GetCountWareneingangToday(); ?></a> </li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-user-secret"></i> <span> Vertrieb </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="kundendaten.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Kundendaten  </span> </a>   </li>
                                    <li><a href="materialdaten.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Materialdaten  </span> </a>   </li>

                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-memory"></i> <span> Produktionsplanung </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="fauf.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Fertigungsaufträge </span></a>   </li>
                                    <li><a href="methodenplanung.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Statusübersicht </span></a>   </li>
                                    <li><a href="ftype.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Auftragsartenübersicht </span></a>   </li>

                                    <li><a href="fids.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Rückmeldungen </span></a>   </li>
                                    <?php if ($_SESSION['last_fid']>0){?>
                                    <li><a href="fid.e.php?sess=<?php echo $_REQUEST['sess']."&fid=".$_SESSION['last_fid']; ?>"> <span> letzte Rückmeldung </span></a>   </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-check"></i> <span> Qualitätssicherung </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                     <li><a href="mfrs.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> alle Messungen </span></a>   </li>
                                    <?php if ($_SESSION['last_fid']>0){?>
                                     <li><a href="mfr.e.php?sess=<?php echo $_REQUEST['sess']."&fid=".$_SESSION['last_fid']; ?>"> <span> letzte Messung bearbeiten </span></a>   </li>
                                    <?php } ?>
                                    <?php if ($us->data['userright']>=4){ ?>
                                    <li><a href="fids.php?sess=<?php echo $_REQUEST['sess']; ?>&view=qs"> <span> Qualitätsprüfbestand </span></a>   </li>
                                    <li><a href="fids.php?sess=<?php echo $_REQUEST['sess']; ?>&view=qs"> <span> alle Qualitätsprüfungen </span></a>   </li>
                                    <?php } ?>
                                    <li><a href="qm1.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Auswertungen </span></a>   </li>
                                </ul>
                            </li>
                            <?php if ($us->data['userright']>=4){ ?>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-notifications-active"></i> <span> Meldungen </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="messages.php?sess=<?php echo $_REQUEST['sess']; ?>&Smessagetype=1"> <span> Unfallmeldung </span></a>   </li>
                                    <li><a href="messages.php?sess=<?php echo $_REQUEST['sess']; ?>&Smessagetype=2"> <span> Schadenmeldung </span></a>   </li>
                                    <li><a href="messages.php?sess=<?php echo $_REQUEST['sess']; ?>&Smessagetype=3"> <span> Fremdfirmenanmeldung </span></a>   </li>
                                    <li><a href="messages.php?sess=<?php echo $_REQUEST['sess']; ?>&Smessagetype=4"> <span> Erlaubnisschein </span></a>   </li>
                                    <li><a href="messages.php?sess=<?php echo $_REQUEST['sess']; ?>&Smessagetype=5"> <span> Besucheranmeldung </span></a>   </li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-labels"></i> <span> Bestandsverwaltung </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="bestandsfuehrung.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> Lagerbestandsübersicht </span></a>   </li>
                                </ul>
                            </li>
                            <?php } ?>

                            <li class="text-muted menu-title">Mehr</li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-settings"></i><span> Einstellungen </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="#custom-width-modal1" data-toggle="modal" data-target="#custom-width-modal1"> <i class="ti-power-off m-r-5"></i> Abmelden</a></li>
                                    <li><a href="help.php?sess=<?php echo $_SESSION['sess']; ?>&userid=<?php echo $_SESSION['userid']; ?>"> <i class="fa fa-question"></i> Hilfe</a></li>
                                    <li><a href="users.e.php?sess=<?php echo $_SESSION['sess']; ?>&useridedit=<?php echo $_SESSION['userid']; ?>"> <i class="fa fa-user"></i> Account</a></li>
                                    <?php if ($us->data['userright']>=4){ ?>
                                    <li><a href="users.e.php?sess=<?php echo $_SESSION['sess']; ?>&userid=<?php echo $_SESSION['userid']; ?>"> <i class="fa fa-plus-circle"></i> Neuer Account</a></li>
                                    <li><a href="users.php?sess=<?php echo $_SESSION['sess']; ?>"> <i class="fa fa-users"></i> Mitarbeiterliste</a></li>
                                    <li><a href="software.php?sess=<?php echo $_SESSION['sess']; ?>" target="_self"> <i class="zmdi zmdi-refresh"></i> Softwareupdate</a></li>
                                <?php } ?>
                                </ul>
                            </li>

                       </ul>



                </span>
                </div>
</div>