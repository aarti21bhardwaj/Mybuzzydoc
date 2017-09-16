<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorCardRequests Controller
 *
 * @property \App\Model\Table\VendorCardRequestsTable $VendorCardRequests
 */
class VendorCardRequestsController extends AppController
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
        if($this->Auth->user('role')->name == 'admin'){
            $vendorCardRequests = $this->VendorCardRequests->find()->order(['VendorCardRequests.id' => 'DESC'])->toArray();
        }else{
            $vendorCardRequests = $this->VendorCardRequests
                                       ->findByVendorId($this->Auth->user('vendor_id'))
                                       ->order(['VendorCardRequests.id' => 'DESC'])
                                       ->toArray();
        }

        $loggedInUserRole = $this->Auth->user('role')->name;
        $this->set(compact('vendorCardRequests','loggedInUserRole'));
        $this->set('_serialize', ['vendorCardRequests','loggedInUserRole']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Card Request id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorCardRequest = $this->VendorCardRequests->findById($id)->contain(['Vendors'])->first();
         $loggedInUserRole = $this->Auth->user('role')->name;
        $this->set(compact('vendorCardRequest','loggedInUserRole'));
        $this->set('_serialize', ['vendorCardRequest','loggedInUserRole']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('VendorCardSeries');
        $vendorCardSeries = $this->VendorCardSeries->findByVendorId($this->Auth->user('vendor_id'))->first();
        if(!$vendorCardSeries){
         $this->Flash->error(__('A card series has not been added to your account yet. Please contact BuzzyDoc'));
         $this->redirect(['action' => 'index']);
         return ;
     }
     $vendorCardRequest = $this->VendorCardRequests->newEntity();
     if ($this->request->is('post')) {
        $this->request->data['vendor_id']=$this->Auth->user('vendor_id');
        $this->request->data['vendor_card_series']=$vendorCardSeries->series;
        $existingRequest = $this->VendorCardRequests->findByVendorId($this->Auth->user('vendor_id'))->where(['status'=>1])->first();
        if($existingRequest ){
            $this->Flash->error(__('You are already having a pending request for  this series.'));
            $this->redirect(['action' => 'index']);
            return;
        }
        $vendorCardRequest = $this->VendorCardRequests->patchEntity($vendorCardRequest, $this->request->data);
        if ($this->VendorCardRequests->save($vendorCardRequest)) {
            $this->Flash->success(__('The vendor card request has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The vendor card request could not be saved. Please, try again.'));
    }
    $this->set(compact('vendorCardRequest','vendorCardSeries'));
    $this->set('_serialize', ['vendorCardRequest','vendorCardSeries']);
}

    /**
     * Edit method
     *
     * @param string|null $id Vendor Card Request id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorCardRequest = $this->VendorCardRequests->get($id, [
            'contain' => []
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
         if(!$vendorCardRequest->status){
            $this->Flash->error(__('You can not update this request. Kindly raise new request.'));
            return $this->redirect(['action' => 'index']);    
        }
        $vendorCardRequest = $this->VendorCardRequests->patchEntity($vendorCardRequest, $this->request->data);
        if ($this->VendorCardRequests->save($vendorCardRequest)) {
            $this->Flash->success(__('The vendor card request has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The vendor card request could not be saved. Please, try again.'));
    }
    $vendors = $this->VendorCardRequests->Vendors->find('list', ['limit' => 200]);
    $this->set(compact('vendorCardRequest', 'vendors'));
    $this->set('_serialize', ['vendorCardRequest']);
}

    /**
     * Delete method
     *
     * @param string|null $id Vendor Card Request id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorCardRequest = $this->VendorCardRequests->get($id);
        if ($this->VendorCardRequests->delete($vendorCardRequest)) {
            $this->Flash->success(__('The vendor card request has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor card request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function issueCards($id){
        $this->request->allowMethod(['post']);
        $vendorCardRequest = $this->VendorCardRequests->findById($id)->first();
        if(!$vendorCardRequest->status){
            $this->Flash->error(__('You have already served this request.'));
            return $this->redirect(['action' => 'index']);    
        }
        $vendor = $this->VendorCardRequests->Vendors->findById($vendorCardRequest->vendor_id)->first();
        if(!$vendorCardRequest->start){
            $this->Flash->error(__('Kindly define start of requested card count.'));
            return $this->redirect(['action' => 'edit',$vendorCardRequest ->id]);       
        }
        $this->loadModel('VendorSettings');
        $liveMode = $this->VendorSettings->findByVendorId($vendorCardRequest->vendor_id)
        ->contain(['SettingKeys' => function($q){
            return $q->where(['name' => 'Live Mode']);
        }])->first()->value;
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode, 'throwErrorMode' => false]);
        $request=[
        'series'=>$vendorCardRequest->vendor_card_series,
        'start_range'=>$vendorCardRequest->start,
        'end_range'=>$vendorCardRequest->end,
        'vendor_id'=>$vendor->people_hub_identifier
        ];
        $issueCards = $this->PeopleHub->issueVendorCards($request);
        if(isset($issueCards->status) && $issueCards->status){
            $this->VendorCardRequests->updateAll(['is_issued'=>1,'status'=>0,'remark'=>'Cards issued successfully.'],
                ['id'=>$id,'vendor_id'=>$vendorCardRequest->vendor_id,'status'=>1]);
            $this->Flash->success(__('Cards issued successfully.'));
        }else{
            $this->VendorCardRequests->updateAll(['is_issued'=>0,'status'=>0,'remark'=>$issueCards->message],
                ['id'=>$id]);
            $this->Flash->error(__($issueCards->message));
        }
        return $this->redirect(['action' => 'index']);
    }
}
