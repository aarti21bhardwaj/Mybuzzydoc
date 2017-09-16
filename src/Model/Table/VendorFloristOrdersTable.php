<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorFloristOrders Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $VendorFloristTransactions
 *
 * @method \App\Model\Entity\VendorFloristOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorFloristOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorFloristOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorFloristOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorFloristOrdersTable extends Table
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

        $this->table('vendor_florist_orders');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
 
        $this->hasOne('PatientAddresses', [
            'foreignKey' => 'patient_peoplehub_identifier',
            'bindingKey' => 'patient_peoplehub_identifier'
        ]);

        $this->hasMany('VendorFloristTransactions', [
            'foreignKey' => 'vendor_florist_order_id'
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
            ->requirePresence('product_code', 'create')
            ->notEmpty('product_code');

        $validator
            ->requirePresence('image_url', 'create')
            ->notEmpty('image_url');

        $validator
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->requirePresence('message', 'create')
            ->notEmpty('message');

        $validator
            ->requirePresence('delivery_date', 'create')
            ->notEmpty('delivery_date');

        $validator
            ->integer('patient_peoplehub_identifier')
            ->requirePresence('patient_peoplehub_identifier', 'create')
            ->notEmpty('patient_peoplehub_identifier');

        return $validator;
    }

    public function beforeMarshal( \Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options){
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
