<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Network\Session;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use App\AuditStashPersister\Traits\AuditLogTrait;
// use Cake\Routing\Router;

/**
 * CreditCardCharges Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\CreditCardCharge get($primaryKey, $options = [])
 * @method \App\Model\Entity\CreditCardCharge newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CreditCardCharge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CreditCardCharge|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CreditCardCharge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CreditCardCharge[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CreditCardCharge findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CreditCardChargesTable extends Table
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

        // $this->Auth->config('authorize', ['Controller']);

        $this->table('credit_card_charges');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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

        $validator
            ->allowEmpty('auth_code');

        $validator
            ->integer('transactionid')
            ->allowEmpty('transactionid');

        $validator
            ->allowEmpty('description');

        $validator
            ->integer('response_code')
            ->allowEmpty('response_code');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }


    public function afterSaveCommit($event, $entity){
        //if card is charged successfully, success event email will fire
        if($entity->response_code == 1){
            $event = new Event('CreditCard.afterCharge.success', $this, [
                'arr' => [
                'hashData' => $entity,
                'eventId' => 5,
                'vendor_id'=>$entity->vendor_id
                ]
                ]);
            $this->VendorDepositBalances = TableRegistry::get('VendorDepositBalances');
            $vendorData = $this->VendorDepositBalances->find()->where(['vendor_id'=>$entity->vendor_id])->first();
            if($vendorData){
              $netAmountToBeUpdated = $entity->amount + $vendorData->balance;  
            }else{
              $netAmountToBeUpdated = $entity->amount;
            }
            
            $data = $this->VendorDepositBalances->updateAll(['balance'=>$netAmountToBeUpdated],['vendor_id'=>$entity->vendor_id]);
            $this->eventManager()->dispatch($event);
              return true;
        }
        //if card is charged fails event email for fail will fire
        else{
            $event = new Event('CreditCard.afterCharge.failure', $this, [
                'arr' => [
                'hashData' => $entity,
                'eventId' => 6,
                'vendor_id'=>$entity->vendor_id
                ]
                ]);
              $this->eventManager()->dispatch($event);
        }

    }


}