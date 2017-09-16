<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Collection\Collection;

/**
 * VendorEmailSettings Controller
 *
 * @property \App\Model\Table\VendorEmailSettingsTable $VendorEmailSettings
 */
class VendorEmailSettingsController extends AppController
{
    public function initialize(){
        parent::initialize();
        // $this->Auth->config('authorize', ['Controller']);
    }

    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        $loggedInUser = $this->Auth->user();

        $this->loadModel('EmailSettings');

        $defaultEmailSettings = $this->EmailSettings->find()->contain(['EmailLayouts', 'EmailTemplates', 'Events'])->all()->toArray();
        // pr($vendorEmailSettings); die;
        $vendorEmailSetting = $this->VendorEmailSettings->find()
                                                        ->contain(['EmailLayouts', 'EmailTemplates', 'Events'])
                                                        ->where(['VendorEmailSettings.vendor_id =' => $this->Auth->user('vendor_id')])
                                                        ->all()
                                                        ->indexBy('event_id')
                                                        ->toArray();
        // pr($vendorEmailSetting); die;
        // $vendorEvent = 

        $this->set('defaultEmailSettings', $defaultEmailSettings);
        $this->set('vendorEmailSetting', $vendorEmailSetting);
        $this->set('loggedInUser', $loggedInUser);

        
        // if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
        //   $vendorEmailSettings = $this->VendorEmailSettings->find()->contain(['Vendors', 'EmailLayouts', 'EmailTemplates', 'Events'])->all();
        //     // $this->paginate = [
        //     // 'contain' => ['Vendors', 'EmailLayouts', 'EmailTemplates', 'Events']
        //     // ];

        // } else{
        //     $vendorEmailSettings = $this->VendorEmailSettings->find()->contain(['Vendors', 'EmailLayouts', 'EmailTemplates', 'Events'])->where(['VendorEmailSettings.vendor_id =' => $this->Auth->user('vendor_id')])->all();
        //     // $this->paginate = [
        //     // 'contain' => ['Vendors', 'EmailLayouts', 'EmailTemplates', 'Events'],
        //     // 'conditions' => ['VendorEmailSettings.vendor_id =' => $this->Auth->user('vendor_id')]
        //     // ];

        // }
        // $vendorEmailSettings = $this->paginate($this->VendorEmailSettings);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Email Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorEmailSetting = $this->VendorEmailSettings->get($id, [
            'contain' => ['Vendors', 'EmailLayouts', 'EmailTemplates', 'Events']
            ]);
        // pr($vendorEmailSetting); die;
        $this->set('vendorEmailSetting', $vendorEmailSetting);
        $this->set('_serialize', ['vendorEmailSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $loggedInUser = $this->Auth->user();
        $this->loadModel('EmailSettings');
        $emailSettingData = $this->EmailSettings->findById($id)->contain(['EmailLayouts', 'EmailTemplates', 'Events'])->first();
        $this->request->data['event_id'] = $emailSettingData->event_id;
        // pr($emailSettingData); die;
        $vendorEmailSetting = $this->VendorEmailSettings->newEntity();
        if ($this->request->is('post')) {
            $vendorEmailSetting = $this->VendorEmailSettings->patchEntity($vendorEmailSetting, $this->request->data);
            if ($this->VendorEmailSettings->save($vendorEmailSetting)) {
                $this->Flash->success(__('ENTITY_SAVED', 'Vendor Email Setting'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'Vendor Email Setting'));
            }
        }
        $vendors = $this->VendorEmailSettings->Vendors->find('list', ['limit' => 200]);
        $events = $this->VendorEmailSettings->Events->find('list');
        $emailLayouts = $this->VendorEmailSettings->EmailLayouts->find('list', ['limit' => 200]);
        $emailTemplates = $this->VendorEmailSettings->EmailTemplates->find('list', ['limit' => 200]);
        $this->set(compact('vendorEmailSetting', 'vendors', 'emailLayouts', 'emailTemplates', 'events'));
        $this->set('_serialize', ['vendorEmailSetting']);
        $this->set('emailSettingData', $emailSettingData);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Email Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // pr(' m here '); die;
        $loggedInUser = $this->Auth->user();
        $vendorEmailSetting = $this->VendorEmailSettings->get($id, [
            'contain' => []
            ]);
        // pr($vendorEmailSetting); die;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorEmailSetting = $this->VendorEmailSettings->patchEntity($vendorEmailSetting, $this->request->data);
            if ($this->VendorEmailSettings->save($vendorEmailSetting)) {
                $this->Flash->success(__('ENTITY_SAVED', 'Vendor Email Setting'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'Vendor Email Setting'));
            }
        }
        $vendors = $this->VendorEmailSettings->Vendors->find('list', ['limit' => 200]);
        $events = $this->VendorEmailSettings->Events->find('list');
        $emailLayouts = $this->VendorEmailSettings->EmailLayouts->find('list', ['limit' => 200]);
        $emailTemplates = $this->VendorEmailSettings->EmailTemplates->find('list', ['limit' => 200]);
        $this->set(compact('vendorEmailSetting', 'vendors', 'emailLayouts', 'emailTemplates', 'events'));
        $this->set('_serialize', ['vendorEmailSetting']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Email Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorEmailSetting = $this->VendorEmailSettings->get($id);
        if ($this->VendorEmailSettings->delete($vendorEmailSetting)) {
            $this->Flash->success(__('ENTITY_DELETED', 'Vendor Email Setting'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'Vendor Email Setting'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // public function isAuthorized($user)
    // {
    //   if($user['role']->name === 'staff_admin' || $user['role']->name === 'staff_manager' ){
    //   $this->loadModel('VendorSettings');
    //   $emailSetting = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))->contain(['SettingKeys' => function($q){
    //         return $q->where(['name' => 'Custom Emails']);
    //     }])->first()->value;

    //     if ($emailSetting == TRUE) {
    //         return true;
    //       }
    //       else{
    //         return false;
    //       }
    //    }
    //     return parent::isAuthorized($user);
    // }
}
