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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Upis</title>
    <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    if(isset($_GET[base64_url_encode("stranica")])){
        if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "staupisati"){
        	$page="staupisati";
        	$title ="Šta upisati...?";
        } else if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "postupakIBodovanjePriUpisu"){
            $page = "postupakIBodovanjePriUpisu";
            $title = "Postupak i bodovanje pri upisu";
        } else if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "vodicZaStudente"){
            $page = "vodicZaStudente";
            $title = "Vodič za učenike";
        } else if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "prijaveKandidata") {
            $page = "prijaveKandidata";
            $title = "Prijave zainteresiranih kandidata";
        } else {

        }
    }
    ?>

</head>

<body class="infobar-offcanvas">

    <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>

       
                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">
                                <h1><?php echo $title; ?></h1>
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
                                                        $staUpisat = "upis.php?".base64_url_encode("stranica")."=".base64_url_encode($page)."&".base64_url_encode("edit")."=".base64_url_encode($page);
                                                         echo '
                                                         <a href="'.$staUpisat.'" class="btn btn-default">Promijeni</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>

                                                   
                                                    	<?php 
                                                    	if(isset($_GET[base64_url_encode("stranica")])){
                                                    		$stranica = base64_url_decode($_GET[base64_url_encode("stranica")]);
                                                            $connect = connectToDb();
                                                    		if($stranica == "staupisati"){
                                                    			
                                                    			$fetchUpisData = $connect->query("SELECT * FROM upis WHERE link_title = 'staupisati' LIMIT 1");
                                                    			if($fetchUpisData->rowCount() > 0){
                                                    				while ($r = $fetchUpisData->fetch(PDO::FETCH_OBJ)) {

                                                    					$linkTitle = $r->link_title;

                                                    					$upisID = $r->upis_id;

                                                    					$upisTitle = $r->upis_title;

                                                    					$upisContent = $r->upis_content;

                                                    					$upisDate = new DateTime($r->upis_date_upload);
                                                    					$upisDateUplad = $upisDate->format('d. m. o.');

                                                                        echo '<div class="row">
																				<div class="col-xs-12">
																				<div class="panel panel-default">
																					<div class="panel-heading"><h2>'.$upisTitle.'</h2></div>
																					<div class="panel-body pb0">
																						<p class="mb20">'.$upisContent.'</p>
																						</div></div></div></div>';

                                                    				}
                                                    			} else {
                                                                    $upisTitle = "";
                                                                    $upisContent ="";
                                                                }
                                                    		} else if($stranica == "postupakIBodovanjePriUpisu") {
                                                                
                                                                $fetchUpisData = $connect->query("SELECT * FROM upis WHERE link_title = 'postupakIBodovanjePriUpisu' LIMIT 1");
                                                                if($fetchUpisData->rowCount() > 0){
                                                                    while ($r = $fetchUpisData->fetch(PDO::FETCH_OBJ)) {

                                                                        $linkTitle = $r->link_title;

                                                                        $upisID = $r->upis_id;

                                                                        $upisTitle = $r->upis_title;

                                                                        $upisContent = $r->upis_content;

                                                                        $upisDate = new DateTime($r->upis_date_upload);
                                                                        $upisDateUplad = $upisDate->format('d. m. o.');

                                                                        echo '<div class="row">
                                                                                <div class="col-xs-12">
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-heading"><h2>'.$upisTitle.'</h2></div>
                                                                                    <div class="panel-body pb0">
                                                                                        <p class="mb20">'.$upisContent.'</p>
                                                                                        </div></div></div></div>';

                                                                    }
                                                                } else {
                                                                    $upisTitle = "";
                                                                    $upisContent ="";
                                                                }
                                                            } else if($stranica == "vodicZaStudente"){
                                                                $fetchUpisData = $connect->query("SELECT * FROM upis WHERE link_title = 'vodicZaStudente' LIMIT 1");
                                                                if($fetchUpisData->rowCount() > 0){
                                                                    while ($r = $fetchUpisData->fetch(PDO::FETCH_OBJ)) {

                                                                        $linkTitle = $r->link_title;

                                                                        $upisID = $r->upis_id;

                                                                        $upisTitle = $r->upis_title;

                                                                        $upisContent = $r->upis_content;

                                                                        $upisData = $r->upis_data;

                                                                        $upisDate = new DateTime($r->upis_date_upload);
                                                                        $upisDateUplad = $upisDate->format('d. m. o.');

                                                                        $urlForView = "../vodic/$upisData";

                                                                        echo '<div class="row">
                                                                                <div class="col-xs-12">
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-heading"><h2>'.$upisTitle.'</h2></div>
                                                                                    <div class="panel-body pb0">
                                                                                        <p class="mb20">'.$upisData.'</p><a target="_blank" class="btn btn-primary" href="'.$urlForView.'">Pogledaj</a><br><br>
                                                                                        </div></div></div></div>';

                                                                    }
                                                                } else {
                                                                    $upisTitle = "";
                                                                    $upisContent ="";
                                                                }
                                                            }
                                                    	}


                                                    	?>
                                                    	
                                                    	 <?php if(isset($_GET[base64_url_encode("edit")])){
                                                            if(base64_url_decode($_GET[base64_url_encode("edit")]) != "vodicZaStudente") {
                                                            $action = "php/upis_control.php?".base64_url_encode("whatUpload")."=".base64_url_encode($page);
                                                            echo '
                        										<div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form name="addlink" role="form" data-toggle="validator" action="'.$action.'" method="post" onsubmit="return validateForm3()" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">IZMIJENI '.strtoupper($title).'</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                            	
                                                                            	<div class="form-group">
                        															<label for="message-text" class="control-label">Naslov: </label>
                        															<textarea required name="title" class="form-control" id="message-text" placeholder="Naslov ...">'.$upisTitle.'</textarea>
                        																	</div>
                                                                              <div class="form-group"><label for="content" class="control-label">Saržaj: </label> <textarea cols="80" rows="10" class="ckeditor" name="content">'.$upisContent.'</textarea></div>
                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Izmijeni">           
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->';
                                                        }
                                                        if(base64_url_decode($_GET[base64_url_encode("edit")]) == "vodicZaStudente") {
                                                            $action = "php/upis_control.php?".base64_url_encode("whatUpload")."=".base64_url_encode($page);
                                                            echo '
                                                                <div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form name="addlink" role="form" data-toggle="validator" action="'.$action.'" method="post" onsubmit="return validateForm3()" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">IZMIJENI '.strtoupper($title).'</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                        Upload fajla: <br><br>
                                                                                        <span class="btn btn-default btn-file">
                                                                                            <span class="fileinput-new">Izaberi fajl</span>
                                                                                            <span class="fileinput-exists">Promijeni</span>
                                                                                            <input type="file" name="student_file">
                                                                                        </span>
                                                                                        <span class="fileinput-filename"></span>
                                                                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                                                    
                                                                                            </div>
                                                                              
                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Izmijeni">           
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->';
                                                        }
                                                    }
                                                    ?>
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
					<script>
                	//Fix since CKEditor can't seem to find it's own relative basepath
                	CKEDITOR_BASEPATH  =  "assets/plugins/form-ckeditor/";
                    CKEDITOR.replace( 'ckeditor', {
                    height: 80
                    });
                    </script>
                    <script type="text/javascript" src="assets/plugins/form-ckeditor/ckeditor.js"></script> 
                    <script type="text/javascript" src="assets/plugins/form-jasnyupload/fileinput.min.js"></script>     
                    <!-- End loading page level scripts-->

                    </body>

                    </html>
<?php if(isset($_GET[base64_url_encode("edit")])){
	
		echo "<script>$('#modalIzdvoji').modal('show');</script>";
}?>