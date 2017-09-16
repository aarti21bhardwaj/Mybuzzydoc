<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * TemplateGiftCoupons Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Templates
 * @property \Cake\ORM\Association\BelongsTo $GiftCoupons
 *
 * @method \App\Model\Entity\TemplateGiftCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateGiftCoupon findOrCreate($search, callable $callback = null)
 */
class TemplateGiftCouponsTable extends Table
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

        $this->table('template_gift_coupons');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
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
        $rules->add($rules->existsIn(['template_id'], 'Templates'));
        $rules->add($rules->existsIn(['gift_coupon_id'], 'GiftCoupons'));

        return $rules;
    }
}
