<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Log\Log;


/**
 * Webhooks Controller
 *
 * @property \App\Model\Table\WebhooksTable $Webhooks
 */
class WebhooksController extends ApiController
{
    
    public function initialize(){

        parent::initialize();
        $this->Auth->allow(['peoplehub']);
    }

    public function freshBooks(){

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        Log::write('debug', 'Freshbooks Webhook call -> '.json_encode($this->request->data));

        die;

        // TODO: Figure out what is being sent from Freshbooks and handle further logic.
    	$this->loadModel('Vendors');
        $data = $this->request->data;
        $disableVendor = true;

        $this->_disableVendor();
    }

    private function _disableVendor(){

            $vendor = $this->Vendors->findByVendorId($vendorId)->first();
            $vendor->status = 0;
            $this->Vendors->save($vendor);
    }

    public function peoplehub(){

        $requestData = $this->request->data;
        $this->loadModel('Vendors');

        $vendor = $this->Vendors->findByPeopleHubIdentifier($requestData['vendor_peoplehub_id'])->first();
        if(!$vendor){
            $vendor = $this->Vendors->findBySandboxPeopleHubIdentifier($requestData['vendor_peoplehub_id'])->first(); 
        }

        if(!$vendor){
            throw new NotFoundException('Vendor Not Found');
        }

        $data = [
                        'patient_peoplehub_id' => $requestData['id'],
                        'vendor_id' => $vendor->id,
                        'user_id' => null,
                        'patient_name' =>  $requestData['name'],
                        'username' =>  $requestData['username'],
                        'password' =>  "Redacted for security purposes"
                    ];

        if(isset($requestData['email']) && $requestData['email']){
            $data['email'] = $requestData['email'];
        }else{
            $data['email'] = $requestData['guardian_email'];
        }

        if(isset($requestData['phone']) && $requestData['phone']){
             $vendorPatient['phone'] = $response->data->phone;
        }

        $vendorPatient = $this->Vendors->VendorPatients->newEntity();

        $vendorPatient = $this->Vendors->VendorPatients->patchEntity($vendorPatient, $data);

        if(!$this->Vendors->VendorPatients->save($vendorPatient)){

            throw new InternalErrorException(__('ENTITY_ERROR', 'vendor patient'));
        }

        $response = [

            'status' => true,
            'data' => $vendorPatient
        ];
        $this->set('response',$vendorPatient);
        $this->set('_serialize', ['response']);
    }

}
