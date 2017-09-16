<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Templates Controller
 *
 * @property \App\Model\Table\TemplatesTable $Templates
 */
class TemplatesController extends AppController
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
        $templates = $this->Templates->find()->all();
        $this->set(compact('templates'));
        $this->set('_serialize', ['templates']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        if($id){
            $template = $this->Templates->findById($id)->first();
        }else{
            $template = $this->Templates->newEntity();
        }

        $this->loadModel('Industries');
        $industry = $this->Industries->find()
                                     ->all()
                                     ->combine('id','name')
                                     ->toArray();
        // pr($industry);die;
        // $hel = $this->Templates->find()->contain(['TemplatePlans'])->all();                             
        // pr($hel);die;
        $this->loadModel('Plans');
        $plans = $this->Plans->find()
                             ->all()
                             ->combine('id','name')
                             ->toArray();                               
        $this->loadModel('Promotions');
        $promotions = $this->Promotions->findByVendorId(1)
                                        ->all();
        $this->loadModel('Surveys');
        $surveys = $this->Surveys->find()
                                 ->all();
        $this->loadModel('GiftCoupons');
        $gift_coupons = $this->GiftCoupons->findByVendorId(1)
                                         ->all();                        
        $this->loadModel('VendorMilestones');
        $milestones = $this->VendorMilestones->findByVendorId(1)
                                             ->all();
        $this->set(compact('template', 'promotions', 'industry', 'surveys', 'plans', 'milestones', 'gift_coupons'));
        $this->set('_serialize', ['template']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Template id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $template = $this->Templates->get($id);
        if ($this->Templates->delete($template)) {
            $this->Flash->success(__('ENTITY_DELETED', 'Template'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'Template'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
