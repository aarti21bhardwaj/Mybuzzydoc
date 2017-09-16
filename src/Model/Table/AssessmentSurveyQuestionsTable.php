<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentSurveyQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AssessmentSurveys
 * @property \Cake\ORM\Association\BelongsTo $ResponseGroups
 * @property \Cake\ORM\Association\HasMany $VendorAssessmentSurveyQuestions
 *
 * @method \App\Model\Entity\AssessmentSurveyQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSurveyQuestion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentSurveyQuestionsTable extends Table
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

        $this->table('assessment_survey_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AssessmentSurveys', [
            'foreignKey' => 'assessment_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ResponseGroups', [
            'foreignKey' => 'response_group_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('VendorAssessmentSurveyQuestions', [
            'foreignKey' => 'assessment_survey_question_id',
            'dependent' => true,
            'cascadeCallBacks' => true
        ]);
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
            ->requirePresence('text', 'create')
            ->notEmpty('text');

        return $validator;
    }

    public function afterSave($event, $entity){
        if($entity->isNew()){
            $vendorAssessmentSurvey = $this->VendorAssessmentSurveyQuestions
                                          ->VendorAssessmentSurveys
                                          ->findByAssessmentSurveyId($entity->assessment_survey_id)
                                          ->select(['vendor_assessment_survey_id' => 'id', 'assessment_survey_question_id' => $entity->id])
                                          ->all()
                                          ->map(function($value, $key){
                                            return $value->toArray();
                                          })
                                          ->toArray();

            $vendorAssessmentSurveyQuestions = $this->VendorAssessmentSurveyQuestions->newEntities($vendorAssessmentSurvey);
            if(!$this->VendorAssessmentSurveyQuestions->saveMany($vendorAssessmentSurveyQuestions)){
                return false;
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
        $rules->add($rules->existsIn(['assessment_survey_id'], 'AssessmentSurveys'));
        $rules->add($rules->existsIn(['response_group_id'], 'ResponseGroups'));

        return $rules;
    }
}
