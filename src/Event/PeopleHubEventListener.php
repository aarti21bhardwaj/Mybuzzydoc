<?php
namespace App\Event;

use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\Collection\Collection;
use Cake\Core\Configure;


class PeopleHubEventListener implements EventListenerInterface {

  public function __construct(){
    $controller = new Controller();
    $this->VendorPatients = $controller->loadModel('VendorPatients');
    $this->LegacyRedemptions = $controller->loadModel('LegacyRedemptions');
    $this->Referrals = $controller->loadModel('Referrals');
    $this->Users = $controller->loadModel('Users');
    $this->Vendors = $controller->loadModel('Vendors');
    $this->Events = $controller->loadModel('Events');
    $this->VendorSettings = $controller->loadModel('VendorSettings');
    $this->VendorPatientUnsubscribedEvents = $controller->loadModel('VendorPatientUnsubscribedEvents');

  }

  public function implementedEvents()
  {
    // pr('here');die;
    return [
      'PeoplehubPatientApi.registerPatient' => 'onRegistration',
      'PeoplehubPatientApi.afterRedemption' =>  'onRedemption',
      'PeoplehubPatientApi.forgotPassword' =>  'onForgotPassword',
      'PeoplehubPatientApi.Referrals' =>  'onReferral',
      'PeoplehubPatientApi.viewVendor' =>  'onViewVendor',
      'PeoplehubPatientApi.unsubscribeEvent' =>  'unsubscribedEmail',
      'PeoplehubPatientApi.getunsubscribeEvent' =>  'getunsubscribedEmail',
      'PeoplehubPatientApi.redeemProduct' =>  'onProductRedeem',
    ];
  }


  public function onRegistration($event, $response)
  { 
      if(!$response){
          Log::write('debug', "Empty Data");
      }
      $vendorSettings = $this->VendorSettings->findByVendorId($response->data->vendor_id)
                              ->contain(['SettingKeys'])
                              ->all()
                              ->indexBy('setting_key.name')
                              ->toArray();

      $selfSignUpSetting = $vendorSettings['Patient Self Sign Up']['value'];

      if(!$selfSignUpSetting){

        throw new UnauthorizedException(__("Vendor setting for patient self sign up is disabled"));
      }  
      $vendorPatient = [
                          'vendor_id' => $response->data->vendor_id,
                          'patient_peoplehub_id' => $response->data->id,
                          'patient_name' => $response->data->name,
                          'username' => $response->data->username,
                          'password' => "Redacted for security purposes",
                          'user_id' => null
                    ];
                    // pr($vendorPatient); die;
      if(isset($response->data->guardian_email) && $response->data->guardian_email){
        $vendorPatient['email'] = $response->data->guardian_email;
      }else if(isset($response->data->email) && $response->data->email){
        $vendorPatient['email'] = $response->data->email;
      }else{
        $vendorPatient['email'] = false;
      }

      if(isset($response->data->phone) && $response->data->phone){
        $vendorPatient['phone'] = $response->data->phone;
      }
      $vendorPatients = $this->VendorPatients->newEntity($vendorPatient);
      Log::write('debug', "Vendor Patient register from patient-portal is : ".json_encode($vendorPatients)); 
      $vendorPatients = $this->VendorPatients->save($vendorPatients);
      if(!$vendorPatients){
          Log::write('debug', "Vendor Patient could not be saved.");
          return false;
        }
      Log::write('debug', "Vendor Patient saved.");

  }

  public function onRedemption($entity, $response){
    if(!$response){
          Log::write('debug', "Empty Data");
      }

      $reward = $this->LegacyRedemptions
              ->LegacyRewards
              ->findByName('Amazon/Tango')
              ->where(['vendor_id' => 1])
              ->first();

      $response['legacy_redemption_status_id'] = 3;
      $response['legacy_reward_id'] = $reward->id;

      $pointValue = (int) Configure::read('pointsValue');
      $points = (int) $response['points'];
      $response['legacy_redemption_amounts'][0]['amount'] = $points / $pointValue;

      $legacyRedemptions = $this->LegacyRedemptions->newEntity($response);
      Log::write('debug', "Legacy Redemption data is: ".json_encode($legacyRedemptions)); 
      $legacyRedemptions = $this->LegacyRedemptions->save($legacyRedemptions);  
      if(!$legacyRedemptions){
          Log::write('debug', "transaction number of patient could not be saved.");
          return false;
      }
      Log::write('debug', "transaction number of patient is saved.");
  }

  public function onReferral($event, $data){
    if(!$data){
        Log::write('debug', "Empty Data");
    }
    $referral = $this->Referrals->newEntity();
    $referralData = [
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'phone' => $data['phone'],
                        'refer_to' => $data['refer_to'],
                        'refer_from' => $data['refer_from'],
                        'status' => $data['status'],
                        'vendor_id' => $data['vendor_id'],
                        'subject' => $data['subject'],
                        'description' => $data['description']
                    ];
        Log::write('debug', "Referrals has been saved : ".json_encode($referralData));
        $referral = $this->Referrals->patchEntity($referral, $referralData);
        if(!$referral){
          Log::write('debug', "Referrals could not be saved.");
          return false;
        }
        Log::write('debug', "Referral Saved.");
      if ($this->Referrals->save($referral)){
      $url = Router::url('/', true);
      $url = $url.'referral-leads/add/'.$referral->uuid;
      $referral->link = $url;
      $referral->subject = $data['subject'];
      $referral->description = $data['description'];
      $referral->name = ucwords($data['first_name']." ".$data['last_name']);
      $referral->email = $referral->refer_to;
      $referral->cc = $referral->refer_from;
      $event = new Event('Referral.requestSent', $this, [
                    'arr' => [
                            'hashData' => $referral,
                            'eventId' => 4, //give the event_id for which you want to fire the email
                            'vendor_id' => $referral->vendor_id
                        ]
                    ]);
      $this->Referrals->eventManager()->dispatch($event);
    }

  }

  public function onForgotPassword($event, $response){
    // pr($response); die;
    $referral = [
                  'url' => $response[1]->data->url,
                  'name' => $response[1]->data->name,
                  'email' => $response[1]->data->email,
                  'token' => $response[1]->data->token,
                  'ref' => $response[0]['ref'],
                  'vendor_id' => $response[0]['vendor_id'],
                  'username' => $response[1]->data->username

                ];
    $referral = $this->Users->newEntity($referral);

    $vendorData = $this->Users->Vendors->findById($response[0]['vendor_id'])->first();
    $referral->reset_link = $referral['ref'].'reset_password?resetToken='.$referral['token'];
    $referral->ref = $referral['ref'];
    $referral->patient_name = $referral['name'];
    $referral->username = $referral['username'];
    $referral->email = $referral['email'];
    $referral->first_name = $referral['first_name'];
    $referral->last_name = $referral['last_name'];
    $referral->phone = $referral['phone'];
    $referral->password = $referral['password'];
    $referral->org_name = $vendorData->org_name;

    $event = new Event('Patient.resetPassword', $this, [
                            'arr' =>[
                                      'hashData' => $referral,
                                      'eventId' => 10,
                                      'vendor_id' => $response[0]['vendor_id'] //give the event_id for which you want to fire the email
                                    ]
    ]);
    
    $this->Users->eventManager()->dispatch($event);
  }

  public function onViewVendor($event, $response){
    $vendor = $this->Vendors->findById($response['vendor_id'])->contain(['ReferralTemplates','VendorLocations','VendorSettings.SettingKeys','VendorPromotions', 'VendorPromotions.Promotions', 'ReviewSettings', 'Tiers.TierPerks', 'VendorSurveys.VendorSurveyQuestions.SurveyQuestions.Questions.QuestionTypes', 'VendorMilestones.MilestoneLevels',])
            ->first();

    // pr($vendor); die;
      if(!$vendor){
        throw new BadRequestException(__('NOT_FOUND','vendor'));
      }
      $vendor->events = $this->_getEvents();
      $settings = (new Collection($vendor->vendor_settings))->combine('setting_key.name', 'value')->toArray();
      $vendor->vendor_legacy_rewards = $this->_getVendorLegacyRewards($vendor->id, $settings);
      $vendor->credit_type = $settings['Credit Type'];
      return $vendor;
  }

  protected function _getEvents(){
     $eventId = [2, 7, 8, 11];
              
      $eventName = $this->Events->find()
                    ->where(['id IN' => $eventId])
                    ->select(['name', 'id'])
                    ->all()
                    ->toArray();
              
      return $eventName;
  }

  private function _getVendorLegacyRewards($vendorId, $settings){

    $vendorLegacyRewards = [];

    if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 0){
        // pr(' m here when pS enabled');
        $vendorLegacyRewards = $this->Vendors->VendorLegacyRewards->findByVendorId($vendorId)
                                                        ->contain(['LegacyRewards'])
                                                        ->where(['LegacyRewards.vendor_id' => $vendorId, 'VendorLegacyRewards.status' => 1, 'LegacyRewards.product_type_id NOT IN' => [1,3]])
                                                        ->all()
                                                        ->toArray();
        // pr($vendorlegacyReward);
    }else if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 1){
        $vendorLegacyRewards = $this->Vendors->VendorLegacyRewards->findByVendorId($vendorId)
                                                        ->contain(['LegacyRewards'])
                                                        ->where(['LegacyRewards.status' => 1, 'VendorLegacyRewards.status' => 1, 'LegacyRewards.product_type_id NOT IN' => [1,3]])
                                                        ->all()
                                                        ->toArray();
    }
        // pr($vendorLegacyRewards);die;

    return $vendorLegacyRewards;
  }

  public function unsubscribedEmail($event, $response){
    $vendorPatient = $this->VendorPatients->findByVendorId($response['vendor_id'])
                                    ->where(['patient_peoplehub_id' => $response['patient_id']])
                                    ->first();
    $event = [];
    if(!isset($response['event'])){
      // pr('m here'); die;
      $vendorPatientUnsubscribedEvents = $this->VendorPatientUnsubscribedEvents->findByVendorPatientId($vendorPatient->id)
                                                                               ->all();
      if($vendorPatientUnsubscribedEvents){
        foreach ($vendorPatientUnsubscribedEvents as $key => $value) {
          $event[$value->event_id] = false;
        }
      }
      return $event;
    }
    
    $data = [];
    foreach ($response['event'] as $key => $value){
      
      if($value !=1){
        $data[] = [   'event_id' => $key,
                      'vendor_patient_id' => $vendorPatient->id,
                      'status' => 1
                  ]; 
        }

    } 

    $this->VendorPatientUnsubscribedEvents->deleteAll(['vendor_patient_id' => $vendorPatient->id]);
     $vendorPatientUnsubscribedEvents = $this->VendorPatientUnsubscribedEvents->newEntity();
     $vendorPatientUnsubscribedEvents = $this->VendorPatientUnsubscribedEvents->patchEntities($vendorPatientUnsubscribedEvents, $data);
     if($this->VendorPatientUnsubscribedEvents->saveMany($vendorPatientUnsubscribedEvents)){
        Log::write('debug', "unsubscribed email successfully");
        return $vendorPatientUnsubscribedEvents;
     }else{
      Log::write('debug', "not unsubscribed");
     }
  }

  public function onProductRedeem($event, $data){

    $data['legacy_redemption_status_id'] = 3;

    //Find the requested legacy reward
    $reward = $this->LegacyRedemptions
            ->LegacyRewards
            ->findById($data['legacy_reward_id'])
            ->first();
    
    if(!$reward){

      throw new NotFoundException(__('Legacy reward not found'));
    }

    //Find Vendor and vendor Settings
    $vendor = $this->Vendors
                   ->findById($data['vendor_id'])
                   ->contain(['VendorSettings.SettingKeys' => function($q){
                                                      return $q->where(['name IN' => ['Credit Type',  'Live Mode']]);
                                                    }
                    ])
                   ->first();

    //get Vendor Peoplehub identifier
    if($vendor->people_hub_identifier){
      $vendorPeoplehubId = $vendor->people_hub_identifier;
    }else{
      $vendorPeoplehubId = $vendor->sandbox_people_hub_identifier;
    }
    //format settings index by name
    $settings = (new Collection($vendor->vendor_settings))->combine('setting_key.name', 'value')->toArray();
    
    $controller = new Controller();
    $this->PeopleHub = $controller->loadComponent('PeopleHub', ['liveMode' => $settings['Live Mode']]);

    //Call method in peoplehub component according to credit type
    if($settings['Credit Type'] == 'store_credit'){

        $rewardRedemptionData = ['user_id' => $data['redeemer_peoplehub_identifier'] ,'points' => $reward->points, 'reward_type' => $settings['Credit Type']];
        $redeemReward = $this->PeopleHub->redeemReward($rewardRedemptionData, $vendorPeoplehubId);
    }else{

        $rewardRedemptionData = ['user_id' => $data['redeemer_peoplehub_identifier'], 'description' => $reward->name, 'service' => 'in_house', 'points' => $reward->points , 'reward_type' => $settings['Credit Type']];    
      
        $redeemReward = $this->PeopleHub->instantRedemption($rewardRedemptionData, $vendorPeoplehubId);
    }

    if(!$redeemReward['status']){
        
        throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($redeemRewardResponse['response']->message)));
    }

    if(!$redeemReward['response']){

      throw new InternalErrorException(__('Error in response from peoplehub'));
      Log::write('debug', $redeemReward);

    }

    //Create entry in Legacy Redemptions table if redemption of points is successful
    $data['transaction_number'] = $redeemReward['response']->data->id;

    $legacyRedemption = $this->LegacyRedemptions->newEntity();
    
    if(isset($data['points'])){

      $pointValue = (int) Configure::read('pointsValue');
      $points = (int) $data['points'];

      $data['legacy_redemption_amounts'][0]['amount'] = $points / $pointValue;


      $legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $data, ['associated' => 'LegacyRedemptionAmounts']);

    }else{

      $legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $data); 

      $legacyRedemption->points = $reward->points;

    }

    if($legacyRedemption->errors()){

      throw new BadRequestException(__("ENTITY_NOT_CORRECT"));
    }

    if(!$this->LegacyRedemptions->save($legacyRedemption)){

      throw new InternalErrorException(__('COULD_NOT_SAVED'));
    }
    
    return $redeemReward;
  }
}
?>
