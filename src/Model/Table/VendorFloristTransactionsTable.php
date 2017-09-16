<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;
/**
 * VendorFloristTransactions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorFloristOrders
 * @property \Cake\ORM\Association\BelongsTo $Responses
 *
 * @method \App\Model\Entity\VendorFloristTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorFloristTransaction findOrCreate($search, callable $callback = null, $options = [])
 */
class VendorFloristTransactionsTable extends Table
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

        $this->table('vendor_florist_transactions');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('AuditStash.AuditLog');
        $this->belongsTo('VendorFloristOrders', [
            'foreignKey' => 'vendor_florist_order_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
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
        $rules->add($rules->existsIn(['vendor_florist_order_id'], 'VendorFloristOrders'));

        return $rules;
    }
}
