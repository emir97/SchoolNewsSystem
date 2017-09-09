<?php include 'core/connection.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <title>ETS - Sekcije</title>
    <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>
 <script src="controllers/sekcije_controller.js"></script>
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
                    <h6><span class="page-active">Sekcije</span></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8">
                 <div class="row" ng-controller="sekcije">
                    <?php
                        $connectToDb = connectOnDb();
                        ?>
                          <div class="col-md-6" ng-repeat="sekcija in sekcije">
                        <div class="blog-grid-item">
                            <div class="blog-grid-thumb zanimanja-header">
                                <a ng-href="{{sekcija.URL}}">
                                    <img ng-src="images/zanimanja/{{sekcija.Image}}" alt="{{sekcija.Image}}" />
                                </a>
                            </div>
                            <div class="box-content-inner zanimanja-content">
                                <h4 class="blog-grid-title"><a ng-href="{{sekcija.URL}}l">{{sekcija.Title}}</a></h4>
                                
                            </div> <!-- /.box-content-inner -->
                        </div> <!-- /.blog-grid-item -->
                    </div> <!-- /.col-md-6 -->  
                            
                </div>
                <!-- /.row -->
            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4 page-widgets">
                <?php 
                    include 'widgets/obavijesti_widget.php'; 
                    include 'widgets/oznake_widget.php';
                    include 'widgets/galerija_widget.php';
                ?>
            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

    <!-- begin The Footer -->
    <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    </body>
</html>