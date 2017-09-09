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
    
    <title>ETS Admin - Albumi</title>
   

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
                                <h1>Izmjena albuma</h1>
                            </div>
                             <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">




                                        <div class="container-fluid">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form action="" class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
                                                         echo '
                                                         <a data-toggle="modal" data-target="#addGallery" class="btn btn-default">Dodaj Sliku</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </form>
                                                   
                                                <div class="row" data-widget-group="group-demo">
                                                                            <?php
                                                                            
                                                                            $author = $_SESSION['AUTHOR_USERID'];

                                                                            if(!isset($_GET[base64_url_encode("albumID")])) {
                                                                                header('location: gallery.php');
                                                                                exit();
                                                                            }

                                                                            $dbConnect = connectToDb();

                                                                            $ID = base64_url_decode($_GET[base64_url_encode("albumID")]);
                                                                         
                                                                            //konektovanje na bazu
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchNotifyData = "SELECT * FROM gallery_images WHERE album_id = '$ID'";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNotifyData);
                                                                            } else {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchNotifyData = "SELECT * FROM gallery_images WHERE album_id = '$ID' AND author_id = '$author'";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNotifyData);
                                                                            }
                                                                            if($result->rowCount() > 0) {
                                                                                foreach ($result as $key => $value) {

                                                                                    $photoID = $value['photo_id'];

                                                                                    $image = "<img src='../images/gallery/".$value['photo_name']."' height='150' width='285' />";

                                                                                    $urlForDeletePhoto = "php/gallery_control.php?". base64_url_encode("actionForAlbum")."=".base64_url_encode("deletePhotoFromAlbum");
                                                                                    $urlForDeletePhoto .= "&".base64_url_encode("ID")."=".base64_url_encode($photoID);
                                                                                    $urlForDeletePhoto .= "&".base64_url_encode("albumID")."=".base64_url_encode($ID);
                                                                                    
                                                                                echo '<div class="col-md-4">';
                                                                                echo '
                                                                                
                                                                                <div class="panel panel-midnightblue" data-widget='."'".'{"id" : "wiget6", "draggable": "false"}'."'".'>
                                                                                <div class="panel-heading">
                                                                                <h2>Slika</h2>
                                                                                <div class="panel-ctrls">
                                                                                    
                                                                                    <a href="'.$urlForDeletePhoto.'" class="button-icon"  data-toggle="tooltip" data-placement="right" title="Obriši obavijest"><i class="fa fa-trash-o"></i></a>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <p><div class="notify-content"> '.$image.'</div></p>
                                                                               
                                                                            </div>
                                                                        </div>


                                                                                ';
                                                                                echo "</div>";
                                                                                
                                                                            
                                                                            }
                                                                        }
                                                                        
                                                                        else {
                                                                        echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA SLIKA U ALBUMU</h1></div><div class='col-md-4'></div>";
                                                                    }
                                                                        $dbConnect = NULL;
                                                                        ?>
                                                                        <div class="modal fade" id="addGallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <?php 
                                                    $uploadUrl = "php/gallery_control.php?".base64_url_encode("actionForAlbum")."=".base64_url_encode("uploadPhotoToAlbum");
                                                    $uploadUrl .= "&".base64_url_encode("albumID")."=".base64_url_encode($ID);
                                                    ?>
                                                     <form action="<?php echo $uploadUrl; ?>" method="post" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">DODAVANJE SLIKE U ALBUM</h2>
                                                    </div>
                                                    <div class="modal-body">
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
                                             </div></div></div></div></div></div></div></div>
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

                    <!-- End loading page level scripts-->
                     <script type="text/javascript" src="assets/plugins/wijets/wijets.js"></script>                                  <!-- Wijet -->

<script>
    $.wijets().make();
</script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip('show')
})
</script>
</body>

</html>
