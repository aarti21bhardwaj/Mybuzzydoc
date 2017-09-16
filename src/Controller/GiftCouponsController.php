<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GiftCoupons Controller
 *
 * @property \App\Model\Table\GiftCouponsTable $GiftCoupons
 */
class GiftCouponsController extends AppController
{

    const SUPER_ADMIN_LABEL = 'admin';

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
        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $giftCoupons = $this->GiftCoupons->find()->contain(['Vendors'])->all();
        }else{
            $giftCoupons = $this->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->where(['gift_coupon_type_id'=>1])->contain(['Vendors'])->all();
        }

        $this->set(compact('giftCoupons'));
        $this->set('_serialize', ['giftCoupons']);
    }

    /**
     * View method
     *
     * @param string|null $id Gift Coupon id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $giftCoupon = $this->GiftCoupons->get($id, [
            'contain' => ['Vendors', 'GiftCouponAwards']
        ]);

        $this->set('giftCoupon', $giftCoupon);
        $this->set('_serialize', ['giftCoupon']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($giftCouponType = null)
    {
        if(!isset($giftCouponType)){
                $giftCouponType = 'standard';
            }

        $giftCoupon = $this->GiftCoupons->newEntity();
        if ($this->request->is('post')) {

            if(!isset($this->request->data['vendor_id']))
                $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
                $giftCouponType = $this->GiftCoupons->GiftCouponTypes->findByName($giftCouponType)->first();
                $this->request->data['gift_coupon_type_id'] = $giftCouponType->id;

            $giftCoupon = $this->GiftCoupons->patchEntity($giftCoupon, $this->request->data);
            if($giftCoupon->errors()){
                throw new InternalServerError(__(json_encode($giftCoupon->errors())));
            }
                
            $saved = $this->GiftCoupons->save($giftCoupon);
            if ($this->GiftCoupons->save($giftCoupon)) {
                $this->Flash->success(__('The gift coupon has been saved.'));
                if($giftCouponType->name == 'standard'){
                return $this->redirect(['action' => 'index']);
                } else {
                return $this->redirect(['controller'=>'VendorInstantGiftCouponSettings','action' => 'add',1]); 
                }
            } else {
                $this->Flash->error(__('The gift coupon could not be saved. Please, try again.'));
            }
        }

        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $vendors = $this->GiftCoupons->Vendors->find('list', ['limit' => 200]);
        }
        $this->set(compact('giftCoupon', 'vendors', 'giftCouponType'));
        $this->set('_serialize', ['giftCoupon']);
        $this->set('giftCoupon', $giftCoupon);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gift Coupon id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $giftCoupon = $this->GiftCoupons->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $giftCoupon = $this->GiftCoupons->patchEntity($giftCoupon, $this->request->data);
            if ($this->GiftCoupons->save($giftCoupon)) {
                $this->Flash->success(__('The gift coupon has been saved.'));
                if($giftCoupon->gift_coupon_type_id == 1){
                    return $this->redirect(['action' => 'index']);
                }elseif($giftCoupon->gift_coupon_type_id == 2){
                    $this->loadModel('VendorInstantGiftCouponSettings');
                    $instantGcSettings = $this->VendorInstantGiftCouponSettings->findByVendorId($this->Auth->user('vendor_id'))->first();
                    return $this->redirect(['controller' => 'VendorInstantGiftCouponSettings', 'action' => 'edit', $instantGcSettings->id]);
                }
            } else {
                $this->Flash->error(__('The gift coupon could not be saved. Please, try again.'));
            }
        }
        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $vendors = $this->GiftCoupons->Vendors->find('list', ['limit' => 200]);
        }
        $this->set(compact('giftCoupon', 'vendors'));
        $this->set('_serialize', ['giftCoupon']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gift Coupon id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $giftCoupon = $this->GiftCoupons->get($id);
        if ($this->GiftCoupons->delete($giftCoupon)) {
            $this->Flash->success(__('The gift coupon has been deleted.'));
        } else {
            $this->Flash->error(__('The gift coupon could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //  public function isAuthorized($user){
     
    //     if(in_array($user['role']['label'], ['Admin']))
    // }

}