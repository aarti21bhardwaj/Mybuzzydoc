<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VendorInstantGiftCouponSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorInstantGiftCouponSetting findOrCreate($search, callable $callback = null, $options = [])
 */
class VendorInstantGiftCouponSettingsTable extends Table
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

        $this->table('vendor_instant_gift_coupon_settings');
        $this->displayField('id');
        $this->primaryKey('id');

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
            ->integer('amount_spent_threshold')
            ->requirePresence('amount_spent_threshold', 'create')
            ->notEmpty('amount_spent_threshold');

        $validator
            ->integer('points_earned_threshold')
            ->requirePresence('points_earned_threshold', 'create')
            ->notEmpty('points_earned_threshold');

        $validator
            ->integer('threshold_time_period')
            ->requirePresence('threshold_time_period', 'create')
            ->notEmpty('threshold_time_period');

        $validator
            ->integer('redemption_expiry')
            ->requirePresence('redemption_expiry', 'create')
            ->notEmpty('redemption_expiry');

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
