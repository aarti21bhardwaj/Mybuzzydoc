<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorSurveyQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorSurveys
 * @property \Cake\ORM\Association\BelongsTo $SurveyQuestions
 * @property \Cake\ORM\Association\HasMany $SurveyInstanceResponses
 *
 * @method \App\Model\Entity\VendorSurveyQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyQuestion findOrCreate($search, callable $callback = null)
 */
class VendorSurveyQuestionsTable extends Table
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

        $this->table('vendor_survey_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorSurveys', [
            'foreignKey' => 'vendor_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SurveyQuestions', [
            'foreignKey' => 'survey_question_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SurveyInstanceResponses', [
            'foreignKey' => 'vendor_survey_question_id'
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
            ->integer('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

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
        $rules->add($rules->existsIn(['vendor_survey_id'], 'VendorSurveys'));
        $rules->add($rules->existsIn(['survey_question_id'], 'SurveyQuestions'));

        return $rules;
    }
}
