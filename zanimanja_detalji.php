<?php
include 'core/connection.php'; 
if(isset($_GET['ID']) && isset($_GET['title'])){
    
    //dohavatanje vijesti
    $ID = $_GET['ID'];
    $title = $_GET['title'];

    $connectToDb = connectOnDb();

    $fetchNews = $connectToDb->prepare("SELECT * FROM zanimanja WHERE zanimanje_id = :ID AND zanimanje_title = :title LIMIT 1");
    $fetchNews->bindParam(":ID", $ID);
    $fetchNews->bindParam(":title", $title);
    $fetchNews->execute();

    if($fetchNews->rowCount() == 1){
        foreach ($fetchNews as $row) {

            //naslov vijesti
            $title = $row['zanimanje_title'];

            //sadrzaj vijesti 
            $content = $row['zanimanje_content'];

            //slika vijesti 
            $image = $row['zanimanje_image'];
        }
        
    } else {
        header('location: vijesti.php');
    }
} else {
    header('location: vijesti.php');
}


?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - <?php echo $title; ?> </title>
    <?php 
    include 'includes/SEO.php';
    include 'includes/css_js.php';
     ?>
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
                    <h6><a href="./vijesti.php">Vijesti</a></h6>
                    <h6><span class="page-active"><?php echo $title; ?></span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-12">
                        <div class="blog-post-container">
                            <div class="blog-post-image">
                                <?php echo '<img src="./images/zanimanja/'.$image.'" alt="'.$image.'" />'; ?>
                            </div> <!-- /.blog-post-image -->
                            <div class="blog-post-inner">
                                <h3 class="blog-post-title">    <?php echo $title; ?>   </h3>
                                <?php echo $content; ?>
                                
                            </div>
                        </div> <!-- /.blog-post-container -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4 page-widgets">

                 <?php 
                include 'widgets/obavijesti_widget.php'; 
                include 'widgets/dogadjaji_widget.php'; 
                include 'widgets/oznake_widget.php';
                include 'widgets/galerija_widget.php';
                ?>

            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

    <!-- begin The Footer -->
   <?php include 'view/footer.php' ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
</body>
</html>