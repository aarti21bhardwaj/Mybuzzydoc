<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReferralStatuses Model
 *
 * @property \Cake\ORM\Association\HasMany $ReferralLeads
 *
 * @method \App\Model\Entity\ReferralStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class ReferralStatusesTable extends Table
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

        $this->table('referral_statuses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->hasMany('ReferralLeads', [
            'foreignKey' => 'referral_status_id'
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
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }
}
