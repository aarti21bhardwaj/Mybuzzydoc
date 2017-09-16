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
class ReferralTemplatesController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|void Redirects on successful show the response from AJAX, renders view for the template data.
    */
    public function index()
    {
        $conditions = null;
        $vendor_id =  $this->request->query('vendor_id');
        if(empty($vendor_id)) {
            $conditions = ['vendor_id' => $vendor_id];
        }
        $this->paginate = [
        'contain' => ['Vendors'],
        'conditions' => ['vendor_id =' => $vendor_id,'ReferralTemplates.status !=' => 0]
        ];
        $referralTemplates = $this->paginate($this->ReferralTemplates);
        $this->set(compact('referralTemplates'));
        $this->set('_serialize', ['referralTemplates']);
    }

}