<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;
use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Log\Log;
/**
 * VendorPatients Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PatientPeoplehubs
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\VendorPatient get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorPatient newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorPatient[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatient|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorPatient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatient[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorPatient findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorPatientsTable extends Table
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

        $this->table('vendor_patients');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('VendorPatientUnsubscribedEvents', [
            'foreignKey' => 'vendor_patient_id'
        ]);

        $controller = new Controller;
        $this->VendorSettings  = $controller->loadModel('VendorSettings');
        $this->UrlShortner = $controller->loadComponent('UrlShortner');
        $this->Bandwidth = $controller->loadComponent('Bandwidth');

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
            ->integer('old_buzzydoc_patient_identifier')
            ->allowEmpty('old_buzzydoc_patient_identifier');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function afterSaveCommit($event, $entity){ 
      // pr($entity); die;
        if($entity->isNew()){
          $vendorPatient = $this->findById($entity->id)->contain(['Vendors.Users' => function($q){
        
                  return $q->where(['role_id' => 2]);
        
                }])->first();
                // pr($vendorPatient); die;
                $url = Router::url('/', true);
                $url = $url.'patient-portal/'.$entity->vendor_id."#";
                $vendorPatient->link = $url;
                $vendorPatient->org_name = $vendorPatient->vendor->org_name;
                $vendorPatient->name = $entity->patient_name;
                $vendorPatient->username = $entity->username;
                $vendorPatient->email = $entity->email;
                $vendorPatient->password = $entity->password;
                // pr($vendorPatient); die;
                if($vendorPatient->email){
                //pr($data);die;
                  $event = new Event('RegisteredPatient.onRegistration', $this, [
                    'arr' => [
                    'hashData' => $vendorPatient,
                    'eventId' => 9, //give the event_id for which you want to fire the email
                    'vendor_id' => $entity->vendor_id
                  ]
                  ]);
                  $this->eventManager()->dispatch($event);  
                }
                 if(isset($entity->phone) && $entity->phone != ""){
        
                  $liveMode = $this->VendorSettings->findByVendorId($entity->vendor_id)->contain(['SettingKeys' => function($q){
                    return $q->where(['name' => 'Live Mode']);
                  }
                  ])
                  ->first()->value;
        
                 if(!$liveMode){
                   $entity->sms = 'Sms not sent as vendor is not live';
                  }else{
                      $shortUrl = $this->UrlShortner->shortenUrl($url);
                      $shortUrl = $shortUrl['url'];
        
                      $message = 'Hi '.$vendorPatient->name.', '.$vendorPatient->org_name.' added you to  BuzzyDoc. Log in by visiting '.$shortUrl.' and using Username:'.$vendorPatient->username.' & Password:'.$vendorPatient->password.'. Have questions? Reach us at help@buzzydoc.com';
        
                      if($this->Bandwidth->sendMessage($entity->phone, $message)){
        
                            $entity->sms = 'Sent';
        
                      }else{
                            $entity->sms = 'Error in SMS';
                      }
                  }
                 
                 }
        
                 if(!$entity->user_id){
                    
                    $notificationData = $this->newEntity();
                    $notificationData->patient_email = $vendorPatient->email;
                    $notificationData->practice_name = $vendorPatient->vendor->org_name;
                    $notificationData->patient_name = $entity->patient_name;
        
                    $event = new Event('RegisteredPatient.onRegistration', $this, [
                      'arr' => [
                      'hashData' => $notificationData,
                      'eventId' => 12, //give the event_id for which you want to fire the email
                      'vendor_id' => $entity->vendor_id
                    ]
                    ]);
                    $this->eventManager()->dispatch($event);
                 }else if($entity->user_id && $entity->email){

                      $vendorLocation = $this->Vendors->VendorLocations->findByVendorId($entity->vendor_id)->where(['is_default' => true])->first();
                      if(!$vendorLocation){
                        Log::write('debug', "Review Request not sent as no default location found for vendor id =".$entity->vendor_id);
                        return;
                      }
                      $reviewData = [
                                      'vendor_id' => $entity->vendor_id,
                                      'vendor_location_id' => $vendorLocation->id,
                                      'people_hub_identifier' => $entity->patient_peoplehub_id,
                                      'email_address' => $vendorPatient->email,
                                      'patient_name' => $vendorPatient->name
                                    ]; 

                      if(isset($entity->phone) && $entity->phone != ""){
                        $reviewData['phone'] = $entity->phone;    
                      }
                      
                      $reviewResponse = $this->Vendors->Users->ReviewRequestStatuses->sendReviewRequest($reviewData);
                      Log::write('debug', json_encode($reviewResponse));
                 }
              }
    }

}
