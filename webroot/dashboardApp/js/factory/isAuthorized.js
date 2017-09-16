//Create isAuthorized factory to include other factories 
dashboardApp.factory('isAuthorized', ['Plan', 'Settings', 'Patient', function isAuthorizedFactory(Plan, Settings, Patient){
//Returning an object
return {

	check : function(features) {
		var isAuthorized = false;
		// console.log(features);
		switch(features){
			case 'tier':

			if(Plan.activefeatures.tier && parseInt(Settings.settingfeatures['tier_program'])){
				isAuthorized = true;
			}
			break;

			case 'tierperks':
			if(parseInt(Settings.settingfeatures['tier_perks'])){
				isAuthorized = true;
			}
			break;

			case 'manualpoints':

			if(Plan.activefeatures.manualpoints && parseInt(Settings.settingfeatures['manual_points'])){
				isAuthorized = true;
			}
			break;

			case 'milestone':

			if(Plan.activefeatures.manualpoints && parseInt(Settings.settingfeatures['milestone'])){
				isAuthorized = true;
			}
			break;

			case 'review':
			if(Plan.activefeatures.review){
				isAuthorized = true;
			}
			break;

			case 'compliancesurvey':
			if(Plan.activefeatures.compliancesurvey){
				isAuthorized = true;
			}
			break;

			case 'giftCoupons':
			if(Plan.activefeatures.giftCoupons && parseInt(Settings.settingfeatures['gift_coupons'])){
				isAuthorized = true;
			}
			break;

			case 'instantRedeem':
			if(Plan.activefeatures.instantRedeem &&  parseInt(Settings.settingfeatures['instant_redeem'])){
				isAuthorized = true;
			}
			break;

			case 'promotions':
			if(Plan.activefeatures.promotions){
				isAuthorized = true;
			}
			break;

			case 'instantGiftCredit':
			if(Plan.activefeatures.instantGiftCredit &&  parseInt(Settings.settingfeatures['express_gifts'])){
				isAuthorized = true;
			}
			break;

			case 'awardPoints':
			if(Plan.activefeatures.awardPoints){
				isAuthorized = true;
			}
			break;

			case 'giftCards':
			if(Plan.activefeatures.giftCards){
				isAuthorized = true;
			}
			break;

			case 'emailMessage':
			if(Plan.activefeatures.emailMessage){
				isAuthorized = true;
			}
			break;

			case 'redeem':
			if(Plan.activefeatures.redeem){
				isAuthorized = true;
			}
			break;
			
			case 'inhouseredemption':
			if(Plan.activefeatures.inhouseredemption){
				isAuthorized = true;
			}
			break;
			case 'redeemwallet':
			if(Plan.activefeatures.redeemwallet){
				isAuthorized = true;
			}
			break;
			case 'creditType':
				isAuthorized = Settings.settingfeatures['credit_type'];
			break;
			
			case 'forperfectsurvey':
			
			if(Plan.activefeatures.compliancesurvey && parseInt(Settings.settingfeatures['award_points_for_perfect_survey'])){
				isAuthorized = true;				
			}
			break;
			case 'referrals':
			
			if(Plan.activefeatures.referral && parseInt(Settings.settingfeatures['referrals'])){
				isAuthorized = true;				
			}
			break;
			case 'productsServices':
			
			if(parseInt(Settings.settingfeatures['products_and_services'])){
				isAuthorized = true;				
			}
			break;
			case 'adminProducts':
			
			if(parseInt(Settings.settingfeatures['admin_products'])){
				isAuthorized = true;				
			}
			break;
			case 'instantRewards':
			if(parseInt(Settings.settingfeatures['instant_gift_coupons']) && Plan.activefeatures.instantreward){
				isAuthorized = true;	
			}
			break;
			case 'referralTiers':
			
			if(parseInt(Settings.settingfeatures['referral_tier_program'])){
				isAuthorized = true;				
			}
			break;
			case 'cards':

            if(parseInt(Settings.settingfeatures['cards'])){
                isAuthorized = true;                
            }
            break;
            case 'florist':

            if(parseInt(Settings.settingfeatures['florist_one'])){
                isAuthorized = true;                
            }
            break;
            case 'assessmentSurveys':

            if(parseInt(Settings.settingfeatures['assessment_surveys'])){
                isAuthorized = true;                
            }
            break;

			default:
				isAuthorized = false;

		}
		return isAuthorized;

	}

}

}]);