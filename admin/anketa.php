<?php 
include('core/connect.php'); 
include('functions/secure.php');
include('functions/text_editing.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>ETS Admin - Anketa</title>
   

    <?php 
    include 'includes/meta_tags.php';
    include 'includes/include_css_js.php'; 
    ?>
    <script src="js/provjera_ankete.js"></script>
    </head>

<body class="infobar-offcanvas">

    <?php include_once 'includes/header.php'; ?>
       <?php include 'includes/menu.php';?>
	   
	   <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <div class="page-heading">
                                <h1>Ankete</h1>
                            </div>
							
                                          
                                                  <div class="form-inline mb10">
                                                        <div class="form-group">
                                                            
                                                        <?php
                                                         echo '
                                                         <a href="anketa.php?'.base64_url_encode("addNewPoll")."=".base64_url_encode("addPoll").'" class="btn btn-default">Dodaj Anketu</a>
                                                         ';?>
                                                           
                                                        </div><br><br>
                                                        <div class="row" data-widget-group="group-demo">
                                                        <?php 
                                                            //konekcija na bazu
                                                            $connect = connectToDb();
                                                            //dohvatanje anketa
                                                            $fetchPoll = $connect->query("SELECT * FROM ankete_pitanja");
                                                            if($fetchPoll->rowCount() > 0){
                                                                while ($r=$fetchPoll->fetch(PDO::FETCH_OBJ)) {

                                                                    //dohvatanje podataka ankete
                                                                    $pollID = $r->anketa_pitanje_id;
                                                                    $pollTitle = $r->anketa_pitanje_naslov;
                                                                    $pollContent = $r->anketa_pitanje_content;
                                                                    $pollIsActive = $r->is_active;
                                                                    $isActive = "";
                                                                    if($pollIsActive == "1"){
                                                                        $isActive = "checked";
                                                                    }

                                                                    $urlForDelete = "php/add_poll.php?".base64_url_encode("action")."=".base64_url_encode("delete");
                                                                    $urlForDelete .= "&".base64_url_encode("ID")."=".base64_url_encode($pollID);
                                                                    $urlForDelete .= "&".base64_url_encode("Title")."=".base64_url_encode($pollTitle);

                                                                    $urlForDispayOnIndex = "php/add_poll.php?".base64_url_encode("action")."=".base64_url_encode("activePoll");
                                                                    $urlForDispayOnIndex .= "&".base64_url_encode("ID")."=".base64_url_encode($pollID);

                                                                     echo '
                                                                            <div class="col-md-6"><div class="panel panel-midnightblue">
                                                                                <div class="panel-heading">
                                                                                    <h2><i class="fa fa-male"></i>'.$pollTitle.'</h2>
                                                                                    <div class="panel-ctrls">
                                                                                    <a href="'.$urlForDispayOnIndex.'"><input class="bootstrap-switch" '.$isActive.' type="checkbox" data-size="small" data-on-color="success" data-off-color="default"></a>
                                                                                    <a href="'.$urlForDelete.'" class="button-icon" title="Obriši anketu"><i class="fa fa-trash-o"></i></a>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                                <div class="panel-body" >
                                                                                    <p><b> '.$pollContent.'</b></p>';
                                                                                        //dohvatanje odgovora
                                                                                        $fetchAnswers = $connect->query("SELECT * FROM ankete_odgovori WHERE anketa_pitanje_id = '$pollID'");
                                                                                       //ukupno glasova
                                                                                       $totalAnswer = $connect->query("SELECT SUM(anketa_odg_num_votes) AS maxAnswers FROM ankete_odgovori WHERE anketa_pitanje_id = '$pollID'");
                                                                                       $row = $totalAnswer->fetch(PDO::FETCH_ASSOC);

                                                                                       $maxAnswers = $row['maxAnswers'];
                                                                                       if($maxAnswers == 0){
                                                                                            $maxAnswers = 1;
                                                                                       }

                                                                                        if($fetchAnswers->rowCount() > 0){
                                                                                            while ($anw = $fetchAnswers->fetch(PDO::FETCH_OBJ)) {
                                                                                                $answerContent = $anw->anketa_odg_content;
                                                                                                $numOdAnswers = $anw->anketa_odg_num_votes;
                                                                                                $precent = ($numOdAnswers / $maxAnswers) * 100;
                                                                                                $precent = round($precent, 2);

                                                                                                echo '<div class="contextual-progress">
                                                                                                            <div class="clearfix">
                                                                                                                <div class="progress-title">'.$answerContent.'</div>
                                                                                                                <div class="progress-percentage">'.$precent.' %</div>
                                                                                                            </div>
                                                                                                            <div class="progress">
                                                                                                                <div class="progress-bar progress-bar-info" style="width: '.$precent.'%"></div>
                                                                                                            </div>
                                                                                                        </div>';
                                                                                            }
                                                                                        }
                                                                     echo ' </div>
                                                                            </div>
                                                                           </div>
                                                                        ';
                                                                }
                                                            } else {
                                                                echo "<div class='col-md-4'></div><div class='col-md-4'>NEMA ANKETA</div><div class='col-md-4'></div>";
                                                            }


                                                        ?>
                                                      
                                                    </div>
                                                    </div>
                                                

                                          <?php 
                                            if(isset($_GET[base64_url_encode("addNewPoll")])) {
                                                if(base64_url_decode($_GET[base64_url_encode("addNewPoll")]) == "addPoll"){
                                                    $urlPoll = "anketa.php?".base64_url_encode("addNewPoll")."=".base64_url_encode("addAnswers");
                                                    echo '
                                                                <div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form name="addPoll" role="form" action="'.$urlPoll.'" method="get" onsubmit="return validateForm()">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">NOVA ANKETA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="message-text" class="control-label">Naziv Ankete: </label>
                                                                                    <textarea  name="title" class="form-control" id="message-text" placeholder="Naziv ..."></textarea>
                                                                                            </div>
                                                                              <div class="form-group"><label for="content" class="control-label">Pitanje: </label> 
                                                                              <textarea name="question" class="form-control" id="message-text" placeholder="Ptanje ..."></textarea></div>
                                                                              <div class="form-group"><label for="content" class="control-label">Broj ponuđenih odgovora: </label> 
                                                                               <input type="number" min="2" max="9" class="form-control" name="answerNumber"></div>
                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                               
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Objavi">           
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->';
                                                               
                                                }
                                            }
                                            if(isset($_GET['title'])){
                                                $urlForAddPoll = "php/add_poll.php?".base64_url_encode("action")."=".base64_url_encode("insert");
                                                    echo '
                                                                <div class="modal fade" id="modalIzdvoji" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                        <form role="form" action="'.$urlForAddPoll.'" method="post">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                <h2 class="modal-title">NOVA ANKETA</h2>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="message-text" class="control-label">Naziv Ankete: </label>
                                                                                    <input type="text" class="form-control" name="title" readonly="readonly" value="'.$_GET['title'].'">
                                                                                            </div>
                                                                              <div class="form-group"><label for="content" class="control-label">Pitanje: </label> 
                                                                              <input type="text" class="form-control" name="question" readonly="readonly" value="'.$_GET['question'].'">
                                                                              </div>';
                                                                                $num = $_GET['answerNumber'];
                                                                                for ($i=1; $i <= $num; $i++) { 
                                                                                    echo '<div class="form-group"><label for="content" class="control-label">Odgovor '.$i.': </label> 
                                                                               <input required type="text" class="form-control" name="answer_'.$i.'"></div>';
                                                                                }
                                                                              echo '
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button class="btn btn-default" data-dismiss="modal">Zatvori</button>
                                                                                <input type="submit" class="btn btn-primary" value="Objavi">           
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->';
                                                               
                                                
                                            }
                                            
                                          ?>

                            <!-- #page-content -->
                        
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
                    <script type="text/javascript" src="assets/plugins/wijets/wijets.js"></script>     
                    <script type="text/javascript" src="assets/plugins/switchery/switchery.js"></script>                               <!-- Wijet -->
                    <script type="text/javascript" src="assets/plugins/form-inputmask/jquery.inputmask.bundle.min.js"></script>     <!-- Input Masks Plugin -->
                    <script type="text/javascript" src="assets/demo/demo-mask.js"></script>

                    <!-- Initialize scripts for this page-->

                    <!-- End loading page level scripts-->

</body>

</html>
<?php 
if(isset($_GET[base64_url_encode("addNewPoll")])) {
if(base64_url_decode($_GET[base64_url_encode("addNewPoll")]) == "addPoll"){
    echo "<script>$('#modalIzdvoji').modal('show');</script>";
}}
if(isset($_GET['title'])){
     echo "<script>$('#modalIzdvoji').modal('show');</script>";
 }
 ?>