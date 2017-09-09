  <script>
app.controller('rand_galery', function($scope, $http) {
   $http.get('response/widgets.php').success(function(response){
     $scope.photos = response.photos;
   });
});
</script>
<div class="widget-main">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Galerija</h4>
                    </div>
                    <div class="widget-inner">
                        <div class="gallery-small-thumbs clearfix" ng-controller="rand_galery">
                            
                            <div class="thumb-small-gallery" ng-repeat="photo in photos">
                                <a class="fancybox" rel="gallery1" href="./images/gallery/{{photo.Photo}}" title="{{photo.Title}}">
                                    <img src="./images/gallery/{{photo.Photo}}" alt="{{photo.Photo}}" />
                                </a>
                            </div>
                        </div>
                        <!-- /.galler-small-thumbs -->
                    </div>
                    <!-- /.widget-inner -->
                </div>
                <!-- /.widget-main -->