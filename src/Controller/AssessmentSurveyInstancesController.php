<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
/**
 * AssessmentSurveyInstances Controller
 *
 * @property \App\Model\Table\AssessmentSurveyInstancesTable $AssessmentSurveyInstances
 */
class AssessmentSurveyInstancesController extends AppController
{

    public function initialize(){
        Parent::initialize();
        $this->Auth->allow(['patientReportedOutcomes']);
    }

    public function patientReportedOutcomes(){

        $assessmentSurveyInstanceId = $this->request->query('key');
        if(!$assessmentSurveyInstanceId){

            throw new NotFoundException(__('NOT_FOUND','Survey'));
        }

        $assessmentSurveyInstance = $this->AssessmentSurveyInstances
                                         ->findByUuid($assessmentSurveyInstanceId)
                                         ->contain(['AssessmentSurveyInstanceResponses'])
                                         ->first();
        if(!$assessmentSurveyInstance){
          throw new NotFoundException(__('NOT_FOUND','Assessment Survey'));
        }

        $this->viewBuilder()
            ->layout('review-form');

        if($assessmentSurveyInstance->assessment_survey_instance_responses){
            $assessmentSurveyInstanceId = false;
        }



        $this->set(compact('assessmentSurveyInstanceId'));
        $this->set('_serialize', ['survey']);
    }
}