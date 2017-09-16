var reviewSettings = angular.module('reviewsetting', []);

reviewSettings.controller('SettingsController', function($scope, $http, $window){
	var config = {	
					headers:  {
        				'accept': 'application/json',
    				}
				};
	 
	$scope.points = new Object();
	$scope.points.vendor_id = $window.vendorId;
	$scope.dollarValue = 50;
	$http.get($window.host + 'api/vendors/'+$scope.points.vendor_id, config).then(function(response){
	 	$scope.points = response.data.vendor.review_settings[0];
	 	if($scope.points == null){
	 		$scope.points = new Object();
			$scope.points.vendor_id = $window.vendorId;
	 		$scope.points.review_points = 0;
	 		$scope.points.rating_points = 0;
	 		$scope.points.fb_points = 0;
	 		$scope.points.gplus_points = 0;
	 		$scope.points.ratemd_points = 0;
	 		$scope.points.yahoo_points = 0;
	 		$scope.points.yelp_points = 0;
	 		$scope.points.healthgrades_points = 0;
	 	}

	 	console.log(response.data.vendor.review_settings[0]);

	
	}, function(response){
		console.log('An error occured with the following response: ' + response);
	});

	this.saveChanges = function (){
		$http.post($window.urlUpdateReviewSettings, $scope.points, config).then(function(response){
			$scope.message = response.data.response.message;

			console.log('success ');
		}, function(response){
			console.log('error');
		})
	}

});