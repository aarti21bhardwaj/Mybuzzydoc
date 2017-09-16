<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AssessmentSurveys Controller
 *
 * @property \App\Model\Table\AssessmentSurveysTable $AssessmentSurveys
 */
class AssessmentSurveysController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SurveyTypes']
        ];
        $assessmentSurveys = $this->paginate($this->AssessmentSurveys);
        $assessmentSurvey = $this->AssessmentSurveys->newEntity();
        $surveyTypes = $this->AssessmentSurveys->SurveyTypes->find('list', ['limit' => 200]);
        $this->set(compact('assessmentSurveys', 'assessmentSurvey', 'surveyTypes'));
        $this->set('_serialize', ['assessmentSurveys']);
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

        $assessmentSurvey = $this->AssessmentSurveys->newEntity();
        
        $assessmentSurvey = $this->AssessmentSurveys->patchEntity($assessmentSurvey, $this->request->data);
        if ($this->AssessmentSurveys->save($assessmentSurvey)) {
            $this->Flash->success(__('The assessment survey has been saved.'));

            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error(__('The assessment survey could not be saved. Please, try again.'));
        $this->set(compact('assessmentSurvey', 'surveyTypes'));
        $this->set('_serialize', ['assessmentSurvey']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Assessment Survey id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $assessmentSurvey = $this->AssessmentSurveys->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $assessmentSurvey = $this->AssessmentSurveys->patchEntity($assessmentSurvey, $this->request->data);
            if ($this->AssessmentSurveys->save($assessmentSurvey)) {
                $this->Flash->success(__('The assessment survey has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The assessment survey could not be saved. Please, try again.'));
        }
        $surveyTypes = $this->AssessmentSurveys->SurveyTypes->find('list', ['limit' => 200]);
    
        $this->set(compact('assessmentSurvey', 'surveyTypes', 'assessmentSurveyQuestion', 'responseGroups'));
        $this->set('_serialize', ['assessmentSurvey']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Assessment Survey id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assessmentSurvey = $this->AssessmentSurveys->get($id);
        if ($this->AssessmentSurveys->delete($assessmentSurvey)) {
            $this->Flash->success(__('The assessment survey has been deleted.'));
        } else {
            $this->Flash->error(__('The assessment survey could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
