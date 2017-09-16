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
class  TierPerksController extends ApiController
{

    public function initialize(){    
        parent::initialize();
    }
    
    /**
     * View method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $tierPerk = $this->TierPerks->findById($id)->contain(['Tiers'])->first();

        $this->set('tierPerk', $tierPerk);
        $this->set('_serialize', ['tierPerk']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $tierPerk = $this->TierPerks->newEntity();
        $tierPerk = $this->TierPerks->patchEntity($tierPerk, $this->request->data);
        if ($this->TierPerks->save($tierPerk)) {
            $response['message'] = (__('ENTITY_SAVED', 'Tier Perk'));
            $response['id']= $tierPerk->id;
        } else {
            throw new Exception('ENTITY_ERROR', 'Tier Perk');
        }

        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Tier Perk id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $tierPerk = $this->TierPerks->findById($id)->first();
        
        $tierPerk = $this->TierPerks->patchEntity($tierPerk, $this->request->data);
        if ($this->TierPerks->save($tierPerk)) {
        
        $response['message'] = (__('ENTITY_SAVED', 'Tier Perk'));
        
        $response['id']= $tierPerk->id;
        
        } else {
            throw new Exception('ENTITY_ERROR', 'Tier Perk');
        }
        
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}

?>
 