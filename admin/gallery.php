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
    
    <title>ETS Admin - Galerija</title>
   

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
                                <h1>Albumi</h1>
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
                                                         <a data-toggle="modal" data-target="#addGallery" class="btn btn-default">Dodaj Album</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>

                                                    
                                                        
                                                                    
                                                                        <?php

                                                                            $authorSesion = $_SESSION['AUTHOR_USERID'];

                                                                            //konektovanje na bazu
                                                                            $dbConnect = connectToDb();
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchGaleryData = "SELECT * FROM albums";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchGaleryData);
                                                                            } else {
                                                                                $queryForFetchGaleryData = "SELECT * FROM albums WHERE author_id = '$authorSesion'";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchGaleryData);
                                                                            }
                                                                            if($result->rowCount() > 0) {
                                                                                echo '<div class="panel"><div class="panel-body panel-no-padding">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Naslov</th>
                                                                        <th>Tekst</th>
                                                                        <th>Slika</th>
                                                                        <th>Autor</th>
                                                                        <th>Datum</th>
                                                                        <th width="240"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                            
                                                                             $authorSesion = NULL;
                                                                            //prikaz podataka 
                                                                            foreach($result as $row){
                                                                                $albumID = $row['album_id']; 
                                                                                
                                                                                // naslov vijesti
                                                                                $albumTitle = $row['album_title'];
                                                                                
                                                     
                                                                                // uredjivanje prikaza naslova
                                                                                $albumTitle = restrictText($albumTitle);
                                                                                
                                                                                // uredjivanje prikaza sadrzaja
                                                                                $albumConent = $row['album_content'];
                                                                                $albumConent = restrictText($albumConent);

                                                                                // naslovna slika albuma
                                                                                $contentPhoto = $row['content_photo'];
                                                                            
                                                                                //datum
                                                                                $albumDate = new DateTime($row['album_date_post']);
                                                                                $albumDatePost = $albumDate->format('d.m.o.');

                                                                                $urlForEditAlbum = "edit_gallery.php?".base64_url_encode("albumID")."=".base64_url_encode($albumID);

                                                                                $urlForViewAlbum = "../galerija_slike.php?ID=".$albumID;

                                                                                $urlForDeleteAlbum = "php/gallery_control.php?".base64_url_encode("actionForAlbum")."=".base64_url_encode("deleteAlbum");
                                                                                $urlForDeleteAlbum .= "&".base64_url_encode("albumID")."=".base64_url_encode($albumID);
                                                                                
                                                                                $newsAuthor = $row['author_id'];
                                                                                $queryForFetchAuthor = "SELECT * FROM authors WHERE author_id='$newsAuthor' LIMIT 1";
                                                                                $authorResult = $dbConnect->query($queryForFetchAuthor);
                                                                                foreach($authorResult as $row){
                                                                                 $authorName = $row['author_name']; 
                                                                                 $authorSurname = $row['author_surname'];     
                                                                                
                                                                                
                                                                                echo "
                                                                                <tr>
                                                                                <td><div class='titleView'>$albumTitle</div></td>
                                                                                <td><div class='contentView'>$albumConent</div></td>
                                                                                <td><img src='../images/gallery/".$contentPhoto."' height='100' width='100'/></td>
                                                                                <td>$authorName $authorSurname</td>
                                                                                ";
                                                                                print("
                                                                                <td>$albumDatePost</td>
                                                                                <td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke albuma <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a target='_blank' href='".$urlForViewAlbum."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj album
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForEditAlbum."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-pencil'></i>
                                                                                            Izmijeni album
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForDeleteAlbum."'><i class='fa fa-trash-o'></i> Obriši album</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>
                                                                                </tr>
                                                                                ");
                                                                                
                                                                            }
                                                                            }
                                                                            echo " </tbody></table></div>";
                                                                            echo ' </div></div></div>';
                                                                        }
                                                                        else {
                                                                            echo '<div class="col-md-4"></div><div class="col-md-4"><h3>NEMA ALBUMA</h3></div><div class="col-md-4"></div>';
                                                                        }
                                                                        ?>
                                                   
                                             <div class="modal fade" id="addGallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                     <form action="php/gallery_control.php" method="post" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">DODAVANJE ALBUMA</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="message-text" class="control-label">Naslov albuma: </label>
                                                            <textarea required name="title" class="form-control" id="message-text" placeholder="Naslov albuma..."></textarea>
                                                        </div>
                                                         <div class="form-group">
                                                            <label for="message-text" class="control-label">Podaci o  albuma: </label>
                                                            <textarea required name="content" class="form-control" id="message-text" placeholder="Podaci o albuma..."></textarea>
                                                        </div>
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            Upload fajla: <br><br>
                                                           
                                                                <input type="file" name="gallery_photo[]" multiple>
                                                    
                                                        </div>
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="btn btn-default" data-dismiss="modal">Zatvori</a>
                                                        <input type="submit" value="Dodaj" class="btn btn-primary"/>
                                                    </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                       


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
<script type="text/javascript" src="assets/plugins/form-jasnyupload/fileinput.min.js"></script>  
                    <!-- End loading page level scripts-->

</body>

</html>
