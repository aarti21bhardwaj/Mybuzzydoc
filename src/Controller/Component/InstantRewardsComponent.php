<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

class InstantRewardsComponent extends Component{

	public $components = ['RequestHandler', 'Bandwidth', 'PeopleHub', 'UrlShortner'];
	
	public function initialize(array $config){
		$this->PeopleHub->initialize($config);
	}

	public function checkStatus($vendorId, $patientsId, $pointsTaken = null, $amountSpent = null){
		//fetch the vendor setting for instant rewards, if not set, return false.
		$vendorSettings = TableRegistry::get('VendorSettings');
		$settings = $vendorSettings->findByVendorId($vendorId)
								   ->where(['setting_key_id' => 20])
								   ->first();

		if(!$settings || !$settings->value){
			return false;
		}

		//initializing variables and data
	    if(!$pointsTaken){
	      $pointsTaken = 0;
	    }
	    if(!$amountSpent){
	      $amountSpent = 0;
	    }
	    $data = ['vendor_id' => $vendorId, 'patient_id' => $patientsId, 'points_taken' => $pointsTaken, 'amount_spent' => $amountSpent];
	    // pr($data); die;
	    Log::write('debug', "Firing event");
	    //Firing event to check if instant rewards have been unlocked or not
	    $event = new Event('InstantGiftCoupon.isAchieved', $this, [
	                        'arr' => [
	                                  'hashData' => $data //give the event_id for which you want to fire the email
	                                  ]
	                ]);
	    $evn = new EventManager();
	    $evn->dispatch($event); 
	    //if instant rewards have been unlocked, call _fireEmailAndSmsForInstantRewards()
	    if($event->result['unlockedNow']){
	      $this->_fireEmailAndSmsForInstantRewards($event->result['entity']);
	    } 

	    Log::write('debug', "Event fired.");
	}

	/*
	*Fire Email And Sms For Instant Rewards Method
	*
	*Fires mailer event and calls Bandwidth component to send the link to access the unlocked rewards, to patients.  
	*/
	private function _fireEmailAndSmsForInstantRewards($spendings){
		//fetch patient details to get the email and phone number.
	      $patientDetails = $this->PeopleHub->patientDetails($spendings->vendor_id, $spendings->peoplehub_user_id);
	      if($patientDetails->status){
	      	$email = $patientDetails->data->email;
	      	$phone = $patientDetails->data->phone;
	      }
	      // pr($patientDetails);die;
	      //create the base url
	      $url = Router::url('/', true);
	      $url = $url.'vendor-instant-gift-coupon-settings/giftCoupons?key='.$spendings->uuid.'&id='.$spendings->id;
	      
	      // $shortUrl = false;
	      //format data to be sent to mailer event listener
	      $spendings->email = $email;
	      $spendings->link = $url;
	      $spendings->patient_name = $patientDetails->data->name;
	      //read the vendor settings from session to get the org_name
	      $session = $this->request->session();
          $vendorSettings = $session->read('VendorSettings');

	      $spendings->org_name = $vendorSettings->org_name;
	      
	      // $spendings->vendor_name = $spendings->vendor->org_name;
	      //Fire mailer event
	      $event = new Event('InstantGiftCoupon.requestSent', $this, [
	        'arr' => [
	          'hashData' => $spendings,
	          'eventId' => 11, //give the event_id for which you want to fire the email
	          'vendor_id' => $spendings->vendor_id
	        ] 
	      ]);

	      $evn = new EventManager();
	      $evn->dispatch($event);
	      
	      $phone = "3526143844"; //phone number for testing
	      if(!$phone){
	        $response['sms'] = 'Sms not sent as vendor is not live';
	       }else{

	        // $this->loadComponent('UrlShortner'); 
	        //call UrlShortner component.
	        $shortUrl = $this->UrlShortner->shortenUrl($url);
	        $spendings->link = $shortUrl['url'];
	        //create the message and call the Bandwidth component to send sms.
	        if(isset($phone) && $phone != null && $shortUrl != false){
	       
	          $message = 'Hi '.'Click the link below to redeem your available gift coupons!!'.$spendings->link;

	          $sms = $this->Bandwidth->sendMessage($phone, $message);

	          if($sms){
	            $response['sms'] = 'Sent';
	          }else{
	            $response['sms'] = 'Error in SMS';
	          }
	        }
	          return $response;
		  }
	}

}

?>