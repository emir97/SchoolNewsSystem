<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(isset($_GET['page'])){
    $page = $_GET['page'];
    
    if($page == "" || $page == "1"){
        $page1=0;
    } else {
        $page1 = $page*5;
    }
} else {
    $page1=0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Vijesti</title>
   

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
                                <h1>Vijesti</h1>
                                <div class="options">

                                </div>
                            </div>
                            <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">




                                        <div class="container-fluid">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
                                                         echo '
                                                         <a href="vijesti.php?'.base64_url_encode("postNewNews") .'='.base64_url_encode("new").'" class="btn btn-default">Dodaj Vijest</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="panel">
                                                        
                                                                    
                                                                        <?php
                                                                            //konektovanje na bazu
                                                                            $dbConnect = connectToDb();
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                $numOFnews = $dbConnect->query("SELECT * FROM news");
                                                                            } else {
                                                                                $authorSesion = $_SESSION['AUTHOR_USERID'];
                                                                                $numOFnews = $dbConnect->query("SELECT * FROM news WHERE author_id = '$authorSesion'");
                                                                                
                                                                            }   
                                                                            if($numOFnews->rowCount() > 0) {
                                                                                echo '<div class="panel-body panel-no-padding table-responsive">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Naslov</th>
                                                                        <th>Tekst</th>
                                                                        <th>Slika</th>
                                                                        <th>Autor</th>
                                                                        <th>Oznaka</th>
                                                                        <th>Datum</th>
                                                                        <th>Broj Pregleda</th>
                                                                        <th>Izdvoji</th>
                                                                        <th width="240"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchNewsData = "SELECT * FROM news ORDER BY news_date DESC LIMIT $page1, 10";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNewsData);
                                                                            } else {
                                                                                $queryForFetchNewsData = "SELECT * FROM news WHERE author_id = '$authorSesion' ORDER BY news_date DESC LIMIT $page1, 10";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNewsData);
                                                                            }
                                                                             $authorSesion = NULL;
                                                                            //prikaz podataka 
                                                                            foreach($result as $row){
                                                                                $newsID = $row['news_id']; 
                                                                                
                                                                                // naslov vijesti
                                                                                $newsTitle = $row['news_title'];
                                                                                
                                                                                //enkriptovani URL
                                                                                $encryptUrl = base64_url_encode($newsTitle);
                                                                                
                                                                                //url za gledanje vijetsti
                                                                                $urlForWatch = "../vijest_detalji.php?ID=" .$newsID.
                                                                                                "&title=". $newsTitle;

                                                                                // uredjivanje prikaza naslova
                                                                                $newsTitle = restrictText($newsTitle);
                                                                                
                                                                                // uredjivanje prikaza sadrzaja
                                                                                $newsConent = $row['news_content'];
                                                                                $newsConent = restrictText($newsConent);
                                                                                
                                                                                //slika vijesti
                                                                                $newsImage = $row['news_image'];
                                                                                
                                                                                //datum
                                                                                $newsDate = new DateTime($row['news_date']);
                                                                                $newsDate = $newsDate->format('d.m.o.');
                                                                                
                                                                                //broj pregleda
                                                                                $newsViews = $row['news_num_views'];
                                                                                
                                                                                //url za izdvajanje vijesti
                                                                                $izdvojeno = $row['izdvojeno'];
                                                                                $urlForSlngle = $_SERVER['PHP_SELF'] . "?". base64_url_encode("newsID") . "=" . base64_url_encode($newsID) . "&" . base64_url_encode("izdvajanje") ."=".base64_url_encode("true");
                                                                                
                                                                                if($izdvojeno == 1){
                                                                                    $linkZaIzdvajanje = "<a href='".$urlForSlngle."' class='btn btn-success'><i class='fa fa-check'></i></a>";
                                                                                } else {
                                                                                    $linkZaIzdvajanje = "<a href='".$urlForSlngle."&".base64_url_encode("setIzdvoji")."=".base64_url_encode("1")."' class='btn btn-inverse'><i class='fa fa-wrench'></i></a>";
                                                                                }
                                                                                
                                                                                
                                                                                $newsKeywords = $row['keywords_id'];
                                                                                $newsAuthor = $row['author_id'];
                                                                                $queryForFetchAuthor = "SELECT * FROM authors WHERE author_id='$newsAuthor' LIMIT 1";
                                                                                $authorResult = $dbConnect->query($queryForFetchAuthor);
                                                                                foreach($authorResult as $row){
                                                                                 $authorName = $row['author_name']; 
                                                                                 $authorSurname = $row['author_surname'];     
                                                                                
                                                                                
                                                                                
                                                                                
                                                                                
                                                                                echo "
                                                                                <tr>
                                                                                <td><div class='titleView'>$newsTitle</div></td>
                                                                                <td><div class='contentView'>$newsConent</div></td>
                                                                                <td><img src='../images/news/".$newsImage."' height='100' width='100'/></td>
                                                                                <td>$authorName $authorSurname</td>
                                                                                <td>
                                                                                ";
                                                                                $key = $dbConnect->query("SELECT keywords_content FROM keywords WHERE keywords_id=$newsKeywords");
                                                                                foreach($key as $a){
                                                                                    
                                                                                  $aa=$a['keywords_content'];
                                                                                  echo"<span class='label label-inverse'>$aa</span> &nbsp;<br>";
                                                                                }
                                                                                print("</td>
                                                                                <td>$newsDate</td>
                                                                                <td>$newsViews</td>
                                                                                <td>$linkZaIzdvajanje</td>
                                                                                <td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke vijesti <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a target='_blank' href='".$urlForWatch."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj vijest
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='vijesti.php?".base64_url_encode("updateNews") ."=".$encryptUrl."&".base64_url_encode("newsID") ."=".base64_url_encode($newsID). "' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-pencil'></i>
                                                                                            Izmijeni vijest
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$_SERVER['PHP_SELF']."?".base64_url_encode("deleteNews") ."=".$encryptUrl."&".base64_url_encode("newsID")."=".base64_url_encode($newsID)."' id='bootbox-demo-3'><i class='fa fa-trash-o'></i> Obriši vijest</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>
                                                                                </tr>
                                                                                ");
                                                                                
                                                                            }
                                                                            }
                                                                            echo " </tbody></table></div>";
                                                                            echo ' </div></div><!-- paginacija --><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="text-center"><ul class="pagination pagination-sm">';
                                
                    
                                 
                                 
                                                                //broj stranica 
                                                                $resultOfPagination = $dbConnect->query("SELECT * FROM news");
                                                                $numberOfPages = $resultOfPagination->rowCount();
                                                                $numberOfPages = floatval($numberOfPages/10);
                                                                $numberOfPages = ceil($numberOfPages);
                                                                if(isset($_GET['page'])){
                                                                    $next = $_GET['page'];
                                                                    $next = $next - 1;
                                                                    if($next <=0){
                                                                        echo '<li class="disabled"><a><i class="fa fa-angle-left"></i></a></li>';
                                                                    } else {
                                                                        $paginationUrl = $_SERVER['PHP_SELF']."?page=".$next;
                                                                        echo '<li><a href="'.$paginationUrl.'"><i class="fa fa-angle-left"></i></a></li>';
                                                                    }        
                                                                 } else {
                                                                     echo '<li class="disabled"><a><i class="fa fa-angle-left"></i></a></li>';
                                                                 }
                                                                
                                                                for ($i = 1; $i <= $numberOfPages; $i++){
                                                                    if(isset($_GET['page'])) {
                                                                        if($i == $_GET['page'])
                                                                            echo "<li class='active'><a href='vijesti.php?page=$i'>$i</a></li>";
                                                                        else
                                                                            echo "<li><a href='vijesti.php?page=$i'>$i</a></li>";
                                                                    } else {
                                                                        if($i==1)
                                                                            echo "<li class='active'><a href='vijesti.php?page=$i'>$i</a></li>";
                                                                         else
                                                                            echo "<li><a href='vijesti.php?page=$i'>$i</a></li>";
                                                                    }
                                                                    
                                                                 }
                                                                 
                                                                 if(isset($_GET['page'])){
                                                                    $next = $_GET['page'];
                                                                    $next = $next + 1;
                                                                    if($next > $numberOfPages){
                                                                        echo '<li class="disabled"><a><i class="fa fa-angle-right"></i></a></li>';
                                                                    } else {
                                                                        $paginationUrl = $_SERVER['PHP_SELF']."?page=".$next;
                                                                        echo '<li><a href="'.$paginationUrl.'"><i class="fa fa-angle-right"></i></a></li>';
                                                                    }        
                                                                 } else if(!isset($_GET['page']) && $numberOfPages > 1){
                                                                     
                                                                     $paginationUrl = $_SERVER['PHP_SELF']."?page=2";
                                                                     echo '<li><a href="'.$paginationUrl.'"><i class="fa fa-angle-right"></i></a></li>';
                                                                     echo '
                                                                     </ul></div></div><div class="col-md-4"></div></div><!-- ./paginacija -->';
                                                                 } else {
                                                                     echo '<li class="disabled"><a><i class="fa fa-angle-right"></i></a></li>';
                                                                 }    
                                                                 $dbConnect=NULL;             
                       
                              
                            
                                                            } else {
                                                                echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA VIJESTI</h1></div><div class='col-md-4'></div>";
                                                            }
                                                            
                                                                        ?>
                                                   

                                        </div>
                                        <!-- .container-fluid -->



                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">BRISANJE VIJESTI</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Da li ste sigurni da želite obrisati ovu vijest...??
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                        <?php $modalUrl = $_SERVER['REQUEST_URI'].'&'.base64_url_encode("requestDelete") .'='.base64_url_encode('YES')?>
                                                        <a class="btn btn-primary" href="<?php echo $modalUrl?>">Obriši</a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                        
                                        <div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">GREŠKA U IZDVAJANJU</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Izdvojeno je vec 5 vijesti što je maksimalan broj. Prijavi te se kao administrator i skinete oznaku "izdvoji" tako što ćete kliknuti na zeleno dugme kako bi mogli izdvojiti iduću vijest.
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                       
                                                        <button class="btn btn-primary" data-dismiss="modal">Zatvori</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->





                                    </div>


                                </div>
                                <!-- .container-fluid -->
                            </div>
                            <!-- #page-content --></div></div>
                        </div>
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


           



                    <!-- Load site level scripts -->

                    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

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
                    <!-- Initialize scripts for this page-->
<script type="text/javascript" src="assets/plugins/form-parsley/parsley.js"></script>                   <!-- Validate Plugin / Parsley -->
<script type="text/javascript" src="assets/demo/demo-formvalidation.js"></script>
                    <!-- End loading page level scripts-->

</body>

</html>

<?php   

    //brisanje vijesti
    $decodeUrlVar = base64_url_encode("deleteNews");
    if(isset($_GET[$decodeUrlVar])){
        if(!isset($_GET[base64_url_encode("requestDelete")]))
            echo "<script>$('#myModal').modal('show')</script>";
        else 
        try{
        $connections = connectToDb();
        $postTitle = base64_url_decode($_GET[$decodeUrlVar]);
        $ID = base64_url_decode($_GET[base64_url_encode("newsID")]);
        //brisanje komentara za tu vijest
        $deleteComment = $connections->prepare("DELETE FROM comments WHERE news_id = :ID");
        $deleteComment->bindParam(":ID", $ID);
        $deleteComment->execute();

        //brisanje vijesti
        $deleteNewsData = "DELETE FROM news WHERE news_title=:postTitle AND news_id = :id";
        $stmt = $connections->prepare($deleteNewsData);
        $stmt->bindParam(":postTitle", $postTitle);
        $stmt->bindParam(":id", $ID);
        $isExecute = $stmt->execute(); 
        if($isExecute){
            $connections = NULL;
            header('Location: vijesti.php'); 
            exit();
        } else{
            $connections = NULL;
            header('location: 500.php');
            exit();
        }
        }
      catch(PDOException $e){
            echo $e->getMessage();
            exit();
        }
        $connections = NULL;
   }
   
   //izmjena vijesti
   $decodeUrlUpdate = base64_url_encode("updateNews");
   if(isset($_GET[$decodeUrlUpdate])){
        $_SESSION['titleOfNews'] = $_GET[$decodeUrlUpdate];
        $_SESSION['ID'] = $_GET[base64_url_encode("newsID")];
        $_SESSION['updatePost'] = "vijest";
        header('Location: edit_page.php?'.base64_url_encode("updatePost")."=".base64_url_encode($_SESSION['updatePost']). "&".
                 base64_url_encode("title") ."=".base64_url_encode($_SESSION['titleOfNews']));
   }
   
   //postavljanje nove vijesti
   $decodeUrlPostNew = base64_url_encode("postNewNews");    //dekodiranje linka
   if(isset($_GET[$decodeUrlPostNew])){
       if($_GET[$decodeUrlPostNew] = base64_url_encode("new")){
           $_SESSION['newPost'] = "vijest";
           $vijest = base64_url_encode($_SESSION['newPost']);
           header('Location: upload_post.php?'.base64_url_encode("novi")."=".$vijest);
       }
   }
   
   //izdvajanje vijesti
   $decodeUrlSingle = base64_url_encode("izdvajanje");    //dekodiranje linka
   if(isset($_GET[$decodeUrlSingle])){
       if(base64_url_decode($_GET[$decodeUrlSingle]) == "true"){
           $izdvID = base64_url_decode($_GET[base64_url_encode("newsID")]);
           $connections = connectToDb();
           $brojIzdvojenihVijesti = $connections->query("SELECT * FROM news WHERE izdvojeno = '1'");
           if(isset($_GET[base64_url_encode("setIzdvoji")])){
            if($brojIzdvojenihVijesti->rowCount() >= 5){
                echo "<script>$('#modalIzdvoji').modal('show')</script>";
                exit();
            }
           }
           $dohvatanjeVijesti =  $connections->prepare("SELECT * FROM news WHERE news_id = :id LIMIT 1");
           $dohvatanjeVijesti->bindParam(":id", $izdvID);
           $dohvatanjeVijesti->execute();
           foreach($dohvatanjeVijesti as $row){
              $checkForSingleOut = $row['izdvojeno'];
              break;
           }
           if($checkForSingleOut == 0){
               $connections->query("UPDATE news SET izdvojeno = 1 WHERE news_id = '$izdvID'");
           } else {
               $connections->query("UPDATE news SET izdvojeno = 0 WHERE news_id = '$izdvID'");
           }
           $connections = NULL;
           header('Location: '. $_SERVER['PHP_SELF']);
           exit();
       }
   }
?>