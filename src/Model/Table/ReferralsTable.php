<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use App\AuditStashPersister\Traits\AuditLogTrait;
/**
 * Referrals Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $ReferralLeads
 *
 * @method \App\Model\Entity\Referral get($primaryKey, $options = [])
 * @method \App\Model\Entity\Referral newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Referral[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Referral|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Referral patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Referral[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Referral findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralsTable extends Table
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

        $this->table('referrals');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasOne('ReferralLeads', [
            'foreignKey' => 'referral_id'
        ]);

        $this->hasOne('ReferralAwards', [
            'foreignKey' => 'referral_id'
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
            ->email('refer_from')
            ->requirePresence('refer_from', 'create')
            ->notEmpty('refer_from','Please provide a Email');

        $validator
            ->email('refer_to')
            ->allowEmpty('refer_to');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');
        /*$validator
            ->requirePresence('get_template_name', 'create')
            ->notEmpty('get_template_name','helloo');*/

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

        $validator
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        /*$validator
            ->allowEmpty('phone');*/

        $validator
            ->allowEmpty('phone');

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
            ->uuid('uuid')
            ->allowEmpty('uuid');

        return $validator;
    }

    public function beforeMarshal( \Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = Text::uuid();
        }
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