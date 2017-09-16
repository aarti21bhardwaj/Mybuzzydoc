<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EventVariables Controller
 *
 * @property \App\Model\Table\EventVariablesTable $EventVariables
 */
class EventVariablesController extends AppController
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
        $this->paginate = [
            'contain' => ['Events']
        ];
        $eventVariables = $this->paginate($this->EventVariables);

        $this->set(compact('eventVariables'));
        $this->set('_serialize', ['eventVariables']);
    }

    /**
     * View method
     *
     * @param string|null $id Event Variable id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $eventVariable = $this->EventVariables->get($id, [
            'contain' => ['Events']
        ]);

        $this->set('eventVariable', $eventVariable);
        $this->set('_serialize', ['eventVariable']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $eventVariable = $this->EventVariables->newEntity();
        if ($this->request->is('post')) {
            $eventVariable = $this->EventVariables->patchEntity($eventVariable, $this->request->data);
            if ($this->EventVariables->save($eventVariable)) {
                $this->Flash->success(__('ENTITY_SAVED', 'eventVariable'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'eventVariable'));
            }
        }
        $events = $this->EventVariables->Events->find('list', ['limit' => 200]);
        $this->set(compact('eventVariable', 'events'));
        $this->set('_serialize', ['eventVariable']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Event Variable id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $eventVariable = $this->EventVariables->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $eventVariable = $this->EventVariables->patchEntity($eventVariable, $this->request->data);
            if ($this->EventVariables->save($eventVariable)) {
                $this->Flash->success(__('ENTITY_SAVED', 'eventVariable'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'eventVariable'));
            }
        }
        $events = $this->EventVariables->Events->find('list', ['limit' => 200]);
        $this->set(compact('eventVariable', 'events'));
        $this->set('_serialize', ['eventVariable']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Event Variable id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $eventVariable = $this->EventVariables->get($id);
        if ($this->EventVariables->delete($eventVariable)) {
            $this->Flash->success(__('ENTITY_DELETED', 'eventVariable'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'eventVariable'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
