<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class  PatientAddressesController extends ApiController
{

    public function initialize(){    
        parent::initialize();
    }
    
    public function updateAddress(){

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $patientAddress = $this->PatientAddresses
                               ->findByPatientPeoplehubIdentifier($this->request->data['patient_peoplehub_identifier'])
                               ->first();
        if(!$patientAddress){
            $patientAddress = $this->PatientAddresses->newEntity();
        }

        $patientAddress = $this->PatientAddresses->patchEntity($patientAddress, $this->request->data);

        if(!$this->PatientAddresses->save($patientAddress)){
            throw new InternalErrorException(__('ENTITY_ERROR', 'Patient Address'));
        }

        $this->set('response', ['message' => 'Save Successfully', 'id' => $patientAddress->id]);
        $this->set('_serialize', ['response']);
    }
}

?>
 