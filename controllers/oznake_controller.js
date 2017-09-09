app.controller('oznake', function($scope, $http) {
   $http.get('response/fetch_keywords.php').success(function(response){
     $scope.keywords1 = response.keywords1;
     $scope.keywords2 = response.keywords2;
   });
});