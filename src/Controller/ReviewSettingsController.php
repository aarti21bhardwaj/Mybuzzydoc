<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReviewSettings Controller
 *
 * @property \App\Model\Table\ReviewSettingsTable $ReviewSettings
 */
class ReviewSettingsController extends AppController
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
            'contain' => ['Vendors']
        ];
        $reviewSettings = $this->paginate($this->ReviewSettings);

        $this->set(compact('reviewSettings'));
        $this->set('_serialize', ['reviewSettings']);
    }

    /**
     * View method
     *
     * @param string|null $id Review Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reviewSetting = $this->ReviewSettings->get($id, [
            'contain' => ['Vendors']
        ]);

        $this->set('reviewSetting', $reviewSetting);
        $this->set('_serialize', ['reviewSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reviewSetting = $this->ReviewSettings->newEntity();
        if ($this->request->is('post')) {
            $reviewSetting = $this->ReviewSettings->patchEntity($reviewSetting, $this->request->data);
            if ($this->ReviewSettings->save($reviewSetting)) {
                $this->Flash->success(__('The review setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The review setting could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->ReviewSettings->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('reviewSetting', 'vendors'));
        $this->set('_serialize', ['reviewSetting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Review Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reviewSetting = $this->ReviewSettings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reviewSetting = $this->ReviewSettings->patchEntity($reviewSetting, $this->request->data);
            if ($this->ReviewSettings->save($reviewSetting)) {
                $this->Flash->success(__('The review setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The review setting could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->ReviewSettings->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('reviewSetting', 'vendors'));
        $this->set('_serialize', ['reviewSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Review Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reviewSetting = $this->ReviewSettings->get($id);
        if ($this->ReviewSettings->delete($reviewSetting)) {
            $this->Flash->success(__('The review setting has been deleted.'));
        } else {
            $this->Flash->error(__('The review setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
