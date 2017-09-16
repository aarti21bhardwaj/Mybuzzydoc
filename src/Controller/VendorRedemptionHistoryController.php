<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
/**
 * VendorRedemptionHistory Controller
 *
 * @property \App\Model\Table\VendorRedemptionHistoryTable $VendorRedemptionHistory
 */
class VendorRedemptionHistoryController extends AppController
{
    public function initialize(){    
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {


        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $vendorRedemptionHistory = $this->VendorRedemptionHistory
                                            ->find()
                                            ->contain(['Vendors'])
                                            ->where($this->_vendorCondition)
                                            ->all();
            }else{
            $vendorRedemptionHistory = $this->VendorRedemptionHistory->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();
        }

        //Get vendor deposit balance from here
        $this->loadModel('VendorDepositBalances');
        if($loggedInUser['role']->name !== self::SUPER_ADMIN_LABEL){
            $vendorDepositBalances = $this->VendorDepositBalances->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->first();
        }

        $this->set(compact('vendorRedemptionHistory','vendorDepositBalances'));
        $this->set('_serialize', ['vendorRedemptionHistory','vendorDepositBalances']); 
        $this->set('loggedInUser', $loggedInUser);

    }

    /**
     * View method
     *
     * @param string|null $id Vendor Redemption History id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorRedemptionHistory = $this->VendorRedemptionHistory->get($id, [
            'contain' => ['Vendors']
        ]);

        $this->set('vendorRedemptionHistory', $vendorRedemptionHistory);
        $this->set('_serialize', ['vendorRedemptionHistory']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Redemption History id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Redemption History id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
}
