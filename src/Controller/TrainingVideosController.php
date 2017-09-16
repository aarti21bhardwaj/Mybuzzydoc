<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TrainingVideos Controller
 *
 * @property \App\Model\Table\TrainingVideosTable $TrainingVideos
 */
class TrainingVideosController extends AppController
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
        $loggedInUser = $this->Auth->user();

        $trainingVideos = $this->TrainingVideos->find()->all();


        $this->set(compact('trainingVideos'));
        $this->set('_serialize', ['trainingVideos']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * View method
     *
     * @param string|null $id Training Video id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $trainingVideo = $this->TrainingVideos->get($id, [
            'contain' => []
        ]);

        $this->set('trainingVideo', $trainingVideo);
        $this->set('_serialize', ['trainingVideo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $trainingVideo = $this->TrainingVideos->newEntity();
        if ($this->request->is('post')) {
            $trainingVideo = $this->TrainingVideos->patchEntity($trainingVideo, $this->request->data);
            if ($this->TrainingVideos->save($trainingVideo)) {
                $this->Flash->success(__('The training video has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The training video could not be saved. Please, try again.'));
        }
        $this->set(compact('trainingVideo'));
        $this->set('_serialize', ['trainingVideo']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Training Video id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $trainingVideo = $this->TrainingVideos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $trainingVideo = $this->TrainingVideos->patchEntity($trainingVideo, $this->request->data);
            if ($this->TrainingVideos->save($trainingVideo)) {
                $this->Flash->success(__('The training video has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The training video could not be saved. Please, try again.'));
        }
        $this->set(compact('trainingVideo'));
        $this->set('_serialize', ['trainingVideo']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Training Video id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trainingVideo = $this->TrainingVideos->get($id);
        if ($this->TrainingVideos->delete($trainingVideo)) {
            $this->Flash->success(__('The training video has been deleted.'));
        } else {
            $this->Flash->error(__('The training video could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
