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
class  VendorDocumentsController extends ApiController
{
    public function initialize(){
        
        parent::initialize();
        $this->Auth->allow(['viewVendorDocuments']);
    }
    
    /**
     * View Vendor Documents method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewVendorDocuments($vendorId = null)
    {   
        
        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if($vendorId == null)
            throw new BadRequestException(__('Vendor Id not specified'));

        $this->loadModel('VendorSettings');
        $documentsSetting = $this->VendorSettings->findByVendorId($vendorId)
        ->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Documents']);
        }
        ])
        ->first()->value;
        if(!$documentsSetting){
            throw new BadRequestException(__('Vendor setting for documents is disabled'));   
        } 

        $vendorDocuments = $this->VendorDocuments->findByVendorId($vendorId)->all();
        // pr($vendorDocuments);die;

        if(!$vendorDocuments){
            throw new NotFoundException(__('No documents found for this vendor.'));
        
        }    
        
        $response = [];
        foreach ($vendorDocuments as $key => $value) {
            $response[] = ['name' => $value->name, 'document_url' => $value->document_url];

        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

}

?>
 