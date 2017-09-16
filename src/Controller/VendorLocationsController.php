<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Number;
use Cake\Collection\Collection;

/**
 * VendorLocations Controller
 *
 * @property \App\Model\Table\VendorLocationsTable $VendorLocations
 */
class VendorLocationsController extends AppController
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
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $vendorLocations = $this->VendorLocations->find()->contain(['Vendors'])->where($this->_vendorCondition)->all();
            // $this->paginate = [
            // 'contain' => ['Vendors'],
            // 'conditions' => $this->_vendorCondition
            // ];

            $adminCheck = 1;
        }else{
            $vendorLocations = $this->VendorLocations->find()->contain(['Vendors'])->where(['vendor_id =' => $this->Auth->user('vendor_id')])->all();
            // $this->paginate = [
            // 'contain' => ['Vendors'],
            // 'conditions' => ['vendor_id =' => $this->Auth->user('vendor_id')]
            // ];

            $adminCheck = 0;

        }
        // $vendorLocations = $this->paginate($this->VendorLocations);
        $vendorId = $this->Auth->user('vendor_id');



        $this->set(compact('vendorLocations', 'adminCheck', 'vendorId'));
        $this->set('_serialize', ['vendorLocations']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Location id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorLocation = $this->VendorLocations->get($id, [
            'contain' => ['Vendors', 'VendorReviews']
            ]);

        $this->set('vendorLocation', $vendorLocation);
        $this->set('_serialize', ['vendorLocation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // pr($this->request->data); die;
        $loggedInUser = $this->Auth->user();
        $vendorLocation = $this->VendorLocations->newEntity();
        if ($this->request->is('post')) {
           
                if(!$this->_updateRecurringProfile($loggedInUser['vendor_id'], 1)){

                    $this->Flash->error(__('Could not update freshbooks recurring profile.'));
                    return false;
                }
                $vendorLocation = $this->VendorLocations->patchEntity($vendorLocation, $this->request->data);
                if(!$vendorLocation->errors()){

                    if ($this->VendorLocations->save($vendorLocation)) {
                        $this->_checkDefault($vendorLocation);
                        $this->Flash->success(__('ENTITY_SAVED', 'Vendor Location'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('ENTITY_ERROR', 'Vendor Location'));
                    }
                }else{
                    $this->Flash->error(__('INVALID_DATA_PROVIDED'));
                }
            
        }

        $vendors = $this->VendorLocations->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('vendorLocation', 'vendors'));
        $this->set('_serialize', ['vendorLocation']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Location id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorLocation = $this->VendorLocations->get($id, [
            'contain' => []
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorLocation = $this->VendorLocations->patchEntity($vendorLocation, $this->request->data);
            if ($this->VendorLocations->save($vendorLocation)) {
                $this->_checkDefault($vendorLocation);
                $this->Flash->success(__('ENTITY_SAVED', 'Vendor Location'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'Vendor Location'));
            }
        }
        $vendors = $this->VendorLocations->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('vendorLocation', 'vendors'));
        $this->set('_serialize', ['vendorLocation']);
    }

    private function _checkDefault($vendorLocation){

        $vendorLocations = $this->VendorLocations->findByVendorId($this->Auth->user('vendor_id'));
        $isDefault = false;
        if(!$vendorLocation->is_default && $vendorLocations->where(['is_default' => true])->all()->count() == 0){
            $isDefault = true;
        }
        if($vendorLocation->is_default){
            $defaultLocations = $vendorLocations->where(['is_default' => true, 'id !=' => $vendorLocation->id])->all()->toArray();
            if($defaultLocations){
                foreach ($defaultLocations as $key => $value) {
                    $defaultLocations[$key]->is_default = false;
                }
                $this->VendorLocations->saveMany($defaultLocations);
                $this->_updateAutoReviewRequests($vendorLocation->id);
            }
            $isDefault = true; 
        }
        $vendorLocation->is_default = $isDefault;
        $this->VendorLocations->save($vendorLocation);

    }

    private function _updateAutoReviewRequests($locationId){

        $vendorLocations = $this->VendorLocations
                       ->findByVendorId($this->Auth->user('vendor_id'))
                       ->contain(['ReviewRequestStatuses' => function($q){
                         return $q->where(['ReviewRequestStatuses.user_id IS NULL', 'ReviewRequestStatuses.vendor_review_id IS NULL']);
                       }])
                       ->all()
                       ->toArray();
        
        $reviewRequests = (new Collection($vendorLocations))->extract('review_request_statuses')->unfold()->toArray();
        if(!$reviewRequests){
            return;
        }
        foreach ($reviewRequests as $key => $value) {
            $reviewRequests[$key]->vendor_location_id = $locationId; 
        }
        $this->VendorLocations->ReviewRequestStatuses->saveMany($reviewRequests);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Location id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorLocation = $this->VendorLocations->get($id);
        if(isset($vendorLocation->is_default) && $vendorLocation->is_default){
            $this->Flash->error(__("Default location cannot be deleted. Please set another location as default before deleting this location."));
            return $this->redirect(['action' => 'index']);
        }
        if(!$this->_updateRecurringProfile($vendorLocation->vendor_id, -1)){
            $this->Flash->error(__('Could not update freshbooks recurring profile.'));
            return false;
        }
        if ($this->VendorLocations->delete($vendorLocation)) {
            $this->Flash->success(__('ENTITY_DELETED', 'Vendor Location'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'Vendor Location'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function _updateRecurringProfile($vendorId, $change){

        $vendorFreshbook = $this->VendorLocations
                                ->Vendors
                                ->VendorFreshBooks
                                ->findByVendorId($vendorId)
                                ->first();

        if(!$vendorFreshbook){

            return true;
        }

        $vendorLocationsCount = $this->VendorLocations->findByVendorId($vendorId)->all()->count();

        if($change == 1){

            if($vendorLocationsCount == 0){
                return true;
            }

            $vendorLocationsCount += 1;

        }else{

            if($vendorLocationsCount == 1){
                return true;
            }
            $vendorLocationsCount -= 1;
        }

        $itemDataArray = [
                        'name'=>'BuzzyDoc Subscription',
                        'description'=>'Base plan',
                        'unit_cost'=>100,
                        'quantity'=> $vendorLocationsCount
                      ];

        $this->loadComponent('FreshBooks');
        //Update recurring profile for this client
        $recurring = $this->FreshBooks->updateRecurringProfile($vendorFreshbook->freshbook_client_id, $vendorFreshbook->recurring_profile_id, $itemDataArray);

        if(!isset($recurring['status']) && !$recurring['status']){

            $this->Flash->error(__('Could not update the recurring profile.'));
            return false;
        }   

        $charge = $itemDataArray['unit_cost'] * $itemDataArray['quantity'];
        $this->Flash->success(__('Recurring profile updated. You will be charged '. Number::currency($charge, 'USD').' monthly for your subscription now.'));
        return true;
    }
}
?>