<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Log\Log;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Core\Configure;
use Cake\Network\Session;
use Cake\Collection\Collection;
use Cake\Utility\Inflector;
use Cake\Event\Event;

/**
 * Legacy Redemptions Controller
 *
 * @property \App\Model\Table\LegacyRedemptionsTable $legacyRedemptions
 */
class LegacyRedemptionsController extends ApiController
{
	const SUPER_ADMIN_LABEL = 'admin';
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('VendorSettings');
		$loggedInUser = $this->Auth->user();
		if($loggedInUser['role']->name != self::SUPER_ADMIN_LABEL){
			$liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
			->contain(['SettingKeys' => function($q){
				return $q->where(['name' => 'Live Mode']);
			}
			])
			->first()->value;
			$this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
		}
	}

	public function add()
	{
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		$this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
		$this->request->data['user_id'] = $this->Auth->user('id');
    	$this->request->data['legacy_redemption_status_id'] = 3; // set to default on redemption

    	$legacyRedemption = $this->LegacyRedemptions->newEntity();

    	$legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data);
    	if ($this->LegacyRedemptions->save($legacyRedemption)) {
    		$this->set('legacyRedemption', $legacyRedemption);
    	} else {
    		throw new InternalErrorException(__('BAD_REQUEST'));
    	}
    	$this->set('_serialize', ['legacyRedemption']);
    }

    public function instantGiftCredit(){
    	if (!$this->request->is('post')) {
    		throw new MethodNotAllowedException(__('BAD_REQUEST'));
    	}

    	$session = $this->request->session();
		$cardSetup = $session->read('CardSetup');
		
		if($cardSetup == 0){

			throw new BadRequestException(__('SETUP_CARD'));

		}
		
    	$this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
    	$this->request->data['user_id'] = $this->Auth->user('id');
		$this->request->data['legacy_redemption_status_id'] = 3; 	//redemption status is always "redeemed" for instant gift credit

		//fetch the details of legacy reward being redeemed from db
		$this->loadModel('LegacyRewards');
		if(!$this->request->data['legacy_reward_id']){
			$legacyReward = $this->LegacyRewards->findByName('Custom Gift Card')->where(['vendor_id' => 1])->first();
			$this->request->data['legacy_reward_id'] = $legacyReward->id;
		}else{
			$legacyReward = $this->LegacyRewards->findById($this->request->data['legacy_reward_id'])->first();
		}
		if($legacyReward && $legacyReward->amount == 0){
			if(!$this->request->data['amount']){
				throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'amount'));
			}
			$amount = $this->request->data['amount'];
		}elseif($legacyReward && $legacyReward->amount){
			$amount = $legacyReward->amount;
		}elseif($legacyReward && !$legacyReward->amount){
			throw new BadRequestException(__('REWARD_ID_NOT_CORRESPOND'));
		}elseif(!$legacyReward){
			throw new BadRequestException(__('RECORD_NOT_FOUND_IN', 'LegacyRewards'));
		}else{
			throw new InternalErrorException(__('BAD_REQUEST'));
		}

		$instantRedemptionData = ['amount' => $amount, 'user_id' => $this->request->data['redeemer_peoplehub_identifier'], 'service' => $this->request->data['service'], 'description' => $legacyReward->name];
		// pr($instantRedemptionData);
		$legacyRedemptionId = $this->_rewardRedemption($this->request->data, $legacyReward, $instantRedemptionData);
		if($legacyRedemptionId){
			$this->set('response', ['status' => 'OK', 'data' => ['id' => $legacyRedemptionId]]);
		}

		$this->set('_serialize', ['response']);
	}

	public function instantRedemption(){
		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}

		$this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
		$this->request->data['user_id'] = $this->Auth->user('id');
		$this->request->data['legacy_redemption_status_id'] = 3; 	//redemption status is always "redeemed" for instant gift credit

	    	//fetch the details of legacy reward being redeemed using legacy_reward_id from db
		$this->loadModel('LegacyRewards');
		$legacyReward = $this->LegacyRewards->findById($this->request->data['legacy_reward_id'])
		->first();
		if(!$legacyReward){
			throw new NotFoundException(__('BAD_REQUEST'));
		}

	    	//convert points to amount
		$creditAmount = $legacyReward->points/50;
		$instantRedemptionData = ['amount' => $creditAmount, 'user_id' => $this->request->data['redeemer_peoplehub_identifier'], 'service' => 'in_house', 'description' => $legacyReward->name];

		$legacyRedemptionId = $this->_rewardRedemption($this->request->data, $legacyReward, $instantRedemptionData);
		if($legacyRedemptionId){
			$this->set('response', ['status' => 'OK', 'data' => ['id' => $legacyRedemptionId]]);
		}

		$this->set('_serialize', ['response']);
	}


	private function _rewardRedemption($legacyRedemptionData, $legacyReward, $instantRedemptionData){
		//hit people hub API for instant gift card redemption. On success:

		$instantRedemptionResponse = $this->PeopleHub->instantRedemption($instantRedemptionData, $this->Auth->user('vendor_peoplehub_id'));

		if(!$instantRedemptionResponse['status']){
			throw new BadRequestException('Some error occured'.json_encode($instantRedemptionResponse));
		}

		$instantRedemptionResponse = $instantRedemptionResponse['response'];
		$legacyRedemptionData['transaction_number'] = $instantRedemptionResponse->data->id;

		if($legacyReward->amount == 0){
			$associated = ['associated' => 'LegacyRedemptionAmounts'];
			$legacyRedemptionData['legacy_redemption_amounts'][] = ['amount' => $instantRedemptionData['amount']];
		}else{
			$associated = [];
		}
		$legacyRedemption = $this->LegacyRedemptions->newEntity($legacyRedemptionData, $associated);

		if($legacyRedemption->errors() || (isset($legacyRedemption->legacy_redemption_amounts[0]) && $legacyRedemption->legacy_redemption_amounts[0]->errors())){
			throw new BadRequestException(__("ENTITY_NOT_CORRECT"));
		}
		if($this->LegacyRedemptions->save($legacyRedemption)){
			return $legacyRedemption->id;
		}else{
			throw new InternalErrorException(__('COULD_NOT_SAVED'));
		}
	}
	/**
     * Edit method
     *
	 *This method edits one legacy redemption status.
     *
     * @param string|null $id Legacy Redemption id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exceptionhttp://localhost/buzzyadmin/api/legacyRedemptions/instantRedemption\MethodNotAllowedException When request is not valid.
     */

	public function edit($id = null){
		if(!$this->request->is(['put'])){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}

		$legacyRedemption = $this->LegacyRedemptions->findById($id)->first();
		if(!$legacyRedemption){
			throw new BadRequestException(__('ENTITY_DOES_NOT_EXISTS','Data'));
		}

		$legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data);
		//pr($legacyRedemption);
		$legacyRedemption = $this->LegacyRedemptions->save($legacyRedemption);
		if($legacyRedemption){
			$data =
			[
			'status' => true,
			'message'=> __('BULK_DATA_SAVED'),
			'data' => [
			'legacy_redemption_id' => $legacyRedemption->id,
			'legacy_redemption_status_id' => $legacyRedemption->legacy_redemption_status_id,
			]
			];

		}
		$this->set('response',$data);
		$this->set('_serialize', ['response']);
	}

	/**
	*BulkUpdate method
	*
	*This method edits legacy redemption status in bulk
	*
	* @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
    * @throws \Cake\Datasource\Exception\BadRequestException When request is invalid.
	*/
	public function bulkUpdate()
	{
		if(!$this->request->is(['put'])){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$this->request->data['redemption_id'] || !$this->request->data['legacy_redemption_status_id']) {
			throw new BadRequestException(__('BAD_REQUEST'));
		}
		$query = $this->LegacyRedemptions->updateAll(
					// change this to new value
			['legacy_redemption_status_id' => $this->request->data['legacy_redemption_status_id']],
				    ['id IN' => $this->request->data['redemption_id']] // array of ids to update
				    );

		if($query){
			$data = [
			'status' => true,
			'message'=> __('BULK_DATA_SAVED'),
			];
		}else {
			$data = [
			'status' => false,
			'message'=> __('BULK_DATA_ERROR'),
			];
		}
		$this->set('response',$data);
		$this->set('_serialize', ['response']);
	}

	public function getPatientActivity($id = null)
	{

		if(!$this->request->is(['get'])){
			throw new BadRequestException(__('BAD_REQUEST'));
		}
		if(!$this->request['id']){
			throw new BadRequestException(__('user id missing'));
		}

		$this->loadModel('Vendors');		
		$patientActivityHistory = $this->Vendors
									   ->findById($this->Auth->user('vendor_id'))
							   		   ->contain(['LegacyRedemptions' => function ($q) use ($id) {
														return $q->contain(['LegacyRewards', 'LegacyRedemptionAmounts'])
								 								 ->where(['redeemer_peoplehub_identifier ' => $id]);
													}, 'PromotionAwards'=> function ($q) use ($id) {
														return $q->contain(['VendorPromotions' =>function($r){
																			return $r->find('withTrashed')
																					 ->contain(['Promotions']);
																			}])
																 ->where(['redeemer_peoplehub_identifier' =>$id]);
													}, 'ReferralAwards'=> function ($q) use ($id) {
														return $q->where(['redeemer_peoplehub_identifier' =>$id])
																 ->contain(['Referrals.ReferralLeads.VendorReferralSettings']);
													}, 'ManualAwards'=> function ($q) use ($id) {
														return $q->where(['redeemer_peoplehub_identifier' =>$id]);
													},	'TierAwards'=> function ($q) use ($id) {
														return $q->contain(['Tiers'])
																 ->where(['redeemer_peoplehub_identifier' =>$id]);
													}, 'ReviewAwards'=> function ($q) use ($id) {
														return $q->contain(['ReviewTypes'])
														         ->where(['redeemer_peoplehub_identifier' =>$id]);
													},  'GiftCouponAwards'=> function ($q) use ($id) {
														return $q->contain(['GiftCouponRedemptions', 'GiftCoupons'])
																 ->where(['redeemer_peoplehub_identifier ' =>$id]);
													}, 'SurveyAwards' => function ($q) use ($id) {
														return $q->where(['redeemer_peoplehub_identifier' => $id]);
													}, 'ReferralTierAwards' => function ($q) use ($id) {
														return $q->contain(['ReferralTiers'])
																->where(['redeemer_peoplehub_identifier' => $id]);
													}
												])
							   		   ->first();

		 // pr($patientActivityHistory); die;
		$patientActivity['summary'] = array();
		$patientActivity['review_awards'] = array();
		$patientActivity['referral_awards'] = array();
		$patientActivity['promotions'] = array();
		$patientActivity['tiers'] = array();
		$patientActivity['manual_awards'] = array();
		$patientActivity['gift_coupon_awards'] = array();
		$patientActivity['compliance_survey_awards'] = array();
		$patientActivity['referral_tier_awards'] = array();
		

		if(empty($patientActivityHistory)){
			throw new BadRequestException(__('No Record Found'));
		}
		// pr($patientActivityHistory); die();
		if(!empty($patientActivityHistory->review_awards)) {
			$patientActivity['review_awards'] = $patientActivityHistory->review_awards;
		}
		if(!empty($patientActivityHistory->referral_awards)){
			$patientActivity['referral_awards'] = $patientActivityHistory->referral_awards;
		}
		if(!empty($patientActivityHistory->promotion_awards)){
			$patientActivity['promotions'] = $patientActivityHistory->promotion_awards;
		}
		if(!empty($patientActivityHistory->tier_awards)){
			$patientActivity['tiers'] = $patientActivityHistory->tier_awards;
		}
		if(!empty($patientActivityHistory->manual_awards)){
			$patientActivity['manual_awards'] = $patientActivityHistory->manual_awards;
		}
		if(!empty($patientActivityHistory->gift_coupon_awards)){
			$patientActivity['gift_coupon_awards'] = $patientActivityHistory->gift_coupon_awards;
		}
		if(!empty($patientActivityHistory->survey_awards)){

			$patientActivity['compliance_survey_awards'] = $patientActivityHistory->survey_awards;
		}
		if(!empty($patientActivityHistory->referral_tier_awards)){
			$patientActivity['referral_tier_awards'] = $patientActivityHistory->referral_tier_awards;
		}
		

		$allActivity = [];
		if($patientActivity){
			foreach ($patientActivity as $activityType) {
				foreach ($activityType as $activity) {
					$class = explode('\\', (is_string($activity) ? $activity : get_class($activity)));
    				$className = $class[count($activity)+2];
    				$tempVar = Inflector::underscore($className);
    				$reason = Inflector::humanize($tempVar);
    				// pr($reason);
    				// pr($tempVar);

    				if($className == 'ReferralAward') {
    					$refLead = $activity['referral']['referral_lead'];
    					$refFirstName = " - ".$refLead['first_name'];
    					$refLastName = " ".$refLead['last_name'];
    					$refLevel = $refLead['vendor_referral_setting'] != null ? " (".$refLead['vendor_referral_setting']['referral_level_name'].")" : "";

    					$reason = $reason.$refFirstName.$refLastName.$refLevel;
    				}
    				
    				if($className == 'PromotionAward') {
    					$reason = $reason.' - '.$activity['description'].($activity['multiplier'] != 1 ? ' x '.$activity['multiplier'] : '');	
    				}

    				if($className == 'ManualAward') {
    					$reason = $reason.' - '.$activity['description'];	
    				}

    				if($className == 'GiftCouponAward'){
    					$allActivity[] = ['id' => $activity->id, 'points' => $activity->gift_coupon->points, 'reason' => $reason, 'created' => $activity->created, 'type' => $tempVar];

    					if(isset($activity['gift_coupon_redemptions']) && count($activity['gift_coupon_redemptions'])){
    						$allActivity[] = ['id' => $activity->id, 'points' => $activity->gift_coupon->points, 'reason' => 'Gift Coupon Redeemed', 'created' => $activity['gift_coupon_redemptions'][0]->created, 'type' => $tempVar];
						}

    				}else{
						$allActivity[] = ['id' => $activity->id, 'points' => $activity->points, 'reason' => $reason, 'created' => $activity->created, 'type' => $tempVar]; 
					}
				}
			}
		}

		$activitySummary = (new Collection($allActivity))->sortBy('created', SORT_DESC)->take(20);
		
		$patientActivity['summary'] = array_merge($patientActivity['summary'], $activitySummary->toArray());
		// pr($activitySummary->toArray()); die;
		// pr($patientActivity); die;
		$patientActivity['redemptions'] = $patientActivityHistory->legacy_redemptions;
		unset($patientActivityHistory);
		$this->set('activityHistory', $patientActivity);
		$this->set('_serialize', ['activityHistory']);
	}

	public function getStaffReport($id = null)
	{
		if(!$this->request->is(['get'])){
			throw new BadRequestException(__('BAD_REQUEST'));
		}

		$profileId = $this->PeopleHub->getStaffReport( $this->Auth->user('vendor_peoplehub_id'));
		if(!(is_array($profileId) && isset($profileId['status']))){
			$this->set(compact('profileId'));
			$this->set('_serialize', ['profileId']);
		}else{
			throw new BadRequestException(__('Something went wrong'));
		}

	}

	public function redeemLegacy(){

		if(!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}

		$isAmazonTangoRedemtion = false;
		$this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
		$this->request->data['user_id'] = $this->Auth->user('id');
		$this->request->data['legacy_redemption_status_id'] = 3; 	//redemption status is always "redeemed" for instant gift credit

		if(isset($this->request->data['legacy_reward_id'])){

			$reward = $this->LegacyRedemptions
							->LegacyRewards
							->findById($this->request->data['legacy_reward_id'])
							->first();
			if(!$reward){

				throw new NotFoundException(__('Legacy reward not found'));
			}

			$redeemReward = $this->_redeemProductOrService(
													$this->request->data['redeemer_peoplehub_identifier'],
													$reward->points,
													$reward->name
												);
		
		}else{

			$isAmazonTangoRedemtion = true;
			$reward = $this->LegacyRedemptions
							->LegacyRewards
							->findByName('Amazon/Tango')
							->where(['vendor_id' => 1])
							->first();
			if(!$reward){

				throw new NotFoundException(__('Wallet Credit custom legacy reward not found'));
			}
			
			$this->request->data['legacy_reward_id'] = $reward->id;
			
			$redeemReward = $this->_redeemAmazonOrTango(
													$this->request->data['redeemer_peoplehub_identifier'],
													$this->request->data['service']
												);				
		}
		if(!$redeemReward['response']){

			throw new InternalErrorException(__('Error in response from peoplehub'));
			Log::write('debug', $redeemReward);


		}

		$this->request->data['transaction_number'] = $redeemReward['response']->data->id;

		$legacyRedemption = $this->LegacyRedemptions->newEntity();
		
		if(isset($this->request->data['points'])){

			$pointValue = (int) Configure::read('pointsValue');
			$points = (int) $this->request->data['points'];

			$this->request->data['legacy_redemption_amounts'][0]['amount'] = $points / $pointValue;


			$legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data, ['associated' => 'LegacyRedemptionAmounts']);

		}else{

			$legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data);	

			$legacyRedemption->points = $reward->points;

		}

		if($legacyRedemption->errors()){

			throw new BadRequestException(__("ENTITY_NOT_CORRECT"));
		}

		if(!$this->LegacyRedemptions->save($legacyRedemption)){

			throw new InternalErrorException(__('COULD_NOT_SAVED'));
		} else { 
			if($isAmazonTangoRedemtion == true){
			$session = $this->request->session();
        	$practiceName = $session->read('VendorSettings.org_name');
			$patientName = $legacyRedemption->redeemer_name;
			$amount = $legacyRedemption->legacy_redemption_amounts->amount;
			$data = array();
            $data['user_id'] = $this->Auth->user('id');
            $data['points'] = $legacyRedemption->points;
            $data['amount'] = $amount;
            $data['patients_name'] = $patientName;
            $data['link'] = 'helpme@buzzydoc.com';
            $data['practice_name'] = $practiceName;

            $event = new Event('RedemptionStatus.sendToPatient', $this, [
                                        'arr' => [
                                                    'hashData' => $data,
                                                    'eventId' => 15, 
                                                    'vendor_id' => $this->Auth->user('vendor_id')
                                                 ]
                                ]);
            $this->eventManager()->dispatch($event);

			}
		}
	
		
	    $this->set(compact('legacyRedemption'));
	    $this->set('_serialize', ['legacyRedemption']);

	}

	private function _redeemAmazonOrTango($patientId, $service){
	   
	  // See AppController::beforeRender() to know more about why we 
	  // are reading from the session and using the loop.
	$session = $this->request->session();
	$cardSetup = $session->read('CardSetup');
	
	if($cardSetup == 0){

		throw new BadRequestException(__('SETUP_CARD'));

	}
	$rewardRedemptionData = ['user_id' => $patientId , 'service' => $service , 'reward_type' => 'wallet_credit'];


	$redeemRewardResponse = $this->PeopleHub->redeemReward($rewardRedemptionData, $this->Auth->user('vendor_peoplehub_id'));
	if(!$redeemRewardResponse['status']){
	  throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($redeemRewardResponse['response']->message)));
	}

	return $redeemRewardResponse;
	}

	private function _redeemProductOrService($patientId, $points, $rewardName){
		
		$this->loadModel('VendorSettings');
  		$rewardType = $this->VendorSettings
  						   ->findByVendorId($this->Auth->user('vendor_id'))
						   ->contain(['SettingKeys' => function($q){
				                                        	return $q->where(['name' => 'Credit Type' ]);
				                                      	}
									 ])
						   ->first()
						   ->value;
		if($rewardType == 'store_credit'){

	    	$rewardRedemptionData = ['user_id' => $patientId , 'points' => $points , 'reward_type' => $rewardType];

	    	$redeemRewardResponse = $this->PeopleHub->redeemReward($rewardRedemptionData, $this->Auth->user('vendor_peoplehub_id'));
		}else{

	    	$rewardRedemptionData = ['user_id' => $patientId , 'description' => $rewardName, 'service' => 'in_house', 'points' => $points , 'reward_type' => $rewardType];    
	    
	    	$redeemRewardResponse = $this->PeopleHub->instantRedemption($rewardRedemptionData, $this->Auth->user('vendor_peoplehub_id'));
	    } 

		if(!$redeemRewardResponse['status']){
	    	
	    	throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($redeemRewardResponse['response']->message)));
		}

		return $redeemRewardResponse;
	}

	public function placeAmazonOrder(){
		// pr($this->request->data);
		// die;
		$data = $this->request->data;
		$status = 1;
		$legacyRedemptionIds = array_keys($data);

		$productCount = array_count_values($data);

		$legacyRedemptions = $this->LegacyRedemptions->find()
													 ->contain(['LegacyRewards'])
													 ->where(['LegacyRedemptions.id IN' => $legacyRedemptionIds])
													 ->all();

		$notFound = [];
		$redeemedProducts = [];
		$orderItems = [];
		$i = 1;
		foreach($legacyRedemptions as $legacyRedemption) {

			if($legacyRedemption->legacy_redemption_status_id != 3){
				$status = 0;
				$redeemedProducts[] = ['legacy_reward' => $legacyRedemption->legacy_reward->name, 'transaction_number' => $legacyRedemption->id];
			}else{
				$orderItems["Operation"] = 'CartCreate';
				$orderItems["Item.$i.ASIN"] = $data[$legacyRedemption->id];
        		$orderItems["Item.$i.Quantity"] = $productCount[$data[$legacyRedemption->id]];
        		$i++;
			}
		}

		if($status == 0){
			$response = ['redeemedProducts' => $redeemedProducts]; //return products that have been redeemed
		}else{
			$this->loadComponent('ApiAmazon');
			$request = $this->ApiAmazon->aws_signed_request('com', $orderItems, Configure::read('AWS.key'), Configure::read('AWS.secret'), Configure::read('AWS.associate_tag'));
		
			$amazonResponse = array();
        	
        	$response = @file_get_contents($request);
        
	        if ($response === FALSE) {
	            $amazonResponse['success'] = 'false';
	        } else {
	            $pxml = simplexml_load_string($response);
	            $xml2array = $this->xml2array($pxml);
	        }
		}

		if(isset($xml2array)){
			if(isset($xml2array['Cart'][0]['Request'][0]['Errors']) && count($xml2array['Cart'][0]['Request'][0]['Errors']) > 0){
	        	$amazonResponse['success'] = 'false';
	        	$amazonResponse['message'] = "Check the item's availability to make sure it is available.";
	        }else if($xml2array['Cart'][0]['Request'][0]['IsValid'] == 'True' ) {
	            $amazonResponse['success'] = 'true';
	            $amazonResponse['PurchaseURL'] = $xml2array['Cart'][0]['PurchaseURL'];
	            $amazonResponse['FormattedPrice'] = $xml2array['Cart'][0]['SubTotal'][0]['FormattedPrice'];
	        }
		}else{
            $amazonResponse['success'] = 'false';
        }

        $this->set('amazonResponse', $amazonResponse);
        $this->set('_serialize', 'amazonResponse');
	}

	public function xml2array($xml) {
        $arr = array();
        foreach ($xml->children() as $r) {
            $t = array();
            if (count($r->children()) == 0) {
                $arr[$r->getName()] = strval($r);
            } else {
                $arr[$r->getName()][] = $this->xml2array($r);
            }
        }
        return $arr;
    }



}?>
