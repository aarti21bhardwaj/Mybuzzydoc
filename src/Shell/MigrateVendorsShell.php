<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Http\Client;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\BadRequestException;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;

class MigrateVendorsShell extends Shell
{

  private $_host = null;
  private $_token  = null;
  private $_vendorId = null;
  public function initialize()
  {
    parent::initialize();
    $this->_host = Configure::read('application.livePhUrl');
  }

  public function connectWithOldBuzzyDoc($sql){

    $host = Configure::read('oldBuzzyDoc.host');
    $username = Configure::read('oldBuzzyDoc.username');
    $password = Configure::read('oldBuzzyDoc.password');
    $database = Configure::read('oldBuzzyDoc.database');

    ConnectionManager::drop('my_connection');
    $config = [
    'className' => 'Cake\Database\Connection',
    'driver' => 'Cake\Database\Driver\Mysql',
    'persistent' => false,
    'host' => $host,
    'username' => $username,
    'password' => $password,
    'database' => $database,
    'encoding' => 'utf8',
    'timezone' => 'UTC',
    'flags' => [],
    'cacheMetadata' => true,
    'log' => true,
    'quoteIdentifiers' => false,
    'url' => env('DATABASE_URL', null),
    ];
    ConnectionManager::config('my_connection', $config);
    $conn = ConnectionManager::get('my_connection');
    $response = $conn->execute($sql);

    return $response;
  }


  public function migrateAllData($clinicId,$reward_method,$points_column_name){

    $vendorModel = TableRegistry::get('Vendors');
    // $response = $vendorModel->connection()->transactional(function () use ($vendorModel, $clinicId, $reward_method, $points_column_name){
      $vendor = $this->getVendorsData($clinicId);
      $vendorId = $vendor[0]->id;
      $users = $this->getUsersData($clinicId, $vendorId);
      $promotions = $this->getPromotions($clinicId, $vendorId);
      $reviewSettings = $this->getReviewSettings($clinicId, $vendorId);
      $vendorDepositBalance = $this->getVendorDepositBalance($clinicId, $vendorId);
      $vendorLocations = $this->getVendorLocations($clinicId, $vendorId);
      $referralSettings = $this->getReferralSettings($clinicId, $vendorId);
      $doctorId = $this->_getNewDoctorId($clinicId, $vendorId);
      if($doctorId){
        $this->getAuthorizeDotNet($clinicId, $doctorId);
        $this->registerVendor($vendorId, $doctorId);        
      }
      $this->out('Vendor Has Been Migrated');
      $this->getVendorCardRequests($clinicId, $vendorId);
      $this->out('Vendor card logs Has Been Migrated');
      $this->migrateRegUser($clinicId , $vendorId,$reward_method,$points_column_name);
      $this->out('Vendor\'s registered user Has Been Migrated');
      $this->migrateUnRegUser($clinicId , $vendorId,$reward_method);
      $this->out('Vendor\'s unregistered user Has Been Migrated');
      $this->migrateVendorCardData($clinicId , $vendorId);
      $this->out('Vendor\'s available cards Has Been Migrated');
      $this->legacyRewardsForMigratedVendors($clinicId);
      $this->out('Vendor\'s migration completed');
    // });
  }

  public function getVendorsData($id){
    $results = $this->connectWithOldBuzzyDoc('select * from clinics where id = '.$id);
    $vendorSettings = Configure::read('vendor.defaultSettings');
    //Migrated vendors should be live by default
    $vendorSettings[0]['value'] = 1;
    $vendorPlans = ['plan_id' => 3];

    $oldBuzzydocVendor = ['old_vendor_id' => $id];

    $vendor = [];
    foreach ($results as $key => $row) {
      $vendor[] = [
      'org_name' =>    $row['display_name'],
      'min_deposit' =>   ($row['minimum_deposit'] <= 0) ? 500 : $row['minimum_deposit'],
      'threshold_value' =>  ($row['minimum_deposit'] <= 0) ? 250 : $row['minimum_deposit']/2,
      'is_legacy' => 0,
      'template_id' => 7,
      'vendor_settings' => $vendorSettings,
      'vendor_plans' => [$vendorPlans],
      'old_buzzydoc_vendor' => $oldBuzzydocVendor
      ];
    }
    if(!count($vendor)){
      $this->out('Did not find vendor');
      pr($vendor);
      return false;
    }
    $this->out('Got vendor');
    pr($vendor);
    $vendor = $this->saveData('Vendors', ['VendorSettings', 'VendorPlans', 'OldBuzzydocVendors'], $vendor);
    return $vendor;
  }

  public function getUsersData($clinicId, $vendorId){

    $results = $this->connectWithOldBuzzyDoc('select * from staffs where clinic_id = '.$clinicId);
    $users = [];
    foreach ($results as $key => $row) {

      if($row['staff_first_name'] == null || $row['staff_last_name'] == null || $row['staff_email'] == null){
        continue;
      }
      $users[$key] = [
      'vendor_id' => $vendorId,
      'first_name' =>    $row['staff_first_name'],
      'last_name' =>   $row['staff_last_name'],
      'email' => $row['staff_email'],
      'status' => $row['active'],
      'phone' => 9999999999
      ];

      $this->loadModel('Users');
      $users[$key]['username'] = $row['staff_id'];
      $users = $this->Users->findByUsername($row['staff_id'])->all()->count();
      if($users){
        $users[$key]['username'] = $row['staff_id'] + $users;
      }

      // $users[$key]['password']  = $this->_randomPassword();
      $users[$key]['password']  = '123456789';
      if(
        $row['staff_role'] == 'A' ||
        $row['staff_role'] == 'D' ||
        $row['staff_role'] == 'Administrator' ||
        $row['staff_role'] == 'Doctor'){

        $users[$key]['role_id'] = 2;

    }else{

      $users[$key]['role_id'] = 3;
    }

  }

  pr($users);
  $this->out('Got Users');
  $users = $this->saveData('Users', [], $users);
  return $users;
}

public function getVendorLocations($clinicId, $vendorId){
  $results = $this->connectWithOldBuzzyDoc('select * from clinic_locations where clinic_id = '.$clinicId);
  $urlFromClinicLocations = $this->connectWithOldBuzzyDoc('select clinics.facebook_url from clinic_locations inner join clinics on clinics.id = clinic_locations.clinic_id where clinics.id = '.$clinicId);
  $vendorLocations = [];
  foreach ($results as $key => $location) {
    $fbUrl = '';
    foreach ($urlFromClinicLocations as $url) {
      $fbUrl = $url['facebook_url'];
    }
    $vendorLocations[] = [
    'vendor_id'  => $vendorId,
    'address' => $location['address'].','.$location['city'].','.$location['state'].' '.$location['pincode'],
        //do we need to store the facebook url from clinics table ?
    'fb_url' => $fbUrl,
    'google_url' => $location['google_business_page_url'],
    'yelp_url' => $location['yelp_business_page_url'],
    'ratemd_url' => $location['ratemds_business_page_url'],
    'healthgrades_url' => $location['healthgrades_business_page_url'],
    'is_default' => $location['is_prime']
    ];
  }
  if(!count($vendorLocations)){
    $this->out('Didnot find Vendor Locations');
    pr($vendorLocations);
    return false;
  }
  $this->out('Got Vendor Locations');
  pr($vendorLocations);
  $vendorLocations = $this->saveData('VendorLocations', [], $vendorLocations);
  return $vendorLocations;
}

public function getReviewSettings($clinicId, $vendorId){

  $results = $this->connectWithOldBuzzyDoc("select * from promotions where clinic_id = ".$clinicId." and is_lite=0 and is_global=0 and `default` = 2");
  $settings = [
  'vendor_id' => $vendorId,
  'yahoo_points' => 0,
  'rating_points' => 0,
  'review_points' => 0,
  'fb_points' => 0,
  'gplus_points' => 0,
  'yelp_points' => 0,
  'ratemd_points' => 0,
  'healthgrades_points' => 0
  ];

  foreach ($results as $key => $row) {

    switch($row['description']){

      case 'Rate':
      $settings['rating_points'] = $row['value'];
      break;
      case 'Review':
      $settings['review_points'] = $row['value'];
      break;
      case 'Facebook Share':
      $settings['fb_points'] = $row['value'];
      break;
      case 'Google Share':
      $settings['gplus_points'] = $row['value'];
      break;
      case 'Yelp Share':
      $settings['yelp_points'] = $row['value'];
      break;
      case 'RateMDs Share':
      $settings['ratemd_points'] = $row['value'];
      break;
      case 'Healthgrades Share':
      $settings['healthgrades_points'] = $row['value'];
      break;
    }

  }

  $reviewSettings = [$settings];
  if(!count($reviewSettings)){
    $this->out('Didnot find vendor');
    pr($reviewSettings);
    return false;
  }
  $this->out('Got Review Settings');
  pr($reviewSettings);
  $reviewSettings = $this->saveData('ReviewSettings', [], $reviewSettings);
  return $reviewSettings;

}

public function getPromotions($clinicId, $vendorId){

  $results = $this->connectWithOldBuzzyDoc("select * from promotions where clinic_id = ".$clinicId." and is_lite=0 and is_global=0 and `default` != 2");
  $promotions = [];
  foreach ($results as $key => $row) {
    if($row['value'] < 0){
      continue;
    }
    $promotions[$key] = [

    'name' => $row['display_name'] != null ? $row['display_name'] : $row['description'],
    'description' => $row['description'],
    'points' => $row['value'],
    'vendor_id' => $vendorId,
    'status' => 1,
    'frequency' => 0
    ];
    $promotions[$key]['vendor_promotions'][] = [
    'vendor_id' => $vendorId,
    'published' => null,
    'points' => $row['value'],
    'frequency' => 0
    ];

  }
  if(!count($promotions)){
    $this->out('Didnot find Promotions');
    pr($promotions);
    return false;
  }
  $this->out('Got Promotions');
  pr($promotions);
  $promotions = $this->saveData('Promotions', ['VendorPromotions'], $promotions);
  return $promotions;

}


public function getReferralSettings($clinicId, $vendorId){
    //check if any local referral settings exist for the vendor in admin_settings table, if yes, fetch the points from admin settings and levels data from lead_levels, else, fetch data from lead_levels corresponding to the vendor's industry id.
  $localVendorReferralSetting = $this->connectWithOldBuzzyDoc('select * from admin_settings where clinic_id = '.$clinicId);
  $vendorReferralSettings = [];
  foreach ($localVendorReferralSetting as $key => $setting) {
    $localSetting =  json_decode($setting['setting_data'], true);

      //creating the set of lead level ids to pass in WHERE clause of lead levels query.
    $leadLevels = '(';
    foreach ($localSetting as $leadLevelId => $leadPoints) {
      if(is_integer($leadLevelId)){
        $leadLevels = $leadLevels.$leadLevelId.", ";
      }
    }
    $leadLevels = strrev(substr(strrev($leadLevels), 2));
    $leadLevels = $leadLevels.')';
    pr($leadLevels);
    $leadLevelsData = $this->connectWithOldBuzzyDoc('select * from lead_levels where id in '.$leadLevels);

    foreach ($leadLevelsData as $leadLevel) {
      $vendorReferralSettings[] = [
      'vendor_id' => $vendorId,
      'referral_level_name' => $leadLevel['leadname'],
      'referrer_award_points' => $localSetting[$leadLevel['id']],
      'referree_award_points' => 0,
      'status'=> 1
      ];
    }
  }

  if(!count($vendorReferralSettings)){
    $results = $this->connectWithOldBuzzyDoc('select lead_levels.* from lead_levels left join clinics on clinics.industry_type = lead_levels.industryId where clinics.id='.$clinicId);
    foreach ($results as $leadLevel) {
      $vendorReferralSettings[] = [
      'vendor_id' => $vendorId,
      'referral_level_name' => $leadLevel['leadname'],
      'referrer_award_points' => $leadLevel['leadpoints'],
      'referree_award_points' => 0,
      'status'=> 1

      ];

    }
  }
  if(!count($vendorReferralSettings)){
    $this->out('Didnot find vendor');
    pr($vendorReferralSettings);
    return false;
  }
  $this->out('Got Vendor Referral Settings');
  $vendorReferralSettings[] = [
  'vendor_id' => $vendorId,
  'referral_level_name' => 'Failed Referral',
  'referrer_award_points' => 0,
  'referree_award_points' => 0,
  'status' => 1
  ];
  pr($vendorReferralSettings);

  $vendorReferralSettings = $this->saveData('VendorReferralSettings', [], $vendorReferralSettings);
  return $vendorReferralSettings;
}

private function _getNewDoctorId($clinicId, $vendorId){

  $results = $this->connectWithOldBuzzyDoc('select * from payment_details inner join staffs ON payment_details.doctor_id = staffs.id where staffs.clinic_id= '.$clinicId);
  $this->loadModel('Users');

  $username = null;
  foreach ($results as $key => $row) {
    $username = $row['staff_id'];
  }
  pr('getting id of doctor having username :'.$username);
  pr($username);
  pr($vendorId);
  if($username){
    $user = $this->Users->find()->where(['vendor_id' => $vendorId,'username'=>$username,'status IN'=>[0,1]])->first();
    if(!$user){
      $user = $this->Users->findByVendorId($vendorId)->first();
    }
    if(!$user){
      echo 'Unable to get new user id of the doctor. please call register vendor on peoplehub and get authorizeNetDetails manually. ';
      return false;
    }

    $this->out($user->username);
  }else{
    return false;
  }
  return $user->id;
}

public function getVendorDepositBalance($clinicId, $vendorId){

  // $results = $this->connectWithOldBuzzyDoc("select * from invoices where id=(select max(id) from invoices where clinic_id = ".$clinicId.") and clinic_id = ".$clinicId."");
   $results = $this->connectWithOldBuzzyDoc("SELECT * FROM integrateortho_live4.invoices  where clinic_id= ".$clinicId." order by payed_on desc limit 1");

  foreach ($results as $key => $row) {

    $balance = [
    'vendor_id' => $vendorId,
    'balance' => $row['current_balance']
    ];


  }
  if(isset($balance['balance']) && $balance['balance']){
   $vendorDepositBalance = [$balance];
   $this->out('Got Vendor Deposit Balance');
   pr($vendorDepositBalance);
   $vendorDepositBalance = $this->saveData('VendorDepositBalances', [], $vendorDepositBalance);
   return $vendorDepositBalance;
 }else{
  return TRUE;
}

}

public function getAuthorizeDotNet($clinicId, $userId){


  $results = $this->connectWithOldBuzzyDoc("select * from payment_details where clinic_id = ".$clinicId."");
  if(!count($results)){
    pr('vendor does not have authorizeDotNet profile, check is it a legacy vendor');
    return true;
  }
  foreach ($results as $key => $row) {

    $info = [
    'user_id' => $userId,
    'profile_identifier' => $row['customer_account_profile_id'],
    'is_card_setup' => 1,
    'payment_profileid' =>$row['customer_account_id']
    ];


  }
  $authorizeDotNet = [$info];
  if(!count($authorizeDotNet)){
    $this->out('Didnot find vendor');
    pr($authorizeDotNet);
    return false;
  }
  $this->out('Got Vendor Deposit Balance');
  pr($authorizeDotNet);
  $authorizeDotNet = $this->saveData('AuthorizeNetProfiles', [], $authorizeDotNet);
  return $authorizeDotNet;
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

  public function getReferrals($clinicId, $vendorId){
    $results = $this->connectWithOldBuzzyDoc('SELECT a.email  as "user-email", a.parents_email, b.*  FROM `refers` b LEFT JOIN users a ON b.user_id = a.id WHERE clinic_id = '.$clinicId);

    $this->loadModel('VendorReferralSettings');
    $firstReferralLevel = $this->VendorReferralSettings->findByVendorId($vendorId)
    ->where(['referral_level_name !=' => 'Failed Referral'])->first()->id;
    $referralsData = [];

    $this->loadModel('VendorPatients');
    foreach ($results as $key => $referral) {
      // pr($referral);
      $patientId = $this->VendorPatients->findByOldBuzzydocPatientIdentifier($referral['user_id'])->where(['vendor_id' => $vendorId])->first()->id;

      if($referral['user-email'] || $referral['parents_email']){
        $referralsData[] = [

        'vendor_id' => $clinicId,
        'refer_from' => ($referral['user-email'] ? 'test_'.$referral['user-email'] : 'test_'.$referral['parents_email']),
        'refer_to' => 'test_'.$referral['email'],
        'peoplehub_identifier' => $patientId,
        'status' => ($referral['status'] == 'Pending' || $referral['status'] == 'Completed') ? 1 : 0,
        'name' => $referral['first_name'].' '.$referral['last_name'],
        'phone' => $referral['phone'] ? $referral['phone'] : '0000000000',
        'referral_leads' => [[
        'name' => $referral['first_name'].' '.$referral['last_name'],
        'email' => 'test_'.$referral['email'],
        'phone' => $referral['phone'] ? $referral['phone'] : '0000000000',
        'vendor_referral_settings_id' => $referral['level'] ? NULL : $firstReferralLevel,
        'status' => ($referral['status'] == 'Pending' || $referral['status'] == 'Completed') ? 1 : 0,
        'vendor_id' => $clinicId,
        'preferred_talking_time' => $referral['prefer_time'] ? $referral['prefer_time'] : ' ',
        ]]
        ];
      }
    }
    pr($referralsData);
    $referrals = $this->saveData('Referrals', ['ReferralLeads'], $referralsData);
    return $referrals;
  }

    /*
    *
    * This method accepts data in the following format:
    *    $modelName = 'Vendors';
    *    $associated = ['VendorSettings', 'VendorPlans'];
    *    $data = [
    *              [
    *               'org_name' => 'Hull & Coleman Orthodontics',
    *               'min_deposit' => 1250,
    *               'threshold_value' => 625,
    *               'is_legacy' => 0,
    *               'vendor_settings' => [
    *                                     [
    *                                        'value' => 0,
    *                                        'setting_key_id' => 7,
    *                                      ]
    *                                    ],
    *               'vendor_plans' => [
    *                                    ['plan_id' => 3],
    *                                  ]
    *               ]
    *             ];
    */
    public function saveData($modelName, $associated, $data){
      $associated = ['associated' => $associated];

      $temp = $this->loadModel($modelName);
      $entity = $this->$modelName->newEntities($data, $associated);
      // pr($entity);die;
      $model = TableRegistry::get($modelName);
      $entities = [];

      $response = $model->connection()->transactional(function () use ($model, $modelName, $associated, $data, $entities){

        $entities = $model->newEntities($data, $associated);
        foreach ($entities as $entity) {
          if($entity->errors()){
            pr($entity->errors());
            throw new BadRequestException(__('KINDLY_PROVIDE_VALID_DATA'));
          }
        }

        if($model->saveMany($entities, $associated)){

          pr('Data has been saved in '.$modelName.' table and associated '.json_encode($associated).' tables');
          // pr($entities);
          return $entities;
        }else{
          pr($entities);
          throw new Exception(__('ENTITY_ERROR', $modelName.' entity'));
        }

      });
      return $response;
    }


    public function registerVendor($vendorId, $userId){
      $this->loadModel('Users');
      $vendorsData = $this->Users->findById($userId)
      ->where(['vendor_id' => $vendorId])
      ->contain(['Vendors'])
      ->first();
      // pr($vendorsData);die;
      $data = $vendorsData['vendor'];
      unset($vendorsData['vendor']);
      $data['users'] = $vendorsData;
      pr($data);
      //die;
      $response = $this->registerVendorOnPeopleHub($data);
      pr($response);
      echo 'peoplehub id is';
      var_dump($response->data->id);
      if($response->status){
        $this->loadModel('Vendors');
        $this->loadModel('VendorCardSeries');
        $req = [
        'vendor_card_series'=>array(array('series'=>$response->data->vendor_card_series[0]->reseller_card_series->series,
          'ph_vendor_card_series_identifier'=>$response->data->vendor_card_series[0]->id
          ))
        ];
        $vendor = $this->Vendors->findById($vendorId)->first();
        $vendor = $this->Vendors->patchEntity($vendor,$req);
        $vendor->people_hub_identifier = $response->data->id;
        $vendor->sandbox_people_hub_identifier = "live";
        if($this->Vendors->save($vendor)){
          $this->out('Vendors peoplehub Id has been saved');
          pr($vendor);
        }else{
          pr($vendor->errors());die;
        }
      }
    }


    public function registerVendorOnPeopleHub($vendorData)
    {
      $token = $this->getResellerToken();
      if(!$token['status']){
        return $token;
      }
      $token = $token['token'];

      //Set up Headers
      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
      //setup request body in $data array
      $data=array();
      // pr($http);die;
      $data['name']=$vendorData->org_name;
      // $data['status'] = $userData->vendors[0]['status'];
      $data['vendor_contacts']['email']=$vendorData->users['email'];
      $data['vendor_contacts']['phone']=$vendorData->users['phone'];
      $data['vendor_contacts']['is_primary']=1;
      $data['vendor_reward_types'][0]['reward_method_id']=1;
      $data['vendor_reward_types'][0]['status']=1;
      $data['vendor_reward_types'][1]['reward_method_id']=2;
      $data['vendor_reward_types'][1]['status']=1;
      $data['vendor_reward_types'][2]['reward_method_id']=3;
      $data['vendor_reward_types'][2]['status']=1;
      // pr($data);die;
      $response = $http->post(  $this->_host.'/api/reseller/add-vendor',json_encode($data));
      //pr($response->body());
      if(!$response->isOk()){
        return $response->body();
      }
      return json_decode($response->body());
      //pr($response->data->id);
    }

    public function  getResellerToken(){
      $response = $this->_renewResellerToken();
      if($response->isOk()){
        $response = json_decode($response->body());
        if($response->status){
          return ['status'=>true,'token'=>$response->data->token];
        }
        return $response;
      }else{
        $err =array();
        $err['status']=false;
        $err['data']['message']='Unable to get reseller token.';
        $err['data']['data']=json_decode($response->body());
        return $err;
      }
    }

    public function getRatingsAndReviews($clinicId, $vendorId, $userId, $vendorLocationId){

      $results = $this->connectWithOldBuzzyDoc(
        'select * from rate_reviews left join users on rate_reviews.user_id=users.id where rate_reviews.clinic_id = '.$clinicId);
      $review = [];
      $request = [];
      $this->loadModel('VendorPatients');
      foreach ($results as $key => $row) {

        $patientId = $this->VendorPatients->findByOldBuzzydocPatientIdentifier($row['user_id'])->where(['vendor_id' => $vendorId])->first()->id;

        if($row['review'] == null && $row['rate'] == null){

          $request[$key] = [
          'user_id' => $userId,
          'rating' => 0,
          'review' => 0,
          'fb' => 0,
          'google_plus' => 0,
          'yelp' => 0,
          'ratemd' => 0,
          'healthgrades' => 0,
          'vendor_location_id' => $vendorLocationId,
          'people_hub_identifier' => $patientId,
          'email_address' => $row['email'],
          'yahoo' => 0
          ];

        }else{


          $review[$key] =[

          'vendor_location_id' => $vendorLocationId,
          'review' => $row['review'],
          'rating' => $row['rate'],
          'review_request_status' => [
          'user_id' => $userId,
          'rating' => 1,
          'review' => 1,
          'fb' => 1,
          'google_plus' => 0,
          'yelp' => 0,
          'ratemd' => 0,
          'healthgrades' => 0,
          'vendor_location_id' => $vendorLocationId,
          'people_hub_identifier' => $patientId,
          'email_address' => $row['email'],
          'yahoo' => 0
          ],
          ];

        }

      }

      $reviews = [$review];
      $requests = [$requests];
      if(!count($reviews) && !count($requests)){
        $this->out('Didnot find reviews or requests');
        pr($reviews);
        return false;
      }
      $this->out('Got Vendor Reviews');
      pr($reviews);
      pr($requests);
      if(count($reviews) > 0){
        $reviews = $this->saveData('VendorReviews', ['ReviewRequestStatuses'], $reviews);
      }
      if(count($requests) > 0){
        $requests = $this->saveData('ReviewRequestStatuses', [''], $requests);
      }
      return ['reviews' => $reviews, 'requests' => $requests];
    }


    private function  _renewResellerToken(){

      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Basic '.base64_encode(Configure::read('reseller.client_id').':'.Configure::read('reseller.client_secret'))]]);
      $response = $http->post( $this->_host.'/api/reseller/token' );
      return $response;
    }

    public function  getVendorToken($vendorId){

      $response = $this->_renewVendorToken($vendorId);
      if($response->isOk()){
        $response = json_decode($response->body());
        if($response->status){
          // $this->_session->write('v_t', $response->data->token);
          return ['status'=>true,'token'=>$response->data->token];
        }else{
          return $response;
        }
      }else{
        $err =array();
        $err['status']=false;
        $err['data']['message']='Unable to get vendor token.';
        $err['data']['data']=json_decode($response->body());
        return $err;
      }

    }

    private function  _renewVendorToken($vendorId){

      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Basic '.base64_encode(Configure::read('reseller.client_id').':'.Configure::read('reseller.client_secret'))]]);
      $data = ['vendor_id' => $vendorId];
      $response = $http->post( $this->_host.'/api/vendor/token', json_encode($data) );
      return $response;
    }

    public function registerPatientAtBuzzy($vendorId,$phId,$oldBuzzyId = false){
      $this->loadModel('Vendors');
      $data = [
      'patient_peoplehub_id' => $phId,
      'vendor_id' => $vendorId

      ];
      if($oldBuzzyId){
        $data['old_buzzydoc_patient_identifier'] = $oldBuzzyId;
      }

      $vendorPatient = $this->Vendors->VendorPatients->newEntity();

      $vendorPatient = $this->Vendors->VendorPatients->patchEntity($vendorPatient, $data);
      if(!$this->Vendors->VendorPatients->save($vendorPatient)){
        throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
      }else{
         $this->out('patient registered');
      }
      return true;
    }

    public function rewardManualPoints($patientsPhId, $points, $rewardType,$userId,$vendorId,$token){
      if($points <= 0){
        return true;
      }
      $data = ['attribute' => $patientsPhId , 'attribute_type' => 'id' , 'points' => $points , 'reward_type' => $rewardType];

      $url = '/api/vendor/rewardCredits/';

      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
      $data['ref'] = Configure::read('authorizeDotNet.redirectUrl');
      $response = $http->post($this->_host.$url, json_encode($data));
      // pr($data);

      pr($response->body());
      $response = json_decode($response->body());
      // pr($http);
      // pr($response);die;
      if(!$response->status){
        throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($response->message)));
      }
      if(!$response->data->id){

        throw new InternalErrorException(__('PEOPLEHUB_TRANSACTION_FAILED'));
      }
      $this->loadModel('ManualAwards');
      $manualReward = [
      'user_id' => $userId,
      'points' => $points,
      'peoplehub_transaction_id' => $response->data->id,
      'description' => 'Migrated User',
      'redeemer_peoplehub_identifier' => $patientsPhId,
      'vendor_id' => $vendorId
      ];

      $manualAward = $this->ManualAwards->newEntity();
      $manualAward = $this->ManualAwards->patchEntity($manualAward, $manualReward);


      if(!$this->ManualAwards->save($manualAward)){
        throw new InternalErrorException(__('ENTITY_ERROR', 'manual award'));
      }

      return $response->data->id;
    }


    public function migrateRegUser($id, $vendorId, $reward_method, $points_column_name){
      $results = $this->connectWithOldBuzzyDoc('select *  from clinic_users left join card_numbers on clinic_users.card_number = card_numbers.card_number and  clinic_users.clinic_id = card_numbers.clinic_id inner join users on  clinic_users.user_id = users.id  where clinic_users.clinic_id= '.$id.' and card_numbers.status=2');
      $addedEmail = array();
      $updatedUsername = array();
      $duplicateEmail = array();
      // pr($token);die;
      foreach ($results as $key => $row) {
        $username = null;
        $email = null;
        $guardian_email = null;
        $name = (isset($row['first_name']) && !empty($row['first_name']))?$row['first_name']:'';
        $name = (isset($row['last_name']) && !empty($row['last_name']))?$name.' '. $row['last_name']:'';
        $name = ($name) ? $name:'anonymous';
        $email = (isset($row['email']) && !empty($row['email']))?$row['email']:'';
        $guardian_email = (isset($row['parents_email']) && !empty($row['parents_email']))?$row['parents_email']:'';
        if ($email && $guardian_email && !filter_var($guardian_email, FILTER_VALIDATE_EMAIL)) {
          $username = $guardian_email;
          $guardian_email = $email;
          $email = null;
        }
        if($email && !$guardian_email){
          if(in_array($email, $addedEmail)){
            $this->log('Found dulicate email with no parent email','error');
            $this->log($row,'error');
            $guardian_email  =$email;
            $duplicateEmail[] = [$email, 'duplicate email with no parent email'];
          }else{
            $username = $email;
          }
        }
        if($email && $guardian_email && filter_var($guardian_email, FILTER_VALIDATE_EMAIL)){
          if(!in_array($email, $addedEmail)){
            $username = $email;
          }else{
            $this->log('Found dulicate email with set parent email as guardian email' ,'error');
            $this->log($row,'error');
            $duplicateEmail[] = [$email, 'duplicate email with parent_email as guardian email'];
            $email = null;
          }
        }
        if (!$email && $guardian_email && !filter_var($guardian_email, FILTER_VALIDATE_EMAIL)) {
          $username = $guardian_email;
          $guardian_email = null;
          $email = null;
        }

        $data= [
        'name'=>$name,
        'password'=>(isset($row['password']) && !empty($row['password']))?$row['password']:$this->_randomPassword(),
        'phone'=>(isset($row['phone']) && !empty($row['phone']))?$row['phone']:'',
        'role_id'=>2,
        'reason'=>'migrated from old buzzydoc, so reason not present'
        ];
        $this->loadModel('Vendors');
        $vendorInfo = $this->Vendors->findById($vendorId)->contain(['VendorCardSeries'])->first();
        if(empty($vendorInfo->vendor_card_series)){
          pr('vendor do not have any seris. kindly define a series first');
          die;
        }
        $phId = $vendorInfo->people_hub_identifier;
        if(isset($row['card_number']) && !empty($row['card_number'])){
          $reqData=[];
          $reqData['card_number'] = sprintf("%010d", $row['card_number']);
          $reqData['series']=$vendorInfo->vendor_card_series[0]->series;
          $reqData['complete_card_number'] = $reqData['series'].$reqData['card_number'];
          $reqData['vendor_id']=$phId;
          $reqData['vendor_card_series_id'] = $vendorInfo->vendor_card_series[0]->ph_vendor_card_series_identifier;
          $data['user_cards'] = array($reqData);
        }

        if(empty($username)){
          if(empty($name)){
            $username = 'anonymous'.$key.$row['id'];
          }else{
            $username = $name.$key;
          }
        }
        if($guardian_email){
          $data['guardian_email'] = strtolower($guardian_email);
        }
        $username = preg_replace('/[^A-Za-z0-9\.\@ -]/', '', $username);
        $username = (str_replace(' ','', $username));
        if(in_array(strtolower($username), $updatedUsername)){
          $username = $username.$key;
        }
        $updatedUsername[] =   $data['username'] = strtolower($username);
        if($email){
          $data['email'] = $email;
        }
        if(isset($data['email']) && !empty($data['email'])){
          $data['email'] = strtolower($data['email']);
          $addedEmail[] = $data['email'];
        }

        pr($data);
        if(!$this->_token){
          $this->_token = $this->getVendorToken($phId);
        }
        if($key % 50 == 0){
          $this->_token = $this->getVendorToken($phId);
        }
        $token = $this->_token;
        // $token = $this->getVendorToken($phId);
        if(!$token['status']){
          return $token;
        }
        $token = $token['token'];
        $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
        $response = $http->post(  $this->_host.'/api/vendor/migrate-user',json_encode($data));
        // pr($response->body());die;
        if(!$response->isOk()){
          $this->Log('registerd user migration  vendor id = '.$vendorId.' clinic id = '.$id,'error');
          $this->Log('query data set','error');
          $this->log($row,'error');
          $this->Log('registerd user migration request data','error');
          $this->Log($data,'error');
          $this->Log('response data','error');
          $this->Log($response->body(),'error');
          continue;
        }

        $response = json_decode($response->body());
        pr($response);
        if(!$response->status){
          $this->Log('registerd user migration  vendor id = '.$vendorId.' clinic id = '.$id,'error');
          $this->Log('query data set','error');
          $this->log($row,'error');
          $this->Log('registerd user migration request data','error');
          $this->Log($data,'error');
          $this->Log('response data','error');
          $this->Log($response,'error');
          continue;
        }
        $peopleHubIdentifier = $response->data->id;
        $data['people_hub_identifier'] =$peopleHubIdentifier;
        $this->registerPatientAtBuzzy($vendorId,$peopleHubIdentifier,$row['user_id']);
        $this->loadModel('Users');
        $userId = $this->Users->findByVendorId($vendorId)->contain(['Roles'=>['conditions'=>['name'=>'staff_admin']]])->first()->id;
        
        if(isset($row[$points_column_name]) && !empty($row[$points_column_name])){
          $this->rewardManualPoints($peopleHubIdentifier,$row[$points_column_name],$reward_method,$userId,$vendorId,$token);
        } 
      }

      pr($duplicateEmail);
    }

    public function migrateUnRegUser($id, $vendorId,$reward_method){
      $results = $this->connectWithOldBuzzyDoc('SELECT card_numbers.*, sum(unreg_transactions.amount) as points  FROM unreg_transactions inner join card_numbers on card_numbers.card_number = unreg_transactions.card_number and card_numbers.clinic_id = unreg_transactions.clinic_id where card_numbers.clinic_id='.$id.' and card_numbers.status != 2 and card_numbers.text is not null group by unreg_transactions.card_number,card_numbers.id');
      $usersArray = array();
      $addedEmail = array();
      $duplicateEmail = array();
      // pr($token);die;
      foreach ($results as $key => $row) {

        $textData = json_decode($row['text'],true);
        if($textData){
          $name = (isset($textData['first_name']) && !empty($textData['first_name']))?$textData['first_name']:'';
          $name = (isset($textData['last_name']) && !empty($textData['last_name']))?$name.' '. $textData['last_name']:'';
          $email = (isset($textData['email']) && !empty($textData['email']))?$textData['email']:'';
        }else{
          $name = 'anonymous';
          $email = "";
        }
        if(!$name){
          $name = 'anonymous';
        }

        $data= [
        'name'=>strtolower($name),
        'password'=>(isset($row['password']) && !empty($row['password']))?$row['password']:$this->_randomPassword(),
        'role_id'=>2,
          // 'status'=>(isset($row['status']) && !empty($row['status']))?$row['status']:'',
        'username'=>'anonymous'.$key.$row['card_number'],
          // 'relationship_id'=>1,
        'reason'=>'migrated from old buzzydoc, so reason not present'
        ];
        if($email){
          if(in_array($email, $addedEmail)){
            $duplicateEmail[]=[$email, 'duplicate email for unknown reason'];
            $email = $key.$email;
          }
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = strtolower($email);
            $addedEmail[]=strtolower($email);
          }
        }
        $this->loadModel('Vendors');
        $vendorInfo = $this->Vendors->findById($vendorId)->contain(['VendorCardSeries'])->first();
        if(empty($vendorInfo->vendor_card_series)){
          pr('vendor do not have any seris. kindly define a series first');
          die;
        }
        $phId = $vendorInfo->people_hub_identifier;
        if(isset($row['card_number']) && !empty($row['card_number'])){
          $reqData=[];
          $reqData['card_number'] = sprintf("%010d", $row['card_number']);
          $reqData['series']=$vendorInfo->vendor_card_series[0]->series;
          $reqData['complete_card_number'] = $reqData['series'].$reqData['card_number'];
          $reqData['vendor_id']=$phId;
          $reqData['vendor_card_series_id'] = $vendorInfo->vendor_card_series[0]->ph_vendor_card_series_identifier;
          $data['user_cards'] = array($reqData);
        }
        pr($data);
        $token = $this->getVendorToken($phId);
        if(!$token['status']){
          return $token;
        }
        $token = $token['token'];
        $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
        $response = $http->post(  $this->_host.'/api/vendor/migrate-user',json_encode($data));
        // pr($response->body());die;
        if(!$response->isOk()){
          $this->Log('unregisterd user migration  vendor id = '.$vendorId.' clinic id = '.$id,'error');
          $this->Log('query data set','error');
          $this->log($row,'error');
          $this->Log('registerd user migration request data','error');
          $this->Log($data,'error');
          $this->Log('unregistered response data','error');
          $this->Log($response,'error');
          continue;
        }

        $response = json_decode($response->body());
        pr($response);
        if(!$response->status){
          $this->Log('unregisterd user migration  vendor id = '.$vendorId.' clinic id = '.$id,'error');
          $this->Log('query data set','error');
          $this->log($row,'error');
          $this->Log('registerd user migration request data','error');
          $this->Log($data,'error');
          $this->Log('unregistered response data','error');
          $this->Log($response,'error');
          continue;
        }
        $peopleHubIdentifier = $response->data->id;
        $this->registerPatientAtBuzzy($vendorId,$peopleHubIdentifier);
        $this->loadModel('Users');
        $userId = $this->Users->findByVendorId($vendorId)->contain(['Roles'=>['conditions'=>['name'=>'staff_admin']]])->first()->id;
        if(isset($row['points']) && !empty($row['points'])){
          $this->rewardManualPoints($peopleHubIdentifier,$row['points'],$reward_method,$userId,$vendorId,$token);
        }

      }

      pr($duplicateEmail);
    }

//     public function migratePointsForUsers ($id, $vendorId,$reward_method) {
//       $this->loadModel('VendorPatients');
//       $vendorPatients = $this->VendorPatients->find()->where(['vendor_id'=>$vendorId])->all()->toArray();
//       $processingPatients = array();
//       foreach ($vendorPatients as $patient) {
//         $processingPatients[$patient->old_buzzydoc_patient_identifier] = $patient->patient_peoplehub_id;
//       };


//       $results = $this->connectWithOldBuzzyDoc('select *  from clinic_users left join card_numbers on clinic_users.card_number = card_numbers.card_number and  clinic_users.clinic_id = card_numbers.clinic_id inner join users on  clinic_users.user_id = users.id  where clinic_users.clinic_id= '.$id.' and card_numbers.status=2');
//       $addedEmail = array();
//       $updatedUsername = array();
//       $duplicateEmail = array();
//       pr($processingPatients);
//       foreach ($results as $key => $row) {

//         $this->loadModel('Vendors');
//         $phId = $this->Vendors->findById($vendorId)->first()->people_hub_identifier;
//         if(!$this->_token){
//           $this->_token = $this->getVendorToken($phId);
//         }
//         if($key % 50 == 0){
//           $this->_token = $this->getVendorToken($phId);
//         }
//         $token = $this->_token;
//         // $token = $this->getVendorToken($phId);
//         if(!$token['status']){
//           return $token;
//         }
//         $token = $token['token'];

//         $peopleHubIdentifier = $processingPatients[$row['user_id']];
//         $this->loadModel('Users');
//         $userId = $this->Users->findByVendorId($vendorId)->contain(['Roles'=>['conditions'=>['name'=>'staff_admin']]])->first()->id;
//         // pr($userId);
//         if(isset($row['points']) && !empty($row['points'])){
//           //wallet credit
//           $this->rewardManualPoints($peopleHubIdentifier,$row['points'],$reward_method,$userId,$vendorId,$token);
//         }

//       }


//     }

//     public function associateUserToVendor($id){

//       $currentVendor = null;
//       $this->loadModel('VendorPatients');
//       $vendorPatients = $this->VendorPatients->findByVendorId($id)->all()->toArray();
// // $processingPatients = array();
//       $this->loadModel('Vendors');


//       foreach ($vendorPatients as $key=>$patient) {

//         if(!$currentVendor){
//           $currentVendor = $patient->vendor_id;
//           $this->_vendorId = $this->Vendors->findById($currentVendor)->first()->people_hub_identifier;
//         }else{
//           if($currentVendor!= $patient->vendor_id){
//             $currentVendor = $patient->vendor_id;
//             $this->_vendorId = $this->Vendors->findById($currentVendor)->first()->people_hub_identifier;
//             $this->_token = null;
//           }
//         }
//         if(!$this->_token){
//           $this->_token = $this->getVendorToken($this->_vendorId);
//         }
//         if($key % 50 == 0){
//           $this->_token = $this->getVendorToken($this->_vendorId);
//         }
//         $ptData = $patient->patient_peoplehub_id;
// // pr($ptData);die;
//         $this->out("associating patient having peoplehub id  = ".$patient->patient_peoplehub_id. " with vendor id ".$this->_vendorId);
//         $response = $this->_associateUserToVendorPh($ptData);
//         pr($response);
//         if(!$response){
//           $this->Log('associating patient with vendor = '.$currentVendor.' patient people hub id = '.$ptData,'error');
//           $this->Log('Didnot receive response from peoplehub.','error');
//           continue;
//         }elseif(!$response->status){
//           $this->Log('associating patient with vendor = '.$currentVendor.' patient people hub id = '.$ptData,'error');
//           $this->Log($response,'error');
//           continue;
//         }else{
//           $this->out("done");
//         }
//       }

//     }

//     private function _associateUserToVendorPh($ptData){
//       $token = ($this->_token['token']);
//       $data = ["people_hub_identifier" => $ptData];
//       $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
//       $response = $http->post($this->_host.'/api/vendor/associate-vendor-to-user', json_encode($data));
//       if(!$response->isOk()){
//         return $response->body();
//       }
//       return json_decode($response->body());
//     }

    public function patientSearchTest($clinicId, $id){
      $results = $this->connectWithOldBuzzyDoc('select *  from clinic_users left join card_numbers on clinic_users.card_number = card_numbers.card_number and  clinic_users.clinic_id = card_numbers.clinic_id inner join users on  clinic_users.user_id = users.id  where clinic_users.clinic_id= '.$clinicId.' and card_numbers.status=2');
      $addedEmail = array();
      $updatedUsername = array();
      $duplicateEmail = array();



      foreach ($results as $key => $row) {
        if(!$this->_token){
          $this->_token = $this->getVendorToken($id);
        }
        if($key % 30 == 0){
          $this->_token = $this->getVendorToken($id);
        }
        $keyToSearch = $row['first_name'];
        if(empty($keyToSearch)){
          if(isset($row['email']) && !empty($row['email'])){
            $keyToSearch = $row['email'];
          }elseif(isset($row['parents_email']) && !empty($row['parents_email'])){
            $keyToSearch = $row['parents_email'];
          }elseif(isset($row['card_number']) && !empty($row['card_number'])){
            $keyToSearch = $row['card_number'];
          }else{
            Log::write('info', "didn't get any identifier to test");
          }
        }
//        $this->out("testing for key ---> ".$keyToSearch." in vendor ".$id);

        $result = $this->_searchApiPh($keyToSearch);
        if(!$result){
          Log::write('info', 'testing search for vendor id '.$id);
          Log::write('info', $row);
        }
  //      $this->out("search result = ".$result);
        // pr($row);die;
      }
    }
    private function _searchApiPh($data){
      $count = 0;
      $token = ($this->_token['token']);
      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);

      $response = $http->get($this->_host.'/api/vendor/user-search?value='.$data);
      if(!$response->isOk()){
        Log::write('info', "error occured".$response);
        Log::write('info', "error body".$response->body);
      }
      $response = json_decode($response->body());
      if(!$response->status){
        Log::write('info', "Status false returned");
        Log::write('info', $response);
      }else{
        if((!isset($response->data->users)) || (isset($response->data->users) && !count($response->data->users))){
          Log::write('info', "No result found for key --->".$data);
        }else{
          $count = count($response->data->users);
        }

      }
      return $count;
    }

    public function getVendorCardRequests($clinicId, $vendorId){
      $this->loadModel('VendorCardSeries');
      $vendorCardInfo = $this->VendorCardSeries->findByVendorId($vendorId)->first();  
      $results = $this->connectWithOldBuzzyDoc("Select * from integrateortho_live4.card_logs where clinic_id = ".$clinicId);


      $vendorCardRequests = [];

      foreach ($results as $key => $row) {

        $vendorCardRequests[] = [
        'vendor_card_series' => $vendorCardInfo->series,
        'vendor_id' => $vendorId,
        'status' => 0,
        'is_issued' => 1,
        'remark' => 'Cards issued successfully.',
        'created' => $row['log_date'],
        'modified' => $row['log_date'],
        'count' => $row['no_of_card'],
        'start' => $row['range_from'],
        'end' => $row['range_to']
        ];
      }

      pr($vendorCardRequests);
      $this->out('Got Vendor Card Requests');
      $vendorCardRequests = $this->saveData('VendorCardRequests', [], $vendorCardRequests);
      pr($vendorCardRequests);
    }

    public function migrateVendorCardData($clinicId, $vendorId){

      $results = $this->connectWithOldBuzzyDoc("SELECT * FROM integrateortho_live4.card_numbers where clinic_id = ".$clinicId." and status = 1 and text is null and card_number not in (SELECT card_number FROM integrateortho_live4.clinic_users where clinic_id = ".$clinicId.")");
      $reqData = array();
      $this->loadModel('Vendors');
      $vendorInfo = $this->Vendors->findById($vendorId)->contain(['VendorCardSeries'])->first();
      if(empty($vendorInfo->vendor_card_series)){
        pr('vendor do not have any seris. kindly define a series first');
        die;
      }
      $phId = $vendorInfo->people_hub_identifier;
      foreach ($results as $key => $row) {
        $data=[];
        $data['card_number'] = sprintf("%010d", $row['card_number']);
        $data['series']=$vendorInfo->vendor_card_series[0]->series;
        $data['complete_card_number'] = $data['series'].$data['card_number'];
        $data['vendor_id']=$phId;
        $data['vendor_card_series_id'] = $vendorInfo->vendor_card_series[0]->ph_vendor_card_series_identifier;
        $reqData[] = $data;
      }
      $token = $this->getVendorToken($phId);
      $token = $token['token'];
      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
      $response = $http->post($this->_host.'/api/vendor/migrate-cards', json_encode($reqData)); 
      if(!$response->isOk()){
        Log::write('info', "error occured".$response);
        pr($response);
        Log::write('info', "error body".$response->body());
        pr($response->body());die;
      }
      $response = json_decode($response->body());
      if(isset($response->status)){
        if($response->status){
          pr($response->data);
          pr('cards migrated');
          die;
        }
      }else{
       pr($response->body());die;
     }
   }


   public function migratePointsForPatients ($id, $vendorId,$reward_method,$points_column) {
    $this->loadModel('VendorPatients');
    $vendorPatients = $this->VendorPatients->find()->where(['vendor_id'=>$vendorId])->all()->toArray();
    $processingPatients = array();
    foreach ($vendorPatients as $patient) {
      $processingPatients[$patient->old_buzzydoc_patient_identifier] = $patient->patient_peoplehub_id;
    };


    $results = $this->connectWithOldBuzzyDoc('select *  from clinic_users left join card_numbers on clinic_users.card_number = card_numbers.card_number and  clinic_users.clinic_id = card_numbers.clinic_id inner join users on  clinic_users.user_id = users.id  where clinic_users.clinic_id= '.$id.' and card_numbers.status=2');
    $addedEmail = array();
    $updatedUsername = array();
    $duplicateEmail = array();
    pr($processingPatients);
    foreach ($results as $key => $row) {

      $this->loadModel('Vendors');
      $phId = $this->Vendors->findById($vendorId)->first()->people_hub_identifier;
      if(!$this->_token){
        $this->_token = $this->getVendorToken($phId);
      }
      if($key % 50 == 0){
        $this->_token = $this->getVendorToken($phId);
      }
      $token = $this->_token;
// $token = $this->getVendorToken($phId);
      if(!$token['status']){
        return $token;
      }
      $token = $token['token'];
      if(isset($processingPatients[$row['user_id']]) && !empty($processingPatients[$row['user_id']])){
        $peopleHubIdentifier = $processingPatients[$row['user_id']];
        $this->loadModel('Users');
        $userId = $this->Users->findByVendorId($vendorId)->contain(['Roles'=>['conditions'=>['name'=>'staff_admin']]])->first()->id;
// pr($userId);
        if(isset($row[$points_column]) && !empty($row[$points_column])){
//wallet credit
          $this->rewardManualPoints($peopleHubIdentifier,$row[$points_column],$reward_method,$userId,$vendorId,$token);
        }
      }else{

        $this->out('patient\'s peoplehub id is missing for old patient id '.$row['user_id']);
        continue;
      }
    }


  }

   public function deleteVendor($vendorId){

        $this->loadModel('Vendors');
        $phId = $this->Vendors->findById($vendorId)->first()->people_hub_identifier;
  if($phId){
        if(!$this->_token){
        $this->_token = $this->getVendorToken($phId);
        }
        $token = $this->_token;
        if(!$token['status']){
          return $token;
        }
        $token = $token['token'];

        $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
        $url = '/api/vendor/rollback_vendor';
        $response = $http->post($this->_host.$url);

        if(!$response->isOk()){
          pr($response);
          pr($response->body());die;
        }
        $this->out('deleted from peoplehub');
  }else{
$this->out('peoplehub identifier not available, so not able to delete peoplehub data');
}
        $users = $this->Vendors->Users->findByVendorId($vendorId)->all()->extract('id')->toArray();
        $deleteUsers = $this->Vendors->Users->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted Users');
        $deleteManualAwards = $this->Vendors->ManualAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted ManualAwards');
        $deletePromotionAwards = $this->Vendors->PromotionAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted PromotionAwards');
        $deleteReferralAwards = $this->Vendors->ReferralAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted ReferralAwards');
        $deleteReviewAwards = $this->Vendors->ReviewAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted ReviewAwards');
        $deleteTierAwards = $this->Vendors->TierAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted TierAwards');

        $deleteSurveyAwards = $this->Vendors->SurveyAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted SurveyAwards');
        $deleteLegacyRedemptions = $this->Vendors->LegacyRedemptions
                                        ->find()->where(['vendor_id' => $vendorId])
                                        ->all();
       
        foreach($deleteLegacyRedemptions as $entity) {
          $this->Vendors->LegacyRedemptions->delete($entity);
        }
        $this->out('deleted LegacyRedemptions');
        $deleteGiftCouponAwards = $this->Vendors->GiftCouponAwards
                                        ->find()->where(['vendor_id' => $vendorId])
                                        ->all();
        foreach($deleteGiftCouponAwards as $entity) {
          $this->Vendors->GiftCouponAwards->delete($entity);
        }
        $this->out('deleted GiftCouponAwards');
        $deleteMilestoneLevelAwards = $this->Vendors->MilestoneLevelAwards->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted MilestoneLevelAwards');
        $deleteReferral = $this->Vendors->Referrals->deleteAll(['vendor_id IN' => $vendorId]);
         $this->out('deleted Referrals');

        $deleteReferralLeads = $this->Vendors->ReferralLeads->deleteAll(['vendor_id IN' => $vendorId]);
        $this->out('deleted ReferralLeads');
        $vendorLocations = $this->Vendors->VendorLocations->findByVendorId($vendorId)->all()->extract('id')->toArray();
if(!empty($vendorLocations)){
        $reviews = $this->Vendors
                        ->VendorLocations
                        ->ReviewRequestStatuses
                        ->find()
                        ->where(['vendor_location_id IN' => $vendorLocations])
                        ->extract('vendor_review_id')
                        ->toArray();
        if($reviews){

          $this->Vendors->VendorLocations->ReviewRequestStatuses->VendorReviews->deleteAll(['id IN' => $reviews]);         
          $this->out('deleted VendorReviews');

        }


        $deleteReviewRequestStatuses = $this->Vendors->ReviewRequestStatuses->deleteAll(['vendor_location_id IN' => $vendorLocations]);
        $this->out('deleted ReviewRequestStatuses');
        $deleteVendorLocations = $this->Vendors->VendorLocations->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorLocations');
}
        $deleteVendorPatients = $this->Vendors->VendorPatients->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorPatients');
        if($users){
          $deleteAuthorizeNetProfiles = $this->Vendors->AuthorizeNetProfiles->deleteAll(['id IN' => $users]); 
          $this->out('deleted AuthorizeNetProfiles');
        }
       
       
        $deleteCrediCardCharges = $this->Vendors->CreditCardCharges->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted CrediCardCharges');
        // $deleteEntries = $this->Vendors->Entries->deleteAll(['vendor_id IN' => [$vendorId]]);
        $deleteGiftCoupons = $this->Vendors->GiftCoupons->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted GiftCoupons');
        $deletePromotions = $this->Vendors->Promotions->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted Promotions');
        // $deleteVendorLocations = $this->Vendors->VendorLocations->deleteAll(['vendor_id IN' => [$vendorId]]);
        $deleteReviewSettings = $this->Vendors->ReviewSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted ReviewSettings');
        $deleteTiers = $this->Vendors->Tiers->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted Tiers');
        $deleteVendors = $this->Vendors->deleteAll(['id IN' => [$vendorId]]);
        $this->out('deleted Vendors');
       
        $deleteVendorCardRequests = $this->Vendors->VendorCardRequests->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorCardRequests');
       
        $deleteVendorCardSeries = $this->Vendors->VendorCardSeries->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorCardSeries');
       
        $deleteVendorDocuments = $this->Vendors->VendorDocuments->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorDocuments');
       
        $deleteVendorDepositBalance = $this->Vendors->VendorDepositBalances->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorDepositBalance');

        $deleteVendorEmailSettings = $this->Vendors->VendorEmailSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorEmailSettings');

        $deleteVendorFloristOrders = $this->Vendors->VendorFloristOrders->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorFloristOrders');

        $deleteVendorFloristSettings = $this->Vendors->VendorFloristSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorFloristSettings');

//        $deleteVendorFloristTransactions = $this->Vendors->VendorFloristTransactions->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorFloristTransactions');
        $deleteVendorFreshbooks = $this->Vendors->VendorFreshbooks->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorFreshbooks');
        $deleteVendorInstantGiftCouponSettings = $this->Vendors->VendorInstantGiftCouponSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorInstantGiftCouponSettings');
        $deleteVendorMilestones = $this->Vendors->VendorMilestones->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorMilestones');
        $deleteVendorPlans = $this->Vendors->VendorPlans->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorPlans');
        $deleteVendorPromotions = $this->Vendors->VendorPromotions->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorPromotions');
        $deleteVendorRedeemedPoints = $this->Vendors->VendorRedeemedPoints->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorRedeemedPoints');
        $deleteVendorRedemptionHistory = $this->Vendors->VendorRedemptionHistory->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorRedemptionHistory');
        $deleteVendorReferralSettings = $this->Vendors->VendorReferralSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorReferralSettings');
        $deleteVendorSettings = $this->Vendors->VendorSettings->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorSettings');
        $deleteVendorSurveys = $this->Vendors->VendorSurveys->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted VendorSurveys');
        $deleteOldBuzzydocVendors = $this->Vendors->OldBuzzydocVendors->deleteAll(['vendor_id IN' => [$vendorId]]);
        $this->out('deleted OldBuzzydocVendors');

        echo "Vendor has been deleted";
  }
  //Adding super admin into OldBuzzydocVendors
  public function insertSuperAdmin(){
      $vendorData = ['vendor_id' => 1, 'old_vendor_id' => 0];
      $this->loadModel('OldBuzzydocVendors');
      $data = $this->OldBuzzydocVendors->newEntity($vendorData);
      if($this->OldBuzzydocVendors->save($data)){
        pr('Super admin saved');
      }else{
        pr('Super admin not saved');
      }

  }

  //If clinic id is given then it'll run for that specific clinic otherwise it'll run for all the migrated vendors.
  public function legacyRewardsForMigratedVendors($clinicId = null){

    $this->loadModel('OldBuzzydocVendors');
    
    if(isset($clinicId)){
    
      $vendorData = $this->OldBuzzydocVendors->findByOldVendorId($clinicId)->first();      
      
      if($vendorData){
        $this->_getLegacyRewards($vendorData->old_vendor_id, $vendorData->vendor_id);
      }else{
        $this->out('Could not find this vendor in OldBuzzydocVendors Table.');
      }

    }else{

      $vendorData = $this->OldBuzzydocVendors->find()->all()->combine('vendor_id','old_vendor_id')->toArray();      
    
      // pr($vendorData); die;

      //calling legacyrewards for super admin first.
      $this->_getLegacyRewards($vendorData[1], 1);

      foreach ($vendorData as $key => $value) {
        
        //$key is vendor_id and $value is old_vendor_id
        if($key != 1){
          $this->_getLegacyRewards($value, $key);
        }
      }
    }


  }

  // Getting super admin legacy rewards

  private function _getLegacyRewards($clinicId, $vendorId){

    $results = $this->connectWithOldBuzzyDoc("SELECT * FROM rewards where clinic_id = ".$clinicId);

    $this->loadModel('ProductTypes');
    $productTypes = $this->ProductTypes->find()->all()->combine('name','id')->toArray();

    $legacyrewards = [];

    foreach ($results as $key => $value) {

      $category = explode(";", $value['category']);

      if($category[0] == 'In-office Products'){
        $category[0] = 'In-House';
      }

      $legacyrewards[] = [
        'name' => $value['description'],
        'vendor_id' => $vendorId,
        'reward_category_id' => 3,
        'product_type_id' => $productTypes[$category[0]],
        'points' => $value['points'],
        'status' => 1,
        'image_link' => isset($value['imagepath']) ? $value['imagepath'] : 'NULL',
        'old_reward_id' => $value['id'],
        'amazon_id' => $value['amazon_id'],
        'vendor_legacy_rewards' => [
                                    [
                                      'vendor_id' => $vendorId,
                                      'status' => 1
                                    ]
                                  ]
      ];

    }
    
    if(!empty($legacyrewards)){

      if($vendorId == 1){
        $rewards = $this->saveData('LegacyRewards', [], $legacyrewards);
        if($rewards){
          // $this->getvendorLegacyRewards();
          $this->out('Saved Legacy Rewards for super admin');
        }

      }else{
        $rewards = $this->saveData('LegacyRewards',['VendorLegacyRewards'] , $legacyrewards);
        if($rewards){
          $this->getvendorLegacyRewards($clinicId, $vendorId);
          $this->out('Saved Legacy Rewards created by the vendor');
        }
      }

    }else{
      if($vendorId != 1){
        $this->out('No legacy rewards created by the vendor '.$vendorId.'.');
        $this->getVendorLegacyRewards($clinicId, $vendorId);
      }
    }
    
  }

  public function getVendorLegacyRewards($clinicId, $vendorId){

    // if(isset($clinicId)){

      $results = $this->connectWithOldBuzzyDoc("SELECT * FROM clinic_rewards where clinic_id = ".$clinicId);
      $this->loadModel('OldBuzzydocVendors');
      $migratedVendor = $this->OldBuzzydocVendors->find()->where(['vendor_id ' => $vendorId])->all()->combine('old_vendor_id','vendor_id')->toArray();
    // }
    // else{

    //   //finding vendor Ids for already migrated vendors and then querying those rewards which belong to these vendors only excluding those belonging to super admin.
    //   $clinicIds = implode(',', array_keys($migratedVendors)); 
    //   $results = $this->connectWithOldBuzzyDoc("SELECT * FROM buzzydoc_old.clinic_rewards where clinic_id IN (".$clinicIds.")");
    // }

    // $count = ;
    // pr($count);
    if($results->count() > 0){
      $rewardIds = [];

      foreach ($results as $key => $value) {
        $rewardIds[] = $value['reward_id'];
      }
      
      $this->loadModel('LegacyRewards');
      $legacyRewardIds = $this->LegacyRewards->find()->where(['old_reward_id IN' => $rewardIds, 'vendor_id' => 1])->all()->combine('old_reward_id', 'id')->toArray();
      
      if(count($legacyRewardIds) > 0){
        $data = []; 
        foreach ($results as $key => $value) { 
          if(isset($legacyRewardIds[$value['reward_id']])){
            $data[] = [
                        'vendor_id' => $migratedVendor[$value['clinic_id']], 
                        'legacy_reward_id' => $legacyRewardIds[$value['reward_id']],
                        'status' => 1,
                        ];
          }
        }

        $vendorLegacyRewards = $this->saveData('VendorLegacyRewards', [], $data); 
        $this->out('Vendor Legacy Rewards for vendor id '.$clinicId.' saved.');
      }
    }else{
      $this->out('No joins found for clinic id '.$clinicId.' in clinic rewards.');
    }
  }

  //to fetch amazon id for already migrated legacy rewards
  public function getAmazonIdForLegacyRewards(){
    $this->loadModel('LegacyRewards');
    $legacyRewards = $this->LegacyRewards->find()
                                   ->where(['old_reward_id IS NOT NULL'])
                                   ->all();
    $rewards = $legacyRewards->combine('old_reward_id', 'id')
                                   ->toArray();

    $oldRewardIds = array_keys($rewards);
    $oldRewardIdsArray = implode(',', $oldRewardIds);

    $results = $this->connectWithOldBuzzyDoc("SELECT * FROM rewards where id IN (".$oldRewardIdsArray.")");

    $data = [];
    foreach ($results as $key => $value) {
      $data[] = ['id' => $rewards[$value['id']], 'amazon_id' => $value['amazon_id']];
    }
    
    if(count($data) > 0){

      $legacyRewards = $this->LegacyRewards->patchEntities($legacyRewards, $data);
      $legacyRewards = $this->LegacyRewards->saveMany($legacyRewards);
      if($legacyRewards){
        $this->out('Saved data');
      }else{
        $this->out('Error in saving data');
      }
    }
  }

  //  public function updateUserCards($clinicId, $vendorId){

  //   $results = $this->connectWithOldBuzzyDoc("Select * from integrateortho_live4.clinic_users where clinic_id = ".$clinicId);

  //   $this->loadModel('VendorPatients');
  //   $vendorPatients = $this->VendorPatients->findByVendorId($vendorId)->all()->indexBy('old_buzzydoc_patient_identifier')->toArray();

  //   $patientCards = [];
  //   foreach ($results as $key => $row) {
  //     if(isset($vendorPatients[$row['user_id']]->patient_peoplehub_id) && ($vendorPatients[$row['user_id']]->patient_peoplehub_id)){

  //       $patientCards[] = [
  //       'card_number' => $row['card_number'],
  //       'user_id' => $vendorPatients[$row['user_id']]->patient_peoplehub_id,
  //       ];
  //     }

  //   }
  //   if($patientCards){
  //     $vendor = $this->VendorPatients->Vendors->findById($vendorId)->first();
  //     $phId = $vendor->people_hub_identifier;
  //     $token =  $this->getVendorToken($phId);
  //     $token = $token['token'];
  //     $this->_updatePatientOnPeoplehub($patientCards,$token);
  //     echo 'All cards Updated!';die;
  //   }else{
  //     $this->out('do not have any card to migrate');
  //   }

  // }

  // private function _updatePatientOnPeoplehub( $data, $token){

  //   $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
  //   $response = $http->post($this->_host.'/api/vendor/bulk-usercards', json_encode($data));
  //   if(!$response->isOk()){
  //     Log::write('info', "error occured".$response);
  //     pr($response);
  //     Log::write('info', "error body".$response->body());
  //   //  pr($response->body());die;
  //   }
  //   $response = json_decode($response->body());
  //   if(isset($response->status)){
  //     if($response->status){
  //       pr( $response->data);
  //       echo 'card updated';
  //     }
  //   }else{
  //     echo $response->body();
  //   }
  // }
 }
 ?>