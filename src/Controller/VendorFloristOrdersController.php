<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorFloristOrders Controller
 *
 * @property \App\Model\Table\VendorFloristOrdersTable $VendorFloristOrders
 */
class VendorFloristOrdersController extends AppController
{
     public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth');
        $this->Auth->allow('makePayment');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Vendors', 'Users']
        ];
        $vendorFloristOrders = $this->paginate($this->VendorFloristOrders);

        $this->set(compact('vendorFloristOrders'));
        $this->set('_serialize', ['vendorFloristOrders']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Florist Order id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorFloristOrder = $this->VendorFloristOrders->get($id, [
            'contain' => ['Vendors', 'Users', 'Products', 'VendorFloristTransactions']
        ]);

        $this->set('vendorFloristOrder', $vendorFloristOrder);
        $this->set('_serialize', ['vendorFloristOrder']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorFloristOrder = $this->VendorFloristOrders->newEntity();
        if ($this->request->is('post')) {
            $vendorFloristOrder = $this->VendorFloristOrders->patchEntity($vendorFloristOrder, $this->request->data);
            if ($this->VendorFloristOrders->save($vendorFloristOrder)) {
                $this->Flash->success(__('The vendor florist order has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vendor florist order could not be saved. Please, try again.'));
        }
        $vendors = $this->VendorFloristOrders->Vendors->find('list', ['limit' => 200]);
        $users = $this->VendorFloristOrders->Users->find('list', ['limit' => 200]);
        $products = $this->VendorFloristOrders->Products->find('list', ['limit' => 200]);
        $this->set(compact('vendorFloristOrder', 'vendors', 'users', 'products'));
        $this->set('_serialize', ['vendorFloristOrder']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Florist Order id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorFloristOrder = $this->VendorFloristOrders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorFloristOrder = $this->VendorFloristOrders->patchEntity($vendorFloristOrder, $this->request->data);
            if ($this->VendorFloristOrders->save($vendorFloristOrder)) {
                $this->Flash->success(__('The vendor florist order has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vendor florist order could not be saved. Please, try again.'));
        }
        $vendors = $this->VendorFloristOrders->Vendors->find('list', ['limit' => 200]);
        $users = $this->VendorFloristOrders->Users->find('list', ['limit' => 200]);
        $products = $this->VendorFloristOrders->Products->find('list', ['limit' => 200]);
        $this->set(compact('vendorFloristOrder', 'vendors', 'users', 'products'));
        $this->set('_serialize', ['vendorFloristOrder']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Florist Order id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorFloristOrder = $this->VendorFloristOrders->get($id);
        if ($this->VendorFloristOrders->delete($vendorFloristOrder)) {
            $this->Flash->success(__('The vendor florist order has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor florist order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function makePayment(){
        $id = $_GET['id'];
        $uuid = $_GET['uuid'];
        $orderTotal = $_GET['total'];
        
        $floristOrder =  $this->VendorFloristOrders->find()->where(['uuid'=> $uuid, 'id' => $id])->first();
        if(!$floristOrder){
            $floristOrder = null;
            $vendorId = null;
            $customerId = null;

        }else{
            $vendorId = $floristOrder->vendor_id; //pass this vendorId to all the APIs that are used in public page for floral order approval.
            $this->loadModel('PatientAddresses');
            $ptAddress = $this->PatientAddresses->findByPatientPeoplehubIdentifier($floristOrder->patient_peoplehub_identifier)->first();
            $floristOrder->patient_address = $ptAddress;
            $this->loadModel('VendorFloristSettings');
            $vendorDetails = $this->VendorFloristSettings->findByVendorId($floristOrder->vendor_id)
                                                        ->first();
            $customerId = $vendorDetails->customer_id;
        }
       


        $this->viewBuilder()
            ->layout('review-form')
            ->template('makePayment');

        $this->set('vendorId', $vendorId);
        $this->set('orderTotal', $orderTotal);
        $this->set('floristOrder', $floristOrder);
        $this->set('customerId', $customerId);
    }
}
