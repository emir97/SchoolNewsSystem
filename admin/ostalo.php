<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);

if(isset($_GET[base64_url_encode("stranica")])){
        if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "download"){
            $page="download";
            $title ="Download";
            $buttonLink = "";
            $addFeatures = 'data-toggle="modal" data-target="#modalIzdvoji"';
            $linkToNewFileName = "Dodaj Fajl";
        } else if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "kutakzaroditelje"){
            $page = "kutakzaroditelje";
            $title = "Kutak za roditelje";
            $addFeatures = 'data-toggle="modal" data-target="#modalIzdvoji"';
            $linkToNewFileName = "Promijeni";
            $buttonLink = "";
        } else if(base64_url_decode($_GET[base64_url_encode("stranica")]) == "sekcija"){
            $page = "sekcije";
            $title = "Sekcije";
            $addFeatures = "";
            $linkToNewFileName = "Dodaj sekciju";
            $buttonLink = "sekcije_edit.php?".base64_url_encode("action")."=".base64_url_encode("insert");
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - <?php echo $title; ?></title>
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
                                                         echo '
                                                         <a href="'.$buttonLink.'" class="btn btn-default" '.$addFeatures.'>'.$linkToNewFileName.'</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>
                                               
                                                   
                                                    	<?php 
                                                    	if(isset($_GET[base64_url_encode("stranica")])){
                                                    		$stranica = base64_url_decode($_GET[base64_url_encode("stranica")]);
                                                            $authorID = $_SESSION['AUTHOR_USERID'];
                                                            $connect = connectToDb();
                                                    		if($stranica == "download"){
                                                    			if(checkIsAdmin($_SESSION['AUTHOR_USERID'])){
                                                                   $fetchDownloads = $connect->query("SELECT * FROM downloads");
                                                                } else {
                                                    		       $fetchDownloads = $connect->query("SELECT * FROM downloads WHERE author_id = '$authorID'");
                                                                }
                                                    			if($fetchDownloads->rowCount() > 0){
                                                                    echo ' <div class="panel"><div class="panel-body panel-no-padding">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nasolov</th>
                                                                        <th>Fajl</th>
                                                                        <th>Datum postavljanja</th>
                                                                        <th width="240"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                    				while ($r = $fetchDownloads->fetch(PDO::FETCH_OBJ)) {

                                                    					
                                                    					$downloadID = $r->download_id;

                                                    					$downloadTitle = $r->download_title;

                                                    					$downloadContent = $r->download_content;

                                                    					$downloadDate = new DateTime($r->download_date_upload);
                                                    					$downloadDateUpload = $downloadDate->format('d. m. o.');

                                                                        $urlForDeleteDownload = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
                                                                        $urlForDeleteDownload .= "&".base64_url_encode("action")."=".base64_url_encode("delete");
                                                                        $urlForDeleteDownload .= "&".base64_url_encode("ID")."=".base64_url_encode($downloadID);
                                                                        $urlForViewDownload = "../files/".$downloadContent;
                                                                        echo '<tr>';
                                                                        echo '<td>'.$downloadTitle.'</td>';
                                                                        echo '<td>'.$downloadContent.'</td>';
                                                                        echo '<td>'.$downloadDateUpload.'</td>';
                                                                        echo "<td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke fajla <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a target='_blank' href='".$urlForViewDownload."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj fajl
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForDeleteDownload."' id='bootbox-demo-3'><i class='fa fa-trash-o'></i> Obriši fajl</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>";

                                                    				}
                                                                    echo " </tbody></table></div>";
                                                                            echo ' </div></div></div>';
                                                    			} else {
                                                                    echo "<div class='col-md-4'></div><div class='col-md-4'><h1> NEMA FAJLOVA</h1></div><div class='col-md-4'></div>";
                                                                }

                                                                $urlForAddDownload = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
                                                                $urlForAddDownload .= "&".base64_url_encode("action")."=".base64_url_encode("insert"); 

                                                                echo '<div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form name="addlink" role="form" data-toggle="validator" action="'.$urlForAddDownload.'" method="post" onsubmit="return validateForm3()" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">DODAVANJE FAJLA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="message-text" class="control-label">Naslov: </label>
                                                                                    <textarea required name="title" class="form-control" id="message-text" placeholder="Naslov ..."></textarea>
                                                                                            </div>
                                                                            Izaberi fajl: <br><br>
                                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                <span class="btn btn-default btn-file">
                                                                                    <span class="fileinput-new">Izaberi fajl</span>
                                                                                    <span class="fileinput-exists">Promijeni</span>
                                                                                    <input type="file" name="content">
                                                                                </span>
                                                                                <span class="fileinput-filename"></span>
                                                                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                                            </div>
                                                                       
                                                                    </div>
                                                                                                                    
                                                                    
                                                                    <div class="modal-footer">
                                                                       
                                                                        <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                        <input type="submit" class="btn btn-primary" value="Objavi">           
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                        <!-- /.modal -->';
                                                    		} else if($stranica == "sekcija") {
                                                                $author = $_SESSION['AUTHOR_USERID'];
                                                                if(checkIsAdmin($_SESSION['AUTHOR_USERID'])){
                                                                    $fetchData = $connect->query("SELECT * FROM sekcije");
                                                                } else {
                                                                    $fetchData = $connect->query("SELECT * FROM sekcije WHERE author_id = '$author'");
                                                                }
                                                                if($fetchData->rowCount() > 0){
                                                                      echo ' <div class="panel"><div class="panel-body panel-no-padding">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nasolov</th>
                                                                        <th>Sadržaj</th>
                                                                        <th>Slika</th>
                                                                        <th>Datum postavljanja</th>
                                                                        <th width="240"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    while ($r = $fetchData->fetch(PDO::FETCH_OBJ)) {

                                                                        $sekcijaID = $r->sekcija_id;

                                                                        $sekcijaTitle = $r->sekcija_title;

                                                                        $sekcijaContent = $r->sekcija_content;

                                                                        $sekcijaDate = new DateTime($r->sekcija_date_upload);
                                                                        $sekcijaDateUpload = $sekcijaDate->format('d. m. o.');

                                                                        $sekcijaImage = $r->sekcija_image;

                                                                        $urlForViewSekcija = "";

                                                                        $urlForEditSekcija = "sekcije_edit.php?".base64_url_encode("action")."=".base64_url_encode("edit");
                                                                        $urlForEditSekcija .= "&".base64_url_encode("ID")."=".base64_url_encode($sekcijaID);

                                                                        $urlForDeleteSekcija = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcije");
                                                                        $urlForDeleteSekcija .= "&".base64_url_encode("action")."=".base64_url_encode("delete");
                                                                        $urlForDeleteSekcija .= "&".base64_url_encode("ID")."=".base64_url_encode($sekcijaID);



                                                                        echo '<tr>';
                                                                        echo '<td><div class="titleView">'.$sekcijaTitle.'</div></td>';
                                                                        echo '<td><div class="contentView">'.$sekcijaContent.'</div></td>';
                                                                        echo "<td><img src='../images/sekcije/".$sekcijaImage."' height='100' width='100'/></td>";
                                                                        echo '<td>'.$sekcijaDateUpload.'</td>';
                                                                        echo "<td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke sekcije <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a target='_blank' href='"."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj sekciju
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                         <a href='".$urlForEditSekcija."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-pencil'></i>
                                                                                            Izmijeni sekciju
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForDeleteSekcija."' id='bootbox-demo-3'><i class='fa fa-trash-o'></i> Obriši sekciju</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>";

                                                                    }
                                                                     echo " </tbody></table></div>";
                                                                            echo ' </div></div></div>';
                                                                } else {
                                                                    echo "<div class='col-md-4'></div><div class='col-md-4'><h1> NEMA SEKCIJA</h1></div><div class='col-md-4'></div>";
                                                                }
                                                            } else if($stranica == "kutakzaroditelje"){
                                                                $fetchUpisData = $connect->query("SELECT * FROM podaci_o_skoli WHERE o_skoli_link_title = 'kutakzaroditelje' LIMIT 1");
                                                                if($fetchUpisData->rowCount() > 0){
                                                                    while ($r = $fetchUpisData->fetch(PDO::FETCH_OBJ)) {

                                                                        $linkTitle = $r->o_skoli_link_title;

                                                                        $oSkoliID = $r->o_skoli_id;

                                                                        $oSkoliTitle = $r->o_skoli_title;

                                                                        $oSkoliContent = $r->o_skoli_content;

                                                                        $oSkoliDate = new DateTime($r->o_skoli_date_upload);
                                                                        $oSkoliDateUpload = $oSkoliDate->format('d. m. o.');

                                                                        echo '<div class="row">
                                                                                <div class="col-xs-12">
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-heading"><h2>'.$oSkoliTitle.'</h2></div>
                                                                                    <div class="panel-body pb0">
                                                                                        '.$oSkoliContent.'
                                                                                        </div></div></div></div>';

                                                                    }
                                                                } else {
                                                                    $oSkoliTitle = "";
                                                                    $oSkoliContent ="";
                                                                }
                                                                $urlForEditParentsCorner = "php/ostalo_control.php?".base64_url_encode("stranica")."=".base64_url_encode("kutakzaroditelje");
                                                                echo '<div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form name="addlink" role="form" data-toggle="validator" action="'.$urlForEditParentsCorner.'" method="post" onsubmit="return validateForm3()" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">KUTAK ZA RODITELJE</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="message-text" class="control-label">Naslov: </label>
                                                                                    <textarea required name="title" class="form-control" id="message-text" placeholder="Naslov ...">'.$oSkoliTitle.'</textarea>
                                                                                            </div>
                                                                                            <div class="form-group"><label for="content" class="control-label">Saržaj: </label> <textarea class="ckeditor" name="content">'.$oSkoliContent.'</textarea></div>
                                                                    </div>
                                                                                                                    
                                                                    
                                                                    <div class="modal-footer">
                                                                       
                                                                        <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                        <input type="submit" class="btn btn-primary" value="Objavi">           
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