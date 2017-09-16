<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VendorRedemptionHistory Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\VendorRedemptionHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorRedemptionHistory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorRedemptionHistoryTable extends Table
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

        $this->table('vendor_redemption_history');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
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
            ->numeric('actual_balance')
            ->requirePresence('actual_balance', 'create')
            ->notEmpty('actual_balance');

        $validator
            ->numeric('redeemed_amount')
            ->requirePresence('redeemed_amount', 'create')
            ->notEmpty('redeemed_amount');

        $validator
            ->numeric('remaining_amount')
            ->requirePresence('remaining_amount', 'create')
            ->notEmpty('remaining_amount');

        $validator
            ->numeric('cc_charged_amount')
            ->allowEmpty('cc_charged_amount');

        $validator
            ->allowEmpty('cc_transaction_identifier');

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
