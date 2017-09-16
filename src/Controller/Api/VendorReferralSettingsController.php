<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\UnauthorizedException;
/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class VendorReferralSettingsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        // $this->Auth->config('authorize', ['Controller']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Referral Lead id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function index($id = null)
    {
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            
            $vendorReferralSettings = $this->VendorReferralSettings->find()->all()->groupBy('vendor_id')->toArray();
        
        }else{

            $vendorReferralSettings = $this->VendorReferralSettings->findByVendorId($loggedInUser['vendor_id'])->all()->groupBy('vendor_id')->toArray();
        }
        

        $this->set(compact('vendorReferralSettings'));
        $this->set('_serialize', ['vendorReferralSettings']);
    }

    
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $loggedInUser = $this->Auth->user();

        $vendorReferralSetting = $this->VendorReferralSettings->newEntity();

        if(!isset($this->request->data['referral_setting_gift_coupon']) || !$this->request->data['referral_setting_gift_coupon']['gift_coupon_id']){
            unset($this->request->data['referral_setting_gift_coupon']);
            $vendorReferralSetting = $this->VendorReferralSettings->patchEntity($vendorReferralSetting, $this->request->data);
        }
        else{

            $vendorReferralSetting = $this->VendorReferralSettings->patchEntity($vendorReferralSetting, $this->request->data, ['associated' => 'ReferralSettingGiftCoupons']); 
        }

        if (!$this->VendorReferralSettings->save($vendorReferralSetting)) {

            throw new InternalErrorException(__('ENTITY_ERROR', 'Referral Level'));
        }

        $message = "Referral Level has been saved";

        $this->set(compact('vendorReferralSetting', 'message'));
        $this->set('_serialize', ['vendorReferralSetting', 'message']);
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
        
        if (!$this->request->is('put')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $loggedInUser = $this->Auth->user();
        $vendorReferralSetting = $this->VendorReferralSettings->findById($id)
                            ->contain(['ReferralSettingGiftCoupons'])
                            ->first();

        if(!$vendorReferralSetting){
            throw new NotFoundException(__('Record not found in vendor referral settings'));
        }

        if($vendorReferralSetting->referral_setting_gift_coupon){

            $this->VendorReferralSettings
                 ->ReferralSettingGiftCoupons
                 ->delete($vendorReferralSetting->referral_setting_gift_coupon);
        }

        if(!isset($this->request->data['referral_setting_gift_coupon']) || !$this->request->data['referral_setting_gift_coupon']['gift_coupon_id']){
            unset($this->request->data['referral_setting_gift_coupon']);
            $vendorReferralSetting = $this->VendorReferralSettings->patchEntity($vendorReferralSetting, $this->request->data);
        }
        else{

            $vendorReferralSetting = $this->VendorReferralSettings->patchEntity($vendorReferralSetting, $this->request->data, ['associated' => 'ReferralSettingGiftCoupons']); 
        }

        if (!$this->VendorReferralSettings->save($vendorReferralSetting)) {
            throw new InternalErrorException(__('ENTITY_ERROR', 'Referral Level'));
        }

        $message = "Referral Level has been saved";

        $this->set(compact('message', 'vendorReferralSetting'));
        $this->set('_serialize', ['vendorReferralSetting', 'message']);
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
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $vendorReferralSetting = $this->VendorReferralSettings->findById($id)
                            ->contain(['ReferralSettingGiftCoupons.GiftCoupons', 'Vendors'])
                            ->first();

        if(!$vendorReferralSetting)
            $vendorReferralSetting = $this->VendorReferralSettings->findById($id)
                                                                  ->contain(['Vendors'])
                                                                  ->first();
        if(!$vendorReferralSetting){
            throw new NotFoundException(__('Record not found in vendor referral settings'));
        }

        $this->set('vendorReferralSetting', $vendorReferralSetting);
        $this->set('_serialize', ['vendorReferralSetting']);
    }

    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }


    public function isAuthorized(){

        if($this->Auth->user('role_id') != 1){

            $this->loadModel('VendorSettings');
            $setting = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                                   ->where(['setting_key_id' => 13])
                                                   ->first()
                                                   ->value;
            if(!$setting)
                throw new UnauthorizedException(__('You are not authorized to access that location'));          
        }
        return true;
    }
}