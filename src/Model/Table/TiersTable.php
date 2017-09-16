<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * Tiers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $PatientTiers
 * @property \Cake\ORM\Association\HasMany $TierAwards
 * @property \Cake\ORM\Association\HasMany $TierPerks
 *
 * @method \App\Model\Entity\Tier get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tier findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TiersTable extends Table
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

        $this->table('tiers');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PatientTiers', [
            'foreignKey' => 'tier_id'
        ]);
        $this->hasMany('TierAwards', [
            'foreignKey' => 'tier_id'
        ]);
        $this->hasMany('TierPerks', [
            'foreignKey' => 'tier_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasOne('TierGiftCoupons', [
            'foreignKey' => 'tier_id'
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
            ->integer('lowerbound')
            ->requirePresence('lowerbound', 'create')
            ->notEmpty('lowerbound');

        $validator
            ->integer('upperbound')
            ->requirePresence('upperbound', 'create')
            ->notEmpty('upperbound');

        $validator
            ->numeric('multiplier')
            ->requirePresence('multiplier', 'create')
            ->notEmpty('multiplier');

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

    //Just Calculate Tier
    public function calTier($amt, $vendorId)
    {
        $newTier = $this->findByVendorId($vendorId)
                        ->select('id', 'multiplier')
                        ->where(['lowerbound <=' => $amt, 'upperbound >=' => $amt])
                        ->first();

        if(!count($newTier)){
            $newTier = $this->findByVendorId($vendorId)->where(['upperbound <=' => $amt])->last();
        }
       
        $tier=$newTier->id;
        $dRate=$newTier->multiplier;
        return [$tier, $dRate];
        
    }

    public function givePoints($tier, $amt_spnt, $amt, $points=0)// Give Points based on the Parameters as well as return Tier and Discount Rate
    {
        $currentTier = $this->findById($tier)->first();
        
        if($amt_spnt){
            $amtInfo= $this->calTier($amt_spnt, $currentTier->vendor_id);
            $amtTier = $this->findById($amtInfo[0])->first();
            $amt_spnt -= ($amtTier->lowerbound-1) ;
            $ubDiff = ($currentTier->upperbound) - (($amtTier->lowerbound)-1);    
        }else{
            $ubDiff = ($currentTier->upperbound) - (($currentTier->lowerbound)-1);
        }
        
        $lastTier = $this->findByVendorId($currentTier->vendor_id)->last();
       
        if(($amt_spnt + $amt) <= $ubDiff || $tier== $lastTier->id)
        {
            $points = $points + ($amt * ($currentTier->multiplier));

            return [
                        'points' => $points, 
                        'tierId' => $currentTier->id,
                        'tierName' => $currentTier->name, 
                        'discount' => $currentTier->multiplier
                   ];

        }else{

            $points = $points + ($ubDiff - $amt_spnt) * ($currentTier->multiplier) ;
            $amt = $amt-($ubDiff-$amt_spnt);
            $amt_spnt = 0;
            $tier=$this->findByVendorId($currentTier->vendor_id)
                        ->where(['lowerbound >=' => ($currentTier->upperbound + 1)])
                        ->first()->id;

            return $this->givePoints($tier, $amt_spnt, $amt, $points);

        }

    }
}
