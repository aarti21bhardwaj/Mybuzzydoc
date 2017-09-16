<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * MilestoneLevels Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorMilestones
 * @property \Cake\ORM\Association\HasMany $MilestoneLevelAwards
 * @property \Cake\ORM\Association\HasMany $MilestoneLevelRewards
 * @property \Cake\ORM\Association\HasMany $MilestoneLevelRules
 *
 * @method \App\Model\Entity\MilestoneLevel get($primaryKey, $options = [])
 * @method \App\Model\Entity\MilestoneLevel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MilestoneLevel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MilestoneLevel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevel findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MilestoneLevelsTable extends Table
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

        $this->table('milestone_levels');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorMilestones', [
            'foreignKey' => 'vendor_milestone_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('MilestoneLevelAwards', [
            'foreignKey' => 'milestone_level_id'
        ]);
        $this->hasMany('MilestoneLevelRewards', [
            'foreignKey' => 'milestone_level_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('MilestoneLevelRules', [
            'foreignKey' => 'milestone_level_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->integer('level_number')
            ->requirePresence('level_number', 'create')
            ->notEmpty('level_number');

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

        return $rules;
    }
}
