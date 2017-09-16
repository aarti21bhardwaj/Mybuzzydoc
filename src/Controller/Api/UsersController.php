<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Routing\Router;

/**
* Legacy Redemptions Controller
*
* @property \App\Model\Table\LegacyRedemptionsTable $legacyRedemptions
*/
class UsersController extends ApiController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');

		$this->loadModel('VendorSettings');
		if($this->Auth->user() && $this->Auth->user('role_id') != 1){
		        $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
		                                         ->contain(['SettingKeys' => function($q){
		                                                                            return $q->where(['name' => 'Live Mode']);
		                                                                        }
		                                                    ])
		                                         ->first()->value;
    	$this->loadComponent('PeopleHub', ['liveMode' => $liveMode, 'throwErrorMode' => false]);
		}
	}
	/** */
	public function updatePassword(){
		if(!$this->request->is(['post'])){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		$data = $this->request->data;
		if(!isset($data['new_password'])){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING','new_password'));
		}
		if(isset($data['new_password']) && empty($data['new_password'])){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED','new_password'));
		}
		if(!isset($data['old_password'])){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING','old_password'));
		}
		if(isset($data['old_password']) && empty($data['old_password'])){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED','old_password'));
		}
		if(!isset($data['user_id'])){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING','user_id'));
		}
		if(isset($data['user_id']) && empty($data['user_id'])){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED','user_id'));
		}
		$id = $data['user_id'];
		$user = $this->Users->find()->where(['id'=>$id])->first();
		if(!$user){
			throw new NotFoundException(__('ENTITY_DOES_NOT_EXISTS','User'));
		}
		$password = $data['new_password'];
		$oldPassword = $data['old_password'];


		$hasher = new DefaultPasswordHasher();
		if(!$hasher->check( $oldPassword,$user->password)){
			throw new BadRequestException(__('UNAUTHORIZED_PROVIDE_OLD_PASSWORD'));
		}
		if(! preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $password)){
			throw new BadRequestException(__('Only numbers 0-9, alphabets a-z A-Z and special characters ~!@#$%^*&;?.+_ are allowed.'));
		}
		$reqData = ['password'=>$password];

		$isContainChars = false;
		for( $i = 0; $i <= strlen($user->username)-3; $i++ ) {
			$char = substr( $user->username, $i, 3 );
			if(strpos($password,$char,0) !== false ){
				$isContainChars = true;
				break;
			}
		}
		if($isContainChars){
			throw new BadRequestException(__('THREE_CONTIGUOUS_CHARACTERS','username'));
		}
		$fullname = $user->full_name;
		for( $i = 0; $i <= strlen($fullname)-3; $i++ ) {
			$char = substr( $fullname, $i, 3 );
			if(strpos($password,$char,0) !== false ){
				$isContainChars = true;
				break;
			}
		}
		if($isContainChars){
				throw new BadRequestException(__('THREE_CONTIGUOUS_CHARACTERS','full name'));
		}
		$this->loadModel('UserOldPasswords');

		$userOldPasswordCheck = $this->UserOldPasswords->find('all')->where(['user_id'=>$id])->toArray();
		foreach ($userOldPasswordCheck as $key => $value) {
			if($hasher->check( $password,$value['password'])){
				throw new BadRequestException(__('SIX_EARLIER_PASSWORD'));
			}
		}
		$user = $this->Users->patchEntity($user, $reqData);
		if($this->Users->save($user)){
			$reqData = ['user_id'=>$id,'password'=>$password];

			$userOldPasswordCheck = $this->UserOldPasswords->newEntity($reqData);
			$userOldPasswordCheck = $this->UserOldPasswords->patchEntity($userOldPasswordCheck, $reqData);
			if($this->UserOldPasswords->save($userOldPasswordCheck)){
				$userOldPasswordCheck = $this->UserOldPasswords->find('all')->where(['user_id'=>$id]);
				if($userOldPasswordCheck->count() > 6){
					$userOldPasswordCheck =$userOldPasswordCheck->order('created ASC')->first();
					$userOldPasswordCheck = $this->UserOldPasswords->delete($userOldPasswordCheck);
				}
				$data =array();
				$data['status']=true;
				$data['data']['id']=$user->id;
				$data['data']['message']='password saved';
				$this->set('response',$data);
				$this->set('_serialize', ['response']);

			}else{
				// pr($userOldPasswordCheck->errors());die;
				//log password not changed
				// throw new BadRequestException(__('can not use earlier used 6 passwords'));
			}
		}else{
			// pr($user->errors());die;
			throw new BadRequestException(__('BAD_REQUEST'));
		}

	}

	public function searchPatient($query, $searchType = null){

		$searchResults = $this->PeopleHub->search($this->Auth->user('vendor_peoplehub_id'), $query, $searchType);
		if(is_array($searchResults) && isset($searchResults['status'])){
			throw new InternalErrorException(__('TOKEN_ERROR'));
		}
		// pr($searchResults);
		$this->set('response', $searchResults);
		$this->set('_serialize', 'response');
	}

	public function registerPatient(){

		if(!isset($this->request->data['email']) && !isset($this->request->data['guardian_email']) && !isset($this->request->data['card_number'])){
			throw new BadRequestException(__("One of the two is required, Email or gaurdian's email"));
		}
		$vendor = $this->Users->Vendors->findById($this->Auth->user('vendor_id'))->contain(['VendorCardSeries'])->first();
		// pr($vendor);die;
		$this->request->data['password'] = $this->_randomPassword();
		$patientFirstName = $this->request->data['first_name'];
		$response = $this->PeopleHub->registerPatient($this->Auth->user('vendor_peoplehub_id'), $this->request->data);
		if(!$response || !$response->status){
			$message ="";
			foreach($response->error as $key=>$value)
			{
				$message= $message." ".$value;
			}
			if($message == " This email id is already registered with Us. Kindly login. Username not available."){

				$response->title = "It looks like this email is linked to another account";
				$response->message = "This email is already linked to another patient's account. In order to assign a second patient to ".$patientFirstName."'s email ID, Please click on 'Add Another Patient'";

			}else{

				throw new BadRequestException(__($message));
			}

		}else{


			$data = [
						'patient_peoplehub_id' => $response->data->id,
						'vendor_id' => $this->Auth->user('vendor_id'),
						'user_id' => $this->Auth->user('id'),
						'patient_name' => $response->data->name,
						'username' => $response->data->username,
						'password' => $this->request->data['password']
					];

			if(isset($response->data->message) && $response->data->message){
				$data['password'] = "Redacted for security purposes";
			}

			if(isset($response->data->guardian_email) && $response->data->guardian_email){
				$data['email'] = $response->data->guardian_email;
			}elseif(isset($response->data->email) && $response->data->email){
				$data['email'] = $response->data->email;
			}

			if(isset($response->data->phone) && $response->data->phone){
       			 $vendorPatient['phone'] = $response->data->phone;
      		}
			$vendorPatient = $this->Users->Vendors->VendorPatients->newEntity();
			$vendorPatient = $this->Users->Vendors->VendorPatients->patchEntity($vendorPatient, $data);

			if(!$this->Users->Vendors->VendorPatients->save($vendorPatient)){

				throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
			}
		}


		$this->set('response', $response);
		$this->set('_serialize', 'response');
	}

	public function editPatientProfile($pId){

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		$response = $this->PeopleHub->editPatient($this->Auth->user('vendor_peoplehub_id'), $pId,$this->request->data);

		if(!$response || !$response->status){
			// pr($response);die;
			if(isset($response->error)){

				$message = json_encode($response->error);
			}else{

				$message = "Error in response from people hub";
			}
			throw new InternalErrorException(__($message));

		}

		$this->set('response', $response);
		$this->set('_serialize', 'response');
	}

	private function _randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	//get a specific patients details from peoplehub and buzzydoc
	public function getPatientDetails($patientsPeoplehubId=null){
		if(!$this->request->is('get')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$patientsPeoplehubId){
			throw new InternalErrorException(__('MANDATORY_FIELD_MISSING', 'patients PeopleHub id'));
		}

		$responseOfPatientDetails = $this->PeopleHub->patientDetails($this->Auth->user('vendor_peoplehub_id'), $patientsPeoplehubId);

		if(is_array($responseOfPatientDetails) && isset($responseOfPatientDetails['status'])){
			throw new InternalErrorException(__('TOKEN_ERROR'));
		}

		$vendorPatients = $this->Users->Vendors->VendorPatients->findByPatientPeoplehubId($patientsPeoplehubId)->where(['vendor_id' => $this->Auth->user('vendor_id')])->first();

		if($vendorPatients)
		{
			$responseOfPatientDetails->data->vendorPatient = $vendorPatients->id;
			$allowRedemptions = $vendorPatients->redemptions;
			$responseOfPatientDetails->data->oldBuzzyId = $vendorPatients->old_buzzydoc_patient_identifier;
		
		}else{

			$allowRedemptions = true;
		}

		$lastMilestoneAchieved = $this->_getLastMilestoneAchieved($patientsPeoplehubId);

		$giftCouponAwards = $this->_patientsCoupons($patientsPeoplehubId);

		$referrals = $this->_getPatientReferrals($patientsPeoplehubId);

		$referralTiers = $this->_getPatientReferralTiers($patientsPeoplehubId);

		$address = $this->_getPatientAddress($patientsPeoplehubId);

		$instantRewardsStatus = $this->_instantRewards($patientsPeoplehubId);
	
		$this->set(compact('responseOfPatientDetails', 'lastMilestoneAchieved', 'giftCouponAwards', 'referrals','referralTiers', 'address', 'instantRewardsStatus', 'allowRedemptions'));
		$this->set('_serialize', ['responseOfPatientDetails', 'lastMilestoneAchieved', 'giftCouponAwards', 'referrals','referralTiers', 'address', 'instantRewardsStatus', 'allowRedemptions']);
	}

	private function _getPatientReferralTiers($patientsPeoplehubId = null){

		$this->loadModel('ReferralTierAwards');
		$referralTiers = $this->ReferralTierAwards->findByRedeemerPeoplehubIdentifier($patientsPeoplehubId)
												->where(['ReferralTierAwards.vendor_id' => $this->Auth->user('vendor_id')])
												->contain(['ReferralTiers.ReferralTierPerks'])
												->last();
		$referralTiersAndPerks = [];
		if(!$referralTiers){
			return false;
		}else{
			
			$referralTiersAndPerks['name'] = $referralTiers->referral_tier->name;
			
			if($referralTiers->referral_tier->referral_tier_perks){
				foreach ($referralTiers->referral_tier->referral_tier_perks as $key => $value) {
					$referralTiersAndPerks['perks'][] = $value->perk;
				}
			}else{
				$referralTiersAndPerks['perks'] = false;
			}
		}

		return $referralTiersAndPerks;
	}

	private function _getLastMilestoneAchieved($patientsPeoplehubId = null){

		$this->loadModel('VendorMilestones');
		$milestone = $this->VendorMilestones->findByVendorId($this->Auth->user('vendor_id'))
						  ->contain(['MilestoneLevels.MilestoneLevelAwards' =>function($q)use($patientsPeoplehubId){
								return $q->where(['redeemer_peoplehub_identifier' => $patientsPeoplehubId]);
								}
							])
		         		  ->first();

		$milestoneLevelAchieved = '';
		if(!empty($milestone) || !empty($milestone->milestone_levels)){
			$milestoneLevels = new Collection($milestone->milestone_levels);
			$milestoneLevAwards = $milestoneLevels->stopWhen(function($value, $key){
				return count($value->milestone_level_awards) == 0;
			});

			if(count($milestoneLevAwards->toArray()) > 0){
				$lastMilestoneAchieved = $milestoneLevAwards->last();
				$milestoneLevelAchieved = $lastMilestoneAchieved->name;

			}
		}

		return $milestoneLevelAchieved;

	}

	private function _getPatientReferrals($patientsPeoplehubId = null){

		$this->loadModel('Referrals');

		$referrals = $this->Referrals->findByVendorId($this->Auth->user('vendor_id'))
						  ->where(['Referrals.peoplehub_identifier' => $patientsPeoplehubId])
						  ->contain(['ReferralLeads'])
		         		  ->all()->toArray();

		 // pr($referrals);die;

		if(!$referrals)
			return false;

		$referralStatuses  = $this->Referrals->ReferralLeads->ReferralStatuses->find()->all()->indexBy('id')->toArray();


		foreach ($referrals as $key => $value) {
			if($value->referral_lead && $value->referral_lead != null)
				$referrals[$key]->referral_lead->referral_status = $referralStatuses[$value->referral_lead->referral_status_id];
		}
		return $referrals;


	}

	private function _patientsCoupons($patientsPeoplehubId = null)
    {

        $this->loadModel('GiftCouponAwards');
        $giftCouponAwards = $this->GiftCouponAwards->findByRedeemerPeoplehubIdentifier($patientsPeoplehubId)
                                                   ->where(['status' => 1])
                                                   ->contain(['GiftCoupons' => function($q){
                                                                return $q->where(['GiftCoupons.vendor_id' => $this->Auth->user('vendor_id')]);
                                                             }])
                                                   ->all();

        if(!$giftCouponAwards){
            throw new NotFoundException(__('No coupons are available for this patient.'));
        }

        $giftCouponAwards = $giftCouponAwards->map(function($value, $key){
                                                        return ['gift_coupon_award_id' => $value->id, 'points' => $value->gift_coupon->points, 'description' => $value->gift_coupon->description];
                                                    });

     	return $giftCouponAwards->toArray();
    }

    private function _getPatientAddress($patientsPeoplehubId = null)
    {

        $this->loadModel('PatientAddresses');
        $patientAddress = $this->PatientAddresses->findByPatientPeoplehubIdentifier($patientsPeoplehubId)
                                                 ->first();

        if(!$patientAddress){
            $patientAddress = false;
        }

     	return $patientAddress;
    }

    private function _instantRewards($patientId = null){
  
	    $this->loadModel('PatientVisitSpendings');
	    $patientVisitSpendings = $this->PatientVisitSpendings->findByPeoplehubUserId($patientId)
	                                                         ->where(['vendor_id' => $this->Auth->user('vendor_id')])
	                                                         ->first();
        $this->loadModel('VendorInstantGiftCouponSettings');
        $setting = $this->VendorInstantGiftCouponSettings->findByVendorId($this->Auth->user('vendor_id'))
                                                         ->first();

        if(!$setting){
        	return false;
        }

	    if(!$patientVisitSpendings){
	    	$isInstantRewardUnlocked = false;
	    }elseif($patientVisitSpendings && !$patientVisitSpendings->instant_reward_unlocked){
	    	$isInstantRewardUnlocked = false;

  	        $createdTime = new Time($patientVisitSpendings->created);
	        $currentTimestamp = new Time();
	        $createdTime->modify('+'.$setting->threshold_time_period.' hours');
	      
	        $diff = $currentTimestamp->diff($createdTime);

	        if($diff->invert == 1){
	        	return false;
	        }

	        if($diff->y > 0)
	        	$diff->m = $diff->y*365;
	      
	        if($diff->m > 0)
	       		$diff->d = $diff->m*30; //Assuming a month to have 30 days on average

	        if($diff->d > 0)
	        	$diff->h = $diff->d*24;

	        if($diff->h > 0 || $diff->i > 0 || $diff->s >0){
	        	$remainingTime = $diff->format('%h hours, %i minutes, %s seconds');
	        }

	        $remainingPoints = $setting->points_earned_threshold - $patientVisitSpendings->points_taken;
	        $remainingAmount = $setting->amount_spent_threshold - $patientVisitSpendings->amount_spent;
	    	$response = ['remainingTime' => $remainingTime, 'remainingPoints' => $remainingPoints, 'remainingAmount' => $remainingAmount];
	    }else{
	    	$isInstantRewardUnlocked = true;
	    	$unlockTime = new Time($patientVisitSpendings->unlock_time);
	        // pr($modifiedTime);
	        $currentTimestamp = new Time();
	        // pr($currentTimestamp);
	        if(!$unlockTime->wasWithinLast($setting->redemption_expiry.' hours')){
	          $isExpired = true;
	        }else{
	          $unlockTime->modify('+'.$setting->redemption_expiry.' hours');
	          $expiryDate = strtotime($unlockTime);
	          // $timeRemaining = $modifiedTime->diff($currentTimestamp);
	          $response['expiry'] = $expiryDate;
	          $isExpired = false;
	        }
	        $response['isExpired'] = $isExpired;
	    }
	    $response['isInstantRewardUnlocked'] = $isInstantRewardUnlocked;
	    	
	    return $response;
	}


    public function resetPasswordLink(){

	    if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}

	    if(!isset($this->request->data['email']) || (isset($this->request->data['email']) && $this->request->data['email'] == "") ){

			throw new InternalErrorException(__('MANDATORY_FIELD_MISSING', 'Email'));

	    }
		$email = $this->request->data['email'];
		$user = $this->Users->find('all')->where(['email'=>$email])->first();
		if(!$user){
			throw new NotFoundException(__('ENTITY_DOES_NOT_EXISTS','User'));
		}

		// pr($user->uuid);die;
		$this->loadModel('ResetPasswordHashes');
		$checkExistPasswordHash = $this->ResetPasswordHashes->find()->where(['user_id'=>$user->id])->first();


		if(empty($checkExistPasswordHash)){


			$resetPwdHash = $this->_createResetPasswordHash($user->id,$user->uuid);

		}else{

			$resetPwdHash = $checkExistPasswordHash->hash;
			$time = new Time($checkExistPasswordHash->created);
			if(!$time->wasWithinLast(1)){
			  $this->ResetPasswordHashes->delete($checkExistPasswordHash);
			  $resetPwdHash =$this->_createResetPasswordHash($user->id,$user->uuid);
			}
		}
		$url = Router::url('/', true);
		$url = $url.'users/resetPassword/?reset-token='.$resetPwdHash;
		$user->link = $url;
		$event = new Event('User.resetPassword', $this, [
															'arr' =>[
																		'hashData' => $user,
																		'eventId' => 3, //give the event_id for which you want to fire the email
																		'vendor_id' => $user->vendor_id
																	]
		]);

		$this->eventManager()->dispatch($event);
		if(!$event)
			throw new InternalErrorException(__('Error in sending email for reset password.'));

		$response = ['message' => __('VERIFIED_AND_CHANGE_PASSWORD')];

		$this->set(compact('response'));
		$this->set('_serialize', ['response']);

	}

	protected function _createResetPasswordHash($userId,$uuid){

	    $this->loadModel('ResetPasswordHashes');
	    $hasher = new DefaultPasswordHasher();
	    $reqData = ['user_id'=>$userId,'hash'=> $hasher->hash($uuid)];
	    $createPasswordhash = $this->ResetPasswordHashes->newEntity($reqData);
	    $createPasswordhash = $this->ResetPasswordHashes->patchEntity($createPasswordhash,$reqData);
	    if($this->ResetPasswordHashes->save($createPasswordhash)){
	      return $createPasswordhash->hash;
	    }else{
        Log::write('error','error in creating resetpassword hash for user id '.$userId);
        Log::write('error',$createPasswordhash);
      }
        return false;
	}

	public function suggestUsername(){
		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$this->request->data){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'data'));
		}
		$data = $this->request->data;

		if(!$data['first_name']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'first name'));
		}
		if($data['first_name'] && empty($data['first_name'])){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'first name'));
		}
		if(!is_string($data['first_name'])){
			throw new BadRequestException(__('INVALID_DATA_PROVIDED'));
		}

		if(!$data['last_name']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'first name'));
		}
		if($data['last_name'] && empty($data['last_name'])){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'first name'));
		}
		if(!is_string($data['last_name'])){
			throw new BadRequestException(__('INVALID_DATA_PROVIDED'));
		}

		$username = $this->PeopleHub->suggestUsername($this->Auth->user('vendor_peoplehub_id'), $this->request->data);

		// pr($searchResults);
		$this->set('response', $username);
		$this->set('_serialize', 'response');
	}

	// Get Patient Transaction History from Old BuzzyDoc
	public function getOldBuzzyHistory(){

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$this->request->data){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'data'));
		}

		$data = $this->request->data;

		if(!isset($data['patient_id'])  && !$data['patient_id']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Patient Id'));
		}

		if(!isset($data['patient_cards'])  && !$data['patient_cards']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Patient Cards'));
		}

		$vendorId = $this->Auth->user('vendor_id');

		if(!$vendorId){

			throw new ForbiddenException(__('You are not authorized to access this data'));
		}

		$vendorPatient = $this->Users
							  ->Vendors
							  ->VendorPatients
							  ->findByVendorId($vendorId)
							  ->where(['patient_peoplehub_id' => $data['patient_id']])
							  ->contain(['Vendors.OldBuzzydocVendors'])
							  ->first();

		if(!$vendorPatient){

			throw new NotFoundException(__('For old BuzzyDoc patients only'));
		}

		if(!$vendorPatient->vendor->old_buzzydoc_vendor){

			throw new NotFoundException(__('For old BuzzyDoc Vendors only'));
		}

		$oldVendorId = $vendorPatient->vendor->old_buzzydoc_vendor->old_vendor_id;


		if(!$vendorPatient->old_buzzydoc_patient_identifier){

			$response = $this->_getUnRegUserHistory($data['patient_cards'], $oldVendorId);

		}else{

			$response = $this->_getRegUserHistory($vendorPatient['old_buzzydoc_patient_identifier'], $oldVendorId);
		}

		$this->set('response', $response);
		$this->set('_serialize', 'response');

	}

	//patient cards and vendor id here is of old buzzydoc
	private function _getUnRegUserHistory($patientCards, $vendorId){

		$cardNumber = $patientCards[0]['card_number'];
		$this->loadComponent('OldBuzzydoc');
		$history = $this->OldBuzzydoc->getResults('select * from unreg_transactions where clinic_id = '.$vendorId.' and card_number = '.$cardNumber);

		return $this->_historyArray($history);


	}

	//patient id and vendor id here is of old buzzydoc
	private function _getRegUserHistory($patientId, $vendorId){

		$this->loadComponent('OldBuzzydoc');
		$history = $this->OldBuzzydoc->getResults('select * from transactions where '.'user_id = '.$patientId.' and  clinic_id = '.$vendorId);

		return $this->_historyArray($history);


	}

	private function _historyArray($history){

		$response = [];
		foreach ($history as $key => $row) {

			$response[] = [
							'transaction_id' => $row['id'],
							'reason' => $row['authorization'],
							'amount' => $row['amount'],
							'date' => $row['date']
						  ];
		}
		if(!$response){

			throw new NotFoundException(__('No history found for this patient'));
		}

		return $response;
	}

	public function deletePatient($patientId = null){

		if(!$this->request->is('delete')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}

		if(!$patientId || $patientId == null){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', "Patient Id"));
		}

		$vendorPatient = $this->Users
							  ->Vendors
							  ->VendorPatients
							  ->findByVendorId($this->Auth->user('vendor_id'))
							  ->where(['patient_peoplehub_id' => $patientId])
							  ->first();
							  // pr($vendorPatient);die;

		if(!$vendorPatient){
			throw new NotFoundException(__('Patient is not associated with this vendor.'));	
		}	

		$peopleHubResponse = $this->PeopleHub->deletePatient($this->Auth->user('vendor_peoplehub_id'), $patientId);
		if(!isset($peopleHubResponse->status) && $peopleHubResponse->status){

			throw new InternalErrorException(__('Error in response from Bountee'));

		}

		$this->Users->Vendors->VendorPatients->delete($vendorPatient);

		$this->set('response', ['message' => 'Patient has been deleted']);
		$this->set('_serialize', 'response');
	}

	public function patientForgotPassword(){

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$this->request->data){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'data'));
		}

		$data = $this->request->data;

		if(!isset($data['email'])  && !$data['email']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', "Email"));
		}

		if(!isset($data['name'])  && !$data['name']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', "Name"));
		}

		if(!isset($data['username'])  && !$data['username']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Username'));
		}

		if(!isset($data['ref'])  && !$data['ref']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Host'));
		}

		$peopleHubResponse = $this->PeopleHub->resetPatientPasswordRequest($this->Auth->user('vendor_peoplehub_id'), $data);

		if(!isset($peopleHubResponse->status) && $peopleHubResponse->status){

			throw new InternalErrorException(__('Error in response from Bountee'));

		}

		$vendor = $this->Users
						->Vendors
						->findById($this->Auth->user('vendor_id'))
						->first();

		$vendor->reset_link = $data['ref'].'reset_password?resetToken='.$peopleHubResponse->data->token;
		$vendor->ref = $data['ref'];
		$vendor->patient_name = $data['name'];
		$vendor->username = $data['username'];
		$vendor->email = $data['email'];

		$event = new Event('Patient.resetPassword', $this, [
															'arr' =>[
																		'hashData' => $vendor,
																		'eventId' => 10, //give the event_id for which you want to fire the email
																		'vendor_id' => $this->Auth->user('vendor_id')
																	]
		]);

		$this->eventManager()->dispatch($event);

		$response['message'] = "Password reset link sent successfully";

		$this->set('response', $response);
		$this->set('_serialize', 'response');

	}


}
