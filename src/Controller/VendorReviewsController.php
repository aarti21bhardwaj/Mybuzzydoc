<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;

/**
 * VendorReviews Controller
 *
 * @property \App\Model\Table\VendorReviewsTable $VendorReviews
 */
class VendorReviewsController extends AppController
{

    
    public function initialize(){
        parent::initialize();
        $this->Auth->allow(['add', 'view', 'sendMail']);
        $this->loadModel('VendorSettings');
    }
    /**
     * View method
     * It also has the options to share on various sites, copy Review and Notify Clinic
     * 
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {   
        $key=$this->request->query('key');
        if($key)
            $dataHide = 1;
        else
            $dataHide = 0;
        
        $requestId = $this->request->query('id');
        
        $request = $this->VendorReviews->ReviewRequestStatuses->findById($requestId)->first();
    
        if(!$request)
            throw new NotFoundException(__('NOT_FOUND','Review'));
        $vendorReview = $this->VendorReviews->findById($request->vendor_review_id)->contain(['VendorLocations', 'ReviewRequestStatuses'])->first();

        $defaultLocation = $this->VendorReviews->VendorLocations
                       ->findByVendorId($vendorReview['vendor_location']['vendor_id'])
                       ->where(['is_default' => 1]) 
                       ->first();
        
        $publicUrl = Router::url('/', true);
        $publicUrl = $publicUrl.'vendor-reviews/add?id='.$requestId;

        if(!$vendorReview['vendor_location']['is_default'] && isset($defaultLocation))
        {
            if(!$vendorReview['vendor_location']['address'])
            {
                $vendorReview['vendor_location']['address'] = $defaultLocation->fb_url;
            }
            if(!$vendorReview['vendor_location']['fb_url'])
            {
                $vendorReview['vendor_location']['fb_url'] = $defaultLocation->fb_url;
            }
            if(!$vendorReview['vendor_location']['google_url'])
            {
                $vendorReview['vendor_location']['google_url'] = $defaultLocation->google_url;   
            }
            if(!$vendorReview['vendor_location']['yelp_url'])
            {
                $vendorReview['vendor_location']['yelp_url'] = $defaultLocation->yelp_url;   
            }
            if(!$vendorReview['vendor_location']['ratemd_url'])
            {
                $vendorReview['vendor_location']['ratemd_url'] = $defaultLocation->ratemd_url;   
            }
            if(!$vendorReview['vendor_location']['healthgrades_url'])
            {
                $vendorReview['vendor_location']['healthgrades_url'] = $defaultLocation->healthgrades_url;   
            }
            if(!$vendorReview['vendor_location']['yahoo_url'])
            {
                $vendorReview['vendor_location']['yahoo_url'] = $defaultLocation->yahoo_url;   
            }
        }

        // pr($vendorReview);die;
        $this->viewBuilder()
            ->layout('review-form')
            ->template('view');

        
        $reviewSetting = $this->VendorReviews->VendorLocations->Vendors->ReviewSettings
                            ->findByVendorId($vendorReview['vendor_location']['vendor_id'])
                            ->first();
    
        $vendorLocation = $this->VendorReviews->VendorLocations->findById($request->vendor_location_id)
                           ->contain('Vendors') 
                           ->first();

        $this->set(compact('vendorReview', 'vendorLocation', 'publicUrl', 'dataHide', 'reviewSetting'));
        $this->set('_serialize', ['vendorReview']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     * @todo Event to award points for Rating & Review Have to be Fired Here & Authenticate Form using key and id
     */
    public function add()
    {   
        $key=$this->request->query('key');
        $requestId = $this->request->query('id');

        $request = $this->VendorReviews->ReviewRequestStatuses->findById($requestId)->first();
        if(!$request)
            throw new NotFoundException(__('NOT_FOUND', 'Review Form'));
        $vendorLocation = $this->VendorReviews->VendorLocations->findById($request->vendor_location_id
            )->contain(['Vendors.ReviewSettings'])->first();
        if(!$vendorLocation)
            throw new NotFoundException(__('Review link has expired, location not found'));
        $vendorsPeopleHubId = $vendorLocation->vendor->people_hub_identifier;

        $publicUrl = Router::url('/', true);
        $publicUrl = $publicUrl.'vendor-reviews/add?id='.$requestId;

    
        if($request->review == 0 && $request->review == 0){    
            
            $vendorReview = $this->VendorReviews->newEntity();
            if ($this->request->is('post')) {

                if(!($this->request->data['review']) && ($this->request->data['rating'] == 0)) {
                 
                    $this->Flash->error(__('EMPTY_NOT_ALLOWED', 'Review'));
                
                }else{

                    $this->request->data['vendor_location_id'] = $vendorLocation->id;
                    
                    $vendorReview = $this->VendorReviews->patchEntity($vendorReview, $this->request->data);
                    // pr($vendorReview);die;
                    if ($this->VendorReviews->save($vendorReview)) {


                        $vendorId = $vendorLocation->vendor_id;

                        if(isset($vendorReview->review) &&  $request->review != 1){
                            $request->review = 1;
                            $this->_awardPoints('review', $requestId, $vendorId, $vendorsPeopleHubId);
                        }

                        //Fire event for awarding points for Review
                        if(isset($vendorReview->rating) && $request->rating !=1){
                            $request->rating = 1;
                            $this->_awardPoints('rating', $requestId, $vendorId, $vendorsPeopleHubId);
                        }

                        //Fire event for awarding points for rating
                        $request->vendor_review_id = $vendorReview->id;

                        if($this->VendorReviews->ReviewRequestStatuses->save($request))
                        {
                            Log::write('debug', 'Review Request'.$request->id.'Updated');
                        }else{
                            Log::write('debug', 'Revview Request'.$request->id.'Not Updated');
                        }

                        return $this->redirect(['action' => 'view', '?' => ['key' => $key, 'id' => $requestId]]);
                    } else {
                        $this->Flash->error(__('ENTITY_ERROR', 'vendor review'));
                    }
                }
            }

            $this->viewBuilder()
                ->layout('review-form')
                ->template('add');

            $this->set(compact('vendorReview', 'vendorLocation', 'publicUrl'));
            $this->set('_serialize', ['vendorReview']);
        }else{

            return $this->redirect(['action' => 'view', '?' => ['key' => $key, 'id' => $requestId],
                ]);
        }
    }


    private function _awardPoints($reviewTypeName, $reviewRequestStatusId, $vendorId, $vendorsPeopleHubId){
        $this->loadModel('ReviewRequestStatuses');
        $reviewRequestStatus = $this->ReviewRequestStatuses->findById($reviewRequestStatusId)->first();

        $attributeType = 'id';
        $attribute = $reviewRequestStatus->people_hub_identifier;

        $reviewSettingName = $reviewTypeName.'_points'; 
        //TODO: When review_type_id is used in vendor setings, remove this.
        $this->loadModel('ReviewSettings');
        $reviewPoints = $this->ReviewSettings->findByVendorId($vendorId)->select([$reviewSettingName])->first()->$reviewSettingName;

        $rewardType = $this->VendorSettings->findByVendorId($vendorId)->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Credit Type']);
        }])->first()->value;
        
        if(!is_string($rewardType)){
            throw new BadRequestException   (__('INVALID_REWARD_TYPE'));
        }
        $rewardCreditData = ['attribute' => $attribute, 'attribute_type' => $attributeType, 'points' => $reviewPoints, 'reward_type' => $rewardType];

        $liveMode = $this->VendorSettings->findByVendorId($vendorId)
                                         ->contain(['SettingKeys' => function($q){
                                                                            return $q->where(['name' => 'Live Mode']);
                                                                        }
                                                    ])
                                         ->first()->value;
                                         
        
        if($reviewPoints > 0){

            $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
            $provideRewardResponse = $this->PeopleHub->provideReward($rewardCreditData, $vendorsPeopleHubId);
            
            if(!$provideRewardResponse['status']){
                throw new BadRequestException(__('PEOPLEHUB_ERROR'.json_encode($provideRewardResponse['response']->message)));

            }

            $transactionId = $provideRewardResponse['response']->data->id;
        
        }else{

            $transactionId = 0;

        }


        if(isset($reviewRequestStatus->user_id) && $reviewRequestStatus->user_id){
            $userId = $reviewRequestStatus->user_id;
        }else{
            $userId = null;
        }
        $vendorReviewId = $reviewRequestStatus->vendor_review_id;
        
        $this->loadModel('ReviewTypes');
        $reviewTypeId = $this->ReviewTypes->findByName($reviewTypeName)->first()->id;
        
        $this->loadModel('ReviewAwards');
        $reviewAward = ['review_request_status_id' => $reviewRequestStatusId, 'user_id' => $userId, 'points' => $reviewPoints, 'peoplehub_transaction_id' => $transactionId, 'review_type_id' => $reviewTypeId, 'redeemer_peoplehub_identifier' => $reviewRequestStatus->people_hub_identifier, 'vendor_id' => $vendorId];
        
        $reviewAward = $this->ReviewAwards->newEntity($reviewAward);
        
        if($reviewAward->errors()){
            throw new InternalErrorException(__('ENTITY_INTERNAL_ERRORS'));
        }
        if($this->ReviewAwards->save($reviewAward)){
            $this->set('response', ['status' => 'OK', 'data' => ['id' => $reviewAward->id]]);
        }else{
            throw new InternalErrorException(__('ENTITY_ERROR', 'review award'));
        }

        //update points taken in patient_visit_spendings table for both referer  and referee
        $this->loadComponent('InstantRewards', ['liveMode' => $liveMode]);        
        $this->InstantRewards->checkStatus($vendorId, $reviewRequestStatus->people_hub_identifier, $reviewPoints);
    }

    public function sendMail()
    {

        if(Email::deliver('success@simulator.amazonses.com', 'Test', 'Test', ['from' => 'james.kukreja@gmail.com', 'transport' => 'default'])) {
            pr('Mail has been Sent!!!');die;
        }
        else {
            pr('Mail could not be Sent!!!');die;
        }

    } 

}
?>