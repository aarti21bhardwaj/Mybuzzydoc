<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorMilestones Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $MilestoneLevels
 * @property \Cake\ORM\Association\HasMany $SurveyVendorMilestones
 *
 * @method \App\Model\Entity\VendorMilestone get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorMilestone newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorMilestone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorMilestone|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorMilestone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorMilestone[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorMilestone findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorMilestonesTable extends Table
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

        $this->table('vendor_milestones');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('MilestoneLevels', [
            'foreignKey' => 'vendor_milestone_id',
            'dependent' => true,
            'cascadeCallbacks' => true

        ]);
        $this->hasMany('VendorTemplateMilestones', [
            'foreignKey' => 'vendor_milestone_id',
            'dependent' => true,
            'cascadeCallbacks' => true

        ]);
        $this->hasMany('SurveyVendorMilestones', [
            'foreignKey' => 'vendor_milestone_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('fixed_term')
            ->requirePresence('fixed_term', 'create')
            ->notEmpty('fixed_term');

        $validator
            ->integer('end_duration')
            ->requirePresence('end_duration', 'create')
            ->notEmpty('end_duration');

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

        return $rules;
    }
}
