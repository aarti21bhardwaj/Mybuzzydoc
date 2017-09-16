<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\InternalServerError;
use Cake\Network\Exception\BadRequestException;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * VendorInstantGiftCouponSettings Controller
 *
 * @property \App\Model\Table\VendorInstantGiftCouponSettingsTable $VendorInstantGiftCouponSettings
 */
class VendorInstantGiftCouponSettingsController extends AppController
{

    const SUPER_ADMIN_LABEL = 'admin';
    const STAFF_ADMIN_LABEL = 'staff_admin';
    const STAFF_MANAGER_LABEL = 'staff_manager';

    public function initialize(){
        parent::initialize();
        $this->Auth->allow(['giftCoupons']);
        $this->loadModel('VendorSettings');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Vendors']
        ];
        $vendorInstantGiftCouponSettings = $this->paginate($this->VendorInstantGiftCouponSettings);

        $this->set(compact('vendorInstantGiftCouponSettings'));
        $this->set('_serialize', ['vendorInstantGiftCouponSettings']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Instant Gift Coupon Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->get($id, [
            'contain' => ['Vendors']
        ]);

        $this->set('vendorInstantGiftCouponSetting', $vendorInstantGiftCouponSetting);
        $this->set('_serialize', ['vendorInstantGiftCouponSetting']);
    }

    /**
     * Add method
     *
     * @param boolean|null $allowForAdd 
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($allowForAdd = null)
    {
        //Case 1- Super admin logs in: he can add a setting for any vendor
        //Case 2- A vendor logs in and already has a setting: redirect to edit
        //Case 3- A vendor logs in and has no settings: allow add
        //Case 4- Super admin tries to store a duplicate setting for a vendor

        $loggedInUser = $this->Auth->user();
        //if logged in user is superadmin or allowForAdd is false, redirect to index, i.e., the user is not allowed to add page.
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL && !$allowForAdd){
          return $this->redirect(['action' => 'index']);
        }else{
          //else, if a setting already exists, redirect to edit page
            $instantGcSetting = $this->VendorInstantGiftCouponSettings->findByVendorId($loggedInUser['vendor_id'])->first();
            
            if($instantGcSetting){
                return $this->redirect(['action' => 'edit', $instantGcSetting->id]);
            }
        }

        //if the user is allowed to add a setting and the vendor hasn't already got a setting configured, create new.
        $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->newEntity(); 

        // $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->newEntity();
        if ($this->request->is('post')) {

            if($loggedInUser['role']->name != self::SUPER_ADMIN_LABEL){
              $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
            }else{
              $instantGcSetting = $this->VendorInstantGiftCouponSettings->findByVendorId($this->request->data['vendor_id'])->first(); 
              if($instantGcSetting){
                $this->Flash->error(__("DUPLICATE_ENTRY", 'Vendor Instant Gift Coupon Setting', 'vendor'));
              }
            }

            $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->patchEntity($vendorInstantGiftCouponSetting, $this->request->data);
            // pr($this->request->data); die;
            if($vendorInstantGiftCouponSetting->errors()){
                throw new InternalServerError(__(json_encode($vendorInstantGiftCouponSetting->errors())));
            }

            if ($this->VendorInstantGiftCouponSettings->save($vendorInstantGiftCouponSetting)) {
                $this->Flash->success(__('The vendor instant gift coupon setting has been saved.'));
                return $this->redirect(['action' => 'edit',$vendorInstantGiftCouponSetting->id]);
            }
            $this->Flash->error(__('The vendor instant gift coupon setting could not be saved. Please, try again.'));
        }
        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $vendors = $this->VendorInstantGiftCouponSettings->Vendors->find('list', ['limit' => 200]);
        }
        $this->set(compact('vendorInstantGiftCouponSetting', 'vendors', 'loggedInUser'));
        $this->set('_serialize', ['vendorInstantGiftCouponSetting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Instant Gift Coupon Setting id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loggedInUser = $this->Auth->user();
        $this->loadModel('GiftCoupons');
        $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->patchEntity($vendorInstantGiftCouponSetting, $this->request->data);
            if ($this->VendorInstantGiftCouponSettings->save($vendorInstantGiftCouponSetting)) {
                $this->Flash->success(__('The vendor instant gift coupon setting has been saved.'));

                return $this->redirect(['action' => 'edit',$vendorInstantGiftCouponSetting->id]);
            }
            $this->Flash->error(__('The vendor instant gift coupon setting could not be saved. Please, try again.'));
        }
        $giftCoupons = $this->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->where(['gift_coupon_type_id =' => 2])->contain(['Vendors'])->all();
        $vendors = $this->VendorInstantGiftCouponSettings->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('vendorInstantGiftCouponSetting', 'vendors','giftCoupons', 'loggedInUser'));
        $this->set('_serialize', ['vendorInstantGiftCouponSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Instant Gift Coupon Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorInstantGiftCouponSetting = $this->VendorInstantGiftCouponSettings->get($id);
        if ($this->VendorInstantGiftCouponSettings->delete($vendorInstantGiftCouponSetting)) {
            $this->Flash->success(__('The vendor instant gift coupon setting has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor instant gift coupon setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
    *Gift Coupons Method
    *
    *Renders UI for the instant rewards available to a patient for redemption through the link sent via email/sms.
    *
    */
    public function giftCoupons(){
        //starting with the assumption that the instant rewards have not expired yet.
        $isExpired = false;
        // pr($this->request->query);die;
        //find the entry of the patient's visit corresponding to the uuid and id recieved.
        $this->loadModel('PatientVisitSpendings');
        $ptVisitSpending = $this->PatientVisitSpendings->find()
                                                       ->where([
                                                                'uuid' => $this->request->query['key'], 
                                                                'id' => $this->request->query['id']
                                                              ])
                                                       ->first();
        // pr($ptVisitEntry); die;           
        //if no entry is found, throw exception
        if(!$ptVisitSpending){
          throw new BadRequestException(__('RECORD_NOT_FOUND'));
        }                                           
        $vendorId = $ptVisitSpending->vendor_id;
        //find vendor setting for the vendor.
        $this->loadModel('VendorInstantGiftCouponSettings');
        $instantGcSetting = $this->VendorInstantGiftCouponSettings->findByVendorId($vendorId)
                                                                  ->first();

        //if no setting exists for the vendor, set isExpired as true.
        if(!$instantGcSetting){
          $isExpired = true;
        }

        //Fetch the time when the patient had unlocked instant rewards
        $unlockTime = new Time($ptVisitSpending->unlock_time);
        $currentTimestamp = new Time();
        //if the rewards were unlocked before redemption_Expiry duration from current time, then they  have expired.
        if(!$unlockTime->wasWithinLast($instantGcSetting->redemption_expiry.' hours')){
          $isExpired = true;
        }else{
          //else, add the redemption expiry duration to unlock time to get  the time when the rewards will expire.
          $unlockTime->modify('+'.$instantGcSetting->redemption_expiry.' hours');
          $expiryDate = strtotime($unlockTime);
          // $timeRemaining = $modifiedTime->diff($currentTimestamp);
          $this->set('expiryDate', $expiryDate);
        }

        //fetch all the instant rewards(gift_coupons of type 2) that have been created by the vendor.
        $this->loadModel('GiftCoupons');
        $giftCoupons = $this->GiftCoupons->findByVendorId($vendorId)->where(['gift_coupon_type_id' => 2])->all();
       
        
        
        $this->viewBuilder()
            ->layout('review-form')
            ->template('giftCoupons');

        $this->set(compact('giftCoupons', 'isExpired', 'ptVisitSpending'));
        // $this->set($giftCoupons, 'giftCoupons');
        // $this->set('_serialize', ['giftCoupons']);
    }

   
}
