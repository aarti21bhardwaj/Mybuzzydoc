<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * SurveyQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Surveys
 * @property \Cake\ORM\Association\BelongsTo $Questions
 * @property \Cake\ORM\Association\HasMany $VendorSurveyQuestions
 *
 * @method \App\Model\Entity\SurveyQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\SurveyQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SurveyQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SurveyQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SurveyQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyQuestion findOrCreate($search, callable $callback = null)
 */
class SurveyQuestionsTable extends Table
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

        $this->table('survey_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Surveys', [
            'foreignKey' => 'survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Questions', [
            'foreignKey' => 'question_id',
            'joinType' => 'INNER'
        ]);

        
        $this->hasMany('VendorSurveyQuestions', [
            'foreignKey' => 'survey_question_id'
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
        $rules->add($rules->existsIn(['survey_id'], 'Surveys'));
        $rules->add($rules->existsIn(['question_id'], 'Questions'));
        $rules->add($rules->IsUnique(['survey_id', 'question_id'], false));
        return $rules;
    }
}
