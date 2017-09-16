dashboardApp.controller('ReferralController', function($window, $scope, $http, Patient, Vendor){

		pdata = Patient.data;
		$scope.submitBtn = false;
		$scope.numRegex = "\\d+";
		$scope.preferredCallingTime = [

			{'time':'10AM - 12 Noon'},
			{'time':'2PM - 4PM'},
			{'time':'4PM - 6PM'},
			{'time':'6PM - 8PM'},
			{'time':'Not Known'}
                                  
		];
		
		init();

		function init(){

			$scope.referralRequest = {};
			$scope.adult = true;
		}
	
		this.addLead = function(){

			$scope.referralRequest.peoplehub_identifier = pdata.id;
			checkEmail();
			$scope.submitBtn = true;
			
			$http.post($window.host + 'api/referrals/dashboardAdd', $scope.referralRequest).then(
				
				function(response){      	
		           
		           swal("Success!", response.data.response.message, "success");
		           init();
		           Patient.loadEverything();
		           $scope.submitBtn = false;
				   updateVendor();

		        }, function(response){
         
		        	swal("Error", response.data.message, "error");
					console.log("Error in adding referral")
					console.log(response); 
					$scope.submitBtn = false;
	        	}

	        );
		}

		this.adultChange = function(){
			console.log('here');
			if($scope.adult == true){
				delete $scope.referralRequest.parent_name;
			}
		}

		function checkEmail(){

			if(typeof pdata.email != "undefined" 
			   && pdata.email != null 
			   && pdata.email.length > 0){

				$scope.referralRequest.refer_from = pdata.email;


			}else if(typeof pdata.guardian_email != "undefined" 
				   && pdata.guardian_email != null 
				   && pdata.guardian_email.length > 0){

				$scope.referralRequest.refer_from = pdata.guardian_email;

			}else{

				console.log("No email is set for the patiet.");
			}


		}

		function updateVendor(){

			Vendor.loadData($window.vendorId).then(function(response){
				Vendor.update(response.data.vendor);
			},function(response){
				//error
			})
		}
		
});