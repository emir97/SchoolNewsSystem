<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
?>
 <?php 
                                $connect = connectToDb();
                                $fetchAuthor = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
                                $fetchAuthor->bindParam(":id", $_SESSION['AUTHOR_USERID']);
                                $fetchAuthor->execute();
                                if($fetchAuthor->rowCount() == 1) {
                                    foreach ($fetchAuthor as $key => $value) {

                                        $authorName = $value['author_name'];

                                        $authorSurname = $value['author_surname'];

                                        $authorEmail = $value['author_email'];

                                        $authorCountry = $value['author_country'];

                                        $authorImage = $value['author_image'];

                                        $outputImage = "<img src='../images/prof/".$authorImage."' style='height:100%; width:100%;'/>";

                                        $classForImageTag = "fileinput fileinput-exists";

                                        $authorCV = $value['author_cv'];

                                        $musko = $zensko = "";
                                        if($value['author_sex'] == "1"){
                                            $musko = "checked";
                                        } else if($value['author_sex'] == "2") {
                                            $zensko = "checked";
                                        }

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
                            <?php $url = "php/edit_profile.php?".base64_url_encode("edit")."=".base64_url_encode("data"); ?>
                           <form method="post" action="<?php echo $url; ?>" enctype="multipart/form-data">
                            <div class="page-heading">            
                                <h1>Postavke računa</h1>
                                 <div class="options">
 
    <div class="btn-toolbar">
        <a data-toggle="modal" data-target="#modalIzdvoji" class="btn btn-midnightblue">Spasi promjene</a>
       
    </div>
</div>
       
    </div>
                            
<div class="col-md-5">
        
        <div class="panel panel-midnightblue">
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
        <br><br>
        <?php 
        $urlForSetDefaultPhoto = "account_settings.php?".base64_url_encode("edit")."=".base64_url_encode("defaultPhoto"); 
        $urlForChangePassword = "account_settings.php?".base64_url_encode("edit")."=".base64_url_encode("changePassword");
        ?>
        <a href="<?php echo $urlForSetDefaultPhoto; ?>" class="btn btn-primary" style="width:100%">Postavi zadanu sliku profila</a><br><br>
        <a href="<?php echo $urlForChangePassword; ?>" class="btn btn-primary" style="width:100%">Promijeni šifru</a><br><br>

    </div>
    <div class="col-md-7">
        <div class="col-md-6">
            <h3>Ime: </h3>
            <input name="name" type="text" class="form-control input-lg mb20" value="<?php echo $authorName; ?>" placeholder="Ime ...">
        </div><div class="col-md-6">
            <h3>Prezime: </h3>
             <input name="surname" type="text" class="form-control input-lg mb20" value="<?php echo $authorSurname; ?>" placeholder="Prezime ...">
         </div>
            
             <div class="col-md-12">
             <h3>Email: </h3>
              <input name="email" type="email" class="form-control input-lg mb20" value="<?php echo $authorEmail; ?>" placeholder="Email ...">
          </div>
          <div class="col-md-12">
                <h3>Spol: </h3>
               <input <?php echo $musko; ?> name="sex" type="radio" value="1"> Muško&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input <?php echo $zensko; ?> name="sex" type="radio" value="2"> Žensko
           </div>
              <div class="col-md-12">
                <h3>Podaci o autoru: </h3>
               <textarea name="cv" cols="20" rows="20" class="form-control input-lg mb20" placeholder="Podaci ..."><?php echo $authorCV; ?></textarea>
           </div><br>
    </div>
    <div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">POTVRDA IZMJENA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                               <h5> Unesite šifru za potvrđivanje izmjena</h5>
                                                                                <div class="form-group">
                                                                                   <input type="password" class="form-control" name="pw" placeholder="Unesite šifru..." required>
                                                                                            </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Spremi">           
                                                                            </div>
                                                                          
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->
</form>
    <?php 
        if(isset($_GET[base64_url_encode("edit")])) {
            if(base64_url_decode($_GET[base64_url_encode("edit")]) == "defaultPhoto") {
                $url = "php/edit_profile.php?".base64_url_encode("edit")."=".base64_url_encode("setDefaultPhoto");
                echo ' <div class="modal fade" id="modalPw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                        <form action="'.$url.'" method="post" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">POČETNA SLIKA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                               <h5> Unesite šifru za potvrđivanje izmjena</h5>
                                                                                <div class="form-group">
                                                                                   <input type="password" class="form-control" name="pw" placeholder="Unesite šifru..." required>
                                                                                            </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Spremi">           
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->';
            } else if(base64_url_decode($_GET[base64_url_encode("edit")]) == "changePassword") {
                 $url = "php/edit_profile.php?".base64_url_encode("edit")."=".base64_url_encode("changeAuthorPassword");
                echo ' <div class="modal fade" id="modalPw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                        <form action="'.$url.'" method="post" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">PROMJENA PASSWORDA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                               <h5> Unesite staru šifru: </h5>
                                                                                <div class="form-group">
                                                                                   <input type="password" class="form-control" name="o_pw" placeholder="Unesite staru šifru..." required>
                                                                                    </div>
                                                                                     <h5> Unesite novu šifru: </h5>
                                                                                <div class="form-group">
                                                                                   <input type="password" class="form-control" name="n_pw" placeholder="Unesite novu šifru..." required>
                                                                                    </div>
                                                                                    <h5> Unesite ponovo novu šifru: </h5>
                                                                                <div class="form-group">
                                                                                   <input type="password" class="form-control" name="n_r_pw" placeholder="Ponovo unesite novu šifru..." required>
                                                                                    </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Spremi">           
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
<script type="text/javascript" src="assets/plugins/form-jasnyupload/fileinput.min.js"></script>  
<script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script> 	<!-- Mousewheel support needed for jScrollPane -->

<script type="text/javascript" src="assets/js/application.js"></script>
<script type="text/javascript" src="assets/demo/demo.js"></script>
<script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

<!-- End loading site level scripts -->
    
    <!-- Load page level scripts-->
    

    <!-- End loading page level scripts-->

    </body>
</html>
<?php 
if(isset($_GET[base64_url_encode("edit")])) {
    if(base64_url_decode($_GET[base64_url_encode("edit")]) == "defaultPhoto") {
        echo "<script>$('#modalPw').modal('show');</script>";
    } else if(base64_url_decode($_GET[base64_url_encode("edit")]) == "changePassword") {
        echo "<script>$('#modalPw').modal('show');</script>";
    }
}