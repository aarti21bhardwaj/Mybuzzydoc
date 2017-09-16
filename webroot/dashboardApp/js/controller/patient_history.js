
dashboardApp.controller('PatientHistoryController', function($timeout,$window, $route,$scope,$http,Plan, Settings, isAuthorized,Patient, RewardProducts, Vendor){
	
	init();
	
	$scope.$watch(function(){
		
		return Patient.activityHistory.referral_awards;
		
	}, function(newValue, oldValue){

		$scope.phistory = Patient.activityHistory;
		if(Patient.activityHistory != false){

			$scope.referralAwards = {};
			$scope.phistory.referral_awards.map(function(item, index){
				$scope.referralAwards[item.referral_id] = item;			
			});
			
		}
	});

	$scope.$watch(function(){
		
		return Patient.referrals;
		
	}, function(newValue, oldValue){

		$scope.referrals = Patient.referrals;
		
	});

	$scope.$watch(function(){
		
		return Patient.details;
		
	}, function(newValue, oldValue){

		if($scope.auth('creditType') == 'wallet_credit'){

				if(Patient.details.totalWalletCredits == 0){
					$scope.isRollbackAvailable = false;
					$scope.$apply();
				}

			}else{

				if(Patient.details.totalStoreCredits == 0){
					$scope.isRollbackAvailable = false;
					$scope.$apply();
				}
			}
	});

	$scope.$watch(function(){
		return Patient.showOldHistory;
	}, function(newValue, oldValue) {
		$scope.showOldHistory = Patient.showOldHistory;
	});
	
	//which history tab should be opened
	$scope.$watch(function(){
		
		return Patient.historyTab;
		
	}, function(newValue, oldValue){

		if(newValue != false){
			$scope.tabSwitch(newValue);
			Patient.historyTab = false;
		}
		
	});
	
	function init(){

		$scope.pat = Patient.data;
		vendorData = Vendor.data;
		$scope.showSearchCount = false;
		$scope.awardReferralButton = false; 
		$scope.showOldHistory = false;
		$scope.referralSettings = {};
		$scope.phistory = Patient.activityHistory;
		$scope.plan = Plan.activefeatures;
		$scope.referrals = Patient.referrals; 
		$scope.referralLevels = vendorData.vendor_referral_settings; 
		$scope.referral_peoplehub_identifier = "";
		$scope.searchQuery = "";
		$scope.referralLeadId = "";
		$scope.isRollbackAvailable = true;
	}

	$scope.tabSwitch = function(x){
		
		var tabs = ['tab1', 'tab2', 'tab3', 'tab4', 'tab5', 'tab6', 'tab7', 'tab8', 'tab9','tab10'];
		for(key in tabs)
			$scope[tabs[key]] = false;
		$scope['tab'+x] = true;
		
	}

	this.awardReferralPoints = function(newPatientId){

		swal({
			title: "Are you sure?",
			text:"This cannot be undone.",
			type: "success",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			closeOnConfirm: true
		}, function (response) {     
			if(response){

				awardRefPoints(newPatientId);
				
			}else{

				delete $scope.referralSettings[$scope.referralLeadId];
			}

		});

	}

	function awardRefPoints(newPatientId){

		$scope.awardReferralButton = true; 
		referralAward = {

			referral_lead_id : $scope.referralLeadId * 1,
			vendor_referral_setting_id: $scope.referralSettings[$scope.referralLeadId] * 1,
			referral_peoplehub_identifier: newPatientId * 1
		}

		$http.post($window.host + 'api/awards/referral', referralAward).then(function(response){      	
			swal("Congrats!", "Points have been awarded.", "success");
			console.log(response);
			$scope.awardReferralButton = false;
			Patient.loadEverything();
			init();
			

		}, function(response){

			swal("Sorry", response.data.message, "error");
			console.log(response); 
			$scope.awardReferralButton = false;         
		});

		

	}

	this.search = function(){

		var data={
			'search-value' : $scope.searchQuery,
		}
		
		var url = $window.host+'api/users/searchPatient/'+$scope.searchQuery;
		
		$http.get(url).then(function(response){
			console.log(response);
			$scope.searchResults = response.data.data.users;
			console.log($scope.searchResults);
			$scope.showSearchCount = true;
		},function(response){
			console.log(response);
		});
	}

	this.getNewPatientId = function(referralLeadId){

		if($scope.referralSettings[referralLeadId] == $scope.referralLevels[$scope.referralLevels.length-1].id){

			$scope.awardReferralButton = true;
			
			$http.put($window.host + 'api/ReferralLeads/'+referralLeadId, {referral_status_id: 3}).then(function(response){      	
				swal("Status Updated", "", "success");
				console.log(response);
				$scope.awardReferralButton = false; 
				Patient.loadEverything();
				init();
				

			}, function(response){

				swal("Sorry", response.data.message, "error");
				console.log(response);
				$scope.awardReferralButton = false;         
			});

		}else{

			$scope.referralLeadId = referralLeadId;
			$('#referralModal').modal('show');
		}
	}

	this.isEmpty = function(obj){
		// console.log(obj);

		return Object.keys(obj).length  == 0;

	}

	this.auth = function(feature){
		return isAuthorized.check(feature);
	}

	this.deleteConfirmation = function(awardId, reason, awardType){
		if($scope.isRollbackAvailable){
			swal({
				title: "Are you sure?",
				text:"You want to delete "+awardType+"?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
	            cancelButtonText: "No, cancel!",
	            closeOnConfirm: false,
	            closeOnCancel: true
			}, function (response) {
				if(response){
					$scope.rollbackAwards(awardId, reason, awardType);
				}     
			});
		}else{
			swal("Sorry", "You don't have enough points to attempt rollback", "error");
		}		
	}

	$scope.promotionAwardToBeDeleted = function(awardId){

		for (x in $scope.phistory.promotions) {
			if($scope.phistory.promotions[x].id == awardId){
				var transactionId = $scope.phistory.promotions[x].peoplehub_transaction_id;
			}
		}

		var IdArray = [];
		for (x in $scope.phistory.promotions) {
			if($scope.phistory.promotions[x].peoplehub_transaction_id == transactionId){
				IdArray.push($scope.phistory.promotions[x].id);
			}
		}
		return IdArray;
	}

	$scope.rollbackAwards = function(awardId, reason, awardType){
		var IdArray = [];
		if(awardType == 'promotion_award'){
			IdArray = $scope.promotionAwardToBeDeleted(awardId);
		}else{
			IdArray = [awardId];
		}

		console.log(IdArray);
		if(IdArray.length > 1){
			swal({
					title: "Are you sure?",
					text:"The "+awardType+" you are trying to delete will lead to deletion of "+IdArray.length+" more "+awardType+"s. Are you sure you want to proceed ?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, proceed!",
		            cancelButtonText: "No, cancel!",
		            closeOnConfirm: true,
		            closeOnCancel: true
				}, function (response) {
					if(response){
						$scope.rollbackApiCall(IdArray, reason);
					}     
				});			
		}else{
			$scope.rollbackApiCall(IdArray, reason);
		}
	}

	$scope.rollbackApiCall = function(IdArray, reason){
		data = {
					"award_id" : IdArray,
					"award_type" : reason,
					"redeemers_id" : $scope.pat.id 
				};
		console.log(data);
		$http.post($window.host + 'api/awards/rollback', data).then(function(response){      	
			swal("Congrats!", "Award has been deleted.", "success");
			console.log(response);
		// 	$scope.awardReferralButton = false;
			Patient.loadEverything();
			init();
			

		}, function(response){

			swal("Sorry", response.data.message, "error");
			console.log(response); 
			// $scope.awardReferralButton = false;         
		});
	}


	this.editReferral = function(referralLeadId){
		console.log(referralLeadId);
	  	window.open($window.host+"referral-leads/edit/"+referralLeadId, '_blank');
	}

	this.refresh = function(){
		Patient.loadEverything();
		init();
	}

});