<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Date;

/**
 * PatientReferralTiers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ReferralTiers
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\PatientReferralTier get($primaryKey, $options = [])
 * @method \App\Model\Entity\PatientReferralTier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PatientReferralTier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PatientReferralTier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PatientReferralTier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PatientReferralTier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PatientReferralTier findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PatientReferralTiersTable extends Table
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

        $this->table('patient_referral_tiers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReferralTiers', [
            'foreignKey' => 'referral_tier_id',
            'joinType' => 'INNER'
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
            ->integer('referrals')
            ->requirePresence('referrals', 'create')
            ->notEmpty('referrals');

        $validator
            ->integer('peoplehub_identifier')
            ->requirePresence('peoplehub_identifier', 'create')
            ->notEmpty('peoplehub_identifier');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->dateTime('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->dateTime('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

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
        // $rules->add($rules->existsIn(['referral_tier_id'], 'ReferralTiers'));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }

     public function calculate($patientId, $vendorId){

        //Init Response
        $response = [

                        'yearChange' => false,
                        'year' =>false,
                        'tierChange' => false,
                        'newTierId' => false,
                        'newTierName' => false,
                        'points' => false
                    ];

        //Find last patient tiers table entry for the patient
        $lastPatientReferralTier = $this->findByPeoplehubIdentifier($patientId)
                   ->where(['vendor_id' => $vendorId])
                   ->last();

        // pr($lastPatientReferralTier);die;
        //If entry not gound then it is a new patient
        if(!$lastPatientReferralTier){

            //Data for the first time entry for a patient
            $patientReferralTier = [
                                        'referrals' => 1,
                                        'year' => 0,
                                        'start_date' => Date::now(),
                                        'end_date' => Date::now()->modify('+364 days'),
                                        'peoplehub_identifier' => $patientId,
                                        'referral_tier_id' => 0,
                                        'vendor_id' => $vendorId
                                    ];


        }else{
            //if an entry is found then prepare data accordingly
            $patientReferralTier = [
                                        'referrals' => $lastPatientReferralTier->referrals+1,
                                        'year' => $lastPatientReferralTier->year,
                                        'start_date' => $lastPatientReferralTier->start_date,
                                        'end_date' => $lastPatientReferralTier->end_date,
                                        'peoplehub_identifier' => $lastPatientReferralTier->peoplehub_identifier,
                                        'referral_tier_id' => $lastPatientReferralTier->referral_tier_id,
                                        'vendor_id' => $vendorId
                                    ];

            //Get the current year for the patient
            $year = $this->_getYear($patientReferralTier); 

            //Check if year has changed for the patient
            if($year > $patientReferralTier['year']){
                $response['yearChange'] = true;
                $response['year'] = $year;
                $patientReferralTier['year'] = $year;
                
                $yearDifference = $year - $lastPatientReferralTier->year;
                $patientReferralTier['start_date'] = $patientReferralTier['start_date']->modify('+'.$yearDifference.'years');
                $patientReferralTier['end_date'] = $patientReferralTier['end_date']->modify('+'.$yearDifference.'years');

                $patientReferralTier['referrals'] = 1;
                
                //if the year has changed and it is greater than 1 were year count starts from 0
                //check if the tier has been maintained
                if($year > 1){
                    //if the difference between current year and the last time patient brought a referral is > 1
                    //then patient didnt bring any referral in previous year, reset referral tier for patient
                    if($yearDifference > 1){

                        $patientReferralTier['referral_tier_id'] = 0;

                    }else{
                        //if difference is not greater than one then check wether the tier was maintained
                        $patientReferralTier['referral_tier_id'] = $this->_checkTierMaintained($patientReferralTier, $vendorId);   
                    }
                }

            }
            
        }

        $totalReferrals = $patientReferralTier['referrals'];


        $referralTier = $this->_calculateReferralTier($totalReferrals, $vendorId);

        $currentTierReferrals = 0;
        if($patientReferralTier['referral_tier_id'] != 0){

            $currentTier = $this->ReferralTiers->findById($patientReferralTier['referral_tier_id'])->first();
            if($currentTier)
                $currentTierReferrals = $currentTier->referrals_required; 
        }
        if($referralTier && $referralTier->referrals_required > $currentTierReferrals){
        
            $response['tierChange'] = true;
            $response['points'] = $referralTier->points;
            $response['newTierId'] = $referralTier->id; 
            $response['newTierName'] = $referralTier->name;   
            $patientReferralTier['referral_tier_id'] = $referralTier->id;
        }


        $patientReferralTier = $this->newEntity($patientReferralTier);

        if(!$this->save($patientReferralTier)){
            return false;
        }

        return $response;
    }

    //function to check the current year of the patient
    private function _getYear($data){

        // $today = new Date('2023-03-19');
        $today = Date::now();

        if($today <= $data['end_date'] && $today >= $data['start_date'])
        {
            return $data['year'];
        }
        
        $today = strtotime($today);
        $lastStartDate = strtotime($data['start_date']); 

        $diff = (int) (($today-$lastStartDate)/(60*60*24*365)) + $data['year'];
        return $diff;
        
    }

    //function to check wether the tier was maintained
    private function _checkTierMaintained($data, $vendorId){

        $referralsInLastYear = $this->findByPeoplehubIdentifier($data['peoplehub_identifier'])
                                    ->where(['vendor_id' => $vendorId, 'start_date >=' => $data['start_date']->modify('-2 years') , 'end_date <=' => $data['end_date']->modify('-2 years')])
                                    ->last();
        if(!$referralsInLastYear){
            $referralsInLastYear = 0;
        }

        $referralsInFollowingYear = $this->findByPeoplehubIdentifier($data['peoplehub_identifier'])
                                    ->where(['vendor_id' => $vendorId,'start_date >=' => $data['start_date']->modify('-1 years') , 'end_date <=' => $data['end_date']->modify('-1 years')])
                                    ->last();

        if(!$referralsInFollowingYear){
            $referralsInFollowingYear = 0;
        }

        if($referralsInFollowingYear['referrals'] >= $referralsInLastYear['referrals']){

            return $referralsInFollowingYear['referral_tier_id'];
        
        }

        $referralTier = $this->_calculateReferralTier($referralsInFollowingYear['referrals'], $vendorId);
        
        if(!$referralTier){
            return $referralTier->id;
        }
        
        return 0;
    }

    //Calculate the referral tier for the patient
    private function _calculateReferralTier($referralsCount, $vendorId){

        $referralTier = $this->ReferralTiers
                             ->findByVendorId($vendorId)
                             ->where(['referrals_required <=' => $referralsCount ])
                             ->order(['referrals_required' => 'DESC'])
                             ->first();
        if(!$referralTier){

           return 0;
        }

        return $referralTier;
    }
}
