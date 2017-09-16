<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * MilestoneLevelRules Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MilestoneLevels
 *
 * @method \App\Model\Entity\MilestoneLevelRule get($primaryKey, $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MilestoneLevelRule findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MilestoneLevelRulesTable extends Table
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

        $this->table('milestone_level_rules');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('MilestoneLevels', [
            'foreignKey' => 'milestone_level_id',
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
            ->requirePresence('level_rule', 'create')
            ->notEmpty('level_rule');

        $validator
            ->dateTime('end_time')
            ->allowEmpty('end_time');

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
        $rules->add($rules->existsIn(['milestone_level_id'], 'MilestoneLevels'));

        return $rules;
    }
}
