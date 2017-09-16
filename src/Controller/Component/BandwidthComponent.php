<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Catapult;
use Cake\Log\Log;

/**
 * Bandwidth component
 */
class BandwidthComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function sendMessage($phone, $message){

      $length = strlen($message);
      
      if($length <= 160){

        return $this->_send($phone, $message);
      
      }else{

        if($this->_send($phone, substr($message, 0, 159))){
          
          $message = substr($message, 159, $length-159);
          sleep(1);
          return $this->sendMessage($phone, $message);

        }else{

          return false;
          
        }      
      }

    }
    

    private function _send($phone, $message){

      $bandwidth_user_id = Configure::read('Bandwidth.userId');
      $bandwidth_api_token = Configure::read('Bandwidth.apiToken');
      $bandwidth_api_secret = Configure::read('Bandwidth.apiSecret');
      
      // Send a JSON request body.
      $cred = new Catapult\Credentials($bandwidth_user_id, $bandwidth_api_token, $bandwidth_api_secret);
      $client = new Catapult\Client($cred);
      try {
              $message = new Catapult\Message(array(
                  "from" => new Catapult\PhoneNumber(Configure::read('Phone.number')), // Replace with a Bandwidth Number
                  "to" => new Catapult\PhoneNumber('+1'.$phone),
                  "text" => new Catapult\TextMessage($message, false)
              ));
              Log::write('debug', 'Sms sent to '.$phone);
              return true;
      }catch (\CatapultApiException $e) {
          Log::write('error', $e);
          Log::write('debug', 'SMS could not be sent to '.$phone);
          return false;
      }
    }

}


