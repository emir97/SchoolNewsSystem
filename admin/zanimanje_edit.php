<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
ob_start();
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: index.php');
    exit();
}
if(isset($_GET[base64_url_encode("action")])){
    if(base64_url_decode($_GET[base64_url_encode("action")]) == "insert"){

        $pagaTitle = "Novo Zanimanje";

        $title = "";
        $content = "";
        $outputImage = "";
        $classForImageTag = "fileinput fileinput-new";

        if(!isset($_GET[base64_url_encode("duration")])) {
            header('location: zanimanja.php');
            exit();
        }
        $duration = base64_url_decode($_GET[base64_url_encode("duration")]);

        $urlContorl = "php/zanimanja_control.php?".base64_url_encode("action")."=".base64_url_encode("insert");
        $urlContorl .= "&".base64_url_encode("duration")."=".base64_url_encode($duration);

        $jsProvjera = "return validateInsert()";

    } else if(base64_url_decode($_GET[base64_url_encode("action")]) == "edit"){

        $pagaTitle = "Izmjena Zanimanja";

        $ID = base64_url_decode($_GET[base64_url_encode("zanimanjeID")]);

        if(!isset($_GET[base64_url_encode("duration")])) {
            header('location: zanimanja.php');
            exit();
        }
        $duration = base64_url_decode($_GET[base64_url_encode("duration")]);

        $connect = connectToDb();
        $fetchEvent = $connect->prepare("SELECT * FROM zanimanja WHERE zanimanje_id = :id LIMIT 1");
        $fetchEvent->bindParam(":id", $ID);
        $fetchEvent->execute();

        foreach ($fetchEvent as $row) {

            $zanimanjeID = $row['zanimanje_id'];

            $title = $row['zanimanje_title'];

            $content = $row['zanimanje_content'];

            $zanimanjeImage = $row['zanimanje_image'];

            $classForImageTag = "fileinput fileinput-exists";
            $outputImage = "<img src='../images/zanimanja/" . $zanimanjeImage."'/>";

            $urlContorl = "php/zanimanja_control.php?".base64_url_encode("action")."=".base64_url_encode("edit");
            $urlContorl .= "&".base64_url_encode("zanimanjeID") . "=". base64_url_encode($zanimanjeID);
            $urlContorl .= "&".base64_url_encode("duration")."=".base64_url_encode($duration);

            $jsProvjera = "return validateEdit()";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <title>ETS Admin - <?php echo $pagaTitle; ?></title>
    <?php 
    include 'includes/meta_tags.php'; 
    include 'includes/include_css_js.php'; 
    ?>

 <script type="text/javascript" src="js/provjera_zanimanja.js"></script>

    </head>

<body class="infobar-offcanvas">

    <?php include 'includes/header.php'; ?>
        <?php include 'includes/menu.php'; ?>
        
                <div class="static-content-wrapper">
                    <div class="static-content">

 <form id="form2" method="post" name="zanimanje" onsubmit="<?php echo $jsProvjera; ?>"  enctype="multipart/form-data"  action="<?php echo $urlContorl; ?>">             
                        <div class="page-content">
                            <div class="page-heading">
                                <h1> <?php echo $pagaTitle; ?></h1>
                                <div class="options">
 
    <div class="btn-toolbar">
        <button value="Spasi Promjene" class="btn btn-midnightblue">Objavi</button>
       
    </div>
</div>
                            </div>
                            
                            <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">


        
        


                                        <div class="container-fluid">

                                           <div class="col-md-7">


		<input name="title" type="text" class="form-control input-lg mb20" value="<?php echo $title; ?>" placeholder="Naslov ...">


		<textarea cols="80" rows="20" class="ckeditor" name="postContent">
          <?php echo $content; ?>
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
                            <input type="hidden" value="" name="">
							<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 200px;"><?php echo $outputImage; ?></div>
							<div>
								<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Ukloni</a>
								<span class="btn btn-default btn-file"><span class="fileinput-new">Izaberi Sliku</span><span class="fileinput-exists">Promjeni</span><input type="file" name="postImage" id="postImage" value=""></span>
								
							</div>
						</div>
					
				</div>
			</div>
		</div>





		<?php 
            $connect = connectToDb();
            $author = $connect->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
            $author->bindParam(":ID", $_SESSION['AUTHOR_USERID']);
            $author->execute();
            if($author->rowCount() == 1){
                foreach ($author as $row) {
                    $firstName = $row['author_name'];
                    $lastName = $row['author_surname'];
                    $authorCV = $row['author_cv'];
                    $authorEmail = $row['author_email'];
                }
            }
       ?>
        
        <div class="panel panel-purple">
            <div class="panel-heading">
                <h2>Autor</h2>
                <div class="panel-ctrls">
                    <a class="button-icon" data-toggle="tooltip" data-trigger="hover" title="Ovdje su prikazani podaci o autoru. Vaši osobni podaci. Ovi podaci se ne mogu promjenitit u ovome polju. Ako želite da prmijenite podatke o autoru idite na stranicu postavke računa. ">
                        <i class="fa fa-info-circle"></i>
                    </a>

                </div>
            </div>
            <div class="panel-body">

                <dl class="dl-horizontal mb20">
                
                    <dt>Ime i Prezime:</dt>
                    <dd>
                       
                    
                    <div class="well well-sm tooltips wells-customize" data-trigger="hover" data-original-title="Ime i Prezime autora">
                       <?php echo $firstName . " " . $lastName; ?>
                    </div>
                    
                
                    
                    </dd>
                    
                    <dt>Email:</dt>
                    <dd>
                        
                       
                    <div class="well well-sm tooltips wells-customize" data-trigger="hover" data-original-title="Email autora">
                        <?php echo $authorEmail; ?>
                    </div>
                               
                            
                        
                        </dd>
                    
                    <dt>Nešto o autoru:</dt>
                    <dd>
                        <div class="well well tooltips wells-customize" data-trigger="hover" data-original-title="Podaci o autoru">
                            <?php echo $authorCV; ?>
                        </div>
                    </dd>
                </dl>

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


<script type="text/javascript" src="assets/plugins/sparklines/jquery.sparklines.min.js"></script>  		<!-- Sparkline -->
<script type="text/javascript" src="assets/plugins/jstree/dist/jstree.min.js"></script>  				<!-- jsTree -->

<script type="text/javascript" src="assets/plugins/codeprettifier/prettify.js"></script> 				<!-- Code Prettifier  -->
<script type="text/javascript" src="assets/plugins/bootstrap-switch/bootstrap-switch.js"></script> 		<!-- Swith/Toggle Button -->

<script type="text/javascript" src="assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>  <!-- Bootstrap Tabdrop -->

<script type="text/javascript" src="assets/plugins/iCheck/icheck.min.js"></script>     					<!-- iCheck -->

<script type="text/javascript" src="assets/js/enquire.min.js"></script> 									<!-- Enquire for Responsiveness -->

<script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script>							<!-- Bootbox -->

<script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script> <!-- nano scroller -->

<script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script> 	<!-- Mousewheel support needed for jScrollPane -->

<script type="text/javascript" src="assets/js/application.js"></script>
<script type="text/javascript" src="assets/demo/demo.js"></script>
<script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

<!-- End loading site level scripts -->
    
    <!-- Load page level scripts-->
    
<script type="text/javascript" src="assets/plugins/form-tokenfield/bootstrap-tokenfield.min.js"></script>     	<!-- Tokenfield -->
<script type="text/javascript" src="assets/plugins/form-jasnyupload/fileinput.min.js"></script>    
<script type="text/javascript" src="assets/plugins/form-typeahead/typeahead.bundle.min.js"></script> 

<script src="js/autocomplite_author.js"></script>

<script type="text/javascript" src="assets/plugins/pines-notify/pnotify.min.js"></script> 		<!-- PNotify -->

<script>
	//Fix since CKEditor can't seem to find it's own relative basepath
	CKEDITOR_BASEPATH  =  "assets/plugins/form-ckeditor/";
    CKEDITOR.replace( 'ckeditor', {
  height: 500
});
</script>
<script type="text/javascript" src="assets/plugins/form-ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="assets/plugins/form-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="assets/demo/demo-mask.js"></script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
<script type="text/javascript" src="assets/plugins/clockface/js/clockface.js"></script>     								<!-- Clockface -->

<script type="text/javascript" src="assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js"></script> 			<!-- Color Picker -->

<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>      			<!-- Datepicker -->
<script type="text/javascript" src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.js"></script>      			<!-- Timepicker -->
<script type="text/javascript" src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script> 	<!-- DateTime Picker -->
<script type="text/javascript" src="assets/plugins/form-daterangepicker/moment.min.js"></script>              			<!-- Moment.js for Date Range Picker -->
<script type="text/javascript" src="assets/plugins/form-daterangepicker/daterangepicker.js"></script>     				<!-- Date Range Picker -->

<script type="text/javascript" src="assets/demo/demo-pickers.js"></script>
</body>

</html>