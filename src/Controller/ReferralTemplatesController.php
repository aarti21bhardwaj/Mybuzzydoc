<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;

/**
 * ReferralTemplates Controller
 *
 * @property \App\Model\Table\ReferralTemplatesTable $ReferralTemplates
 */
class ReferralTemplatesController extends AppController
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
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $referralTemplates = $this->ReferralTemplates->find()->contain(['Vendors'])->where($this->_vendorCondition)->all();
        }else{
            $referralTemplates = $this->ReferralTemplates->find()->contain(['Vendors'])->where(['vendor_id' => $this->Auth->user('vendor_id')])->all();
          }
          $this->set(compact('referralTemplates'));
          $this->set('_serialize', ['referralTemplates']);
          $this->set('loggedInUser', $loggedInUser); 
        
    }

    /**
     * View method
     *
     * @param string|null $id Referral Template id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referralTemplate = $this->ReferralTemplates->find('all')->where(['ReferralTemplates.id' => $id])->contain(['Vendors' => function($x) {
                    return $x->where([$this->_vendorCondition]);
        }])->first();
        if($referralTemplate)
        {
            $this->set('referralTemplate', $referralTemplate);
            $this->set('_serialize', ['referralTemplate']);
        }else{
            $this->Flash->error(__('RECORD NOT FOUND.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loggedInUser = $this->Auth->user();
        $referralTemplate = $this->ReferralTemplates->newEntity();
        if ($this->request->is('post')) {
            $referralTemplate = $this->ReferralTemplates->patchEntity($referralTemplate, $this->request->data);
            if ($this->ReferralTemplates->save($referralTemplate)) {
                $this->Flash->success(__('ENTITY_SAVED', 'referral template'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'referral template'));
            }
        }
        $vendors = $this->ReferralTemplates->Vendors->find('list', array(
                                                        'conditions' => array('Vendors.status !=' => 0)
                                                             ));
        $this->set(compact('referralTemplate', 'vendors'));
        $this->set('_serialize', ['referralTemplate']);
        $this->set('loggedInUser', $loggedInUser);

    }

    /**
     * Edit method
     *
     * @param string|null $id Referral Template id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loggedInUser = $this->Auth->user();
        
        $referralTemplate = $this->ReferralTemplates->find('all')->where(['ReferralTemplates.id' => $id])->contain(['Vendors' => function($x) {
                    return $x->where([$this->_vendorCondition]);
        }])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $referralTemplate = $this->ReferralTemplates->patchEntity($referralTemplate, $this->request->data);
            if ($this->ReferralTemplates->save($referralTemplate)) {
                $this->Flash->success(__('ENTITY_SAVED', 'referral template'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'referral template'));
            }
        }
        $vendors = $this->ReferralTemplates->Vendors->find('list', array(
                                                        'conditions' => array('Vendors.status !=' => 0)
                                                             ));
        $this->set(compact('referralTemplate', 'vendors'));
        $this->set('_serialize', ['referralTemplate']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Delete method
     *
     * @param string|null $id Referral Template id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referralTemplate = $this->ReferralTemplates->find('all')->where(['ReferralTemplates.id' => $id])->contain([])->first();
        if($referralTemplate)
        {
            if ($this->ReferralTemplates->delete($referralTemplate)) {
                $this->Flash->success(__('ENTITY_DELETED','referral template'));
            } else {
                $this->Flash->error(__('ENTITY_DELETED_ERROR','referral template'));
            }

            return $this->redirect(['action' => 'index']);
        }else {
            $this->Flash->error(__('RECORD NOT FOUND.'));
            return $this->redirect(['action' => 'index']);
        }
        }
    
}