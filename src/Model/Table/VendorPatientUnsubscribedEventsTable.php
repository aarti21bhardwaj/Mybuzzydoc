<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VendorPatientUnsubscribedEvents Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorPatients
 * @property \Cake\ORM\Association\BelongsTo $Events
 *
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatientUnsubscribedEvent findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorPatientUnsubscribedEventsTable extends Table
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

        $this->table('vendor_patient_unsubscribed_events');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('VendorPatients', [
            'foreignKey' => 'vendor_patient_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Events', [
            'foreignKey' => 'event_id',
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
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['vendor_patient_id'], 'VendorPatients'));
        $rules->add($rules->existsIn(['event_id'], 'Events'));

        return $rules;
    }
}
