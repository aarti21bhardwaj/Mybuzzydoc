<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorRedeemedPoints Controller
 *
 * @property \App\Model\Table\VendorRedeemedPointsTable $VendorRedeemedPoints
 */
class VendorRedeemedPointsController extends AppController
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
        $vendorRedeemedPoints = $this->paginate($this->VendorRedeemedPoints);

        $this->set(compact('vendorRedeemedPoints'));
        $this->set('_serialize', ['vendorRedeemedPoints']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Redeemed Point id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorRedeemedPoint = $this->VendorRedeemedPoints->get($id, [
            'contain' => []
        ]);

        $this->set('vendorRedeemedPoint', $vendorRedeemedPoint);
        $this->set('_serialize', ['vendorRedeemedPoint']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorRedeemedPoint = $this->VendorRedeemedPoints->newEntity();
        if ($this->request->is('post')) {
            $vendorRedeemedPoint = $this->VendorRedeemedPoints->patchEntity($vendorRedeemedPoint, $this->request->data);
            if ($this->VendorRedeemedPoints->save($vendorRedeemedPoint)) {
                $this->Flash->success(__('ENTITY_SAVED', 'vendor redeemed point'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'vendor redeemed point'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('vendorRedeemedPoint'));
        $this->set('_serialize', ['vendorRedeemedPoint']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Redeemed Point id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorRedeemedPoint = $this->VendorRedeemedPoints->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorRedeemedPoint = $this->VendorRedeemedPoints->patchEntity($vendorRedeemedPoint, $this->request->data);
            if ($this->VendorRedeemedPoints->save($vendorRedeemedPoint)) {
                $this->Flash->success(__('ENTITY_SAVED', 'vendor redeemed point'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'vendor redeemed point'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('vendorRedeemedPoint'));
        $this->set('_serialize', ['vendorRedeemedPoint']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Redeemed Point id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorRedeemedPoint = $this->VendorRedeemedPoints->get($id);
        if ($this->VendorRedeemedPoints->delete($vendorRedeemedPoint)) {
            $this->Flash->success(__('ENTITY_DELETED','vendor redeemed point'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','vendor redeemed point'));
        }
        return $this->redirect(['action' => 'index']);
    }
}