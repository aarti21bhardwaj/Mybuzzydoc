<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Network\Session;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Collection\Collection;
use Cake\Log\Log;
use Cake\I18n\Time;
use AuditStash\Meta\RequestMetadata;
use Cake\Event\EventManager;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

   /*
   * Constant Defined for the admin levels and can be accessed in any controller
   */
   const SUPER_ADMIN_LABEL = 'admin';
   const STAFF_ADMIN_LABEL = 'staff_admin';
   const STAFF_MANAGER_LABEL = 'staff_manager';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authorize' => ['Custom' => [] ],
            'authError' => 'You have been automatically logged out of your dashboard. Please use valid credentials to log back in.',
        ]);
        $this->loadComponent('Cookie');
    }

    public function beforeFilter(Event $event)
    {
        //Log will keep the track of all the request and store it in logs/audit.log file
        Log::write('debug', $this->request);

        if(($this->request->params['controller']=='Vendors' && $this->request->params['action']=='setUpWizard') || ($this->request->params['controller']=='Users' && $this->request->params['action']=='logout')){
            return;
        }


        $user  =$this->Auth->user();
        
        /* Server side Idle Timer */
        $session = $this->request->session();
        $lockScreenCheck = $session->read('lockScreenCheck');
        $idleTime = Configure::read('idleTime');

        if($user && $user['idle_timer'] && $this->request->url != 'users/lockscreen'){

            $currentTime = Time::now();
            $currentTime = strtotime($currentTime);
            $lastSeen = $session->read('lastSeen');
            if(($currentTime - $lastSeen) >= ($idleTime/1000) && isset($lastSeen) && $lastSeen != ""){

                return $this->redirect(['controller' => 'Users', 'action' => 'lockscreen']);
            
            }else{
                if(!$lockScreenCheck){
                    
                    $session->write('lastSeen', $currentTime);
                }    
            }
        
        }elseif($user && $this->request->url == 'users/lockscreen'){

            $currentTime = Time::now();
            $currentTime = strtotime($currentTime);
            $lastSeen = $session->read('lastSeen');
            if(($currentTime - $lastSeen) < ($idleTime/1000)){

                $session->write('lockScreenCheck', true);
                return $this->redirect($this->referer());
            }
        
        }elseif($lockScreenCheck == true){

            $session->write('lockScreenCheck', false);
        }

        if($user && ($user['role']->name === self::STAFF_ADMIN_LABEL || $user['role']->name === self::STAFF_MANAGER_LABEL)){
            $this->loadModel('Vendors');
            $vendor = $this->Vendors->find()->where(['id'=>$user['vendor_id']])->first();
            //get the vendor setting id to update sandbox mode to live mode
            if(!$vendor->template_id || $vendor->template_id == 0){
                 return $this->redirect(['controller' => 'Vendors', 'action' => 'setUpWizard']);
            }
        }

        //Meta data for Id and IP of the logged in User for auditing.
        EventManager::instance()->on(new RequestMetadata($this->request, $this->Auth->user('id')));

        if(($this->request->params['action'] !='forceResetPassword')){
            $resetPwdHash =$this->_isPasswordResetRequired();
            if($resetPwdHash){
                $this->redirect(['controller'=>'Users','action' => 'forceResetPassword','?'=>['reset-token'=>$resetPwdHash]]);
            }
        }
    }

    public function beforeRender(Event $event){

        //Log will keep the track of all the response and store it in logs/audit.log file
      //  Log::write('debug', $this->viewVars);

        $user  =$this->Auth->user();
        if($user){
        $session = new Session();
        // Session key is set while user logs in Users Controller Action Login
        if (!$session->check('VendorSettings') && !$session->check('CardSetup') ) {
            // Something bad happened that deleted these session keys
            //Log the user out now.
            $this->redirect(['controller'=>'Users', 'action' => 'logout']);
        }

        $topSearch = true;

        $vendorPlanFeatures = $session->read('VendorSettings');
        //Check if the user is set, he is a either a staff admin or a staff manager, and, action isn't setup wizard.
        if($user && ($user['role']->name === self::STAFF_ADMIN_LABEL || $user['role']->name === self::STAFF_MANAGER_LABEL) && $this->request->params['action'] != 'setUpWizard'){

            //Checking if staging or live mode
            if(!empty($vendorPlanFeatures['vendor_settings']) ){
            $topHeader = ['id'=>$vendorPlanFeatures['vendor_settings'][7]->id,'value'=>$vendorPlanFeatures['vendor_settings'][7]->value,'org_name' => $vendorPlanFeatures->org_name,'vendor_id' => $vendorPlanFeatures->id, 'role_id' => $user['role_id']];
                $this->set('topHeader', $topHeader);
            }

            //Checking if credit card is setup or not
        // Session key is set while user logs in Users Controller Action Login
        // It is also set when the user adds a new card in AuthorizeNetController
            $cardSetup = $session->read('CardSetup');
 
           // Now we will set the variable for the view which will control the display of the message 
            // to setup the card.
            if($cardSetup == 0 && $vendorPlanFeatures['vendor_settings'][4]->value == 'wallet_credit') {
                $this->set('cardSetup', false);
                if($vendorPlanFeatures['vendor_settings'][4]->value == 'wallet_credit' && $vendorPlanFeatures['vendor_settings'][7]->value == 1){
                    
                    $topSearch = false;
                    if($this->request->params['controller'] == 'Users' && $this->request->params['action'] == 'dashboard'){
                        $this->redirect(['controller' => 'AuthorizeNetProfiles', 'action' => 'index']);
                    }
                } 
            } else if($cardSetup == 0 && $vendorPlanFeatures['vendor_settings'][4]->value == 'store_credit' && $vendorPlanFeatures['vendor_settings'][25]->value == 0 ){
                
                $this->set('cardSetup', true);

            }
            else if($cardSetup == 0 && $vendorPlanFeatures['vendor_settings'][4]->value == 'store_credit' && $vendorPlanFeatures['vendor_settings'][25]->value == 1 ){
                
                $this->set('cardSetup', false);

            } else {
                $this->set('cardSetup', true);
            }

        }else{
            $this->set('topHeader', '');
            $this->set('cardSetup', '');
        }
        $vendorSettings = $vendorPlanFeatures->vendor_settings;
        $temp = new Collection($vendorSettings);
        $temp = $temp->indexBy('setting_key_id');
        $vendorSettings = $temp->toArray();
        $vendorPlanFeatures = $vendorPlanFeatures->vendor_plans[0]->plan->plan_features;
        $idleTime = Configure::read('idleTime');

        
        $this->set('topSearch', $topSearch);
        $this->set('vendorSettings', $vendorSettings);
        $this->set('vendorPlanFeatures', $vendorPlanFeatures);
        $this->set('patientPortalUrl', Configure::read('authorizeDotNet.redirectUrl').'/patient-portal/'.$this->Auth->user('vendor_id'));        
        if($user['idle_timer']){
            $this->set('idleTime', $idleTime);
        }else{
            $this->set('idleTime', 0);
        }
    }
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        $user = $this->Auth->user();
        $sideNavData = ['id'=>$user['id'],'first_name' => $user['first_name'],'last_name' => $user['last_name'] ,'role_name' => $user['role']['name'],'role_label' => $user['role']['label']];
        $this->set('sideNavData', $sideNavData);
    }

    protected function _createResetPasswordHash($userId,$uuid){
        $this->loadModel('ResetPasswordHashes');
        $resetPasswordrequestData = $this->ResetPasswordHashes->findByUserId($userId)->first();
        if($resetPasswordrequestData){
            return $resetPasswordrequestData->hash;
        }
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

    protected function _isPasswordResetRequired(){
        $user = $this->Auth->user();
        if(!$user){
            return false;
        }
        $this->loadModel('UserOldPasswords');
        $userOldPasswordCheck = $this->UserOldPasswords->find('all')
        ->where(['user_id'=>$user['id']])
        ->order('created DESC')
        ->first();
        if(!empty($userOldPasswordCheck)){
          $time = new Time($userOldPasswordCheck->modified);
      }else{
          $time = new Time($user['created']);
      }
      if(!$time->wasWithinLast(60)){
          $resetPwdHash =$this->_createResetPasswordHash($user['id'],$user['uuid']);
          return $resetPwdHash;
      }
      return false;

    }

    /*
    * isAuthorized function is defined so that users can access to there defined roles
    */
//     public function isAuthorized($user)
//     {


//     if($user['role']->name == self::SUPER_ADMIN_LABEL) {
//         $this->_vendorCondition == null;
//     } else {
//         $this->_vendorCondition == ['vendor_id' => $user['vendor_id']];
//     }
//     $action = $this->request->params['action'];
//     $controller = $this->request->params['controller'];
//     if (in_array($action, ['logout'])) {
//         return true;
//     }
//     if (in_array($action, ['index', 'view', 'edit','add']) && $user['role']->name === 'staff_manager') {
//         return true;
//     }

//     if (in_array($controller, ['LegacyRedemptions', 'LegacyRewards','VendorLocations'] ) && in_array($action, ['index', 'view', 'edit', 'add']) && $user['role']->name === 'staff_manager') {
//         return true;
//     }
//     if (in_array($action, ['add','index', 'view', 'edit','delete','issueCards']) && $user['role']->name === 'admin') {
//         return true;
//     }
//     if (in_array($action, ['add','index','view','delete','edit','pointsHistoryReport']) && in_array($user['role']->name, ['staff_admin'])) {
//         return true;
//     }

//     if (empty($this->request->params['pass'][0])) {
//         return false;
//     }

//     return false;
// }

}
