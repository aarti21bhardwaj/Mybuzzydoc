<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;



/**
 * VendorReviews Controller
 *
 * @property \App\Model\Table\VendorReviewsTable $Vendors
 */
//removed extra spaces
class VendorReviewsController extends ApiController
{
	

  public function initialize(){
        
        parent::initialize();
        $this->Auth->allow(['notify']);
        $this->loadComponent('RequestHandler');
  }
	
  /**
     * notify method (Api)
     * This method is called when review is shared on fb or a user wishes to notify the clinic where he has shared reviews
     * If Review is shared on fb then its status is updated in db. Status is also updated in db for other four sites if  * user notifies.
     * 
     * @return \Cake\Network\Response saved & review status if saved. 
     * @param Boolean (gplus, yelp, ratemd, healthgrades, fb, yahoo), UUID vendorReviewId as ajax call
     * @author James Kukreja
     * @todo Event needs to be created to award points for fb & Authenticate whether the key and request id match
     * 
     */

  public function notify()
    {
      if(!$this->request->is('post')){
         throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      if(!$this->request->data){
         throw new BadRequestException(__('BAD_REQUEST'));
      }
     $data = $this->request->data;
     $vendorReview = $this->VendorReviews->findByUuid($data['vendorReviewId'])->first();
     $reviewStatus = $this->VendorReviews->ReviewRequestStatuses->findByVendorReviewId($vendorReview->id)->contain(['VendorLocations.Vendors'])->first();
     $response = array();
     if(!$reviewStatus){

        $response['response'] = __('RECORD_NOT_FOUND');
      }
      if($data['fb'] == "true"){

        if(!$reviewStatus->fb){
          $reviewStatus->fb = (boolean) $data['fb'];
          $this->_review($reviewStatus, 'fb');

        }else{

          throw new BadRequestException(__('POINTS_ALREADY_AWARDED'));

        }

      }else{
        
        if($reviewStatus->google_plus && $reviewStatus->yelp && $reviewStatus->ratemd && $reviewStatus->yahoo && $reviewStatus->healthgrades){

              $response['already_notified'] = true;
              $response['response'] = __('Entity_Notified'); 
        
        }else{
             if(!$reviewStatus->google_plus)
                  $reviewStatus->google_plus = (boolean) $data['gplus'];
             if(!$reviewStatus->yelp)
                  $reviewStatus->yelp = (boolean) $data['yelp'];
             if(!$reviewStatus->ratemd)
                  $reviewStatus->ratemd = (boolean) $data['ratemd'];
             if(!$reviewStatus->healthgrades)
                  $reviewStatus->healthgrades = (boolean) $data['healthgrades'];
             if(!$reviewStatus->yahoo)
                  $reviewStatus->yahoo = (boolean) $data['yahoo'];
        }
      }
      // pr($reviewStatus);die;
      if($this->VendorReviews->ReviewRequestStatuses->save($reviewStatus)){
        //@TODO Fire Event Here to give points for fb

        $response['response'] = __('ENTITY_SAVED', 'Review Request Status');
        $response['data'] = $reviewStatus;
             
      }else{

        $response['response'] = __('ENTITY_ERROR', 'Review Request Status');
      }

      // $response = json_encode($response);
      $this->set([
              'response' => $response,
              '_serialize' => ['response']
      ]);

    }


    private function _review($reviewRequestStatus, $reviewTypeName){
        // pr($this->request->data); die;
        $requestId = $reviewRequestStatus->id;
        $redeemersPeoplehubId = $reviewRequestStatus->people_hub_identifier;
        $attributeType= 'id';
        $vendorId = $reviewRequestStatus->vendor_location->vendor_id;
        $this->loadModel('ReviewSettings');
        $reviewSettingName = $reviewTypeName.'_points'; 
        //TODO: When review_type_id is used in review setings, remove this.

        $attribute= $reviewRequestStatus->email_address;
        $reviewPoints = $this->ReviewSettings->findByVendorId($vendorId)->select([$reviewSettingName])->first()->$reviewSettingName;
        
        if($reviewPoints == 0){

          return true;
        }

        $this->loadModel('VendorSettings');

        $rewardType = $this->VendorSettings->findByVendorId($vendorId)->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Credit Type']);
        }])->first()->value;
        
        if(!is_string($rewardType)){
            throw new BadRequestException(__('INVALID_REWARD_TYPE'));
        }
        $rewardCreditData = ['attribute' => $redeemersPeoplehubId, 'attribute_type' => $attributeType, 'points' => $reviewPoints, 'reward_type' => $rewardType];        

        
        $liveMode = $this->VendorSettings->findByVendorId($vendorId)
                                         ->contain(['SettingKeys' => function($q){
                                                                            return $q->where(['name' => 'Live Mode']);
                                                                        }
                                                    ])
                                         ->first()->value;
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);

        $provideRewardResponse = $this->PeopleHub->provideReward($rewardCreditData, $reviewRequestStatus->vendor_location->vendor->people_hub_identifier);

        if(!$provideRewardResponse['status']){
            throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message)));
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
        $reviewAward = ['review_request_status_id' => $requestId, 'user_id' => $userId, 'points' => $reviewPoints, 'peoplehub_transaction_id' => $provideRewardResponse['response']->data->id, 'review_type_id' => $reviewTypeId, 'redeemer_peoplehub_identifier' => $reviewRequestStatus->people_hub_identifier, 'vendor_id' => $vendorId];
        
        $reviewAward = $this->ReviewAwards->newEntity($reviewAward);
        if($reviewAward->errors()){
            throw new InternalErrorException(__('ENTITY_ERROR', 'reviewAward'));
        }
        if($this->ReviewAwards->save($reviewAward)){
            return true;
        }else{
            throw new InternalErrorException(__('POINTS_NOT_AWARDED'));
        }
        
        //update points taken in patient_visit_spendings table for both referer  and referee
        $this->InstantRewards->checkStatus($vendorId, $$reviewRequestStatus->people_hub_identifier, $reviewPoints);
    }
}


?>