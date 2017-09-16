<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class ReferralLeadsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        // $this->Auth->config('authorize', ['Controller']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Referral Lead id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->request->is(['put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $referralLead = $this->ReferralLeads->find('all')->where(['id'=>$id])->first();
        if(!$referralLead){
            throw new NotFoundException(__('BAD_REQUEST'));
        }
        
        $referralLead = $this->ReferralLeads->patchEntity($referralLead, $this->request->data);
        $referralLead = $this->ReferralLeads->save($referralLead);
        //pr($referralLead);
        if($referralLead){
                $data = 
                [
                    'status' => true,
                    'message'=> 'Saved',
                    'data' => [
                            'referral_lead_id' => $referralLead->id,
                            'vendor_referral_settings_id' => $referralLead->vendor_referral_settings_id,
                            'errors' => $referralLead->errors(),
                          ]
                ];
        }
        $this->set('response',$data);
        $this->set('_serialize', ['response']);
    }

     public function add()
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
     public function view($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

}
