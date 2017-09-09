<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>
    

    </head>

    <body class="infobar-offcanvas">
        
      
	
          <?php include_once 'includes/header.php'; ?>
          <?php include 'includes/menu.php';?>

        <div id="wrapper">
            <div id="layout-static">
                
                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                           
                            <div class="page-heading">            
                                <h1>Račun</h1>
                            </div>
                            <?php 
                            	$connect = connectToDb();
                            	$fetchAuthor = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
                            	$fetchAuthor->bindParam(":id", $_SESSION['AUTHOR_USERID']);
                            	$fetchAuthor->execute();
                            	if($fetchAuthor->rowCount() == 1) {
                            		foreach ($fetchAuthor as $key => $value) {

                            			$authorName = $value['author_name']." ".$value['author_surname'];

                            			$authorEmail = $value['author_email'];

                            			$authorCountry = $value['author_country'];

                            			$authorImage = $value['author_image'];

                            			$authorCV = $value['author_cv'];

                            			$authorPerm = "Profesor";
                            			if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                            				$authorPerm = "Administrator";
                            			}
                            		}
                            		$connect = NULL;
                            	} else {
                            		$connect = NULL;
                            		header('location: 404.php');
                            		exit();
                            	}
                            ?>
                            <div class="container-fluid">
                                
<div class="row">
	<div class="col-md-3">
		<div class="panel panel-profile">
			<div class="panel-body">
				<div class="user-card">
                    <div class="avatar">
                        <img src="../images/prof/<?php echo $authorImage; ?>" class="img-responsive">
                    </div>
                    <div class="contact-name"><?php echo $authorName; ?></div>
                    <div class="contact-status"><?php echo $authorPerm; ?></div>
                    <ul class="details">
                        <li><a href="#"><?php echo $authorEmail; ?></a></li>
                        <li><?php echo $authorCountry; ?></li>
                        <li><a href="http://etsmostar.edu.ba">etsmostar.edu.ba</a></li>
                    </ul>
                </div>
                
                <hr class="outsider">
                <p class="m-n"><?php echo $authorCV; ?></p>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#timeline" data-toggle="tab">Objavljene vijesti</a></li>
						
					</ul>
				</h2>
			</div>
			<?php 
				$connect = connectToDb();
				$fetchNews = $connect->prepare("SELECT * FROM news WHERE author_id = :id");
				$fetchNews->bindParam(":id", $_SESSION['AUTHOR_USERID']);
				$fetchNews->execute();
				if($fetchNews->rowCount() > 0){
					echo '<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane active" id="timeline">
						<ul class="timeline">';
						foreach ($fetchNews as $key => $value) {

							$newsTitle = $value['news_title'];

							echo '<li class="timeline-midnightblue">
								<div class="timeline-icon"><i class="fa fa-pencil"></i></div>
								<div class="timeline-body">
									<div class="timeline-content">
										<h3>'.$newsTitle.'</h3>
									</div>
								</div>
							</li>';
						}
						echo '</ul>
					</div>';
				} else {
                    echo '<div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="timeline">
                        ';
                        
                            echo '<div><h3>NEMA OBJAVLJENIH VIJESTI</h3></div>';
                        
                        echo '
                    </div>';
                }


			?>
				
			</div>
		</div>
	</div>

</div>

                            </div> <!-- .container-fluid -->
                        </div> <!-- #page-content -->
                    </div>
                    <footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;"> &copy; 2015 Avenger</h6></li>
        </ul>
        <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
    </div>
</footer>
                </div>
            </div>
        </div>




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