<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
$whatToUpload = base64_url_decode($_GET[base64_url_encode("novi")]);
if($whatToUpload == "vijest"){
     $naslov = "Nova Vijest";
} else if($whatToUpload == "slider"){
     $naslov = "Novi Slide";
} else {
     header('index.php');
     exit();
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
    <script type="text/javascript" src="js/provjera_forme_upload_post.js"></script>
	</head>

<body class="infobar-offcanvas">

    <?php include 'includes/header.php'; ?>
        <?php include 'includes/menu.php'; ?>
        
                <div class="static-content-wrapper">
                    <div class="static-content">
 <form id="form2" method="post" name="updatePost" onsubmit="return validateForm()"  enctype="multipart/form-data"  action='<?php echo "php/insert_news.php?".base64_url_encode("postType") ."=".base64_url_encode($whatToUpload); ?>'>             
                        <div class="page-content">
                            <div class="page-heading">
                                <h1><?php print $naslov; ?></h1>
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


		<input name="postTitle" type="text" class="form-control input-lg mb20" value="" placeholder="Naslov ...">


		<textarea name="postContent" cols="80" rows="20" class="ckeditor">
         
  		</textarea>


	</div>
	<div class="col-md-5">
        
        <div class="panel panel-midnightblue">
			<div class="panel-heading">
                <h2>Postavi Sliku</h2>
                <div class="panel-ctrls">
                    <a class="button-icon" data-toggle="tooltip" data-trigger="hover" title="Postavljanje slike za vijest izabirete u polju ispod.">
                        <i class="fa fa-info-circle"></i>
                    </a>

                </div>
            </div>
			<div class="panel-body">

				<div class="form-group">
					
						<div class="fileinput fileinput-new" style="width: 100%;" data-provides="fileinput">
							<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 200px;"></div>
							<div>
								<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Ukloni</a>
								<span class="btn btn-default btn-file"><span class="fileinput-new">Izaberi Sliku</span><span class="fileinput-exists">Promjeni</span><input type="file" name="postImage" id="postImage" value=""></span>
								
							</div>
						</div>
					
				</div>
			</div>
		</div>
        
       
        
        
        <?php 
        if($whatToUpload == "slider"){
            ?>
        
        <div class="panel panel-midnightblue">
			<div class="panel-heading">
                <h2>Redni broj slide-a</h2>
                <div class="panel-ctrls">
                    <a class="button-icon" data-toggle="tooltip" data-trigger="hover" title="Redni broj slide-a podešavate tako što će te odabrati odgovarajuće dugme ispod.">
                        <i class="fa fa-info-circle"></i>
                    </a>

                </div>
            </div>
			<div class="panel-body">

				
					<?php
						for($num = 1; $num<=6; $num++){
                            
                              
                                 echo "
                                <input class='radio-inline icheck'  type='radio' value='$num' name='redBrSlide'/> 
                                <label class='radio-inline icheck'>$num</label>
                               
                               
                               
                               ";
                            
                        }
						
						
					?>
				

			</div>
		</div>
       <?php } ?>
        
        <div class="panel panel-success">
			<div class="panel-heading">
                <h2>Oznake</h2>
                <div class="panel-ctrls">
                    <a class="button-icon" data-toggle="tooltip" data-trigger="hover" title="Oznaku upisujete u odgovarajuće polje ispod. Oznake šluže za lakše pretraživanje vijesti.">
                        <i class="fa fa-info-circle"></i>
                    </a>

                </div>
            </div>
			<div class="panel-body">

						<input name="postKeywords" value="" class="form-control typeahead example-countries" placeholder="Oznaka..." >
      
						
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
	
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
<script type="text/javascript" src="assets/plugins/form-ckeditor/ckeditor.js"></script>  

</body>

</html>
