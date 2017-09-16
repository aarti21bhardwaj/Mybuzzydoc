//Creating the Patient Factory for access to other controllers
dashboardApp.factory('Patient', ['$http', 'apiHost', 'Vendor', '$window',function patientFactory($http, apiHost, Vendor, $window){
	//Returning an object
		var obj ={};

			obj.data = false;
			obj.activityHistory =  false;
			obj.details = false;
			obj.lastMilestoneAchieved = false;
			obj.giftCoupons = false;
			obj.referrals = false;
			obj.referralTier = false;
			obj.referralsCount = false;
			obj.historyTab = false;
			obj.redeemTab = false;
			obj.oldBuzzyHistory = false;
			obj.instantRewardsStatus = false;
			obj.address = false;
			obj.allowRedemptions = false;
			obj.showOldHistory = false;
			obj.isRedirect = false;

		
		obj.init = function(){

			obj.data = false;
			obj.address = false;
			obj.activityHistory =  false;
			obj.details = false;
			obj.lastMilestoneAchieved = false;
			obj.giftCoupons = false;
			obj.referrals = false;
			obj.referralTier = false;
			obj.referralsCount = false;
			obj.historyTab = false;
			obj.redeemTab = false;
			obj.oldBuzzyHistory = false;
			obj.instantRewardsStatus = false;
			obj.allowRedemptions = false;
			obj.showOldHistory = false;
			obj.isRedirect = false;
		}

		obj.update = function(data) {
			obj.data = data;
		};

		obj.loadEverything = function(){

			obj.loadHistory();
			obj.loadPatientsDetails();
		}

		obj.loadHistory = function() {
			if(obj.data.id == false)
			{
				return false;
			}


			$http.get(apiHost+'api/LegacyRedemptions/getPatientActivity/'+obj.data.id)
				 .then(
					 	   function(response){

					 	   			obj.updateHistory(response.data.activityHistory);

					 	   },
					 	   function(response){
					 	   		console.log('in error case for loadHistory in Patient Factory');
					 	   		console.log(response);

					 	   }
				 	   );
		};

		obj.updateHistory = function(data) {
			obj.activityHistory = data;
			obj.updateTierData(data);
		};

		obj.updateTierData = function(data){
			if(typeof data.tiers[0] !== 'undefined' && typeof Vendor.data.tiers !== 'undefined')
			{	
				obj.data.tier = [];
				current_tier_id = data.tiers[data.tiers.length-1].tier_id;
				Vendor.data.tiers.map(function(item, index){
					if(item.id == current_tier_id) {
						obj.data.tier[0] = item;
					}
				});
			}else if(typeof data.tiers[0] == 'undefined' && typeof Vendor.data.tiers !== 'undefined'){

				obj.data.tier = [];
				obj.data.tier[0] = Vendor.data.tiers[0];
			
			}else{

				obj.data.tier = false;
			}

			console.log(obj.data);
		};

		obj.loadPatientsDetails = function(){
			$http.get(apiHost+'api/users/getPatientDetails/'+obj.data.id)
				 .then(
					 	   function(response){
					 	   		obj.updateDetails(response.data);
					 	   },
					 	   function(response){
					 	   		console.log('in error case for loadPatientsDetails in Patient Factory');

					 	   }
				 	   );
		};

		obj.updateDetails = function(data) {
			obj.details = data.responseOfPatientDetails.data;
			obj.lastMilestoneAchieved = data.lastMilestoneAchieved;
			obj.giftCoupons = data.giftCouponAwards;
			obj.referralTier = data.referralTiers;
			obj.address = data.address;
			obj.referrals = data.referrals;
			obj.instantRewardsStatus = data.instantRewardsStatus;
			obj.allowRedemptions = data.allowRedemptions;

			console.log('patientDetails');
			if(obj.referrals != false){
				obj.referralsCount = 0;
				obj.referrals.map(function(item, index){
					if(item.referral_lead != null && item.referral_lead.referral_status_id == 1) {
						obj.referralsCount += 1;
					}
				});
			}

			obj.canSeeOldHistory();
			
		};

		obj.canSeeOldHistory = function(){

			if(typeof obj.details != "undefined" && obj.details){

				if(typeof obj.details.oldBuzzyId != 'undefined' && obj.details.oldBuzzyId != null && obj.details.oldBuzzyId != ''){
					obj.showOldHistory = true;
				}

				if(obj.details.username.substr(0, 9) == 'anonymous'){
					obj.showOldHistory = true;
				}
			}
		}

		obj.getOldBuzzyHistory = function(){

			var oldHistoryReq = {

				patient_id: obj.data.id,
				patient_cards: obj.details.user_cards

			};

			return $http.post($window.host + 'api/Users/getOldBuzzyHistory', oldHistoryReq);
		}

		obj.getEmail = function(){


			if(typeof obj.data.email != "undefined" 
				   && obj.data.email != null 
				   && obj.data.email.length > 0){
				
				return obj.data.email;

			}else if(typeof obj.data.guardian_email != "undefined" 
				   && obj.data.guardian_email != null 
				   && obj.data.guardian_email.length > 0){

				return obj.data.guardian_email;
			
			}else{

				return false;
			}
		}

		obj.getPhone = function(){

			if(typeof obj.data.phone != "undefined" 
			   && obj.data.phone != null 
			   && obj.data.phone.length > 0){
			
				return obj.data.phone;
			}else{
				return false;
			}	

		}

		return obj;


}]);
