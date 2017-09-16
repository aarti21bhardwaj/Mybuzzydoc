<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReferralTiers Controller
 *
 * @property \App\Model\Table\ReferralTiersTable $referralTiers
 */
class ReferralTiersController extends AppController
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
    public function index()
    {
        // $this->paginate = [
        //     'contain' => ['Vendors'],
        //     'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id')]
        // ];
        
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $referralTiers = $this->ReferralTiers->find()->contain(['Vendors'])->where($this->_vendorCondition)->all();
        }else{
           $referralTiers = $this->ReferralTiers->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();

        }
        
        $this->set(compact('referralTiers'));
        $this->set('_serialize', ['referralTiers']);
    }

    /**
     * View method
     *
     * @param string|null $id ReferralTier id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referralTier = $this->ReferralTiers->findById($id)
                            ->contain(['ReferralTierGiftCoupons.GiftCoupons'])
                            ->first();

        if(!$referralTier)
            $referralTier = $this->ReferralTiers->findById($id)
                                ->first();



        $this->set('referralTier', $referralTier);
        $this->set('_serialize', ['referralTier']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $referralTier = $this->ReferralTiers->newEntity();
        if ($this->request->is('post')) {
        
            if(!$this->request->data['referral_tier_gift_coupon']['gift_coupon_id']){
                unset($this->request->data['referral_tier_gift_coupon']);
                $referralTier = $this->ReferralTiers->patchEntity($referralTier, $this->request->data);
            
            }
            else{

                $referralTier = $this->ReferralTiers->patchEntity($referralTier, $this->request->data, ['associated' => 'ReferralTierGiftCoupons']); 
            }
            if ($this->ReferralTiers->save($referralTier)) {
                $this->Flash->success(__('The referral tier has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {

                $this->Flash->error(__('The referral tier could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->ReferralTiers->Vendors->find('list', ['limit' => 200]);
        $loggedInUser = $this->Auth->user();
        $giftCoupons = $this->ReferralTiers->ReferralTierGiftCoupons->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'description');

        $this->set(compact('referralTier', 'vendors', 'loggedInUser', 'lastTier', 'giftCoupons'));
        $this->set('_serialize', ['referralTier']);
    }

    /**
     * Edit method
     *
     * @param string|null $id ReferralTier id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referralTier = $this->ReferralTiers->findById($id)->contain(['ReferralTierGiftCoupons'])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {

            if($referralTier->referral_tier_gift_coupon)
                $this->ReferralTiers->ReferralTierGiftCoupons->delete($referralTier->referral_tier_gift_coupon);

            if(!$this->request->data['referral_tier_gift_coupon']['gift_coupon_id']){
                unset($this->request->data['referral_tier_gift_coupon']);
                $referralTier = $this->ReferralTiers->patchEntity($referralTier, $this->request->data);
            
            }else{

                $referralTier = $this->ReferralTiers->patchEntity($referralTier, $this->request->data, ['associated' => 'ReferralTierGiftCoupons']); 
            }


                    
            $referralTier = $this->ReferralTiers->patchEntity($referralTier, $this->request->data, ['associated' => 'ReferralTierGiftCoupons']);
            if ($this->ReferralTiers->save($referralTier)) {
                $this->Flash->success(__('The referral tier has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The referral tier could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->ReferralTiers->Vendors->find('list', ['limit' => 200]);
        $loggedInUser = $this->Auth->user();
        $referralTier->multiplier *= 100;
        $giftCoupons = $this->ReferralTiers->ReferralTierGiftCoupons->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'description');
        $this->set(compact('referralTier', 'vendors', 'loggedInUser', 'giftCoupons'));
        $this->set('_serialize', ['referralTier']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Tier id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referralTier = $this->ReferralTiers->get($id);
        if ($this->ReferralTiers->delete($referralTier)) {
            $this->Flash->success(__('The referral tier has been deleted.'));
        } else {
            $this->Flash->error(__('The referral tier could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
