<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\Network\Session;
use Cake\Routing\Router;
use Cake\Network\Exception\BadRequestException;
use Cake\I18n\Date;
/**
 * Referrals Controller
 *
 * @property \App\Model\Table\ReferralsTable $Referrals
 */
class ReferralsController extends AppController
{

    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;

    public function initialize(){
        parent::initialize();
        // $this->Auth->config('authorize', ['Controller']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('post')|| $this->request->is('put')){
            $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $from = new Date($from);
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            } else {
            $this->Flash->error(__('Please enter a valid date range')); 
            return $this->redirect(['action' => 'index']);
            }
        } else{   
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
        }

        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $referrals = $this->Referrals->find()->contain(['Vendors'])->where($this->_vendorCondition)->where(['                                                           Referrals.created >=' => $from,
                                                                            'Referrals.created <' => $to,
                                                                            ])->limit(2000)->order(['Referrals.created' => 'DESC'])->all();
        }else{          
        $referrals = $this->Referrals->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->where($this->_vendorCondition)->where(['Referrals.created >=' => $from,'Referrals.created <' => $to])->limit(2000)->order(['Referrals.created' => 'DESC'])->all();
        }
        $this->set('referrals', $referrals);
        $this->set('_serialize', ['referrals']);
    }

    /**
     * View method
     *
     * @param string|null $id Referral id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $referral = $this->Referrals->find('all')->where(['Referrals.id'=>$id])->contain(['Vendors', 'ReferralLeads'])->first();
        $this->set('referral', $referral);
        $this->set('_serialize', ['referral']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */

    // public function add()
    // {
    //     $loggedInUser = $this->Auth->user();
    //     $referral = $this->Referrals->newEntity();
    //     if ($this->request->is('post')) {

    //         $status = $this->request->data['status'];

    //             $referral = $this->Referrals->patchEntity($referral, $this->request->data);
    //             if ($this->Referrals->save($referral)) {
                
    //                 $url = Router::url('/', true);
    //                 $url = $url.'referral-leads/add/'.$referral->uuid;

    //                 $referral->link = $url;
    //                 $referral->email = $referral->refer_to;

    //                 if($status)
    //                     $referral->cc = $referral->refer_from;

    //                 $event = new Event('Referral.requestSent', $this, [
    //                 'arr' => [
    //                         'hashData' => $referral,
    //                         'eventId' => 4, //give the event_id for which you want to fire the email
    //                         'vendor_id' => $referral->vendor_id
    //                     ] 
    //                 ]);
                    
    //                 $this->eventManager()->dispatch($event);
    //                 $this->Flash->success(__('ENTITY_SAVED', 'referral'));

    //                 return $this->redirect(['action' => 'view', $referral->id]);
    //             } else {
    //                 $this->Flash->error(__('ENTITY_ERROR', 'referral'));
    //             }

    //     }

    //     $vendors = $this->Referrals->Vendors->find('list', array(
    //                                                     'conditions' => array('Vendors.status !=' => 0)
    //                                                          ));
    //     $this->loadModel('ReferralTemplates');
    //     $referral_template = $this->ReferralTemplates->find('all',
    //                                                    array('conditions' => array('status' => 1) ) );
    //     $this->set(compact('referral', 'vendors','referral_template'));
    //     $this->set('_serialize', ['referral']);
    //     $this->set('loggedInUser', $loggedInUser); 
    // }

    /**
     * Edit method
     *
     * @param string|null $id Referral id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new BadRequestException('BAD_REQUEST');
    }

    /**
     * Delete method
     *
     * @param string|null $id Referral id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new BadRequestException('BAD_REQUEST');    
    }

    // public function isAuthorized($user){

    //     $this->loadModel('VendorSettings');
    //     if($user['role_id'] != 1){


    //         $setting = $this->VendorSettings->findByVendorId($user['vendor_id'])
    //                                                ->where(['setting_key_id' => 13])
    //                                                ->first()
    //                                                ->value;

    //         if(!$setting){
                
    //             return false;
    //         }
            
    //     }
    //     return parent::isAuthorized($user); 
    // }



}