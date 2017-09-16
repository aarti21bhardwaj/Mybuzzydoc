<?php
namespace App\Event;

use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Network\Exception;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\View\Helper\UrlHelper;
use Cake\Routing\Router;

class MilestoneEventListener implements EventListenerInterface {


	public function __construct(){
		  $controller = new Controller();
    	$this->GiftCouponAwards = $controller->loadModel('GiftCouponAwards');
      $this->GiftCoupons = $controller->loadModel('GiftCoupons');
    	$this->MilestoneLevelAwards = $controller->loadModel('MilestoneLevelAwards');
      $this->VendorSettings = $controller->loadModel('VendorSettings');
  }

  public function implementedEvents()
  {
      return [
          'Milestone.achieved' => 'processMilestoneAchievement',
      ];
  }


  //this function awards the rewards set for the acheived milestone level 
  public function processMilestoneAchievement($event, $data){

    $hashData = $data['hashData'];
    $rewardData = $hashData['reward_data'];
    Log::write('debug', "data recieved is: ".json_encode($hashData));
    
    $milestoneLevelPointsAward = NULL;
    $giftCouponAwardData = NULL;
    $milestoneLevelGcAward = NULL;
    foreach ($rewardData as $reward) {
      //if reward is of type "points", prepare data for entering in milestone_level_awards table.
      if($reward->reward_type->type == 'Points'){
          $milestoneLevelPointsAward[] = ['user_id'=>$hashData['user_id'], 'milestone_level_id' => $hashData['level_achieved'], 'points' => $reward->points, 'redeemer_peoplehub_identifier' => $hashData['redeemer_peoplehub_id'], 'vendor_id' => $hashData['vendor_id']];
      }
      //if reward is of the type gift coupon, prepare data for entering in milestone_level_awards and gift_coupon_awards table. 
      if($reward->reward_type->type == 'GiftCoupons'){
          $milestoneLevelGcAward[] = ['user_id'=>$hashData['user_id'], 'milestone_level_id' => $hashData['level_achieved'], 'redeemer_peoplehub_identifier' => $hashData['redeemer_peoplehub_id'], 'peoplehub_transaction_id' => $hashData['peoplehub_transaction_id'], 'vendor_id' => $hashData['vendor_id']];

          $giftCouponAwardData[] = ['gift_coupon_id' => $reward->reward_id, 'user_id' => $hashData['user_id'], 'redeemer_peoplehub_identifier' => $hashData['redeemer_peoplehub_id'], 'transaction_number' => 0, 'redeemer_name' => $hashData['patients_name'], 'status' => 1, 'reason' => 'milestone level '.$hashData['level_achieved'].' was achieved.', 'vendor_id' => $hashData['vendor_id']];
      }
    }
    
    if($milestoneLevelPointsAward){
      Log::write('debug', "milestoneLevelAwardData is:".json_encode($milestoneLevelPointsAward));
      //award points on peoplehub.
      $this->_awardPeopleHubPoints($hashData, $milestoneLevelPointsAward);
      
      //check if instant rewards have been unlocked
      $this->InstantRewards->checkStatus($hashData['vendor_id'], $hashData['redeemer_peoplehub_id'], $reward->points);

    }else{
      Log::write('debug', "There were no points to be awarded for this milestone level");
    }

    if($giftCouponAwardData){
        $this->_milestoneLevelAward($milestoneLevelGcAward);
        foreach ($giftCouponAwardData as $gcAward) {
          $this->_giftCouponAward($gcAward);
        }
    }else{
      Log::write('debug', "There were no gift coupons to be awarded for this milestone level"); 
    }

  }

  //awards points on peoplehub and then internally calls _milestoneLevelAward
  private function _awardPeopleHubPoints($hashData, $milestoneLevelAwardData){
    //fetch vendor settings to make the api request
    $vendorSettings = $this->VendorSettings->findByVendorId($hashData['vendor_id'])
                                       ->contain(['SettingKeys' => function($q){
                                            return $q->where(['name IN' => ['Credit Type', 'Live Mode']]);
                                       }])
                                       ->all();
    foreach ($vendorSettings as $setting) {
      if($setting->setting_key->name == 'Live Mode'){
        $liveMode = $setting->value;
      }
      if($setting->setting_key->name == 'Credit Type'){
        $creditType = $setting->value;
      }
    }

    Log::write('debug', $liveMode);
    Log::write('debug', $creditType);

    foreach ($milestoneLevelAwardData as $key=>$reward) {
      //prepare request data for hitting peoplehub api and hit the api per milestoneLevelAwardData.
      $awardPointsData = ['attribute_type' => 'id', 'attribute' => $hashData['redeemer_peoplehub_id'], 'points' => $reward['points'], 'reward_type' => $creditType];
      $controller = new Controller();
      $this->PeopleHub = $controller->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
      $provideRewardResponse = $this->PeopleHub->provideReward($awardPointsData, $hashData['vendor_peoplehub_id']);
      log::write('debug', json_encode($provideRewardResponse));

       if(!$provideRewardResponse['status']){
          log::write('debug', 'PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message));
          $provideRewardResponse['response']->data->id = NULL;
            if(!$provideRewardResponse['response']->data->id){
              log::write('debug', 'PEOPLEHUB_TRANSACTION_FAILED');
              $provideRewardResponse['response']->data->id = NULL;
            }
        }
        Log::write('debug', 'peoplehub_transaction_id is :'.json_encode($provideRewardResponse['response']->data->id));
        $milestoneLevelAwardData[$key]['peoplehub_transaction_id'] = $provideRewardResponse['response']->data->id;
    }
    $this->_milestoneLevelAward($milestoneLevelAwardData);
  }

  //creates the entry for the award in milestone_level_awards table
  private function _milestoneLevelAward($milestoneLevelAwardData){
      $milestoneLevelAwards = $this->MilestoneLevelAwards->newEntities($milestoneLevelAwardData);
      Log::write('debug', 'attempting to save the milestone Award now. Here is the entity'.json_encode($milestoneLevelAwards));
      if($this->MilestoneLevelAwards->saveMany($milestoneLevelAwards)){
        return true;
        Log::write('debug', "the following milestoneLevelAwards were given".json_encode($milestoneLevelAwards));
      } else{
        return false;
        Log::write('debug', "ENTITY_ERROR",'milestone awards');
      }
  }

  //create the entry for the award in gift_coupon_awards table.
  private function _giftCouponAward($gcAward){
    $gcStatus = $this->GiftCoupons->findById($gcAward['gift_coupon_id'])->where(['vendor_id' => $gcAward['vendor_id']])->first();
    if(!$gcStatus){
      return true;
    }
    $giftCouponAwards = $this->GiftCouponAwards->newEntity($gcAward);
    Log::write('debug', 'attempting to save the gift coupon Award now. Here is the entity'.json_encode($giftCouponAwards));
    if($this->GiftCouponAwards->save($giftCouponAwards)){
      return true;
      Log::write('debug', "the following giftCouponAwards were given".json_encode($giftCouponAwards));
      }else{
        return false;
          Log::write('debug', "ENTITY_ERROR".json_encode($gcAward->errors()),'gift coupon awards');
      }
  }

}

?>
