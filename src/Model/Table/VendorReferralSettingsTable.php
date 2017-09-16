<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorReferralSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\VendorReferralSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorReferralSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorReferralSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorReferralSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorReferralSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorReferralSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorReferralSetting findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorReferralSettingsTable extends Table
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
        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
            ]);

        $this->addBehavior('AuditStash.AuditLog');

        $this->table('vendor_referral_settings');
        $this->displayField('referral_level_name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasOne('ReferralSettingGiftCoupons', [
            'foreignKey' => 'vendor_referral_setting_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->requirePresence('referral_level_name', 'create')
            ->notEmpty('referral_level_name');

        $validator
            ->integer('referrer_award_points')
            ->requirePresence('referrer_award_points', 'create')
            ->notEmpty('referrer_award_points');

        $validator
            ->integer('referree_award_points')
            ->requirePresence('referree_award_points', 'create')
            ->notEmpty('referree_award_points');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

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
