<?php
namespace App\Controller\PatientPortalApis;

use App\Controller\PatientPortalApis\ApiController;
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
    }

    public function add()
    {
        /*if(!$this->request->is('post')){
             throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }*/
        if(!isset($this->request->data['first_name'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'First Name'));
        }
        if(!isset($this->request->data['last_name'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Last name'));
        }
        if(!isset($this->request->data['refer_from'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Email'));
        }
        if(!isset($this->request->data['refer_to'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', "Referral's Email"));
        }
        if(!isset($this->request->data['subject'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Subject'));
        }
        if(!isset($this->request->data['description'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'Description'));
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
                            'vendor_id' => $this->request->data['vendor_id']
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

}
