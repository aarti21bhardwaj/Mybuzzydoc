<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * SurveyVendorMilestones Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorMilestones
 * @property \Cake\ORM\Association\BelongsTo $VendorSurveys
 *
 * @method \App\Model\Entity\SurveyVendorMilestone get($primaryKey, $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SurveyVendorMilestone findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveyVendorMilestonesTable extends Table
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

        $this->table('survey_vendor_milestones');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorMilestones', [
            'foreignKey' => 'vendor_milestone_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorSurveys', [
            'foreignKey' => 'vendor_survey_id',
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
            ->boolean('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->dateTime('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmpty('end_time');

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
        $rules->add($rules->existsIn(['vendor_milestone_id'], 'VendorMilestones'));
        $rules->add($rules->existsIn(['vendor_survey_id'], 'VendorSurveys'));

        return $rules;
    }
}
