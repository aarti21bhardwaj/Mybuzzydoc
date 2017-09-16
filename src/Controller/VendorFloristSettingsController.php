<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorFloristSettings Controller
 *
 * @property \App\Model\Table\VendorFloristSettingsTable $VendorFloristSettings
 */
class VendorFloristSettingsController extends AppController
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
        $this->paginate = [
            'contain' => ['Vendors']
        ];
        
        $vendorFloristSettings = $this->VendorFloristSettings->find()->first();
        
        if($vendorFloristSettings){
            $address = json_decode($vendorFloristSettings->address);
        }
        // pr($address->address); die;
        // $addressData = 
        $vendorFloristSettings = $this->paginate($this->VendorFloristSettings);
        $this->set(compact('vendorFloristSettings', 'address'));
        $this->set('_serialize', ['vendorFloristSettings']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Florist Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorFloristSetting = $this->VendorFloristSettings->get($id, [
            'contain' => ['Vendors']
        ]);

        $this->set('vendorFloristSetting', $vendorFloristSetting);
        $this->set('_serialize', ['vendorFloristSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // pr($this->request->data); die;
        $vendorFloristSetting = $this->VendorFloristSettings->newEntity();
        if ($this->request->is('post')) {
        
        $address = ['address'=>$this->request->data['address1'], 'city'=>$this->request->data['city'], 'state'=>$this->request->data['state'], 'zip'=>$this->request->data['zip'], 'country'=>$this->request->data['country']];
        $address = json_encode($address);
        // pr($address); die;
        $this->request->data['address'] = $address;
            $vendorFloristSetting = $this->VendorFloristSettings->patchEntity($vendorFloristSetting, $this->request->data);
            if ($this->VendorFloristSettings->save($vendorFloristSetting)) {
                $this->Flash->success(__('The vendor florist setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vendor florist setting could not be saved. Please, try again.'));
        }
        // $addressView = json_decode($address);
        // pr($addressView); die;
        // $usStates = file_get_contents('https://gist.githubusercontent.com/mshafrir/2646763/raw/8b0dbb93521f5d6889502305335104218454c2bf/states_hash.json');
        // $usStates = json_decode($usStates);
        $country = 'US';
        $vendors = $this->VendorFloristSettings->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('vendorFloristSetting', 'vendors', 'addressView', 'usStates', 'country'));
        $this->set('_serialize', ['vendorFloristSetting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Florist Setting id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorFloristSetting = $this->VendorFloristSettings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorFloristSetting = $this->VendorFloristSettings->patchEntity($vendorFloristSetting, $this->request->data);
            if ($this->VendorFloristSettings->save($vendorFloristSetting)) {
                $this->Flash->success(__('The vendor florist setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vendor florist setting could not be saved. Please, try again.'));
        }
        $vendors = $this->VendorFloristSettings->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('vendorFloristSetting', 'vendors'));
        $this->set('_serialize', ['vendorFloristSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Florist Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorFloristSetting = $this->VendorFloristSettings->get($id);
        if ($this->VendorFloristSettings->delete($vendorFloristSetting)) {
            $this->Flash->success(__('The vendor florist setting has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor florist setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
