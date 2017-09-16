<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;
use Cake\Cache\Cache;
use Cake\Network\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\Controller\Component\CookieComponent;
use Cake\Network\Session;
use Cake\Core\Configure;
use Freshbooks\FreshBooksApi;


class FreshBooksComponent extends Component{
   
    protected $_tokenizeCcUrl = 'https://paid.freshbooks.com/gateway/authorize/tokenize';

    public function initialize(array $config){
       $this->freshBooks = new FreshBooksApi(Configure::read('freshbooks.api_url'), Configure::read('freshbooks.token'));
       
    }
    /*
    *
    * This function is used to create client profile at freshbooks
    *
    */
    public function createClientProfile($firstName,$lastName,$email,$orgName){       
        $this->freshBooks->setMethod('client.create');
        $this->freshBooks->post(array('client'=> array(
            'first_name'=>$firstName,
            'last_name'=>$lastName,
            'organization'=>$orgName,
            'email' =>$email
            )));
        $this->freshBooks->request();
        if($this->freshBooks->success())
        {
            return ['status'=>true,'response'=>$this->freshBooks->getResponse()];
        }
        else
        {
            return ['status'=>false,'error'=>$this->freshBooks->getError()];
        }
    }
    public function getClientProfile($email){
        $this->freshBooks->setMethod('client.list');
        $this->freshBooks->post(array(
            'email' => $email
            ));
        $this->freshBooks->request();
        if($this->freshBooks->success())
        {
            return ['status'=>true,'response'=>$this->freshBooks->getResponse()];
        }
        else
        {
            return ['status'=>false,'error'=>$this->freshBooks->getError()];
        }
    }

    public function tokenizeCC($ccInfo){
        //Implemented for Authorize.net
        $ccInfo = ['cc_info' => $ccInfo];

        $http = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $response = $http->post( $this->_tokenizeCcUrl,json_encode($ccInfo) );


        $response = json_decode($response->body());

        if(isset($response->error)){
            return false;
        }
        return $response->cc_token;
    }

    public function createRecurringProfile($clientId,$itemDataArray,$ccInfo = false, $ccToken){
       // return ['status'=>true,'response'=>['recurring_id'=>35]];
        $reqData = array(
            'recurring' => array(
                'client_id' => $clientId,
                'frequency'=>'monthly',
                'currency_code'=>'USD',
                'language'=>'en',
                'lines' => array(
                    'line' => $itemDataArray
                    )
            ));
        if($ccInfo){
            $ccInfo = array('cc_info'=>$ccInfo);

            $reqData['recurring']['autobill']=array(
                    'gateway_name'=>'Authorize.net',
                    'card' => array(
                            'cc_token' => $ccToken,
                            'name' => $ccInfo['name'],
                            'expiration' => array(
                                'month'=>$ccInfo['expiry_month'],
                                'year'=>$ccInfo['expiry_year']
                                )
                            )
                    );
        }
        $this->freshBooks->setMethod('recurring.create');
        $this->freshBooks->post($reqData);
        $this->freshBooks->request();
        if($this->freshBooks->success())
        {
            return ['status'=>true,'response'=>$this->freshBooks->getResponse()];
        }
        else
        {
            return ['status'=>false,'error'=>$this->freshBooks->getError()];
        }
    }
    public function updateRecurringProfile($clientId,$recurringId,$itemDataArray,$ccInfo=false){
       
        $this->freshBooks->setMethod('recurring.update');
        $reqData = array(
            'recurring' => array(
                'recurring_id' => $recurringId,
                'client_id' => $clientId,
                'frequency'=>'weekly',
                'currency_code'=>'USD',
                'language'=>'en',
                'lines' => array(
                    'line' => $itemDataArray
                    )
            ));
        if($ccInfo){
            $ccInfo = array('cc_info'=>$ccInfo);
            $result = $this->_tokenizeCC($ccInfo);
            $reqData['recurring']['autobill']=array(
                    'gateway_name'=>'Authorize.net',
                    'card' => array(
                            'cc_token' => $result->cc_token,
                            'name' => $ccInfo['name'],
                            'expiration' => array(
                                'month'=>$ccInfo['expiry_month'],
                                'year'=>$ccInfo['expiry_year']
                                )
                            )
                    );
        }
        $this->freshBooks->post($reqData);
        $this->freshBooks->request();
        if($this->freshBooks->success())
        {
            return ['status'=>true,'response'=>$this->freshBooks->getResponse()];
        }
        else
        {
            return ['status'=>false,'error'=>$this->freshBooks->getError()];
        }
    }
}

?>