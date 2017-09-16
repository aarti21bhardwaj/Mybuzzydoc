<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Tiers Controller
 *
 * @property \App\Model\Table\TiersTable $Tiers
 */
class TiersController extends AppController
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
            $tiers = $this->Tiers->find()->contain(['Vendors'])->where($this->_vendorCondition)->all();
        }else{
           $tiers = $this->Tiers->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();

        }
        
        $this->set(compact('tiers'));
        $this->set('_serialize', ['tiers']);
    }

    /**
     * View method
     *
     * @param string|null $id Tier id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tier = $this->Tiers->findById($id)
                            ->contain(['TierGiftCoupons.GiftCoupons'])
                            ->first();

        if(!$tier)
            $tier = $this->Tiers->findById($id)
                                ->first();



        $this->set('tier', $tier);
        $this->set('_serialize', ['tier']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tier = $this->Tiers->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['multiplier'] /= 100 ;
            if(!$this->request->data['tier_gift_coupon']['gift_coupon_id']){
                unset($this->request->data['tier_gift_coupon']);
                $tier = $this->Tiers->patchEntity($tier, $this->request->data);
            
            }
            else{

                $tier = $this->Tiers->patchEntity($tier, $this->request->data, ['associated' => 'TierGiftCoupons']); 
            }
            if ($this->Tiers->save($tier)) {
                $this->Flash->success(__('The tier has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {

                $this->Flash->error(__('The tier could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->Tiers->Vendors->find('list', ['limit' => 200]);
        $loggedInUser = $this->Auth->user();
        $lastTier = $this->Tiers->findByVendorId($loggedInUser['vendor_id'])->last();
        $giftCoupons = $this->Tiers->TierGiftCoupons->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'description');

        $this->set(compact('tier', 'vendors', 'loggedInUser', 'lastTier', 'giftCoupons'));
        $this->set('_serialize', ['tier']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Tier id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tier = $this->Tiers->findById($id)->contain(['TierGiftCoupons'])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {

            if($tier->tier_gift_coupon)
                $this->Tiers->TierGiftCoupons->delete($tier->tier_gift_coupon);

            if(!$this->request->data['tier_gift_coupon']['gift_coupon_id']){
                unset($this->request->data['tier_gift_coupon']);
                $tier = $this->Tiers->patchEntity($tier, $this->request->data);
            
            }else{

                $tier = $this->Tiers->patchEntity($tier, $this->request->data, ['associated' => 'TierGiftCoupons']); 
            }


            $this->request->data['multiplier'] /= 100 ;        
                $tier = $this->Tiers->patchEntity($tier, $this->request->data, ['associated' => 'TierGiftCoupons']);
            if ($this->Tiers->save($tier)) {
                $this->Flash->success(__('The tier has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The tier could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->Tiers->Vendors->find('list', ['limit' => 200]);
        $loggedInUser = $this->Auth->user();
        $tier->multiplier *= 100;
        $giftCoupons = $this->Tiers->TierGiftCoupons->GiftCoupons->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'description');

        $this->set(compact('tier', 'vendors', 'loggedInUser', 'giftCoupons'));
        $this->set('_serialize', ['tier']);
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
        $tier = $this->Tiers->get($id);
        if ($this->Tiers->delete($tier)) {
            $this->Flash->success(__('The tier has been deleted.'));
        } else {
            $this->Flash->error(__('The tier could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
