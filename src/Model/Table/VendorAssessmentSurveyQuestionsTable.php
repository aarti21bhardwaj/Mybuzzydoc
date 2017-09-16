<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VendorAssessmentSurveyQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorAssessmentSurveys
 * @property \Cake\ORM\Association\BelongsTo $AssessmentSurveyQuestions
 * @property \Cake\ORM\Association\HasMany $AssessmentSurveyInstanceResponses
 *
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurveyQuestion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorAssessmentSurveyQuestionsTable extends Table
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

        $this->table('vendor_assessment_survey_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('VendorAssessmentSurveys', [
            'foreignKey' => 'vendor_assessment_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AssessmentSurveyQuestions', [
            'foreignKey' => 'assessment_survey_question_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentSurveyInstanceResponses', [
            'foreignKey' => 'vendor_assessment_survey_question_id'
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

        return $validator;
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
        $rules->add($rules->existsIn(['assessment_survey_question_id'], 'AssessmentSurveyQuestions'));

        return $rules;
    }
}
