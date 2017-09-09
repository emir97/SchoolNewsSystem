
<?php
    include 'core/connection.php'; 
    	$linkTitle = "kutakzaroditelje";
    	//konekcija na bazu
        $connect = connectOnDb();
        $fetchData = $connect->prepare("SELECT * FROM podaci_o_skoli WHERE o_skoli_link_title = :link LIMIT 1");
        $fetchData->bindParam(":link", $linkTitle);
        $fetchData->execute();
        if($fetchData->rowCount() == 1){
            while ($r = $fetchData->fetch(PDO::FETCH_OBJ)) {
                
                $title = $r->o_skoli_title;
                $content = $r->o_skoli_content;
                $browserTitle = $r->o_skoli_title;
            }
        } 
        $connect = NULL;


?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - <?php echo $browserTitle; ?></title>
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
    <?php 
         include('view/header_mobile.php');
         include('view/header.php'); 
    ?>
    
    
    
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.phphtml">Naslovnica</a></h6>
                    <h6><span class="page-active">ETS Mostar</span></h6>
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
                        
                        <div class="course-post">
                            <div class="course-details clearfix">
                                <h3 class="course-post-title"><?php echo $title; ?></h3>
                                <p><?php echo $content; ?></p>
                            </div> <!-- /.course-details -->
                        </div> <!-- /.course-post -->

                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

            </div> <!-- /.col-md-8 -->


            <!-- Here begin Sidebar -->
            <div class="col-md-4">

               <?php 
                    include 'widgets/dogadjaji_widget.php'; 
                    include 'widgets/oznake_widget.php';
                    include 'widgets/galerija_widget.php';
                ?>

            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

   <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>

</body>
</html>