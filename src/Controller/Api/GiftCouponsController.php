<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\UnauthorizedException;

/**
 * GiftCoupons Controller
 *
 * @property \App\Model\Table\GiftCouponsTable $GiftCoupons
 */
class GiftCouponsController extends ApiController
{

    public function initialize(){
        parent::initialize();
        // $this->Auth->config('authorize', ['Controller']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function getVendorsCoupons($gcTypeId = null, $vendorId = null)
    {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$gcTypeId){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'gift coupon type id'));
        }
        if(!in_array($gcTypeId, [1, 2])){
            throw new BadRequestException(__('INVALID_VALUE', 'gift coupon type id'));
        }
        
        if(!$vendorId){
            $vendorId = $this->Auth->user('vendor_id');
        }


        $giftCoupons = $this->GiftCoupons->findByVendorId($vendorId)->where(['gift_coupon_type_id' => $gcTypeId])->all();

        $response = ['message' => 'gift coupons for vendor id '.$vendorId.' have been retrieved.', 'giftCoupons' => $giftCoupons];
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }


    public function redemption(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!$this->request->data['gift_coupon_award_id']){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'gift coupon award id'));
        }

        $this->loadModel('GiftCouponAwards');
        $gcAward = $this->GiftCouponAwards->findById($this->request->data['gift_coupon_award_id'])->first();

        if(!$gcAward){
            throw new NotFoundException(__('RECORD_NOT_FOUND'));
        }else if(!$gcAward->status){
            throw new BadRequestException(__('the gift coupon is not valid anymore.'));
        }

        $gcRedemptionData = ['gift_coupon_award_id' => $this->request->data['gift_coupon_award_id'], 'redemption_status' => 1    /*corresponds to the status: redeemed*/];
        $this->loadModel('GiftCouponRedemptions');
        $gcRedemption = $this->GiftCouponRedemptions->newEntity($gcRedemptionData);
        // pr($gcRedemption); die;
        $gcRedemption = $this->GiftCouponRedemptions->save($gcRedemption);
        if($gcRedemption){
            //update end_time for gift_coupon_award_id received.
            $deactivateGc = ['status' => 0];
            $expiredGcAward = $this->GiftCouponAwards->patchEntity($gcAward, $deactivateGc);

            if($this->GiftCouponAwards->save($expiredGcAward)){
                $this->set('response', ['status' => 'OK', 'data' => ['id' => $gcRedemption->id]]);
                $this->set('_serialize', ['response']);
            }else{
                throw new InternalErrorException(__('ENTITY_ERROR', 'gift coupon award'));
            }
        }
        else{
            throw new InternalErrorException(__('ENTITY_ERROR', 'gift coupon redemption'));
        }
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
    public function add()
    {
        $giftCoupon = $this->GiftCoupons->newEntity();
        if ($this->request->is('post')) {
            $giftCoupon = $this->GiftCoupons->patchEntity($giftCoupon, $this->request->data);
            if ($this->GiftCoupons->save($giftCoupon)) {
                $this->Flash->success(__('The gift coupon has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gift coupon could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->GiftCoupons->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('giftCoupon', 'vendors'));
        $this->set('_serialize', ['giftCoupon']);
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

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gift coupon could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->GiftCoupons->Vendors->find('list', ['limit' => 200]);
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
        $this->loadModel('MilestoneLevelRewards');

        $response = ['status' => FALSE, 'message' => NULL];
        if ($this->GiftCoupons->delete($giftCoupon)){
            if($this->MilestoneLevelRewards->deleteAll(['reward_type_id' => 2, 'reward_id' => $id])){
                $response = ['status' => TRUE, 'message' => (__('ENTITY_DELETED', 'gift coupon'))];
            }
        } else {
            throw new InternalErrorException(__('ENTITY_DELETED_ERROR', 'gift coupon'));
        }
        $this->set('response', $response);
        $this->set('_serialize', 'response');
    }

    public function checkForMilestoneRewards($id = null){
        $this->loadModel('MilestoneLevelRewards');
        $milestoneRewards = $this->MilestoneLevelRewards->findByRewardId($id)->where(['reward_type_id' => 2])->all();
        $message = NULL;
        if($milestoneRewards){
            $message = "If you delete this gift coupon, one or more levels in your milestone program may get orphaned.";
        }

        $this->set('response', $message);
        $this->set('_serialize', ['response']);
    }

}
