<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\Collection\Collection;
/**
 * AssessmentSurveyInstances Controller
 *
 * @property \App\Model\Table\AssessmentSurveyInstancesTable $AssessmentSurveyInstances
 */
class AssessmentSurveyInstancesController extends ApiController
{   
     public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['submitPatientReportedOutcome', 'getVendorAssessmentSurvey']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($this->request->data['vendor_assessment_survey_id']) || !$this->request->data['vendor_assessment_survey_id']){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor_assessment_survey_id'));
        }
        if(!isset($this->request->data['patient_peoplehub_id']) || !$this->request->data['patient_peoplehub_id']){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'patient_peoplehub_id'));
        }

        if(!isset($this->request->data['email']) || !$this->request->data['email']){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'email'));
        }

        $vendorAssessmentSurvey = $this->AssessmentSurveyInstances
                                       ->VendorAssessmentSurveys
                                       ->findById($this->request->data['vendor_assessment_survey_id'])
                                       ->contain(['AssessmentSurveys'])
                                       ->where(['vendor_id' => $this->Auth->user('vendor_id')])
                                       ->andWhere(['AssessmentSurveys.survey_type_id' => 1])
                                       ->first();
        if(!$vendorAssessmentSurvey){
            throw new NotFoundException('Vendor assessment survey not found.');
        }

        $query = $this->AssessmentSurveyInstances
                                    ->findByVendorAssessmentSurveyId($this->request->data['vendor_assessment_survey_id'])
                                    ->where(['patient_peoplehub_id'=> $this->request->data['patient_peoplehub_id']]);        
        $history = $query->last();

        if($history){                
            if($history->created->wasWithinLast(1)){
                throw new BadRequestException(__("Survey has already been submitted for today"));
            }
        }  

        $assessmentSurveyInstances = $query->all()->count();


        $this->request->data['iteration'] = $assessmentSurveyInstances +1;
        $assessmentSurveyInstance = $this->AssessmentSurveyInstances->newEntity();
        $assessmentSurveyInstance = $this->AssessmentSurveyInstances->patchEntity($assessmentSurveyInstance, $this->request->data, ['associated'=> 'AssessmentSurveyInstanceResponses']);   

        $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');

        if ($this->AssessmentSurveyInstances->save($assessmentSurveyInstance, ['reqData' => $this->request->data])){
            $response = ['message' => 'The assessment survey instance has been saved.'];
            
        }else{
            throw new InternalErrorException(__('The assessment survey instance could not be saved. Please, try again.'));
        }
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Assessment Survey Instance id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assessmentSurveyInstance = $this->AssessmentSurveyInstances->get($id);
        if ($this->AssessmentSurveyInstances->delete($assessmentSurveyInstance)) {
            $this->Flash->success(__('The assessment survey instance has been deleted.'));
        } else {
            $this->Flash->error(__('The assessment survey instance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getHistory($patientPeoplehubId = null){
        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if (!$patientPeoplehubId) {
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'patientPeoplehubId'));
        }
        $vendorId = $this->Auth->user('vendor_id');
        $query = $this->AssessmentSurveyInstances
                         ->findByPatientPeoplehubId($patientPeoplehubId)
                         ->contain(
                                    [ 'AssessmentSurveyInstanceResponses.ResponseOptions', 
                                     'Users', 
                                     'VendorAssessmentSurveys.AssessmentSurveys.SurveyTypes' =>function($q) use($vendorId){
                                        return $q->where(['VendorAssessmentSurveys.vendor_id' => $vendorId]);
                                    }]
                         )
                         ->all();
                         


        $history = [];

        foreach ($query as $key => $value) {
            if(!empty($value->assessment_survey_instance_responses)){
                $history[] = [
                                'date' => $value->created->format('Y-m-d'), 
                                'takenBy' => $value->user->first_name,
                                'surveyType' => $value->vendor_assessment_survey->assessment_survey->survey_type->name,
                                'surveyTypeId' => $value->vendor_assessment_survey->assessment_survey->survey_type_id,
                                'score' => (new Collection($value->assessment_survey_instance_responses))->sumOf(function($resp){
                                    return $resp->response_option->weightage;
                                })                                
                            ];
            }
        }


        if(!empty($history)){
            $history =  (new Collection($history))->groupBy('surveyTypeId')->toArray();
        }

        //Get Last survey Date
        if(!empty($history[1])){
            $lastStaffAssessmentDate = new Time($history[1][count($history[1])-1]['date']);
            if($lastStaffAssessmentDate->wasWithinLast(1)){
                $wasLastSurveyTakenToday = true;
            }else{
                $wasLastSurveyTakenToday = false;
            }
        }else{
            $wasLastSurveyTakenToday = false;
        }
                         
        $this->set(compact('history', 'wasLastSurveyTakenToday'));
        $this->set('_serialize', ['history', 'wasLastSurveyTakenToday']);
    }

    public function submitPatientReportedOutcome($uuid = null){
    
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if (!$uuid) {
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'key'));
        }
        $assessmentSurveyInstanceData =  $this->AssessmentSurveyInstances->findByUuid($uuid)
                                            ->contain([ 
                                            'VendorAssessmentSurveys.AssessmentSurveys', 'AssessmentSurveyInstanceResponses'])
                                            ->where(['AssessmentSurveys.survey_type_id' => 2])
                                            ->first();

        if(!$assessmentSurveyInstanceData){
          throw new NotFoundException(__('RECORD_NOT_FOUND'));
        }

        if(isset($assessmentSurveyInstanceData['assessment_survey_instance_responses']) && !empty($assessmentSurveyInstanceData['assessment_survey_instance_responses'])){
          throw new BadRequestException(__('Survey has already been submitted')); 
        }


        if(!isset($this->request->data['assessment_survey_instance_responses']) || !$this->request->data['assessment_survey_instance_responses'] || empty($this->request->data['assessment_survey_instance_responses'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'assessment_survey_instance_responses'));
        }

        foreach ($this->request->data['assessment_survey_instance_responses']  as $key => $value) {
          $this->request->data['assessment_survey_instance_responses'][$key]['assessment_survey_instance_id'] = $assessmentSurveyInstanceData->id;
        }

          $data =$this->request->data['assessment_survey_instance_responses'];
          $this->loadModel('AssessmentSurveyInstanceResponses');
          $assessmentSurveyInstanceResponses = $this->AssessmentSurveyInstanceResponses->newEntities($data);
          $assessmentSurveyInstanceResponses = $this->AssessmentSurveyInstanceResponses->patchEntities($assessmentSurveyInstanceResponses, $this->request->data['assessment_survey_instance_responses']);
          $save = $this->AssessmentSurveyInstanceResponses->saveMany($assessmentSurveyInstanceResponses);

          if ($save){
            $response = ['message' => 'The patient report outcome has been saved.'];
            
        }else{
            throw new InternalErrorException(__('The patient report outcome could not be saved. Please, try again.'));
        }
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function getVendorAssessmentSurvey($uuid){
      if (!$this->request->is('get')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
      if (!$uuid) {
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'uuid'));
        }
      $assessmentSurveyInstanceData =  $this->AssessmentSurveyInstances->findByUuid($uuid)
                                            ->contain(['VendorAssessmentSurveys.VendorAssessmentSurveyQuestions.AssessmentSurveyQuestions', 
                                            'VendorAssessmentSurveys.AssessmentSurveys'])
                                            ->where(['AssessmentSurveys.survey_type_id' => 2])
                                            ->first();
      if(!$assessmentSurveyInstanceData){
          throw new NotFoundException(__('RECORD_NOT_FOUND'));
      }

      $vendorAssessmentSurvey =$assessmentSurveyInstanceData->vendor_assessment_survey;

      $this->set('vendorAssessmentSurvey', $vendorAssessmentSurvey);
      $this->set('_serialize', ['vendorAssessmentSurvey']);

    }



}
