<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * GiftCouponAwards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GiftCoupons
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $GiftCouponRedemptions
 *
 * @method \App\Model\Entity\GiftCouponAward get($primaryKey, $options = [])
 * @method \App\Model\Entity\GiftCouponAward newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GiftCouponAward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponAward|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GiftCouponAward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponAward[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponAward findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GiftCouponAwardsTable extends Table
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

        $this->table('gift_coupon_awards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('GiftCoupons', [
            'foreignKey' => 'gift_coupon_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('GiftCouponRedemptions', [
            'foreignKey' => 'gift_coupon_award_id'
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
            ->integer('transaction_number')
            ->requirePresence('transaction_number', 'create')
            ->notEmpty('transaction_number');

        $validator
            ->requirePresence('redeemer_name', 'create')
            ->notEmpty('redeemer_name');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->allowEmpty('reason');

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
        $rules->add($rules->existsIn(['gift_coupon_id'], 'GiftCoupons'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }
}
