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
    
    <title>ETS Admin - Potvrda autora</title>
   

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
                                <h1>Potvrda autora</h1>
                                <div class="options">

                                </div>
                            </div>
                            <div class="container-fluid">


                                <div data-widget-group="group1">

                                    <div class="row">




                                        <div class="container-fluid">

                                            <div class="row">
                                                <div class="col-md-12">
                                                        
                                                                    
                                                                        <?php
                                                                            //konektovanje na bazu
                                                                            $dbConnect = connectToDb();
                                                                            $fetchAuthors = $dbConnect->query("SELECT * FROM authors_register WHERE author_register_email_active = '1'");
                                                                            if($fetchAuthors->rowCount() > 0) {


                                                                                echo '<div class="panel"><div class="panel-body panel-no-padding">
                                                                                      <table class="table table-striped table-bordered">
                                                                                          <thead>
                                                                                              <tr>
                                                                                                  <th>Email</th>
                                                                                                  <th>Ime i Prezime</th>
                                                                                                  <th width="240"></th>
                                                                                              </tr>
                                                                                          </thead>
                                                                                          <tbody>';
                                                                              foreach ($fetchAuthors as $key => $value) {
                                                                                $ID = $value['author_register_id'];
                                                                                $authorEmail = $value['author_register_email'];
                                                                                $authorName = $value['author_register_name'];
                                                                                $authorSurname = $value['author_register_surname'];
                                                                                
                                                                                // prihvatanje autora
                                                                                $acceptForAutor = "php/admin_author_settings.php?".base64_url_encode("addForAuthor")."=".base64_url_encode("true");
                                                                                $acceptForAutor .= "&".base64_url_encode("ID")."=".base64_url_encode($ID);
                                                                                // odbijanje autora
                                                                                $doNotAcceptForAuthor = "php/admin_author_settings.php?".base64_url_encode("addForAuthor")."=".base64_url_encode("false");;
                                                                                $doNotAcceptForAuthor .= "&".base64_url_encode("ID")."=".base64_url_encode($ID);
                                                                                
                                                                                echo "
                                                                                <tr>
                                                                                <td><div class='titleView'>$authorEmail</div></td>
                                                                                <td>$authorName $authorSurname</td>
                                                                                ";
                                                                                print("
                                                                                <td>
                                                                                <div class='btn-group'>
                                                                                    <button type='button' class='btn btn-midnightblue-alt dropdown-toggle' data-toggle='dropdown'>
                                                                                     <i class='fa fa-cog fa-spin'></i> Postavke autora <span class='caret'></span>
                                                                                    </button>
                                                                                    <ul class='dropdown-menu' role='menu'>
                                                                                        <li>
                                                                                       <a href='".$acceptForAutor."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-unlock'></i>
                                                                                            Prihvati kao autora
                                                                                        </a>
                                                                                        </li>
                                                                                        <li>
                                                                                        <a href='".$doNotAcceptForAuthor."' id='bootbox-demo-3'>
                                                                                            <i class='fa fa-lock'></i>
                                                                                            Ne sada
                                                                                        </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                    </div>
                                                                                
                                                                                </td>
                                                                                </tr>
                                                                                ");
                                                                                
                                                                            }
                                                                            
                                                                            echo " </tbody></table></div>";
                                                                            echo '</div></div>';
                                                                            } else {
                                                                              echo "<div class='col-md-4'></div><div class='col-md-4'><h3>NEMA PRIJAVLJENIH AUTORA</h3></div><div class='col-md-4'></div>";
                                                                            }
                                                                        ?>
                                                   

                                        </div>
                                        <!-- .container-fluid -->

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