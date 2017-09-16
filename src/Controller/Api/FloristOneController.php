<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Mailer\Email;

/**
 * Florist One Controller
 *
 */
class  FloristOneController extends ApiController
{
    public function initialize(){
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('FloristOne');
        $this->Auth->allow(['getCards', 'getStripeKey', 'floristOrders', 'placeOrder', 'deleteCards']);
        // die('errt');
        // $this->Auth->config('authorize', ['Controller']);
    }


/**
     * View method
     *
     * @return \Cake\Network\Response|void Redirects on successful show the response from AJAX, renders view for the template data.
     */
    public function getProductCategories(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $product = $this->FloristOne->categories;
        $this->set(compact('product'));
        $this->set('_serialize', ['product']);
    }
    
    public function getProductList($category, $start = null, $count = null){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$category){
            throw new BadRequestException(__('BAD_REQUEST'));
        }

        $productList = $this->FloristOne->getProducts($category, $start= null, $count= null);
        $this->set(compact('productList'));
        $this->set('_serialize', ['productList']);
    }

	public function placeOrder($vendorId = null){
		$orders = $this->request->data('orders');
		$cc = $this->request->data('cc');
		$orderTotal = $this->request->data('total');

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!$orders || !$cc || !$orderTotal){

            throw new BadRequestException(__('BAD_REQUEST'));           
        }

         if($this->Auth->user('vendor_id')){
            $vendorId = $this->Auth->user('vendor_id');
            $firstName = $this->Auth->user('first_name');
            $phone = $this->Auth->user('phone');
            $email = $this->Auth->user('email');

        }elseif($vendorId){

            $this->loadModel('Users');
            $customerData = $this->Users->findByVendorId($vendorId)->where(['status' => 1])->first();
            $firstName = $customerData->first_name;
            $phone = $customerData->phone;
            $email = $customerData->email;

        }else{
            throw new BadRequestException(__('BAD_REQUEST'.'Could not retrieve vendor id.'));
        }
        
        $this->loadModel('VendorFloristSettings');
		$vendorDetails = $this->VendorFloristSettings->findByVendorId($vendorId)
													// ->contain(['Vendor'])
													->first();

		$vendorAddress = json_decode($vendorDetails->address);

		$customer = [
						'ZIPCODE' => $vendorAddress->zipcode,
                        'NAME' => $firstName,
						'PHONE' => $phone,
						'ADDRESS2' => '',
						'ADDRESS1' => $vendorAddress->address,
						'CITY' => $vendorAddress->city,
						'STATE' => $vendorAddress->state,
						'COUNTRY' => $vendorAddress->country,
						'IP' => $_SERVER['REMOTE_ADDR'],
						'EMAIL' => $email,
		];
		$customer = json_encode($customer);
		
		foreach ($orders as $key => $order) {
			
			$products[] = [
						        "PRICE" => $order['price'],
						        "CARDMESSAGE" => $order['message'],
						        "RECIPIENT" => [
						            "ZIPCODE" => $order['patient_address']['zipcode'],
						            "PHONE" => $order['patient_address']['phone'],
						            "ADDRESS2" => "",
						            "STATE" => $order['patient_address']['state'],
						            "ADDRESS1" => $order['patient_address']['address'],
						            "NAME" => $order['name'],
						            "COUNTRY" => $order['patient_address']['country'],
						            "INSTITUTION" => "",
						            "CITY" => $order['patient_address']['city']
						        ],
						        "DELIVERYDATE" => $order['delivery_date'],
						        "CODE" => $order['product_code']
							];
		}
        $products = json_encode($products);

	
        if(!$vendorDetails->customer_id){        
            //when the customer is making first order.
            $ccinfo = [
                        "stripe_token" => $cc['stripe_token']
                       ];
        }elseif($vendorDetails->customer_id && isset($cc['stripe_token'])){
            //New card being saved for existing user.
            $ccinfo = [
                        "STRIPE_TOKEN" => $cc['stripe_token'],
                        "CUSTOMER_ID" => $vendorDetails->customer_id,
                       ];
        }else{
            //Existing card for existing user
            $ccinfo = [
                        "CUSTOMER_ID" => $vendorDetails->customer_id,
                        "CARD_ID" => $cc['card_id']
                       ];
        }

		$ccinfo = json_encode($ccinfo);

		$orderDetails = $this->FloristOne->placeOrder($customer, $products, $ccinfo, $orderTotal);

        if(!isset($orderDetails->errors)){
            
            $ids = [];
            $orderStatus = [];
            foreach ($orders as $key => $order) {

                $ids[] = $order['id'];
                $orderStatus[] = [
                                    'id' => $order['id'],
                                    'status' => 1,
                                    'vendor_florist_transactions' => [  
                                                                        [ 'florist_transaction_id' => $orderDetails->ORDERNO]
                                                                    ]
                                    ];
            }
            $this->loadModel('VendorFloristOrders');
            $orderEntries = $this->VendorFloristOrders->find()
                                                        ->where(['id IN' => $ids])
                                                        ->contain(['VendorFloristTransactions'])
                                                        ->all();
            
            $orderEntries = $this->VendorFloristOrders->patchEntities($orderEntries, $orderStatus, ['associated' => 'VendorFloristTransactions']);
            $orderEntries = $this->VendorFloristOrders->saveMany($orderEntries);

            $vendorDetails->customer_id = $orderDetails->CUSTOMER_ID;
            $this->VendorFloristSettings->save($vendorDetails);
        }

        $this->set(compact('orderDetails'));
        $this->set('_serialize', ['orderDetails']);
	}

    public function getOrderInfo($orderNo){

    	$orderDetails = $this->FloristOne->getOrderInfo($orderNo);

    	if($orderDetails){
    		return json_decode($orderDetails);
    	}else{
    		return false;
    	}
    }
    
    public function getSingleProduct($code){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$code){
            throw new BadRequestException(__('BAD_REQUEST'));
        }

        $product = $this->FloristOne->getSingleProduct($code);
        $this->set(compact('product'));
        $this->set('_serialize', ['product']);
   }

    public function checkDeliveryDate($patientPeoplehubIdentifier){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        // $zipcode = "90001";
        $this->loadModel('PatientAddresses');
        $zipcode = $this->PatientAddresses->findByPatientPeoplehubIdentifier($patientPeoplehubIdentifier)->first()->zipcode;
        $deliveryDate = $this->FloristOne->checkDeliveryDate($zipcode, $date= null);
        $this->set(compact('deliveryDate'));
        $this->set('_serialize', ['deliveryDate']);
    }

    public function getTotal($vendorId = null){

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
    	
    	$products = $this->request->data('products');

        if(!$products){
            throw new BadRequestException(__('BAD_REQUEST'));
        }

        $products = json_encode($products);

        $totalAmount = $this->FloristOne->getTotal($products);


        if($this->Auth->user('vendor_id')){
            $vendorId = $this->Auth->user('vendor_id');
        }elseif(!$vendorId){
            throw new BadRequestException(__('BAD_REQUEST'));
        }
        $this->loadModel('VendorFloristSettings');
        $vendorDetails = $this->VendorFloristSettings->findByVendorId($vendorId)
                                                    ->first();

        $this->set(compact('totalAmount','vendorDetails'));
        $this->set('_serialize', ['totalAmount','vendorDetails']);
   }

    public function floristOrders($status, $vendorId = null){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if($this->Auth->user('vendor_id')){
            $vendorId = $this->Auth->user('vendor_id');
        }elseif(!$vendorId){
            throw new BadRequestException(__('BAD_REQUEST'));
        }
        $loggedInUser = $this->Auth->user();
        $this->loadModel('VendorFloristOrders');

        if(isset($loggedInUser) && $loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){

            if($status == 2){
                $where = [];
            }else{
                $where = ['VendorFloristOrders.status'=>$status];
            }

        }else{

            if($status == 2){
                $where = ['VendorFloristOrders.vendor_id' => $vendorId ];
            }else{
                $where = ['VendorFloristOrders.status'=>$status, 'VendorFloristOrders.vendor_id' => $vendorId];
            }
        }
        $vendorFloristOrder = $this->VendorFloristOrders->find()
                                                        ->contain(['PatientAddresses', 'Users', 'VendorFloristTransactions'])
                                                        ->where($where)
                                                        ->all();
        
        $this->set(compact('vendorFloristOrder'));
        $this->set('_serialize', ['vendorFloristOrder']);
   }

   public function addFloristOrders(){

        $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
        $this->request->data['user_id'] = $this->Auth->user('id');
        $this->request->data['status'] = 0;
        $this->loadModel('VendorFloristOrders');
        // pr($this->request->data);  die;
        $floristOrder = $this->VendorFloristOrders->newEntity();
        $floristOrder = $this->VendorFloristOrders->patchEntity($floristOrder, $this->request->data);
        // pr($floristOrder);  die;
        if ($this->VendorFloristOrders->save($floristOrder)) {
            $this->_emailOrderDetails($floristOrder);
            $this->set('floristOrder', $floristOrder);
            $this->set('response', ['status' => "OK"]);
        } else {
            throw new InternalErrorException(__('Internal Error'));
        }
                $data =array();
                $data['status']=true;
                $data['data']['id']=$floristOrder->id;
                $this->set('response',$data);
                $this->set('_serialize', ['response']);
    
    }

    private function _emailOrderDetails($floristOrder){
        // pr($floristOrder); die;
        //create url to send via email
        $url = Router::url('/', true);
        $url = $url.'vendor-florist-orders/makePayment?id='.$floristOrder->id.'&uuid='.$floristOrder->uuid;

        $this->loadModel('PatientAddresses');
        $zipcode = $this->PatientAddresses->findByPatientPeoplehubIdentifier($floristOrder['patient_peoplehub_identifier'])->first()->zipcode;
        $products = [['PRICE'=>$floristOrder['price'], 'RECIPIENT'=>['ZIPCODE'=> $zipcode], 'CODE'=> $floristOrder['product_code']]];
        $products = json_encode($products);
        // pr($products); die;
        $totalAmount = $this->FloristOne->getTotal($products);
        //create the complete url by appending ordertotal and uuid of florist orders to it.
        $url = $url.'&total='.$totalAmount->ORDERTOTAL;

        $this->loadModel('Users');
        $practiceDetails = $this->Users->findByVendorId($this->Auth->user('vendor_id'))->where(['role_id'=>2])->first();

         //Email For florist order approval
            $email = new Email();
            $email->to($practiceDetails->email);
            $email->subject('Flower Order Approval');
            $email->template('flowerOrderApproval');
            
            $email->viewVars([
                                'orderName' => $floristOrder->name,
                                'productCode' => $floristOrder['product_code'],
                                'price' => $floristOrder['price'],
                                'sub_total' => $totalAmount->SUBTOTAL,
                                'serviceCharge' => $totalAmount->FLORISTONESERVICECHARGE,
                                'taxes' => $totalAmount->FLORISTONETAX,
                                'total' => $totalAmount->ORDERTOTAL,
                                'imgLink' => $floristOrder->image_url,
                                'link' => $url
                            ]);
            $email->emailFormat('html');
            $email->send();
    



        // $data = ['link' => $url, 'staff_name' => $practiceDetails->first_name, 'patient_name' => $floristOrder->name, 'price' => $floristOrder['price'], 'product_code' => $floristOrder['product_code'], 'sub_total' => $totalAmount->SUBTOTAL, 'service_charge' =>$totalAmount->FLORISTONESERVICECHARGE, 'tax' => $totalAmount->FLORISTONETAX, 'total' => $totalAmount->ORDERTOTAL, 'email' => $practiceDetails->email,'vendor_id' => $this->Auth->user('vendor_id')];

        // $data = (object)$data;

        // $event = new Event('SendFlower.orderQueued', $this, [
        //     'arr' => [
        //       'hashData' => $data,
        //       'eventId' => 14, //give the event_id for which you want to fire the email
              
        //     ] 
        //   ]);
        //   $this->eventManager()->dispatch($event);

    }

    public function DefaultFloristOrders($vendorId){
        $this->loadModel('VendorFloristSettings');
        $floristOrder = $this->VendorFloristSettings->findByVendorId($vendorId)->first();
        if(!$floristOrder){
           throw new NotFoundException(__('RECORD_NOT_FOUND'));
        }
        if ($this->request->is('post')) {
            $floristOrder = $this->VendorFloristOrders->patchEntity($floristOrder, $this->request->data);
            if ($this->VendorFloristOrders->save($floristOrder)) {
               $this->set('floristOrder', $floristOrder);
               $this->set('response', ['status' => "Updated Successfuly"]);
            } else {
                throw new InternalErrorException(__('Internal Error'));
            }
                $data =array();
                $data['status']=true;
                $this->set('response',$data);
                $this->set('_serialize', ['response']);
        }
    }
   
   public function addDefaultFloristSettings(){
        $this->loadModel('VendorFloristSettings');
        $floristSettings = $this->VendorFloristSettings->findByVendorId($this->Auth->user('vendor_id'))->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
        $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
        $address = ['address'=>$this->request->data['address1'], 'city'=>$this->request->data['city'], 'state'=>$this->request->data['state'], 'zipcode'=>$this->request->data['zipcode'], 'country'=>$this->request->data['country']];

        $address = json_encode($address);
        $this->request->data['address'] = $address;
        if (!$floristSettings) {
         $floristSettings = $this->VendorFloristSettings->newEntity();   
        }
        $floristSettings = $this->VendorFloristSettings->patchEntity($floristSettings, $this->request->data);
      
        if ($this->VendorFloristSettings->save($floristSettings)) {

            $this->set('floristSettings', $floristSettings);
            $this->set('response', ['status' => "OK"]);
        } else {
            throw new InternalErrorException(__('Internal Error'));
        }
    }
                $data =array();
                $data['status']=true;
                $data['data']['id']=$floristSettings->id;
                $this->set('response',$data);
                $this->set('_serialize', ['response']);
    
    }

    public function getDefaultFloristSettings(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $this->loadModel('VendorFloristSettings');
        $floristSettings = $this->VendorFloristSettings->findByVendorId($this->Auth->user('vendor_id'))->first();
        
        if(!$floristSettings){
            $floristSettings = false;
        }
        $this->set(compact('floristSettings'));
        $this->set('_serialize', ['floristSettings']);

    }

    public function getCards($customerId = null)
    {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if(!$customerId){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'customer_id'));
        }

        $cardsData = $this->FloristOne->getCards($customerId);
        $this->set(compact('cardsData'));
        $this->set('_serialize', ['cardsData']);
    }

    public function setCards($customerId, $token)
    {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $response = $this->FloristOne->setCards($customerId, $token);
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function deleteCards($customerId, $cardId)
    {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $response = $this->FloristOne->deleteCards($customerId, $cardId);
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function getStripeKey()
    {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $response = $this->FloristOne->getStripeKey();
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}

?>