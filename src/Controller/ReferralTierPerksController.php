<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReferralTierPerks Controller
 *
 * @property \App\Model\Table\ReferralTierPerksTable $ReferralTierPerks
 */
class ReferralTierPerksController extends AppController
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
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $referralTierPerks = $this->ReferralTierPerks->find()->contain(['ReferralTiers.Vendors'])->where($this->_vendorCondition)->all();
        }else{
            $referralTierPerks = $this->ReferralTierPerks->find()->contain(['ReferralTiers'])->where(['vendor_id =' => $loggedInUser['vendor_id']])->all();

        }

        $vendorId = $loggedInUser['vendor_id'];
        $this->set(compact('referralTierPerks', 'vendorId'));
        $this->set('_serialize', ['referralTierPerks']);
    }

    /**
     * View method
     *
     * @param string|null $id Referral Tier Perk id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referralTierPerk = $this->ReferralTierPerks->get($id, [
            'contain' => ['ReferralTiers']
            ]);

        $this->set('referralTierPerk', $referralTierPerk);
        $this->set('_serialize', ['referralTierPerk']);
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
        $this->set('_serialize', ['referralTierPerk']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Referral Tier Perk id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referralTierId = $id;
        $vendorId = $this->Auth->user('vendor_id');
        
        $this->set(compact('referralTierId', 'vendorId'));
        $this->set('_serialize', ['referralTierPerk']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Referral Tier Perk id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referralTierPerk = $this->ReferralTierPerks->get($id);
        if ($this->ReferralTierPerks->delete($referralTierPerk)) {
            $this->Flash->success(__('The referral tier perk has been deleted.'));
        } else {
            $this->Flash->error(__('The referral tier perk could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
