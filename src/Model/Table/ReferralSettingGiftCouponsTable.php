<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReferralSettingGiftCoupons Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorReferralSettings
 * @property \Cake\ORM\Association\BelongsTo $GiftCoupons
 *
 * @method \App\Model\Entity\ReferralSettingGiftCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralSettingGiftCoupon findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralSettingGiftCouponsTable extends Table
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

        $this->table('referral_setting_gift_coupons');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorReferralSettings', [
            'foreignKey' => 'vendor_referral_setting_id',
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
        $rules->add($rules->existsIn(['vendor_referral_setting_id'], 'VendorReferralSettings'));
        $rules->add($rules->existsIn(['gift_coupon_id'], 'GiftCoupons'));

        return $rules;
    }
}
