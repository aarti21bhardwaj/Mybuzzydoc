<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Cache\Cache;
use Cake\Controller\Component\CookieComponent;
use Cake\Collection\Collection;
use Cake\Utility\Inflector;
use Cake\Core\Configure;

/**
* Vendors Controller
*
* @property \App\Model\Table\VendorsTable $Vendors
*/
class VendorsController extends ApiController
{

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
    $this->loadComponent('Cookie');
    $this->Auth->allow(['add']);
  }

  /**
  * Add method
  * This method also hits PeopleHub add vendor api. If People hub id is received it is saved along with the vendor.
  * A staff admin user is always created at the time of vendor creation.
  *
  * @return \Cake\Network\Response
  * @throws \Cake\Network\Exception\InternalErrorException When record not saved.
  * @throws \Cake\Network\Exception\BadRequestException if data in request is not valid.
  * @throws \Cake\Network\Exception\MethodNotAllowedException if request is not post.
  * @author James Kukreja
  * @todo   min_deposit & threshold_value to be taken from database in future based on reward template type.
  */
  /**
  * @api {post} /api/vendors/ Adds Vendor to BuzzyDoc and hits People Hub api
  * @apiVersion 0.0.0
  * @apiName Add
  * @apiGroup Vendor
  *
  * @apiParam {string} org_name Vendor's organizarion name.
  * @apiParam {Boolean} [is_legacy] Vendor is legacy client or not.
  * @apiParam {integer} [min_deposit] Minimum deposit made by Vendor.
  * @apiParam {integer} [threshold_value] Minimum value deposit can depriciate to.
  * @apiParam {integer} reward_template_id Vendor's reward template type.
  * @apiParam {Array}  users  Contains details of admin created with the Vendor.
  * @apiParam {String} first_name First name of User.
  * @apiParam {String} last_name Last name of User.
  * @apiParam {String} username Username for identifying user.
  * @apiParam {String} phone  User's Phone number.
  * @apiParam {String} email  User's Email Address.
  * @apiParam {String} Password  is primary contact.
  *
  * @apiSuccess {Boolean} success status of the request.
  *
  * @apiSuccessExample Success-Response:
  *          HTTP/1.1 200 OK
  *     {
  *      "response": {
  *            "status": true,
  *            "data": {
  *                    "buzzydoc_id": 3,
  *                    "peoplehub_id": 26
  *                    }
  *                  }
  *     }
  *
  */
  public function add()
  {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }
     
    if(!isset($this->request->data['threshold_value'])){
        $this->request->data['threshold_value'] = 125;  
    } 
    if (isset($this->request->data['threshold_value'])) {
      if(!$this->request->data['threshold_value'] || $this->request->data['threshold_value'] < 125){
        $this->request->data['threshold_value'] = 125;  
      }
    }

    if(!isset($this->request->data['min_deposit'])){
        $this->request->data['min_deposit'] = 250;  
    } 
    if (isset($this->request->data['min_deposit'])) {
      if(!$this->request->data['min_deposit'] || $this->request->data['min_deposit'] < 250){
        $this->request->data['min_deposit'] = 250;  
      }
    }

    $data = $this->request->data;
    //pr($data); die();
    //Set role_id to 2 of the received user
    $this->request->data['users'][0]['role_id']= 2;

    if(isset($data['users'][0]['username'])){
          throw new BadRequestException(__('USERNAME_NOT_ALLOWED'));
    }
    if($data['threshold_value'] > $data['min_deposit']){
        throw new BadRequestException(__('THRESHOLD_LESS_THAN_MINIMUM_DEPOSIT'));
    }


    //Default value for minimum deposit and threshhold vale if set to 0
    if(!isset($this->request->data['min_deposit']) || is_string($this->request->data['min_deposit']) || $this->request->data['min_deposit'] == 0 )
    {
      $this->request->data['min_deposit'] = 250;
    }

    if(!isset($this->request->data['threshold_value']) || is_string($this->request->data['threshold_value']) || $this->request->data['threshold_value'] == 0 )
    {
      $this->request->data['threshold_value'] = 125;
    }

    $this->request->data['vendor_settings'] = Configure::read('vendor.defaultSettings');


    //Creating new Vendor Entity
    $vendorData = $this->Vendors->newEntity($this->request->data, [
      'associated' => ['Users', 'VendorPlans','VendorSettings']]);

      $this->request->data['users'][0]['username'] = $this->request->data['users'][0]['email'];
    
      $proposedUsername = $this->request->data['users'][0]['email'];
      $usernameExists = $this->Vendors->Users->find('all')->where(['username'=>$proposedUsername])->count();

      //check if username generated from email
      if($usernameExists > 0){
        $proposedUsername1 = $this->request->data['users'][0]['first_name'].$this->request->data['users'][0]['last_name'];
        $proposedUsername1 =   Inflector::slug(strtolower($proposedUsername1));
        $this->request->data['users'][0]['username'] = $proposedUsername1;
        $usernameExists1 = $this->Vendors->Users->find('all')->where(['username LIKE'=>$proposedUsername1.'%'])->count();

          //check if username from first and last name already  exists
          if($usernameExists1 > 0){
            $auto = 1;
            $countIncrement = $usernameExists1+$auto;
            $this->request->data['users'][0]['username'] = $proposedUsername1.$countIncrement;
          }

      }
      //associate 0 vendor location with the new user
      $this->request->data['users'][0]['vendor_location_id'] = 0;

      $vendorData = $this->Vendors->patchEntity($vendorData, $this->request->data, [
        'associated' => ['Users', 'VendorPlans','VendorSettings']]);

        if( $vendorData->errors()){
          throw new BadRequestException(__('KINDLY_PROVIDE_VALID_DATA'));
        }
      // pr($vendorData);die;
        $this->loadComponent('PeopleHub', ['liveMode' => 0]);

        $peopleHubData = $this->PeopleHub->registerVendor($vendorData);
        
        if(is_array($peopleHubData) && isset($peopleHubData['status'])) {
          throw new BadRequestException(json_encode($peopleHubData));
        }
        //setting the data from peoplehub into the vendor object
        $vendor->people_hub_identifier = $peopleHubData->data->id;
        $req = [
                'vendor_card_series'=>array(array('series'=>$peopleHubData->data->vendor_card_series[0]->reseller_card_series->series,
                  'ph_vendor_card_series_identifier'=>$peopleHubData->data->vendor_card_series[0]->id
                  ))
                ];
        $vendor = $this->Vendors->patchEntity($vendor,$req);
        $vendor->people_hub_identifier = $peopleHubData->data->id;
        $vendor->sandbox_people_hub_identifier = 'sandbox';

        if($this->Vendors->save($vendorData, ['associated' => ['Users', 'VendorPlans','VendorSettings', 'VendorCardSeries']])) {
          //if vendor is saved hit people hub api to save vendor
          if($vendorData->template_id != null){
            $this->Vendors->applyTemplate($vendorData);
          }
        //Return Response
        $data =array();
        $data['status']=true;
        $data['data']['id']=$vendorData->id;
        $data['data']['people_hub_identifier']=$peopleHubData->data->id;
        $this->set('response',$data);
        $this->set('_serialize', ['response']);
      }else{

        throw new InternalErrorException(__('INTERNAL_SERVER_ERROR'));
      }


    }

    /**
    * View method
    *
    * @param string|null $id Vendor id.
    * @return \Cake\Network\Response|null
    * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {

      $vendor = $this->Vendors->findById($id)->contain(['VendorPromotions.Promotions', 'Users', 'ReviewSettings', 'VendorLocations', 'VendorDepositBalances', 'VendorPlans.Plans.PlanFeatures.Features', 'Tiers.TierPerks', 'ReferralTiers', 'VendorSurveys.VendorSurveyQuestions.SurveyQuestions.Questions.QuestionTypes', 'VendorMilestones.MilestoneLevels', 'VendorSettings.SettingKeys', 'VendorReferralSettings', 'Referrals.ReferralLeads.ReferralStatuses', 'OldBuzzydocVendors','VendorCardSeries', 'VendorFloristSettings', 'VendorAssessmentSurveys.VendorAssessmentSurveyQuestions.AssessmentSurveyQuestions', 'VendorAssessmentSurveys.AssessmentSurveys.SurveyTypes' => function($q){
          return $q->where(['SurveyTypes.name' => 'Staff Assessment']);
        }])
      ->first();
      // $vendorTier = $vendor->tiers->indexBy('');
      if(!$vendor){
        throw new BadRequestException(__('NOT_FOUND','vendor'));
      }
      //get($id, ['contain' => ['Users']
      // 'contain' => ['Users', 'BuzzyDocPlans', 'Staffs']]);
      $this->set(compact('vendor','reward'));
      $this->set('_serialize', ['vendor']);
    }

    public function sendVendorLive(){
      
      //pr($this->Auth->user('vendor_id')); die();

      $vendorData = $this->Vendors->find()->where(['id'=>$this->Auth->user('vendor_id')])->contain(['Users','VendorSettings'])->first();
      $data=array();
      //$data['name']= $vendorData->org_name;
      $data['name']= $vendorData->org_name;
      $data['people_hub_identifier']= $vendorData->people_hub_identifier;
      $data['vendor_contacts']['email']=$vendorData->users[0]['email'];
      $data['vendor_contacts']['phone']=$vendorData->users[0]['phone'];
      $data['vendor_contacts']['is_primary']=1;
      $data['vendor_reward_types'][0]['reward_method_id']=1;
      $data['vendor_reward_types'][0]['status']=1;
      $data['vendor_reward_types'][1]['reward_method_id']=2;
      $data['vendor_reward_types'][1]['status']=1;
      $data['vendor_reward_types'][2]['reward_method_id']=3;
      $data['vendor_reward_types'][2]['status']=1;
      $this->loadComponent('PeopleHub', ['liveMode' => $vendorData['vendor_settings'][0]->value]);

      $peopleHubData = $this->PeopleHub->registerVendorOnLiveServer($data);
      // pr($peopleHubData);die;
      if((is_array($peopleHubData) && $peopleHubData['status'])){
        throw new InternalErrorException(__('TOKEN_ERROR'));
      }
      if(!$peopleHubData->status){
        throw new BadRequestException(__('cannot proceed now'));
      }

      //people_hub_identifier
      $vendorUpdateReq= array();
      $vendorUpdateReq['people_hub_identifier'] = $peopleHubData->data->id;
      $settingReqData = array();
      $settingReqData['id'] = $vendorData['vendor_settings'][0]->id;
      $settingReqData['value'] = 1;
      $vendorUpdateReq['vendor_settings'] = array($settingReqData);
      $vendorData = $this->Vendors->patchEntity($vendorData, $vendorUpdateReq,['associated'=>['VendorSettings']]);
      $vendorData->sandbox_people_hub_identifier = 'live';
      if ($this->Vendors->save($vendorData)) {

        $this->loadModel('Users');
        $user = $this->Users->find('all')->where(['id'=>$this->Auth->user('id')])->toArray();
        $user= $user[0];
        $this->loadModel('Roles');
        $user['role'] = $query = $this->Roles->find('RolesById', ['role' => $user['role_id']])->select(['name', 'label'])->first();
        $this->loadModel('VendorPlans');
        $user['plan'] = $query = $this->VendorPlans->findByVendorId($user['vendor_id'])->contain(['Plans.PlanFeatures'])->first();
        $user['vendor_peoplehub_id'] = $query = $this->Vendors->findById($user['vendor_id'])->select(['people_hub_identifier'])->first()->people_hub_identifier;
        $this->Auth->setUser($user);
        // pr($user); die;
        $this->_deleteAwardsRedemptionsOnLive();                

        $data =array();
        $data['status']=true;
        $data['data']['id']=$vendorData->id;
        $data['data']['people_hub_identifier']=$peopleHubData;
        $this->set('response',$data);
        $this->set('_serialize', ['response']);

      }else{

        throw new InternalErrorException(__('INTERNAL_SERVER_ERROR'));
      }

    }

    private function _deleteAwardsRedemptionsOnLive()
    {
        $this->loadModel('Users');
        $vendoruser = $this->Users->findByVendorId($this->Auth->user('vendor_id'))->all()->extract('id')->toArray();
            
        $this->loadModel('ManualAwards');
        $deleteManualAwards = $this->ManualAwards->deleteAll(['user_id IN' => $vendoruser]);

        $this->loadModel('PromotionAwards');
        $deletePromotionAwards = $this->PromotionAwards->deleteAll(['user_id IN' => $vendoruser]);

        $this->loadModel('ReferralAwards');
        $deleteReferralAwards = $this->ReferralAwards->deleteAll(['user_id IN' => $vendoruser]);

        $this->loadModel('ReviewAwards');
        $deleteReviewAwards = $this->ReviewAwards->deleteAll(['user_id IN' => $vendoruser]);
        
        $reviews = $this->Users
                        ->ReviewRequestStatuses
                        ->find()
                        ->where(['user_id IN' => $vendoruser])
                        ->extract('vendor_review_id')
                        ->toArray();
        if($reviews){

          $this->Users->ReviewRequestStatuses->VendorReviews->deleteAll(['id IN' => $reviews]);          
          
        }

        $deleteReviewRequestStatuses = $this->Users->ReviewRequestStatuses->deleteAll(['user_id IN' => $vendoruser]);


        $this->loadModel('TierAwards');
        $deleteTierAwards = $this->TierAwards->deleteAll(['user_id IN' => $vendoruser]);

        $this->loadModel('SurveyAwards');
        $deleteSurveyAwards = $this->SurveyAwards->deleteAll(['user_id IN' => $vendoruser]);

        $this->loadModel('LegacyRedemptions');
        $deleteLegacyRedemptions = $this->LegacyRedemptions
                                        ->find()->where(['user_id IN' => $vendoruser])
                                        ->all();
        
        foreach($deleteLegacyRedemptions as $entity) {
          $this->LegacyRedemptions->delete($entity);
        }

        $this->loadModel('GiftCouponAwards');
        $deleteGiftCouponAwards = $this->GiftCouponAwards
                                        ->find()->where(['user_id IN' => $vendoruser])
                                        ->all();
        
        foreach($deleteGiftCouponAwards as $entity) {
          $this->GiftCouponAwards->delete($entity);
        }

        $this->loadModel('MilestoneLevelAwards');
        $deleteMilestoneLevelAwards = $this->MilestoneLevelAwards->deleteAll(['user_id IN' => $vendoruser]);

        $vendorId = $this->Auth->user('vendor_id');
        $this->loadModel('Referrals');
        $deleteReferral = $this->Referrals->deleteAll(['vendor_id IN' => $vendorId]);

        $this->loadModel('ReferralLeads');
        $deleteReferralLeads = $this->ReferralLeads->deleteAll(['vendor_id IN' => $vendorId]);

        $this->loadModel('VendorCardSeries');
        $deleteVendorCardSeries = $this->VendorCardSeries->deleteAll(['vendor_id IN' => $vendorId]);

        $this->loadModel('VendorCardRequests');
        $deleteVendorCardRequests = $this->VendorCardRequests->deleteAll(['vendor_id IN' => $vendorId]);
    }

    public function index()
    {
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $vendors = $this->Vendors->find()->select(['id', 'org_name'])->all();
     

      $this->set(compact('vendors'));
      $this->set('_serialize', ['vendors']);
    }

    public function tiers($vendorId){
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      if(!$vendorId){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor_id'));
      }

      $tiers = $this->Vendors->Tiers->findByVendorId($vendorId)->all();
      
      if(sizeOf($tiers) == 0){

        throw new NotFoundException(__('NOT_FOUND', 'Tiers'));
      }

      $this->set('tiers', $tiers);
      $this->set('_serialize', 'tiers');
    }

    public function referralTiers($vendorId){
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      if(!$vendorId){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor_id'));
      }

      $referralTiers = $this->Vendors->ReferralTiers->findByVendorId($vendorId)->all();
      
      if(sizeOf($referralTiers) == 0){

        throw new NotFoundException(__('NOT_FOUND', 'Referral Tiers'));
      }

      $this->set('referralTiers', $referralTiers);
      $this->set('_serialize', 'referralTiers');
    }
  }