dashboardApp.controller('ProfileController', function($window, $scope, $http, Patient){
	$scope.pdata = Patient.data;
});