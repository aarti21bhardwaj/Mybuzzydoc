<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReferralTierGiftCoupons Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ReferralTiers
 * @property \Cake\ORM\Association\BelongsTo $GiftCoupons
 *
 * @method \App\Model\Entity\ReferralTierGiftCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierGiftCoupon findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralTierGiftCouponsTable extends Table
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

        $this->table('referral_tier_gift_coupons');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReferralTiers', [
            'foreignKey' => 'referral_tier_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('GiftCoupons', [
            'foreignKey' => 'gift_coupon_id',
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
        $rules->add($rules->existsIn(['referral_tier_id'], 'ReferralTiers'));
        $rules->add($rules->existsIn(['gift_coupon_id'], 'GiftCoupons'));

        return $rules;
    }
}
