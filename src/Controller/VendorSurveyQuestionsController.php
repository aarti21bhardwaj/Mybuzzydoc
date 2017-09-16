<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorSurveyQuestions Controller
 *
 * @property \App\Model\Table\VendorSurveyQuestionsTable $VendorSurveyQuestions
 */
class VendorSurveyQuestionsController extends AppController
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

        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            // $this->paginate = [
            // 'contain' => ['VendorSurveys', 'SurveyQuestions.Questions']      
            // ];
           $vendorSurveyQuestions = $this->VendorSurveyQuestions->find()->contain(['VendorSurveys.Vendors', 'SurveyQuestions.Questions'])->all();
        } else{
        //      $this->paginate = [
        //     'contain' => ['VendorSurveys', 'SurveyQuestions.Questions'],
        //     'conditions' => ['VendorSurveys.vendor_id =' => $this->Auth->user('vendor_id'), $this->_vendor]

        // ];
        $vendorSurveyQuestions = $this->VendorSurveyQuestions->find()->contain(['VendorSurveys.Vendors', 'SurveyQuestions.Questions'])->where(['VendorSurveys.vendor_id =' => $this->Auth->user('vendor_id'), $this->_vendor])->all();

        }
        // $vendorSurveyQuestions = $this->paginate($this->VendorSurveyQuestions);

        $this->set(compact('vendorSurveyQuestions'));
        $this->set('_serialize', ['vendorSurveyQuestions']);
        $this->set('loggedInUser', $loggedInUser);
    }
    /**
     * View method
     *
     * @param string|null $id Vendor Survey Question id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->get($id, [
            'contain' => ['VendorSurveys', 'SurveyQuestions', 'SurveyInstanceResponses']
        ]);

        $this->set('vendorSurveyQuestion', $vendorSurveyQuestion);
        $this->set('_serialize', ['vendorSurveyQuestion']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->newEntity();
        if ($this->request->is('post')) {
            $vendorSurveyQuestion = $this->VendorSurveyQuestions->patchEntity($vendorSurveyQuestion, $this->request->data);
            if ($this->VendorSurveyQuestions->save($vendorSurveyQuestion)) {
                $this->Flash->success(__('The vendor survey question has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor survey question could not be saved. Please, try again.'));
            }
        }
        $vendorSurveys = $this->VendorSurveyQuestions->VendorSurveys->find('list', ['limit' => 200]);
        $surveyQuestions = $this->VendorSurveyQuestions->SurveyQuestions->find('list', ['limit' => 200]);
        $this->set(compact('vendorSurveyQuestion', 'vendorSurveys', 'surveyQuestions'));
        $this->set('_serialize', ['vendorSurveyQuestion']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Survey Question id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['put'])) {
            $vendorSurveyQuestion = $this->VendorSurveyQuestions->patchEntity($vendorSurveyQuestion, $this->request->data);
            if ($this->VendorSurveyQuestions->save($vendorSurveyQuestion)) {
                $this->Flash->success(__('The vendor survey question has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor survey question could not be saved. Please, try again.'));
            }
        }
        $vendorSurveys = $this->VendorSurveyQuestions->VendorSurveys->find('list', ['limit' => 200]);
        $surveyQuestions = $this->VendorSurveyQuestions->SurveyQuestions->find('list', ['limit' => 200]);
        $this->set(compact('vendorSurveyQuestion', 'vendorSurveys', 'surveyQuestions'));
        $this->set('_serialize', ['vendorSurveyQuestion']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Survey Question id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->get($id);
        if ($this->VendorSurveyQuestions->delete($vendorSurveyQuestion)) {
            $this->Flash->success(__('The vendor survey question has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor survey question could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
