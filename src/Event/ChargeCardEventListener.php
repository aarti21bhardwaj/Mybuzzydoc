<?php
namespace App\Event;

use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Network\Exception;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\View\Helper\UrlHelper;
use Cake\Routing\Router;

class ChargeCardEventListener implements EventListenerInterface {


  public function __construct(){
    $controller = new Controller();
    $this->AuthorizeDotNet = $controller->loadComponent('AuthorizeDotNet');
    $this->CreditCardCharges = $controller->loadModel('CreditCardCharges');
    // $this->eventManager = new EventManager();
  }

  public function implementedEvents()
  {
    return [
      'CreditCard.beforeCharge' => 'chargeCreditCard'

    ];
  }

  public function chargeCreditCard($event, $data){
    /*get details by vendor id */
    $amount = $data['hashData']['balance'];
    $user_id = $data['hashData']['user_id'];
    $profileId = $data['hashData']['profileId'];
    $paymentprofileid = $data['hashData']['payment_profileid'];
    $vendor_id = $data['hashData']['vendor_id'];
    $reason = $data['hashData']['reason'];
    $email_bcc = null;
    $email = null;
    if(isset($data['hashData']['bcc']) && !empty($data['hashData']['bcc'])){
      $email_bcc = $data['hashData']['bcc'];
    }
    if(isset($data['hashData']['email']) && !empty($data['hashData']['email'])){
      $email = $data['hashData']['email'];
    }
    $first_name = $data['hashData']['first_name'];
    $transaction_fee = $amount* 0.01;
    $totalFee = $amount + $transaction_fee;

    $chargeCustomerProfile = $this->AuthorizeDotNet->chargeCustomerProfile($profileId, $paymentprofileid, $totalFee);
    if($chargeCustomerProfile['code'] == 'failure'){

      $errorData = array();

      $errorData['user_id'] = $user_id;
      $errorData['vendor_id'] = $vendor_id;
      $errorData['response_code'] = $chargeCustomerProfile['errorData']['response_code'];
      $errorData['description'] = $chargeCustomerProfile['errorData']['description'];
      $errorData['reason'] = $reason;
      $errorData['amount'] = $amount;
      $errorData['email'] = $email;
      $errorData['bcc'] = $email_bcc;
      $errorData['first_name'] = $first_name;
      $creditCardCharges = $this->CreditCardCharges->newEntity();
      $creditCardCharges = $this->CreditCardCharges->patchEntity($creditCardCharges, $errorData);
      $this->CreditCardCharges->save($creditCardCharges);
      Log::write('debug', "Error charging the card".json_encode($chargeCustomerProfile));
      return ['error'=>$errorData];
    }else{
      $data = array();
      $data['user_id'] = $user_id;
      $data['auth_code'] = $chargeCustomerProfile['data']['auth_code'];
      $data['transactionid'] = $chargeCustomerProfile['data']['transaction_id'];
      $data['description'] = $chargeCustomerProfile['data']['description'];
      $data['response_code'] = $chargeCustomerProfile['data']['response_code'];
      $data['vendor_id'] = $vendor_id;
      $data['reason'] = $reason;
      $data['amount'] = $amount;
      $data['transaction_fee'] = $transaction_fee;
      $data['email'] = $email;
      $data['bcc'] = $email_bcc;
      $data['first_name'] = $first_name;
      $creditCardCharges = $this->CreditCardCharges->newEntity();
      $creditCardCharges = $this->CreditCardCharges->patchEntity($creditCardCharges, $data);
      Log::write('debug', "Creditcard Charged successfully: ".json_encode($creditCardCharges));
    }
    if ($this->CreditCardCharges->save($creditCardCharges)) {
      Log::write('debug', "CreditCard successfully saved".json_encode($creditCardCharges));
    }else {
      Log::write('debug', "Error in saving CreditCard".json_encode($data));
    }
    return ['response'=>$data];


  }

}

?>