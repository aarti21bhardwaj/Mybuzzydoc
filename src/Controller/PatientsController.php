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
class PatientsController extends AppController
{
  public function initialize(){
    parent::initialize();
    // $this->Auth->config('authorize', ['Controller']);
    $this->Auth->allow(['setUpWizard','add','index']);
  }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
      $this->loadModel('Vendors');
      $vendors = $this->Vendors->find()->contain('Users')->all()->toArray();

      $this->set(compact('vendors'));
      $this->set('_serialize', ['vendors']);
    }

     /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
     public function add()
     {
      $this->loadModel('Vendors');
      $vendors = $this->Vendors->find()->contain('Users')->all()->toArray();
      if ($this->request->is('post')) {
        if($this->request['data']['file']){
          $fileName = $this->request['data']['file']['name'];
          $ext = explode('.', $fileName);
          $ext = strtolower(end($ext));
          $type = $this->request['data']['file']['type'];
          $tmpName = $this->request['data']['file']['tmp_name'];
          $size = $this->request['data']['file']['size'];
          if($ext=='csv'){
            $csvAsArray = array_map('str_getcsv', file($tmpName));
            $userDataArray  = $this->_parsePatientsInfo($csvAsArray);
            if($userDataArray){
              $this->loadModel('VendorSettings');
              $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
              ->contain(['SettingKeys' => function($q){
                return $q->where(['name' => 'Live Mode']);
              }
              ])
              ->first()->value;
              $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
              $userDataArray = $this->PeopleHub->uploadUsers($this->Auth->user('vendor_peoplehub_id'),$userDataArray);
              if($userDataArray){
                if($userDataArray->status){
                 $this->_storeDataAtBuzzy($userDataArray->data);
                 $this->Flash->success(__('Patient uploaded successfully'));
               }else{
                 $this->Flash->error(__(json_encode($userDataArray->data)));
               }
               return $this->redirect(['action' => 'index']);
             }
           }
         }else{
          $this->Flash->error(__('Kindly upload CSV file.'));
        }
        return $this->redirect(['action' => 'index']);
      }
    }
    $this->set(compact('vendors'));
    $this->set('_serialize', ['vendors']);
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
    private function _parsePatientsInfo($dataArray){
      $usersDataArray = [];
      $firstNameKey = null;
      $lastNameKey = null;
      $emailKey = null;
      $dobKey = null;
      $phoneKey = null;
      $cardNumberKey = null;
      $pointsKey = null;
      foreach ($dataArray[0] as $key => $value) {
        $value = strtolower($value);
        if($value == 'first name'){
          $firstNameKey = $key;
        }else if($value == 'last name'){
          $lastNameKey = $key;
        }else if($value == 'email'){
          $emailKey = $key;
        }else if($value == 'dob'){
          $dobKey = $key;
        }else if($value == 'phone'){
          $phoneKey = $key;
        }else if($value == 'card number'){
          $cardNumberKey = $key;
        }else if($value == 'points'){
          $pointsKey = $key;
        }
      }
      $this->loadModel('VendorSettings');
      $vendorSettings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
      ->contain(['SettingKeys'])->toArray();
      $creditType = null;
      $vendorMode = null;
      foreach ($vendorSettings as $key => $settingKey) {
        if($settingKey->setting_key->name == 'Credit Type'){
          $creditType = $settingKey->value;
        }
        if($settingKey->setting_key->name == 'Live Mode'){
          $vendorMode = $settingKey->value;
        }
        if($vendorMode && $creditType){
          break;
        }
      }
      unset($dataArray[0]);
      foreach ($dataArray as $key => $value) {
        $data = [];
        $data['name'] = (isset($value[$lastNameKey]) && $value[$lastNameKey])? $value[$firstNameKey].''.$value[$lastNameKey]:$value[$firstNameKey];
        $data['email'] = (isset($value[$emailKey]) && $value[$emailKey])?$value[$emailKey]:null;
        $data['phone'] = (isset($value[$phoneKey]) && $value[$phoneKey])?$value[$phoneKey]:null;
        // $data['dob'] = (isset($value[$dobKey]) && $value[$dobKey])?$value[$dobKey]:null;
        $data['password'] = $this->_randomPassword();
        $data['points'] = (isset($value[$pointsKey]) && $value[$pointsKey])?$value[$pointsKey]:null;
        $data['reward_type'] = $creditType;
        if(isset($value[$cardNumberKey]) && $value[$cardNumberKey]){
          $data['card_number'] = $value[$cardNumberKey];  
        }
        $usersDataArray[] = $data;
      }
      return $usersDataArray;
    }

    private function _storeDataAtBuzzy($userDataArray) {
      foreach ($userDataArray as $key => $value) {
        $data = [
        'patient_peoplehub_id' => $value->id,
        'vendor_id' => $this->Auth->user('vendor_id')
        ];
        $errorArray = array();
        $this->loadModel('Users');
        $vendorPatient = $this->Users->Vendors->VendorPatients->newEntity();

        $vendorPatient = $this->Users->Vendors->VendorPatients->patchEntity($vendorPatient, $data);
        if($vendorPatient->errors()){
          $errorArray[$key] = $vendorPatient->errors();
        }
        if(!$this->Users->Vendors->VendorPatients->save($vendorPatient)){
          throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
        }
        if(isset($value->reward_credits)){
          $this->loadModel('ManualAwards');
        $manualReward = [
        'user_id' => $this->Auth->user('id'), 
        'points' => $value->reward_credits[0]->points, 
        'peoplehub_transaction_id' => $value->activities[0]->id, 
        'description' => 'csv upload',
        'redeemer_peoplehub_identifier' =>  $value->id, 
        'vendor_id' => $this->Auth->user('vendor_id')
        ];

        $manualAward = $this->ManualAwards->newEntity();
        $manualAward = $this->ManualAwards->patchEntity($manualAward, $manualReward);


        if(!$this->ManualAwards->save($manualAward)){
          pr($manualAward);die;
          throw new InternalErrorException(__('ENTITY_ERROR', 'manual award'));
        }  
        }
        

      }
    }
  }