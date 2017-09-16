<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LegacyRedemptionStatuses Controller
 *
 * @property \App\Model\Table\LegacyRedemptionStatusesTable $LegacyRedemptionStatuses
 */
class LegacyRedemptionStatusesController extends AppController
{   

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
        $legacyRedemptionStatuses = $this->paginate($this->LegacyRedemptionStatuses);

        $this->set(compact('legacyRedemptionStatuses'));
        $this->set('_serialize', ['legacyRedemptionStatuses']);
    }

    /**
     * View method
     *
     * @param string|null $id Legacy Redemption Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->find('all')->where(['LegacyRedemptionStatuses.id'=> $id])->contain(['LegacyRedemptions'])->first();
        if($legacyRedemptionStatus)
        { 
            $this->set('legacyRedemptionStatus', $legacyRedemptionStatus);
            $this->set('_serialize', ['legacyRedemptionStatus']);
        }else{
            $this->Flash->error('BAD_REQUEST');
            return $this->redirect(['action'=>'index']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->newEntity();
        if ($this->request->is('post')) {
            $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->patchEntity($legacyRedemptionStatus, $this->request->data);
            if($legacyRedemptionStatus->errors()){
                $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
            }
            if ($this->LegacyRedemptionStatuses->save($legacyRedemptionStatus)) {
                $this->Flash->success(__('ENTITY_SAVED','legacyRedemptionStatus'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'legacyRedemptionStatus'));
            }
        }
        $this->set(compact('legacyRedemptionStatus'));
        $this->set('_serialize', ['legacyRedemptionStatus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Legacy Redemption Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->find('all')->where(['LegacyRedemptionStatuses.id'=> $id])->first();
        if(!$legacyRedemptionStatus)
        { 
            $this->Flash->error("Bad Request");
            return $this->redirect(['action'=>'index']);
        }
        if ($this->request->is(['post', 'put'])) {
            $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->patchEntity($legacyRedemptionStatus, $this->request->data);
            
             if($legacyRedemptionStatus->errors()){
                $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
            }
            
            if ($this->LegacyRedemptionStatuses->save($legacyRedemptionStatus)) {
                $this->Flash->success(__('ENTITY_SAVED','legacyRedemptionStatus'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'legacyRedemptionStatus'));
            }
        }
        $this->set(compact('legacyRedemptionStatus'));
        $this->set('_serialize', ['legacyRedemptionStatus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Legacy Redemption Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $legacyRedemptionStatus = $this->LegacyRedemptionStatuses->find('all')->where(['LegacyRedemptionStatuses.id'=> $id])->contain(['LegacyRedemptions'])->first();
        if($legacyRedemptionStatus)
        { 
            $this->set('legacyRedemptionStatus', $legacyRedemptionStatus);
            $this->set('_serialize', ['legacyRedemptionStatus']);
        }else{
            $this->Flash->error('BAD_REQUEST');
            return $this->redirect(['action'=>'index']);
        }
        if ($this->LegacyRedemptionStatuses->delete($legacyRedemptionStatus)) {
            $this->Flash->success(__('ENTITY_DELETED', 'legacyRedemptionStatus'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'legacyRedemptionStatus'));
        }

        return $this->redirect(['action' => 'index']);
    }

}