<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorTemplateMilestones Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Templates
 * @property \Cake\ORM\Association\BelongsTo $VendorMilestones
 *
 * @method \App\Model\Entity\VendorTemplateMilestone get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorTemplateMilestone findOrCreate($search, callable $callback = null)
 */
class VendorTemplateMilestonesTable extends Table
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

        $this->table('vendor_template_milestones');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorMilestones', [
            'foreignKey' => 'vendor_milestone_id',
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
        $rules->add($rules->existsIn(['template_id'], 'Templates'));
        $rules->add($rules->existsIn(['vendor_milestone_id'], 'VendorMilestones'));

        return $rules;
    }
}
