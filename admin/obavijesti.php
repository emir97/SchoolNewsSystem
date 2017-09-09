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
    
    <title>ETS Admin - Obavijesti</title>
   

    <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>
<script>
    function validateForm() {
    var postTitle = document.forms["updateNotify"]["notify_content"].value;
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
							title: 'GREŠKA U SADRŽAJU ',
							text: 'Sadržaj obavijesti mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravite ovu grešku i pokušajte ponovo.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    
    
    
}
    </script>
</head>

<body class="infobar-offcanvas">

    <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>
	   
	   <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">
                                <h1>Obavijesti</h1>
                            </div>
							 <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">




                                        <div class="container-fluid">

                                            <div class="row">
                                                <div class="col-md-12">
													<form action="" class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
														$urlNewNotify = $_SERVER['PHP_SELF'] . "?". base64_url_encode("operation") ."=".base64_url_encode("newNotify");
                                                         echo '
                                                         <a href="'.$urlNewNotify.'" class="btn btn-default">Dodaj Obavijest</a>
                                                         ';?>
                                                            
                                                        </div>
                                                    </form>
                                                   
                                                <div class="row" data-widget-group="group-demo">
                                                    <?php
                                                    $dbConnect = connectToDb();
                                                    $authorSession = $_SESSION['AUTHOR_USERID'];

                                                    if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                        $NumOfNoty = $dbConnect->query("SELECT * FROM notify");
                                                    } else {
                                                        $NumOfNoty = $dbConnect->query("SELECT * FROM notify WHERE author_id = '$authorSession'");
                                                    }
                                                    if($NumOfNoty->rowCount() > 0) {
                                                    $firstItem = 0;
                                                    for ($i=0; $i < 3; $i++) { 
                                                        # code...
                                                       
                                                                            echo '<div class="col-md-4">';

                                                       
                                                                            //konektovanje na bazu
                                                                            if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchNotifyData = "SELECT * FROM notify ORDER BY notify_id DESC LIMIT $firstItem, 2";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNotifyData);
																			} else {
                                                                                // upit za dohvatanje podataka iz tabele slider
                                                                                $queryForFetchNotifyData = "SELECT * FROM notify WHERE author_id = '$authorSession' ORDER BY notify_id DESC LIMIT $firstItem, 2";
                                                                                //dohvatanje podataka 
                                                                                $result = $dbConnect->query($queryForFetchNotifyData);
                                                                            }
                                                                            //prikaz podataka 
                                                                            foreach($result as $row){
                                                                                $notifyID = $row['notify_id']; 
                                                                                
                                                                                // uredjivanje prikaza sadrzaja
                                                                                $notifyConent = $row['notify_content'];
                                                                                
																				//url za brisanje obavijesti
																				$urlForDelete = $_SERVER['PHP_SELF'] . "?".base64_url_encode("notifyID") . "=" . base64_url_encode($notifyID) . "&".
																						base64_url_encode("operation") ."=".base64_url_encode("delete");
                                                                                        
                                                                                //url za izmjenu obavijesti
                                                                                $urlforUpdate = $_SERVER['PHP_SELF'] . "?" .base64_url_encode("notifyID") . "=" . base64_url_encode($notifyID) . "&".
																						base64_url_encode("operation") ."=".base64_url_encode("updateNotify");
                                                                                
                                                                                //datum
                                                                                $notifyDate = new DateTime($row['notify_date']);
                                                                                $notifyDate = $notifyDate->format('d.m.o.');
                                                                                
                                                                                $notifyAuthor = $row['author_id'];
                                                                                $queryForFetchAuthor = "SELECT * FROM authors WHERE author_id='$notifyAuthor' LIMIT 1";
                                                                                $authorResult = $dbConnect->query($queryForFetchAuthor);
                                                                                foreach($authorResult as $row){
                                                                                 $authorName = $row['author_name']; 
                                                                                 $authorSurname = $row['author_surname'];     
                                                                                
                                                                                echo '
                                                                                
                                                                                <div class="panel panel-midnightblue" data-widget='."'".'{"id" : "wiget6", "draggable": "false"}'."'".'>
                                                                                <div class="panel-heading">
                                                                                
                                                                                <h2><i class="fa fa-info-circle"></i> ID Obavijesti: '.$notifyID.'</h2>
                                                                                <div class="panel-ctrls">
                                                                                    
                                                                                    <a href="'.$urlforUpdate.'" class="button-icon" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Izmijeni">
                                                                                    <i class="fa fa-pencil"></i>
                                                                                    </a>
                                                                                     <a href="../index.php" target="_blank" class="button-icon"  data-toggle="tooltip" data-placement="right" title="Pogledaj obavijest"><i class="fa fa-eye"></i></a>
                                                                                    <a href="'.$urlForDelete.'" class="button-icon"  data-toggle="tooltip" data-placement="right" title="Obriši obavijest"><i class="fa fa-trash-o"></i></a>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <p><div class="notify-content"> '.$notifyConent.'</div></p>
                                                                                <div class="panel-footer">
                                                                                    <span class="text-gray">'.$notifyDate."&nbsp;&nbsp;|&nbsp;&nbsp;".$authorName." ". $authorSurname.'</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                                ';
                                                                                
                                                                            
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        $firstItem = $firstItem + 2;
                                                                        }
                                                                    }
                                                                    else {
                                                                        echo "<div class='col-md-4'></div><div class='col-md-4'><h1>NEMA OBAVIJESTI</h1></div><div class='col-md-4'></div>";
                                                                    }
                                                                        $dbConnect = NULL;
                                                                        ?>
                                            

                                            </div>

                                                    </div>
                                                </div>
												 </div>
												  </div>
										<?php 
											if(isset($_GET[base64_url_encode("operation")])){
												$operation = base64_url_decode($_GET[base64_url_encode("operation")]);
												if($operation == "delete"){
													$modalUrl = $_SERVER['REQUEST_URI'].'&'.base64_url_encode("requestDelete") .'='.base64_url_encode('YES');
													
												echo '
														<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">BRISANJE OBAVIJESTI</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Da li ste sigurni da želite obrisati ovu obavijest...??
                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="'.$_SERVER['PHP_SELF'].'" class="btn btn-default" data-dismiss="modal">Zatvori</a>
                                                         
                                                        <a class="btn btn-primary" href="'.$modalUrl.'">Obriši</a>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
													';
												} else if($operation == "newNotify") {
                                                    $connect = connectToDb();
                                                    $numberOfNotify = $connect->query("SELECT * FROM notify");
                                                    if($numberOfNotify->rowCount() < 6){
													$urlNewNotify = "php/obavijesti_operacije.php" ."?". base64_url_encode("notifyOperation") . "=" . base64_url_encode("newnotify");
													echo '
														<div class="modal fade" id="newNotifyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="exampleModalLabel">DODAJ NOVU OBAVIJEST</h4>
																</div>
                                                                <form name="updateNotify" role="form" data-toggle="validator" action="'.$urlNewNotify.'" method="post" onsubmit="return validateForm()">
																<div class="modal-body">
																	
																	<div class="form-group">
																		<label for="message-text" class="control-label">Sadržaj Obavijesti:</label>
																		<textarea name="notify_content" class="form-control" id="message-text" placeholder="Sadržaj obavijesti..."></textarea>
																	</div>
																	
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
																	<input type="submit" class="btn btn-primary" value="Objavi">
																</div>
                                                                </form>
																</div>
															</div>
															</div>
													';
                                                    } else {
                                                        echo '
														<div class="modal fade" id="newNotifyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h2 class="modal-title">GREŠKA U OBAVIJESTIMA</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        Maksimalan broj obavijesti može iznosti 6 (šest). Trenutni broj obavijesti je veći od 6. Prijavite se kao administrator pa obrišite jednu obavijest da bi mogli novu objaviti.
                                        
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
													';
                                                    $connect = NULL;
                                                    }
												} else if($operation = "updateNotify"){
                                                    while(isset($_GET[base64_url_encode("notifyID")])){
                                                    $connect = connectToDb();
                                                    $updateNotify = $connect->prepare("SELECT * FROM notify WHERE notify_id = :id LIMIT 1");
                                                    $updateNotify->bindParam(":id", base64_url_decode($_GET[base64_url_encode("notifyID")]));
                                                    $updateNotify->execute();
                                                    foreach($updateNotify as $row){
                                                        $ID = $row['notify_id'];
                                                        $content = $row['notify_content'];
                                                        $urlNewNotify = "php/obavijesti_operacije.php" ."?". base64_url_encode("notifyOperation") . "=" . base64_url_encode("update") . "&" . base64_url_encode("notifyID") . "=" . base64_url_encode($ID);
													echo '
														<div class="modal fade" id="editNotifyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="exampleModalLabel">IZMJENA OBAVIJESTI</h4>
																</div>
                                                                <form role="form" data-toggle="validator" action="'.$urlNewNotify.'" method="post" name="updateNotify" onsubmit="return validateForm()">
																<div class="modal-body">
																	<div class="form-group">
																		<label for="message-text" class="control-label">Sadržaj Obavijesti:</label>
																		<textarea name="notify_content" class="form-control" id="message-text" placeholder="Sadržaj obavijesti...">'.$content.'</textarea>
																	</div>
																	
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
																	<input type="submit" class="btn btn-primary" value="Objavi">
																</div>
                                                                </form>
																</div>
															</div>
															</div>
													';
                                                    }
                                                    
                                                    break;
                                                    }
                                                }
											}
										?>

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
                    <script type="text/javascript" src="assets/plugins/pines-notify/pnotify.min.js"></script> 
                    <!-- Initialize scripts for this page-->

                    <!-- End loading page level scripts-->
                     <script type="text/javascript" src="assets/plugins/wijets/wijets.js"></script>                                  <!-- Wijet -->

<script>
    $.wijets().make();
</script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip('show')
})
</script>
</body>

</html>
<?php
	//brisanje vijesti
    $decodeUrlVar = base64_url_encode("notifyID");
    if(isset($_GET[$decodeUrlVar])){
        if(isset($_GET[base64_url_encode("operation")])){
		if(base64_url_decode($_GET[base64_url_encode("operation")]) == "delete"){
            echo "<script>$('#myModal').modal('show')</script>";
		} 
		
        if(isset($_GET[base64_url_encode("requestDelete")]))
        try{
        $connections = connectToDb();
        $ID = base64_url_decode($_GET[base64_url_encode("notifyID")]);
        $deleteNotifyData = "DELETE FROM notify WHERE notify_id=:ID";
        $stmt = $connections->prepare($deleteNotifyData);
        $stmt->bindParam(":ID", $ID);
        $isExecute = $stmt->execute(); 
        if($isExecute){
            $connections = NULL;
            header('Location: obavijesti.php'); 
            exit();
        } else{
            $connections = NULL;
            header('Location: 500.php'); 
            exit();
        }
        }
      catch(PDOException $e){
            echo $e->getMessage();
            exit();
        }
        $connections = NULL;
		}
   }
   
   //unos  obavijesti
   if(isset($_GET[base64_url_encode("operation")])){
   if(base64_url_decode($_GET[base64_url_encode("operation")]) == "newNotify"){
		echo "<script>$('#newNotifyModal').modal('show')</script>";
	} else if(base64_url_decode($_GET[base64_url_encode("operation")]) == "updateNotify"){
        echo "<script>$('#editNotifyModal').modal('show')</script>";
    }
   }

?>