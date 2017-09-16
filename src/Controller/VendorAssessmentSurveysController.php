<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorAssessmentSurveys Controller
 *
 * @property \App\Model\Table\VendorAssessmentSurveysTable $VendorAssessmentSurveys
 */
class VendorAssessmentSurveysController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $vendorAssessmentSurveys = $this->VendorAssessmentSurveys->find()->contain(['Vendors', 'AssessmentSurveys'])->all();

        $vendors = $this->VendorAssessmentSurveys->Vendors->find('list', ['limit' => 200]);
        $assessmentSurveys = $this->VendorAssessmentSurveys->AssessmentSurveys->find('list', ['limit' => 200]);
        $this->set(compact('vendorAssessmentSurveys', 'vendors', 'assessmentSurveys'));
        $this->set('_serialize', ['vendorAssessmentSurveys']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Assessment Survey id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->get($id, [
            'contain' => ['Vendors', 'AssessmentSurveys', 'AssessmentSurveyInstances', 'VendorAssessmentSurveyQuestions']
        ]);

        $this->set('vendorAssessmentSurvey', $vendorAssessmentSurvey);
        $this->set('_serialize', ['vendorAssessmentSurvey']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'index']);        
        }

        $assessmentSurveyId = $this->request->data['assessment_survey_id'];
        
        $this->loadModel('AssessmentSurveyQuestions');
        
        $surveyQues = $this->AssessmentSurveyQuestions->findByAssessmentSurveyId($assessmentSurveyId)->select(['assessment_survey_question_id' => 'id'])->all()->map(function ($value, $key) {
                return $value->toArray();
        })->toArray();
        $this->request->data['vendor_assessment_survey_questions'] = $surveyQues;
        
        $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->newEntity();
        $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->patchEntity($vendorAssessmentSurvey, $this->request->data,  ['associated' => 'VendorAssessmentSurveyQuestions']);

        if ($this->VendorAssessmentSurveys->save($vendorAssessmentSurvey)) {
            $this->Flash->success(__('The vendor assessment survey has been saved.'));
        }else{
            
        $this->Flash->error(__('The vendor assessment survey could not be saved. Please, try again.'));
        }
            

        return $this->redirect(['action' => 'index']);
        
        // $this->set(compact('vendorAssessmentSurvey', 'vendors', 'assessmentSurveys'));
        // $this->set('_serialize', ['vendorAssessmentSurvey']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Assessment Survey id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $assessmentSurveyId = $this->request->data['assessment_survey_id'];
            $this->loadModel('AssessmentSurveyQuestions');
            $surveyQues = $this->AssessmentSurveyQuestions->findByAssessmentSurveyId($assessmentSurveyId)->select(['assessment_survey_question_id' => 'id'])->all()->map(function ($value, $key) {
                    return $value->toArray();
            })->toArray();
            $this->request->data['vendor_assessment_survey_questions'] = $surveyQues;
            $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->patchEntity($vendorAssessmentSurvey, $this->request->data,  ['associated' => 'VendorAssessmentSurveyQuestions']);
            if ($this->VendorAssessmentSurveys->save($vendorAssessmentSurvey)) {
                $this->Flash->success(__('The vendor assessment survey has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vendor assessment survey could not be saved. Please, try again.'));
        }
        $vendors = $this->VendorAssessmentSurveys->Vendors->find('list', ['limit' => 200]);
        $assessmentSurveys = $this->VendorAssessmentSurveys->AssessmentSurveys->find('list', ['limit' => 200]);
        $this->set(compact('vendorAssessmentSurvey', 'vendors', 'assessmentSurveys'));
        $this->set('_serialize', ['vendorAssessmentSurvey']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Assessment Survey id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorAssessmentSurvey = $this->VendorAssessmentSurveys->get($id);
        if ($this->VendorAssessmentSurveys->delete($vendorAssessmentSurvey)) {
            $this->Flash->success(__('The vendor assessment survey has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor assessment survey could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
