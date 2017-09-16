<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReferralTiers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $PatientReferralTiers
 * @property \Cake\ORM\Association\HasMany $ReferralTierAwards
 * @property \Cake\ORM\Association\HasMany $ReferralTierGiftCoupons
 * @property \Cake\ORM\Association\HasMany $ReferralTierPerks
 *
 * @method \App\Model\Entity\ReferralTier get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralTier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralTier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralTier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTier findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralTiersTable extends Table
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

        $this->table('referral_tiers');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PatientReferralTiers', [
            'foreignKey' => 'referral_tier_id'
        ]);
        $this->hasMany('ReferralTierAwards', [
            'foreignKey' => 'referral_tier_id'
        ]);
        $this->hasOne('ReferralTierGiftCoupons', [
            'foreignKey' => 'referral_tier_id'
        ]);
        $this->hasMany('ReferralTierPerks', [
            'foreignKey' => 'referral_tier_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->integer('referrals_required')
            ->requirePresence('referrals_required', 'create')
            ->notEmpty('referrals_required');

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
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->IsUnique(['vendor_id', 'referrals_required'], false));

        return $rules;
    }
}
