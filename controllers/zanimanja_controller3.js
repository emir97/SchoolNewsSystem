app.controller('zanimanja', function($http, $scope) {
	    $http.get("response/fetch_zanimanja.php?trajanje=3%20Godine").success(function(response) {
	        $scope.smjerovi = response.zanimanja;
	        $scope.trajanje = response.trajanje;
	    });
	});