<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorSurveyInstances Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorSurveys
 * @property \Cake\ORM\Association\BelongsTo $PatientPeoplehubs
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $SurveyInstanceResponses
 *
 * @method \App\Model\Entity\VendorSurveyInstance get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorSurveyInstance findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorSurveyInstancesTable extends Table
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

        $this->table('vendor_survey_instances');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorSurveys', [
            'foreignKey' => 'vendor_survey_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SurveyInstanceResponses', [
            'foreignKey' => 'vendor_survey_instance_id'
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
            ->integer('iteration')
            ->requirePresence('iteration', 'create')
            ->notEmpty('iteration');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
