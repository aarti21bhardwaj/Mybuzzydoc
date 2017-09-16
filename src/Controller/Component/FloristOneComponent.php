<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Http\Client;
use Cake\Core\Exception\Exception;
use Cake\Error\BaseErrorHandler;
use App\Error\AppError;
use Cake\Core\Configure;

class FloristOneComponent extends Component
{
	private $_host = 'https://www.floristone.com/api/rest/flowershop/';
	
	private function _auth(){
		$username = Configure::read('FloristOne.username'); //Add in the configuration.
		$password = Configure::read('FloristOne.password');
		
		return base64_encode($username.':'.$password);
	}	
	
	public $categories = [
								'Occasions' =>	[
															    'Every Day'  => 'ao',
															    'Birthday'  => 'bd',
															    'Anniversary'  => 'an',
															    'Love & Romance'  => 'lr',
															    'Get Well'  => 'gw',
															    'New Baby'  => 'nb',
															    'Thank You'  => 'ty',
															    'Funeral and Sympathy'  => 'sy',
										    	],
								
								'Products'	=>	[
														    	'centerpieces'	=>	'c',
														    	'one sided arrangements'	=>	'o',
														    	'novelty arrangements'	=>	'n',
														    	'vased arrangements'	=>	'v',
														    	'roses'	=>	'r',
														    	'cut bouquets'	=>	'q',
														    	'fruit baskets'	=>	'x',
														    	'plants'	=>	'p',
														    	'balloons'	=>	'b',
												],

								'Price'		=>	[

    															'Flowers under $60'	=>	'u60',
    															'Flowers between $60 and $80'	=>	'60t80',
    															'Flowers between $80 and $100'	=>	'80t100',
    															'Flowers above $100'	=>	'a100',
    															'Funeral Flowers Under $60'	=>	'fu60',
    															'Funeral Flowers between $60 and $80'	=>	'f60t80',
    															'Funeral Flowers between $80 and $100'	=>	'f80t100',
    															'Funeral Flowers above $100'	=>	'fa100',

												],

								'All'	=> 'all',

								'Holiday' => 	[
															    'Christmas'	=>	'cm',
															    'Easter'	=>	'ea',
															    'Valentines Day'	=>	'vd',
															    'Mothers Day'	=>	'md',
												],
                            ];

    public function getProducts($category, $start = null, $count = null){
    	
    	if($start){
    		$start = '&start='.$start;
    	}

    	if($count){
    		$count = '&count='.$count;
    	}

    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'getproducts?category='.$category.$start.$count );

		$response = json_decode($response->body());
		return $response;
    }

    public function getSingleProduct($code){

    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'getproducts?code='.$code );
		$response = json_decode($response->body());
		return $response;
    }

    public function checkDeliveryDate($zipcode, $date = null){
    	//echo "hello"; die;
    	if($date){
    		$date = '&date='.$date;
    	}

    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'checkdeliverydate?zipcode='.$zipcode.$date );
		$response = json_decode($response->body());
		
		if($date){
			$response = $response->DATE_AVAILABLE;
		}

		return $response;
    }

    public function getTotal($products){

    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'gettotal?products='.$products );
		$response = json_decode($response->body());

		return $response;
    }

    public function placeOrder($customer, $products, $ccinfo, $ordertotal){

    	$data = [
    				'products' => $products, 'customer' => $customer, 'ccinfo' => $ccinfo, 'ordertotal' => $ordertotal
		    	];

    	$http = new Client(['headers' => ['Authorization' => $this->_auth(),'Content-Type' => 'application/json']]);
		$response = $http->post( $this->_host.'placeorder', json_encode($data) );
		$response = json_decode($response->body());

		return $response;
    }

    public function getOrderInfo($orderNo){

    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'getorderinfo?orderno='.$orderNo );
		$response = json_decode($response->body());

		return $response;
    }

    public function getCards($customerId){
    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'payment?customer_id='.$customerId);
		$response = json_decode($response->body());

		return $response;
    }
    
    public function setCards($customerId, $token){
    	$http = new Client(['headers' => ['Authorization' => $this->_auth(),'Content-Type' => 'application/json']]);
    	$response = $http->post($this->_host.'payment?customer_id='.$customerId.'&token='.$token, json_encode(''));
		$response = json_decode($response->body());
		
		return $response;
    }

    public function deleteCards($customerId, $cardId){
    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->delete($this->_host.'payment?customer_id='.$customerId.'&card_id='.$cardId);
		$response = json_decode($response->body());
		
		return $response;
    }

    public function getStripeKey(){
    	$http = new Client(['headers' => ['Authorization' => $this->_auth()]]);
		$response = $http->get( $this->_host.'getstripekey');
		$response = json_decode($response->body());

		return $response;
    }
}