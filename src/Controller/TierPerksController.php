<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TierPerks Controller
 *
 * @property \App\Model\Table\TierPerksTable $TierPerks
 */
class TierPerksController extends AppController
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
            $tierPerks = $this->TierPerks->find()->contain(['Tiers.Vendors'])->where($this->_vendorCondition)->all();
        }else{
            $tierPerks = $this->TierPerks->find()->contain(['Tiers'])->where(['vendor_id =' => $loggedInUser['vendor_id']])->all();

        }

        $vendorId = $loggedInUser['vendor_id'];
        $this->set(compact('tierPerks', 'vendorId'));
        $this->set('_serialize', ['tierPerks']);
    }

    /**
     * View method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tierPerk = $this->TierPerks->get($id, [
            'contain' => ['Tiers']
            ]);

        $this->set('tierPerk', $tierPerk);
        $this->set('_serialize', ['tierPerk']);
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
        $this->set('_serialize', ['tierPerk']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tierId = $id;
        $vendorId = $this->Auth->user('vendor_id');
        
        $this->set(compact('tierId', 'vendorId'));
        $this->set('_serialize', ['tierPerk']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tierPerk = $this->TierPerks->get($id);
        if ($this->TierPerks->delete($tierPerk)) {
            $this->Flash->success(__('The tier perk has been deleted.'));
        } else {
            $this->Flash->error(__('The tier perk could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
