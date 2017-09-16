<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReferralTierAwards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $ReferralTiers
 * @property \Cake\ORM\Association\BelongsTo $Referrals
 * @property \Cake\ORM\Association\BelongsTo $PeoplehubTransactions
 *
 * @method \App\Model\Entity\ReferralTierAward get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralTierAward newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralTierAward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierAward|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralTierAward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierAward[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierAward findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralTierAwardsTable extends Table
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

        $this->table('referral_tier_awards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
        ]);
        
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReferralTiers', [
            'foreignKey' => 'referral_tier_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Referrals', [
            'foreignKey' => 'referral_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PeoplehubTransactions', [
            'foreignKey' => 'peoplehub_transaction_id',
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
            ->integer('redeemer_peoplehub_identifier')
            ->requirePresence('redeemer_peoplehub_identifier', 'create')
            ->notEmpty('redeemer_peoplehub_identifier');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->existsIn(['referral_tier_id'], 'ReferralTiers'));
        $rules->add($rules->existsIn(['referral_id'], 'Referrals'));

        return $rules;
    }
}
