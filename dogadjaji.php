<?php include 'core/connection.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Događaji</title>
    <?php 
    include 'includes/SEO.php';
    include 'includes/css_js.php';
     ?>
     <script>
app.controller('events', function($http, $scope) {
    $http.get("response/fetch_events.php").success(function(response) {
        $scope.events = response.events;
    });
});
</script>
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
    <!-- This one in here is responsive menu for tablet and mobiles -->
    
   <?php include('view/header_mobile.php'); ?>


   <?php include('view/header.php'); ?>
    
    
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Događaji</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8" id="eventsRow">
                <div class="row">

                    <div class="col-md-12 eventContent" ng-controller="events">
                        <?php $connectToDb = connectOnDb(); ?>
                        <div class="list-event-item" ng-repeat="event in events">
                            <div class="box-content-inner clearfix">
                                <div class="list-event-thumb">
                                    <a ng-href="{{event.URL}}">
                                        <img ng-src="./images/events/{{event.Image}}" alt="{{event.Image}}" />
                                    </a>
                                </div>
                                <div class="list-event-header">
                                    <span class="event-place small-text"><i class="fa fa-globe"></i>{{event.Place}}</span>
                                    <span class="event-date small-text"><i class="fa fa-calendar-o"></i>{{event.startDate}}</span>
                                    <div class="view-details"><a ng-href="{{event.URL}}" class="lightBtn">Detaljnije</a></div>
                                </div>
                                <h5 class="event-title title-event"><a ng-href="{{event.URL}}">{{event.Title}}</a></h5>
                                <p class="event-content">{{event.Content}}</p>
                            </div> <!-- /.box-content-inner -->
                        </div> <!-- /.list-event-item -->
                        
                       
                    </div> <!-- /.col-md-12 -->

                </div> <!-- /.row -->
                   <div class="showbox loader">
                            <div class="loader">
                                <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                                </svg>
                            </div>
               </div>

            </div> <!-- /.col-md-8 -->

            <!-- Here begin Sidebar -->
            <div class="col-md-4">

                 <?php include 'widgets/obavijesti_widget.php'; ?>
                 <?php include 'widgets/dogadjaji_widget.php'; ?>
                 <?php include 'widgets/galerija_widget.php'; ?>

            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

    <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>
    <script>
        var load = 4;
        $('.loader').hide();
        function yHandler(){
        
            
            var wrap = document.getElementById('eventsRow');
            var contentHeight = wrap.offsetHeight + 450;
            var yOffset = window.pageYOffset; 
            var y = yOffset + window.innerHeight;
            if(y >= contentHeight){
                $('.loader').show();
                load = load + 2;
                $.post("ajax_load_events.php",{load:load}, function(data){
                    $('.eventContent').append(data);
                });
                $('.loader').hide();
            }
        }
window.onscroll = yHandler;
</script>
</body>
</html>