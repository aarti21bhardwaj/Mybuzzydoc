<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;
use Cake\Event\Event;

/**
 * LegacyRedemptions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LegacyRewards
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $LegacyRedemptionStatuses
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $LegacyRedemptionAmounts
 *
 * @method \App\Model\Entity\LegacyRedemption get($primaryKey, $options = [])
 * @method \App\Model\Entity\LegacyRedemption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LegacyRedemption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LegacyRedemption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemption findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LegacyRedemptionsTable extends Table
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

        $this->table('legacy_redemptions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');
        $this->belongsTo('LegacyRewards', [
            'foreignKey' => 'legacy_reward_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LegacyRedemptionStatuses', [
            'foreignKey' => 'legacy_redemption_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('LegacyRedemptionAmounts', [
            'foreignKey' => 'legacy_redemption_id',
            'dependent' => true,
        'cascadeCallbacks' => true,
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
            ->integer('transaction_number')
            ->requirePresence('transaction_number', 'create')
            ->notEmpty('transaction_number');

        $validator
            ->requirePresence('redeemer_name', 'create')
            ->notEmpty('redeemer_name');

        $validator
            ->requirePresence('redeemer_peoplehub_identifier', 'create')
            ->notEmpty('redeemer_peoplehub_identifier');

        return $validator;
    }

    public function afterSaveCommit($event, $entity){

        if($entity->legacy_redemption_status_id == 3 && $entity->isNew()){

                
                $legacyRedemption = $this->findById($entity->id)->contain(['LegacyRewards', 'LegacyRedemptionAmounts', 'Vendors'])->first();

                $notificationData = $this->newEntity();
                $notificationData->practice_name = $legacyRedemption->vendor->org_name;
                $notificationData->redemption_type = $legacyRedemption->legacy_reward->name;
                $notificationData->patient_name = $legacyRedemption->redeemer_name;
                if(isset($legacyRedemption->legacy_redemption_amounts) && $legacyRedemption->legacy_redemption_amounts){

                    $notificationData->points = '$'.$legacyRedemption->legacy_redemption_amounts[0]->amount;
                }else{

                    $notificationData->points = $legacyRedemption->legacy_reward->points.' points';
                }

                $event = new Event('RegisteredPatient.onRegistration', $this, [
                'arr' => [
                            'hashData' => $notificationData,
                            'eventId' => 13, //give the event_id for which you want to fire the email
                            'vendor_id' => $entity->vendor_id
                        ]
                ]);
                $this->eventManager()->dispatch($event);

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
        $rules->add($rules->existsIn(['legacy_reward_id'], 'LegacyRewards'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->existsIn(['legacy_redemption_status_id'], 'LegacyRedemptionStatuses'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
