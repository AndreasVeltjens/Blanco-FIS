<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 06.10.18
 * Time: 13:51
 */

define('FIS_SW_VERSION', '12.0.22');

include_once("../config/setup.php");

include('./FisMailer.php');
include("./general.php");
include("./FisWpFunction.php");
include("./FisWpConfig.php");
include("./FisUpdate.php");
include("./FisToaster.php");
include("./FisSettings.php");
include("./FisView.php");
include("./FisImaging.php");
include("./FisThumbnail.php");
include("./FisMorrisChart.php");
include "./FisGauge.php";

include("./FisUser.php");
include("./FisWorkflow.php");
include("./FisTool.php");
include("./FisPlan.php");
include("./FisMaintenance.php");
include("./FisReport.php");
include("./FisProcess.php");
include("./FisSignature.php");
include("./FisDocument.php");
include("./FisCustomer.php");
include("./FisVersion.php");
include("./FisMaterial.php");
include("./FisWarehouse.php");
include("./FisFid.php");
include("./FisFauf.php");
include("./FisEvent.php");
include("./FisEquipment.php");
include("./FisWorkplace.php");
include("./FisReturn.php");
include("./FisReturnDevice.php");
include("./FisDatalog.php");

include("./session.php");

$_SESSION['view']= new FisView();
$toaster =new FisToaster();
$wp=new FisWorkplace($_GET['wp']);