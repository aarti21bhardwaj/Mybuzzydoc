<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmailSettings Controller
 *
 * @property \App\Model\Table\EmailSettingsTable $EmailSettings
 */
class EmailSettingsController extends AppController
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
        $emailSettings = $this->EmailSettings->find()->contain(['EmailLayouts', 'EmailTemplates', 'Events'])->all();

        $this->set(compact('emailSettings'));
        $this->set('_serialize', ['emailSettings']);
    }

    /**
     * View method
     *
     * @param string|null $id Email Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emailSetting = $this->EmailSettings->get($id, [
            'contain' => ['EmailLayouts', 'EmailTemplates', 'Events']
        ]);

        $this->set('emailSetting', $emailSetting);
        $this->set('_serialize', ['emailSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $emailSetting = $this->EmailSettings->newEntity();
        if ($this->request->is('post')) {
            $emailSetting = $this->EmailSettings->patchEntity($emailSetting, $this->request->data);
            // pr($emailSetting); die;
            if ($this->EmailSettings->save($emailSetting)) {
                $this->Flash->success(__('ENTITY_SAVED','email setting'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR','email setting'));
            }
        }
        $events = $this->EmailSettings->Events->find('list');
        $emailLayouts = $this->EmailSettings->EmailLayouts->find('list', ['limit' => 200]);
        $emailTemplates = $this->EmailSettings->EmailTemplates->find('list', ['limit' => 200]);
        $this->set(compact('emailSetting', 'emailLayouts', 'emailTemplates', 'events'));
        $this->set('_serialize', ['emailSetting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Email Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $emailSetting = $this->EmailSettings->get($id, [
            'contain' => ['Events.EventVariables']
        ]);
        
        if ($this->request->is(['post', 'put'])) {
            $emailSetting = $this->EmailSettings->patchEntity($emailSetting, $this->request->data);
            if ($this->EmailSettings->save($emailSetting)) {
                $this->Flash->success(__('ENTITY_SAVED','email setting'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR','email setting'));
            }
        }
        $events = $this->EmailSettings->Events->find('list');
        $emailLayouts = $this->EmailSettings->EmailLayouts->find('list', ['limit' => 200]);
        $emailTemplates = $this->EmailSettings->EmailTemplates->find('list', ['limit' => 200]);
        $this->set(compact('emailSetting', 'emailLayouts', 'emailTemplates', 'events'));
        $this->set('_serialize', ['emailSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Email Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    /*Email Settings Cannot be deleted because if vendor email settings are not present then these settings act as a fallback*/
    
    // public function delete($id = null)
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $emailSetting = $this->EmailSettings->get($id);
    //     if ($this->EmailSettings->delete($emailSetting)) {
    //         $this->Flash->success(__('ENTITY_DELETED','email setting'));
    //     } else {
    //         $this->Flash->error(__('ENTITY_DELETED_ERROR','email setting'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }

    //  public function isAuthorized($user)
    // {

    //     if ($user['role']->name === 'admin') {
    //         return true;
    //       }
    //       else{
    //         return false;
    //       }
    //     return parent::isAuthorized($user);
       
    // }

}
