<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorReviews Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VendorLocations
 * @property \Cake\ORM\Association\HasMany $ReviewAwards
 * @property \Cake\ORM\Association\HasMany $ReviewRequestStatuses
 *
 * @method \App\Model\Entity\VendorReview get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorReview newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorReview[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorReview|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorReview patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorReview[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorReview findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorReviewsTable extends Table
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

        $this->table('vendor_reviews');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('VendorLocations', [
            'foreignKey' => 'vendor_location_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReviewAwards', [
            'foreignKey' => 'vendor_review_id'
        ]);
        $this->hasMany('ReviewRequestStatuses', [
            'foreignKey' => 'vendor_review_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->allowEmpty('review');

        $validator
            ->numeric('rating')
            ->allowEmpty('rating');

        $validator
            ->uuid('uuid')
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid');

        return $validator;
    }
    public function beforeMarshal( \Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options){
       if (!isset($data['uuid'])) {
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
        $rules->add($rules->existsIn(['vendor_location_id'], 'VendorLocations'));

        return $rules;
    }
}
