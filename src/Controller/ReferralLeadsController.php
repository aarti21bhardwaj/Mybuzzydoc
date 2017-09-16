<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Network\Exception\NotFoundException;
use Cake\Collection\Collection;
use Cake\I18n\Date;

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class ReferralLeadsController extends AppController
{
    
    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;

    public function initialize(){
        parent::initialize();
        // $this->Auth->config('authorize', ['Controller']);
        $this->Auth->allow(['add', 'viewForm']);
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('post')|| $this->request->is('put')){
            $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $from = new Date($from);
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            } else {
            $this->Flash->error(__('Please enter a valid date range')); 
            return $this->redirect(['action' => 'index']);
            }
        } else{   
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
        }
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
          
          $referralLeads = $this->ReferralLeads
                                ->find()
                                ->contain(['Referrals', 'ReferralStatuses','VendorReferralSettings', 'Vendors'])
                                ->where([$this->_vendorCondition])->where(['ReferralLeads.created >=' => $from,'ReferralLeads.created <' => $to, ])->limit(2000)->order(['ReferralLeads.created' => 'DESC'])
                                ->all();
        }else{

          $referralLeads = $this->ReferralLeads
                                ->find()
                                ->contain(['Referrals', 'ReferralStatuses','VendorReferralSettings','Vendors'])
                                ->where(['ReferralLeads.vendor_id' => $this->Auth->user('vendor_id')])
                                ->where(['ReferralLeads.created >=' => $from,'ReferralLeads.created <' => $to, ])->limit(2000)->order(['ReferralLeads.created' => 'DESC'])
                                ->all();
          // pr($referralLeads);die;
        }
        $this->set(compact('referralLeads', 'loggedInUser'));
        // $this->set(compact('referralLeads'));
        // $this->set('_serialize', ['referralLeads']);
    }

    /**
     * View method
     *
     * @param string|null $id Referral Lead id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referralLead = $this->ReferralLeads->get($id, [
            'contain' => ['Referrals', 'VendorReferralSettings', 'Vendors','ReferralStatuses']
        ]);

        $this->set('referralLead', $referralLead);
        $this->set('_serialize', ['referralLead']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        /*This is a template for the reviw form as it will go from PeopleHub
        * TODO
        */
        $this->loadModel('Referrals');
        $this->viewBuilder()->layout('review-form');
        $get_uuid = $this->request->params['pass'][0];
        if(empty($get_uuid)){
          throw new NotFoundException(__('BAD_REQUEST'));
        }
        $referralData = $this->Referrals->findByUuid($get_uuid)->contain(['ReferralLeads'])->first(); 

        if(!$referralData) {
          throw new NotFoundException(__('BAD_REQUEST'));
        }

        if($referralData->referral_lead != null){

          return $this->redirect(['action' => 'viewForm', $get_uuid]);

        }

        $referralLead = $this->ReferralLeads->newEntity();
        if ($this->request->is('post')) {

            if(!isset($this->request->data['last_name']) || !$this->request->data['last_name']){

              $this->Flash->error(__('FIELD_REQUIRED', 'Last Name'));
              return false;
            }

            if(!isset($this->request->data['email']) || !$this->request->data['email']){

              $this->Flash->error(__('FIELD_REQUIRED', 'Email'));
              return false;
            }

            if(!isset($this->request->data['phone']) || !$this->request->data['phone']){

              $this->Flash->error(__('FIELD_REQUIRED', 'Phone'));
              return false;
            }


            $referralStatusId = $this->ReferralLeads->ReferralStatuses->findByStatus('Pending')->first()->id;
            // Get Referral Id Of The User
            $this->request->data['referral_id']=$referralData->id;

            // Get Vendor Id As For Which Vendor Referral Has Been Done 
            $this->request->data['vendor_id']= $referralData->vendor_id;
            
            $this->request->data['referral_status_id'] = $referralStatusId;
            

            $referralLead = $this->ReferralLeads->patchEntity($referralLead, $this->request->data);
            if ($this->ReferralLeads->save($referralLead)) {
                $this->Flash->success(__('THANK_YOU_MESSAGE'));

                return $this->redirect(['action' => 'viewForm',$get_uuid]);
            } else {
                $this->Flash->error(__('THANK_YOU_MESSAGE_ERROR'));
            }
        }

        $vendors = $this->ReferralLeads->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('referralLead', 'vendors', 'referralData'));
        $this->set('_serialize', ['referralLead']);
    }

    public function viewForm($referralUuid)
    {
        $this->loadModel('Referrals');
        $this->viewBuilder()->layout('review-form');
        $get_uuid = $referralUuid;
        if(empty($get_uuid)){
          throw new NotFoundException(__('BAD_REQUEST'));
        }
        $referral = $this->Referrals->findByUuid($get_uuid)->contain(['ReferralLeads'])->first(); 
        if(!$referral) {
          throw new NotFoundException(__('BAD_REQUEST'));
        }

        $referralLead = $referral->referral_lead;

        $this->set(compact('referralLead'));
        $this->set('_serialize', ['referralLead']);
      
      }




    /**
     * Edit method
     *
     * @param string|null $id Referral Lead id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referralLead = $this->ReferralLeads->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $referralLead = $this->ReferralLeads->patchEntity($referralLead, $this->request->data);
            if ($this->ReferralLeads->save($referralLead)) {
                $this->Flash->success(__('ENTITY_SAVED', 'referral lead'));
             
                return $this->redirect($this->request->data['redirectTo']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'referral lead'));
            }
        }
        $redirectTo = $this->referer();
        $referrals = $this->ReferralLeads->Referrals->find('list', ['limit' => 200]);
        $vendorReferralSettings = $this->ReferralLeads->VendorReferralSettings->find('list', ['limit' => 200]);
        $vendors = $this->ReferralLeads->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('referralLead', 'referrals', 'vendorReferralSettings', 'vendors','redirectTo'));
        $this->set('_serialize', ['referralLead']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Referral Lead id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referralLead = $this->ReferralLeads->get($id);
        if ($this->ReferralLeads->delete($referralLead)) {
            $this->Flash->success(__('ENTITY_DELETED','referral lead'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','referral lead'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
}