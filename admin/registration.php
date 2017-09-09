<?php include 'functions/secure.php'; ?>
<!DOCTYPE html>
<html lang="en" class="coming-soon">
<head>
    
    <title>Admin - Registracija</title>
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
        
        
<div class="container" id="registration-form">
	<a class="login-logo registration"><img src="assets/img/admin logo.png"></a>
	<?php
		if(isset($_GET[base64_url_encode('error')])) {
			echo '<p style="text-align: center; color:red;">'.base64_url_decode($_GET[base64_url_encode("error")]) .'</p><br/>';
		
	}
	?>
	<div class="row">
		<div class="col-md-7 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><h2>Forma za registraciju</h2></div>
				<div class="panel-body">
					<form action="php/register.php" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="FullName" class="col-xs-4 control-label">Ime i Prezime</label>
	                        <div class="col-xs-8">
	                        	<input type="text" class="form-control" name="FullName" id="FulltName" placeholder="Ime i Prezime" required>
	                        </div>
	                       
						</div>
						<div class="form-group">
							<label for="Email" class="col-xs-4 control-label">Email</label>
	                        <div class="col-xs-8">
	                        	<input type="text" class="form-control" name="Email" id="Email" placeholder="Email" required>
	                        </div>
						</div>
						<div class="form-group">
							<label for="Password" class="col-xs-4 control-label">Šifra</label>
	                        <div class="col-xs-8">
	                        	<input type="password" class="form-control" name="Password" id="Password" placeholder="Šifra" required>
	                        </div>
						</div>
						<div class="form-group">
							<label for="ConfirmPassword" class="col-xs-4 control-label">Ponovo unesite šifru</label>
	                        <div class="col-xs-8">
	                        	<input type="password" class="form-control" name="ConfirmPassword" id="ConfirmPassword" placeholder="Ponovljena šifra" required>
	                        </div>
						</div>
						<div class="form-group">
							<label for="ConfirmPassword" class="col-xs-4 control-label">Spol</label>
	                        <div class="col-sm-8">
						<label class="radio-inline ">
							<input type="radio" id="inlineradio1" value="1" name="sex" required> Muško
						</label>
						<label class="radio-inline ">
							<input type="radio" id="inlineradio2" value="2" name="sex" required> Žensko
						</label>
					</div>
						</div>
						<div class="form-group">
							<label for="ConfirmPassword" class="col-xs-4 control-label">Nešto o autoru</label>
	                        <div class="col-xs-8">
	                        	<textarea class="form-control" name="about" id="ConfirmPassword" placeholder="Podaci o autoru" required></textarea>
	                        </div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<div class="checkbox ">
									<label for=""><input type="checkbox" name="agreement"/> Prihvatam <a href="../pravila_stranice.php">pravila korištenja</a></label>
								</div>
							</div>
						</div>
						
					<div class="panel-footer">
						<div class="clearfix">
							<a href="LogIn.php" class="btn btn-default pull-left">Već ste registrovani? Prijavite se</a>
							<input type="submit" class="btn btn-primary pull-right" value="Registruj se">
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

<script type="text/javascript" src="assets/plugins/iCheck/icheck.min.js"></script>     					<!-- iCheck -->

<script type="text/javascript" src="assets/js/enquire.min.js"></script> 									<!-- Enquire for Responsiveness -->

<script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script>							<!-- Bootbox -->

<script type="text/javascript" src="assets/plugins/simpleWeather/jquery.simpleWeather.min.js"></script> <!-- Weather plugin-->

<script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script> <!-- nano scroller -->

<script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script> 	<!-- Mousewheel support needed for jScrollPane -->

<script type="text/javascript" src="assets/js/application.js"></script>
<script type="text/javascript" src="assets/demo/demo.js"></script>
<script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

<!-- End loading site level scripts -->
    <!-- Load page level scripts-->
    
    <!-- End loading page level scripts-->
    </body>
</html>