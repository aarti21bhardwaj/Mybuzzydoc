<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotAcceptableException;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Collection\Collection;
use Cake\Routing\Router;
use Cake\I18n\Time;


/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\VendorRedeemedPointsTable 
 */
class  AwardsController extends ApiController
{
    //Used in rollback().
    private $_awardTypes = [
                             // 'Gift Coupon Award' => 'GiftCouponAwards',
                             'Manual Award' => 'ManualAwards',
                             'Milestone Level Award' => 'MilestoneLevelAwards',
                             'Promotion Award' => 'PromotionAwards', //if promotion award key changes, make changes in the method as well.
                             'Referral Award' => 'ReferralAwards',
                             'Referral Tier Award' => 'ReferralTierAwards',
                             'Review Award' => 'ReviewAwards',
                             'Survey Award' => 'SurveyAwards',
                             'Tier Award' => 'TierAwards'
                           ];

    public function initialize()
    {
        parent::initialize();

        $user = $this->Auth->user();
        if(!empty($user)){
          $this->loadModel('VendorSettings');
          $liveMode = $this->VendorSettings->findByVendorId($user['vendor_id'])
                                           ->contain(['SettingKeys' => function($q){
                                                                  return $q->where(['name' => 'Live Mode']);
                                                              }
                                                      ])
                                           ->first()->value;
          $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
          $this->loadComponent('InstantRewards', ['liveMode' => $liveMode]);
        }
        $this->loadComponent('RequestHandler');

        $this->Auth->allow('redeemInstantRewards');
    }
    
    public function reviewAwardsIndex(){
      if (!$this->request->is('get')) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $this->loadModel('ReviewAwards');
      $reviewAwards = $this->ReviewAwards
      ->find()
      ->where(['vendor_id' => $this->Auth->user('vendor_id')])
      ->all();
      $reviewTypes = $this->ReviewAwards->ReviewTypes->find()->all();
      $reviewSettings = $this->ReviewAwards->Users->Vendors->ReviewSettings->findByVendorId($this->Auth->user('vendor_id'))->first();

      $this->set(compact('reviewAwards','reviewTypes','reviewSettings'));
      $this->set('_serialize', ['reviewAwards', 'reviewTypes', 'reviewSettings']);
    }

    public function referral(){

      if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

      if(!isset($this->request->data['referral_lead_id'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'referal_lead_id'));
      }

      if(!isset($this->request->data['referral_peoplehub_identifier'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Referral Bountee Id'));
      }

      if(!isset($this->request->data['vendor_referral_setting_id'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor_referral_setting_id'));
      }

      $this->loadModel('ReferralLeads');

      $loggedInUser = $this->Auth->user();
      if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
          
          $referralLead = $this->ReferralLeads
                           ->findById($this->request->data['referral_lead_id'])
                           ->contain('Referrals')
                           ->first();
          if($referralLead)
            $vendorId = $referralLead->vendor_id;
      
      }else{
          
          $vendorId = $this->Auth->user('vendor_id');

          $referralLead = $this->ReferralLeads
                               ->findById($this->request->data['referral_lead_id'])
                               ->where(['ReferralLeads.vendor_id' => $vendorId])
                               ->contain('Referrals')
                               ->first();    
      }

      if(!$referralLead){

        throw new NotFoundException("Record not found in referral leads");
      }

      if($referralLead->vendor_referral_setting_id != null){

        throw new BadRequestException("Points have already been awarded for this referral");
      }

      $referralSetting = $this->ReferralLeads
                              ->VendorReferralSettings
                              ->findById($this->request->data['vendor_referral_setting_id'])
                              ->first();
      if(!$referralSetting){

        throw new NotFoundException("Record not found in vendor referral settings");
      }

      if($referralSetting->referrer_award_points == 0 && $referralSetting->referree_award_points == 0){

        throw new InternalErrorException("Points not awarded as referral level points not set");

      }

      $referralAwards = [];
      $response = [];

      if($referralSetting->referrer_award_points > 0){

        $referrerPoints = $this->_givePeoplehubPoints($referralLead->referral->peoplehub_identifier, $referralSetting->referrer_award_points, $vendorId);
        $referrerAward = [
                            'referral_id' => $referralLead->referral_id,
                            'user_id' => $this->Auth->user('id'),
                            'points' => $referralSetting->referrer_award_points,
                            'peoplehub_transaction_id' => $referrerPoints['response']->data->id,
                            'redeemer_peoplehub_identifier' => $referralLead->referral->peoplehub_identifier,
                            'vendor_id' => $vendorId,
                            'role' => 'referrer'
                         ];

        $referralAwards[] = $referrerAward;

        $response['referrerPoints'] = $referralSetting->referrer_award_points;


      }else{

        $response['referrerPoints'] = "Not awarded as referral level points are set to zero";
      }

      if($referralSetting->referree_award_points > 0){

        $referreePoints = $this->_givePeoplehubPoints($this->request->data['referral_peoplehub_identifier'], $referralSetting->referree_award_points);

        $referreeAward = [
                            'referral_id' => $referralLead->referral_id,
                            'user_id' => $this->Auth->user('id'),
                            'points' => $referralSetting->referree_award_points,
                            'peoplehub_transaction_id' => $referreePoints['response']->data->id,
                            'redeemer_peoplehub_identifier' => $this->request->data['referral_peoplehub_identifier'],
                            'vendor_id' => $vendorId,
                            'role' => 'referree'
                         ];

        $referralAwards[] = $referreeAward;
        $response['referreePoints'] = $referralSetting->referree_award_points;


      }else{

        $response['referreePoints'] = "Not awarded as referral level points are set to zero";
      }      

      $referralLead->vendor_referral_settings_id = $this->request->data['vendor_referral_setting_id'];

      $referralLead->referral_status_id = $this->ReferralLeads
                                               ->ReferralStatuses
                                               ->findByStatus('Complete')
                                               ->first()
                                               ->id;
      

      $referralLead->peoplehub_identifier =  $this->request->data['referral_peoplehub_identifier'];


      if(!$this->ReferralLeads->save($referralLead)){

        throw new InternalErrorException("Vendor Referral Level Could Not be updated but points have been awarded");

      }

      $response['status'] = 'Complete';
      $response['referral_level_name'] = $referralSetting->referral_level_name; 
      
      $referralAwards = $this->ReferralLeads->Referrals->ReferralAwards->newEntities($referralAwards);

      if(!$this->ReferralLeads->Referrals->ReferralAwards->saveMany($referralAwards)){

        throw new InternalErrorException("Error in saving referral awards");
      }

      //update points taken in patient_visit_spendings table for both referer  and referee
      foreach ($referralAwards as $refAward) {
        
        $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $refAward->redeemer_peoplehub_identifier, $refAward->points);
      }

      //Gift coupon awarded to the patient if associated with a tier
      $giftCoupon = $this->ReferralLeads
                         ->VendorReferralSettings
                         ->ReferralSettingGiftCoupons
                         ->findByVendorReferralSettingId($this->request->data['vendor_referral_setting_id'])
                         ->first();

      if($giftCoupon){
        
        $giftCouponAwardData = [
                                  'gift_coupon_id' => $giftCoupon->gift_coupon_id, 
                                  'user_id' => $this->Auth->user('id'),
                                  'redeemer_peoplehub_identifier' => $this->request->data['referral_peoplehub_identifier'], 
                                  'redeemer_name' => $referralLead->first_name,
                                  'transaction_number' => 0, 
                                  'reason' => 'Referral: '.$referralLead->first_name,
                                  'status' => 1, 
                                  'vendor_id' => $vendorId
                               ];

        $saved =  $this->_giftCouponAward($giftCouponAwardData);

        if($saved[1]){


          $response['Gift Coupon Award'] = "Awarded";
          Log::write('debug', 'Associated Gift Coupon Awarded');

        }else{
          $response['Gift Coupon Award'] = "Failed";
          Log::write('debug', 'Associated Gift Coupon could not be awarded');
        }

      }
      //Check Referral Tier
      $response['Referral Tier'] = $this->_referralTiers($referralLead->referral->peoplehub_identifier, $referralLead->first_name, $referralLead->referral->id);
      
      $this->set('response', $response);
      $this->set('_serialize', 'response');

    }

    private function _referralTiers($patientId, $patientName, $referralId){

      $this->loadModel('Vendors');
      $vendor = $this->Vendors
                     ->findById($this->Auth->user('vendor_id'))
                     ->contain(['VendorSettings.SettingKeys' => function($q){
                                return $q->where(['name' => 'Referral Tier Program']);
                              }, 'ReferralTiers'
                     ])

                     ->first();

      if(!isset($vendor->vendor_settings[0]->value) ||  !$vendor->vendor_settings[0]->value){
        return "Program is not active";
      }
        
      if(!$vendor->referral_tiers){
        return "No tiers have been defined.";
      }

      $referralTierCalculate = $this->Vendors->ReferralTiers->PatientReferralTiers->calculate($patientId, $vendor->id);

      if(!$referralTierCalculate){
      
        return "Referral tier could not be calculated.";
      
      }

      if(!$referralTierCalculate['tierChange']){

        return "No new tier achieved";
      
      }

      if($referralTierCalculate['points']){

         $referralTierPoints = $this->_givePeoplehubPoints($patientId, $referralTierCalculate['points']);
      }

      $referralTierAward = [

                              'vendor_id' => $vendor->id,
                              'user_id' => $this->Auth->user('id'),
                              'redeemer_peoplehub_identifier' => $patientId,
                              'points' => $referralTierCalculate['points'],
                              'referral_tier_id' => $referralTierCalculate['newTierId'],
                              'referral_id' => $referralId,
                              'peoplehub_transaction_id' => (isset($referralTierPoints['response']->data) ? $referralTierPoints['response']->data->id  : 0)
                           ];

      $referralTierAward = $this->Vendors->ReferralTierAwards->newEntity($referralTierAward);

      if(!$this->Vendors->ReferralTierAwards->save($referralTierAward)){

        return "Referral tier could not be awarded.";
        Log::write('debug', 'Referral tier could not be awarded.');
      }

      //update points taken in patient_visit_spendings table for both referer  and referee
      $this->InstantRewards->checkStatus($vendor->id, $patientId, $referralTierCalculate['points']);

      $referralTierGiftCoupon = $this->Vendors
                                     ->ReferralTiers
                                     ->ReferralTierGiftCoupons
                                     ->findByReferralTierId($referralTierCalculate['newTierId'])
                                     ->first();

      if($referralTierGiftCoupon){
        
        $giftCouponAwardData = [
                                  'gift_coupon_id' => $referralTierGiftCoupon->gift_coupon_id, 
                                  'user_id' => $this->Auth->user('id'),
                                  'redeemer_peoplehub_identifier' => $patientId, 
                                  'redeemer_name' => $patientName,
                                  'transaction_number' => 0, 
                                  'reason' => 'Referral Tier: '.$referralTierCalculate['newTierName'],
                                  'status' => 1, 
                                  'vendor_id' => $vendor->id
                               ];

        $saved =  $this->_giftCouponAward($giftCouponAwardData);
        if($saved[1]){

          return "Referral tier & associated gift coupon awarded.";
          Log::write('debug', 'Referral tier & associated gift coupon awarded.');

        }else{
          return "Referral tier awarded & associated gift coupon could not be awarded.";
          Log::write('debug', 'Referral tier awarded & associated gift coupon not awarded.');
        }

      }

      return "Referral tier has been awarded";  

    }


    public function review(){
      if (!$this->request->is('post')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      //Find the review request status for the request_id
      $reviewTypeName = $this->request->data['review_type_name'];
      $this->loadModel('ReviewRequestStatuses');
      $reviewRequestStatus = $this->ReviewRequestStatuses->findById($this->request->data['review_request_status_id'])->first();


      $reviewSettingName = $reviewTypeName.'_points'; 
      //TODO: When review_type_id is used in vendor setings, remove this.
      $this->loadModel('ReviewSettings');
      $reviewPoints = $this->ReviewSettings->findByVendorId($this->Auth->user('vendor_id'))->select([$reviewSettingName])->first()->$reviewSettingName;

      if(!$reviewPoints){
        throw new NotFoundException('Points have not been stored for the review type.');
      }

      //Prepare data and send request to PeopleHub.
      $provideRewardResponse = $this->_givePeoplehubPoints($reviewRequestStatus->people_hub_identifier, $reviewPoints);

      //On success from peoplehub, prepare data and log in review awards table.
      if(isset($reviewRequestStatus->user_id) && $reviewRequestStatus->user_id){
        $userId = $reviewRequestStatus->user_id;
      }else{
        $userId = null;
      }
      $vendorReviewId = $reviewRequestStatus->vendor_review_id;

      $this->loadModel('ReviewTypes');
      $reviewTypeId = $this->ReviewTypes->findByName($reviewTypeName)->first()->id;

      $this->loadModel('ReviewAwards');
      $reviewAward = ['review_request_status_id' => $this->request->data['review_request_status_id'], 'user_id' => $userId, 'points' => $reviewPoints, 'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 'review_type_id' => $reviewTypeId, 'redeemer_peoplehub_identifier' => $reviewRequestStatus->people_hub_identifier, 'vendor_id' => $this->Auth->user('vendor_id')];

      $reviewAward = $this->ReviewAwards->newEntity($reviewAward);
      if($reviewAward->errors()){
          throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'));
      }

      if($this->ReviewAwards->save($reviewAward)){

          //update points taken in patient_visit_spendings table
          $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $reviewRequestStatus->people_hub_identifier, $reviewPoints);

          $this->set('response', ['status' => 'OK', 'data' => ['id' => $reviewAward->id]]);
          $this->set('_serialize', ['response']);
      }else{
          throw new InternalErrorException(__('ENTITY_ERROR', 'review award'));
      }
    } 


    public function promotions()
    {

      if (!$this->request->is('post')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $data = $this->request->data;

      if(!isset($data['user_id'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'user_id'));
      }
      if(!isset($data['redeemersId'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemersId'));
      }
      if(!isset($data['selectedPromotions'])){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'selectedPromotions'));
      }

      if(!count($data['selectedPromotions'])){
        throw new BadRequestException(__('No promotions to award.'));
      }

      $this->loadModel('PromotionAwards');
      $vendorPromotions = $this->PromotionAwards->VendorPromotions->findByVendorId($this->Auth->user('vendor_id'))->all();
      $promotionAwards = [];
      $dataForPHub = [];
      $points = 0;
      $patientsId = $data['redeemersId'];

      $rewardType = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Credit Type']);
        }])->first()->value;
        
        if(!is_string($rewardType)){
            throw new BadRequestException(__('INVALID_REWARD_TYPE'));
        }

      foreach ($data['selectedPromotions'] as $key => $value) {
        foreach ($vendorPromotions as $value2) {
            if($value['promotion_id'] === $value2['id']){
                $promotionAwards[$key]['vendor_promotion_id'] = $value2['id'];

                $promotionAwards[$key]['user_id'] = $data['user_id'];
                $promotionAwards[$key]['points'] = $value2['points']*$value['multiplier'];
                $promotionAwards[$key]['multiplier'] = $value['multiplier'];
                $promotionAwards[$key]['description'] = $value['description'];
                $points += $promotionAwards[$key]['points'];

                $dataForPHub[] =  [
                                    "attribute" => $data['redeemersId'],
                                    "attribute_type" => "id",
                                    "points" => $promotionAwards[$key]['points'],
                                    "reward_type" => $rewardType
                                  ]; 
            }
        }
      }
      $maxAwardLimit = $this->_checkMaxAwardLimit($points);
      if($maxAwardLimit){
        throw new NotAcceptableException("NOTICE: Staff cannot issue more than ".$maxAwardLimit." points. Please contact an Admin User.");
      }

      $provideRewardResponse = $this->PeopleHub->bulkReward($this->Auth->user('vendor_peoplehub_id'), $dataForPHub);
      
      $promotionAwardsCollection = new Collection($promotionAwards);
      $promotionAwardsCollection = $promotionAwardsCollection->map(function($value, $key)use($provideRewardResponse, $patientsId){
          $value['peoplehub_transaction_id'] = $provideRewardResponse->data[$key]->transaction_id;
          $value['redeemer_peoplehub_identifier'] = $patientsId;
          $value ['vendor_id'] = $this->Auth->user('vendor_id');
          return $value;
      });
      $promotionAwards = $promotionAwardsCollection->toArray();

      $promotionAwards = $this->PromotionAwards->newEntities($promotionAwards);
      if($this->PromotionAwards->saveMany($promotionAwards)) {

          //update points taken in patient_visit_spendings table
          $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $data['redeemersId'], $points);

          $this->set('response', $promotionAwards);
          $this->set('_serialize', ['response']);
      }else{      
          throw new InternalErrorException(__('ENTITY_ERROR.'.json_encode($promotionAwards), 'Promotion Awards'));
      }
    }

/**
  * Tier method
  * This method is called when amount is spent by the patient. Calculate method in PatientTiersTable is called.
  * If tier upgrades mailer event is fired further if a gift coupon is associated to the tier then GiftCoupon method 
  * is called.
  * If year changes for a patient then mailer event is fired.
  *
  * @return \Cake\Network\Response
  * @throws \Cake\Network\Exception\InternalErrorException when data provided is not valid.
  * @throws \Cake\Network\Exception\BadRequestException if data is empty.
  * @throws \Cake\Network\Exception\MethodNotAllowedException if request is not post.
  * @author James Kukreja
  */

public function tier(){

    if (!$this->request->is('post')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->data;

    if(!$data)
        throw new BadRequestException(__('DATA_EMPTY'));

    $data['vendor_id'] = $this->Auth->user('vendor_id');

    $this->loadModel('TierAwards');

    //Calculate method in PatientTiers is called here.
    $tierInfo = $this->TierAwards
    ->Tiers
    ->PatientTiers
    ->calculate($data);

    //Method to give peoplehub points is called here.
    $provideRewardResponse = $this->_givePeoplehubPoints($data['redeemersId'], $tierInfo['points']);

    //Data to be stored in TierAwards Table is stored here
    $tierReward = [
    'user_id' => $this->Auth->user('id'), 
    'points' => (int) $tierInfo['points'], 
    'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 
    'tier_id' => $tierInfo['tierId'], 
    'amount' => $data['amount'],
    'redeemer_peoplehub_identifier' => $data['redeemersId'],
    'vendor_id' => $data['vendor_id']
    ];

    $tierAward = $this->TierAwards->newEntity();
    $tierAward = $this->TierAwards->patchEntity($tierAward, $tierReward);

    if(!$this->TierAwards->save($tierAward)){
        throw new InternalErrorException(__('ENTITY_ERROR', 'tier award'));
    }

    if(isset($data['first_name'])){
        $tierAward->first_name = $data['first_name'];
    }

    $tierAward->tier_name = $tierInfo['tierName'];
    $tierAward->name    = $this->TierAwards
    ->Users
    ->Vendors
    ->findById($data['vendor_id'])
    ->first()
    ->org_name;

    //update points taken in patient_visit_spendings table
    $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $data['redeemersId'], (int) $tierInfo['points'], $data['amount']);

    if($tierInfo['tierChange']){

        if(isset($data['attribute_type']) && $data['attribute_type'] == 'email'){

          $tierAward->email = $data['attribute'];
          $this->_fireEmailEvent($tierAward, 7);
        }


        //Gift coupon awarded to the patient if associated with a tier
        $giftCoupon = $this->TierAwards->Tiers->TierGiftCoupons->findByTierId($tierInfo['tierId'])->first();

        if($giftCoupon){

          $giftCouponAwardData = ['gift_coupon_id' => $giftCoupon->gift_coupon_id, 'user_id' => $this->Auth->user('id'), 'redeemer_peoplehub_identifier' => $data['peoplehub_identifier'], 'redeemer_name' => $data['patient_name'],'transaction_number' => $provideRewardResponse['response']->data->id, 'reason' => $tierAward->tier_name ,'status' => 1, 'vendor_id' => $data['vendor_id']];

          $saved =  $this->_giftCouponAward($giftCouponAwardData);

          if($saved[1]){

            Log::write('debug', 'Associated Gift Coupon Awarded');

          }else{

            Log::write('debug', 'Associated Gift Coupon could not be awarded');
          }

        }
    }

    if($tierInfo['yearChange'] && $data['attribute_type'] == 'email'){
        $tierAward->email = $data['attribute'];
        $this->_fireEmailEvent($tierAward, 8);
    }

    $this->set('response', ['status' => 'OK', 
        'data' =>   [
        'id' => $tierAward->id, 
        'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 
        'points' => $tierInfo['points']
        ] 
        ]);

    $this->set('_serialize', ['response']);
}

public function manual(){

    if (!$this->request->is('post')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    if(!isset($this->request->data['user_id'])){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'user_id'));
    }
    if(!isset($this->request->data['points'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'points'));
    }

    $maxAwardLimit = $this->_checkMaxAwardLimit($this->request->data['points']);
    if($maxAwardLimit){
      throw new NotAcceptableException("NOTICE: Staff cannot issue more than ".$maxAwardLimit." points. Please contact an Admin User.");
    }

    if(!isset($this->request->data['redeemersId'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemersId'));
    }

    $data = $this->request->data;



    if(!$data)
        throw new BadRequestException(__('DATA_EMPTY'));

    $this->loadModel('ManualAwards');

    $provideRewardResponse = $this->_givePeoplehubPoints($data['redeemersId'], $data['points']);

    $manualReward = [
    'user_id' => $data['user_id'], 
    'points' => (int) $data['points'], 
    'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 
    'description' => $data['description'],
    'redeemer_peoplehub_identifier' =>  $this->request->data['redeemersId'],
    'vendor_id' => $this->Auth->user('vendor_id')
    ];

    $manualAward = $this->ManualAwards->newEntity();
    $manualAward = $this->ManualAwards->patchEntity($manualAward, $manualReward);


    if(!$this->ManualAwards->save($manualAward)){
        throw new InternalErrorException(__('ENTITY_ERROR', 'manual award'));
    }
    
    //update points taken in patient_visit_spendings table
    $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $data['redeemersId'], $data['points']);

    $this->set('response', ['status' => 'OK', 
        'data' =>   [
        'id' => $manualAward->id, 
        'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 
        'points' => $data['points']
        ] 
        ]);

    $this->set('_serialize', ['response']);
}

public function giftCoupon(){
  //if request is not post, throw error.
  if (!$this->request->is('post')) {
    throw new MethodNotAllowedException(__('BAD_REQUEST'));
  }
  $data = $this->request->data;
        //check the request data and throw errors if anything is absent
  if(!isset($data['gift_coupon_id'])){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'gift_coupon_id'));
  }
  if(!isset($data['redeemer_peoplehub_identifier'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemer_peoplehub_identifier'));
  }
  if(!isset($data['redeemer_name'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemer_name'));
  }

  if(isset($data['vendor_id'])){
    $data = $this->_instantGiftCoupon($data['vendor_id']);
  }

  $this->loadModel('GiftCoupons');
  $giftCoupon = $this->GiftCoupons->findById($data['gift_coupon_id'])->first();

  if(!$giftCoupon){
      throw new InternalErrorException(__('RECORD_NOT_FOUND'));
  }

  $gcRedemptionResponse = $this->_redeemCredit($data['redeemer_peoplehub_identifier'], $giftCoupon->points, 'Gift Coupon');
          //check response and log in giftCouponAwards
  if(!$gcRedemptionResponse['status']){
      throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message)));
  }

  $giftCouponAwardData = ['gift_coupon_id' => $data['gift_coupon_id'], 'user_id' => $this->Auth->user('id'), 'redeemer_peoplehub_identifier' => $data['redeemer_peoplehub_identifier'], 'redeemer_name' => $data['redeemer_name'],'transaction_number' => $gcRedemptionResponse['response']->data->id ,'status' => 1, 'vendor_id' => $this->Auth->user('vendor_id')];

  $saved = $this->_giftCouponAward($giftCouponAwardData);
  
  if($saved[1]){
      $this->set('response', ['status' => 'ok', 'id' => $saved[0]]);
      $this->set('_serialize', ['response']);
  }
  else{
      throw new InternalErrorException(__('ENTITY_ERROR', 'gift coupon award'));
  }

} 

public function redeemInstantRewards($vendorId = null){
  //if request is not post, throw error.
  if (!$this->request->is('post')) {
    throw new MethodNotAllowedException(__('BAD_REQUEST'));
  }
  $data = $this->request->data;
  //check the request data and throw errors if anything is absent
  if(!isset($data['gift_coupon_id'])){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'gift_coupon_id'));
  }
  if(!isset($data['redeemer_peoplehub_identifier'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemer_peoplehub_identifier'));
  }
  if(!isset($data['redeemer_name'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemer_name'));
  }

  if(!isset($data['vendor_id'])){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor_id'));
  }

  $this->loadModel('Vendors');
  $vendorsData = $this->Vendors->findById($data['vendor_id'])
                               ->contain(['VendorSettings.SettingKeys' => function($q){
                                                  return $q->where(['name' => 'Live Mode']);
                                            }, 'Users' => function($r){
                                                  return $r->where(['role_id' => 2, 'status' => 1]);
                                            }
                                      ])
                               ->first();
  // pr($vendorsData);die;
  $userId = $vendorsData->users[0]->id;
  $liveMode = $vendorsData->vendor_settings[0]->value;
  $vendorsPhId = $vendorsData->people_hub_identifier;

  $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
  $this->loadComponent('InstantRewards', ['liveMode' => $liveMode]);

  $this->loadModel('GiftCoupons');
  $giftCoupon = $this->GiftCoupons->findById($data['gift_coupon_id'])->first();

  if(!$giftCoupon){
      throw new InternalErrorException(__('RECORD_NOT_FOUND'));
  }

  $gcRedemptionResponse = $this->_redeemCredit($data['redeemer_peoplehub_identifier'], $giftCoupon->points, 'Gift Coupon', $data['vendor_id'], $vendorsPhId);

  //check response and log in giftCouponAwards
  if(!$gcRedemptionResponse['status']){
      throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message)));
  }

  $giftCouponAwardData = ['gift_coupon_id' => $data['gift_coupon_id'], 'user_id' => $userId, 'redeemer_peoplehub_identifier' => $data['redeemer_peoplehub_identifier'], 'redeemer_name' => $data['redeemer_name'],'transaction_number' => $gcRedemptionResponse['response']->data->id ,'status' => 1, 'vendor_id' => $data['vendor_id']];

  $saved = $this->_giftCouponAward($giftCouponAwardData);
  
  if($saved[1]){
      $this->set('response', ['status' => 'ok', 'id' => $saved[0]]);
      $this->set('_serialize', ['response']);
  }
  else{
      throw new InternalErrorException(__('ENTITY_ERROR', 'gift coupon award'));
  }  
}

private function _giftCouponAward($giftCouponAwardData){

  $this->loadModel('GiftCouponAwards');
  $giftCouponAward = $this->GiftCouponAwards->newEntity($giftCouponAwardData);

  if($giftCouponAward->errors()){
      throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'));
  }

  $saved = $this->GiftCouponAwards->save($giftCouponAward);
  if($giftCouponAward->errors()){
      throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'.','.json_encode($giftCouponAward->errors())));
  }

  return [$giftCouponAward->id, true];

}

private function _checkMaxAwardLimit($points){

  $this->loadModel('VendorSettings');

  $maxAwardLimit = $this->VendorSettings
                        ->findByVendorId($this->Auth->user('vendor_id'))
                        ->contain(['SettingKeys' => function($q){
                                    return $q->where(['name' => 'Maximum Points Award Limit']);
                                  }])
                        ->first()
                        ->value;
  $maxAwardLimit = (int) $maxAwardLimit;

  if($points > $maxAwardLimit){
    return $maxAwardLimit;
  }

  return false;
}


private function _givePeoplehubPoints($patientsPeoplehubId, $points, $vendorId = null){
    
    if($vendorId == null){
      $vendorId = $this->Auth->user('vendor_id');
      $vendorPeopleHubId = $this->Auth->user('vendor_peoplehub_id');
    }else{
      $this->loadModel('Vendors');
      $vendorPeopleHubId = $this->Vendors->findById($vendorId)->first()->people_hub_identifier; 
    }

    $rewardType = $this->VendorSettings->findByVendorId($vendorId)->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Credit Type']);
        }])->first()->value;
        
        if(!is_string($rewardType)){
            throw new BadRequestException(__('INVALID_REWARD_TYPE'));
        } 
        $rewardCreditData = ['attribute' => $patientsPeoplehubId , 'attribute_type' => 'id' , 'points' => $points , 'reward_type' => $rewardType];

    $provideRewardResponse = $this->PeopleHub->provideReward($rewardCreditData, $vendorPeopleHubId);
    if(!$provideRewardResponse['status']){
        throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message)));
    }
    if(!$provideRewardResponse['response']->data->id){

        throw new InternalErrorException(__('PEOPLEHUB_TRANSACTION_FAILED'));
    }

    return $provideRewardResponse;

}

private function _redeemCredit($redeemersId, $points, $description = null, $vendorId = null, $vendorsPhId = null){
  
  if(!$vendorId){
    $vendorId = $this->Auth->user('vendor_id');
  }
  if(!$vendorsPhId){
    $vendorsPhId = $this->Auth->user('vendor_peoplehub_id');
  }
  $this->loadModel('VendorSettings');
  $rewardType = $this->VendorSettings->findByVendorId($vendorId)
                     ->contain(['SettingKeys' => function($q){
                                                                return $q->where(['name' => 'Credit Type' ]);
                                                              }
                      ])
                     ->first()
                     ->value; 

  if($rewardType == 'store_credit'){

    $rewardRedemptionData = ['user_id' => $redeemersId , 'points' => $points , 'reward_type' => $rewardType];

    $redeemRewardResponse = $this->PeopleHub->redeemReward($rewardRedemptionData, $vendorsPhId);

  }else{

    $rewardRedemptionData = ['user_id' => $redeemersId , 'description' => $description, 'service' => 'in_house', 'points' => $points , 'reward_type' => $rewardType];    
    
    $redeemRewardResponse = $this->PeopleHub->instantRedemption($rewardRedemptionData, $vendorsPhId);
  }

  if(!$redeemRewardResponse['status']){
      throw new BadRequestException(__(json_encode($redeemRewardResponse['response']->message)));
  }

  return $redeemRewardResponse;
}

public function rollback(){

  if(!$this->request->is('post')){
    throw new MethodNotAllowedException(__('BAD_REQUEST'));
  }
  if(!$this->request->data || !is_array($this->request->data)){
    throw new BadRequestException(__('BAD_REQUEST'));
  }

  $data = $this->request->data;
  // pr($data);die;
  if(!$this->request->data['award_type']){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'award_type'));
  }
  if(!$this->request->data['award_id']){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'award_id'));
  }
  if(!$this->request->data['redeemers_id']){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'redeemers_id'));
  }

  $status = true;
  $isPromotionAward = strpos($data['award_type'], 'Promotion Award');

  if($isPromotionAward !== false){
    $data['award_type'] = 'Promotion Award';
  };

  $isReferralAward = strpos($data['award_type'], 'Referral Award');

  if($isReferralAward !== false){
    $data['award_type'] = 'Referral Award';
  };

  $table = $this->_awardTypes[$data['award_type']];

  $this->loadModel($table);
  $transactions = $this->$table->find()
                               ->where(['id IN' => $data['award_id']])
                               ->all()->toArray();

   if(!$transactions || count($transactions) == 0 || $transactions[0]->vendor_id != $this->Auth->user('vendor_id') || $data['redeemers_id'] != $transactions[0]->redeemer_peoplehub_identifier){
    throw new BadRequestException(__('ENTITY_DOES_NOT_EXISTS', 'transaction'));
  }

  $data = ['transaction_id' => $transactions[0]->peoplehub_transaction_id];
  $response = $this->PeopleHub->rollbackAwards($this->Auth->user('vendor_peoplehub_id'), $data);
  // $response = [
  //               'status' => true,
  //               'data' => [
  //                 'id' => 10
  //               ]
  //             ];

  if(!$response){
    throw  new InternalErrorException(__('REQUEST_ERROR'));
  }
  if(!$response->status){
      throw new BadRequestException(__(json_encode($response['response']->message)));
  }


  //TODO: Find the entry in PatientVisitSpendings and deduct the amount/points and check for instant_reward_unlocked.
  // $this->loadModel('PatientVisitSpendings');
  // $spendingsData = $this->PatientVisitSpendings->findByPeoplehubUserId($data['redeemers_id'])
  //                                              ->where(['vendor_id' => $this->Auth->user('vendor_id')])
  //                                              ->first();
  
  //Saving the deletion_transaction_number returned
  $transactions = (new Collection($transactions))->map(function($value, $key) use($response){
                      $value->deletion_transaction_number = $response->data->id;
                      return $value;
                  });
  $transactions = $transactions->toArray();

  $saved = $this->$table->saveMany($transactions);
  if(!$saved){
    throw new InternalErrorException(__('ENTITY_ERROR', 'transaction'));
  }

  foreach ($transactions as $key => $transaction) {
    $isDeleted = $this->$table->delete($transaction);
    if(!$isDeleted){
      $status = false;
    }
    
  }
  // $isDeleted = $this->$table->deleteAll(['peoplehub_transaction_id IN' => $transactions[0]->peoplehub_transaction_id]);
  // if(!$isDeleted){
  //   $status = false;
  // }
  
  $response = ['status' => $status];

  $this->set('response', $response);
  $this->set('_serialize', 'response');
}

private function _fireEmailEvent($entity, $eventId){

    $event = new Event('User.afterCreate', $this, [
      'arr' => [
                'hashData' => $entity,
                'eventId' => $eventId, //give the event_id for which you want to fire the email
                'vendor_id' => $entity->vendor_id,
                ]
                ]);

    $this->eventManager()->dispatch($event);

}


// public function isAuthorized()
//   {

//     $action = $this->request->params['action'];
//     $controller = $this->request->params['controller'];

//     $this->loadModel('VendorSettings');
//     $vendorSettings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))->contain(['SettingKeys'])
//     ->where(['VendorSettings.value > '=> 0 ,'VendorSettings.value is NOT'=> null])->toArray();

//     // $vendorSettings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
//     //     ->contain(['SettingKeys' => function($q){
//     //         return $q->where(['type' => 'boolean']);
//     //     }
//     //     ])
//     $enabledFeatures = [];
//     //$enabledControllers = [];
//     foreach ($vendorSettings as $key => $value) {
//       switch ($value->setting_key->name) {
//         // pr($value); die;
//         case 'Tier Program':
//           $enabledFeatures[] = 'tier';
//           //$enabledControllers[] = 'Tiers';
//           break;

//         case 'Manual Points':
//           $enabledFeatures[] = 'manual';
//          // $enabledControllers[] = 'Award';
//           break;

//         case 'Gift Coupons':
//           $enabledFeatures[] = 'giftCoupon';
//           break;

//       }

//     }    
//        // pr($enabledFeatures); die;
//        // pr($action);die;
//     if (in_array($action, $enabledFeatures)) {
//       return true;
//     }else{
//       throw new MethodNotAllowedException(__('BAD_REQUEST'));
//     }

//   }


}
