<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * ReviewSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\ReviewSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReviewSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReviewSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReviewSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReviewSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewSetting findOrCreate($search, callable $callback = null)
 */
class ReviewSettingsTable extends Table
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

        $this->table('review_settings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

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
            ->integer('review_points')
            ->requirePresence('review_points', 'create')
            ->notEmpty('review_points');

        $validator
            ->integer('rating_points')
            ->requirePresence('rating_points', 'create')
            ->notEmpty('rating_points');

        $validator
            ->integer('fb_points')
            ->requirePresence('fb_points', 'create')
            ->notEmpty('fb_points');

        $validator
            ->integer('gplus_points')
            ->requirePresence('gplus_points', 'create')
            ->notEmpty('gplus_points');

        $validator
            ->integer('yelp_points')
            ->requirePresence('yelp_points', 'create')
            ->notEmpty('yelp_points');

        $validator
            ->integer('ratemd_points')
            ->requirePresence('ratemd_points', 'create')
            ->notEmpty('ratemd_points');

        $validator
            ->integer('yahoo_points')
            ->requirePresence('yahoo_points', 'create')
            ->notEmpty('yahoo_points');

        $validator
            ->integer('healthgrades_points')
            ->requirePresence('healthgrades_points', 'create')
            ->notEmpty('healthgrades_points');

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

        return $rules;
    }
}
