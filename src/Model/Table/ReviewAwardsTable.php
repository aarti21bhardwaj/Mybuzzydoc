<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReviewAwards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $PeoplehubTransactions
 * @property \Cake\ORM\Association\BelongsTo $ReviewTypes
 * @property \Cake\ORM\Association\BelongsTo $ReviewRequestStatuses
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\ReviewAward get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReviewAward newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReviewAward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReviewAward|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReviewAward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewAward[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewAward findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReviewAwardsTable extends Table
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

        $this->table('review_awards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
        ]);
        
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReviewTypes', [
            'foreignKey' => 'review_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReviewRequestStatuses', [
            'foreignKey' => 'review_request_status_id',
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
            ->integer('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->integer('redeemer_peoplehub_identifier')
            ->allowEmpty('redeemer_peoplehub_identifier');

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

        $rules->add($rules->existsIn(['review_type_id'], 'ReviewTypes'));
        $rules->add($rules->existsIn(['review_request_status_id'], 'ReviewRequestStatuses'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }
}
