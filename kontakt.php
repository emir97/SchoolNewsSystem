<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Kontakt</title>
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
<body>
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
                    <h6><span class="page-active">Kontakt</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="col-md-5">
                <div class="contact-map">
                    <div class="google-map-canvas" id="map-canvas" style="height: 542px;">
                       
                    </div>
                </div>
            </div> <!-- /.col-md-5 -->
            
            <div class="col-md-7">
                <div class="contact-page-content">
                    <div class="contact-heading">
                        <h3>Kontaktirajte nas</h3>
                        <p>Ako imate bilo kakava pitanja ili želite nešto podijeliti sa nama slobodno nam se obratite putem ove kontakt forme.</p>
                    </div>
                    <form action="send_email.php" method="post">
                    <div class="contact-form clearfix">
                        <p class="full-row">
                            <span class="contact-label">
                                <label for="name-id">Ime:</label>
                                <span class="small-text">Unesite svoje ime </span>
                            </span>
                            <input required type="text" id="name-id" name="name-id" />
                        </p>
                        <p class="full-row"> 
                            <span class="contact-label">
                                <label for="surname-id">Prezime:</label>
                                <span class="small-text">Unesite svoje prezime</span>
                            </span>
                            <input required type="text" id="surname-id" name="surname-id" />
                        </p>
                        <p class="full-row">
                            <span class="contact-label">
                                <label for="email-id">E-mail:</label>
                                <span class="small-text">Unesite svoju e-mail adresu</span>
                            </span>
                            <input required type="email" id="email-id" name="email-id" />
                        </p>
                        <p class="full-row">
                            <span class="contact-label">
                                <label for="message">Poruka:</label>
                                <span class="small-text">Unesite svoju poruku za nas</span>
                            </span>
                            <textarea required name="message" id="message" rows="6"></textarea>
                        </p>
                        <p class="full-row">
                            <input class="mainBtn" type="submit" name="" value="Pošalji poruku" />
                        </p>
                    </div>
                </form>
                </div>
            </div> <!-- /.col-md-7 -->

        </div> <!-- /.row -->
    </div> <!-- /.container -->

    <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    

</body>
</html>