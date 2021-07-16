<?php
include_once("../apps/session.php");
include ('../apps/function.php');

if (isset($_POST['new'])){
    $u=new ifisUser();
    if ($u->InsertData($_POST)){
        header ("Location: ../apps/users.php?sess=".$_SESSION['sess']."");
        exit;
    }
}

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Wareneingang">
        <meta name="author" content="Andreas Veltjens">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

        <!-- App title -->
        <title>Mitarbeiter registrieren</title>

        <!-- App CSS -->
        <link href="../layout-2_blue/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="../layout-2_blue/assets/js/modernizr.min.js"></script>

    </head>
    <body>

        <div class="text-center logo-alt-box">
            <a href="../index.php" class="logo"><span>ALBA<span>FIS</span></span></a>
            <h5 class="text-muted m-t-0">Mitarbeiter registrieren</h5>
        </div>

        <div class="wrapper-page">

        	<div class="m-t-30 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold m-b-0">Registrierung</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-10" method="post">
                        <input type="hidden" name="userid" value="<?php echo $u->userid; ?>">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Vor- und Nachname*</label>
                            <div class="col-sm-7">
                                <input type="text" required parsley-type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $u->data['name']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Username (E-Mailadresse)*</label>
                            <div class="col-sm-7">
                                <input type="email" required parsley-type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $u->data['email']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-4 control-label">Password*</label>
                            <div class="col-sm-7">
                                <input  type="password" required class="form-control" name="password" id="password" placeholder="" value="<?php echo $u->data['password']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abteilung" class="col-sm-4 control-label">Abteilung*</label>
                            <div class="col-sm-7">
                                <input  type="text" required class="form-control" name="abteilung" id="abteilung" placeholder="Abteilung" value="<?php echo $u->data['abteilung']; ?>">
                            </div>
                        </div>

						<div class="form-group">
							<div class="col-xs-12">
								<div class="checkbox checkbox-custom">
									<input id="checkbox-signup" type="checkbox" checked="checked">
									<label for="checkbox-signup">I accept <a href="#">Terms and Conditions</a></label>
								</div>

							</div>
						</div>

						<div class="form-group text-center m-t-30">
							<div class="col-xs-12">
								<button name="new" id="new" class="btn btn-custom btn-bordred btn-block waves-effect waves-light text-uppercase" type="submit">
									Registeren
								</button>
							</div>
						</div>



					</form>

                </div>
            </div>
            <!-- end card-box -->

			<div class="row">
				<div class="col-sm-12 text-center">
					<p class="text-muted">Already have account?<a href="../index.php" class="text-primary m-l-5"><b>Sign In</b></a></p>
				</div>
			</div>

        </div>
        <!-- end wrapper page -->




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
        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="../layout-2_blue/assets/plugins/parsleyjs/dist/parsley.min.js"></script>


        <!-- App js -->
        <script src="../layout-2_blue/assets/js/jquery.core.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.app.js"></script>

	</body>
</html>