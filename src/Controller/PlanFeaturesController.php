<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * PlanFeatures Controller
 *
 * @property \App\Model\Table\PlanFeaturesTable $PlanFeatures
 */
class PlanFeaturesController extends AppController
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
            'contain' => ['Plans', 'Features']
        ];
        $planFeatures = $this->paginate($this->PlanFeatures);

        $this->set(compact('planFeatures'));
        $this->set('_serialize', ['planFeatures']);
    }

    /**
     * View method
     *
     * @param string|null $id Plan Feature id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $planFeature = $this->PlanFeatures->get($id, [
        //     'contain' => ['Plans', 'Features']
        // ]);
        $planFeature = $this->PlanFeatures->find('all')->where(['PlanFeatures.id'=>$id])->contain(['Plans', 'Features'])->first();
        if(!$planFeature){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan feature'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('planFeature', $planFeature);
        $this->set('_serialize', ['planFeature']);
    }


  
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
        $featureId = $this->request->data['feature_id'];
            $collection = new Collection($featureId);
            $new = $collection->map(function($value, $key){
                return ['plan_id'=> $this->request->data['plan_id'], 'feature_id' => $value];
            });
            $new = $new->toArray();
            $planFeature = $this->PlanFeatures->newEntities($new);
            $planFeature = $this->PlanFeatures->patchEntities($planFeature, $new);
           
            if ($this->PlanFeatures->saveMany($planFeature)) {
                // $this->Flash->success(__('The plan feature has been saved.'));
                $this->Flash->success(__('ENTITY_SAVED', 'plan feature'));


            } else {
                // $this->Flash->error(__('The plan feature could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'plan feature'));
            }
            return $this->redirect(['action' => 'index']);
        }
        $plans = $this->PlanFeatures->Plans->find('list', ['limit' => 200]);
        $features = $this->PlanFeatures->Features->find('list', ['limit' => 200]);
        $this->set(compact('planFeature', 'plans', 'features'));
        $this->set('_serialize', ['planFeature']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Plan Feature id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $planFeature = $this->PlanFeatures->find('all')->where(['id'=>$id])->first();
         if(!$planFeature){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan feature'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $planFeature = $this->PlanFeatures->patchEntity($planFeature, $this->request->data);

            if ($this->PlanFeatures->save($planFeature)) {
                // $this->Flash->success(__('The plan feature has been saved.'));
                $this->Flash->success(__('ENTITY_SAVED', 'plan feature'));

            } else {
                // $this->Flash->error(__('The plan feature could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'plan feature'));
            }
            return $this->redirect(['action' => 'index']);
        }
        $plans = $this->PlanFeatures->Plans->find('list', ['limit' => 200]);
        $features = $this->PlanFeatures->Features->find('list', ['limit' => 200]);
        $this->set(compact('planFeature', 'plans', 'features'));
        $this->set('_serialize', ['planFeature']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Plan Feature id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        // $planFeature = $this->PlanFeatures->get($id);
        $planFeature = $this->PlanFeatures->find('all')->where(['id'=>$id])->first();
        if(!$planFeature){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan feature'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->PlanFeatures->delete($planFeature)) {
            // $this->Flash->success(__('The plan feature has been deleted.'));
            $this->Flash->success(__('ENTITY_DELETED', 'plan feature'));
        } else {
            // $this->Flash->error(__('The plan feature could not be deleted. Please, try again.'));
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'plan feature'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
