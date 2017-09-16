<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Configure;

/**
 * LegacyRedemptions Controller
 *
 * @property \App\Model\Table\LegacyRedemptionsTable $LegacyRedemptions
 */
class LegacyRedemptionsController extends AppController
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
            $legacyRedemptions = $this->LegacyRedemptions->find()->contain(['LegacyRewards', 'Vendors', 'LegacyRedemptionStatuses', 'LegacyRedemptionAmounts'])->where($this->_vendorCondition)->all()->sortBy('id', SORT_DESC);
        }else{
            $legacyRedemptions = $this->LegacyRedemptions->find()->contain(['LegacyRewards', 'LegacyRedemptionAmounts','Vendors', 'LegacyRedemptionStatuses'])->where(['LegacyRedemptions.vendor_id' => $this->Auth->user('vendor_id')])->all()->sortBy('id', SORT_DESC);
        }

        $pointsValue = (int) Configure::read('pointsValue');

        /*$this->paginate = [
        'contain' => ['LegacyRewards', 'Vendors', 'LegacyRedemptionStatuses']
        ];*/
        // $legacyRedemptions = $this->paginate($this->LegacyRedemptions);

        $redemptionStatuses = $this->LegacyRedemptions
        ->LegacyRedemptionStatuses
        ->find()
        ->all()
        ->combine('id', 'name')
        ->toArray()
        ;
        $this->set('redemptionStatuses', $redemptionStatuses);                                
        $this->set(compact('legacyRedemptions', 'pointsValue'));
        $this->set('_serialize', ['legacyRedemptions']);
    }

    /**
     * View method
     *
     * @param string|null $id Legacy Redemption id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $legacyRedemption = $this->LegacyRedemptions->find('all')->where(['LegacyRedemptions.id'=>$id])->contain(['LegacyRewards', 'Vendors', 'LegacyRedemptionStatuses'])->first();
        if($legacyRedemption){
            $this->set('legacyRedemption', $legacyRedemption);
            $this->set('_serialize', ['legacyRedemption']);
        }else{
            $this->Flash->error(__('RECORD_NOT_FOUND'));
            return $this->redirect(['action' => 'index']);
        }
        
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $legacyRedemption = $this->LegacyRedemptions->newEntity();
        if ($this->request->is('post')) {
            $legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data);
            if($legacyRedemption->errors()){
                $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
            }
            if ($this->LegacyRedemptions->save($legacyRedemption)) {

                // $this->Flash->success(__('The legacy redemption has been saved.'));
                $this->Flash->success(__('ENTITY_SAVED', 'legacy redemption'));

                return $this->redirect(['action' => 'index']);
            } else {
                // $this->Flash->error(__('The legacy redemption could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'legacy redemption'));
            }
        $legacyRewards = $this->LegacyRedemptions->LegacyRewards->find('list', ['limit' => 200]);
        $vendors = $this->LegacyRedemptions->Vendors->find('list', ['limit' => 200]);
        $legacyRedemptionStatuses = $this->LegacyRedemptions->LegacyRedemptionStatuses->find('list', ['limit' => 200]);
        $this->set(compact('legacyRedemption', 'legacyRewards', 'vendors', 'legacyRedemptionStatuses'));
        $this->set('_serialize', ['legacyRedemption']);
       }
    }

    /**
     * Edit method
     *
     * @param string|null $id Legacy Redemption id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $legacyRedemption = $this->LegacyRedemptions->find('all')->where(['id'=>$id])->first();
        if(!$legacyRedemption){
           $this->Flash->error(__('RECORD_NOT_FOUND'));
           return $this->redirect(['action' => 'index']);
        }
        if($this->request->is(['put'])){
        $legacyRedemption = $this->LegacyRedemptions->patchEntity($legacyRedemption, $this->request->data);
        if ($this->LegacyRedemptions->save($legacyRedemption)) {
            // $this->Flash->success(__('The legacy redemption has been saved.'));
            $this->Flash->success(__('ENTITY_SAVED', 'legacy redemption'));

            return $this->redirect(['action' => 'index']);
        } else {
            // $this->Flash->error(__('The legacy redemption could not be saved. Please, try again.'));
            $this->Flash->error(__('ENTITY_ERROR', 'legacy redemption'));
        }
        }
        $legacyRewards = $this->LegacyRedemptions->LegacyRewards->find('list', ['limit' => 200]);
        $vendors = $this->LegacyRedemptions->Vendors->find('list', ['limit' => 200]);
        $legacyRedemptionStatuses = $this->LegacyRedemptions->LegacyRedemptionStatuses->find('list', ['limit' => 200]);
        $this->set(compact('legacyRedemption', 'legacyRewards', 'vendors', 'legacyRedemptionStatuses'));
        $this->set('_serialize', ['legacyRedemption']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Legacy Redemption id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $legacyRedemption = $this->LegacyRedemptions->find('all')->where(['id'=>$id])->first();
        if(!$legacyRedemption){
           $this->Flash->error(__('RECORD_NOT_FOUND'));
           return $this->redirect(['action' => 'index']);
       }
       if ($this->LegacyRedemptions->delete($legacyRedemption)) {
        // $this->Flash->success(__('The legacy redemption has been deleted.'));
        $this->Flash->success(__('ENTITY_DELETED', 'legacy redemption'));
        } else {
        // $this->Flash->error(__('The legacy redemption could not be deleted. Please, try again.'));
        $this->Flash->error(__('ENTITY_DELETED_ERROR', 'legacy redemption'));
        }

        return $this->redirect(['action' => 'index']);
   }

    public function isAuthorized($user){

        if(in_array($user['role']['label'], ['Staff Admin', 'Admin', 'Staff Manager']))
        return parent::isAuthorized($user); 
    }
}