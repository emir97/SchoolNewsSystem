<?php include 'core/connection.php'; ?>
<?php $connectToDb = connectOnDb(); ?>
<?php
	if(isset($_GET['ID'])){
		$IDOfEvent = htmlentities(strip_tags($_GET['ID']));
		$titileOfSelectedEvenet = htmlentities(strip_tags($_GET['title']));
		$fetchEvent = $connectToDb->prepare("SELECT * FROM events WHERE event_id = :id AND event_title = :title LIMIT 1");
        $fetchEvent->bindParam(":id", $IDOfEvent);
        $fetchEvent->bindParam(":title", $titileOfSelectedEvenet);
        $fetchEvent->execute();
        if($fetchEvent->rowCount() == 1){
            foreach ($fetchEvent as $key => $value) {
                #ID dogadjaja
                $eventID = $value['event_id'];
                # naslov dogadjaja 
                $eventTitle = $value['event_title'];
                # sadrzaj dogadjaja
                $eventContent = $value['event_content'];
                # slika dogadjaja
                $eventImage = $value['event_image'];
                # mjesto odrzavanja dogadjaja
                $eventPlace = $value['event_place'];
                # vrijeme odrzavanja dogadjaja
                # pocetak dogadjaja
                $eventStart = new DateTime($value['event_start_date']);
                $eventStartDate = $eventStart->format("d. m. o.");
                $eventStartTime = $eventStart->format("H:i");

                # zavrsetak dogadjaja
                $eventEnd = new DateTime($value['event_end_date']);
                $eventEndDate = $endDateString = $eventEnd->format("d.m.o.");
                if(strtotime($eventEndDate) == 0){
                    $eventEndDate = "";
                } else {
                    $eventEndDate = "Datum završetka: ".$eventEndDate;
                }
                $eventEndTime = $eventEnd->format('H:i');
                if($eventEndTime == "00:00"){
                    $eventEndTime = "";
                } else {
                    $eventEndTime = "Vrijeme završetka: ".$eventEndTime;
                }
                #author dogadjaja
                $authorID = $value['author_id'];
                $fetchAuthor = $connectToDb->query("SELECT  * FROM authors WHERE author_id = '$authorID' LIMIT 1");
                if($fetchAuthor->rowCount() == 1){
                    while ($r=$fetchAuthor->fetch(PDO::FETCH_OBJ)) {
                        $authorName = $r->author_name ." " . $r->author_surname;
                        $authorImage = $r->author_image;
                        $authorCV = $r->author_cv;
                    }
                } else {

                }
                # prikaz dogadjaja ispod naslova
                $eventStartDayString = $eventStart->format('N');
                $eventStartDayInt =  $eventStart->format('d');
                switch ($eventStartDayString) {
                    case 1: $eventStartDayString = "Ponedjeljak"; break;
                    case 2: $eventStartDayString = "Utorak"; break;
                    case 3: $eventStartDayString = "Srijeda"; break;
                    case 4: $eventStartDayString = "Četvrtak"; break;
                    case 5: $eventStartDayString = "Petak"; break;
                    case 6: $eventStartDayString = "Subota"; break;
                    case 7: $eventStartDayString = "Nedjelja"; break;
                    default: break;
                }
                $eventStartMontString = $eventStart->format('m');
                switch($eventStartMontString){
                    case "01":  $eventStartMontString = "Januar"; break;
                    case "02":  $eventStartMontString = "Februar"; break;
                    case "03":  $eventStartMontString = "Mart"; break;
                    case "04":  $eventStartMontString = "April"; break;
                    case "05":  $eventStartMontString = "Maj"; break;
                    case "06":  $eventStartMontString = "Juni"; break;
                    case "07":  $eventStartMontString = "Juli"; break;
                    case "08":  $eventStartMontString = "August"; break;
                    case "09":  $eventStartMontString = "Septembar"; break;
                    case "10":  $eventStartMontString = "Oktobar"; break;
                    case "11":  $eventStartMontString = "Novembar"; break;
                    case "12":  $eventStartMontString = "Decembar"; break;
                }
                $eventStartYear = $eventStart->format('o');
                $dateToDisplay = $eventStartDayString . " ". $eventStartDayInt ." ". $eventStartMontString ." ". $eventStartYear . " ".$eventStartTime;

                if(strtotime($endDateString) != 0){
                    $eventEndDayString = $eventEnd->format('N');
                    $eventEndDayInt =  $eventEnd->format('d');
                    switch ($eventEndDayString) {
                        case 1: $eventEndDayString = "Ponedjeljak"; break;
                        case 2: $eventEndDayString = "Utorak"; break;
                        case 3: $eventEndDayString = "Srijeda"; break;
                        case 4: $eventEndDayString = "Četvrtak"; break;
                        case 5: $eventEndDayString = "Petak"; break;
                        case 6: $eventEndDayString = "Subota"; break;
                        case 7: $eventEndDayString = "Nedjelja"; break;
                    default: break;
                    }
                    $eventEndMontString = $eventEnd->format('m');
                    switch($eventEndMontString){
                        case "01":  $eventEndMontString = "Januar"; break;
                        case "02":  $eventEndMontString = "Februar"; break;
                        case "03":  $eventEndMontString = "Mart"; break;
                        case "04":  $eventEndMontString = "April"; break;
                        case "05":  $eventEndMontString = "Maj"; break;
                        case "06":  $eventEndMontString = "Juni"; break;
                        case "07":  $eventEndMontString = "Juli"; break;
                        case "08":  $eventEndMontString = "August"; break;
                        case "09":  $eventEndMontString = "Septembar"; break;
                        case "10":  $eventEndMontString = "Oktobar"; break;
                        case "11":  $eventEndMontString = "Novembar"; break;
                        case "12":  $eventEndMontString = "Decembar"; break;
                    }
                    $eventStartYear = $eventEnd->format('o');
                    $dateToDisplay = $dateToDisplay. " - ".$eventEndDayString. " " .$eventEndDayInt. " ".$eventEndMontString." ".$eventStartYear;
                } 

                $whatcomment = "event." .$eventTitle.".". $eventID;
                $fetchComments = $connectToDb->query("SELECT * FROM comments WHERE event_id = '$eventID'");
                $numberOfComments = $fetchComments->rowCount();
                if($numberOfComments == 1){
                    $numberOfComments = $numberOfComments . " Komentar";
                } else {
                    $numberOfComments = $numberOfComments. " Komentara";
                }


            }
        } else {

        }
	} else {

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
    <title>ETS - <?php echo $eventTitle; ?></title>
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
<script>
window.addEventListener("load", function(){
  var load_screen = document.getElementById("loader");
  document.body.removeChild(load_screen);
});
</script>
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
    <!-- This one in here is responsive menu for tablet and mobiles -->
    
   <?php include('view/header_mobile.php'); ?>
   
   <?php include('view/header.php'); ?>
   
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><a href="./dogadjaji.php">Događaji</a></h6>
                    <h6><span class="page-active"><?php echo $eventTitle; ?></span></h6>
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
                        <div class="event-container clearfix">
                            <div class="left-event-content">
                                <img src="./images/events/<?php echo $eventImage; ?>" alt="" />
                                <div class="event-contact">
                                    <h4>Detalji</h4>
                                    <ul>
                                        <li>Mjesto održavanja: <?php echo $eventPlace; ?></li>
                                        <li>Datum početka: <?php echo $eventStartDate; ?></li>
                                        <li>Vrijeme početka: <?php echo $eventStartTime; ?></li>
                                        <li><?php echo $eventEndDate; ?></li>
                                        <li><?php echo $eventEndTime; ?></li>
                                        <li>Autor događaja: <?php echo $authorName; ?></li>
                                    </ul>
                                </div>
                            </div> <!-- /.left-event-content -->
                            <div class="right-event-content">
                                <h2 class="event-title"><?php echo $eventTitle; ?></h2> 
                                <span class="event-time event-single-time"><?php echo $dateToDisplay; ?></span>
                                <p><?php echo $eventContent; ?></p>
                                
                                
                            </div> <!-- /.right-event-content -->
                        </div> <!-- /.event-container -->
                    </div>
                </div> <!-- /.row -->
                <div class="row">
                    <div class="col-md-12">
                        <div id="blog-author" class="clearfix">
                            <div class="blog-author-img">
                                <img src="./images/prof/<?php echo $authorImage; ?>" alt="" class="blog-author-img"/>
                            </div>
                            <div class="blog-author-info">
                                <h4 class="author-name"><a href="#">
                                    <?php echo $authorName; ?>
                                </a></h4>
                                <p> <?php echo $authorCV; ?></p>
                            </div>
                        </div> <!-- /.blog-author -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->
                <?php 
                                $fetchComments = $connectToDb->query("SELECT * FROM comments WHERE event_id = '$eventID'");
                                if($fetchComments->rowCount() > 0){

                                    echo '
                                         <div class="row">
                                        <div class="col-md-12">
                                            <div id="blog-comments" class="blog-post-comments">
                                                <div class="widget-main-title">
                                                    <h4 class="widget-title">'. $numberOfComments.'</h4>
                                                </div>
                                                <div class="blog-comments-content">
                                          ';

                                    while ($r = $fetchComments->fetch(PDO::FETCH_OBJ)) {
                                        $userFirstname = $r->user_firstname;
                                        $userLastname = $r->user_lastname;
                                        $userPhoto = $r->user_photo;
                                        $userComment = $r->user_comment;
                                        $commentDate = new DateTime($r->user_date_post);
                                        $date = $commentDate->format('d.m.o.');
                                        $day = $commentDate->format('N');
                                        if($day == 1){
                                            $day = "Ponedjeljak";
                                        } else if($day == 2) {
                                            $day = "Utorak";
                                        } else if ($day == 3){
                                            $day = "Srijeda";
                                        } else if($day == 4) {
                                            $day = "Četvrtak";
                                        } else if($day == 5) {
                                            $day = "Petak";
                                        } else if($day == 6) {
                                            $day = "Subota";
                                        } else if($day == 7) {
                                            $day = "Nedjelja";
                                        }

                                        $time = new DateTime($r->user_time_post);
                                        $time = $time->format("G:i");
                                        echo '
                                                <div class="media">
                                                    <div class="pull-left" href="#">
                                                        <img class="media-object" src="./images/'.$userPhoto.'" alt="" />
                                                    </div>
                                                    <div class="media-body">
                                                        <h4 class="media-heading">'.$userFirstname ." ". $userLastname.'</h4>
                                                        <p> '.$userComment.'</p><span class="small-text">'.$day." ".$date."&nbsp;&nbsp; " .$time.'</span>
                                                        
                                                    </div>
                                                </div>
                                            ';
                                    }
                                    echo '
                                           </div> <!-- /.blog-comments-content -->
                                            </div> <!-- /.blog-post-comments -->
                                        </div> <!-- /.col-md-12 -->
                                    </div> <!-- /.row -->
                                        ';
                                }
                                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget-main comment-form">
                            <div class="widget-main-title">
                                <h4 class="widget-title">Ostavi komentar</h4>
                            </div> <!-- /.widget-main-title -->
                            <form action="commenting.php" method="post">
                            <input type="hidden" name="whatcomment" value="<?php echo $whatcomment; ?>"/>
                            <div class="widget-inner">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>
                                            <label for="name-id">Ime:</label>
                                            <input type="text" id="name-id" name="firstname-id" />
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>
                                            <label for="email-id">Prezime:</label>
                                            <input type="text" id="name-id" name="lastemail-id" />
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>
                                            <label for="site-id">Email:</label>
                                            <input type="email" id="email-id" name="email-id" />
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <label for="comment">Komentar:</label>
                                            <textarea name="comment" id="comment" rows="4"></textarea>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                         <div class="g-recaptcha" data-sitekey="6LeidRgTAAAAADV4GUqKKLzPofhGMzDiHsdhsF-I"></div>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="mainBtn" type="submit" name="" value="Postavi komentar" />
                                    </div>
                                </div>
                            </div> <!-- /.widget-inner -->
                        </form>
                        </div> <!-- /.widget-main -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4">
				 <?php include 'widgets/obavijesti_widget.php'; ?>
                 <?php include 'widgets/dogadjaji_widget.php'; ?>
                 <?php include 'widgets/galerija_widget.php'; ?>
            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

    <!-- begin The Footer -->
	<?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        function initialize() {
          var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(42.888543, 20.879661)
          };

          var map = new google.maps.Map(document.getElementById('map-canvas'),
              mapOptions);
        }

        function loadScript() {
          var script = document.createElement('script');
          script.type = 'text/javascript';
          script.src = 'https://maps.googleapis.com/maps/api/js_70075098.js' +
              'callback=initialize';
          document.body.appendChild(script);
        }

        window.onload = loadScript;
    </script>

</body>
</html>