dashboardApp.controller('TierController', function($window, $scope, $http, Patient, Vendor){
$scope.request = {};
$scope.pdata = Patient.data;
$scope.request.peoplehub_identifier = $scope.pdata.id;
$scope.awardButton = true;
$scope.setTiers = $window.host+"Tiers";

if(typeof $scope.pdata.name !== 'undefined' && $scope.pdata.name.length){
	$scope.request.first_name = $scope.pdata.name;
}


this.awardTierPoints = function(){

$scope.awardButton = false;
	if($scope.pdata.id){
		checkemail();
		$scope.request.patient_name = $scope.pdata.name;
		$scope.request.redeemersId = $scope.pdata.id;
		console.log($scope.request);
		$http.post($window.host + 'api/awards/tier', $scope.request).then(function(response){
		          	
		           swal("Congrats!", response.data.response.data.points+" points have been awarded.", "success");
		           console.log(response);
		           $scope.awardButton = true;
		           Patient.loadEverything();
		           

		        }, function(response){

		        	swal("Sorry", "There was some error in awarding points", "error");
					console.log(response); 
					$scope.awardButton = true;         
		});
	}else{

		swal("Not Registered!","Patient Not Registered, Kindly Register Patient", "error");
	}
	

}

function checkemail(){

	if(typeof $scope.pdata.email !== 'undefined' && $scope.pdata.email != null){

			$scope.request.attribute_type =  "email";
			$scope.request.attribute 		= $scope.pdata.email;
			return true;

		}else if(typeof $scope.pdata.phone != 'undefined' && $scope.pdata.phone != null){
			
			$scope.request.attribute_type =  "phone";
			$scope.request.attribute 		= $scope.pdata.phone;
			return true;

		}else if(typeof $scope.pdata.email == 'undefined' && typeof $scope.pdata.phone == 'undefined'){
			
			return false;
			

		}else if($scope.pdata.email == 'null' &&  $scope.pdata.phone == 'null'){

			return false;
		}
}

});