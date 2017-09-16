//Creating the Plan Factory for access to other controllers
dashboardApp.factory('Plan', [function planFactory(){
	//Returning an object
	return {

		planfeatures:{},
		activefeatures:{},


		 
		 update: function(pfeatures) {
		  	
		  	for(ft in pfeatures){
		  		this.planfeatures[pfeatures[ft].feature.name] = true;
		  	}
		  	
		  	if(this.planfeatures.promotions){
		  		this.activefeatures.awardPoints = true;
		  		this.activefeatures.giftCards = true;
		  		this.activefeatures.promotions = true;

		  	}else{
		  		this.activefeatures.emailMessage = true;
		  	}

		  	if(this.planfeatures.review)
		  		this.activefeatures.review = true;

		  	if(this.planfeatures.instantcredit)
		  		this.activefeatures.instantGiftCredit = true;
		  	
		  	if(this.planfeatures.instantredemption)
		  		this.activefeatures.instantRedeem = true;
		  	
		  	if(this.planfeatures.inhouseredemption)
		  		this.activefeatures.inhouseredemption = true;
		  	
		  	if(this.planfeatures.redeemwallet)
		  		this.activefeatures.redeemwallet = true;
		  	
		  	if(this.planfeatures.giftcoupons)
		  		this.activefeatures.giftCoupons = true;

		  	if(this.planfeatures.compliancesurvey)
		  		this.activefeatures.compliancesurvey = true;

		  	if(this.planfeatures.manualpoints)
		  		this.activefeatures.manualpoints = true;

		  	if(this.planfeatures.tier)
		  		this.activefeatures.tier = true;

		  	if(this.planfeatures.referral)
		  		this.activefeatures.referral = true;

		  	if(this.planfeatures.instantreward)
		  		this.activefeatures.instantreward = true;
		}
		
	}
}]);