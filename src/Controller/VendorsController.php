<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\Log\Log;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Network\Session;
use Cake\Utility\Inflector;


/**
 * Vendors Controller
 *
 * @property \App\Model\Table\VendorsTable $Vendors
 */
class VendorsController extends AppController
{
  public function initialize(){
    parent::initialize();
    // $this->Auth->config('authorize', ['Controller']);
    $this->Auth->allow(['signUp']);
    $this->loadComponent('FreshBooks');
  }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
      $vendors = $this->Vendors->find()->contain('Users')->order(['Vendors.org_name' => 'ASC'])->all();


      $this->set(compact('vendors'));
      $this->set('_serialize', ['vendors']);
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
      $vendor = $this->Vendors->findById($id)->contain(['Users'])->first();
      if(!$vendor){
        $this->Flash->error(__('NOT_FOUND','vendor'));
        return $this->redirect(['action' => 'index']);
      }
        //get($id, ['contain' => ['Users']
            // 'contain' => ['Users', 'BuzzyDocPlans', 'Staffs']]);
      $template = $this->Vendors->Templates->findById($vendor->template_id)->first();
      $this->set(compact('vendor','template'));
      $this->set('_serialize', ['vendor']);
    }

    // ->order(
    //                array('User.username ASC') //use whatever field you want to sort
    //                );

    /**
     * Add method
     * This method also hits PeopleHub add vendor api. If People hub id is received it is saved along with the vendor.
     * A staff admin user is always created at the time of vendor creation.
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
      //pr($this->request->data['vendor_settings'][]); die('ss');
      $vendor = $this->Vendors->newEntity();

      // $template = $this->Vendors->Templates->find()->all()->combine('id', 'name');
      // $template['0'] = 'Blank';
      $plans = $this->Vendors->VendorPlans->Plans->find()->all()->combine('id', 'name');

      if ($this->request->is('post')) {
        // pr($this->request->data); die('ss');
        $this->loadModel('Roles');
        $this->request->data['vendor_plans'][] = ['plan_id' => $this->request->data['vendor_plans']['plan_id']];
        unset($this->request->data['vendor_plans']['plan_id']);
        
        $this->request->data['vendor_settings'] = Configure::read('vendor.defaultSettings'); 

      $this->request->data['user']['username'] = $this->_getUsername($this->request->data['user']);    //User registered with Vendor will have the role Satff Admin
      $role=$this->Roles->findByName("staff_admin")->select(['id'])->first();
      $this->request->data['users'][0]= $this->request->data['user'];
      unset($this->request->data['user']);
      $this->request->data['users'][0]['role_id']=$role->id;
      $this->request->data['users'][0]['vendor_location_id'] = 0;
      $vendor = $this->Vendors->newEntity($this->request->data,['associated' => ['Users', 'VendorPlans','VendorSettings']]);

        // pr($this->request->data); die;
      $vendor = $this->Vendors->patchEntity($vendor, $this->request->data,['associated' => ['Users','VendorPlans','VendorSettings']]);
        // $vendor->sandbox_people_hub_identifier = 10;
        // $vendor->people_hub_identifier = 20;
        // pr($vendor);die;
        //throw new BadRequestException(json_encode($vendor));

      if( !$vendor->errors()){
          //pr($vendor); die();
        if ($this->Vendors->save($vendor, ['associated' => ['Users', 'VendorPlans','VendorSettings']])) {
            //TODO People Hub Component needs to be called
            //         //hitting add vendor api on people hub
            // $this->loadModel('VendorSettings');
            // $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
            //                              ->contain(['SettingKeys' => function($q){
            //                                                                 return $q->where(['name' => 'Live Mode']);
            //                                                             }
            //                                         ])
            //                              ->first()->value;
          $this->loadComponent('PeopleHub', ['liveMode' => 0]);
          $response = $this->PeopleHub->registerVendor($vendor);
          if(!(is_array($response) && isset($response['status']))){
            $vendor->people_hub_identifier = $response->data->id;
            $req = [
                    'vendor_card_series'=>array(array('series'=>$response->data->vendor_card_series[0]->reseller_card_series->series,
                      'ph_vendor_card_series_identifier'=>$response->data->vendor_card_series[0]->id
                      ))
                    ];
            $vendor = $this->Vendors->patchEntity($vendor,$req);
            $vendor->people_hub_identifier = $response->data->id;
            $vendor->sandbox_people_hub_identifier = 'sandbox';
          }

          if(!$this->Vendors->save($vendor)) {
                  //log data not saved on peoplehub
          }else{

            Log::write('debug', 'Vendor Saved with people hub id '.$vendor['people_hub_identifier']);
          }

          $this->Flash->success(__('ENTITY_SAVED', 'vendor'));
          return $this->redirect(['action' => 'index']);
        }else {
          $this->Flash->error(__('ENTITY_ERROR', 'vendor'));
        }
      }else{
        $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
          // throw new BadRequestException(__('KINDLY_PROVIDE_VALID_DATA'));
      }
    }
        // pr($reward_templates);die;

    $this->set(compact('vendor', 'plans'));
    $this->set('_serialize', ['vendor']);
  }

    /**
     * Edit method
     * This method also hits PeopleHub update vendor api.
     *
     * @param string|null $id Vendor id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
      $loggedInUser = $this->Auth->user();

      // if($loggedInUser['vendor_id'] != $id && $loggedInUser['role_id'] != 1){
      //   $this->Flash->error(__('You are not authorized to access that location'));
      //   return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
      // }
      $vendor = $this->Vendors->findById($id)->contain(['Users'])->first();
      if(!$vendor){
        $this->Flash->error(__('NOT_FOUND','vendor'));
        return $this->redirect(['action' => 'index']);
      }
      //If old image is available, unlink the path(and delete the image) and and  upload image from "upload" folder in webroot.
      $oldImageName = $vendor->image_name;
      $path = Configure::read('ImageUpload.unlinkPathForVendorLogos');

      if ($this->request->is(['patch', 'post', 'put'])) {
        $vendor = $this->Vendors->patchEntity($vendor, $this->request->data);
        if($vendor->errors()){
          $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
        }

        // We are unsetting these fields to prevent the flip flop during the save method when not switching modes.


        if ($this->Vendors->save($vendor)) {

            //Removing image name index if image_is not submitted when editing a reward
          if(empty($this->request->data['image_name']['tmp_name'])){
            unset($this->request->data['image_name']);
            $oldImageName ='';
          }
          
          //TODO: Update Vendor On Peoplehub as well

          // $liveMode = $this->Vendors
          //                  ->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
          //                  ->contain(['SettingKeys' => function($q){
          //                                                             return $q->where(['name' => 'Live Mode']);
          //                                               }
          //                             ])
          //                   ->first()->value;

          // $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
          // $editVendorOnPeoplehub = $this->PeopleHub->editVendor($loggedInUser['vendor_peoplehub_id'], $this->request->data);
          // pr($editVendorOnPeoplehub);die;
          // if(!isset($editVendorOnPeoplehub['status']) && !$editVendorOnPeoplehub['status']){
          //   $this->Flash->error('Vendor could not be updated on bountee');
          //   Log::write('debug', 'Vendor could not be updated on bountee - VendorId => '.$vendor->id."Error => ".json_encode($editVendorOnPeoplehub));
          // }

          $this->Flash->success(__('ENTITY_SAVED', 'vendor'));
          if(!empty($oldImageName)){
            $filePath = $path . '/'.$oldImageName;
            if( $filePath != '' && file_exists( $filePath ) ){
              unlink($filePath);
            }
          }
          return $this->redirect($this->request->referer());
        } else {
          $this->Flash->error(__('ENTITY_ERROR', 'vendor'));
        }
      }

      $this->set(compact('vendor'));
      $this->set('_serialize', ['vendor']);
    }


    public function delete($id = null)
    {
      $this->request->allowMethod(['post', 'delete']);
      $vendor = $this->Vendors->findById($id)->first();
      if ($this->Vendors->delete($vendor)) {
        $this->Flash->success(__('ENTITY_DELETED','vendor'));
      } else {
        $this->Flash->error(__('ENTITY_DELETED_ERROR','vendor'));
      }

      return $this->redirect(['action' => 'index']);
    }


    public function viewBill($id)
    {
      $vendor= $this->Vendors->findById($id)->first();
      $this->loadModel('VendorSettings');
      $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
      ->contain(['SettingKeys' => function($q){
        return $q->where(['name' => 'Live Mode']);
      }
      ])
      ->first()->value;
      $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
      $data= array();
      $data['vendor_id'] = $vendor->people_hub_identifier;
      $data['month'] = Date::now()->subMonth(1)->month;
      $vendorBill = $this->PeopleHub->viewVendorBill($data);
      $this->set('vendorBill', $vendorBill);
      $this->set('_serialize', ['vendorBill']);

    }

    public function vendorReports(){
      // pr($this->Auth->user('vendor_id'));
      $vendor =  $this->Vendors->findById($this->Auth->user('vendor_id'))
      ->contain(['VendorPlans.Plans.PlanFeatures.Features', 'CreditCardCharges', 'VendorDepositBalances'])
      ->first();
      // pr($vendor);
      $this->loadModel('LegacyRedemptions');
      $redemptions = $this->LegacyRedemptions->findByVendorId($this->Auth->user('vendor_id'))
      ->contain(['LegacyRewards', 'LegacyRedemptionAmounts'])
      ->all();
      $this->set('vendor', $vendor);
      $this->set('redemptions', $redemptions);
    }

    public function setUpWizard(){

      $vendor = $this->Vendors->findById($this->Auth->user('vendor_id'))->first();
      $data = $this->request->data;

      if(isset($vendor->template_id)){
        return $this->redirect(['controller' => 'Users',
          'action' => 'index'
          ]);

      }

      if($this->request->is('put')){
        if(!$vendor){
          $this->Flash->error(__('NOT_FOUND','vendor'));
        }else {
          $vendor = $this->Vendors->patchEntity($vendor, $data);


          if ($this->Vendors->save($vendor)) {
            $this->Flash->success(__('ENTITY_SAVED','template'));
            $this->Vendors->applyTemplate($vendor);
            return $this->redirect(['controller' => 'Users',
              'action' => 'index'
              ]);
          } else {
            $this->Flash->error(__('ENTITY_ERROR','vendor id'));
          }
        }
      }
      $this->loadModel('Templates');
      $templates = $this->Templates->find()
      ->contain(['TemplatePromotions'])
      ->all()
      ->combine('id', 'name')
      ->toArray();

      $this->viewBuilder()
      ->layout('setup-wizard');
      $this->set(compact('vendor','templates'));
      $this->set('_serialize', ['vendor']);
    }

    public function signUp(){

      //set Blank layout
      $this->viewBuilder()->layout('review-form');
      
      $vendor = $this->Vendors->newEntity();
      $date = Date::now();
      $currentYear = $date->year - 2000;
      
      if ($this->request->is('post')) {

        if(!isset($this->request->data['cc']) || !$this->request->data['cc'] || empty($this->request->data['cc'])){

          $this->Flash->error(__('Credit card information is required'));
          return;
        }

        $cardKeys = ['card_number' => 'Credit Card Number', 'expiry_month' => 'Month of Expiry', 'expiry_year' => 'Year of Expiry', 'name' => "Name on Card", 'postal_code' => 'Zip Code', 'cvv' => 'CVV'];
        
        foreach ($cardKeys as $key => $value) {
          if(!isset($this->request->data['cc'][$key]) || !$this->request->data['cc'][$key]){
            $this->Flash->error(__($value.' is required'));
            return;
          }
        }
        

        $devEnv = Configure::read('development.env');

        if($devEnv != 'staging' && $devEnv != 'dev'){

          $ccInfo = $this->request->data['cc'];
          $ccInfo['country'] = 'US';
          $ccInfo['email'] = $this->request->data['user']['email'];
 
          $ccToken = $this->FreshBooks->tokenizeCC($ccInfo);
          if(!$ccToken){

            $this->Flash->error(__('Credit card information is incorrect'));
            return;
          }
          
        }else{

          $ccToken = false;
          $this->request->data['cc'] = false;
        }
        // $ccToken = false;

        //Create Vendor Data
        //Default value for minimum deposit and threshhold vale if set to 0
        $this->request->data['min_deposit'] = 250;
        $this->request->data['threshold_value'] = 125;
        $this->request->data['vendor_plans'][] = ['plan_id' => 1];
        $this->request->data['is_legacy'] = false;
        $this->request->data['status'] = false;
        $this->request->data['vendor_settings'] = Configure::read('vendor.defaultSettings');
        //First user registered with Vendor will have the role Satff Admin
        $role=$this->Vendors->Users->Roles->findByName("staff_admin")->select(['id'])->first();
        $this->request->data['users'][0]= $this->request->data['user'];
        unset($this->request->data['user']);
        $this->request->data['users'][0]['role_id']=$role->id;
        $this->request->data['users'][0]['vendor_location_id'] = 0;
        $this->request->data['users'][0]['status'] = false;
        $this->request->data['users'][0]['username'] = $this->_getUsername($this->request->data['users'][0]);

        $vendor = $this->Vendors->newEntity($this->request->data,['associated' => ['Users', 'VendorPlans','VendorSettings']]);
        
        $vendor = $this->Vendors->patchEntity($vendor, $this->request->data,['associated' => ['Users','VendorPlans','VendorSettings']]);

        if ($this->Vendors->save($vendor, ['associated' => ['Users', 'VendorPlans','VendorSettings']])){

          $this->loadComponent('PeopleHub', ['liveMode' => 0]);
          $response = $this->PeopleHub->registerVendor($vendor);

          if(!(is_array($response) && isset($response['status']))){
            $vendor->people_hub_identifier = $response->data->id;
            $req = [
                    'vendor_card_series'=>array(array('series'=>$response->data->vendor_card_series[0]->reseller_card_series->series,
                      'ph_vendor_card_series_identifier'=>$response->data->vendor_card_series[0]->id
                      ))
                    ];
            $vendor = $this->Vendors->patchEntity($vendor,$req);
            $vendor->people_hub_identifier = $response->data->id;
            $vendor->sandbox_people_hub_identifier = 'sandbox';
          }

          if($this->Vendors->save($vendor)){

            Log::write('debug', 'Vendor Saved with people hub id '.$vendor['people_hub_identifier']);
            $this->Flash->success(__('ENTITY_SAVED', 'vendor'));
          }

          if(!$this->_registerOnFreshBooks($vendor->id, $this->request->data, $ccToken)){
            $this->Flash->error('Error in FreshBooks.');            
          }

          return $this->redirect(['controller' => 'Users','action' => 'login']);

        }else{

          $this->Flash->error(__('ENTITY_ERROR', 'vendor'));
        }
      }

      $this->set(compact('vendor', 'currentYear'));
      $this->set('_serialize', ['vendor', 'currentYear']);

    }

    private function _registerOnFreshBooks($vendorId, $data, $ccToken){

      $user = $data['users'][0];
      

      //Create freshbooks client
      $client = $this->FreshBooks->createClientProfile($user['first_name'], $user['last_name'], $user['email'], $data['org_name']);

      
      if(!isset($client['status']) && !$client['status']){

        $this->Flash->error(__('Could not create a FreshBooks profile.'));
        return false;
      }

      $clientId = $client['response']['client_id'];
      
      $itemDataArray = [
      'name'=>'BuzzyDoc Subscription',
      'description'=>'Base plan',
      'unit_cost'=>100,
      'quantity'=>1
      ];

      //Create recurring profile for this client
      $recurring = $this->FreshBooks->createRecurringProfile($clientId,$itemDataArray,$data['cc'], $ccToken);
      
      if(!isset($recurring['status']) && !$recurring['status']){

        $this->Flash->error(__('Could not create a recurring profile.'));
        return false;
      }

      $recurringProfileId = $recurring['response']['recurring_id'];

      $vendorFreshBook = [
      'vendor_id' => $vendorId, 
      'freshbook_client_id' => $clientId,
      'recurring_profile_id' => $recurringProfileId
      ];

      $vendorFreshBook = $this->Vendors->VendorFreshbooks->newEntity($vendorFreshBook);

      if(!$this->Vendors->vendorFreshBooks->save($vendorFreshBook)){
        $this->Flash->error(__('ENTITY_ERROR', 'Vendor Freshbooks'));
        return false;
      }

      $vendor = $this->Vendors->findById($vendorId)->contain(['Users' => function($q){

        return $q->where(['Users.status' => 0]);

      }])->first();

      $vendor->status = 1;
      $vendor->users[0]->status = 1;

      $this->Vendors->save($vendor);
      $this->Vendors->Users->save($vendor->users[0]);
      // pr($vendor);die;
      return true;
    }

    private function _getUsername($data){

      $proposedUsername = $data['email'];
      $usernameExists = $this->Vendors->Users->find('all')->where(['username'=> $proposedUsername])->count();

      //check if username generated from email
      if($usernameExists > 0){
        $proposedUsername1 = $data['first_name'].$data['last_name'];
        $proposedUsername1 =   Inflector::slug(strtolower($proposedUsername1));
        $username = $proposedUsername1;
        $usernameExists1 = $this->Vendors->Users->find('all')->where(['username LIKE'=>$proposedUsername1.'%'])->count();

        //check if username from first and last name already  exists
        if($usernameExists1 > 0){
          $auto = 1;
          $countIncrement = $usernameExists1+$auto;
          $username = $proposedUsername1.$countIncrement;
        }

        return $username;

      }else{

        return $proposedUsername;
      } 
    }
  }