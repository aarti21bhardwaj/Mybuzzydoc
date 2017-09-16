<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;

/**
 * VendorReferralSettings Controller
 *
 * @property \App\Model\Table\VendorReferralSettingsTable $VendorReferralSettings
 */
class VendorReferralSettingsController extends AppController
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
            $vendorReferralSettings = $this->VendorReferralSettings->find()->contain(['Vendors'])->where($this->_vendorCondition)->all();
        }else{
           
            $vendorReferralSettings = $this->VendorReferralSettings->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();
          }
        //$vendorReferralSettings = $this->paginate($this->VendorReferralSettings);
        $this->set(compact('vendorReferralSettings'));
        $this->set('_serialize', ['vendorReferralSettings']); 
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Referral Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        $vendorReferralSetting = $this->VendorReferralSettings->findById($id)
                            ->contain(['ReferralSettingGiftCoupons.GiftCoupons', 'Vendors'])
                            ->first();

        if(!$vendorReferralSetting)
            $vendorReferralSetting = $this->VendorReferralSettings->findById($id)
                                                                  ->contain(['Vendors'])
                                                                  ->first();

        $this->set('vendorReferralSetting', $vendorReferralSetting);
        $this->set('_serialize', ['vendorReferralSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $vendorId = $this->Auth->user('vendor_id'); 

        $this->set(compact('vendorId'));
        $this->set('_serialize', ['vendorId']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Referral Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorId = $this->Auth->user('vendor_id');
        $vendorReferralSettingId = $id;

        $this->set(compact('vendorId', 'vendorReferralSettingId'));
        $this->set('_serialize', ['vendorId', 'vendorReferralSettingId']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Referral Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorReferralSetting = $this->VendorReferralSettings->get($id);
        if ($this->VendorReferralSettings->delete($vendorReferralSetting)) {
            $this->Flash->success(__('ENTITY_DELETED','vendor referral setting'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','vendor referral setting'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user){

        $this->loadModel('VendorSettings');
        if($user['role_id'] != 1){


            $setting = $this->VendorSettings->findByVendorId($user['vendor_id'])
                                                   ->where(['setting_key_id' => 13])
                                                   ->first()
                                                   ->value;

            if(!$setting){
                
                return false;
            }
            
        }
        return parent::isAuthorized($user); 
    }

}