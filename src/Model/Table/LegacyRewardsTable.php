<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\I18n\Time; 
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * LegacyRewards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $RewardCategories
 * @property \Cake\ORM\Association\BelongsTo $ProductTypes
 * @property \Cake\ORM\Association\HasMany $LegacyRedemptions
 *
 * @method \App\Model\Entity\LegacyReward get($primaryKey, $options = [])
 * @method \App\Model\Entity\LegacyReward newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LegacyReward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LegacyReward|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LegacyReward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyReward[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LegacyReward findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LegacyRewardsTable extends Table
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
        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
        ]);
        $this->table('legacy_rewards');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RewardCategories', [
            'foreignKey' => 'reward_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductTypes', [
            'foreignKey' => 'product_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('LegacyRedemptions', [
            'foreignKey' => 'legacy_reward_id'
        ]);
        $this->hasMany('VendorLegacyRewards', [
            'foreignKey' => 'legacy_reward_id'
        ]);
      $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image_name' => [
            'path' => Configure::read('ImageUpload.uploadPath'),
            'fields' => [
            'dir' => 'image_path'
            ],
            'nameCallback' => function ($data, $settings) {
                return time(). $data['name'];
            },
            ],
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

        $validator
        ->integer('points')
        // ->requirePresence('points', 'create')
        ->allowEmpty('points')
        ->add('points', 'naturalNumber', [
            'rule' => array('naturalNumber', true),
            'message' => 'Only natural numbers are allowed.',
            ]);

        $validator
            ->allowEmpty('image_path');

        $validator
            ->allowEmpty('image_name');

        $validator
            ->integer('status')
            ->allowEmpty('status');

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

        $validator
            ->integer('amount')
            // ->requirePresence('amount', 'create')
            ->allowEmpty('amount')
            ->add('amount', 'naturalNumber', [
            'rule' => array('naturalNumber', true),
            'message' => 'Only natural numbers are allowed.',
            ]);
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
        $rules->add($rules->existsIn(['reward_category_id'], 'RewardCategories'));
        $rules->add($rules->existsIn(['product_type_id'], 'ProductTypes'));
        
        $rules->add(function ($entity, $options) {
                // Return a boolean to indicate pass/failure
                if(isset($entity->image_name['name'])) {
                    $array = explode('.',$entity->image_name['name']);
                    return in_array(strtolower(end($array)), ['jpeg','jpg','png']); 
                }

                return true;

            }, 'mimeTypeChecker',['errorField' => 'image_name',
        'message' => 'Select a valid image format!']);

        return $rules;
    }
}
