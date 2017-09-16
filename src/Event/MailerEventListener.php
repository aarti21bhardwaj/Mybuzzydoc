<?php 
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception;
use Cake\Controller\Controller;

class MailerEventListener implements EventListenerInterface {

	use MailerAwareTrait;

	public function __construct(){
    $controller = new Controller();
    $this->VendorEmailSettings = $controller->loadModel('VendorEmailSettings');
    $this->VendorPatients = $controller->loadModel('VendorPatients');
  }

	public function implementedEvents()
	{
	    return [
	        'User.afterCreate' => 'onRegistration',
	        'Review.requestSent' => 'sendReviewRequest',
	        'User.resetPassword' => 'sendResetPasswordUrl',
	        'Patient.resetPassword' => 'sendResetPasswordUrl',
	        'Referral.requestSent' => 'sendReferralRequest',
	        'CreditCard.afterCharge.success' => 'confirmCreditCardCharge',
	        'CreditCard.afterCharge.failure' => 'confirmCreditCardCharge',
	        'RegisteredPatient.onRegistration' => 'onRegistration',
	        'InstantGiftCoupon.requestSent' => 'sendInstantGiftCouponRequest',
	        'SendFlower.orderQueued' => 'flowerOrderApproval',
	        'patientReportedOutcomes.sendLink' => 'sendReviewRequest',
	        'RedemptionStatus.sendToPatient' => 'sendRedemptionAlert',
	    ];
	}

	public function onRegistration($event, $data){
		// pr($event);
		// pr($data['eventId']); die;
		$status = $this->_checkStatus($data);
		if($status){
			try{
				$this->getMailer('General')->send('sendMail', [$data]);
			}
			catch(Exception $e){
				Log::write('debug', json_encode($e));
			}
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
	}

	public function sendReviewRequest($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
				
	}

	public function sendReferralRequest($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
		
	}

	public function sendResetPasswordUrl($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
	}

	public function confirmCreditCardCharge($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
	}

	/*public function sendStaffReport($event, $data){
		
				$this->getMailer('General')->send('sendMail', [$data]);
			
				Log::write('debug', json_encode($data));	
	}*/
	public function sendInstantGiftCouponRequest($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
	}

	private function _checkStatus($data){
		$vendorPatientId = $this->_patientUnsubscribeEmail($data);

		$emailSettings = $this->VendorEmailSettings->findByEventId($data['eventId'])
												   ->where(['vendor_id' => $data['vendor_id']])
												   ->first();	
		if(isset($emailSettings) && !$vendorPatientId){
			return $emailSettings->status;
		}elseif(!isset($emailSettings) && !$vendorPatientId){
			return true;
		}elseif($vendorPatientId) {
			return false;
		}
	}

	private function _patientUnsubscribeEmail($data){
		if(isset($data['hashData']->peoplehub_user_id)){
			$patientId = $data['hashData']->peoplehub_user_id;
		}else if(isset($data['hashData']->redeemer_peoplehub_identifier)){
			$patientId = $data['hashData']->redeemer_peoplehub_identifier;
		}
		if(isset($patientId)){
			$vendorPatient = $this->VendorPatients->findByVendorId($data['vendor_id'])
											  ->where(['patient_peoplehub_id' =>  $patientId])
									   		  ->contain(['VendorPatientUnsubscribedEvents' => function($q) use($data){
									   					return $q->where(['event_id' => $data['eventId']]);
									   			 }])

									   		  ->first();
			if(isset($vendorPatient['vendor_patient_unsubscribed_events']) && !empty($vendorPatient['vendor_patient_unsubscribed_events'])){
			   return $vendorPatient;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	public function flowerOrderApproval($event, $data){
		// pr($data);
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}

	}

	public function sendRedemptionAlert($event, $data){
		$status = $this->_checkStatus($data);
		if($status){
			$this->getMailer('General')->send('sendMail', [$data]);
			
			Log::write('debug', json_encode($data));	
		}else{
			Log::write('debug', 'Email Disabled for eventId '.json_encode($data['eventId']));
		}
	}

}

?>