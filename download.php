<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Download</title>
     <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>
    <script>
    app.controller("download", function($http, $scope){
        $http.get("response/fetch_download.php").success(function(response){
            $scope.downloads = response.downloads;
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
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Download</span></h6>
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
                        <div class="widget-main">
                            <div class="widget-inner">
                              
                                <h3 class="archive-title">Skirpte</h3>
                                <ul class="archive-list" ng-controller="download">
                                    <li ng-repeat="download in downloads">
                                        <a ng-href="files/{{download.url}}" download>
                                            {{download.title}}
                                        </a>
                                    </li>
                                </ul>
                            </div> <!-- /.widget-inner -->
                        </div> <!-- /.widget-main -->
                    </div> <!-- /.col-md-12 -->

                </div> <!-- /.row -->
            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4">
                 <?php 
                    include 'widgets/pretraga_widget.php'; 
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