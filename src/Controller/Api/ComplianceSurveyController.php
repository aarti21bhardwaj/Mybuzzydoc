<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Core\Exception\Exception;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\I18n\FrozenTime;

/**
 * ComplianceSurvey Controller: Apis pertaining to compliance survey and awarding milestones through it.
 *
 * @property \App\Model\Table\ComplianceSurveyTable $ComplianceSurvey
 */
class ComplianceSurveyController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('VendorSettings');
        $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                         ->contain(['SettingKeys' => function($q){
                                                                            return $q->where(['name' => 'Live Mode']);
                                                                        }
                                                    ])
                                         ->first()->value;
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
        $this->loadComponent('InstantRewards', ['liveMode' => $liveMode]);
    }

    /**
   * @api {post} Save compliance survey responses and give the required reward
   * @apiName SaveResponses
   *
   * @apiParam {Number} patients_peoplehub_id Redeemers unique ID on peoplehub.
   * @apiParam {String} patient_name Redeemers name.
   * @apiParam {String} attribute_type Either email address, phone number or card.
   * @apiParam {Mixed} attribute Value of the attribute to which the points are to be awarded on peoplehub.
   * @apiParam {Array} survey_instance_responses All the responses in an array of object where each object has keys: vendor_survey_question_id and response.
   *
   * @apiSuccess {String} message Success message string "ok".
   * @apiSuccess {Object} data
   * @apiSuccess {Number} data.id Unique vendor_survey_instance id.
   *
   * @apiSuccessExample Success-Response:
   *     HTTP/1.1 200 OK
   *     {
   *       "message" : "ok",
   *       "data" : {
   *                  "id" : 1
   *                }
   *     }
   */
    public function saveResponses(){
        //validations
        if(!$this->request->is('post')){
             throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!isset($this->request->data['patient_peoplehub_id'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'patient_peoplehub_id'));
        }
        if(!isset($this->request->data['patient_name'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'patient_name'));
        }
        if(!isset($this->request->data['survey_instance_responses'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','survey_instance_responses'));
        }else{
            foreach ($this->request->data['survey_instance_responses'] as $response) {
                if(!isset($response['vendor_survey_question_id'])){
                    throw new BadRequestException(__('MANDATORY_FIELD_MISSING','vendor survey question id'));      
                }
                if(!isset($response['response'])){
                    throw new BadRequestException(__('MANDATORY_FIELD_MISSING','response'));
                }
            }
        }

        Log::write('debug', 'saving the responses for the survey');
        //fetch the VendorSurveys data to fetch the questions, points and instances associated to it.
        $this->loadModel('VendorSurveys');
        $vendorSurveyData = $this->VendorSurveys->findByVendorId($this->Auth->user('vendor_id'))->contain(['VendorSurveyInstances', 'VendorSurveyQuestions'])->first();
        
        if(!$vendorSurveyData){
            Log::write('debug', 'got bad data');
            throw new BadRequestException(__('RECORD_NOT_FOUND'));
        }

        $vendorSurveyId = $vendorSurveyData->id;
        $iteration = 1; 
        //find the number of iterations for the survey. If no surveys have been taken yet, $iteration=1. 
        if(count($vendorSurveyData->vendor_survey_instances) > 0){
            $temp = new Collection($vendorSurveyData->vendor_survey_instances);
            $temp = $temp->last();
            $temp = ($temp->toArray());
            $iteration = $temp['iteration'];
            Log::write('debug', 'number of iterations are more than 1');
        }
        //for creating a new survey instance and making entry in VendorSurveyInstances.
        $vendorSurveyInstanceData = ['vendor_survey_id' => $vendorSurveyId, 'patient_peoplehub_id' => $this->request->data['patient_peoplehub_id'], 'user_id' => $this->Auth->user('id'), 'iteration' => $iteration+1, 'survey_instance_responses' => $this->request->data['survey_instance_responses'], 'perfect_patient' => 0];
        
        $this->loadModel('VendorSurveyInstances');
        $vendorSurveyInstance = $this->VendorSurveyInstances->newEntity($vendorSurveyInstanceData, ['associated' => 'SurveyInstanceResponses']);
        //proceed only if entity has no errors.
        if($vendorSurveyInstance->errors()){
            Log::write('debug', 'found errors while preparing data to save survey instance');
            throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'));
        }
        //if save attempt is successful, proceed to find out the rule that should be applied.
        $vendorSurveyInstances = $this->VendorSurveyInstances->save($vendorSurveyInstance);
        if(!$vendorSurveyInstances){
            Log::write('debug', 'survey instance was not saved even though entity reported no errors');
            throw new InternalErrorException(__('ENTITY_ERROR', 'vendor survey instance'));
        }

        Log::write('debug', 'survey instance saved successfully. now will go to set the rule');
        $responseOfSetRule = $this->_setRule();
        if(!$responseOfSetRule){
            Log::write('debug', 'back from set rule.. some error occured');
            throw new InternalErrorException(__('BAD_REQUEST'));
        }

        $rule = $responseOfSetRule['rule'];
        $isMilestoneEnabled = $responseOfSetRule['isMilestoneEnabled'];
        Log::write('debug', 'back from set rule.. no error found and proceeding to awardPointsForSurvey');

        //In case of milestone, first calculate award points for survey and then make entry in survey. If after survey is complete and responses saved, the perfect patient count equals any milestone level rule that hasn't yet been awarded to that patient, give milestone reward. 
        $responseOfAwardPointsForSurvey = $this->_awardPointsForSurvey($vendorSurveyData->vendor_survey_questions, $this->request->data['survey_instance_responses'], $vendorSurveyInstance->id, $this->request->data['patient_peoplehub_id'], $vendorSurveyId, $rule, $this->request->data['patient_name']);

        if(!$responseOfAwardPointsForSurvey){
            Log::write('debug', 'back form awardPointsForSurvey..no response from the award point method');
            throw new InternalErrorException(__('BAD_REQUEST'));
        }

        $peoplehubTransactionId = $responseOfAwardPointsForSurvey['peoplehubTransactionId'];
        $points = $responseOfAwardPointsForSurvey['points'];

        if($isMilestoneEnabled){
            Log::write('debug', 'going to go over to _awardPointsForMilestone now');
            $responseFromMilestoneAwards = $this->_awardPointsForMilestone($this->request->data['patient_peoplehub_id'], $vendorSurveyId, $peoplehubTransactionId, $points, $this->request->data['patient_name']);

            if($responseFromMilestoneAwards){
                $response = ['message' => 'Ok', 'data' => ['id' => $vendorSurveyInstance->id]];
                $this->set('response', $response);
                Log::write('debug', 'back from awardPointsForMilestone. points should be awarded for Milestone');
                Log::write('debug', 'bbye from this function surveyAwards');
            }else{
                Log::write('debug', 'awardPointsForMilestone returned an impossible condition of false');
                throw new InternalErrorException(__('BAD_REQUEST'));
            }
        }else{
            $response = ['message' => 'Ok', 'data' => ['id' => $vendorSurveyInstance->id]];
            $this->set('response', $response);
        }

        $this->set('_serialize', ['response']);
    } 

    /**
    *Mathod to setRule for awarding points for perfect patient according to the vendor setting. Also, check if milestone program has been enabled by the vendor or not,
    *
    *
    */
    private function _setRule(){

        $this->loadModel('VendorSettings');
        Log::write('debug', 'welcome to set rule');
        $vendorSettings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                               ->where(['setting_key_id IN' => [8,9]]) //8 is the id for milestone and 9 for awarding points to compliance survey when it is perfect
                                               ->all()->indexBy('setting_key_id')->toArray();
        //find rule for awarding points
        $isMilestoneEnabled = FALSE;
        if(isset($vendorSettings[8]) && $vendorSettings[8]->value == 1){
            $isMilestoneEnabled = TRUE;
                Log::write('debug', 'milestone is enabled');
        }
        
        if(isset($vendorSettings[9]) && $vendorSettings[9]->value == 1){
            $rule = 'perfectPatient';
            Log::write('debug', 'rule is perfectPatient');
        }else{

            $rule = 'perQuestion';
            Log::write('debug', 'rule is perQuestion');
        }
        Log::write('debug', 'bbye now from setRule');
        return ['rule' => $rule, 'isMilestoneEnabled' => $isMilestoneEnabled];   
    }

    /**
    *Method to calculate the points for the survey as per the rule. 
    *
    *
    */
    private function _awardPointsForSurvey($quesData, $responseData, $vendorSurveyInstId, $patientsPeoplehubId, $vendorSurveyId, $rule, $patientsName){

        Log::write('debug', 'welcome to AwardPointForSurvey');
        //initializing variables
        $pointsToAward = 0; //total points that should be awarded to the patient for a survey instance.
        $questionsAnswered =[]; //the count of questions that were answered as true
        $forPerfectSurvey = []; //the count of questions that should be answered as true for a perfect survey
        
        Log::write('debug', 'will start calculating totalpoints to award');        
        
        foreach ($responseData as $response){
            //if a response is true, increment $questionsAnswered and add its points to $pointsToAward.
            if($response['response'] == true) {
                $questionsAnswered[] = $response['vendor_survey_question_id'];
                $temp = new Collection($quesData);
                 //Calculation of points for a response
                 $temp = $temp->reduce(function($pointsOfQues, $valueOfQuesData) use($response){
                     if($valueOfQuesData->id == $response['vendor_survey_question_id'] && $response['response'] == true){
                         return $pointsOfQues+$valueOfQuesData->points;
                     }
                     return $pointsOfQues;
                 }, 0);
                $pointsToAward = $pointsToAward+$temp; 
            }
            //if a question is to be considered for a survey to be perfect, increment $forPerfectSurvey.
            if($response['forPerfectSurvey']){
                $forPerfectSurvey[] = $response['vendor_survey_question_id'];
            } 
        }

        Log::write('debug', 'totalpoints to award have been calculated');
        $isPerfectPatient = 0;
        if(is_array($forPerfectSurvey) && is_array($questionsAnswered) && count(array_diff($forPerfectSurvey, $questionsAnswered)) == 0){
            $isPerfectPatient = 1;
        }
        Log::write('debug', 'just figured out with the perfectPatient is true or not for the survey');
        //check in vendorSettings if points are to be awarded per questions, per perfect complaince survey or per milestone level.
        //if rule is to awardPoints only when a survey has perfect responses.
        if($rule == 'perfectPatient' && !$isPerfectPatient){
            $pointsToAward = 0;    
        }

        Log::write('debug', 'Now i know if i have to award points or not based on the last step');

        Log::write('debug', 'Now handing control to _awardPointsAtPeopleHub function');
        $response = $this->_awardPointsAtPeopleHub($pointsToAward, $vendorSurveyInstId, $isPerfectPatient, $patientsPeoplehubId, $vendorSurveyId, $rule, $patientsName);
        Log::write('debug', 'back from _awardPointsAtPeopleHub function.. will send response back to saveResponses function');

        //update points taken in patient_visit_spendings table
        $this->InstantRewards->checkStatus($this->Auth->user('vendor_id'), $patientsPeoplehubId, $pointsToAward);
        
        if($response){
            Log::write('debug', 'bbye from function _awardPointsForSurvey');
            return $response;
        } else{
            Log::write('debug', 'Some problem... couldnt send response back to saveResponses');
            throw new InternalErrorException(__('in _awardPointsForSurvey'));
        }
    }


   
    /**
    *Method to award points for survey on peoplehub
    *
    *
    */
    private function _awardPointsAtPeopleHub($points, $vendorSurveyInstId = null, $isPerfectPatient=null, $patientsPeoplehubId, $vendorSurveyId, $rule, $patientsName){
        // echo 'in _awardPointsAtPeopleHub'; pr($rule); die;
        Log::write('debug', 'welcome to AwardPoints');
        $rewardType = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                           ->contain(['SettingKeys' => function($q){
                                                return $q->where(['name' => 'Credit Type']);
                                            }])
                                           ->first()->value;

        if(!is_string($rewardType)){
            Log::write('debug', 'invalid reward type');
            throw new BadRequestException(__('INVALID_REWARD_TYPE'));
        }

        if($points){

            Log::write('debug', 'we have points.. so figuring out what to do next');
            $awardPointsData = ['attribute_type' => 'id', 'attribute' => $patientsPeoplehubId, 'points' => $points, 'reward_type' => $rewardType];

            Log::write('debug', 'telling peoplehub component to give points');
            $awardPointsResponse = $this->PeopleHub->provideReward($awardPointsData, $this->Auth->user('vendor_peoplehub_id'));

            if(!$awardPointsResponse['status']){
                throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($awardPointsResponse['response']->message)));
                Log::write('debug', 'peoplehub is upset didnt give points');
            }

            if(!$awardPointsResponse['response']->status){
            Log::write('debug', 'peoplehub is upset didnt give points for a different reason than before');
                throw new InternalErrorException(__('PEOPLEHUB_TRANSACTION_FAILED'));
            }

            $phTransactionId = $awardPointsResponse['response']->data->id;
            Log::write('debug', 'peoplehub listened to me and gave points');
        }else{
            $phTransactionId = NULL;
        }
        Log::write('debug', 'now we are going to over to surveyAwards');        
        
        $response = $this->_surveyAwards($vendorSurveyInstId, $points, $phTransactionId, $isPerfectPatient, $patientsPeoplehubId, $vendorSurveyId, $patientsName, $rule);
        Log::write('debug', 'back from surveyAwards.. hope everything worked out fine');        

        if($response){
            Log::write('debug', 'bbye from this function _awardPointsAtPeopleHub');
            return $response;
        } else{
            Log::write('debug', 'some error occured in surveyAwards');        
            throw new InternalErrorException(__('in _awardPointsAtPeopleHub'));
        }        
    }

    /**
    *Method to award survey rewards after they have been awarded on Peoplehub.
    *
    *
    */
    private function _surveyAwards($vendorSurveyInstId, $points, $peoplehubTransactionId = null, $isPerfectPatient, $patientsPeoplehubId, $vendorSurveyId, $patientsName, $rule){

        Log::write('debug', 'welcome to surveyAwards');        

        $surveyAwardData = ['vendor_survey_instance_id' => $vendorSurveyInstId, 'points' => $points, 'peoplehub_transaction_id' => $peoplehubTransactionId, 'user_id' => $this->Auth->user('id'), 'redeemer_peoplehub_identifier' => $patientsPeoplehubId, 'vendor_id' => $this->Auth->user('vendor_id')];
        $vendorSurveyData = ['perfect_patient' => $isPerfectPatient];
        
        Log::write('debug', 'we will now update the survey instance based on whether the survey was perfect or not');

        $this->loadModel('VendorSurveyInstances');
        $vendorSurvey = $this->VendorSurveyInstances->get($vendorSurveyInstId);
        $vendorSurvey = $this->VendorSurveyInstances->patchEntity($vendorSurvey, $vendorSurveyData);
        
        $statusOfVendorSurveyInstances = $this->VendorSurveyInstances->save($vendorSurvey); 
        if(!$statusOfVendorSurveyInstances){
            Log::write('debug', 'survey instance did not update ');            
           throw new InternalErrorException(__('ENTITY_ERROR', 'vendor survey instance'));
        }
        Log::write('debug', 'survey instance updated');
        
        $this->loadModel('SurveyAwards');
        $surveyAward = $this->SurveyAwards->newEntity($surveyAwardData);
        
        if($surveyAward->errors()){
            Log::write('debug', 'survey award entity has errors');
            throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'.'in survey award'));
        }
        // pr($surveyAward); die;
        $surveyAward = $this->SurveyAwards->save($surveyAward);

        if(!$surveyAward){
            Log::write('debug', 'survey award table couldnt save data');
            throw new InternalErrorException(__('ENTITY_ERROR', 'survey awards'));
        }
        return ['peoplehubTransactionId' => $peoplehubTransactionId, 'points' => $points];
    }


     /**
    *Method to check the acheivement of a milestone level and fire the event to process its acheivement.
    *
    *Milestone Program: A vendor can create a milestone program that is either of a fixed duration or unlimited duration.
    *A fixed duration program will have multiple levels that can be acheived after a certain number of perfect surveys have been taken by the patient.
    *An unlimited program will have one level that can be acheived after every certain number of perfect surveys taken by the patient.
    *
    */
     private function _awardPointsForMilestone($patientsPeoplehubId, $vendorSurveyId, $peoplehubTransactionId, $points, $patientsName){
        
        Log::write('debug', 'welcome to awardPointsForMilestone');
        $this->loadModel('Vendors');
        //fetch vendors survey data to find perfect surveys and milestones data to fetch its milestone program.
        $vendorsData = $this->Vendors->findById($this->Auth->user('vendor_id'))
                     ->contain([
                               'VendorSurveys' =>function($a) use($vendorSurveyId, $patientsPeoplehubId){
                                     return $a->where(['id' => $vendorSurveyId])
                                              ->contain(['VendorSurveyInstances' => function($x) use($patientsPeoplehubId){
                                                 return $x->where(['perfect_patient' => 1, 'patient_peoplehub_id' => $patientsPeoplehubId]);
                                              }
                                              ]);
                                }, 
                                'VendorMilestones' => function($b) use($patientsPeoplehubId){
                                     return $b->contain(['MilestoneLevels' => function($y)use($patientsPeoplehubId){
                                                  return $y->contain(['MilestoneLevelRewards.RewardTypes', 'MilestoneLevelRules', 'MilestoneLevelAwards' => function($x) use($patientsPeoplehubId){
                                                      return $x->where(['redeemer_peoplehub_identifier' => $patientsPeoplehubId]);
                                                    }]
                                                );
                                            }
                                    ]);
                                }
                        ])
                     ->first();
        //get all the perfect surveys for the patient
        $perfectPatientSurveyInstances = $vendorsData->vendor_surveys[0]->vendor_survey_instances;
        //check if the vendor has any active milestone programs or not
        if(!isset($vendorsData->vendor_milestone->milestone_levels)){
            Log::write('debug', 'no vendor milestone found');
            Log::write('debug', 'bbye from this function');

            return true;
        }

        $vendorMilestoneLevelsData = $vendorsData->vendor_milestone->milestone_levels;
        //check the number of perfect patient surveys. 
        $collection = new Collection($perfectPatientSurveyInstances);
        //find the number of perfect patient surveys that have been taken since the milestone program was created.
        $perfectPatientSurveyInstances = $collection->filter(function($value, $key) use($vendorsData){
                                                $time1 = new FrozenTime($value->created);
                                                $time2 = new FrozenTime($vendorsData->vendor_milestone->modified);
                                                $timeDiff = $time2->diff($time1);
                                                if($timeDiff->invert == 0){
                                                    return true;
                                                }
                                                return false;
                                            });
        $perfectPatientSurveyInstances = $perfectPatientSurveyInstances->toArray();
        $previousPerfectPatientCount = count($perfectPatientSurveyInstances);
 
        //Initializing $rewards
        $rewards = NULL;
        foreach ($vendorMilestoneLevelsData as $milestoneLevel) {
            //if milestone_level_awards don't exist and the program is of limited duration OR if the program is unlimited then proceed for the level.
                //Each level of a limited term program can be awarded only once, hence the check.
            if((empty($milestoneLevel->milestone_level_awards) && $vendorsData->vendor_milestone->fixed_term == 1) || $vendorsData->vendor_milestone->fixed_term == 0){
                if(empty($milestoneLevel->milestone_level_rules)){
                    Log::write('debug', 'no rule set for the milestone level');
                    throw new BadRequestException(__('Set the rule for milestone level '.$milestoneLevel->name));
                }
              
                //If the program is of limited duration and the level_rule is equivalent to the perfect patient count then the level has been acheived.
                if($vendorsData->vendor_milestone->fixed_term == 1 && $milestoneLevel->milestone_level_rules[0]->level_rule == $previousPerfectPatientCount){
                    Log::write('debug', 'this is fixed term and perfect patient count is equal');
                    $levelAcheived = $milestoneLevel->id;
                    $rewards = $milestoneLevel->milestone_level_rewards;          
                    break;
                }else{
                    Log::write('debug', 'this is either not fixed term or perfect patient count is not equal to rule');
                }
                //If the program is of unlimited duration and the perfect surveys are a multiple of the level_rule then the level has been acheived.
                    //0 is a multiple of all numbers so don't proceed if the previousPerfectPatientCount is 0.
                if($vendorsData->vendor_milestone->fixed_term == 0 && (($previousPerfectPatientCount % $milestoneLevel->milestone_level_rules[0]->level_rule) == 0 && $previousPerfectPatientCount != 0 && $points != 0))
                {
                    Log::write('debug', 'this is unlimited term and perfect patient count is equal');
                    $levelAcheived = $milestoneLevel->id;
                    $rewards = $milestoneLevel->milestone_level_rewards;
                    break;
                }else{
                    Log::write('debug', 'this is either not unlimited term or perfect patient count is not equal to rule');
                }
            }else{
                Log::write('debug', 'Error Case. This is either not fixed term or unlimited term program. ');
            }
        }

        Log::write('debug', 'rewards found.. see json'.json_encode($rewards));

        Log::write('debug', 'we should fire the event next');

        if($rewards){
            Log::write('debug', 'ready to fire the event now');
            //fire event
            $data = array();
            $data['user_id'] = $this->Auth->user('id');
            $data['peoplehub_transaction_id'] = $peoplehubTransactionId;
            $data['redeemer_peoplehub_id'] = $patientsPeoplehubId;
            $data['patients_name'] = $patientsName;
            $data['level_achieved'] = $levelAcheived;
            $data['reward_data'] = $rewards;
            $data['vendor_id'] = $this->Auth->user('vendor_id');
            $data['vendor_peoplehub_id'] = $this->Auth->user('vendor_peoplehub_id');

            $event = new Event('Milestone.achieved', $this, [
                                        'arr' => [
                                                    'hashData' => $data,
                                                    'eventId' => 9, //give the event_id for which you want to fire the email
                                                 ]
                                ]);
            $this->eventManager()->dispatch($event);
        }else{
            Log::write('debug', 'we didnt fire the event');

        }
        Log::write('debug', 'event fired... bbye this function - _awardPointsForMilestone');
        return true;
     }

    /**
    *Api to fetch last response to each question in the survey.
    *
    *
    */
    public function getResponses($vendorSurveyId = null, $patientsPeoplehubId = null){
        
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$vendorSurveyId){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'vendor survey id'));
        }
        if(!$patientsPeoplehubId){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'patients peoplehub id'));
        }
        //fetch the vendor surveys, its instances and its responses.
        $this->loadModel('VendorSurveys');
        $vendorSurveyData = $this->VendorSurveys->findById($vendorSurveyId)
                                                ->contain(['VendorSurveyInstances' => function($q)use($patientsPeoplehubId){
                                                        return $q->where(['patient_peoplehub_id' => $patientsPeoplehubId])
                                                                 ->contain(['SurveyInstanceResponses']);
                                                        }
                                                ])
                                                ->first();
        if(!$vendorSurveyData){
            throw new BadRequestException(__('RECORD_NOT_FOUND'));
        }
        if($vendorSurveyData->vendor_id != $this->Auth->user('vendor_id')){
            throw new BadRequestException(__('MISMATCH'.': vendor_id'));
        }
        //if no survey instances exist, set responses as NULL.
        if(!count($vendorSurveyData->vendor_survey_instances)){
            $responses = NULL;
        }
        //for every survey instance, see if any responses exist
        foreach ($vendorSurveyData->vendor_survey_instances as $surveyIns) {
            // pr($surveyIns['survey_instance_responses']);
            //If no responses exist, set them as NULL.
            if(!$surveyIns['survey_instance_responses']){
                $responses[] = NULL;
            }else{
                foreach ($surveyIns['survey_instance_responses'] as $response) {
                    $responses[] = $response;
                }
            }
        }
        // pr($responses);
        //Fetch only the most recent response to all questions in the survey.
        if(!$responses){
            $lastResponses = NULL;
            $latestSurveyDate = NULL;
        }else{
            //filter $responses to get only the elements that aren't NULL
            $temp1 = new Collection($responses);
            $temp1 = $temp1->filter(function($value, $key){
                return $value != NULL;
            });
            //if count of $responses is 0, set api response variables as NULL 
            if(count($temp1->toArray()) == 0){
                $lastResponses = NULL;
                $latestSurveyDate = NULL;
            }else{    
                //group the responses by their question ids. Find the latest response for each question.
                $temp1 = $temp1->groupBy('vendor_survey_question_id');
                $temp1 = $temp1->map(function($value, $key){
                    $temp2 = new Collection($value);
                    $temp2 = $temp2->sortBy('created')->first();
                    return $temp2->toArray();
                });
                //find the latest response amongst all the responses and set as $latestSurveyDate.
                $latestSurveyDate = $temp1->sortBy('created')->extract('created')->first()->toFormattedDateString();
                $lastResponses = $temp1->toArray();
            }
        }
        $response = ['status' => 'ok', 'data' => $lastResponses, 'latestSurveyDate' => $latestSurveyDate];
        $this->set('response', $response);
        $this->set('_serialize', 'response');
    }
}
