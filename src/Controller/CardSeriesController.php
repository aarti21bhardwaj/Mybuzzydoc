<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;


/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class CardSeriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $cardSeries = array();
        $this->loadModel('Vendors');
        $vendors = $this->Vendors->find()->contain(['VendorSettings','VendorSettings.SettingKeys'=>function($q){
            return $q->where(['name' => 'Live Mode']);}])->toArray();
        if ($this->request->is(['get'])) {

         if(Configure::read('reseller.mode')=='demo'){
            $liveMode = 0;
        }else{
            $liveMode = 1;
        }
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
        $cardSeries = $this->PeopleHub->getResellerSeries();
        if(!$cardSeries['status']){
            foreach ($cardSeries['response']->error as $key => $value) {
                $this->Flash->error(__($value));
            }

        }
    }
    $this->set(compact('cardSeries'));
    $this->set('_serialize', ['cardSeries']);
}

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
            ]);

        $this->set('setting', $setting);
        $this->set('_serialize', ['setting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $cardSeries = array();
        $this->loadModel('Vendors');
        $vendors = $this->Vendors->find('list')->toArray();
        if ($this->request->is(['post'])) {
          if(!isset($this->request->data['series'])){
             $this->Flash->error(__('MANDATORY_FIELD_MISSING','series'));
         }
         if(isset($this->request->data['series']) && empty($this->request->data['series'])){
             $this->Flash->error(__('EMPTY_NOT_ALLOWED','series'));
         }
         if(!isset($this->request->data['vendor_id'])){
             $this->Flash->error(__('MANDATORY_FIELD_MISSING','vendor_id'));
         }
         if(isset($this->request->data['vendor_id']) && empty($this->request->data['vendor_id'])){
             $this->Flash->error(__('EMPTY_NOT_ALLOWED','vendor_id'));
         }
         $vendorId = $this->request->data['vendor_id'];
         $vendorInfo = $this->Vendors->findById($vendorId)->contain(['VendorSettings','VendorSettings.SettingKeys'=>function($q){
            return $q->where(['name' => 'Live Mode']);}])->first();
         if((!empty($vendorInfo->vendor_settings))){
            $liveMode = $vendorInfo->vendor_settings[0]->value;
        }else{
            $this->Flash->error(__('Vendor\'s setting not available'));
            $this->redirect(['action' => 'add']);
            return;
        }

        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
        $request=[
        'series'=>$this->request->data['series'],
        'vendor_id'=>$vendorInfo->people_hub_identifier
        ];
        $registerCardSeries = $this->PeopleHub->registerNewSeries($request);
        // pr($registerCardSeries);
        if(!$registerCardSeries['status']){
            if(isset($registerCardSeries['response']->error)){
                foreach ($registerCardSeries['response']->error as $key => $value) {
                    $this->Flash->error(__($value));
                }
            }else{
                $this->Flash->error(__($registerCardSeries['response']->message));   
            }
        }else{
            $this->loadModel('VendorCardSeries');
            $cardSeriesReqData = [
            'vendor_id'=>$this->request->data['vendor_id'],
            'series'=>$this->request->data['series'],
            'ph_vendor_card_series_identifier'=> $registerCardSeries['response']->vendor_card_series[0]->id
            ];
            $vendorCardSeries = $this->VendorCardSeries->newEntity($cardSeriesReqData);
            $vendorCardSeries = $this->VendorCardSeries->patchEntity($vendorCardSeries,$cardSeriesReqData);
            if($this->VendorCardSeries->save($vendorCardSeries)){

            }else{
            $this->Flash->error(__($vendorCardSeries['response']->message));   
            }
            $this->Flash->success(__('Series created and associated with vendor successfully'));
            $this->redirect(['action' => 'index']);
            return;
        }
    }
    $this->set(compact('cardSeries','vendors'));
    $this->set('_serialize', ['cardSeries']);
}

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The setting could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('setting'));
        $this->set('_serialize', ['setting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__('The setting has been deleted.'));
        } else {
            $this->Flash->error(__('The setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user){

        if(in_array($user['role']['label'], ['Admin']))
            return true;
        return parent::isAuthorized($user); 
    }
}