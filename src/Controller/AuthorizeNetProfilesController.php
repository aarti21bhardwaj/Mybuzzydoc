<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Network\Exception\NotFoundException;
use Cake\Collection\Collection;
use Cake\Mailer\MailerAwareTrait;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\View\Helper\UrlHelper;
use Cake\Routing\Router;
use Cake\I18n\Date;

/**
 * AuthorizeNetProfiles Controller
 *
 * @property \App\Model\Table\AuthorizeNetProfilesTable $AuthorizeNetProfiles
 */
class AuthorizeNetProfilesController extends AppController
{
    public function initialize(){
        parent::initialize();
        $this->loadComponent('Auth');
        // $this->Auth->config('authorize', ['Controller']);
        $this->loadComponent('AuthorizeDotNet');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('VendorDepositBalances');
        $this->loadModel('Vendors');
        $this->loadModel('Users');

        //Check if ProfileId exists or not.
        $profileId = $this->AuthorizeNetProfiles->findByUserId($this->Auth->user('id'))->first();
        if(!empty($profileId)){
          //Get User Info (email) from profileId of the user
          $getUserInfo = $this->Users->findById($profileId->user_id)->first();
        }

        // $profileId->fetchAuditLogs(); 
        $addCardUrl = Configure::read('authorizeDotNet.addPaymentUrl');
        $editCardUrl = Configure::read('authorizeDotNet.editPaymentUrl');

        $this->set('addCardUrl', $addCardUrl);
        $this->set('editCardUrl', $editCardUrl);
        if(empty($profileId)){
        //Do this if profile id does not exist.
          $profileId = $this->AuthorizeDotNet->createCustomerProfile($this->Auth->user('email'), $this->Auth->user('id'));
          if($profileId['code'] == 'failure'){
            $this->Flash->error(__($profileId['error']));
            return;
          }
        else {
            //Save to the table
            $profileId = $profileId['data'];
            $data = array();
            $data['user_id'] = $this->Auth->user('id');
            $data['profile_identifier'] = $profileId;
            
            $authorizeNetProfile = $this->AuthorizeNetProfiles->newEntity();
            $authorizeNetProfile = $this->AuthorizeNetProfiles->patchEntity($authorizeNetProfile, $data);
            if ($this->AuthorizeNetProfiles->save($authorizeNetProfile)) {
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR','authorize net profile'));
            }
            $this->set(compact('authorizeNetProfile'));
            $this->set('_serialize', ['authorizeNetProfile']);
        }
    }
    else {
        $profileId = $profileId->profile_identifier;
        // pr($profileId); die('here');
        if(isset($getProfile['error']) && $getProfile['error']){

          $this->Flash->error(__($getProfile['error']. "Card is already setup with this email id for another Vendor. Delete old id from authorizeDotNet account & entry from authorizeNetProfiles table for corresponding user"));
          return $this->redirect(['controller'=>'Users','action' => 'dashboard']);

        }
        $getProfile = $this->AuthorizeDotNet->getCustomerProfile($profileId);
        /*foreach ($getProfile as $getCardLists) {
          pr($getCardLists);
        } die();*/
        $data = array();
        if(!$getProfile['data']['totalCards']) {
            $this->Flash->success(__('PLEASE_SETUP_THE_CARD'));
            $data['is_card_setup'] = 0;
            $session = new Session();
            $session->delete('CardSetup');
            $session->write('CardSetup', 0);
        } else {

            
            //TODO: Validate the credit card to using Authorize.net method for validation
            $this->set('cards', $getProfile['data']['paymentCards']);
            //$data['is_card_setup'] = 1;
            $data['payment_profileid'] = $getProfile['data']['paymentProfileId'];
            $data['is_card_setup'] = 1;
 
            //Fire event to charge the credit card
            $vendorId = $this->Auth->user('vendor_id');
            $amount = $this->Vendors->findById($vendorId)->first();
            $VendorDepositBalances = $this->VendorDepositBalances->findByVendorId($vendorId)->first();
                       
            $currentDateTime = Date::now();
            $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
            if(!$VendorDepositBalances){
              $data = array();
              $data['is_card_setup'] = 1;
              $data['user_id'] = $this->Auth->user('id');
              $data['vendor_id'] = $this->Auth->user('vendor_id');
              $data['balance'] = $amount->min_deposit;
              $data['profileId'] = $profileId;
              $data['payment_profileid'] = $getProfile['data']['paymentProfileId'];
              $data['reason'] = $currentDateTime.' is charged via C.C.';
              $data['first_name'] = $this->Auth->user('first_name');
              $data['email'] = $getUserInfo->email;

              $adminEmail = Configure::read('adminEmail');
              $data['bcc'] = $adminEmail; //Admin Email
              //Credit Card Charge Event
              $event = new Event('CreditCard.beforeCharge', $this, [
                'arr' => [
                      'hashData' => $data,
                      'eventId' => 5, //give the event_id for which you want to fire the email
                ]
              ]);
            
              $this->eventManager()->dispatch($event);
              $VendorDepositBalances = $this->VendorDepositBalances->newEntity();
              $VendorDepositBalances = $this->VendorDepositBalances->patchEntity($VendorDepositBalances, $data);
              $this->VendorDepositBalances->save($VendorDepositBalances);
            }

            $session = new Session();
            $session->delete('CardSetup');
            $session->write('CardSetup', 1);
        } //pr($data); die('get data');
        
        $authorizeNetProfile = $this->AuthorizeNetProfiles->find()->where(['profile_identifier'=>$profileId])->first();
        if(!$authorizeNetProfile){
            die('unknown entity');
        }
        $authorizeNetProfile = $this->AuthorizeNetProfiles->patchEntity($authorizeNetProfile, $data);
        $this->AuthorizeNetProfiles->save($authorizeNetProfile);

        // If profileId exists then fetch the token and fetch the payment profile from the API.
        $hostedProfilePage = $this->AuthorizeDotNet->getHostedProfilePage($profileId);//Once token is fetched set the variables for the view to create the form.
        $this->set('profileId', $profileId);
        $this->set('getProfile', $getProfile);
        $this->set('hostedProfilePage', $hostedProfilePage);
        $this->set(compact('authorizeNetProfiles'));
        $this->set('_serialize', ['authorizeNetProfiles']);
    }
}

    /**
     * View method
     *
     * @param string|null $id Authorize Net Profile id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $authorizeNetProfile = $this->AuthorizeNetProfiles->get($id, [
            'contain' => []
            ]);

        $this->set('authorizeNetProfile', $authorizeNetProfile);
        $this->set('_serialize', ['authorizeNetProfile']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $authorizeNetProfile = $this->AuthorizeNetProfiles->newEntity();
        if ($this->request->is('post')) {
            $authorizeNetProfile = $this->AuthorizeNetProfiles->patchEntity($authorizeNetProfile, $this->request->data);
            if ($this->AuthorizeNetProfiles->save($authorizeNetProfile)) {
                $this->Flash->success(__('ENTITY_SAVED','authorize net profile'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR','authorize net profile'));
            }
        }
        $this->set(compact('authorizeNetProfile'));
        $this->set('_serialize', ['authorizeNetProfile']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Authorize Net Profile id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $authorizeNetProfile = $this->AuthorizeNetProfiles->get($id, [
            'contain' => []
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $authorizeNetProfile = $this->AuthorizeNetProfiles->patchEntity($authorizeNetProfile, $this->request->data);
            if ($this->AuthorizeNetProfiles->save($authorizeNetProfile)) {
                $this->Flash->success(__('ENTITY_SAVED','authorize net profile'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR','authorize net profile'));
            }
        }
        $this->set(compact('authorizeNetProfile'));
        $this->set('_serialize', ['authorizeNetProfile']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Authorize Net Profile id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $authorizeNetProfile = $this->AuthorizeNetProfiles->get($id);
        if ($this->AuthorizeNetProfiles->delete($authorizeNetProfile)) {
            $this->Flash->success(__('ENTITY_DELETED','authorize net profile'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','authorize net profile'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
