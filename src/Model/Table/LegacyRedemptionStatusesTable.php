<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * LegacyRedemptionStatuses Model
 *
 * @property \Cake\ORM\Association\HasMany $LegacyRedemptions
 *
 * @method \App\Model\Entity\LegacyRedemptionStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyRedemptionStatus findOrCreate($search, callable $callback = null)
 */
class LegacyRedemptionStatusesTable extends Table
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

        $this->table('legacy_redemption_statuses');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->hasMany('LegacyRedemptions', [
            'foreignKey' => 'legacy_redemption_status_id'
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
