<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * GiftCouponRedemptions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GiftCouponAwards
 *
 * @method \App\Model\Entity\GiftCouponRedemption get($primaryKey, $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponRedemption findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GiftCouponRedemptionsTable extends Table
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

        $this->table('gift_coupon_redemptions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('GiftCouponAwards', [
            'foreignKey' => 'gift_coupon_award_id',
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
            ->boolean('redemption_status')
            ->requirePresence('redemption_status', 'create')
            ->notEmpty('redemption_status');

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
        $rules->add($rules->existsIn(['gift_coupon_award_id'], 'GiftCouponAwards'));

        return $rules;
    }
}
