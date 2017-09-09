<?php
    include 'core/connection.php'; 
    if(isset($_GET['page'])) {
        if($_GET['page'] == "sta upisati"){
            $connect = connectOnDb();
            $fetchData = $connect->query("SELECT * FROM upis WHERE link_title = 'staupisati' LIMIT 1");
            if($fetchData->rowCount() == 1){
                while ($r = $fetchData->fetch(PDO::FETCH_OBJ)) {
                    
                    $title = $r->upis_title;
                    $content = $r->upis_content;
                    $browserTitle = $r->upis_title;
                }
            } else {
                header("location: 404.php");
                exit();
            }
            $connect = NULL;
        } else if($_GET['page'] == "postupak i bodovanje pri upisu"){
            $connect = connectOnDb();
            $fetchData = $connect->query("SELECT * FROM upis WHERE link_title = 'postupakIBodovanjePriUpisu' LIMIT 1");
            if($fetchData->rowCount() == 1){
                while ($r = $fetchData->fetch(PDO::FETCH_OBJ)) {
                    
                    $title = $r->upis_title;
                    $content = $r->upis_content;
                    $browserTitle = $r->upis_title;
                }
            }
            $connect = NULL;
        } else if($_GET['page'] == "vodic za ucenike") {
            $connect = connectOnDb();
            $fetchData = $connect->query("SELECT * FROM upis WHERE link_title = 'vodicZaStudente' LIMIT 1");
            if($fetchData->rowCount() == 1){
                while ($r = $fetchData->fetch(PDO::FETCH_OBJ)) {
                    $content = $r->upis_data;
                    header("location: vodic/$content");
                    $connect = NULL;
                    exit();
                }
            }
            $connect = NULL;
        } else {
             header("location: 404.php");
             exit();
        }
    } else {
        header("location: 404.php");
        exit();
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
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Upis</span></h6>
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