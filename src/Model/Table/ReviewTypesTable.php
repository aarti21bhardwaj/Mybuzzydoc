<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReviewTypes Model
 *
 * @property \Cake\ORM\Association\HasMany $ReviewAwards
 *
 * @method \App\Model\Entity\ReviewType get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReviewType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReviewType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReviewType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReviewType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewType findOrCreate($search, callable $callback = null)
 */
class ReviewTypesTable extends Table
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

        $this->table('review_types');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->hasMany('ReviewAwards', [
            'foreignKey' => 'review_type_id'
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
