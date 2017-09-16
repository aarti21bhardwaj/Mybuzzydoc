<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Log\Log;


/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
* ReferralLeads Controller
*
* @property \App\Model\Table\VendorRedeemedPointsTable 
*/
class  VendorPatientsController extends ApiController
{ 
  public function initialize(){    
    parent::initialize();
  }

  public function add(){
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->data;
    $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');

    //pr($data); die;
    //pr($vendorPatient); die;
    $this->loadModel('Vendors');
    $vendor = $this->Vendors->findById($this->Auth->user('vendor_id'))->first();

    //pr($vendorPatient); die;

    if(!$data)
      throw new BadRequestException(__('DATA_EMPTY'));

    $vendorPatient = $this->VendorPatients->newEntity();
    $existingData = $this->VendorPatients->find()
    ->where([
      'vendor_id' => $this->request->data['vendor_id'], 
      'patient_peoplehub_id' => $this->request->data['patient_peoplehub_id'],
      'user_id' => $this->Auth->user('id'),
      'patient_name' => $this->request->data['name']
      ])

    ->first();

    if($existingData){

      $this->set('response', $existingData);
      $this->set('_serialize', ['response']);

    }else{

      $vendorPatient = $this->VendorPatients->patchEntity($vendorPatient, $this->request->data);

      if(!$this->VendorPatients->save($vendorPatient)){
        throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
      }
      $url = Router::url('/', true);
      $url = $url.'patient-portal/'.$vendor->id;
      $vendorPatient->link = $url;
      $vendorPatient->org_name = $vendor->org_name;
      $vendorPatient->password = 'Redacted for security purposes.';
      $vendorPatient->username = $this->request->data['username'];
      $event = new Event('RegisteredPatient.onRegistration', $this, [
        'arr' => [
          'hashData' => $vendorPatient,
          'eventId' => 9, //give the event_id for which you want to fire the email
          'vendor_id' => $vendor->id
        ] 
      ]);                
      $this->eventManager()->dispatch($event);

      if(isset($this->request->data['phone']) && $this->request->data['phone'] != ""){

        $liveMode = $this->VendorPatients->Vendors->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                       ->contain(['SettingKeys' => function($q){
                                                                          return $q->where(['name' => 'Live Mode']);
                                                                      }
                                                  ])
                                       ->first()->value;

        if(!$liveMode){

          $response['sms'] = 'Sms not sent as vendor is not live';

        }else{
          
          $this->loadComponent('UrlShortner');

          $shortUrl = $this->UrlShortner->shortenUrl($url);
          $shortUrl = $shortUrl['url'];

          $message = 'Hi '.$this->request->data['name'].', '.$vendor->org_name.' added you to  BuzzyDoc. Log in by visiting '.$shortUrl.' and using Username:'.$this->request->data['username'].' & Password: Redacted for security. Have questions? Reach us at help@buzzydoc.com';

          $this->loadComponent('Bandwidth');

          if($this->Bandwidth->sendMessage($this->request->data['phone'], $message)){

            $response['sms'] = 'Sent';
          
          }else{

            $response['sms'] = 'Error in sending sms';
          
          }

        }

      }

      $response['status'] = 'OK';
      $this->set('response', $response);

      $this->set('_serialize', ['response']);
    }
  }


  public function edit($id = null)
    {
        $vendorPatient = $this->VendorPatients->findById($id)->first();
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!$vendorPatient){

          throw new NotFoundException('Patient is not linked to this vendor.');
        }
        
        $vendorPatient = $this->VendorPatients->patchEntity($vendorPatient, $this->request->data);
        if (!$this->VendorPatients->save($vendorPatient)) {
            $response['message '] = "Vendor Patient could not be saved.";
        }

        $response['message'] = "Saved";

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}