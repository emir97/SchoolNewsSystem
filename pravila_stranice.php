
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Pravila stranice</title>
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
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Pravila stranice</span></h6>
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
                                <h3 class="course-post-title">Pravia stranice</h3>
                                <p>Pravila objavljivanja na stranici: <br>
                                Na stranici je zabranjeno objavljivanje: </p>
                                <ul>
                                    <li>Bilo kakava politička propaganda. Svaka će biti uklonjena.</li>
                                    <li>Vulgarne teme</li>
                                    <li>Objavljivanje neprimjerenog sadržaja</li>
                                    <li>Koristiti stranicu za svoja oglašavanja</li>
                                </ul>
                                <p>Pravila ponašanja na stranici: <br>
                                Na stranici je zabranjeno: </p>
                                <ul>
                                    <li>Bilo kakava politička propaganda objavljivati u komentare. Svaka će biti uklonjena.</li>
                                    <li>Vulgarnost u komentarina</li>
                                </ul>
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