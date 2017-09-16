<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorSurveys Controller
 *
 * @property \App\Model\Table\VendorSurveysTable $VendorSurveys
 */
class VendorSurveysController extends AppController
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
        $vendorSurveys = $this->VendorSurveys->find()->contain(['Vendors', 'Surveys'])->all();
        $this->set(compact('vendorSurveys'));
        $this->set('_serialize', ['vendorSurveys']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Survey id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorSurvey = $this->VendorSurveys->get($id, [
            'contain' => ['Vendors', 'Surveys', 'VendorSurveyInstances', 'VendorSurveyQuestions']
        ]);

        $this->set('vendorSurvey', $vendorSurvey);
        $this->set('_serialize', ['vendorSurvey']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorSurvey = $this->VendorSurveys->newEntity();
        if ($this->request->is('post')) {
            $surveyId = $this->request->data['survey_id'];
            $this->loadModel('SurveyQuestions');
            $surveyQuestions = $this->SurveyQuestions->findBySurveyId($surveyId)->contain(['Questions'])
                                         ->all();
            foreach ($surveyQuestions as $surveyQuestion) {
               $surveyQuestionId= $surveyQuestion->id;
               $points =$surveyQuestion->question->points;
                $vendorSurveyQuestions[] = ['survey_question_id'=>$surveyQuestionId, 'points'=>$points];
            }
            $this->request->data['vendor_survey_questions'] = $vendorSurveyQuestions;
            $vendorSurvey = $this->VendorSurveys->newEntity($this->request->data, [
                            'associated' => ['VendorSurveyQuestions']]);
            $vendorSurvey = $this->VendorSurveys->patchEntity($vendorSurvey, $this->request->data, [
                            'associated' => ['VendorSurveyQuestions']]);
            // pr($vendorSurvey); die;
            if ($this->VendorSurveys->save($vendorSurvey, [
                'associated' => ['VendorSurveyQuestions']])) {
                $this->Flash->success(__('The vendor survey has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor survey could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->VendorSurveys->Vendors->find('list', ['limit' => 200])->where(['org_name <>'=>'admin']);
        $surveys = $this->VendorSurveys->Surveys->find('list', ['limit' => 200]);
        $this->set(compact('vendorSurvey', 'vendors', 'surveys'));
        $this->set('_serialize', ['vendorSurvey']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Survey id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorSurvey = $this->VendorSurveys->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $surveyId = $this->request->data['survey_id'];
            $this->loadModel('SurveyQuestions');
            $surveyQuestions = $this->SurveyQuestions->findBySurveyId($surveyId)->contain(['Questions'])
                                         ->all();
            foreach ($surveyQuestions as $surveyQuestion) {
               $surveyQuestionId= $surveyQuestion->id;
               $points =$surveyQuestion->question->points;
                $vendorSurveyQuestions[] = ['survey_question_id'=>$surveyQuestionId, 'points'=>$points];
            }
            $this->request->data['vendor_survey_questions'] = $vendorSurveyQuestions;
            $vendorSurvey = $this->VendorSurveys->newEntity($this->request->data, [
                            'associated' => ['VendorSurveyQuestions']]);
            $vendorSurvey = $this->VendorSurveys->patchEntity($vendorSurvey, $this->request->data, [
                            'associated' => ['VendorSurveyQuestions']]);
            // pr($vendorSurvey); die;
            if ($this->VendorSurveys->save($vendorSurvey, [
                'associated' => ['VendorSurveyQuestions']])) {
                $this->Flash->success(__('The vendor survey has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor survey could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->VendorSurveys->Vendors->find('list', ['limit' => 200])->where(['org_name <>'=>'admin']);
        $surveys = $this->VendorSurveys->Surveys->find('list', ['limit' => 200]);
        $this->set(compact('vendorSurvey', 'vendors', 'surveys'));
        $this->set('_serialize', ['vendorSurvey']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Survey id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorSurvey = $this->VendorSurveys->get($id);
        if ($this->VendorSurveys->delete($vendorSurvey)) {
            $this->Flash->success(__('The vendor survey has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor survey could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

     public function isAuthorized($user){

        if(in_array($user['role']['label'], ['Admin']))
        return parent::isAuthorized($user); 
    }
}
