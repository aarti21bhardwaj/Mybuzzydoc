<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\Core\Configure;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
* Vendors Model
*
* @property \Cake\ORM\Association\BelongsTo $Templates
* @property \Cake\ORM\Association\HasMany $CreditCardCharges
* @property \Cake\ORM\Association\HasMany $EmailLayouts
* @property \Cake\ORM\Association\HasMany $EmailTemplates
* @property \Cake\ORM\Association\HasMany $GiftCoupons
* @property \Cake\ORM\Association\HasMany $LegacyRedemptions
* @property \Cake\ORM\Association\HasMany $LegacyRewards
* @property \Cake\ORM\Association\HasMany $Promotions
* @property \Cake\ORM\Association\HasMany $ReferralLeads
* @property \Cake\ORM\Association\HasMany $ReferralTemplates
* @property \Cake\ORM\Association\HasMany $Referrals
* @property \Cake\ORM\Association\HasMany $ReviewSettings
* @property \Cake\ORM\Association\HasMany $Tiers
* @property \Cake\ORM\Association\HasMany $Users
* @property \Cake\ORM\Association\HasMany $VendorDepositBalances
* @property \Cake\ORM\Association\HasMany $VendorEmailSettings
* @property \Cake\ORM\Association\HasMany $VendorLocations
* @property \Cake\ORM\Association\HasMany $VendorPlans
* @property \Cake\ORM\Association\HasMany $VendorPromotions
* @property \Cake\ORM\Association\HasMany $VendorRedeemedPoints
* @property \Cake\ORM\Association\HasMany $VendorReferralSettings
* @property \Cake\ORM\Association\HasMany $VendorSettings
* @property \Cake\ORM\Association\HasMany $VendorSurveys
*
* @method \App\Model\Entity\Vendor get($primaryKey, $options = [])
* @method \App\Model\Entity\Vendor newEntity($data = null, array $options = [])
* @method \App\Model\Entity\Vendor[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\Vendor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\Vendor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\Vendor[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\Vendor findOrCreate($search, callable $callback = null)
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class VendorsTable extends Table
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

    $this->table('vendors');
    $this->displayField('org_name');
    $this->primaryKey('id');

    $this->addBehavior('Timestamp');
    $this->addBehavior('AuditStash.AuditLog');

    $this->belongsTo('Templates', [
      'foreignKey' => 'template_id'
    ]);
    $this->hasMany('CreditCardCharges', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('EmailLayouts', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('EmailTemplates', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('GiftCoupons', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('LegacyRedemptions', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('LegacyRewards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('Promotions', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReferralLeads', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReferralTemplates', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('Referrals', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReviewSettings', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('Tiers', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReferralTiers', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('Users', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorDepositBalances', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorEmailSettings', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorLocations', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorPlans', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorPromotions', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorRedeemedPoints', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorRedemptionHistory', [
            'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorReferralSettings', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorSettings', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorSurveys', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorMilestones', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorPatients', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorFreshbooks', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorDocuments', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorLegacyRewards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReferralTierAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('TierAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('SurveyAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('GiftCouponAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReviewAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('PromotionAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ReferralAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('ManualAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('MilestoneLevelAwards', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('OldBuzzydocVendors', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorFloristSettings', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorFloristOrders', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasOne('VendorFloristTransactions', [
      'foreignKey' => 'vendor_id'
    ]);
    $this->hasMany('VendorCardSeries', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->hasMany('VendorAssessmentSurveys', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->hasMany('VendorCardRequests', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->hasMany('ReviewRequestStatuses', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->hasMany('AuthorizeNetProfiles', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->hasMany('VendorInstantGiftCouponSettings', [
            'foreignKey' => 'vendor_id'
        ]);
    $this->addBehavior('Josegonzalez/Upload.Upload', [
      'image_name' => [
        'path' => Configure::read('ImageUpload.uploadPathForVendorLogos'),
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
    ->requirePresence('org_name', 'create')
    ->notEmpty('org_name');

    $validator
    ->allowEmpty('reward_url');

    $validator
    ->boolean('is_legacy')
    ->requirePresence('is_legacy', 'create')
    ->notEmpty('is_legacy', 'Please select the type of Client');

    $validator
    ->integer('people_hub_identifier')
    ->allowEmpty('people_hub_identifier');

    $validator
    ->boolean('status')
    ->allowEmpty('status');

    $validator
    ->dateTime('is_deleted')
    ->allowEmpty('is_deleted');

    $validator
    ->numeric('min_deposit')
    ->allowEmpty('min_deposit');

    $validator
    ->numeric('threshold_value')
    ->allowEmpty('threshold_value');

    $validator
    ->allowEmpty('image_path');

    $validator
    ->allowEmpty('image_name');

    $validator->add('threshold_value', 'comparison', [
    'rule' => function ($value, $context) {
        return intval($value) <= intval($context['data']['min_deposit']);
    },
    'message' => 'Threshold Value cannot be bigger than Minimum Deposit.'
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
    $rules->add(function($entity)
                    {
                        return $entity->min_deposit >= 250; //value of minimum deposit's minimum value
                    }, ['errorField' => 'min_deposit', 'message' => 'Min deposit cannot be lower than 250']
                );
    $rules->add(function($entity)
                    {
                        return $entity->threshold_value >= 125; //value of minimum deposit's minimum value
                    }, ['errorField' => 'threshold_value', 'message' => 'Threshold cannot be lower than 125']
                );

    $rules->add(function ($entity, $options) {
        // Return a boolean to indicate pass/failure
        if(isset($entity->image_name['name'])) {
            $array = explode('.',$entity->image_name['name']);
            return in_array(strtolower(end($array)), ['jpeg','jpg','png']); 
        }

        return true;

    }, 'mimeTypeChecker');
    return $rules;
  }

  public function isAllowedMinDeposit($value, array $context)
  {
      return $value >= 250; //value of minimum deposit's minimum value
  }

  public function isAllowedThreshold($value, array $context)
  {
      return $value >= 250; //value of minimum deposit's minimum value
  }

  public function applyTemplate($entity) {
    $template = $this   ->Templates
    ->findById($entity->template_id)
    ->contain(['TemplatePromotions.Promotions', 'TemplateGiftCoupons.GiftCoupons','TemplateSurveys.Surveys.SurveyQuestions.Questions', 'VendorTemplateMilestones.VendorMilestones.MilestoneLevels' => function($q){
      return $q->contain(['MilestoneLevelRewards', 'MilestoneLevelRules']);
    }])
    ->first();
    //pr($template);  die;
    if($template->review){
      $settings = $this->ReviewSettings->newEntity();
      $settings->vendor_id = $entity->id;
      $settings = $this->ReviewSettings->patchEntity($settings, json_decode($template->review, True));
      if($this->ReviewSettings->save($settings))
      {
        Log::write('debug', 'Review Settings Applied from template');
      }else{

        Log::write('debug', 'Review Settings could not be applied template');
      }
    }

    if($template->referral){
      $referrals = json_decode($template->referral, True);
      foreach ($referrals as $key => $value) {
        $referrals[$key]['vendor_id'] = $entity->id;
      }
      $settings = $this->VendorReferralSettings->newEntities($referrals);

      if($this->VendorReferralSettings->saveMany($settings))
      {
        Log::write('debug', 'Referral Settings Applied from template');
      }else{

        Log::write('debug', 'Referral Settings could not be applied from template');
      }
    }

    if($template->template_gift_coupons){
      $settings = [];
      $giftMap = [];
      foreach ($template->template_gift_coupons as $key => $value) {
        // pr($value); die;

        $settings[$key]['vendor_id'] = $entity->id;
        $settings[$key]['description'] = $value->gift_coupon->description;
        $settings[$key]['expiry_duration'] = $value->gift_coupon->expiry_duration;
        $settings[$key]['points'] = $value->gift_coupon->points;
      }

      // pr($settings); die;
      // Log::write('debug', $settings);

      $settings = $this->GiftCoupons->newEntities($settings);

      if($this->GiftCoupons->saveMany($settings)){

        foreach($settings as $key => $value){

          $giftMap[$template->template_gift_coupons[$key]->gift_coupon_id] = $value->id;
        }


        Log::write('debug', 'Gift Coupons Applied from template');
      }else{

        //pr($settings); die;
        Log::write('debug', 'Gift Coupons could not be applied from template');
      }

    }
    if($template->tier){
      $tiers = json_decode($template->tier, True);
      foreach ($tiers as $key => $value) {
        $tiers[$key]['vendor_id'] = $entity->id;
        if(isset($tiers[$key]['tier_gift_coupon']) && $tiers[$key]['tier_gift_coupon']['gift_coupon_id'] != ''){

          $tiers[$key]['tier_gift_coupon']['gift_coupon_id'] = $giftMap[$tiers[$key]['tier_gift_coupon']['gift_coupon_id']];
        }
      }
      $settings = $this->Tiers->newEntities($tiers, ['associated' => ['TierGiftCoupons']]);

      if($this->Tiers->saveMany($settings))
      {
        Log::write('debug', 'Tiers Settings Applied from template');
      }else{

        Log::write('debug', 'Tiers Settings could not be applied from template');
      }
    }

    if($template->template_promotions){
      $settings = [];
      foreach ($template->template_promotions as $key => $value) {
        $value['promotion']['promotion_id'] = $value['promotion']['id'];
        $value['promotion']['vendor_id'] = $entity->id;
        //pr($value); die;
        unset($value['promotion']['id'], $value['promotion']['created'], $value['promotion']['modified']);
        $settings[] = $value['promotion']->toArray();
      }

      $settings = $this->VendorPromotions->newEntities($settings);
      //pr($settings); die;

      if($this->VendorPromotions->saveMany($settings)){
        Log::write('debug', 'Promotions Applied from template');
      }else{


        Log::write('debug', 'Promotions could not be applied from template');
      }

    }
    if($template->template_surveys){
      $settings = [];
      foreach ($template->template_surveys as $key => $value) {
        // pr($value); die;
        $settings[$key]['survey_id'] = $value->survey->id;
        $settings[$key]['vendor_id'] = $entity->id;
        $settings[$key]['name'] = $value->survey->name;
        foreach ($value->survey->survey_questions as $key1 => $value1) {
          $settings[$key]['vendor_survey_questions'][$key1]['survey_question_id'] = $value1->id;
          // $settings[$key1]['points']
          $settings[$key]['vendor_survey_questions'][$key1]['points'] = $value1->question->points;
        }
      }

      $settings = $this->VendorSurveys->newEntities($settings, ['associated' => ['VendorSurveyQuestions']]);
      //pr($settings); die;

      if($this->VendorSurveys->saveMany($settings, ['associated' => ['VendorSurveyQuestions']])){
        Log::write('debug', 'Surveys Applied from template');
      }else{


        Log::write('debug', 'Surveys could not be applied from template');
      }

    }
    if($template->vendor_template_milestones){
      $settings = [];
      foreach ($template->vendor_template_milestones as $key => $value) {
        // pr($value); die;
        $settings[$key]['vendor_id'] = $entity->id;
        $settings[$key]['name'] = $value->vendor_milestone->name;
        $settings[$key]['fixed_term'] = $value->vendor_milestone->fixed_term;
        $settings[$key]['end_duration'] = $value->vendor_milestone->end_duration;
        foreach ($value['vendor_milestone']['milestone_levels'] as $key1 => $value1) {
          $settings[$key]['milestone_levels'][$key1]['name'] = $value1->name;
          $settings[$key]['milestone_levels'][$key1]['level_number'] = $value1->level_number;
          foreach ($value1['milestone_level_rules'] as $key2 => $value2) {
            $settings[$key]['milestone_levels'][$key1]['milestone_level_rules'][$key2]['level_rule'] = $value2->level_rule;
          }
          foreach ($value1['milestone_level_rewards'] as $key3 => $value3) {
            $settings[$key]['milestone_levels'][$key1]['milestone_level_rewards'][$key3]['reward_type_id'] = $value3->reward_type_id;
            $settings[$key]['milestone_levels'][$key1]['milestone_level_rewards'][$key3]['points'] = $value3->points;
            $settings[$key]['milestone_levels'][$key1]['milestone_level_rewards'][$key3]['amount'] = $value3->amount;
            $settings[$key]['milestone_levels'][$key1]['milestone_level_rewards'][$key3]['reward_id'] = $value3->reward_id;
          }
        }
      }

      $settings = $this->VendorMilestones->newEntities($settings, ['associated' => ['MilestoneLevels', 'MilestoneLevels.MilestoneLevelRewards', 'MilestoneLevels.MilestoneLevelRules']]);
      //pr($settings); die;

      if($this->VendorMilestones->saveMany($settings, ['associated' => ['MilestoneLevels', 'MilestoneLevels.MilestoneLevelRewards', 'MilestoneLevels.MilestoneLevelRules']])){

        Log::write('debug', 'Milestones Applied from template');
      }else{

        //pr($settings); die;
        Log::write('debug', 'Milestones could not be applied from template');
      }

    }
    return true;

  }

  public function findAll(Query $query, array $options)
  {
    return $query->formatResults(function ($results) {
      return $results->map(function ($row) {
        if(isset($row->sandbox_people_hub_identifier) && ($row->sandbox_people_hub_identifier)){
          $row->people_hub_identifier = $row->sandbox_people_hub_identifier;
        }
        return $row;
      });
    });
  }

  public function beforeSave( \Cake\Event\Event $event, $entity, \ArrayObject $options){
     if (isset($entity->sandbox_people_hub_identifier) && isset($entity->people_hub_identifier) && $entity->sandbox_people_hub_identifier == 'sandbox') {
       $entity->sandbox_people_hub_identifier = $entity->people_hub_identifier;
       unset($entity->people_hub_identifier);
     }else if (isset($entity->sandbox_people_hub_identifier) && isset($entity->people_hub_identifier) && $entity->sandbox_people_hub_identifier == 'live') {
       $entity->sandbox_people_hub_identifier = null;
     }else{
       unset($entity->people_hub_identifier);
       unset($entity->sandbox_people_hub_identifier);
     }
  }


}
