<?php include 'core/connection.php'; ?>
<?php 
    if(isset($_GET['trajanje'])) {
        if ($_GET['trajanje'] == "4 Godine") {
            $script = "<script src='controllers/zanimanja_controller4.js'></script>";
        } else if($_GET['trajanje'] == "3 Godine") {
            $script = "<script src='controllers/zanimanja_controller3.js'></script>";
        } else {
            header('location: 404.php');
            exit(); 
        }
    } else {
        header('location: 404.php');
        exit();
    }
?>
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
    <title>ETS - Zanimanja</title>
    <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>
    <script src="js/angular.min.js"></script>
    <script src="js/angular-route.min.js"></script>
    <script src="js/angular-sanitize.min.js"></script>
    <script>
		app = angular.module('ETS', ['ngSanitize']);
	</script>
    <script src="controllers/oznake_controller.js"></script>
    <?php echo $script; ?>
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
    <div class="container" ng-controller="zanimanja">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Zanimanja</span></h6>
                    <h6><span class="page-active">{{trajanje}}</span></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8">
                 <div class="row" ng-controller="zanimanja">
                    <?php
                        $connectToDb = connectOnDb();
                        ?>
                          <div class="col-md-6" ng-repeat="smjer in smjerovi">
                        <div class="blog-grid-item">
                            <div class="blog-grid-thumb zanimanja-header">
                                <a ng-href="{{smjer.URL}}">
                                    <img ng-src="images/zanimanja/{{smjer.Image}}" alt="{{smjer.Image}}" />
                                </a>
                            </div>
                            <div class="box-content-inner zanimanja-content">
                                <h4 class="blog-grid-title"><a ng-href="{{smjer.URL}}l">{{smjer.Title}}</a></h4>
                                
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