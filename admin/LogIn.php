<?php 
require 'core/connect.php';
require 'functions/secure.php';
session_start();
if(isset($_SESSION["AUTHOR_USERID"])){
	returnheader("index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Admin Prijava</title>
    <?php include_once 'includes/meta_tags.php'; ?>

    <?php include_once 'includes/include_css_js.php'; ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
    <!--[if lt IE 9]>
        <link type="text/css" href="assets/css/ie8.css" rel="stylesheet">
        <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The following CSS are included as plugins and can be removed if unused-->
    
    </head>

    <body class="focused-form">
        
        
<div class="container" id="login-form">
	<a href="LogIn.php" class="login-logo"><img src="assets/img/admin logo.png"></a>
	<?php
		if(isset($_SESSION['errors']))
			echo '<p style="text-align: center; color:red;">'.$_SESSION['errors'] .'</p><br/>';

		unset($_SESSION['errors']);
	?>
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h2>Prijavi se</h2></div>
				<div class="panel-body">
					
					<form action="php/login_script.php" class="form-horizontal" id="validate-form" method="post">
						<input type="hidden" name="loginbugaround" value="1" />
						<div class="form-group">
	                        <div class="col-xs-12">
	                        	<div class="input-group">							
									<span class="input-group-addon">
										<i class="fa fa-user"></i>
									</span>
									<input type="text" value="" class="form-control" placeholder="Email" data-parsley-minlength="6" placeholder="At least 6 characters" name="email" required>
								</div>
	                        </div>
						</div>

						<div class="form-group">
	                        <div class="col-xs-12">
	                        	<div class="input-group">
									<span class="input-group-addon">
										<i class="fa fa-key"></i>
									</span>
									<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
								</div>
	                        </div>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<a href="extras-forgotpassword.html" class="pull-left">Zaboravili ste šifru...?</a>
								<div class="checkbox-inline icheck pull-right pt0">
									<label for="">
										<input type="checkbox"></input>
										Zapamti me
									</label>
								</div>
							</div>
						</div>
					

						<div class="panel-footer">
							<div class="clearfix">
								<a href="registration.php" class="btn btn-default pull-left">Registruj se</a>
								<input type="submit" class="btn btn-primary pull-right" value="Prijavi se">
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>


    <!-- Load site level scripts -->

<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script> 							<!-- Load jQuery -->
<script type="text/javascript" src="assets/js/jqueryui-1.9.2.min.js"></script> 							<!-- Load jQueryUI -->

<script type="text/javascript" src="assets/js/bootstrap.min.js"></script> 								<!-- Load Bootstrap -->


<script type="text/javascript" src="assets/plugins/easypiechart/jquery.easypiechart.js"></script> 		<!-- EasyPieChart-->
<script type="text/javascript" src="assets/plugins/sparklines/jquery.sparklines.min.js"></script>  		<!-- Sparkline -->
<script type="text/javascript" src="assets/plugins/jstree/dist/jstree.min.js"></script>  				<!-- jsTree -->

<script type="text/javascript" src="assets/plugins/codeprettifier/prettify.js"></script> 				<!-- Code Prettifier  -->
<script type="text/javascript" src="assets/plugins/bootstrap-switch/bootstrap-switch.js"></script> 		<!-- Swith/Toggle Button -->

<script type="text/javascript" src="assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>  <!-- Bootstrap Tabdrop -->
     	
<script type="text/javascript" src="assets/js/enquire.min.js"></script> 									<!-- Enquire for Responsiveness -->

<script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script>							<!-- Bootbox -->

<script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script> <!-- nano scroller -->

<script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script> 	<!-- Mousewheel support needed for jScrollPane -->

<script type="text/javascript" src="assets/js/application.js"></script>
<script type="text/javascript" src="assets/demo/demo.js"></script>
<script type="text/javascript" src="assets/plugins/iCheck/icheck.min.js"></script>  

<!-- End loading site level scripts -->
    <!-- Load page level scripts-->
    

    <!-- End loading page level scripts-->
    </body>
</html>