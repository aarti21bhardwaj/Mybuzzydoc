dashboardApp.controller('ManualController', function($window, $scope, $http, Patient, Vendor){

$scope.request = {};
$scope.pdata = Patient.data;
$scope.vendor = Vendor.data;
$scope.staffId = Vendor.loggedInUserId;
$scope.manualAwardButton = false;
$scope.manualUserId = '';


this.awardManualPoints = function(){


//$scope.awardButton = false;

	swal({
                title: "Are you sure you want to award "+$scope.request.points+" points?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function () {     
                awardPoints();
            });

}

function awardPoints(){
	if($scope.pdata.id){
		$scope.manualAwardButton = true;
		$scope.request.user_id = $scope.manualUserId;
		$scope.request.redeemersId = $scope.pdata.id;
		console.log($scope.request);
		$http.post($window.host + 'api/awards/manual', $scope.request).then(function(response){      	
		           swal({
		           			title: "Congrats!", 
		           			text: response.data.response.data.points+" points have been awarded.", 
		           			type: "success", 
		   					// closeOnConfirm: true
		   				// }, function(){
							// Patient.checkForInstantRewards();	   					
		   				});
		           console.log(response);
		           $scope.manualAwardButton = false;
		           $scope.request = {};
		           Patient.loadEverything();
		           // var phone = '';
		           // var email = '';

		           // if($scope.pdata.phone != "undefined" && $scope.pdata.phone.length)
		           // 	phone = $scope.pdata.phone;

		           // if($scope.pdata.email != "undefined" && $scope.pdata.email.length)
		           // 	email = $scope.pdata.email;


		           // var dataForInstantRewards = {
		           // 								  "id" : $scope.pdata.id,
									    //        	  "email" : $scope.pdata.email,
									    //        	  "phone" : $scope.pdata.phone	
									    //        };

	        }, function(response){

	        	swal("Sorry", response.data.message, "error");
				console.log(response); 
				$scope.manualAwardButton = false;         
	        });


		} else {
		swal("Not Registered!","Patient Not Registered, Kindly Register Patient", "error");
	}
}

// function checkForInstantRewards(){

// 	   $http.get($window.host+'api/awards/instantRewards/'+$scope.pdata.id).then(function(response){
// 	   		console.log('in awardPoints');
// 	   		if(response.data.isInstantRewardUnlocked == true){
// 	   			swal("Congrats", "You have unlocked instant rewards.", "success");
// 	   		}else{
	   			
// 	   			console.log(response);
// 	   			swal({	title: "",
// 	   					text: "To unlock instant rewards you need to: <br><span class='text-center'>Earn "+parseInt(response.data.remainingPoints)+" more points, or</span><br><span class='text-center'>Spend $"+parseInt(response.data.remainingAmount)+".</span><br><br><span class='text-center'>Time Remaining is :"+response.data.remainingTime+"</span>", 
// 	   					type: "info", 
// 	   					html: true

// 	   				});
//    			}
// 	   		console.log(response);
// 	   }, function(response){
// 	   		console.log('in awardPoints');
// 	   		console.log(response);
// 	   });

// }
// function checkemail(){

// 	if(typeof $scope.pdata.email !== 'undefined' && $scope.pdata.email != null){

// 			$scope.request.attribute_type =  "email";
// 			$scope.request.attribute 		= $scope.pdata.email;
// 			return true;

// 		}else if(typeof $scope.pdata.phone != 'undefined' && $scope.pdata.phone != null){
			
// 			$scope.request.attribute_type =  "phone";
// 			$scope.request.attribute 		= $scope.pdata.phone;
// 			return true;

// 		}else if(typeof $scope.pdata.email == 'undefined' && typeof $scope.pdata.phone == 'undefined'){
			
// 			return false;
			
// 		}else if($scope.pdata.email == 'null' && $scope.pdata.phone == 'null'){

// 			return false;
// 		}
// }

});