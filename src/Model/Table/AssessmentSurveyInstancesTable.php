<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Network\Session;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Utility\Text;
use Cake\Controller\Controller;

/**
 * AssessmentSurveyInstances Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorAssessmentSurveys
 * @property \Cake\ORM\Association\BelongsTo $PatientPeoplehubs
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $AssessmentSurveyInstanceResponses
 *
 * @method \App\Model\Entity\AssessmentSurveyInstance get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyInstance findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentSurveyInstancesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('assessment_survey_instances');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('VendorAssessmentSurveys', [
            'foreignKey' => 'vendor_assessment_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PatientPeoplehubs', [
            'foreignKey' => 'patient_peoplehub_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentSurveyInstanceResponses', [
            'foreignKey' => 'assessment_survey_instance_id'
        ]);

        $controller = new Controller;
        $this->UrlShortner = $controller->loadComponent('UrlShortner');
        $this->Bandwidth = $controller->loadComponent('Bandwidth');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('iteration')
            ->requirePresence('iteration', 'create')
            ->notEmpty('iteration');

        return $validator;
    }


    public function beforeSave($event, $entity, $options){
        
        if($entity->isNew()){ 
            $entity->uuid = Text::uuid();
        }

    }

    public function afterSaveCommit($event, $entity, $options){ 

        $surveyTypeId = $this->VendorAssessmentSurveys->findById($entity->vendor_assessment_survey_id)->contain(['AssessmentSurveys'])->first()->assessment_survey->survey_type_id;
        if($surveyTypeId == 1){
            //Send a PRO Request
            $reqData = $options->offsetGet('reqData');
            $proSurvey = $this->VendorAssessmentSurveys->findByVendorId($reqData['vendor_id'])->contain(['AssessmentSurveys'])->where(['AssessmentSurveys.survey_type_id' => 2])->first();

            if($proSurvey){

                $assessmentSurveyInstancesCount = $this->findByVendorAssessmentSurveyId($proSurvey->id)
                                    ->where(['patient_peoplehub_id'=> $entity->patient_peoplehub_id])
                                    ->all()
                                    ->count();

                $assessmentSurveyInstance = [
                    'vendor_assessment_survey_id' => $proSurvey->id,
                    'patient_peoplehub_id' => $entity->patient_peoplehub_id,
                    'user_id' => $entity->user_id,
                    'iteration' => $assessmentSurveyInstancesCount+1
                ];

                $assessmentSurveyInstance = $this->newEntity($assessmentSurveyInstance);   

                if ($this->save($assessmentSurveyInstance)) {
                
                    $url = Router::url('/', true);
                    $url = $url.'assessmentSurveyInstances/patientReportedOutcomes?key='.$assessmentSurveyInstance->uuid;
                    // $url = 'http://buzzy.twinspark.co/dev/assessmentSurveyInstance/patientReportedOutcomes?key='.$assessmentSurveyInstance->id;
                    $session = new Session();
                    $assessmentSurveyInstance->email = $reqData['email'];
                    $assessmentSurveyInstance->phone = $reqData['phone'];
                    $assessmentSurveyInstance->patient_name = $reqData['patient_name'];
                    $assessmentSurveyInstance->clinic_name = $session->read('VendorSettings.org_name');
                    $assessmentSurveyInstance->link = $url;
                    $assessmentSurveyInstance->vendorId = $reqData['vendor_id'];

                    $this->_sendLink($assessmentSurveyInstance);

                }else{
                    Log::write('error', "PRO survey could not be sent, Patient Peoplehub Id ".$entity->patient_peoplehub_id." Patient Name ".$reqData['name']);
                } 
            }

        }
    }

    private function _sendLink($entity){
        $event = new Event('patientReportedOutcomes.sendLink', $this, [
                'arr' => [
                    'hashData' => $entity,
                    'eventId' => 14, //give the event_id for which you want to fire the email
                    'vendor_id' => $entity->vendorId
                ]
        ]);

        $this->eventManager()->dispatch($event);
        if(isset($entity->phone) && $entity->phone != "" && $entity->phone){
        
            $liveMode = $this->VendorAssessmentSurveys
                             ->Vendors
                             ->VendorSettings
                             ->findByVendorId($entity->vendorId)
                             ->contain(['SettingKeys' => function($q){
                                return $q->where(['name' => 'Live Mode']);
                             }])
                             ->first()->value;

            $host = explode('/', $entity->link);
        
            if(!$liveMode){
            
                Log::write('debug', "PRO survey sms not sent as vendor is not live, Patient Peoplehub Id ".$entity->patient_peoplehub_id." Patient Name ".$entity->patient_name);
            
            }elseif($host[2] == 'localhost'){
                Log::write('debug', "PRO survey sms not sent as enviorment is local, Patient Peoplehub Id ".$entity->patient_peoplehub_id." Patient Name ".$entity->patient_name);

            }else{
        

                $shortUrl = $this->UrlShortner->shortenUrl($entity->link);
                $shortUrl = $shortUrl['url'];
            
                $message =  'Hi '.$entity->patient_name.','.'We at '.$entity->clinic_name.' care about you and want your health  to progress every week. Let us know how you are feeling by taking this short survey.'.$shortUrl;
            
            
                if($this->Bandwidth->sendMessage($entity->phone, $message)){
                    Log::write('debug', "PRO survey sms sent, Patient Peoplehub Id ".$entity->patient_peoplehub_id." Patient Name ".$entity->patient_name);

                }else{
                    Log::write('error', "Error in PRO survey sms, Patient Peoplehub Id ".$entity->patient_peoplehub_id." Patient Name ".$entity->patient_name);
                }
            }
         }
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['vendor_assessment_survey_id'], 'VendorAssessmentSurveys'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
