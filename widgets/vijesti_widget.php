<script>
app.controller('izdvojenoNews', function($scope, $http) {
   $http.get('response/widgets.php').success(function(response){
     $scope.newsIzdvojeno = response.newsIzdvojeno;
   });
});
app.controller('mostReadNews', function($scope, $http) {
   $http.get('response/widgets.php').success(function(response){
     $scope.newsMostRead = response.newsMostRead;
   });
});
app.controller('popularNews', function($scope, $http) {
   $http.get('response/widgets.php').success(function(response){
     $scope.newsPopular = response.newsPopular;
   });
});
</script>
<div class="widget-item" style="padding:0;" >
                    <div class="request-information">
                        <!-- Nav tabs -->
                        <ul id="tabs" class="nav nav-tabs nav-news-tabs" data-tabs="tabs">
                            <li class="active"><a href="#section-1" data-toggle="tab">Izdvojeno</a></li>
                            <li><a href="#section-2" data-toggle="tab">Najčitanije</a></li>
                            <li><a href="#section-3" data-toggle="tab">Popularno</a></li>
                        </ul>
                        <div id="my-tab-content" class="tab-content">
                            <div class="tab-pane fade in active" id="section-1" ng-controller="izdvojenoNews">
                                               <div class="blog-list-post clearfix" ng-repeat="news in newsIzdvojeno">
                                                <div class="blog-list-thumb">
                                                    <a ng-href="{{news.URL}}"><img ng-src="images/news/{{news.Image}}" alt="{{news.Image}}" /></a>
                                                </div>
                                                <div class="blog-list-details">
                                                    <div class="news-list-title"><h5 class="blog-list-title"><a ng-href="{{news.URL}}">{{news.Title}}</a></h5></div>
                                                    <p class="blog-list-meta small-text"><span>{{news.Date}}</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span>{{news.Author}}</span></p>
                                                </div>
                                               </div>   <!-- /.news-most-read -->
                                   
                            </div>
                            <div class="tab-pane fade" id="section-2" ng-controller="mostReadNews">
                                
                                               <div class="blog-list-post clearfix" ng-repeat="mostread in newsMostRead">
                                                <div class="blog-list-thumb">
                                                    <a ng-href="{{mostread.URL}}"><img ng-src="images/news/{{mostread.Image}}" alt="{{mostread.Image}}" /></a>
                                                </div>
                                                <div class="blog-list-details">
                                                    <div class="news-list-title"><h5 class="blog-list-title"><a ng-href="{{mostread.URL}}">{{mostread.Title}}</a></h5></div>
                                                    <p class="blog-list-meta small-text"><span>{{mostread.Date}}</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span>{{mostread.Author}}</span></p>
                                                </div>
                                               </div>   <!-- /.news-most-read -->
                                          
                            </div>
                    <div class="tab-pane fade in" id="section-3" ng-controller="popularNews">
                                
                                               <div class="blog-list-post clearfix" ng-repeat="popular in newsPopular">
                                                <div class="blog-list-thumb">
                                                    <a ng-href="{{popular.URL}}"><img ng-src="images/news/{{popular.Image}}" alt="{{popular.Image}}" /></a>
                                                </div>
                                                <div class="blog-list-details">
                                                    <div class="news-list-title"><h5 class="blog-list-title"><a ng-href="{{popular.URL}}">{{popular.Title}}</a></h5></div>
                                                    <p class="blog-list-meta small-text"><span>{{popular.Date}}</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span>{{popular.Author}}</span></p>
                                                </div>
                                               </div>   <!-- /.news-most-read -->
                            </div>
                        </div>
                    </div>
                    <!-- /.request-information -->
                </div>
                <!-- /.widget-item -->