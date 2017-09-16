<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorEmailSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $EmailLayouts
 * @property \Cake\ORM\Association\BelongsTo $EmailTemplates
 * @property \Cake\ORM\Association\BelongsTo $Events
 *
 * @method \App\Model\Entity\VendorEmailSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorEmailSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorEmailSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorEmailSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorEmailSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorEmailSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorEmailSetting findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorEmailSettingsTable extends Table
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

        $this->table('vendor_email_settings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('EmailLayouts', [
            'foreignKey' => 'email_layout_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('EmailTemplates', [
            'foreignKey' => 'email_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Events', [
            'foreignKey' => 'event_id',
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
            ->allowEmpty('subject');

        $validator
            ->allowEmpty('body');

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
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->existsIn(['email_layout_id'], 'EmailLayouts'));
        $rules->add($rules->existsIn(['email_template_id'], 'EmailTemplates'));
        $rules->add($rules->existsIn(['event_id'], 'Events'));
        $rules->add($rules->IsUnique(['vendor_id', 'event_id'], false));
        return $rules;
    }
}
