<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReferralLeads Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Referrals
 * @property \Cake\ORM\Association\BelongsTo $VendorReferralSettings
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\ReferralLead get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralLead newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralLead[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralLead|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralLead patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralLead[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralLead findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralLeadsTable extends Table
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

        $this->table('referral_leads');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Referrals', [
            'foreignKey' => 'referral_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorReferralSettings', [
            'foreignKey' => 'vendor_referral_settings_id'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReferralStatuses', [
            'foreignKey' => 'referral_status_id',
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
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        $validator
            ->requirePresence('referral_status_id', 'create')
            ->notEmpty('referral_status_id');

        $validator
            ->allowEmpty('parent_name');

        $validator
            ->allowEmpty('notes');

        $validator
            ->allowEmpty('peoplehub_identifier');

        $validator
            ->email('email')
            ->allowEmpty('email');
            

        /*$validator
            ->requirePresence('phone', 'create')
            ->notEmpty('phone');*/

        /*$validator = new Validator();
        // add the provider to the validator
        $validator->provider('fr', 'Localized\Validation\FrValidation');
        // use the provider in a field validation rule
        $validator->add('phone', 'phone', [
            'rule' => 'phone',
            'provider' => 'fr'
        ]);*/

        $validator
            ->allowEmpty('phone')
            ->add('phone', 'phone', [
            'rule' => 'numeric',
            'message'=> 'Invalid phone Number.'
            ]);
        // ->requirePresence('phone', 'create')

        // $validator->add('phone', 'custom', [
        //  'rule' => function ($value, $context) {
        //      if ( preg_match( '/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{3})[\)\.\-\s]*([\d]{3})[\.\-\s]?([\d]{4})$/', $context['data']['phone'] ) ) {
        //          if(strlen($context['data']['phone']) == 10 || $context['data']['phone'] == 15){
        //             return TRUE;
        //         }else{
        //             return FALSE;
        //         }
                
        //     } else {
        //         return FALSE;
        //     }
        // },
        // 'message' => 'Invalid phone Number.'
        // ]);

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

        $validator
            ->requirePresence('preferred_talking_time', 'create')
            ->notEmpty('preferred_talking_time');

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
        //$rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['referral_id'], 'Referrals'));
        $rules->add($rules->existsIn(['vendor_referral_settings_id'], 'VendorReferralSettings'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }
}