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
    
    <title>ETS Admin - Događaji</title>
   

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
                                <h1>Događaji</h1>
                                <div class="options">

                                </div>
                            </div>
                            <div class="row">
                            	<div class="col-md-12">
                                                    <div class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
                                                         echo '
                                                         <a href="izmjena_dogadjaji.php?'.base64_url_encode("action")."=".base64_url_encode("insert").'" class="btn btn-default">Dodaj Događaj</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </div>
                                                    <?php 
                                                    $connect = connectToDb();
                                                    if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                        $numOfEvent = $connect->query("SELECT * FROM events");
                                                    } else {
                                                         $authorSesion = $_SESSION['AUTHOR_USERID'];
                                                         $numOfEvent = $connect->query("SELECT * FROM events WHERE author_id = '$authorSesion'");
                                                    }
                                                    if($numOfEvent->rowCount() > 0) {
                                                        if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                            $fetchEvents = $connect->query("SELECT * FROM events ORDER BY event_date_post DESC LIMIT $page1, 10");
                                                        } else {
                                                            $fetchEvents = $connect->query("SELECT * FROM events WHERE author_id = '$authorSesion' ORDER BY event_date_post DESC LIMIT $page1, 10");
                                                            $authorSesion = NULL;
                                                        }
                                                    if($fetchEvents->rowCount() > 0){
                                                    	echo '<div class="panel"><div class="panel-body panel-no-padding table-responsive"><table class="table table-striped table-bordered" style="overflow-y:hidden;"><thead><tr>
                                                    					<th>Naslov</th>
                                                                        <th>Tekst</th>
                                                                        <th>Slika</th>
                                                                        <th>Autor</th>
                                                                        <th>Početak događaja</th>
                                                                        <th>Kraj događaja</th>
                                                                        <th>Datum objavljivanja</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                foreach ($fetchEvents as $key => $value) {

                                                                	$IDEvent = $value['event_id'];

                                                                	$eventTitle = $value['event_title'];

                                                                	$eventContent = $value['event_content'];

                                                                	$eventPlace = $value['event_place'];

                                                                	$eventImage = $value['event_image'];

                                                                	$eventDatePost = new DateTime($value['event_date_post']);
                                                                	$eventDate = $eventDatePost->format('d. M. o.');

                                                                	$startDateOfEvents = new DateTime($value['event_start_date']);
                                                                	$startDate = $startDateOfEvents->format('d. M. o.');
                                                                	$startTime = $startDateOfEvents->format('H:i');

                                                                    
                                                                    	$endDateEnd = new DateTime($value['event_end_date']);
                                                                    	$endDate = $endDateEnd->format('d. m. o.');
                                                                    	$endTime = $endDateEnd->format('H:i');
                                                                        if(strtotime($endDate) == 0){
                                                                            $endDate = "-. -. -. <br>";
                                                                        }

                                                                	$eventAuthor = $value['author_id'];
                                                                	$fetchAutor = $connect->query("SELECT author_name, author_surname FROM authors WHERE author_id = '$eventAuthor' LIMIT 1");
                                                                	while ($r=$fetchAutor->fetch(PDO::FETCH_OBJ)) {
                                                                		$firstname = $r->author_name;
                                                                		$lastname = $r->author_surname;
                                                                	}

                                                                        $urlForChangeEvent = "izmjena_dogadjaji.php?".base64_url_encode("eventTitle") ."=". base64_url_encode($eventTitle). "&".base64_url_encode("eventID")."=".base64_url_encode($IDEvent) . "&".base64_url_encode("action")."=".base64_url_encode("edit");

                                                                        $urlForDeleteEvent = "dogadjaji.php?".base64_url_encode("eventID")."=".base64_url_encode($IDEvent) . "&".base64_url_encode("action")."=".base64_url_encode("delete"). "&".base64_url_encode("delete")."=".base64_url_encode("no");

                                                                        $urlForViewEvent = "../dogadjaji_detalji.php?ID=".$IDEvent."&title=".$eventTitle;

                                                                	echo "<tr><td><div class='titleView'>".$eventTitle."</div></td><td><div class='contentView'>".$eventContent."</div></td><td><img src='../images/events/".$eventImage."' height='100' width='100'/></td><td> ".$firstname." ". $lastname."</td><td>".$startDate." ".$startTime." </td><td> ".$endDate." ".$endTime."</td><td>".$eventDate."</td><td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke događaja <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a target='_blank' href='".$urlForViewEvent."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-eye'></i>
                                                                                            Pogledaj događaj
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForChangeEvent."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-pencil'></i>
                                                                                            Izmijeni događaj
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$urlForDeleteEvent."' id='bootbox-demo-3'><i class='fa fa-trash-o'></i> Obriši događaj</a>
                                                                                        </li>
                                                                                        
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>";

                                                                }



                                                                echo ' </tbody></table></div></div></div>';

                                                           echo '      <!-- paginacija -->
                                                <div class="row">
                                                     <div class="col-md-4"></div>
                                                   <div class="col-md-4">
                        <div class="text-center">
                            <ul class="pagination pagination-sm">';
                                
                    
                                 
                                 
                                //broj stranica 
                                $resultOfPagination = $connect->query("SELECT * FROM events");
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
                                            echo "<li class='active'><a href='dogadjaji.php?page=$i'>$i</a></li>";
                                        else
                                            echo "<li><a href='dogadjaji.php?page=$i'>$i</a></li>";
                                    } else {
                                        if($i==1)
                                            echo "<li class='active'><a href='dogadjaji.php?page=$i'>$i</a></li>";
                                         else
                                            echo "<li><a href='dogadjaji.php?page=$i'>$i</a></li>";
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
                                 } else {
                                     echo '<li class="disabled"><a><i class="fa fa-angle-right"></i></a></li>';
                                 }    
                                 $dbConnect=NULL;             
                       
                              
                           echo '   </ul>
                                    </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    </div><!-- ./paginacija -->';
                                                    }
                                                } else {
                                                    echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA DOGAĐAJA</h1></div><div class='col-md-4'></div>";
                                                }
                                                    ?>

                            	


                                    <?php 
                                    if(isset($_GET[base64_url_encode("delete")])){

                                        $UrlDelete = "php/control_events.php?".base64_url_encode("eventID")."=".$_GET[base64_url_encode("eventID")] . "&".base64_url_encode("action")."=".base64_url_encode("delete"). "&".base64_url_encode("delete")."=".base64_url_encode("yes");

                                       echo ' <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                        <a href="" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                        
                                                        <a class="btn btn-primary" href="'.$UrlDelete.'">Obriši</a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                        ';

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
if(isset($_GET[base64_url_encode("delete")])){
  echo "<script>$('#myModal').modal('show');</script>";
}
 ?>