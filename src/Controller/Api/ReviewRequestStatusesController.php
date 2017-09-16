<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\Collection\Collection;
use Cake\Network\Exception\NotAcceptableException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;


/**
 * ReviewRequestStatuses Controller
 *
 * @property \App\Model\Table\ReviewRequestStatusesTable $Vendors
 */

class ReviewRequestStatusesController extends ApiController
{
	

  public function initialize(){
        
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('VendorSettings');
        $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                         ->contain(['SettingKeys' => function($q){
                                                                            return $q->where(['name' => 'Live Mode']);
                                                                        }
                                                    ])
                                         ->first()->value;
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);

  }

	/**
     * requestReview method (Api)
     * This method creates a review request and generates the link to be sent to the user.
     * Review link event is fired if the link is generated.
     * 
     * @return \Cake\Network\Response containing link to be generated
     * @param user_id, vendor_location_id, email of the people hub user as a ajax call
     * @author James Kukreja
     * @todo People Hub Id in hash key instead of email
     */


  public function requestReview()
    {
        
        if(!$this->request->is('post')){
           throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$this->request->data){
           throw new BadRequestException(__('BAD_REQUEST'));
        } 
        
        $data = $this->request->data;
        if(isset($data['vendor_id'])){
          unset($data['vendor_id']);
        }
        $data['user_id'] = $this->Auth->user('id');
        $data['vendor_id'] = $this->Auth->user('vendor_id');
        // $data['email_address'] = $data['email'];

        $response = $this->ReviewRequestStatuses->sendReviewRequest($data);
        // $response = json_encode($response);
        $this->set([
                'response' => $response,
                '_serialize' => ['response']
            ]);

    }

    public function getPatientReviewInfo($patientId){

      if (!$this->request->is('get')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      if(!$patientId){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Patient Id'));
      }

      $vendorLocations = $this->ReviewRequestStatuses->VendorLocations->findByVendorId($this->Auth->user('vendor_id'))->all();

      if(!$patientId){
        throw new BadRequestException(__('RECORD_NOT_FOUND_IN', 'Vendor Locations'));
      }
      
      $reviewRequests = [];
      $patientReviewInfo = [];
      $reviewTypes = $this->ReviewRequestStatuses
                          ->ReviewAwards
                          ->ReviewTypes
                          ->find()
                          ->all()
                          ->combine('id','name')
                          ->toArray();
      
      // pr($reviewTypes);die;
      
      foreach ($vendorLocations as $key => $value) {

        $reviewRequest = $this->ReviewRequestStatuses
                              ->findByPeopleHubIdentifier($patientId)
                              ->contain(['VendorReviews', 'ReviewAwards', 'VendorLocations'])
                              ->where([
                                        'VendorLocations.vendor_id' => $this->Auth->user('vendor_id'), 
                                        'ReviewRequestStatuses.vendor_location_id' => $value->id
                                      ])
                              ->last();
        //Is there any request that exists for this location
        if($reviewRequest){


          $reviewRequest->gplus = $reviewRequest->google_plus;
          //If any Rewards have been awarded in respect to this request
          if($reviewRequest->review_awards){

            $reviewRequest['review_awards'] = (new Collection($reviewRequest['review_awards']))->indexBy('review_type_id')->toArray();
          }
          // pr($reviewRequest->vendor_review->review);die;
          $patientReviewInfo[$value->id]= [

            'id' => $reviewRequest->id,
            'created' => $reviewRequest->created->i18nFormat('yyyy-MM-dd'),
            'review' => isset($reviewRequest->vendor_review->review) && $reviewRequest->vendor_review->review != "" ? $reviewRequest->vendor_review->review : 0,
            'rating' => isset($reviewRequest->vendor_review->rating) && $reviewRequest->vendor_review->rating != "" ? $reviewRequest->vendor_review->rating : 0

          ];

          //Notified array lists all the locations on which user has shared the review 
          $patientReviewInfo[$value->id]['notified'] = [];
          $patientReviewInfo[$value->id]['review_awards'] = []; 

          foreach ($reviewTypes as $key1 => $value1) {
            $patientReviewInfo[$value->id]['notified'][$value1] = $reviewRequest->$value1 ? $reviewRequest->$value1 : 0;

            if(isset($reviewRequest['review_awards'][$key1])){

              $patientReviewInfo[$value->id]['review_awards'][$value1] = 1;
            
            }else{

              $patientReviewInfo[$value->id]['review_awards'][$value1] = 0;
            }
          }


        }else{

          $patientReviewInfo[$value->id] = false;

        }

      }

      $this->set('patientReviewInfo', $patientReviewInfo);
      $this->set('_serialize', ['patientReviewInfo']);

    }
}

?>