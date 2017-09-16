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
 * Referral Tier Perks Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class  ReferralTierPerksController extends ApiController
{

    
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * View method
     *
     * @param string|null $id Referral Tier Perk id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $referralTierPerk = $this->ReferralTierPerks->findById($id)->contain(['ReferralTiers'])->first();

        $this->set('referralTierPerk', $referralTierPerk);
        $this->set('_serialize', ['referralTierPerk']);
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
        $referralTierPerk = $this->ReferralTierPerks->newEntity();
        $referralTierPerk = $this->ReferralTierPerks->patchEntity($referralTierPerk, $this->request->data);
        if ($this->ReferralTierPerks->save($referralTierPerk)) {
            $response['message'] = (__('ENTITY_SAVED', 'Referral Tier Perk'));
            $response['id']= $referralTierPerk->id;
        } else {
            throw new Exception('ENTITY_ERROR', 'Referral Tier Perk');
        }

        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Referral Tier Perk id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $referralTierPerk = $this->ReferralTierPerks->findById($id)->first();
        
        $referralTierPerk = $this->ReferralTierPerks->patchEntity($referralTierPerk, $this->request->data);
        if ($this->ReferralTierPerks->save($referralTierPerk)) {
        
        $response['message'] = (__('ENTITY_SAVED', 'Referral Tier Perk'));
        
        $response['id']= $referralTierPerk->id;
        
        } else {
            throw new Exception('ENTITY_ERROR', 'Referral Tier Perk');
        }
        
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}

?>
 