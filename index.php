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
    <title>ETS - Naslovnica</title>
    <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>
		   
        
        <script>

app.controller('lastNews', function($scope, $http) {
   $http.get('response/parts_on_index.php').success(function(response){
     $scope.lastNews = response.news;
   });
});
app.controller('slider', function($scope, $http) {
   $http.get('response/parts_on_index.php').success(function(response){
     $scope.slider = response.slider;
   });
});
app.controller('anketa', function($scope, $http) {
   $http.get('response/fetch_poll.php').success(function(response){
     $scope.question = response.Question;
     $scope.answers = response.Answers;
     $scope.ID = response.ID;
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

    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8">
                <div class="main-slideshow">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" ng-controller="slider">
                             
                            <?php 
                                   $connectToDb = connectOnDb();
                                  ?>
                                            <div ng-repeat="slide in slider" class="{{slide.class}}">
                                                <a ng-href="{{slide.URL}}"><img ng-src="images/slider/{{slide.Image}}" alt="{{slide.Image}}"></a>
                                                <a ng-href="{{slide.URL}}"><div class="carousel-caption">
                                                    <h2>{{slide.Title}}</h2>
                                                </div></a>
                                            </div>
                                        
                              
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"> <img src="images/arrow_right.png"/> </span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"> <img src="images/left_arrow.png"/> </span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!-- /.main-slideshow -->
                <div class="row" ng-controller="lastNews">
                  
                            <div class="col-md-6" ng-repeat="news in lastNews">
                                <news-card info="news"></news-card>
                            </div><!-- /.col-md-6 -->
                </div>
                <!-- /.row -->






            </div>
            <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4">

        <?php include 'widgets/obavijesti_widget.php'; ?>
        <?php include 'widgets/vijesti_widget.php'; ?>
        <?php include 'widgets/dogadjaji_widget.php'; ?>       
        <?php include 'widgets/galerija_widget.php'; ?>
        <?php include 'widgets/poll_widget.php'; ?>
            </div>
        </div>
    </div>

   <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/bootstrap/bootstrap.js"></script>
        <script>
        $('.carousel').carousel({
       interval: 7000
    });
    </script>
</body>

</html>