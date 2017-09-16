<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReferralTierPerks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ReferralTiers
 *
 * @method \App\Model\Entity\ReferralTierPerk get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferralTierPerk newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferralTierPerk[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierPerk|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferralTierPerk patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierPerk[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferralTierPerk findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReferralTierPerksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('referral_tier_perks');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReferralTiers', [
            'foreignKey' => 'referral_tier_id',
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
            ->requirePresence('perk', 'create')
            ->notEmpty('perk');

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
        $rules->add($rules->existsIn(['referral_tier_id'], 'ReferralTiers'));

        return $rules;
    }
}
