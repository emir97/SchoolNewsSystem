app.controller("sekcije", function($http, $scope){
	$http.get("response/fetch_sekcije.php").success(function(response){
		$scope.sekcije = response.sekcije;
	});
});