<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * SurveyInstanceResponses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorSurveyInstances
 * @property \Cake\ORM\Association\BelongsTo $VendorSurveyQuestions
 *
 * @method \App\Model\Entity\SurveyInstanceResponse get($primaryKey, $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyInstanceResponse findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveyInstanceResponsesTable extends Table
{

    use AuditLogTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('survey_instance_responses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorSurveyInstances', [
            'foreignKey' => 'vendor_survey_instance_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorSurveyQuestions', [
            'foreignKey' => 'vendor_survey_question_id',
            'joinType' => 'INNER'
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
            ->requirePresence('response', 'create')
            ->notEmpty('response');

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
        $rules->add($rules->existsIn(['vendor_survey_instance_id'], 'VendorSurveyInstances'));
        $rules->add($rules->existsIn(['vendor_survey_question_id'], 'VendorSurveyQuestions'));

        return $rules;
    }
}
