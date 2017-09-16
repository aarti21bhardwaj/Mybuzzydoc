<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * Templates Model
 *
 * @property \Cake\ORM\Association\HasMany $IndustryTemplates
 * @property \Cake\ORM\Association\HasMany $TemplateGiftCoupons
 * @property \Cake\ORM\Association\HasMany $TemplatePlans
 * @property \Cake\ORM\Association\HasMany $TemplatePromotions
 * @property \Cake\ORM\Association\HasMany $TemplateSurveys
 * @property \Cake\ORM\Association\HasMany $VendorTemplateMilestones
 * @property \Cake\ORM\Association\HasMany $Vendors
 *
 * @method \App\Model\Entity\Template get($primaryKey, $options = [])
 * @method \App\Model\Entity\Template newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Template[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Template|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Template patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Template[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Template findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TemplatesTable extends Table
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

        $this->table('templates');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->hasMany('IndustryTemplates', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('TemplateGiftCoupons', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('TemplatePlans', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('TemplatePromotions', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('TemplateSurveys', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('VendorTemplateMilestones', [
            'foreignKey' => 'template_id'
        ]);
        $this->hasMany('Vendors', [
            'foreignKey' => 'template_id'
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
            ->notEmpty('name', 'Name cannot be empty')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table','message' => 'Template by this name already exists.']);

        $validator
            ->allowEmpty('review');

        $validator
            ->allowEmpty('referral');

        $validator
            ->allowEmpty('tier');

        $validator
            ->allowEmpty('milestone');

        $validator
            ->allowEmpty('gift_coupon');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['name']));
        return $rules;
    }
}
