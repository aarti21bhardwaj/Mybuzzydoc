<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AssessmentSurveyQuestions Controller
 *
 * @property \App\Model\Table\AssessmentSurveyQuestionsTable $AssessmentSurveyQuestions
 */
class AssessmentSurveyQuestionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        $query = $this->request->query;

        if(!isset($query['assessment_survey_id']) || !$query['assessment_survey_id']){
            $this->Flash->error(__('The assessment survey id is required'));
            return $this->redirect(['controller'=>'AssessmentSurveys','action' => 'index']);
        };

        $assessmentSurvey = $this->AssessmentSurveyQuestions->AssessmentSurveys->findById($query['assessment_survey_id'])->first();


        if(!$assessmentSurvey){
            $this->Flash->error(__('The assessment survey does not exist'));
            return $this->redirect(['controller'=>'AssessmentSurveys','action' => 'index']);
        };

        $assessmentSurveyQuestions = $this->AssessmentSurveyQuestions->find()->where($query)->contain(['AssessmentSurveys', 'ResponseGroups'])->all();

        $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->newEntity();

        $responseGroups = $this->AssessmentSurveyQuestions->ResponseGroups->find('list', ['limit' => 200]);

        $this->set(compact('assessmentSurveyQuestions', 'assessmentSurveyQuestion', 'responseGroups', 'assessmentSurvey'));
        $this->set('_serialize', ['assessmentSurveyQuestions']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is('post')) {
            return $this->redirect($this->referer());
        }

        $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->newEntity();

        $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->patchEntity($assessmentSurveyQuestion, $this->request->data);
        
        if ($this->AssessmentSurveyQuestions->save($assessmentSurveyQuestion)) {
            $this->Flash->success(__('The assessment survey question has been saved.'));

            return $this->redirect($this->referer());
        }
        
        $this->Flash->error(__('The assessment survey question could not be saved. Please, try again.'));
        
        $this->set(compact('assessmentSurveyQuestion'));
        $this->set('_serialize', ['assessmentSurveyQuestion']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Assessment Survey Question id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->patchEntity($assessmentSurveyQuestion, $this->request->data);
            if ($this->AssessmentSurveyQuestions->save($assessmentSurveyQuestion)) {
                $this->Flash->success(__('The assessment survey question has been saved.'));

                return $this->redirect(['action' => 'index', "?" => ["assessment_survey_id" => $assessmentSurveyQuestion->assessment_survey_id]]);
            }
            $this->Flash->error(__('The assessment survey question could not be saved. Please, try again.'));
        }
        $assessmentSurveys = $this->AssessmentSurveyQuestions->AssessmentSurveys->find('list', ['limit' => 200]);
        $responseGroups = $this->AssessmentSurveyQuestions->ResponseGroups->find('list', ['limit' => 200]);
        $this->set(compact('assessmentSurveyQuestion', 'assessmentSurveys', 'responseGroups'));
        $this->set('_serialize', ['assessmentSurveyQuestion']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Assessment Survey Question id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assessmentSurveyQuestion = $this->AssessmentSurveyQuestions->get($id);
        if ($this->AssessmentSurveyQuestions->delete($assessmentSurveyQuestion)) {
            $this->Flash->success(__('The assessment survey question has been deleted.'));
        } else {
            $this->Flash->error(__('The assessment survey question could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
