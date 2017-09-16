<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Features Controller
 *
 * @property \App\Model\Table\FeaturesTable $Features
 */
class FeaturesController extends AppController
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
        $features = $this->paginate($this->Features);

        $this->set(compact('features'));
        $this->set('_serialize', ['features']);
    }

    /**
     * View method
     *
     * @param string|null $id Feature id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $feature = $this->Features->get($id, [
            'contain' => ['PlanFeatures']
        ]);

        $this->set('feature', $feature);
        $this->set('_serialize', ['feature']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $feature = $this->Features->newEntity();
        if ($this->request->is('post')) {
            $feature = $this->Features->patchEntity($feature, $this->request->data);
            if ($this->Features->save($feature)) {
                $this->Flash->success(__('ENTITY_SAVED', 'feature'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'feature'));
            }
        }
        $this->set(compact('feature'));
        $this->set('_serialize', ['feature']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Feature id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $feature = $this->Features->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $feature = $this->Features->patchEntity($feature, $this->request->data);
            if ($this->Features->save($feature)) {
                $this->Flash->success(__('ENTITY_SAVED', 'feature'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'feature'));
            }
        }
        $this->set(compact('feature'));
        $this->set('_serialize', ['feature']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Feature id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $feature = $this->Features->get($id);
        if ($this->Features->delete($feature)) {
            $this->Flash->success(__('ENTITY_DELETED', 'feature'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'feature'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
