<?php
namespace App\Controller\PatientPortalApis;

use App\Controller\PatientPortalApis\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Log\Log;

/**
* Legacy Redemptions Controller
*
* @property \App\Model\Table\LegacyRedemptionsTable $legacyRedemptions
*/
class UsersController extends ApiController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
	}

	public function add(){
		
		if(!$this->request->is(['post'])){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!isset($this->request->data['email']) && !isset($this->request->data['guardian_email'])){
			throw new BadRequestException(__("One of the two is required, Email or gaurdian's email"));
		}

		$this->loadModel('VendorSettings');
		$vendorSettings = $this->VendorSettings
		->findByVendorId($this->request->data['vendor_id'])
		->contain(['SettingKeys'])
		->all()
		->indexBy('setting_key.name')
		->toArray();

		$selfSignUpSetting = $vendorSettings['Patient Self Sign Up']['value'];

		if(!$selfSignUpSetting){

			throw new UnauthorizedException(__("Vendor setting for patient self sign up is disabled"));
		}  

		$liveMode = $vendorSettings['Live Mode']['value'];

		$patientFirstName = $this->request->data['first_name'];
		
		$this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
		$this->request->data['name'] = $this->request->data['first_name'].' '.$this->request->data['last_name'];
		$response = $this->PeopleHub->registerPatient($this->request->data['vendor_peopleHub_id'], $this->request->data);
		$vendor = $this->Users->Vendors->findById($this->request->data['vendor_id'])->first();

		if(!$response || !$response->status){
		// pr($response);die;
			$message ="";
			foreach($response->error as $key=>$value)
			{
				$message= $message." ".$value->unique;
			}
			if($message == " This email id is already registered with Us. Kindly login. Username not available."){
				$response->title = "It looks like this email is linked to another account";
				$response->message = "This email is already linked to another patient's account. Kindly login or contact buzzydoc support.";

			}else{
				throw new InternalErrorException(__($message));
			}

		}else{

			$data = [
			'patient_peoplehub_id' => $response->data->id,
			'vendor_id' => $this->request->data['vendor_id']
			];

			$vendorPatient = $this->Users->Vendors->VendorPatients->newEntity();

			$vendorPatient = $this->Users->Vendors->VendorPatients->patchEntity($vendorPatient, $data);

			if(!$this->Users->Vendors->VendorPatients->save($vendorPatient)){
				throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
			}

		// pr($response);die;
		//Preparing data for sending email
			$url = Router::url('/', true);
			$url = $url.'patient-portal/'.$vendor->id."#";
			$vendorPatient->password = $this->request->data['password'];
			$vendorPatient->link = $url;
			$vendorPatient->org_name = $vendor->org_name;
			$vendorPatient->id = $response->data->id;
			$vendorPatient->name = $response->data->name;
			$vendorPatient->username = $response->data->username;
			if(isset($response->data->guardian_email) && $response->data->guardian_email){
				$vendorPatient->email = $response->data->guardian_email;
			}else if(isset($response->data->email) && $response->data->email){
				$vendorPatient->email = $response->data->email;
			}else{
				$vendorPatient->email = false;
			}
			if($vendorPatient->email){
			//pr($data);die;
				$event = new Event('RegisteredPatient.onRegistration', $this, [
					'arr' => [
					'hashData' => $vendorPatient,
				'eventId' => 9, //give the event_id for which you want to fire the email
				'vendor_id' => $vendor->id
				]
				]);
				$this->eventManager()->dispatch($event);	
			}

		//Code for sending SMS
			if(isset($response->data->phone) && $response->data->name != ""){


				$liveMode = $this->Users->Vendors->VendorSettings->findByVendorId($this->request->data['vendor_id'])
				->contain(['SettingKeys' => function($q){
					return $q->where(['name' => 'Live Mode']);
				}
				])
				->first()->value;

				if(!$liveMode){

					$response->sms = 'Sms not sent as vendor is not live';

				}else{

					$this->loadComponent('UrlShortner');
					$shortUrl = $this->UrlShortner->shortenUrl($url);
					$shortUrl = $shortUrl['url'];

					$message = 'Hi '.$vendorPatient->name.', '.$vendor->org_name.' added you to  BuzzyDoc. Log in by visiting '.$shortUrl.' and using Username:'.$response->data->username.' & Password:'.$this->request->data['password'].'. Have questions? Reach us at help@buzzydoc.com';

					$this->loadComponent('Bandwidth');

					if($this->Bandwidth->sendMessage($response->data->phone, $message)){

						$response->sms = 'Sent';

					}else{
						$response->sms = 'Error in SMS';
					}

				}
			}
		}

		$this->set('response', $response);
		$this->set('_serialize', 'response');
	}

	public function forgotPassword(){

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		if(!$this->request->data){
			throw new BadRequestException(__('EMPTY_NOT_ALLOWED', 'data'));
		}

		$data = $this->request->data;

		if(!isset($data['vendor_id'])  && !$data['vendor_id']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', "Vendor Id"));
		}
		
		if(!isset($data['username'])  && !$data['username']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Username'));
		}

		if(!isset($data['ref'])  && !$data['ref']){
			throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Host'));
		}
		
		$vendor = $this->Users
						->Vendors
						->findById($data['vendor_id'])
						->first();

		$this->loadModel('VendorSettings');

        $liveMode = $this->VendorSettings->findByVendorId($data['vendor_id'])
	                                         ->contain(['SettingKeys' => function($q){
	                                                                            return $q->where(['name' => 'Live Mode']);
	                                                                        }
	                                                    ])
	                                         ->first()->value;
		$this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);

		$peopleHubResponse = $this->PeopleHub->resetPatientPasswordRequest($data['vendor_id'], $data);
		// pr($peopleHubResponse);die;
		// $peopleHubResponse = json_decode($peopleHubResponse);

		if(isset($peopleHubResponse->code) && $peopleHubResponse->code != 200){

			throw new InternalErrorException(__($peopleHubResponse->message));

		} 


		$vendor->reset_link = $data['ref'].'reset_password?resetToken='.$peopleHubResponse->data->token;
		$vendor->ref = $data['ref'];
		$vendor->patient_name = $peopleHubResponse->data->name;
		// $vendor->patient_name = "James Kukreja";
		$vendor->username = $data['username'];
		// $vendor->email = "james.kukreja@twinspark.co";
		$vendor->email = $peopleHubResponse->data->email; 

		$event = new Event('Patient.resetPassword', $this, [
															'arr' =>[
																		'hashData' => $vendor,
																		'eventId' => 10, //give the event_id for which you want to fire the email
																		'vendor_id' => $data['vendor_id']
																	]
		]);
		
		$this->eventManager()->dispatch($event);

		$response['message'] = "Password reset link sent successfully";
		
		$this->set('response', $response);
		$this->set('_serialize', 'response');

		
	}

	public function getReviewLink(){

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException(__('BAD_REQUEST'));
		}
		$data = $this->request->data;
		if(!isset($data['patientId']) || !$data['patientId'] ){
			throw new BadRequestException("PatientId not set");
		}
		if(!isset($data['vendorId']) || !$data['vendorId'] ){
			throw new BadRequestException("VendorId not set");
		}
		if(!isset($data['email']) || !$data['email'] ){
			throw new BadRequestException("Email not set");
		}
		$vendorId = $data['vendorId'];
		$this->loadModel('ReviewRequestStatuses');
		$reviewRequestStatus = $this->ReviewRequestStatuses
									->findByPeopleHubIdentifier($data['patientId'])
									->where(['user_id IS NULL'])
									->andWhere(['VendorLocations.vendor_id' => $vendorId])
									->contain(['VendorLocations'=> function($q) use($vendorId){
												return $q->where(['VendorLocations.vendor_id' => $vendorId, 'VendorLocations.is_default' => true]);
											}])
									->last();
		if(!$reviewRequestStatus){

			$reviewRequestStatus = $this->_generateReviewRequest($data['email'],$data['patientId'], $vendorId);
			$reviewRequestStatus = $this->ReviewRequestStatuses
										->findById($reviewRequestStatus->id)
										->contain(['VendorLocations'])
										->last();
		}
		if(!isset($reviewRequestStatus->vendor_location) || !$reviewRequestStatus->vendor_location){
			throw new NotFoundException("No Review Request found for this patient by this vendor");
		}

		$string = $reviewRequestStatus->email_address.':'.$reviewRequestStatus->id;
		$key = hash('sha256', $string);
		$url = Router::url('/', true);
		$url = $url.'vendor-reviews/add?key='.$key.'&id='.$reviewRequestStatus->id;
        
        $response = ['status' => true,'reviewUrl' => $url]; 
        $this->set('response', $response);
		$this->set('_serialize', 'response');

	}

	private function _generateReviewRequest($email,$patientId,$vendorId){
      
      $vendorLocation = $this->Users->Vendors->VendorLocations->findByVendorId($vendorId)->where('is_default')->first();
      if(!$vendorLocation){
      	throw new NotFoundException('No default location exists for this practice');
      }
      $data = [
                'vendor_location_id' => $vendorLocation->id,
                'people_hub_identifier' => $patientId,
                'email_address' => $email
              ];
      $reviewRequest = $this->Users->ReviewRequestStatuses->newEntity();
      $reviewRequest = $this->Users->ReviewRequestStatuses->patchEntity($reviewRequest, $data);
      if(!$this->Users->ReviewRequestStatuses->save($reviewRequest)){
        Log::write('error', 'Review Request could not be created for patient id '. $patientId.' '.json_encode($reviewRequest));
      	throw new NotFoundException('Error in saving review request.');	
      }

      return $reviewRequest;
    }
}
