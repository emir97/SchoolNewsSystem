<?php
    if (isset($_GET['key'])) {
        $keyURL = '"'."response/keywords_click.php?key=".$_GET['key'].'"';
    } else if(isset($_GET['search_query'])) {
         $keyURL = '"'."response/keywords_click.php?search_query=".$_GET['search_query'].'"';
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
    <title>ETS - Vijesti</title>
    <?php 
    include 'includes/SEO.php';
    include 'includes/css_js.php';
     ?>
    <script>
    app.controller('newsKey', function($http, $scope) {
    $http.get(<?php echo $keyURL; ?>).success(function(response) {
        $scope.news = response.news;
        $scope.page = response.page;
    });
});
    </script>
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
    <div class="container" ng-controller="newsKey">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">{{page}}</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8" id="newsRow">
                 <div class="row news_content" ng-controller="newsKey">
                    
                        
                            <div class="col-md-6" ng-repeat="stuff in news">

                                <news-card info="stuff"></news-card>
                                
                            </div><!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
               
            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4 page-widgets">
                <?php 
                    include 'widgets/dogadjaji_widget.php'; 
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