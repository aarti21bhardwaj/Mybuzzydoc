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
class  EventsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    
    /**
     * View method
     *
     * @return \Cake\Network\Response|void Redirects on successful show the response from AJAX, renders view for the template data.
    */
    public function view($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => ['EventVariables']
        ]);
        $this->set('event', $event);
        $this->set('_serialize', ['event']);
    }
    
}