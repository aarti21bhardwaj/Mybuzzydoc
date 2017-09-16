<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Hpatoio\Bitly\Plugin;
use Cake\Log\Log;

/**
 * UrlShortner component
 */
class UrlShortnerComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function shortenUrl($url){

      $my_bitly = new \Hpatoio\Bitly\Client("d6e8a8ee9a6aba44b9aefe94f2ea350ceac20a90");
      try{

      	// $url= "http://www.google.com";
        $response = $my_bitly->shorten(["longUrl" => $url]);
        return $response;
      
      }catch(Exception $e){

        Log::write('debug', 'Short url could not be generated'.json_encode($e));
        
      }
      return false;

    }
}
