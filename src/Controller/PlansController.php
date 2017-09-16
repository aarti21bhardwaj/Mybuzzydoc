<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Mailer\AbstractTransport;

/**
 * Plans Controller
 *
 * @property \App\Model\Table\PlansTable $Plans
 */
class PlansController extends AppController
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
        $plans = $this->paginate($this->Plans);

        $this->set(compact('plans'));
        $this->set('_serialize', ['plans']);

       

    }

    /**
     * View method
     *
     * @param string|null $id Plan id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $plan = $this->Plans->get($id, [
        //     'contain' => ['PlanFeatures', 'VendorPlans']
        // ]);
        $plan = $this->Plans->find('all')->where(['Plans.id'=>$id])->contain(['PlanFeatures.Plans','PlanFeatures.Features'])->first();
        if(!$plan){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('plan', $plan);
        $this->set('_serialize', ['plan']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $plan = $this->Plans->newEntity();
        if ($this->request->is('post')) {
            $plan = $this->Plans->patchEntity($plan, $this->request->data);
            if ($this->Plans->save($plan)) {
                // $this->Flash->success(__('The plan has been saved.'));
                $this->Flash->success(__('ENTITY_SAVED', 'plan'));

                return $this->redirect(['action' => 'index']);
            } else {
                // $this->Flash->error(__('The plan could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'plan'));
            }
        }
        $this->set(compact('plan'));
        $this->set('_serialize', ['plan']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Plan id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // $plan = $this->Plans->get($id, [
        //     'contain' => []
        // ]);
        $plan = $this->Plans->find('all')->where(['id'=>$id])->first();
        if(!$plan){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $plan = $this->Plans->patchEntity($plan, $this->request->data);
            if ($this->Plans->save($plan)) {
                // $this->Flash->success(__('The plan has been saved.'));
                $this->Flash->success(__('ENTITY_SAVED', 'plan'));

                return $this->redirect(['action' => 'index']);
            } else {
                // $this->Flash->error(__('The plan could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'plan'));
            }
        }
        $this->set(compact('plan'));
        $this->set('_serialize', ['plan']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Plan id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        // $plan = $this->Plans->get($id);
        $plan = $this->Plans->find('all')->where(['id'=>$id])->first();
        if(!$plan){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS', 'plan'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->Plans->delete($plan)) {
            // $this->Flash->success(__('The plan has been deleted.'));
            $this->Flash->success(__('ENTITY_DELETED', 'plan'));
        } else {
            // $this->Flash->error(__('The plan could not be deleted. Please, try again.'));
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'plan'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
