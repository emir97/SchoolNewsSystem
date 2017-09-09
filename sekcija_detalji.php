<?php
include 'core/connection.php'; 
if(isset($_GET['ID']) && isset($_GET['title'])){
    
    //dohavatanje vijesti
    $ID = $_GET['ID'];
    $title = $_GET['title'];

    $connectToDb = connectOnDb();

    $fetchNews = $connectToDb->prepare("SELECT * FROM sekcije WHERE sekcija_id = :ID AND sekcija_title = :title LIMIT 1");
    $fetchNews->bindParam(":ID", $ID);
    $fetchNews->bindParam(":title", $title);
    $fetchNews->execute();

    if($fetchNews->rowCount() == 1){
        foreach ($fetchNews as $row) {

            //naslov vijesti
            $title = $row['sekcija_title'];

            //sadrzaj vijesti 
            $content = $row['sekcija_content'];

            //slika vijesti 
            $image = $row['sekcija_image'];

            $authorID = $row['author_id'];
            $fetchAuthor = $connectToDb->prepare("SELECT author_name, author_surname, author_cv, author_image FROM authors WHERE author_id = :id LIMIT 1");
            $fetchAuthor->bindParam(":id", $authorID);
            $fetchAuthor->execute();
            if($fetchAuthor->rowCount() == 1) {
                foreach ($fetchAuthor as $key => $value) {
                    $authorFirstName = $value['author_name'];

                    $authorLastName = $value['author_surname'];

                    $authorProfile = $value['author_image'];

                    $authorCV = $value['author_cv'];
                }
            }
        }
        
    } else {
        header('location: 404.php');
    }
} else {
    header('location: 404.php');
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
                    <h6><a href="./sekcije.php">Sekcije</a></h6>
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
                                <?php echo '<img src="./images/sekcije/'.$image.'" alt="'.$image.'" />'; ?>
                            </div> <!-- /.blog-post-image -->
                            <div class="blog-post-inner">
                                <h3 class="blog-post-title">    <?php echo $title; ?>   </h3>
                                <?php echo $content; ?>
                                
                            </div>
                        </div> <!-- /.blog-post-container -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->
            <div class="row">
                    <div class="col-md-12">
                        <div id="blog-author" class="clearfix">
                            <div class="blog-author-img">
                                <img src="./images/prof/<?php echo $authorProfile; ?>" alt="" class="blog-author-img"/>
                            </div>
                            <div class="blog-author-info">
                                <h4 class="author-name"><a href="#">
                                    <?php echo $authorFirstName . " ".$authorLastName; ?>
                                </a></h4>
                                <p> <?php echo $authorCV; ?>    </p>
                            </div>
                        </div> <!-- /.blog-author -->
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