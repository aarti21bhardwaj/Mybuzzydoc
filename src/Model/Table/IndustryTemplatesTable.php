<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * IndustryTemplates Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Industries
 * @property \Cake\ORM\Association\BelongsTo $Templates
 *
 * @method \App\Model\Entity\IndustryTemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\IndustryTemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IndustryTemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IndustryTemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndustryTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IndustryTemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IndustryTemplate findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IndustryTemplatesTable extends Table
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

        $this->table('industry_templates');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Industries', [
            'foreignKey' => 'industry_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
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
        $rules->add($rules->existsIn(['industry_id'], 'Industries'));
        $rules->add($rules->existsIn(['template_id'], 'Templates'));

        return $rules;
    }
}
