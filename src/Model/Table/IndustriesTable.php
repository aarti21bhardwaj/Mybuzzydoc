<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * Industries Model
 *
 * @property \Cake\ORM\Association\HasMany $IndustryTemplates
 *
 * @method \App\Model\Entity\Industry get($primaryKey, $options = [])
 * @method \App\Model\Entity\Industry newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Industry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Industry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Industry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Industry[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Industry findOrCreate($search, callable $callback = null)
 */
class IndustriesTable extends Table
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

        $this->table('industries');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->hasMany('IndustryTemplates', [
            'foreignKey' => 'industry_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
