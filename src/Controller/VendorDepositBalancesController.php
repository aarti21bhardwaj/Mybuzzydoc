<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\Date;

/**
 * VendorDepositBalances Controller
 *
 * @property \App\Model\Table\VendorDepositBalancesTable $VendorDepositBalances
 */
class VendorDepositBalancesController extends AppController
{
    public function initialize(){    
        parent::initialize();
    }
    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        /*if($this->request->is('post')|| $this->request->is('put')){
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
            $vendorDepositBalances = $this->VendorDepositBalances->find()->contain(['Vendors'])->where(['VendorDepositBalances.created >=' => $from,
                                'VendorDepositBalances.created <' => $to,
                                ])->limit(2000)->order(['VendorDepositBalances.created' => 'DESC'])->all();
            
        } else{
            $vendorDepositBalances = $this->VendorDepositBalances->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->where(['VendorDepositBalances.created >=' => $from,
                                'VendorDepositBalances.created <' => $to,
                                ])->limit(2000)->order(['VendorDepositBalances.created' => 'DESC'])->all();
           
        }

        $this->set(compact('vendorDepositBalances'));
        $this->set('_serialize', ['vendorDepositBalances']);*/
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $vendorDepositBalances = $this->VendorDepositBalances->find()->contain(['Vendors'])->all();
        } else{
            $vendorDepositBalances = $this->VendorDepositBalances->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();
        }

        // $vendorDepositBalances = $this->paginate($this->VendorDepositBalances);
        $this->set(compact('vendorDepositBalances'));
        $this->set('_serialize', ['vendorDepositBalances']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Deposit Balance id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $vendorDepositBalance = $this->VendorDepositBalances->get($id, [
        //     'contain' => ['Vendors']
        // ]);
        $vendorDepositBalance = $this->VendorDepositBalances->find('all')->where(['VendorDepositBalances.id'=>$id])->contain(['Vendors'])->first();

        $this->set('vendorDepositBalance', $vendorDepositBalance);
        $this->set('_serialize', ['vendorDepositBalance']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Deposit Balance id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Deposit Balance id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
}
