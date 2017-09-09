<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Slider</title>
   

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
                                <h1>Slider meni</h1>
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
                                                            

                                                          <a href='<?php echo "slider.php?".base64_url_encode("postNewSlide") ."=".base64_url_encode("new")?>'>  <div class="btn btn-default">Dodaj Slider</div> </a>
                                                        </div>
                                                    </div>

                                                   <div class="panel">
                                                        
                                                                    
                                                                        <?php
                                                                            //konektovanje na bazu
                                                                            $dbConnect = connectToDb();
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchSliderData = "SELECT * FROM slider ORDER BY red_br";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchSliderData);
                                                                            } else {
                                                                                $authorSesion = $_SESSION['AUTHOR_USERID'];
                                                                                 // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchSliderData = "SELECT * FROM slider WHERE author_id = '$authorSesion' ORDER BY red_br";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchSliderData);
                                                                            }
                                                                            
                                                                            if($result->rowCount() > 0) {
                                                                                echo ' <div class="panel-body panel-no-padding table-responsive">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Broj</th>
                                                                        <th>Naslov</th>
                                                                        <th>Tekst</th>
                                                                        <th>Slika</th>
                                                                        <th>Autor</th>
                                                                        <th>Oznaka</th>
                                                                        <th>Datum</th>
                                                                        <th width="240"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="table-hover">';
                                                                            //prikaz podataka 
                                                                            foreach($result as $row){
                                                                                $sliderID = $row['id']; 
                                                                                $no = $row['red_br'];
                                                                                
                                                                                // uredjivanje prikaza naslova
                                                                                $sliderTitle = $row['title'];
                                                                                
                                                                                $encryptUrl = base64_url_encode($sliderTitle);
                                                                                
                                                                                $sliderTitle = restrictText($sliderTitle);
                                                                                
                                                                                // uredjivanje prikaza sadrzaja
                                                                                $sliderConent = $row['content'];
                                                                                $sliderConent = restrictText($sliderConent);
                                                                                
                                                                                $sliderImage = $row['image'];
                                                                                //datum objavljivanja
                                                                                $sliderDate = new DateTime($row['date']);
                                                                                $sliderDate = $sliderDate->format('d.m.o.');
                                                                                
                                                                                $sliderKeywords = $row['keywords_id'];
                                                                                $sliderAuthor = $row['author_id'];
                                                                                $queryForFetchAuthor = "SELECT * FROM authors WHERE author_id='$sliderAuthor'";
                                                                                $authorResult = $dbConnect->query($queryForFetchAuthor);
                                                                                foreach($authorResult as $row){
                                                                                 $authorName = $row['author_name']; 
                                                                                 $authorSurname = $row['author_surname'];     
                                                                                
                                                                                
                                                                                
                                                                                
                                                                                
                                                                                echo "
                                                                                <tr>
                                                                                <td>$no</td>
                                                                                <td><div class='titleView'>$sliderTitle</div></td>
                                                                                <td><div class='contentView'>$sliderConent ...</div></td>
                                                                                <td><img src='../images/slider/".$sliderImage."' height='100' width='100'></td>
                                                                                <td>$authorName $authorSurname</td>
                                                                                <td>
                                                                                ";
                                                                                $key = $dbConnect->query("SELECT keywords_content FROM keywords WHERE keywords_id=$sliderKeywords");
                                                                                foreach($key as $a){
                                                                                    
                                                                                  $aa=$a['keywords_content'];
                                                                                  echo"<span class='label label-inverse'>$aa</span> &nbsp;<br>";
                                                                                }
                                                                                print("</td>
                                                                                <td>$sliderDate</td>
                                                                                <td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke slidera <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a href='../index.php' tyrget='_blank' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj slider
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='slider.php?".base64_url_encode("updateSlide") ."=".$encryptUrl."&".base64_url_encode("sliderID") ."=".base64_url_encode($sliderID). "' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-pencil'></i>
                                                                                            Izmijeni slider
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a data-toggle='modal' href='".$_SERVER['PHP_SELF']."?".base64_url_encode("deleteSlide") ."=".$encryptUrl."&".base64_url_encode("slideID")."=".base64_url_encode($sliderID)."' id='bootbox-demo-3'><i class='fa fa-trash-o'></i> Obriši slider</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                
                                                                                </td>
                                                                                </tr>
                                                                                ");
                                                                                
                                                                                
                                                                            }
                                                                            }
                                                                            echo '</tbody></table>';
                                                                        } else {
                                                                            echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA SLIDER-a</h1></div><div class='col-md-4'></div>";
                                                                        }
                                                                        $dbConnect=NULL;
                                                                        
                                                                        ?>
                                                                       
                                                                
                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- .container-fluid -->

                                     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">BRISANJE SLIDE-A</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Da li ste sigurni da želite obrisati ovaj slide...??
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                        <?php $modalUrl = $_SERVER['REQUEST_URI'].'&'.base64_url_encode("requestDelete") .'='.base64_url_encode('YES')?>
                                                        <a class="btn btn-primary" href="<?php echo $modalUrl?>">Obriši</a>
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
                            <!-- #page-content -->
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
                    <!-- Initialize scripts for this page-->

                    <!-- End loading page level scripts-->
<script type="text/javascript" src="assets/plugins/bootbox/bootbox.js"></script> 	<!-- Bootbox -->

<script type="text/javascript" src="assets/demo/demo-modals.js"></script> 
</body>

</html>

<?php   
    //brisanje slidera
    $decodeUrlVar = base64_url_encode("deleteSlide");   //dekodiranje linka
    if(isset($_GET[$decodeUrlVar])){
        if(!isset($_GET[base64_url_encode("requestDelete")]))
            echo "<script>$('#myModal').modal('show')</script>";
        else 
        try{
        //konektovanje na bazu
        $connections = connectToDb();
        //dekodiranje imena slidera
        $postTitle = base64_url_decode($_GET[$decodeUrlVar]);
        $ID = base64_url_decode($_GET[base64_url_encode("slideID")]);
        //brisanje slidera
        $deleteSliderData = "DELETE FROM slider WHERE title=:postTitle AND id=:id";
        $stmt = $connections->prepare($deleteSliderData);
        $stmt->bindParam(":postTitle", $postTitle);
        $stmt->bindParam(":id", $ID);
        $isExecute = $stmt->execute(); 
        //u slucaju da je slider obrisan osvjezavanje stranice
        if($isExecute){
            header('Location: slider.php'); 
        }
        }
      catch(PDOException $e){
            echo $e->getMessage();
            exit();
        }
        //zatvaranje konekcije sa bazom
        $connections = NULL;
   }
   
   //izmjena podataka slidera
   $decodeUrlUpdate = base64_url_encode("updateSlide");     //dekodiranje linka
   if(isset($_GET[$decodeUrlUpdate])){
        //prosljedjivanje podataka narednoj stranici
        $_SESSION['titleOfNews'] = $_GET[$decodeUrlUpdate];
        $_SESSION['ID'] = $_GET[base64_url_encode("sliderID")];
        $_SESSION['updatePost'] = "slider";
        header('Location: edit_page.php?'.base64_url_encode("updatePost")."=".base64_url_encode($_SESSION['updatePost']). "&".
                 base64_url_encode("title") ."=".base64_url_encode($_SESSION['titleOfNews']));
   }
   
   //postavljanje novog slidera
   $decodeUrlPostNew = base64_url_encode("postNewSlide");    //dekodiranje linka
   if(isset($_GET[$decodeUrlPostNew])){
       if($_GET[$decodeUrlPostNew] = base64_url_encode("new")){
           try{
           $connections = connectToDb();
           $numberOfSliders = $connections->prepare("SELECT * FROM slider");
           $numberOfSliders->execute();
           
           if($numberOfSliders->rowCount() < 6){
           $_SESSION['newPost'] = "slider";
           $slider = base64_url_encode($_SESSION['newPost']);
           header('Location: upload_post.php?'.base64_url_encode("novi")."=".$slider);
           }
           else{
               echo "<script>
               bootbox.alert('<b>Greška. Maksimalan broj slide-ova može iznosti 6. Toliki broj ih već postoji. Prijavite se kao administrator i obrišite jedan da bi mogli novi napraviti</b>');
                   </script>";
                   
           }
           $connections = NULL;
           } catch(PDOEception $e){
               $e->getMessage();
           }
       }
   }
   
?>