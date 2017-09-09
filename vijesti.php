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
    <title>ETS - Vijesti</title>
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
                    <h6><span class="page-active">Vijesti</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8" id="newsRow">
                 <div class="row news_content" ng-controller="news">
                    <?php
                        $connectToDb = connectOnDb();?>
                        
                            <div class="col-md-6" ng-repeat="stuff in news">

                                <news-card info="stuff"></news-card>
                                
                            </div><!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
               <div class="showbox loader">
                            <div class="loader">
                                <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                                </svg>
                            </div>
               </div>
                

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
    <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    <script>
    var y = new Date();
    var year = y.getFullYear();
    document.getElementById("year").innerHTML = year;
    </script>
     <script>
        var load = 4;
        $('.loader').hide();
        function yHandler(){
        
            
            var wrap = document.getElementById('newsRow');
            var contentHeight = wrap.offsetHeight + 450;
            var yOffset = window.pageYOffset; 
            var y = yOffset + window.innerHeight;
            if(y >= contentHeight){
                $('.loader').show();
                load = load + 2;
                $.post("ajax_load.php",{load:load}, function(data){
                    $('.news_content').append(data);
                });
                console.log(load);
                $('.loader').hide();
            }
        }
window.onscroll = yHandler;
</script>


</body>
</html>