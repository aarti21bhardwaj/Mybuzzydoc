<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorPromotions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $Promotions
 * @property \Cake\ORM\Association\HasMany $PromotionAwards
 *
 * @method \App\Model\Entity\VendorPromotion get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorPromotion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorPromotion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorPromotion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorPromotion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPromotion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPromotion findOrCreate($search, callable $callback = null)
 */
class VendorPromotionsTable extends Table
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

        $this->table('vendor_promotions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'published'
        ]);
        
        $this->belongsTo('Promotions', [
            'foreignKey' => 'promotion_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PromotionAwards', [
            'foreignKey' => 'vendor_promotion_id'
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
            ->dateTime('published')
            ->allowEmpty('published');

        $validator
            ->integer('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->integer('frequency')
            ->allowEmpty('frequency');

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
        $rules->add($rules->existsIn(['promotion_id'], 'Promotions'));

        return $rules;
    }
    /**
     * Returns points awarded to user from vendor promotions
     * custom integrity.
     *
     * @param \Cake\ORM\PointsChecker $points The points object to be modified.
     * @return \Cake\ORM\PointsChecker
     */
    public function rewardPoints($vendor_id, array $data){
        //echo $vendor_id; die('hieh');
        /*This query is to get the id in array for creating subquery*/
        //$query = $this->find()->where(['id' => $ids], ['id' => 'integer[]']);

        /*This query gets all the relevent points releated to login vendor*/
        $vendorPromotions = $this->find('all')
                                            ->where(['id IN' => $ids,
                                            'vendor_id'=>$this->Auth->user('vendor_id')
                                            ])
                                           ->sumOf('points');
        return $vendorPromotions;
        //pr($vendorPromotions); die('sss');    

    }
}
