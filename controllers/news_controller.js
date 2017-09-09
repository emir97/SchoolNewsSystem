app.controller('news', function($http, $scope) {
    $http.get("response/fetch_news.php").success(function(response) {
        $scope.news = response.news;
    });
});