<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
?>
<?php 
if(isset($_GET[base64_url_encode("deleteThisComment")]) && isset($_GET[base64_url_encode("IDOfComment")])){

    if(base64_url_decode($_GET[base64_url_encode("deleteThisComment")]) == "true"){
        $IDOfComment = base64_url_decode($_GET[base64_url_encode("IDOfComment")]);
        $IDOfNews = base64_url_decode($_GET[base64_url_encode("newsID")]);

        $dbConnect = connectToDb();

        if($IDOfNews != 0) {
            $deleteComCounter = $dbConnect->query("SELECT num_comment FROM news WHERE news_id = '$IDOfNews'");
            foreach ($deleteComCounter as $key => $value) {
                $numOfComm = $value['num_comment'];
            }
            $numOfComm = $numOfComm - 1;
            $updateNumOfComm = $dbConnect->query("UPDATE news SET num_comment = '$numOfComm' WHERE news_id = '$IDOfNews'");
        }
        $deleteComment = $dbConnect->prepare("DELETE FROM comments WHERE comment_id = :id");
        $deleteComment->bindParam(":id", $IDOfComment);
        $result = $deleteComment->execute();
        if($result){
            $dbConnect = NULL;
            header('location: komentari.php');
            exit();
        } else {
            $dbConnect = NULL;
            header('location: 500.php');
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Komentari</title>
   

    <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>
</head>
<body class="infobar-offcanvas">

    <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>

       <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">
                                <h1>Komentari</h1>
                                <div class="options">

                                </div>
                            </div>
                            <div class="row">
                            <?php
                            $dbConnection = connectToDb();
                            $fetchCommentsNuberOfComments = $dbConnection->query("SELECT * FROM comments");
                            if($fetchCommentsNuberOfComments->rowCount() > 0) {
                            $numberOfComments = $fetchCommentsNuberOfComments->rowCount();
                            $halfOfComments = $numberOfComments / 2;
                            $halfOfComments = ceil($halfOfComments);
                            $i = 1;

                            $fetchComments = $dbConnection->query("SELECT * FROM comments LIMIT 0, $halfOfComments");
                            if($fetchComments->rowCount() > 0){
                                echo "<div class='col-md-6'>";
                            	while ($r = $fetchComments->fetch(PDO::FETCH_OBJ)) {

                                    $commentID = $r->comment_id;
                                    
                                    //ime i prezime autora komentara
                            		$userFirstName = $r->user_firstname;
                            		$userLastName = $r->user_lastname;

                                    //email adresa autora komentara
                            		$userEmail = $r->user_email;

                                    //sarzaj komentara
                            		$userComment = $r->user_comment;

                                    //ip adresa
                            		$userIPAddress = $r->user_ipaddress;

                                    //datume 
                            		$commentDate = new DateTime($r->user_date_post);
                                    $commnetDate = $commentDate->format('d.m.o');

                                    //vrijeme
                            		$commentTime = new DateTime($r->user_time_post);
                                    $commentTime = $commentTime->format('G:i');

                            		$newsID = $r->news_id;
                                    if($newsID != 0) {
                                         $fetchNews = $dbConnection->query("SELECT news_title FROM news WHERE news_id = '$newsID'");
                                         while ($news = $fetchNews->fetch(PDO::FETCH_OBJ)) {
                                            $newsTitle = $news->news_title;
                                            $urlForViewComment = "../vijest_detalji.php?ID=".$newsID."&title=".$newsTitle;
                                         }
                                    }
                                    $eventID = $r->event_id;
                                    if($eventID != 0) {
                                        $fetchNews = $dbConnection->query("SELECT event_title FROM events WHERE event_id = '$eventID'");
                                         while ($news = $fetchNews->fetch(PDO::FETCH_OBJ)) {
                                            $eventTitle = $news->event_title;
                                            $urlForViewComment = "../dogadjaji_detalji.php?ID=".$eventID."&title=".$eventTitle;
                                         }
                                    }
                            		$sliderID = $r->slider_id;
                            		$userCountry = $r->user_location_country;
                            		$userRegion = $r->user_location_region;
                            		$userProvider = $r->user_isp_provider;

                                    $urlDeleteComment = "komentari.php?".base64_url_encode("deleteThisComment")."=".base64_url_encode("no")."&".base64_url_encode("IDOfComment") ."=".base64_url_encode($commentID) ."&".base64_url_encode("newsID")."=".base64_url_encode($newsID);

                            		echo '
                                            <div class="panel panel-midnightblue">
                                                        <div class="panel-heading">
                                                            <h2><i class="fa fa-comment"></i> Komentar '.$i.'</h2>
                                                            <div class="panel-ctrls">
                                                                <a href="'.$urlDeleteComment.'" class="button-icon" title="Obriši komentar"><i class="fa fa-trash-o"></i></a>
                                                                <a href="'.$urlForViewComment.'" class="button-icon" title="Pogledaj komentar"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body" >
                                                            <p>Datum i vrijeme komentara:<b> '.$commnetDate ."&nbsp;&nbsp;&nbsp;".$commentTime.'</b></p>
                                                            <p>Ime i Prezime osobe:<b> '.$userFirstName. "&nbsp;&nbsp;&nbsp;".$userLastName .'</b></p>
                                                            <p>Email osobe:<b> '.$userEmail .'</b></p>
                                                            <p>IP Adresa osobe:<b> '.$userIPAddress .'</b></p>
                                                            <p>Država i region osobe:<b> '.$userCountry ."&nbsp;&nbsp;&nbsp;".$userRegion.'</b></p>
                                                            <p>Provajder telekomunikacionih usluga osobe:<b> '.$userProvider .'</b></p>
                                                            <p>Sadržaj komentara:<b> '.$userComment .'</b></p>
                                                            
                                                        </div>
                                            </div>
                            			';

                                        $i = $i + 2;

                            	}
                                echo "</div>";
                            }

                            $i = 2;
                            $fetchComments = $dbConnection->query("SELECT * FROM comments LIMIT $halfOfComments, $numberOfComments");
                            if($fetchComments->rowCount() > 0){
                                echo "<div class='col-md-6'>";
                                while ($r = $fetchComments->fetch(PDO::FETCH_OBJ)) {

                                    $commentID = $r->comment_id;
                                    
                                    //ime i prezime autora komentara
                                    $userFirstName = $r->user_firstname;
                                    $userLastName = $r->user_lastname;

                                    //email adresa autora komentara
                                    $userEmail = $r->user_email;

                                    //sarzaj komentara
                                    $userComment = $r->user_comment;

                                    //ip adresa
                                    $userIPAddress = $r->user_ipaddress;

                                    //datume 
                                    $commentDate = new DateTime($r->user_date_post);
                                    $commnetDate = $commentDate->format('d.m.o');

                                    //vrijeme
                                    $commentTime = new DateTime($r->user_time_post);
                                    $commentTime = $commentTime->format('G:i');

                                    $newsID = $r->news_id;
                                    $eventID = $r->event_id;
                                    if($newsID != 0) { 
                                        $fetchNews = $dbConnection->query("SELECT news_title FROM news WHERE news_id = '$newsID'");
                                        while ($news = $fetchNews->fetch(PDO::FETCH_OBJ)) {
                                            $newsTitle = $news->news_title;
                                            $urlForViewComment = "../vijest_detalji.php?ID=".$newsID."&title=".$newsTitle;
                                        }
                                    } else if($eventID != 0){
                                        $fetchNews = $dbConnection->query("SELECT event_title FROM events WHERE event_id = '$eventID'");
                                        while ($news = $fetchNews->fetch(PDO::FETCH_OBJ)) {
                                            $eventTitle = $news->event_title;
                                            $urlForViewComment = "../vijest_detalji.php?ID=".$eventID."&title=".$eventTitle;
                                        }
                                    }
                                    $sliderID = $r->slider_id;
                                    $userCountry = $r->user_location_country;
                                    $userRegion = $r->user_location_region;
                                    $userProvider = $r->user_isp_provider;

                                    $urlDeleteComment = "komentari.php?".base64_url_encode("deleteThisComment")."=".base64_url_encode("no")."&".base64_url_encode("IDOfComment") ."=".base64_url_encode($commentID)."&".base64_url_encode("newsID")."=".base64_url_encode($newsID);;

                                    echo '
                                            <div class="panel panel-midnightblue">
                                                        <div class="panel-heading">
                                                            <h2><i class="fa fa-comment"></i> Komentar '.$i.'</h2>
                                                            <div class="panel-ctrls">
                                                                <a href="'.$urlDeleteComment.'" class="button-icon" title="Obriši komentar"><i class="fa fa-trash-o"></i></a>
                                                                <a href="'.$urlForViewComment.'" class="button-icon" title="Pogledaj komentar"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body" >
                                                        <p>Datum i vrijeme komentara:<b> '.$commnetDate ."&nbsp;&nbsp;&nbsp;".$commentTime.'</b></p>
                                                            <p>Ime i Prezime osobe:<b> '.$userFirstName. "&nbsp;&nbsp;&nbsp;".$userLastName .'</b></p>
                                                            <p>Email osobe:<b> '.$userEmail .'</b></p>
                                                            <p>IP Adresa osobe:<b> '.$userIPAddress .'</b></p>
                                                            <p>Država i region osobe:<b> '.$userCountry ."&nbsp;&nbsp;&nbsp;".$userRegion.'</b></p>
                                                            <p>Provajder telekomunikacionih usluga osobe:<b> '.$userProvider .'</b></p>
                                                            <p>Sadržaj komentara:<b> '.$userComment .'</b></p>
                                                            
                                                        </div>
                                            </div>
                                        ';

                                        $i = $i + 2;

                                }
                                echo "</div>";
                            } } else {
                                echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA KOMENTARA</h1></div><div class='col-md-4'></div>";
                            }


                            $dbConnection = NULL;

                            if(isset($_GET[base64_url_encode("deleteThisComment")]) && isset($_GET[base64_url_encode("IDOfComment")])){

                                if(base64_url_decode($_GET[base64_url_encode("deleteThisComment")]) == "no"){
                                    $modalUrl = $urlDeleteComment = "komentari.php?".base64_url_encode("deleteThisComment")."=".base64_url_encode("true")."&".base64_url_encode("IDOfComment") ."=".base64_url_encode($commentID)."&".base64_url_encode("newsID")."=".base64_url_encode($newsID);;

                                    echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">BRISANJE KOMETARA</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Da li ste sigurni da želite obrisati ovaj komentar...??
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                         
                                                        <a class="btn btn-primary" href="'.$modalUrl.'">Obriši</a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->';
                                }
                            }
?>
                                          
</div>

</div></div>
                            <footer role="contentinfo">
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline pull-left">
                                    <li>
                                        <h6 style="margin: 0;"> &copy; 2015 ETS Mostar</h6></li>
                                </ul>
                                <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>


           



                     <script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
                    <!-- Load jQuery -->
                    <script type="text/javascript" src="assets/js/jqueryui-1.9.2.min.js"></script>
                    <!-- Load jQueryUI -->

                    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
                    <!-- Load Bootstrap -->


                    <script type="text/javascript" src="assets/plugins/easypiechart/jquery.easypiechart.js"></script>
                    <!-- EasyPieChart-->
                    <script type="text/javascript" src="assets/plugins/sparklines/jquery.sparklines.min.js"></script>
                    <!-- Sparkline -->
                    <script type="text/javascript" src="assets/plugins/jstree/dist/jstree.min.js"></script>
                    <!-- jsTree -->

                    <script type="text/javascript" src="assets/plugins/codeprettifier/prettify.js"></script>
                    <!-- Code Prettifier  -->
                    <script type="text/javascript" src="assets/plugins/bootstrap-switch/bootstrap-switch.js"></script>
                    <!-- Swith/Toggle Button -->

                    <script type="text/javascript" src="assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>
                    <!-- Bootstrap Tabdrop -->


                    <script type="text/javascript" src="assets/js/enquire.min.js"></script>
                    <!-- Enquire for Responsiveness -->



                    <script type="text/javascript" src="assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script>
                    <!-- nano scroller -->

                    <script type="text/javascript" src="assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script>
                    <!-- Mousewheel support needed for jScrollPane -->

                    <script type="text/javascript" src="assets/js/application.js"></script>
                    <script type="text/javascript" src="assets/demo/demo.js"></script>
                    <script type="text/javascript" src="assets/demo/demo-switcher.js"></script>

                    <!-- End loading site level scripts -->
                    <script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script>    
                    <!-- Load page level scripts-->

                    <!-- Date Range Picker -->

                    <script type="text/javascript" src="assets/demo/demo-index.js"></script>
                    <script type="text/javascript" src="assets/plugins/pines-notify/pnotify.min.js"></script> 
                   
</body>

</html>
<?php 
 if(base64_url_decode($_GET[base64_url_encode("deleteThisComment")]) == "no")
        echo "<script>$('#myModal').modal('show')</script>";

?>