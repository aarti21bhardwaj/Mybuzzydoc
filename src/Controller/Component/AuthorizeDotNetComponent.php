<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE", "");
/**
 * AuthorizeDotNet component
 */
class AuthorizeDotNetComponent extends Component
{
  // const MERCHANT_LOGIN_ID = '59cRWJ2uG';
  // const MERCHANT_TRANSACTION_KEY = '666cv3Q8x9ZUBygj';

  function createCustomerProfile($email, $user_id){
     //pr($email); die();
    // Common setup for API credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(Configure::read('authorizeDotNet.merchantLoginId'));
    $merchantAuthentication->setTransactionKey(Configure::read('authorizeDotNet.merchantTransactionKey'));
    $refId = 'ref' . time();
    
   // Create a Customer Profile Request
   //  1. create a Payment Profile
   //  2. create a Customer Profile   
   //  3. Submit a CreateCustomerProfile Request
   //  4. Validate Profile ID returned

    $customerprofile = new AnetAPI\CustomerProfileType();
    $customerprofile->setDescription("Customer 2 Test PHP");
    $customerprofile->setMerchantCustomerId("M_".$user_id);
    $customerprofile->setEmail($email);
    $request = new AnetAPI\CreateCustomerProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId( $refId);
    $request->setProfile($customerprofile);
    $controller = new AnetController\CreateCustomerProfileController($request);
    if(Configure::read('development.env') == 'live'){
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }

    
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
      return ['data' => $response->getCustomerProfileId(), 'code' => 'success'];
    }
    else
    {
      $errorMessages = $response->getMessages()->getMessage();
      return ['error' => "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n", 'code' => 'failure'];
    }
  }


  function getHostedProfilePage($customerprofileid)
  {
    // Common setup for API credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(Configure::read('authorizeDotNet.merchantLoginId'));
    $merchantAuthentication->setTransactionKey(Configure::read('authorizeDotNet.merchantTransactionKey'));
    
    // Use an existing payment profile ID for this Merchant name and Transaction key
    
    $setting = new AnetAPI\SettingType();
    $setting->setSettingName("hostedProfileReturnUrl");
    $setting->setSettingValue(Configure::read('authorizeDotNet.redirectUrl').'/authorize-net-profiles');
    
    $request = new AnetAPI\GetHostedProfilePageRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setCustomerProfileId($customerprofileid);
    $request->addToHostedProfileSettings($setting);
    
    $controller = new AnetController\GetHostedProfilePageController($request);
    if(Configure::read('development.env') == 'staging'){
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }
    
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
      return ['data' => $response->getToken(). "\n", 'code' => 'success'];
    }
    else
    {
      $errorMessages = $response->getMessages()->getMessage();
      return ['error' => "Response : " .$response->getMessages()->getMessage(). "\n", 'code' => 'failure'];
      // echo "ERROR :  Failed to get hosted profile page\n";
      // $errorMessages = $response->getMessages()->getMessage();
      // echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
  }

  function getCustomerProfile($profileIdRequested){
  // Common setup for API credentials

    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(Configure::read('authorizeDotNet.merchantLoginId'));
    $merchantAuthentication->setTransactionKey(Configure::read('authorizeDotNet.merchantTransactionKey'));
    $refId = 'ref' . time();

    // Retrieve an existing customer profile along with all the associated payment profiles and shipping addresses

    $request = new AnetAPI\GetCustomerProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setCustomerProfileId($profileIdRequested);
    $controller = new AnetController\GetCustomerProfileController($request);
    if(Configure::read('development.env') == 'staging'){
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {

      $profileSelected = $response->getProfile();
      $paymentProfilesSelected = $profileSelected->getPaymentProfiles();
      $paymentProfileId= NULL;
      $cards = NULL;
      if(count($paymentProfilesSelected)) {
        $cards = array();
        foreach ($paymentProfilesSelected as $profile){
          $paymentProfileId = $profile->getCustomerPaymentProfileId();
          $cardPayment = $profile->getPayment()->getCreditCard();
          $card['card_number'] = $cardPayment->getCardNumber();
          $card['card_type'] = $cardPayment->getCardType();
          $card['expiration_date'] = $cardPayment->getExpirationDate();
          $cards[] = $card;
        };

      }

      return [
      'data' => [
      'paymentCards' => $cards,
      'paymentProfileId' => $paymentProfileId,
      'totalCards' => count($cards)
      ],
      'code' => 'success'
      ];

    }
    else
    {
      $errorMessages = $response->getMessages()->getMessage();
      return ['error' => "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n", 'code' => 'failure'];
    }
  }

  function chargeCustomerProfile($profileid, $paymentprofileid, $amount){
    // Common setup for API credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(Configure::read('authorizeDotNet.merchantLoginId'));
    $merchantAuthentication->setTransactionKey(Configure::read('authorizeDotNet.merchantTransactionKey'));
    $refId = 'ref' . time();

    $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
    $profileToCharge->setCustomerProfileId($profileid);
    $paymentProfile = new AnetAPI\PaymentProfileType();
    $paymentProfile->setPaymentProfileId($paymentprofileid);
    $profileToCharge->setPaymentProfile($paymentProfile);

    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType( "authCaptureTransaction");
    $transactionRequestType->setAmount($amount);
    $transactionRequestType->setProfile($profileToCharge);
    $settings = new AnetAPI\SettingType();
    $settings->setSettingName('duplicateWindow');
    $settings->setSettingValue('1');
    $transactionRequestType->addToTransactionSettings($settings);

    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId( $refId);
    $request->setTransactionRequest( $transactionRequestType);
    $controller = new AnetController\CreateTransactionController($request);
    if(Configure::read('development.env') == 'staging'){
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
      $tresponse = $response->getTransactionResponse();
      if ($tresponse != null && $tresponse->getMessages() != null)   
        {
          $data = array();
          $data['auth_code'] = $tresponse->getAuthCode();
          $data['transaction_id'] = $tresponse->getTransId();
          $data['description'] = $tresponse->getMessages()[0]->getDescription();
          $data['response_code'] = $tresponse->getMessages()[0]->getCode();
        return [ 
         'data'=>$data,
        'code' => 'success'
        ];
       }
      else
        {
          if($tresponse->getErrors() != null)
          {
            $errorMessages = $tresponse->getErrors()[0]->getErrorText();
            $errorMessages = $tresponse->getErrors()->responseCode();


            return [$errorMessages, 'code' => 'failure'];
          }
        }
    }

      else
      {
        $tresponse = $response->getTransactionResponse();
        if($tresponse != null && $tresponse->getErrors() != null)
        {
          $errorData = array();
          $errorData['response_code'] = $tresponse->getResponseCode();
          $errorData['description'] = $tresponse->getErrors()[0]->getErrorText();

          return ['errorData' =>$errorData, 'code' => 'failure'];

        }
        else
        {
          $errorMessages = $tresponse->getMessages()->getMessage()[0]->getText();

          return [$errorMessages, 'code' => 'failure'];

        }
      }
    }
    
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

  }
