dashboardApp.controller('PatientAddressController', function($window, $location, $scope, $http, Patient, SearchResults){
	
	$scope.pdata = Patient.data;
	$scope.numRegex = "\\d+";
	$scope.states = "";
	$scope.statesUrl = $window.host+"json/us_states_titlecase.json"
	$scope.addressRequest = {};
	if(typeof $scope.pdata.phone != 'undefined' && $scope.pdata.phone){
		$scope.addressRequest.phone = angular.copy($scope.pdata.phone);
	}
	$scope.saveBtn = true;

	$scope.$watch(function(){

		return Patient.address;

	}, function(newValue, oldValue) {

		if(typeof Patient.address != "undefined" && Patient.address != false){
		
			$scope.addressRequest = angular.copy(Patient.address);
			delete $scope.addressRequest.created;
			delete $scope.addressRequest.modified;
			delete $scope.addressRequest.id;
		}
	});

	this.saveAddress = function(){

		$scope.saveBtn = false;

		if(typeof $scope.addressRequest.patient_peoplehub_identifier == "undefined"){

			$scope.addressRequest.patient_peoplehub_identifier = $scope.pdata.id;
		}

		$scope.addressRequest.zipcode = parseInt($scope.addressRequest.zipcode);

		$http.post($window.host+'api/PatientAddresses/updateAddress', $scope.addressRequest).then(function(response){

			swal("Saved", "Patient Address Saved", "success");
			Patient.loadPatientsDetails();
			$scope.saveBtn = true;
			$location.path("/patient");

		}, function(response){
			
			swal({
				title: "Oops! Something didn't go well!",
				text: response.data.message,
				type: "warning",
			});
			console.log('In error case for save patient address.');
			console.log(response);
			$scope.saveBtn = true;
			
		});

	}

	$http.get($scope.statesUrl).success(function(data){
   		$scope.states = data;
	});

});