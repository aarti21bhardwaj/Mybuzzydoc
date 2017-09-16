dashboardApp.controller('MenuController', function($location, $scope, $http, $window, Patient, Plan, isAuthorized, Vendor){
	//Initial menu state

	$scope.showOldHistory = false;
	$scope.plan = Plan.activefeatures;
	$scope.pointBalance = 0;
	$scope.patientData = false;
	$scope.allowRedemptions = false;
	// $scope.isRollbackAvailable = true;
	$scope.instantButtonUrl = $window.host+"dashboardApp/views/instantNotify.html";

	
	$scope.startTour = function(){
		// alert('m here');
		var tour;
		tour = new Tour({
		  steps: $window.steps
		});
		// console.log('yes');
		// console.log($window.steps);
		tour.init();
		tour.restart();
	}
 

	$scope.$watch(function(){
		return Patient.allowRedemptions;
	}, function(newValue, oldValue) {

		$scope.allowRedemptions = Patient.allowRedemptions;
	});

	$scope.$watch(function(){
		return Patient.showOldHistory;
	}, function(newValue, oldValue) {
		$scope.showOldHistory = Patient.showOldHistory;
	});

	$scope.confirmDeletePatient = function(){

		swal({
		  title: "Are you sure you want to delete this patient?",
		  text: "You will not be able to revert this!",
		  type: "error",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes, delete it!",
		  closeOnConfirm: true
		},
		function(){
		  deletePatient();
		});
	}

	function deletePatient(){
		
		$http.delete($window.host+"api/users/deletePatient/"+$scope.patientData.id).then(function(response){

			swal("Success!", "Patient has been deleted", "success");
			Patient.init();
			$location.path('/');

		}, function(response){

			swal("Error!", response.data.message , "error");
			console.log("Error in deleting the patient");
			console.log(response);
		});		
	}
	// $scope.$watch(function(){
	//    return Patient.data;
	// }, function(newValue, oldValue){
	// 	//Logic to display or hide the menu
	// 	if(Patient.data == false) {
	// 		$scope.showMenu = false;
	// 	} else {
	// 		$scope.showMenu = true;
	// 	}

	//     console.log(newValue + '<->' + oldValue);
	//     console.log(Patient);
	// });

	$scope.sendFlowerValidation = function(){
		
		Vendor.loadData($window.vendorId).then(

			function(response)
				{
				//success
				console.log(response.data.vendor);

				Vendor.update(response.data.vendor);
					if(!Patient.address || Patient.address == null){
						swal({
			                title: "No patient address",
			                text: "There is no address for this patient.",
			                type: "warning",
			                showCancelButton: false,
			                confirmButtonColor: "#DD6B55",
			                confirmButtonText: "Fill it up!",
			                closeOnConfirm: true
			            }, function () {
			                $location.path("/patientAddress");
					        $scope.$apply();
			            });
					}else if(typeof Vendor.data.vendor_florist_setting == 'undefined' || Vendor.data.vendor_florist_setting == null){
						console.log('yaha bhi');
						swal({
			                title: "No Default flower settings",
			                text: "You haven't set up the default settings for the flower orders. You have to set that first.",
			                type: "warning",
			                showCancelButton: false,
			                confirmButtonColor: "#DD6B55",
			                confirmButtonText: "Take me",
			                closeOnConfirm: true
			            }, function () {
			            	 window.open($window.host+"vendor-florist-settings/add", '_blank');
			                // $window.location.href = $window.host+"vendor-florist-settings/add";
			                $scope.$apply();
			            });
					}else{
						$location.path("/sendFlower");
					}
				},
			function(response){
				//error
				})

	}

	$scope.$watch(function(){
	
	   return $window.topNavSearch;
	
	}, function(newValue, oldValue){
		if($window.topNavSearch != ""){
			
			$location.path('/');
			
		}	
	});



	$scope.$watch(function(){
   		return Patient.instantRewardsStatus
	}, function(newValue, oldValue){
		console.log('in watch of instantRewardsStatus');
		console.log('new value is '+newValue);
		console.log('old value is '+oldValue);

		console.log($scope.auth('instantRewards'));
		
		if($scope.auth('instantRewards')){
			if(typeof newValue.isInstantRewardUnlocked != 'undefined' && newValue.isInstantRewardUnlocked == true && oldValue.isInstantRewardUnlocked == false){
	   			
	   			swal({
	   					title : "Congrats", 
	   					text : "You have unlocked instant rewards.", 
	   					type : "success",
	   					showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Go to Instant Rewards!",
			            cancelButtonText: "Cancel",
			            closeOnConfirm: true,
			            closeOnCancel: true
					 },  function(){
					 	$scope.redirectToInstantRewards();	
	   				});
	   		}else if(typeof newValue.isInstantRewardUnlocked != 'undefined' && newValue.isInstantRewardUnlocked == false){
	   			console.log('here');
				// console.log(response);
				console.log('nv2');
				var x = newValue;
				console.log(x);
				if(typeof x.remainingPoints !== 'undefined' && x && x.remainingPoints && typeof x.remainingAmount !== 'undefined' && x.remainingAmount && typeof x.remainingTime !== 'undefined'){
				console.log('in condition');
				swal({	
						title: "",
						text: "To unlock instant rewards you need to: <br><span class='text-center'>Earn "+parseInt(x.remainingPoints)+" more points, or</span><br><span class='text-center'>Spend $"+parseInt(x.remainingAmount)+".</span><br><br><span class='text-center'>Time Remaining is :"+x.remainingTime+"</span>", 
						type: "info", 
						html: true
					});
				}
			}
		}
	});

	$scope.$watchGroup([ function(){
	   return Patient.data;
	}, function(){
	   return Patient.activityHistory;

	}, function(){
		
		return Patient.details;
	
	},
	function(){
		
		return Patient.referrals;
	
	}], function(newValue, oldValue, scope){
		//corresponds to showMenu (first element of the first parameter) in the watchgroup 

		if(newValue[0] == false){
			$scope.showMenu = false;
		} else {
			$scope.showMenu = true;
			$scope.patientData = Patient.data;
		}

		if(newValue[1] == false) {
			$scope.showActivityHistory = false;
			$scope.sendWelcome = false;

		}else{
			
			for(key in newValue[1]){
				if(newValue[1][key].length > 0){
					$scope.showActivityHistory = true;
				}
			}

			if($scope.auth('creditType') == 'wallet_credit'){
				$scope.pointBalance = Patient.details.totalWalletCredits;
				// if(Patient.details.totalWalletCredits == 0){
				// 	$scope.isRollbackAvailable = false;
				// }
			}else{

				$scope.pointBalance = Patient.details.totalStoreCredits;
				// if(Patient.details.totalStoreCredits == 0){
				// 	$scope.isRollbackAvailable = false;
				// }
			}

		}

		if(newValue[2] == false){
			$scope.showWelcome = false;
		} else {

			if(typeof newValue[2].vendorPatient == "undefined")
				$scope.showWelcome = true;
			else
				$scope.showWelcome = false;
		}

		if(newValue[3].length > 0){
			$scope.showReferralHistory = true;
		} else {

			$scope.showReferralHistory = false;

		}

	});


	$scope.sendForgotPasswordLink = function(){

		forgotPasswordRequest = {

			name:Patient.data.name,
			email:getEmail(),
			username:Patient.data.username,
			ref: $window.host+"patient-portal/"+Vendor.data.id+"#/"
		};

		if(forgotPasswordRequest.email == false){

			swal("Error!", "Patient email is required", "error");
			return false;
		}

		$http.post($window.host+"api/users/patientForgotPassword", forgotPasswordRequest).then(function(response){

			swal("Success!", "Reset password link has been sent to "+forgotPasswordRequest.email , "success");
			console.log(response);

		}, function(response){

			swal("Error!", response.data.message , "error");
			console.log("Error in sending forgot password link");
			console.log(response);
		});

		

	}

	$scope.showInstantRewardsButton = function(){
		if($scope.auth('instantRewards') && Patient.instantRewardsStatus.isInstantRewardUnlocked){
			return true;
		}
		return false;
	}

	$scope.auth = function(feature){
		return isAuthorized.check(feature);
	}

	$scope.getClass = function (path) {
  		return ($location.path().substr(0, $location.path().length) === path) ? 'btn btn-primary' : 'btn btn-white';
	};

	$scope.clearPatientData = function(){

		Patient.init();
	}

	$scope.sendWelcomeEmail = function(){
		var data =	{

						name : Patient.data.name,
						patient_peoplehub_id : Patient.data.id,
						username:Patient.data.username
					};

		data.email = getEmail();

		if(data.email == false){

			swal('Error!','No email is set for the patient', 'error');
		}

		if(typeof Patient.data.phone != "undefined" 
			   && Patient.data.phone != null 
			   && Patient.data.phone.length > 0){
			
			data.phone = Patient.data.phone;
		}
		
		$http.post($window.host+"api/VendorPatients/add", data).then(function(response){

			swal("Welcome Email Sent", "" , "success");
			Patient.loadEverything();
			console.log(response);

		}, function(response){

			swal("Error in sending welcome email", "" , "error");
			console.log("Error in sending welcome email with response from server");
			console.log(response);
		});

	}

	$scope.redirectToInstantRewards = function(){
		console.log($location.path());
			console.log(Patient.redeemTab);

		if($location.path() == '/redeem'){
			
			Patient.redeemTab = 5;

		// $scope.$apply();
		}else{
			Patient.isRedirect = true;
			$location.path('/redeem');
			Patient.redeemTab = 5;
			$scope.$apply();
		}
	}

	function getEmail(){

		if(typeof Patient.data.email != "undefined" 
			   && Patient.data.email != null 
			   && Patient.data.email.length > 0){
			
			return Patient.data.email;

		}else if(typeof Patient.data.guardian_email != "undefined" 
			   && Patient.data.guardian_email != null 
			   && Patient.data.guardian_email.length > 0){

			return Patient.data.guardian_email;
		
		}else{

			return false;
		}

	}

});  
