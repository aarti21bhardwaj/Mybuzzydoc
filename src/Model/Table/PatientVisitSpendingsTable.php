<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * PatientVisitSpendings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $PeoplehubUsers
 *
 * @method \App\Model\Entity\PatientVisitSpending get($primaryKey, $options = [])
 * @method \App\Model\Entity\PatientVisitSpending newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PatientVisitSpending[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PatientVisitSpending|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PatientVisitSpending patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PatientVisitSpending[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PatientVisitSpending findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PatientVisitSpendingsTable extends Table
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

        $this->table('patient_visit_spendings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
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
            ->integer('amount_spent')
            ->requirePresence('amount_spent', 'create')
            ->notEmpty('amount_spent');

        $validator
            ->integer('points_taken')
            ->requirePresence('points_taken', 'create')
            ->notEmpty('points_taken');

        $validator
            ->boolean('instant_reward_unlocked')
            ->requirePresence('instant_reward_unlocked', 'create')
            ->notEmpty('instant_reward_unlocked');

        return $validator;
    }

    public function beforeMarshal( \Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options){
        if (!isset($data['uuid']) && isset($data['vendor_id']) && isset($data['peoplehub_user_id'])) {
           $data['uuid'] = Text::uuid();
       }
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
