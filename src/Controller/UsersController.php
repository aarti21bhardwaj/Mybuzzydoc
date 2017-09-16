<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Mailer\MailerAwareTrait;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\Time;
use ke\View\Helper\UrlHelper;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Collection\Collection;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*/
class UsersController extends AppController
{

  const SUPER_ADMIN_LABEL = 'admin';
  const STAFF_ADMIN_LABEL = 'staff_admin';
  const STAFF_MANAGER_LABEL = 'staff_manager';


  public function initialize(){
    parent::initialize();
    // $this->Auth->config('authorize');
    $this->Auth->allow(['resetPassword','forgotPassword','login', 'lockscreen']);
    $this->loadComponent('Cookie');
    $this->Cookie->configKey('username', [
            'path' => '/',
            'encryption'=>false
    ]);
  }

  public function dashboard(){
    $loggedInUser = $this->Auth->user();
    if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
      $users =   $this->Users->find('all',
        [
        'contain' => ['Roles', 'Vendors']] );
    }
    else if($loggedInUser['role']->name == self::STAFF_ADMIN_LABEL){
      $users =  $this->Users->find('all',
        [
        'contain' => ['Roles', 'Vendors'],
        'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id'),'Roles.name <>'=>self::SUPER_ADMIN_LABEL]
        ]);
    }
    else {
      $users =  $this->Users->find('all',
        [
        'contain' => ['Roles', 'Vendors'],
        'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id'), 'Roles.name'=>self::STAFF_MANAGER_LABEL]
        ]);
    }
    $userData = $this->Auth->user();
    $userData = $this->Users->findById($userData['id'])->contain(['Vendors'])->first();
    $this->loadModel('VendorSettings');
    $liveMode = $this->VendorSettings->findByVendorId($userData['vendor_id'])
    ->contain(['SettingKeys' => function($q){
      return $q->where(['name' => 'Live Mode']);
    }
    ])
    ->first()->value;
    $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
    if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
      $resellerTokenData = $this->PeopleHub->getResellerToken();
    }else{
      if($userData->vendor->people_hub_identifier){
        $resellerTokenData = $this->PeopleHub->getVendorToken($userData->vendor->people_hub_identifier);
      }else{
        $resellerTokenData = null;
      }
    }


    $vendorId = $this->Auth->user('vendor_id');
    $users = $this->paginate($users);
    $this->set(compact('users', 'vendorId'));
    $this->set('_serialize', ['users']);
  }

  public function forgotPassword()
  {
    if($this->Auth->user()){
      $this->Flash->error(__("UNAUTHORIZED_REQUEST"));
      $this->redirect(['action' => 'logout']);
    }
    $this->viewBuilder()->layout('login-admin');
    if ($this->request->is('post')) {
      $email = $this->request->data['email'];
      $user = $this->Users->find('all')->where(['email'=>$email])->first();
      if(!$user){
        return $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS','Email'));
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
        'arr' => [
        'hashData' => $user,
  'eventId' => 3, //give the event_id for which you want to fire the email
  'vendor_id' => $user->vendor_id
  ]
  ]);
      $this->eventManager()->dispatch($event);
  //Set Flash
      $this->Flash->success(__('VERIFIED_AND_CHANGE_PASSWORD'));
      $this->redirect(['action' => 'login']);
    }
  }

  public function resetPassword()
  {
  // pr($this->request);die;
  // if($this->Auth->user()){
  //   $this->Flash->error(__("UNAUTHORIZED_REQUEST"));
  //   $this->redirect(['action' => 'logout']);
  // }
    $this->viewBuilder()->layout('login-admin');
    $uuid = $this->request->query('reset-token');
    if ($this->request->is('get') && !$uuid) {
      $this->Flash->error(__('BAD_REQUEST'));
      $this->redirect(['action' => 'login']);
      return;
    }

    if ($this->request->is('post')) {
      $uuid = (isset($this->request->data['reset-token']))?$this->request->data['reset-token']:'';

      if(!$uuid){
        $this->Flash->error(__('BAD_REQUEST'));
        $this->redirect(['action' => 'login']);
        return;
      }
      $password = (isset($this->request->data['new_pwd']))?$this->request->data['new_pwd']:'';
      if(!$password){
        $this->Flash->error(__('PROVIDE_PASSWORD'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }
      $cnfPassword = (isset($this->request->data['cnf_new_pwd']))?$this->request->data['cnf_new_pwd']:'';
      if(!$cnfPassword){
        $this->Flash->error(__('CONFIRM_PASSWORD'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }
      if($password !== $cnfPassword){
        $this->Flash->error(__('MISMATCH_PASSWORD'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }

      $this->loadModel('ResetPasswordHashes');
      $checkExistPasswordHash = $this->ResetPasswordHashes->find()->where(['hash'=>$uuid])->first();

      if(!$checkExistPasswordHash){
        $this->Flash->error(__('INVALID_RESET_PASSWORD'));
        $this->redirect(['action' => 'login']);
        return;
      }

      $userUpdate = $this->Users->findById($checkExistPasswordHash->user_id)->first();
      if(!$userUpdate){
        $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS','User'));
        $this->redirect(['action' => 'login']);
        return;
      }
      if(! preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $password)){
        $this->Flash->error(__('PASSWORD_CONDITION'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }
      $isContainChars = false;
      for( $i = 0; $i <= strlen($userUpdate->username)-3; $i++ ) {
        $char = substr( $userUpdate->username, $i, 3 );
        if(strpos($password,$char,0) !== false ){
          $isContainChars = true;
          break;
        }
      }
      if($isContainChars){
        $this->Flash->error(__('PASSWORD_USER_CONDITION'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }
      $fullname = $userUpdate->full_name;
      for( $i = 0; $i <= strlen($fullname)-3; $i++ ) {
        $char = substr( $fullname, $i, 3 );
        if(strpos($password,$char,0) !== false ){
          $isContainChars = true;
          break;
        }
      }
      if($isContainChars){
        $this->Flash->error(__('PASSWORD_NAME_CONDITION'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
        return;
      }

  // pr($userUpdate);die;
      $reqData = ['password'=>$password];
      $this->loadModel('UserOldPasswords');
      $userOldPasswordCheck = $this->UserOldPasswords->find('all')->where(['user_id'=>$checkExistPasswordHash->user_id])->toArray();
      $hasher = new DefaultPasswordHasher();
      foreach ($userOldPasswordCheck as $key => $value) {
  // pr($value);die;
        if($hasher->check( $password,$value['password'])){
          $this->Flash->error(__('PASSWORD_LIMIT'));
          $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
          return;
        }
      }
      $userUpdate = $this->Users->patchEntity($userUpdate,$reqData);
      if($this->Users->save($userUpdate)){

        $reqData = ['user_id'=>$checkExistPasswordHash->user_id,'password'=>$password];

        $userOldPasswordCheck = $this->UserOldPasswords->newEntity($reqData);
        $userOldPasswordCheck = $this->UserOldPasswords->patchEntity($userOldPasswordCheck, $reqData);
        if($this->UserOldPasswords->save($userOldPasswordCheck)){
          $userOldPasswordCheck = $this->UserOldPasswords->find('all')->where(['user_id'=>$checkExistPasswordHash->user_id]);
          if($userOldPasswordCheck->count() > 6){
            $userOldPasswordCheck =$userOldPasswordCheck->order('created ASC')->first();
            $userOldPasswordCheck = $this->UserOldPasswords->delete($userOldPasswordCheck);

          }
          $this->ResetPasswordHashes->delete($checkExistPasswordHash);
        }else{
  // pr($userOldPasswordCheck->errors());die;
  //log password not changed
  // throw new BadRequestException(__('can not use earlier used 6 passwords'));
        }

        $this->Flash->success(__('NEW_PASSWORD_UPDATED'));
        $this->_deleteSession();    
        $this->redirect(['action' => 'login']);
      }else{
        $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
        $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
      }
    }
    $this->set('resetToken',$uuid);
    $this->set('_serialize', ['reset-token']);
  }
  public function forceResetPassword()
  {
    $this->viewBuilder()->layout('login-admin');
    $uuid = $this->request->query('reset-token');
    if ($this->request->is('get') && !$uuid) {
      $this->Flash->error(__('BAD_REQUEST'));
      $this->redirect(['action' => 'login']);
    }
    $this->set('resetToken',$uuid);
    $this->set('_serialize', ['reset-token']);
  }

  /**
  * Index method
  *
  * @return \Cake\Network\Response|null
  */


  public function index()
  {
    $users = $this->Users->find('WithDisabled')->contain(['Roles', 'Vendors'])->where(['Users.username !=' => 'admin'])->all();
  //   $users =  $this->paginate(
  //   $this->Users->find('WithDisabled',
  //   [
  //     'contain' => ['Roles', 'Vendors'],
  //     'conditions' => ['Users.username !=' => 'admin']
  //   ]
  // ));

    $loggedInUser = $this->Auth->user();
  //pr($loggedInUser);

    if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
      $users = $this->Users->find('WithDisabled')->contain(['Roles', 'Vendors'])->all();
  //   $users =   $this->Users->find('WithDisabled',
  //   [
  //     'contain' => ['Roles', 'Vendors']
  //   ]
  // );

    }
    else if($loggedInUser['role']->name == self::STAFF_ADMIN_LABEL){
      $users = $this->Users->find('WithDisabled')->contain(['Roles', 'Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id'),'Roles.name <>'=>self::SUPER_ADMIN_LABEL])->all();
  // $users =  $this->Users->find('WithDisabled',
  // [
  //   'contain' => ['Roles', 'Vendors'],
  //   'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id'),'Roles.name <>'=>self::SUPER_ADMIN_LABEL]
  // ]);
    }
    else {
      $users = $this->Users->find('WithDisabled')->contain(['Roles', 'Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id'), 'Roles.name'=>self::STAFF_MANAGER_LABEL])->all();
  // $users =  $this->Users->find('WithDisabled',
  // [
  //   'contain' => ['Roles', 'Vendors'],
  //   'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id'), 'Roles.name'=>self::STAFF_MANAGER_LABEL]
  // ]);
    }
  // $users = $this->paginate($users);
    $this->set(compact('users'));
    $this->set('_serialize', ['users']);
    $this->set('loggedInUser', $loggedInUser);
  }

  /**
  * View method
  *
  * @param string|null $id User id.
  * @return \Cake\Network\Response|null
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function view($id = null)
  {
    $user = $this->Users->find('WithDisabled')->contain(['Vendors','Roles','VendorLocations'])->where(['Users.id' => $id])->first();
    if(!$user){
      $user = $this->Users->find('WithDisabled')->contain(['Vendors','Roles'])->where(['Users.id' => $id])->first();
    }
    
    if(!$user){
      $this->Flash->error(__('USER NOT FOUND'));
      $this->redirect(['action' => 'index']);
    }
    $this->set('user', $user);
    $this->set('_serialize', ['user']);

  }

  /**
  * Add method
  *
  * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
  */
  public function add()
  {
    $user = $this->Users->newEntity();
    if ($this->request->is('post')) {
      if(!isset($this->request->data['vendor_location_id']) || $this->request->data['vendor_id'] == 1){
        $this->request->data['vendor_location_id'] = 0; //Incase of superadmin, vendor location id is to be 0.
      }
      $user = $this->Users->patchEntity($user, $this->request->data);

      if(!$user->errors()){
        if ($this->Users->save($user)) {
          $this->Flash->success(__('ENTITY_SAVED', 'user'));
          $this->redirect(['action' => 'index']);
        } else {
          $this->Flash->error(__('ENTITY_ERROR', 'user'));
  // CakeLog::write('error');
        }
      }else{

        $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
      }
    }

    $loggedInUser = $this->Auth->user();

    if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){

      $vendors = $this->Users->Vendors->find('list')->where(['status'=>1])->all()->toArray();
      $roles = $this->Users->Roles->find('list')->where(['status'=>1])->all()->toArray();
      $locations = $this->Users->VendorLocations->find()->all()->groupBy('vendor_id')->map(function($value, $key){
        $temp = (new Collection($value))->combine('id', 'address');
        return $temp->toArray();
      })->toArray();
      $this->set('vendors', $vendors);
    }else {
  //$vendors = $this->Users->Vendors->find('list')->where(['status'=>1, 'id'=>$loggedInUser['vendor_id']])->all()->toArray();
      $roles = $this->Users->Roles->find('list')->where(['status'=>1,'name <>'=>'admin'])->all()->toArray();
      $locations = $this->Users->VendorLocations->find()
      ->where(['vendor_id' => $loggedInUser['vendor_id']])
      ->all()->groupBy('vendor_id')->map(function($value, $key){
        $temp = (new Collection($value))->combine('id', 'address');
        return $temp->toArray();
      })->toArray();
    }
    $this->set('roles', $roles);
    $this->set('user', $user);
    $this->set('locations', $locations);
    $this->set('loggedInUser', $loggedInUser);
    $this->set('_serialize', ['user']);


  }

  /**
  * Edit method
  *
  * @param string|null $id User id.
  * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function edit($id = null)
  {
    $user = $this->Users->find('WithDisabled')->contain(['Vendors'])->where(['Users.id' => $id])->first();
    if(!$user){
      $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS','user'));
      $this->redirect(['action' => 'index']);
    }
    
    $loggedInUser = $this->Auth->user();
    
    if ($this->request->is(['patch', 'post', 'put'])) {
      if(!isset($this->request->data['vendor_location_id']) || $this->request->data['vendor_id'] == 1){
        $this->request->data['vendor_location_id'] = 0; //Incase of superadmin, vendor location id is to be 0.
      }

      $idleTimer = $user->idle_timer;
      unset($user->vendor);
      $user = $this->Users->patchEntity($user, $this->request->data);
      if(!$user->errors()){
        if ($this->Users->save($user)) {
          if($loggedInUser['id'] == $user->id && $idleTimer != $user->idle_timer){
            $thisSession = $this->request->session();
            $lastSeen = Time::now();
            $lastSeen = strtotime($lastSeen);
            $thisSession->write('lastSeen', $lastSeen);
            $thisSession->write('Auth.User.idle_timer', $user->idle_timer);
          }
          $this->Flash->success(__('ENTITY_SAVED', 'user'));
          $this->redirect(['action' => 'index']);
        } else {
          $this->Flash->error(__('ENTITY_ERROR', 'user'));
        }
      }else{

        $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
      }
    }
    

    
    if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){

      $vendors = $this->Users->Vendors->find('list')->where(['status'=>1])->all()->toArray();
      $roles = $this->Users->Roles->find('list')->where(['status'=>1])->all()->toArray();
      $locations = $this->Users->VendorLocations->find()->all()->groupBy('vendor_id')->map(function($value, $key){
        $temp = (new Collection($value))->combine('id', 'address');
        return $temp->toArray();
      })->toArray();

    }else if($loggedInUser['role']->name == self::STAFF_ADMIN_LABEL){
      $vendors = $this->Users->Vendors->find('list')
      ->where(['status'=>1, 'id'=>$loggedInUser['vendor_id']])
      ->all()
      ->toArray();

      $roles = $this->Users->Roles->find('list')
      ->where(['status'=>1,'name <>'=>'admin'])
      ->all()
      ->toArray();

      $locations = $this->Users->VendorLocations->find()
      ->where(['vendor_id' => $loggedInUser['vendor_id']])
      ->all()->groupBy('vendor_id')->map(function($value, $key){
        $temp = (new Collection($value))->combine('id', 'address');
        return $temp->toArray();
      })->toArray();
    }
    else {
      $vendors = $this->Users->Vendors->find('list')
      ->where(['status'=>1, 'id'=>$loggedInUser['vendor_id']])
      ->all()
      ->toArray();
      $roles = $this->Users->Roles->find('list')
      ->where(['status'=>1,'name'=>'staff_manager'])
      ->all()
      ->toArray();
      $locations = $this->Users->VendorLocations->find()
      ->where(['vendor_id' => $loggedInUser['vendor_id']])
      ->all()->groupBy('vendor_id')->map(function($value, $key){
        $temp = (new Collection($value))->combine('id', 'address');
        return $temp->toArray();
      })->toArray();
    }

    $this->set('loggedInUser', $loggedInUser);
    $this->set(compact('user','vendors','roles', 'locations'));
    $this->set('_serialize', ['user']);
  }

  /**
  * Delete method
  *
  * @param string|null $id User id.
  * @return \Cake\Network\Response|null Redirects to index.
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $user = $this->Users->find('WithDisabled')->contain(['Vendors','Roles'])->where(['Users.id' => $id])->first();
    if ($this->Users->delete($user)) {
      $this->Flash->success(__('ENTITY_DELETED','user'));
    } else {
      $this->Flash->error(__('ENTITY_DELETED_ERROR','user'));
    }

    $this->redirect(['action' => 'index']);
  }

  public function login()
  {

  // $this->loadModel('Vendors');
  // $vendors = $this->Vendors->findById(2)->first();
  // pr($vendors);die;

  // Layout for the admin login
    // pr('here'); pr($this->request); die;
    $this->viewBuilder()->layout('login-admin');
    if ($this->request->is('post')) {
      $cacheKey = 'count_'.$this->request->data['username'];
      $count = Cache::read($cacheKey);
      if($count > 5){
        $this->Flash->error(__('MAXIMUM_LOGIN_ATTEMPT'));
        $this->redirect(['controller' => 'Users',
          'action' => 'login'
          ]);
      }

      $user = $this->Auth->identify();
      if ($user) {
        if($this->Cookie->check('username'))
          $username = $this->Cookie->delete('username');
        Cache::delete($cacheKey);
        $this->loadModel('Roles');
        $user['role'] = $query = $this->Roles->find('RolesById', ['role' => $user['role_id']])->select(['name', 'label'])->first();
        $this->loadModel('VendorPlans');
        $user['plan'] = $query = $this->VendorPlans->findByVendorId($user['vendor_id'])->contain(['Plans.PlanFeatures.Features'])->first();
        $this->loadModel('Vendors');
        $query = $this->Vendors->findById($user['vendor_id'])->first();

        $user['vendor_peoplehub_id'] = $query->people_hub_identifier;

        if($query->status != 1) {
          $this->Flash->error(__('VENDOR_ACCOUNT_DISABLED', $query->org_name ));
          return null;
        }

        $this->Auth->setUser($user);
        $resetPwdHash =$this->_isPasswordResetRequired();
        if($resetPwdHash){
          $this->redirect(['action' => 'forceResetPassword','?'=>['reset-token'=>$resetPwdHash]]);
        }

  //Setup Session Data to Handle View Elements

        $vendorPlanFeatures = $this->Vendors->find()->where(['id'=>$this->Auth->user('vendor_id')])->contain(['VendorPlans.Plans.PlanFeatures.Features','VendorSettings.SettingKeys'])->first();
        $vendorSettings = new Collection($vendorPlanFeatures->vendor_settings);
        $vendorSettings = $vendorSettings->indexBy('setting_key_id');
        $vendorPlanFeatures->vendor_settings = $vendorSettings->toArray();

        $creditCardSetup = $this->Vendors->findById($this->Auth->user('vendor_id'))
        ->contain(['Users','Users.AuthorizeNetProfiles'])
        ->all()
        ->extract('users.{*}.authorize_net_profiles.{*}.is_card_setup')
        ->toArray();
        $cardSetup = 0;

  //Next we loop through the data to check if any user has a card setup. 
        foreach($creditCardSetup as $creditCard){
          if($creditCard == 1){
            $cardSetup = 1;
            break;
          }
        }

        $lastSeen = Time::now();
        $lastSeen = strtotime($lastSeen);
        $lockScreenCheck = false;

  //pr($creditCardSetup); die;
        $session = new Session();
  //$session->delete('CardSetup');
  //Used in AppController
        $session->write('VendorSettings', $vendorPlanFeatures);
        $session->write('CardSetup', $cardSetup);
        $session->write('lastSeen', $lastSeen);
        $session->write('lockScreenCheck', $lockScreenCheck);



        $loggedInUser = $this->Auth->user();

        //Setting username cookie
        $this->Cookie->write('username', $loggedInUser['username']);

        $userId = $loggedInUser['id'];
        if($loggedInUser['role']->name == self::STAFF_MANAGER_LABEL || $loggedInUser['role']->name == self::STAFF_ADMIN_LABEL){
          $this->redirect(['controller' => 'Users',
            'action' => 'dashboard']);
        }
        else {
          $this->redirect(['controller' => 'Vendors',
            'action' => 'index'
            ]);
        }
      }else{
        $this->Flash->error(__('LOGIN_FAILED'));
        if(empty($count)){
          $count = 1;
        }else{
          $count++;
        }
        Cache::write('count_'.$this->request->data['username'],$count);
      }
    }
  }
  // public function isAuthorized($user)
  // {

  //   $action = $this->request->params['action'];
  // // The add and index actions are always allowed.
  //   if (in_array($action, ['logout', 'lockscreen'])) {
  //     return true;
  //   }

  //   if (in_array($action, ['index','edit','dashboard','forceResetPassword']) && $user['role']->name === 'staff_manager') {
  //     return true;
  //   }

  //   if (in_array($action, ['add','index', 'view', 'edit','delete','dashboard','forceResetPassword']) && $user['role']->name === 'admin') {
  //     return true;
  //   }

  //   if (in_array($action, ['add','index','view','delete','edit','dashboard','forceResetPassword']) && in_array($user['role']->name, ['staff_admin'])) {
  //     return true;
  //   }

  // // All other actions require an id.
  //   if (empty($this->request->params['pass'][0])) {
  //     return false;
  //   }




  //   return parent::isAuthorized($user);
  // }


  /**
  * This method is to logout user
  *
  **/
  public function logout()
  {
    $this->Flash->success('You are now logged out.');
    $this->_deleteSession();    
    $this->redirect($this->Auth->logout());
  }

  private function _deleteSession(){

    $user = $this->Auth->user();
    $this->Auth->logout();
    $this->Cookie->config(['path'=>'/','domain'=>$this->request->host(),'encryption'=>false]);
    

    if ($user['role']['name'] == self::SUPER_ADMIN_LABEL) {
      
      $cookie = 'r_s_t';
    
    }else{
      
      $cookie = 'v_t';
    
    }
    
    if($this->Cookie->check($cookie)){
      $token = $this->Cookie->read($cookie);
      if($token){
        Cache::delete($token, $cookie);
      }
      $token = null;
      $this->Cookie->delete($cookie);
    }

    $session = $this->request->session();
    $session->destroy();

  }

  public function lockscreen(){


    if($this->Cookie->check('username'))
      $username = $this->Cookie->read('username');
    else
      $this->redirect($this->Auth->logout());

    $actionUrl = Router::url('/', false).'users/login';
    $this->_deleteSession();

    $userVoiceUrl = 'http://help.buzzydoc.com/knowledgebase/articles/1126477-auto-sign-out-feature';

    // pr($url);die;

    $this->viewBuilder()->layout('login-admin');

    $this->set(compact('username', 'actionUrl', 'userVoiceUrl'));
    $this->set('_serialize', ['username']);

  }
}
