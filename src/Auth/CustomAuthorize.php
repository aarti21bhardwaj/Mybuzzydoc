<?php
namespace App\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Utility\Inflector;

class CustomAuthorize extends BaseAuthorize{
	// protected $_settings;

	// protected $_defaultConfig = [];

	public function authorize($user, Request $request){

		//setting request parameters
		$this->reqController = $request->params['controller'];
		$this->reqAction = $request->params['action'];
		$this->reqPass = $request->params['pass'];
		$userRole = $user['role']['label'];
		if($userRole != 'Admin'){

			//Authorize based on plan
			if(!$this->_authorizePlan($user['plan']['plan']['plan_features'])){
	
				return false;
			}

			//Authorize based on vendor settings
			if(!$this->_authorizeVendorSettings($user['vendor_id'])){
				return false;
			}
		}


		//Check if user allowed to acces the resource based on his role
		if(!$this->_checkRoleAccess($userRole)){
			
			return false;
		} 

		
		//if acessing a record then check ownership
		if(isset($this->reqPass[0]) && is_numeric($this->reqPass[0]) && $userRole != 'Admin'){

			if($this->_checkExemptedLocations()){

				return true;				
			}

			
			if(!$this->_checkOwnerShip($user['vendor_id'], $this->reqPass[0])){
				return false;
			}
		}
		return true;
	}

	//method to check wether current controller & action matches some list of un authorized controllers 
	private function _checkUnAuthorized($unAuthorizedLocations){

		if(isset($unAuthorizedLocations[$this->reqController])){

			if($unAuthorizedLocations[$this->reqController][0] == 'all' || in_array($this->reqAction, $unAuthorizedLocations[$this->reqController])){
				
				return false;
			}
		}	

		return true;

	}

	//method to get unauthorized locations for current vendor according to the set plan 
	private function _authorizePlan($planFeatures){

		$features = [];
		foreach ($planFeatures as $key => $value) {
				$features[] = $value['feature']['name'];
		}

		$unAuthorizedLocations = $this->_getUnAuthorizedPlanLocations($features);

		if(!$unAuthorizedLocations){
			return true;
		}

		return $this->_checkUnAuthorized($unAuthorizedLocations);


	}

	//method to get unauthorized locations for current vendor according to the set vendor settings
	private function _authorizeVendorSettings($vendorId){

		$vendorSettingsModel = TableRegistry::get('VendorSettings');

		$vendorSettings = $vendorSettingsModel->findByVendorId($vendorId)->contain(['SettingKeys'])->all()->toArray();
		if($vendorSettings){
			$settings = [];
			$falseSettings = [];

			foreach ($vendorSettings as $key => $value) {

    				$settings[$value['setting_key']['name']] = $value['value'];
			}	
		}

		$unAuthorizedLocations = $this->_getUnAuthorizedSettingLocations($settings);

		if(!$unAuthorizedLocations){
			return true;
		}

		return $this->_checkUnAuthorized($unAuthorizedLocations);
	}

	////method to get unauthorized locations for current user according to his/her role
	private function _checkRoleAccess($role){

		$unAuthorizedLocations = $this->_getUnAuthorizedRoleLocations($role);
		return $this->_checkUnAuthorized($unAuthorizedLocations); 	

	}

	//method to get unauthorized locations for current user based on the resource ownership of the vendor
	private function _checkOwnerShip($vendorId, $entityId){
		$target = 'Vendors';
		$associationRoute = $this->_getAssociationRoute($this->reqController, $target, [], []);

		if(!$associationRoute){
			return true;
		}

		// $associationRoute = $this->_getAssociationRoute('Features', $target, [], []);
		if ($associationRoute == $target) {
			
			if($vendorId == $entityId){
				return true;
			}
			return false;

		}elseif ($associationRoute[0] == $target && count($associationRoute) == 1) {
			
			$direct = true;
			$tableObject = TableRegistry::get($this->reqController);
			$associations = $tableObject->associations();
			$foreignKey = $associations->get($target)->foreignKey();
			$entity = $tableObject->findById($entityId)->where([$foreignKey => $vendorId])->first();


		} else{
			
			$direct = false;
			$pathToModel = $this->_decorateRoute($associationRoute);
			$tableObject = TableRegistry::get($this->reqController);
			$entity = $tableObject->findById($entityId)->contain([$pathToModel => function($q) use ($vendorId, $target){
				return $q->where([$target.'.id' => $vendorId]);
			}])->first();
		}

		//Check Vendor OwnerShip
		if(isset($entity) && $entity){
			return true;
		}
		// return false;
		unset($entity);
		//Check Super Admin OwnerShip
		$superAdmins = $this->_getSuperAdmins();
		if ($direct) {

			$tableObject = TableRegistry::get($this->reqController);
			$associations = $tableObject->associations();
			$foreignKey = $associations->get($target)->foreignKey();
			$entity = $tableObject->findById($entityId)->where([$foreignKey.' IN' => $superAdmins])->all();

		} else{

			$pathToModel = $this->_decorateRoute($associationRoute);
			$tableObject = TableRegistry::get($this->reqController);
			$entity = $tableObject->findById($entityId)->contain([$pathToModel => function($q) use ($superAdmins, $target){
				return $q->where([$target.'.id IN' => $superAdmins]);
			}])->all();
		}

		if(isset($entity) && count($entity)){
			
			return $this->_superAdminOwnerShipRules();
		}
			
		return false;
	}

	private function _decorateRoute($associations) {
		// $associations = array_reverse($associations);

		$associationPath = implode('.', array_reverse($associations));
		
		////For PHP 7.0 only
		// $associations = array_merge_recursive(...array_reverse($associations));
		////For getting association types
		// $pathToTragetId = '';
		// foreach ($associations['className'] as $key => $value) {

		// 	switch ($associations['associationType'][$key]) {
		// 		case 'oneToOne':
		// 		case 'manyToOne':
		// 			$pathToTragetId = $pathToTragetId.'->'.Inflector::underscore(Inflector::singularize($value));
		// 			break;
				
		// 		case 'oneToMany':
		// 		case 'ManyToMany':

		// 			$pathToTragetId = $pathToTragetId.'->'.Inflector::underscore($value).'[0]';
		// 			// pr($pathToTragetId);die;
		// 			break;				
		// 		default:
		// 			break;
		// 	}
		// }
		return $associationPath;
	}

	//Calculates an association route between source and target model
	private function _getAssociationRoute($source, $target, $route, $exclude){

		if($source == $target) {
			return $target;
		}

		$tableObject = TableRegistry::get($source);
		// pr($tableObject);die;
		$entityClass = $tableObject->entityClass();
		// pr($entityClass);die;
		$entityClass = (new \ReflectionClass($entityClass))->getShortName();
		//Check if Model Exists or not ($entityClass will equal to 'Entity if no model exists')
		if($entityClass == 'Entity'){
			return false;
		}

		$associations = $tableObject->associations();
		unset($tableObject);
		// pr($associations);die;
		$array = $associations->keys();
		// pr($array);die;
		$source = strtolower($source);
		$target = strtolower($target);
    
		if(!$array){
			return false;
		}

		if(in_array($target, $array)){
			$route[] = $associations->get($target)->name();
			return $route;
		}else{
        	
			$exclude[] = $source;
			foreach ($array as $key => $value) {

				if(!in_array($value, $exclude)){
					$value = $associations->get($value)->name();
					$check = $this->_getAssociationRoute($value, $target, $route, $exclude);
					if($check != false){
						$check[] = $value;
						return $check;
					}
				}
			}
			return false;

		}
	}


	private function _getSuperAdmins(){

		$tableObject = TableRegistry::get('Users');
		$superAdmins = $tableObject->find()->contain(['Roles' => function($q){

			return $q->where(['label' => 'Admin']);

		}])->all()->extract('id')->toArray();
		

		return $superAdmins;

	}

	private function _superAdminOwnerShipRules(){
		
		//list of super admin owned unAuthorized Locations
		$unAuthorizedLocations = [

			'Users' => ['view', 'edit'],
			'LegacyRewards' => ['edit'],
			'GiftCoupons' => ['edit'],
			'Tiers' => ['edit'],
			'TierPerks' => ['edit'],
			'ReferralTiers' => ['edit'],
			'ReferralTierPerks' => ['edit'],
			'VendorLocations' => ['view', 'edit'],
			'ReferralTemplates' => ['view', 'edit'],
			'VendorReferralSettings' => ['view', 'edit'],

		];
		return $this->_checkUnAuthorized($unAuthorizedLocations);
	}





	//List of unauthorized locations according to plan features. 
	private function _getUnAuthorizedPlanLocations($features){

		//Array of unAuthorized locations if a feature is not present in the plan.
		$featUnAuthLocations = [

			'email' => [

				'VendorEmailSettings' => ['all']
			],
			'instantcredit' => [
				'LegacyRedemptions' => ['instantGiftCredit']
			],
			'instantredemption' => [

				'LegacyRedemptions' => ['instantRedemption']
			],
			'promotions' => [
				'VendorPromotions' => ['all'],
				'Awards' => ['promotions']
			],
			'referral' => [ 
				'Awards' => ['referral'],
				'Referrals' => ['all'],
				'ReferralLeads' => ['all'],
				'ReferralTemplates' => ['all'],
				'ReferralTiers' => ['all'],
				'ReferralTierPerks' => ['all'],
				'VendorReferralSettings' => ['all']
			],
			'review' => [
				'ReviewRequestStatuses' => ['all'],
				'VendorReviews' => ['all'],
				'ReviewSettings' => ['all']
			],
			'compliancesurvey' => [

				'ComplianceSurvey' => ['all'],
				'VendorMilestones' => ['all'],
				'VendorSurveyQuestions' => ['all'],
			], 
			'patienthistory' => [],
			'staffhistory' => [
				'Reports' => ['all']
			],
			'manualpoints' => [
				'Awards' => ['manual']
			], 
			'giftcoupons' => [
				'Awards' => ['giftCoupon'],
				'GiftCoupons' => ['all']
			],
			'tier' => [
				'Tiers' => ['all'],
				'TierPerks' => ['all'],
				'Awards' => ['tier'],
			],
			'inhouseredemption' => [],
			'redeemwallet' => [],
			'instantreward' => [
				'Awards' => ['redeemInstantRewards'],
			]
		]; 

		$unAuthorizedLocations = [];

		foreach ($featUnAuthLocations as $key => $value) {

			if(!in_array($key, $features) && !empty($value)){

				$unAuthorizedLocations = array_merge_recursive($unAuthorizedLocations, $value);
			}
		}

		if(!empty($unAuthorizedLocations)){

			return $unAuthorizedLocations;
		}

		return false;
	}

	//List of unauthorized locations according to vendor settings 
	private function _getUnAuthorizedSettingLocations($settings){

		//Array of unAuthorized locations if a feature is not active in Vendor Settings.
		$settingsUnAuthLocations = [ 

			'Tier Program' => [
				'Tiers' => ['all'],
				'TierPerks' => ['all'],
				'Awards' => ['tier'],
			],

			'Tier Perks' => [
				'TierPerks' => ['all']
			],
			'Manual Points Award Limit' => [],

			'Credit Type' => [],

			'Instant Credit Award Limit' => [],

			'Maximum Points Award Limit' => [],

			'Live Mode' => [],

			'Milestone' => [

				'VendorMilestones' => ['all']
			],

			'Award Points For Perfect Survey' => [],

			'Manual Points' => [

				'Awards' => ['giftCoupon'],
			],

			'Custom Emails' => [

				'VendorEmailSettings' => ['all']

			],

			'Gift Coupons' => [

				'Awards' => ['giftCoupon'],
				'GiftCoupons' => ['all']

			],

			'Referrals' => [

				'Awards' => ['referral'],
				'Referrals' => ['all'],
				'ReferralLeads' => ['all'],
				'ReferralTemplates' => ['all'],
				'ReferralTiers' => ['all'],
				'ReferralTierPerks' => ['all'],
				'VendorReferralSettings' => ['all']

			],

			'Documents' => [

				'VendorDocuments' => ['all']

			],

			'Products And Services' => [

				'LegacyRewards' => ['all']
			],

			'Admin Products' => [],

			'Patient Self Sign Up' => [
				'Reports' => ['selfSignUp']
			],

			'Florist One' => [],


			'Referral Tier Program' => [
				'ReferralTiers' => ['all'],
				'ReferralTierPerks' => ['all']
			],

			'Instant Gift Coupons' => [
				'Awards' => ['redeemInstantRewards'],
			],

			'Cards' => [
				'VendorCardRequests' => ['all'],
				'VendorCards' => ['all']
			],

			'Training Videos' => [
				'TrainingVideos' => ['all'],
			],

			'Chat' => [],

			'Instant Redeem' => [
				'LegacyRedemptions' => ['instantRedemption']
			],

			'Express Gifts' => [
				'LegacyRedemptions' => ['instantGiftCredit']
			],

			'Assessment Surveys' => [
				'AssessmentSurveyInstances' => ['all']
			],


		];


		$unAuthorizedLocations = [];

		foreach($settings as $key => $value) {

			if($value == 0 && !empty($settingsUnAuthLocations[$key])){

				$unAuthorizedLocations = array_merge_recursive($unAuthorizedLocations, $settingsUnAuthLocations[$key]);
			}
		}
		// pr($unAuthorizedLocations);die;
		if(!empty($unAuthorizedLocations)){

			return $unAuthorizedLocations;
		}

		return false;

	}

	//List of unauthorized locations according to user role 
	private function _getUnAuthorizedRoleLocations($role){
		$unAuthorizedLocations = [

			'Admin' => [


									'Users' => ['dashboard'],									
									'Awards' => ['all'],
									'VendorFloristSettings' => ['all'],
									'VendorFloristOrders' => ['all'],
									'AssessmentSurveyInstances' => ['all'],
			],
			'Staff Admin' => [


									'Events' => ['add', 'edit', 'delete', 'index'],									
									'Templates' => ['add', 'edit', 'view', 'delete'],
									'VendorSettings' => ['all'], 									
									'EmailSettings' => ['add', 'edit', 'index'], 									
									'EventVariables' => ['all'], 													
									'Features' => ['all'],
									'LegacyRedemptions' => ['index', 'add', 'edit', 'delete'],						
									'PlanFeatures' => ['add', 'edit', 'delete'],									
									'Plans' => ['add', 'edit', 'delete'],									
									'Promotions' => ['edit','delete','index'],
									'Questions' => ['all'],
									'Roles' => ['all'],									
									'Settings' => ['all'],								
									'SurveyQuestions' => ['all'],
									'VendorSurveys' => ['add', 'edit'],
									'VendorSurveyQuestions' => ['add', 'view'],
									'Surveys' => ['add', 'edit', 'delete'],
									'TrainingVideos' => ['add', 'edit', 'delete'],
									'Vendors' => ['add', 'delete'],
									'VendorInstantGiftCouponSettings' => ['index'],
									'VendorFloristSettings' => ['index', 'edit', 'view', 'delete'],
									'VendorFloristOrders' => ['add', 'edit', 'view', 'delete'],
									'AssessmentSurveys' => ['all'],
									'AssessmentSurveyQuestions' => ['all'],
									'VendorAssessmentSurveys' => ['all'],



			],

			'Staff Manager' => [
									'AuthorizeNetProfiles' => ['all'],
									'CreditCardCharges' => ['all'],
									'EmailSettings' => ['add', 'edit', 'index'],
									'EventVariables' => ['all'],
									'Events' => ['all'],
									'Features' => ['all'],
									'EmailSettings' => ['add', 'edit', 'delete'],
									'GiftCoupons' => ['add', 'edit', 'delete'],
									'LegacyRedemptions' => ['index', 'add', 'edit', 'delete'],
									'LegacyRewards' => ['add', 'edit', 'delete'],
									'PlanFeatures' => ['add', 'edit', 'delete'],
									'Plans' => ['add', 'edit', 'delete'],
									'Promotions' => ['all'],
									'Questions' => ['all'],
									'Reports' => ['add', 'edit', 'delete', 'view', 'index', 'redemptions', 'selfSignUp'],
									'ReferralTemplates' => ['add', 'edit', 'delete'],
									'ReferralTierPerks' => ['add', 'edit', 'delete'],
									'ReferralTiers' => ['add', 'edit', 'delete'],
									'ReviewRequestStatuses' => ['index'],
									'ReviewSettings' => ['add', 'edit', 'delete', 'updatePoints'],
									'Roles' => ['all'],
									'Settings' => ['all'],
									'SurveyQuestions' => ['all'],
									'Surveys' => ['add', 'edit', 'delete'],
									'Templates' => ['all'],
									'TierPerks' => ['add', 'edit', 'delete'],
									'Tiers' => ['add', 'edit', 'delete'],
									'TrainingVideos' => ['add', 'edit', 'delete'],
									'Users' => ['add', 'delete'],
									'VendorDocuments' => ['add', 'edit', 'delete'],
									'VendorCardRequests' => ['add', 'edit', 'delete'],
									'VendorCards' => ['add', 'edit', 'delete'],
									'VendorCards' => ['add', 'delete'],
									'VendorEmailSettings' => ['add', 'edit', 'delete'],
									'VendorLocations' => ['add', 'edit', 'index','delete'],
									'VendorMilestones' => ['add', 'edit', 'delete'],
									'VendorPromotions' => ['add', 'edit', 'delete'],
									'VendorRedeemedPointsController' => ['add', 'edit', 'delete'],
									'VendorReferralSettings' => ['add', 'edit', 'delete'],
									'VendorSettings' => ['all'], 
									'VendorSurveyQuestions' => ['add','view', 'edit', 'delete'],
									'VendorSurveys' => ['add', 'edit', 'delete'],
									'Vendors' => ['add', 'edit', 'delete', 'setUpWizard'],
									'VendorInstantGiftCouponSettings' => ['index', 'add', 'edit', 'view'],
									'VendorFloristSettings' => ['all'],
									'VendorFloristOrders' => ['all'],
									'AssessmentSurveys' => ['all'],
									'AssessmentSurveyQuestions' => ['all'],
									'VendorAssessmentSurveys' => ['all'],

		  	],
		];

		return $unAuthorizedLocations[$role];
	}

	//Resources exempted from ownership checks
	private function _checkExemptedLocations(){

		$allowedLocations = [

			'Users' => ['editPatientProfile', 'getPatientDetails', 'searchPatient', 'deletePatient'],
			'Awards' => ['all'],
			'LegacyRedemptions' => ['getPatientActivity'],
			'ComplianceSurvey' => ['getResponses'],
			'ReviewRequestStatuses' => ['getPatientReviewInfo'],
			'VendorMilestones' => ['getRewardTypes'],
			'TrainingVideos' => ['all'],
			'GiftCoupons' => ['getVendorsCoupons'],
			'FloristOne' => ['all'],
			'VendorInstantGiftCouponSettings' => ['giftCoupons', 'add'],
			'Templates' => ['templateDetails'],
			'VendorEmailSettings'=> ['add'],
			'AssessmentSurveyInstances'=> ['getHistory'],
		];

		return !$this->_checkUnAuthorized($allowedLocations);
	}
}

?>
