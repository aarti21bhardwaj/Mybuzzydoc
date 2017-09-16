<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Event\Event;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class ReferralsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        // $this->Auth->config('authorize', ['Controller']);
    }
    
    /**
     * View method
     *
     * @return \Cake\Network\Response|void Redirects on successful show the response from AJAX, renders view for the template data.
     */
    public function view($id=null){
        if($this->request->is('ajax')){
            $this->loadModel('ReferralTemplates');

            $referral_template_data = $this->ReferralTemplates->findById($id)->first();

            $this->set(compact('referral_template_data'));
            $this->set('_serialize', ['referral_template_data']); 

        }
    }

    public function add()
    {
        /*if(!$this->request->is('post')){
             throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }*/
        if(!isset($this->request->data['refer_from'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'refer_from'));
        }
        if(!isset($this->request->data['refer_to'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'refer_to'));
        }
        if(!isset($this->request->data['subject'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'subject'));
        }
        if(!isset($this->request->data['description'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'description'));
        }
        $referral = $this->Referrals->newEntity();
        if ($this->request->is('post')) {
            $status = $this->request->data['status'];
                $referral = $this->Referrals->patchEntity($referral, $this->request->data);
                if ($this->Referrals->save($referral)) {
                    
                    $url = Router::url('/', true);
                    $url = $url.'referral-leads/add/'.$referral->uuid;
                    $referral->link = $url;
                    $referral->email = $referral->refer_to;
                    if($status)
                        $referral->cc = $referral->refer_from;
                    $event = new Event('Referral.requestSent', $this, [
                    'arr' => [
                            'hashData' => $referral,
                            'eventId' => 4, //give the event_id for which you want to fire the email
                            'vendor_id' => $referral->vendor_id
                        ] 
                    ]);
                    
                    $this->eventManager()->dispatch($event);
                    $this->set(compact('referral'));
                    $this->set('_serialize', ['referral']); 
                } else {
                    throw new InternalErrorException(__('ENTITY_ERROR', 'referral'));
                }

        }

        $vendors = $this->Referrals->Vendors->find('list', array(
                                                        'conditions' => array('Vendors.status !=' => 0)
                                                             ));
        $this->loadModel('ReferralTemplates');
        $referral_template = $this->ReferralTemplates->find('all',
                                                       array('conditions' => array('status' => 1) ) );
        $this->set(compact('referral', 'vendors','referral_template'));
        $this->set('_serialize', ['referral']); 
    }

    public function dashboardAdd(){

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($this->request->data['refer_from'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'refer_from'));
        }

        if(!isset($this->request->data['first_name'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'first_name'));
        }

        if(!isset($this->request->data['peoplehub_identifier'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'peoplehub_identifier'));
        }

        if(!isset($this->request->data['referral_lead']['preferred_talking_time'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'preferred_talking_time'));
        }

        $this->request->data['vendor_id'] = $this->request->data['referral_lead']['vendor_id'] = $this->Auth->user('vendor_id');

        $this->request->data['referral_lead']['referral_status_id'] = $this->Referrals->ReferralLeads->ReferralStatuses->findByStatus('Pending')->first()->id;

        $this->request->data['status'] = 1;

        $referral = $this->Referrals->newEntity();
        $referral = $this->Referrals->patchEntity($referral, $this->request->data, ['associated' => 'ReferralLeads']);

        if(!$this->Referrals->save($referral)){
            pr($referral);die;
            throw new InternalErrorException(__('ENTITY_ERROR', 'referral'));
        }

        $response['id'] = $referral->id;
        $response['message'] = __('ENTITY_SAVED', 'Lead');

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);

    }

    public function isAuthorized(){

        if($this->Auth->user('role_id') != 1){

            $this->loadModel('VendorSettings');
            $setting = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                                   ->where(['setting_key_id' => 13])
                                                   ->first()
                                                   ->value;
            if(!$setting)
                throw new UnauthorizedException(__('You are not authorized to access that location'));          
        }
        return true;
    }
    
}