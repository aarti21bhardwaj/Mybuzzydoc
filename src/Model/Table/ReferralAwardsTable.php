<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReferralAwards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Referrals
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $PeoplehubTransactions
 *
 * @method \App\Model\Entity\ReferralAward get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralAward newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralAward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralAward|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralAward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralAward[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralAward findOrCreate($search, callable $callback = null)
 */
class ReferralAwardsTable extends Table
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

        $this->table('referral_awards');
        $this->displayField('id');
        $this->primaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');
        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
        ]);

        $this->belongsTo('Referrals', [
            'foreignKey' => 'referral_id',
            'joinType' => 'INNER'
        ]);
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
            ->integer('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

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
        $rules->add($rules->existsIn(['referral_id'], 'Referrals'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
