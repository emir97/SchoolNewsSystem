<?php
include 'core/connection.php'; 
if(isset($_GET['ID']) && isset($_GET['title'])){
    
    //dohavatanje vijesti
    $ID = $_GET['ID'];
    $title = $_GET['title'];

    $connectToDb = connectOnDb();

    //unos da je vijest pregledana
    $fetcNumOfViews = $connectToDb->prepare("SELECT news_num_views FROM news WHERE news_id = :ID LIMIT 1");
    $fetcNumOfViews->bindParam(":ID", $ID);
    $fetcNumOfViews->execute();
    while ($r = $fetcNumOfViews->fetch(PDO::FETCH_OBJ)) {
        $numViews = $r->news_num_views;
    }
    $numViews = $numViews + 1;
    $insertView = $connectToDb->prepare("UPDATE news SET news_num_views = :numOfViews WHERE news_id = :ID");
    $insertView->bindParam(":ID", $ID);
    $insertView->bindParam(":numOfViews", $numViews);
    $insertView->execute();

    $fetchNews = $connectToDb->prepare("SELECT * FROM news WHERE news_id = :ID AND news_title = :title LIMIT 1");
    $fetchNews->bindParam(":ID", $ID);
    $fetchNews->bindParam(":title", $title);
    $fetchNews->execute();

    if($fetchNews->rowCount() == 1){
        foreach ($fetchNews as $row) {
            //ID vijesti
            $newsID = $row['news_id'];

            //naslov vijesti
            $newsTitle = $row['news_title'];

            //link za komentarisanje
            $whatcomment = "news." .$newsTitle.".". $ID;

            if (strlen($newsTitle) > 100) {
                $newsTitle = substr($newsTitle, 0, 100);
            }

            //sadrzaj vijesti 
            $newsContent = $row['news_content'];

            //datum vijesti 
            $newsDate = new DateTime($row['news_date']);
            $newsDate = $newsDate->format('d.m.o.');

            //slika vijesti 
            $newsImage = $row['news_image'];

            //ID autora
            $IDAuthor = $row['author_id'];
            $fetchAuthor = $connectToDb->query("SELECT * FROM authors WHERE author_id = '$IDAuthor' LIMIT 1");
            if($fetchAuthor->rowCount() == 1){
                while ($r = $fetchAuthor->fetch(PDO::FETCH_OBJ)) {
                    //ime i prezime autora
                    $authorFirstName = $r->author_name;
                    $authorLastName = $r->author_surname;
                    $authorProfile = $r->author_image;
                    $authorCV = $r->author_cv;
                    break;
                }
            }

            $IDKeywords = $row['keywords_id'];
            $fetchKeywords = $connectToDb->query("SELECT * FROM keywords WHERE keywords_id = '$IDKeywords' LIMIT 1");
            if($fetchKeywords->rowCount() == 1){
                while ($r = $fetchKeywords->fetch(PDO::FETCH_OBJ)) {
                    $key = $r->keywords_content;
                    break;
                }
            }

            //provjera koliko postoji vijesti u bazi
            $numberOfNewsQuery = $connectToDb->query("SELECT news_id FROM news ORDER BY news_id DESC LIMIT 1");
            $numberOfNews = 0;
            while ($r = $numberOfNewsQuery->fetch(PDO::FETCH_OBJ)) {
                $numberOfNews = $r->news_id;
            }
            //link za sljedecu vijest
            $fetchNextNewsID = $newsID + 1;
            $buttonForNextNews = "";
            for ($i = $fetchNextNewsID; $i <= $numberOfNews; $i++) { 
                $fetchNextNews = $connectToDb->query("SELECT * FROM news WHERE news_id = '$i' LIMIT 1");
                if($fetchNextNews->rowCount() == 1){
                    while ($r = $fetchNextNews->fetch(PDO::FETCH_OBJ)) {
                        $idOfNextNews = $r->news_id;
                        $titleOfNextNews = $r->news_title;
                    }
                    $urlForNexNews = $_SERVER['PHP_SELF'] ."?ID=".$idOfNextNews."&title=".$titleOfNextNews;
                    $buttonForNextNews = '<span class="whiteBtn">
                                            <a href="'.$urlForNexNews.'" class="next">Sljedeća vijest<i class="fa fa-angle-right">
                                            </i></a>
                                            </span>';
                    break;
                }
            }

        //link za prethodnu vijest
        $previousNewsID = $newsID - 1;
        $buttonForPrevNews = "";
        for ($i = $previousNewsID; $i >= 1; $i--) { 
            $fetchNextNews = $connectToDb->query("SELECT * FROM news WHERE news_id = '$i' LIMIT 1");
            if($fetchNextNews->rowCount() == 1){
                    while ($r = $fetchNextNews->fetch(PDO::FETCH_OBJ)) {
                        $idOfPrevNews = $r->news_id;
                        $titleOfPrevNews = $r->news_title;
                    }
                    $urlForPrevNews = $_SERVER['PHP_SELF'] ."?ID=".$idOfPrevNews."&title=".$titleOfPrevNews;
                    $buttonForPrevNews = '<span class="whiteBtn">
                                            <a href="'.$urlForPrevNews.'" class="prev"><i class="fa fa-angle-left"></i>Prethodna vijest</a>
                                          </span>';
                    break;
                }
        }

        }
        $fetchComments = $connectToDb->query("SELECT * FROM comments WHERE news_id = '$ID'");
        $numberOfComments = $fetchComments->rowCount();
        if($numberOfComments == 1){
            $numberOfComments = $numberOfComments . " Komentar";
        } else {
            $numberOfComments = $numberOfComments. " Komentara";
        }
        
        $urlForShareNews = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlImage = "http://".$_SERVER['SERVER_NAME']."/images/news/$newsImage";
        
        
    } else {
        header('location: vijesti.php');
    }
} else {
    header('location: vijesti.php');
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
    
    <title>ETS - <?php echo $newsTitle; ?> </title>
    <?php 
    include 'includes/SEO.php';
    include 'includes/css_js.php';
     ?>
     
    <meta property="og:url"           content="<?php echo $urlForShareNews; ?>" />
	<meta property="og:type"          content="article" />
	<meta property="og:title"         content="ETS - <?php echo $newsTitle; ?>" />
	<meta property="og:description"   content="<?php echo strip_tags($newsContent); ?>" />
	<meta property="og:image"         content="<?php echo $urlImage; ?>" />
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hr_HR/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    


    <?php include('view/header_mobile.php'); ?>


   <?php include('view/header.php'); ?>
    
    
    
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><a href="./vijesti.php">Vijesti</a></h6>
                    <h6><span class="page-active"><?php echo $newsTitle; ?></span></h6>
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
                        <div class="blog-post-container">
                            <div class="blog-post-image">
                                <?php echo '<img src="./images/news/'.$newsImage.'" alt="'.$newsImage.'" />'; ?>
                                <div class="blog-post-meta">
                                    <ul>
                                        <li><i class="fa fa-calendar-o"></i>    <?php print $newsDate; ?>   </li>
                                        <li><a href="#blog-comments"><i class="fa fa-comments"></i><?php echo $numberOfComments; ?></a></li>
                                        <li><a href="#blog-author"><i class="fa fa-user"></i>   
                                            <?php echo $authorFirstName . " " . $authorLastName; ?> 
                                        </a></li>
                                    </ul>
                                </div> <!-- /.blog-post-meta -->
                            </div> <!-- /.blog-post-image -->
                            <div class="blog-post-inner">
                                <h3 class="blog-post-title">    <?php echo $newsTitle; ?>   </h3>
                                <?php echo $newsContent; ?>
                                <div class="tag-items">
                                    <span class="small-text">Oznake:</span>
                                    <a href="#" rel="tag">  <?php echo $key; ?>   </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span class="small-text">Podijeli: &nbsp;&nbsp;&nbsp;<div class="fb-share-button" data-href="<?php echo $urlForShareNews;?>" data-layout="button"></div></span>
                                </div>
                                
                            </div>
                        </div> <!-- /.blog-post-container -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="prev-next-post clearfix">
                            <?php echo $buttonForPrevNews; ?>
                            <?php echo $buttonForNextNews; ?>
                        </div>
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

                <div class="row">
                    <div class="col-md-12">
                        <div id="blog-author" class="clearfix">
                            <div class="blog-author-img">
                                <img src="./images/prof/<?php echo $authorProfile; ?>" alt="" class="blog-author-img"/>
                            </div>
                            <div class="blog-author-info">
                                <h4 class="author-name"><a href="#">
                                    <?php echo $authorFirstName . " ".$authorLastName; ?>
                                </a></h4>
                                <p> <?php echo $authorCV; ?>    </p>
                            </div>
                        </div> <!-- /.blog-author -->
                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->
                
               

                                <?php 
                                $fetchComments = $connectToDb->query("SELECT * FROM comments WHERE news_id = '$ID'");
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
   <?php include 'view/footer.php' ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>