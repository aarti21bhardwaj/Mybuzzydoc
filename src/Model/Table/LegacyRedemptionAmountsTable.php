<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * LegacyRedemptionAmounts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LegacyRedemptions
 *
 * @method \App\Model\Entity\LegacyRedemptionAmount get($primaryKey, $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionAmount findOrCreate($search, callable $callback = null)
 */
class LegacyRedemptionAmountsTable extends Table
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

        $this->table('legacy_redemption_amounts');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');
        
        $this->belongsTo('LegacyRedemptions', [
            'foreignKey' => 'legacy_redemption_id',
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
            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

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
        $rules->add($rules->existsIn(['legacy_redemption_id'], 'LegacyRedemptions'));

        return $rules;
    }
}
