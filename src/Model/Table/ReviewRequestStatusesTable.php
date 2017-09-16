<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;
use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Network\Exception\NotAcceptableException;

/**
 * ReviewRequestStatuses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $VendorReviews
 * @property \Cake\ORM\Association\BelongsTo $VendorLocations
 *
 * @method \App\Model\Entity\ReviewRequestStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReviewRequestStatus findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReviewRequestStatusesTable extends Table
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

        $this->table('review_request_statuses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorReviews', [
            'foreignKey' => 'vendor_review_id'
        ]);
        $this->belongsTo('VendorLocations', [
            'foreignKey' => 'vendor_location_id'
        ]);
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReviewAwards', [
            'foreignKey' => 'review_request_status_id'
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
            ->boolean('rating')
            ->allowEmpty('rating');

        $validator
            ->boolean('review')
            ->allowEmpty('review');

        $validator
            ->boolean('fb')
            ->allowEmpty('fb');

        $validator
            ->boolean('google_plus')
            ->allowEmpty('google_plus');

        $validator
            ->boolean('yelp')
            ->allowEmpty('yelp');

        $validator
            ->boolean('ratemd')
            ->allowEmpty('ratemd');

        $validator
            ->boolean('yahoo')
            ->allowEmpty('yahoo');

        $validator
            ->boolean('healthgrades')
            ->allowEmpty('healthgrades');

        $validator
            ->integer('people_hub_identifier')
            ->allowEmpty('people_hub_identifier');

        $validator
            ->requirePresence('email_address', 'create')
            ->notEmpty('email_address');

        return $validator;
    }

    public function sendReviewRequest($data){

        $reviewRequest = $this->newEntity();
        $reviewRequest = $this->patchEntity($reviewRequest, $data);

        if(!$this->save($reviewRequest)){
            throw new NotAcceptableException(__('REQUEST_ERROR'.json_encode($reviewRequest->errors())));
        }


        $string = $data['email_address'].':'.$reviewRequest->id;
        $key = hash('sha256', $string);

        $url = Router::url('/', true);
        $url = $url.'vendor-reviews/add?key='.$key.'&id='.$reviewRequest->id;
// $url = "http://buzzy.twinspark.co/dev/VendorReviews/add?key=".$key."&id=".$reviewRequest->id;
        $shortUrl = false;

        $clinicAddress = $this->VendorLocations
                              ->findById($reviewRequest
                              ->vendor_location_id)
                              ->contain([
                                    'Vendors.ReviewSettings', 
                                    'Vendors.VendorSettings.SettingKeys' => 
                                    function($q){
                                      return $q->where(['name' => 'Live Mode']);
                                  }])
                               ->first();

        $liveMode = $clinicAddress->vendor->vendor_settings[0]->value;

        $reviewSettings = $clinicAddress->vendor->review_settings[0]->toArray();

        $this->ReviewTypes = TableRegistry::get('ReviewTypes');
        $reviewTypes = $this->ReviewTypes->find()->all();
        $points = 0;


        foreach($reviewTypes as $type){

            $points += $reviewSettings[$type->name.'_points'];
        }

        $reviewRequest->points = $points;
        $reviewRequest->link = $shortUrl == false ? $url : $shortUrl['url'];
        $reviewRequest->email = $data['email_address'];
        $reviewRequest->address = $clinicAddress->address;
        $reviewRequest->clinic_name = $clinicAddress->vendor->org_name;

        $event = new Event('Review.requestSent', $this, [
            'arr' => [
                        'hashData' => $reviewRequest,
                        'eventId' => 2, //give the event_id for which you want to fire the email
                        'vendor_id' => $clinicAddress->vendor_id
                    ] 
        ]);
        $controller = new Controller();
        $this->eventManager()->dispatch($event);

        $host = explode('/', $url);
        if(!$liveMode){

            $response['sms'] = 'Sms not sent as vendor is not live.';

        }elseif($host[2] == 'localhost'){

            $response['sms'] = 'Sms not sent as enviornment is local';
        }else{

            $this->UrlShortner = $controller->loadComponent('UrlShortner');
            $shortUrl = $this->UrlShortner->shortenUrl($url);
            $reviewRequest->link = $shortUrl['url'];

        }

        if(isset($data['phone']) && $data['phone'] != null && $shortUrl != false && $liveMode == 1){

            $message = 'Hi '.$reviewRequest->patient_name.', if you want to earn '.$reviewRequest->points.' more points at '.$reviewRequest->clinic_name.', click the link below to quickly leave us a review!! '.$reviewRequest->link;

            $this->Bandwidth = $controller->loadComponent('Bandwidth');
            $sms = $this->Bandwidth->sendMessage($reviewRequest->phone, $message);

            if($sms){
                $response['sms'] = 'Sent';
            }else{
                $response['sms'] = 'Error in SMS';
            }

        }


        $response['url'] = $url;

        return $response;


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
        
        $rules->add($rules->existsIn(['vendor_review_id'], 'VendorReviews'));
        $rules->add($rules->existsIn(['vendor_location_id'], 'VendorLocations'));

        return $rules;
    }
}
