<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VendorAssessmentSurveys Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $AssessmentSurveys
 * @property \Cake\ORM\Association\HasMany $AssessmentSurveyInstances
 * @property \Cake\ORM\Association\HasMany $VendorAssessmentSurveyQuestions
 *
 * @method \App\Model\Entity\VendorAssessmentSurvey get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorAssessmentSurvey findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorAssessmentSurveysTable extends Table
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

        $this->table('vendor_assessment_surveys');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AssessmentSurveys', [
            'foreignKey' => 'assessment_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentSurveyInstances', [
            'foreignKey' => 'vendor_assessment_survey_id'
        ]);
        $this->hasMany('VendorAssessmentSurveyQuestions', [
            'foreignKey' => 'vendor_assessment_survey_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallback' => true

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
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->existsIn(['assessment_survey_id'], 'AssessmentSurveys'));
        $rules->add($rules->IsUnique(['vendor_id', 'assessment_survey_id'], false));


        return $rules;
    }
}
