<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!isset($_GET[base64_url_encode("action")])) {
    header('location 404.php');
    exit();
}
$action = base64_url_decode($_GET[base64_url_encode("action")]);

$Title = "";
$Content = "";
$Image = "";

$classForImageTag = "fileinput fileinput-new";
$outputImage = "";
$actionForForm = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcije");
$actionForForm .= "&". base64_url_encode("action")."=".base64_url_encode("insert");
$validateForm = "return validateForm()";

if($action == "edit") {
    $ID = base64_url_decode($_GET[base64_url_encode("ID")]);

    $connect = connectToDb();
    $fetchData = $connect->prepare("SELECT * FROM sekcije WHERE sekcija_id = :id LIMIT 1");
    $fetchData->bindParam(":id", $ID);
    $fetchData->execute();
    if($fetchData->rowCount() == 1) {
        foreach ($fetchData as $key => $value) {
            $ID = $value['sekcija_id'];
            $Title = $value['sekcija_title'];
            $Content = $value['sekcija_content'];
            $Image = $value['sekcija_image'];

            $classForImageTag = "fileinput fileinput-exists";
            $outputImage = "<img src='../images/sekcije/" . $Image."'/>";

            $actionForForm = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcije");
            $actionForForm .= "&".base64_url_encode("action")."=".base64_url_encode("edit");
            $actionForForm .= "&".base64_url_encode("ID")."=".base64_url_encode($ID);

            $validateForm = "return validateForm2()";
        }
    }
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <title>ETS Admin</title>
    <?php 
    include 'includes/meta_tags.php'; 
    include 'includes/include_css_js.php'; 
    
	?>
    <script type="text/javascript" src="js/provjera_forme_sekcije.js"></script>
	</head>

<body class="infobar-offcanvas">

    <?php include 'includes/header.php'; ?>
        <?php include 'includes/menu.php'; ?>
        
                <div class="static-content-wrapper">
                    <div class="static-content">
 <form id="form2" method="post" name="updatePost" onsubmit="<?php echo $validateForm; ?>"  enctype="multipart/form-data"  action='<?php echo $actionForForm; ?>'>             
                        <div class="page-content">
                            <div class="page-heading">
                                <h1>Sekcije</h1>
                                <div class="options">
                                    <div class="btn-toolbar">
        <button value="Spasi Promjene" class="btn btn-midnightblue" onClick="submitForms()">Objavi</button>
       
    </div>
</div>
                            </div>
                            
                            <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">


        
        


                                        <div class="container-fluid">

                                           <div class="col-md-7">


		<input name="postTitle" type="text" class="form-control input-lg mb20" value="<?php echo $Title; ?>" placeholder="Naslov ...">


		<textarea name="postContent" cols="80" rows="20" class="ckeditor">
         <?php echo $Content; ?>
  		</textarea>


	</div>
	<div class="col-md-5">
        
        <div class="panel panel-midnightblue">
			<div class="panel-heading">
                <h2>Postavi Sliku</h2>
                <div class="panel-ctrls">
                    <a class="button-icon" data-toggle="tooltip" data-trigger="hover" title="Postavljanje slike izabirete u polju ispod.">
                        <i class="fa fa-info-circle"></i>
                    </a>

                </div>
            </div>
			<div class="panel-body">

				<div class="form-group">
					
						<div class="<?php echo $classForImageTag; ?>" style="width: 100%;" data-provides="fileinput">
							<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 200px;"><?php echo $outputImage; ?></div>
							<div>
								<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Ukloni</a>
								<span class="btn btn-default btn-file"><span class="fileinput-new">Izaberi Sliku</span><span class="fileinput-exists">Promjeni</span><input type="file" name="postImage" id="postImage" value=""></span>
								
							</div>
						</div>
					
				</div>
			</div>
		</div>
        
		
	</div>
                                        </div>
                                        <!-- .container-fluid -->

                                    </div>


                                </div>
                                <!-- .container-fluid -->
                            </div>
                            <!-- #page-content -->
                            </form>
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
    
<script type="text/javascript" src="assets/plugins/form-tokenfield/bootstrap-tokenfield.min.js"></script>     	<!-- Tokenfield -->
<script type="text/javascript" src="assets/plugins/summernote/dist/summernote.js"></script>  					<!-- Summernote -->
<script type="text/javascript" src="assets/plugins/form-jasnyupload/fileinput.min.js"></script>    
<script type="text/javascript" src="assets/plugins/form-typeahead/typeahead.bundle.min.js"></script> 

<script src="js/autocomplite_author.js"></script>

<script type="text/javascript" src="assets/plugins/pines-notify/pnotify.min.js"></script> 		<!-- PNotify -->

<script>
	//Fix since CKEditor can't seem to find it's own relative basepath
	CKEDITOR_BASEPATH  =  "assets/plugins/form-ckeditor/";
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
<script type="text/javascript" src="assets/plugins/form-ckeditor/ckeditor.js"></script>  

</body>

</html>
