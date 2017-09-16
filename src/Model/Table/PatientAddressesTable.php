<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PatientAddresses Model
 *
 * @method \App\Model\Entity\PatientAddress get($primaryKey, $options = [])
 * @method \App\Model\Entity\PatientAddress newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PatientAddress[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PatientAddress|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PatientAddress patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PatientAddress[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PatientAddress findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PatientAddressesTable extends Table
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

        $this->table('patient_addresses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->requirePresence('country', 'create')
            ->notEmpty('country');

        $validator
            ->requirePresence('phone', 'create')
            ->notEmpty('phone');

        $validator
            ->integer('zipcode')
            ->requirePresence('zipcode', 'create')
            ->notEmpty('zipcode');

        $validator
            ->integer('patient_peoplehub_identifier')
            ->requirePresence('patient_peoplehub_identifier', 'create')
            ->notEmpty('patient_peoplehub_identifier');

        return $validator;
    }
}
