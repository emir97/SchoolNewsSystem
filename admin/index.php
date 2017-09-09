<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
?>
<?php 
//linkovi 
$addNews = 'vijesti.php?'.base64_url_encode("postNewNews") .'='.base64_url_encode("new");
$addSlider = 'slider.php?'.base64_url_encode("postNewSlide") .'='.base64_url_encode("new");
$addFiles = "ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
$viewFiles = "ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
$addEvent = 'izmjena_dogadjaji.php?'.base64_url_encode("action")."=".base64_url_encode("insert");

?>
<?php 
	
	$numOfPostedNews = 0;
	$numOfPostedEvent = 0;
	$numOfPostedFiles = 0;
	$numOfPostedSliders = 0;
	// konekcija na bazu
	$connect = connectToDb();
	// dohvatanje podataka koliko je autor objavio vijesti
	$fetchNumOfNews = $connect->prepare("SELECT COUNT(news_id) AS numOfPostedNews FROM news WHERE author_id = :author");
	$fetchNumOfNews->bindParam(":author", $_SESSION['AUTHOR_USERID']);
	$fetchNumOfNews->execute();
	$row = $fetchNumOfNews->fetch(PDO::FETCH_ASSOC);
	$numOfPostedNews = $row['numOfPostedNews'];
	// dohvatanje podatka koliko je autor objavio dogadjaja
	$fetchNumOfEvent = $connect->prepare("SELECT COUNT(event_id) AS numOfPostedEvent FROM events WHERE author_id = :author");
	$fetchNumOfEvent->bindParam(":author", $_SESSION['AUTHOR_USERID']);
	$fetchNumOfEvent->execute();
	$row = $fetchNumOfEvent->fetch(PDO::FETCH_ASSOC);
	$numOfPostedEvent = $row['numOfPostedEvent'];
	// dohvatanje podatka koliko je autor objavio fajlova
	$fetchNumOfEvent = $connect->prepare("SELECT COUNT(download_id) AS numOfPostedFiles FROM downloads WHERE author_id = :author");
	$fetchNumOfEvent->bindParam(":author", $_SESSION['AUTHOR_USERID']);
	$fetchNumOfEvent->execute();
	$row = $fetchNumOfEvent->fetch(PDO::FETCH_ASSOC);
	$numOfPostedFiles = $row['numOfPostedFiles'];
	// dohvatanje podatka koliko je autor objavio slidera
	$fetchNumOfEvent = $connect->prepare("SELECT COUNT(id) AS numOfPostedSliders FROM slider WHERE author_id = :author");
	$fetchNumOfEvent->bindParam(":author", $_SESSION['AUTHOR_USERID']);
	$fetchNumOfEvent->execute();
	$row = $fetchNumOfEvent->fetch(PDO::FETCH_ASSOC);
	$numOfPostedSliders = $row['numOfPostedSliders'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>ETS Admin</title>
    
<?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
    <!--[if lt IE 9]>
        <link type="text/css" href="assets/css/ie8.css" rel="stylesheet">
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
        <script type="text/javascript" src="assets/plugins/charts-flot/excanvas.min.js"></script>
        <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The following CSS are included as plugins and can be removed if unused-->
    


    </head>

    <body class="infobar-offcanvas">
        
        <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>
                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">            
                                <h1>Početna</h1>
                                <div class="options">

</div>
                            </div>
                            <div class="container-fluid">
                                

	<div data-widget-group="group1">

		<div class="row">
		
			<div class="col-md-3">
				<div class="amazo-tile tile-white">
					<div class="tile-heading">
						<div class="title">Broj objavljenih vijesti</div>
					</div>
					<div class="tile-body">
						<span class="content"><?php echo $numOfPostedNews; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="amazo-tile tile-white">
					<div class="tile-heading">
						<div class="title">Broj objavljenih događaja</div>
					</div>
					<div class="tile-body">
						<span class="content"><?php echo $numOfPostedEvent; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="amazo-tile tile-white">
					<div class="tile-heading">
						<div class="title">Broj objavljenih fajlova</div>
					</div>
					<div class="tile-body">
						<span class="content"><?php echo $numOfPostedFiles; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="amazo-tile tile-white">
					<div class="tile-heading">
						<div class="title">Broj objavljenih slider-a</div>
					</div>
					<div class="tile-body">
						<span class="content"><?php echo $numOfPostedSliders; ?></span>
					</div>
				</div>
			</div>
			
		</div>

		

		<div class="row">
			
			<div class="col-sm-6">
					<a href="vijesti.php" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-eye"></i></div>
				</div>
				<div class="tile-footer">
					Pogledaj vijesti
				</div>
			</a>
				
			</div>
			<div class="col-sm-6">
					<a href="<?php echo $addNews; ?>" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-edit"></i></div>
				</div>
				<div class="tile-footer">
					Dodaj vijest
				</div>
			</a>
				
			</div>
			
	</div>
	<div class="row">
			
			<div class="col-sm-6">
					<a href="slider.php" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-eye"></i></div>
				</div>
				<div class="tile-footer">
					Pogledaj slider
				</div>
			</a>
				
			</div>
			<div class="col-sm-6">
					<a href="<?php echo $addSlider; ?>" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-columns"></i></div>
				</div>
				<div class="tile-footer">
					Dodaj slider
				</div>
			</a>
				
			</div>
			
	</div>
<div class="row">
			
			<div class="col-sm-6">
					<a href="dogadjaji.php" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-eye"></i></div>
				</div>
				<div class="tile-footer">
					Pogledaj događaje
				</div>
			</a>
				
			</div>
			<div class="col-sm-6">
					<a href="<?php echo $addEvent; ?>" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-car"></i></div>
				</div>
				<div class="tile-footer">
					Dodaj događaj
				</div>
			</a>
				
			</div>
			
	</div>
	<div class="row">
			
			<div class="col-sm-6">
					<a href="<?php echo $viewFiles; ?>" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-eye"></i></div>
				</div>
				<div class="tile-footer">
					Pogledaj fajlove
				</div>
			</a>
				
			</div>
			<div class="col-sm-6">
					<a href="<?php echo $addFiles; ?>" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-download"></i></div>
				</div>
				<div class="tile-footer">
					Dodaj fajl
				</div>
			</a>
				
			</div>
			
	</div>
	<div class="row">
			
			<div class="col-sm-6">
					<a href="gallery.php" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-eye"></i></div>
				</div>
				<div class="tile-footer">
					Pogledaj Albume
				</div>
			</a>
				
			</div>
			<div class="col-sm-6">
					<a href="gallery.php" class="shortcut-tile tile-midnightblue">
				<div class="tile-body">
					<div class="pull-left"><i class="fa fa-film"></i></div>
				</div>
				<div class="tile-footer">
					Dodaj Album
				</div>
			</a>
				
			</div>
			
	</div>


                            </div> <!-- .container-fluid -->
                        </div> <!-- #page-content -->
                    </div>
                      <footer role="contentinfo">
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline pull-left">
                                    <li>
                                        <h6 style="margin: 0;"> &copy; 2015 ETS Mostar</h6></li>
                                </ul>
                                <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
                            </div>
                        </footer
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



<script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script> <!-- nano scroller -->

<script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script> 	<!-- Mousewheel support needed for jScrollPane -->

<script type="text/javascript" src="assets/js/application.js"></script>
<script type="text/javascript" src="assets/demo/demo.js"></script>
<script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

<!-- End loading site level scripts -->
    
    <!-- Load page level scripts-->
    
  				<!-- Date Range Picker -->

<script type="text/javascript" src="assets/demo/demo-index.js"></script> 										<!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->

    </body>
</html>