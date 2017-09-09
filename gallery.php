<?php include 'core/connection.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Galerija</title>
     <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>

    <!--[if lt IE 8]>
    <div style=' clear: both; text-align:center; position: relative;'>
            <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="../../../../storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
        </div>
    <![endif]-->
   
    <script>
        window.addEventListener("load", function(){
        var load_screen = document.getElementById("loader");
        document.body.removeChild(load_screen);
        });
    </script>
</head>
<body ng-app="ETS">
<!-- LOADER -->
    <div class="page-loader" id="loader">
      <div class="loading">
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
      </div>
    </div>
    <!-- ./LOADER -->
<?php include('view/header_mobile.php'); ?>
<?php include('view/header.php'); ?>
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Galerija</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="col-md-3">
                <?php include 'widgets/pretraga_widget.php'; ?>

                <div class="widget-main">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Sortiranje</h4>
                    </div>
                    <div class="widget-inner">
                        <ul class="mixitup-controls">
                            <li class="sort active" data-sort="default" data-order="desc">Random</li>
                            <li class="sort" data-sort="data-cat" data-order="asc">Uzlazno</li>
                            <li class="sort" data-sort="data-cat" data-order="desc">Silazno</li>
                        </ul>
                    </div> <!-- /.widget-inner -->
                </div> <!-- /.widget-main -->

            </div> <!-- /.col-md-3 -->

            <div class="col-md-9">
                <div class="row">
                    
                    <div id="Grid">
<?php
//povezivanje na bazu
$connectToDb = connectOnDb();
//dohvatanje dogadjaja
$fetchAlbum = $connectToDb->query("SELECT * FROM albums ORDER BY RAND()");
if($fetchAlbum->rowCount() > 0){
while($row = $fetchAlbum->fetch(PDO::FETCH_OBJ)){
    
    //ID dogadjaja
    $albumID = $row->album_id;
    
    //naslov dogadjaja
    $albumTitle = $row->album_title;
    // uklanjanje nepozeljnih karaktera iz stringa
    $albumTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $albumTitle);
    $albumTitle = preg_replace("/\r|\n/", "", $albumTitle);
    
    // slika albuma
    $albumImage = $row->content_photo;
    
    //sadrzaj dogadjaja
    $albumContent = strip_tags( $row->album_content);
    // uklanjanje nepozeljnih karaktera iz stringa
    $albumContent = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $albumContent);
    $albumContent = preg_replace("/\r|\n/", "", $albumContent);

    
    $albumUrl = "galerija_slike.php?ID=" . $albumID;
                        
          echo '          
    <div class="col-md-4 mix students" data-cat="'.$albumID.'">
        <div class="gallery-item">
            <a class="fancybox" rel="gallery1" href="'.$albumUrl.'">
                <div class="gallery-thumb">
                    <img src="./images/gallery/'.$albumImage.'" alt="'.$albumImage.'" />
                </div>
                <div class="gallery-content">
                    <h4 class="gallery-title">'.$albumTitle.'</h4>
                </div>
            </a>
        </div> <!-- /.gallery-item -->
    </div> <!-- /.col-md-4 -->
    ';
}}
?>
                    </div> <!-- /#Grid -->

                </div> <!-- /.row -->

            </div> <!-- /.col-md-9 -->

        </div> <!-- /.row -->
        
    </div> <!-- /.container -->

    <?php include 'view/footer.php'; ?>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
</body>
</html>