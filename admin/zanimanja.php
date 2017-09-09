<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: index.php');
    exit();
}
if(isset($_GET[base64_url_encode("trajanjeSkolovanja")])) {
	$duration = base64_url_decode($_GET[base64_url_encode("trajanjeSkolovanja")]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Zanimanja</title>
   

    <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>

</head>

<body class="infobar-offcanvas">

    <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>
       <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">
                                <h1>Zanimanja <?php echo $duration; ?> godine</h1>
                                <div class="options">

                                </div>
                            </div>
                            <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
                                                        $urlForAddZanimanje = "zanimanje_edit.php?".base64_url_encode("action")."=".base64_url_encode("insert");
                                                        $urlForAddZanimanje .= "&".base64_url_encode("duration")."=".base64_url_encode($duration);
                                                         echo '
                                                         <a href="'.$urlForAddZanimanje.'" class="btn btn-default">Dodaj Zanimanje</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>
  												<div class="row">
  													<?php 
  														$connect = connectToDb();
  														$fetch = $connect->query("SELECT * FROM zanimanja WHERE zanimanje_duration = '$duration'");
  														if($fetch->rowCount() > 0){
  															while ($r = $fetch->fetch(PDO::FETCH_OBJ)) {

  																// id zanimanja
  																$zanimanjeID = $r->zanimanje_id;

  																// naslov zanimanja
  																$zanimanjeTitle = $r->zanimanje_title;
  																if(strlen($zanimanjeTitle) > 45){
  																	$zanimanjeTitle = substr($zanimanjeTitle, 0, 45) ."...";
  																}

  																// sadrzaj zanimanja
  																$zanimanjeContent = $r->zanimanje_content;
  																

  																// vrijeme upload zanimanja
  																$dateUpload = new DateTime($r->zanimanje_date_upload);
  																$zanimanjeDateUpload = $dateUpload->format("d. m. o G:i");
  																
  																//vrijeme editovanja zanimanja
  																$dateEdit = new DateTime($r->zanimanje_date_edit);
  																$zanimanjeDateEdit = $dateEdit->format("d. m. o G:i");

  																// slika zanimanja 
  																$zanimanjeImage = $r->zanimanje_image;
  																$zanimanjeImage = "<img src='../images/zanimanja/".$zanimanjeImage."'/>";

  																// trajanje 
  																$zanimanjeDuration = $r->zanimanje_duration;

  																// link za brisanje zanimanja
  																$urlForDelete = "php/zanimanja_control.php?".base64_url_encode("action")."=".base64_url_encode("delete");
  																$urlForDelete .= "&".base64_url_encode("ID")."=".base64_url_encode($zanimanjeID);
  																$urlForDelete .= "&".base64_url_encode("duration")."=".base64_url_encode($duration);

  																// link za editovanje zanimanja
                                  $urlForEdit = "zanimanje_edit.php?".base64_url_encode("action")."=".base64_url_encode("edit");
                                  $urlForEdit .= "&".base64_url_encode("zanimanjeID")."=".base64_url_encode($zanimanjeID);
                                  $urlForEdit .= "&".base64_url_encode("duration")."=".base64_url_encode($duration);

  																// link za gledanje zanimanja
                                  $urlForView = "../zanimanja_detalji.php?ID=".$zanimanjeID."&title=".$r->zanimanje_title;
  																echo '
  																	<div class="col-md-6">
				  													<div class="panel panel-midnightblue">
				                                                    <div class="panel-heading">
				                                                        <div class="zanimanjeTitle"><h2><i class="fa fa-bank"></i> '.$zanimanjeTitle.'   </h2></div>
				                                                    <div class="panel-ctrls">

		                                                                <a href="'.$urlForDelete.'" class="button-icon" title="Obriši"><i class="fa fa-trash-o"></i></a>
		                                                                <a href="'.$urlForEdit.'" class="button-icon" title="Izmijeni"><i class="fa fa-pencil"></i></a>
		                                                                <a href="'.$urlForView.'" class="button-icon" title="Pogledaj"><i class="fa fa-eye"></i></a>
		                                                                

		                                                            </div>
		                                                            </div>
		                                                            <div class="panel-body">
			                                                            <div class="zanimanjeImage">'.$zanimanjeImage.'</div>
			                                                            <div class="zanimanjeContent">'.$zanimanjeContent.'</div>
			                                                        </div>
			                                                        </div></div>
  																';
  															}
  														} else {
  															echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA ZANIMANJA</h1></div><div class='col-md-4'></div>";
  														}
  													?>
                                                            
                                                           
                                                        
                                                        
                                           
  												</div>
                                        <!-- .container-fluid -->
                                        </div>


                                </div>
                                <!-- .container-fluid -->
                            </div>
                            <!-- #page-content --></div></div>
                        </div>
                        <footer role="contentinfo">
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline pull-left">
                                    <li>
                                        <h6 style="margin: 0;"> &copy; 2015 ETS Mostar</h6></li>
                                </ul>
                                <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>


           



                    <!-- Load site level scripts -->

                    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

                    <script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
                    <!-- Load jQuery -->
                    <script type="text/javascript" src="assets/js/jqueryui-1.9.2.min.js"></script>
                    <!-- Load jQueryUI -->

                    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
                    <!-- Load Bootstrap -->


                    <script type="text/javascript" src="assets/plugins/easypiechart/jquery.easypiechart.js"></script>
                    <!-- EasyPieChart-->
                    <script type="text/javascript" src="assets/plugins/sparklines/jquery.sparklines.min.js"></script>
                    <!-- Sparkline -->
                    <script type="text/javascript" src="assets/plugins/jstree/dist/jstree.min.js"></script>
                    <!-- jsTree -->

                    <script type="text/javascript" src="assets/plugins/codeprettifier/prettify.js"></script>
                    <!-- Code Prettifier  -->
                    <script type="text/javascript" src="assets/plugins/bootstrap-switch/bootstrap-switch.js"></script>
                    <!-- Swith/Toggle Button -->

                    <script type="text/javascript" src="assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>
                    <!-- Bootstrap Tabdrop -->


                    <script type="text/javascript" src="assets/js/enquire.min.js"></script>
                    <!-- Enquire for Responsiveness -->



                    <script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script>
                    <!-- nano scroller -->

                    <script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script>
                    <!-- Mousewheel support needed for jScrollPane -->

                    <script type="text/javascript" src="assets/js/application.js"></script>
                    <script type="text/javascript" src="assets/demo/demo.js"></script>
                    <script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

                    <!-- End loading site level scripts -->
                    <script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script>	
                    <!-- Load page level scripts-->

                    <!-- Date Range Picker -->

                    <script type="text/javascript" src="assets/demo/demo-index.js"></script>
                   <script type="text/javascript" src="assets/plugins/pines-notify/pnotify.min.js"></script> 
                    <!-- Initialize scripts for this page-->
				<script type="text/javascript" src="assets/plugins/form-parsley/parsley.js"></script>                   <!-- Validate Plugin / Parsley -->
				<script type="text/javascript" src="assets/demo/demo-formvalidation.js"></script>
                    <!-- End loading page level scripts-->

</body>

</html>