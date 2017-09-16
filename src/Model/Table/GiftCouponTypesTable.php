<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GiftCouponTypes Model
 *
 * @property \Cake\ORM\Association\HasMany $GiftCoupons
 *
 * @method \App\Model\Entity\GiftCouponType get($primaryKey, $options = [])
 * @method \App\Model\Entity\GiftCouponType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GiftCouponType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GiftCouponType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GiftCouponType findOrCreate($search, callable $callback = null, $options = [])
 */
class GiftCouponTypesTable extends Table
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

        $this->table('gift_coupon_types');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('GiftCoupons', [
            'foreignKey' => 'gift_coupon_type_id'
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

        return $validator;
    }
}
