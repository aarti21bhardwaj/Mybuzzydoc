<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * VendorSettings Controller
 *
 * @property \App\Model\Table\VendorSettingsTable $VendorSettings
 */
class VendorSettingsController extends AppController
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
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
        $this->paginate = [
            'contain' => ['Vendors', 'SettingKeys']
        ];
        $vendorSettings = $this->paginate($this->VendorSettings);

        $this->set(compact('vendorSettings'));
        $this->set('_serialize', ['vendorSettings']);
        /*return $this->redirect(
            ['controller' => 'Vendors', 'action' => 'index']
        );*/
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
        $vendorSetting = $this->VendorSettings->get($id, [
            'contain' => ['Vendors', 'SettingKeys']
        ]);

        $this->set('vendorSetting', $vendorSetting);
        $this->set('_serialize', ['vendorSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($vendorId = null)
    {
        if(!$vendorId){
            $this->Flash->error(__('Invalid Access.'));
            return $this->redirect($this->referer());
        }

        $this->loadModel('SettingKeys');
        $allKeys = $this->SettingKeys->find()
                                    ->contain(['VendorSettings' => function($q) use ($vendorId){
                                        return $q->where(['vendor_id' => $vendorId]);
                                    }])
                                    ->all();
        $this->set('settingKeys', $allKeys);

        $allKeys = new Collection($allKeys);
        $allKeys = $allKeys->indexBy('id')->toArray();

        $vendorSetting = $this->VendorSettings->newEntity();
        if ($this->request->is('post')) {

            foreach($this->request->data as $id => $settingKeyValue){

                if(!$allKeys[$id]['vendor_settings']){

                    $settingKeys[]= ['vendor_id'=> $vendorId,
                                     'setting_key_id'=> $id,
                                     'value'=> $settingKeyValue
                                    ];
                }else{
                    $settingKeys[]= [
                                        'id' => $allKeys[$id]['vendor_settings'][0]['id'],
                                        'setting_key_id' => $id,
                                        'value'=> $settingKeyValue
                                    ];
                }

            }

            $settingKeys = (new Collection($settingKeys))->indexBy('setting_key_id')->toArray();

            //Referrals Setting cannot be false if Referral Tier is active
            if($settingKeys[19]['value'] && !$settingKeys[13]['value']){

               $settingKeys[13]['value'] = true;
               $this->Flash->success(__('Referrals activated for this vendor as referral tiers have also been activated.')); 
            }

            //Gift Coupon Setting cannot be false if Tier or Milestone Program or referrals is active
            if(!$this->_checkGiftCouponActive($settingKeys)){

               $settingKeys[12]['value'] = true;
               $this->Flash->success(__('Gift Coupons activated for this vendor as gift coupons are being used by one or more active features.')); 
            }


            $vendorSetting = $this->VendorSettings->patchEntities($vendorSetting, $settingKeys);
            if ($this->VendorSettings->saveMany($vendorSetting)) {

                $this->Flash->success(__('The vendor setting has been saved.'));
                return $this->redirect(['controller' => 'Vendors', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor setting could not be saved. Please, try again.'));
            }
        }

        $this->loadModel('Vendors');
        $vendor = $this->Vendors->findById($vendorId)->first();

        $this->set('vendor', $vendor);
        $this->set('_serialize', ['vendor']);


    }

    private function _checkGiftCouponActive($settingKeys){
        
        if(!$settingKeys[12]['value'] ){

            if($settingKeys[1]['value'] || $settingKeys[8]['value'] || $settingKeys[13]['value'] || $settingKeys[19]['value'] || $settingKeys[20]['value']){
                return false;
            }

        }
        return true;
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vendorSetting = $this->VendorSettings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorSetting = $this->VendorSettings->patchEntity($vendorSetting, $this->request->data);
            if ($this->VendorSettings->save($vendorSetting)) {
                $this->Flash->success(__('The vendor setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor setting could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->VendorSettings->Vendors->find('list', ['limit' => 200]);
        $keys = $this->VendorSettings->SettingKeys->find('list', ['limit' => 200]);
        $this->set(compact('vendorSetting', 'vendors', 'keys'));
        $this->set('_serialize', ['vendorSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorSetting = $this->VendorSettings->get($id);
        if ($this->VendorSettings->delete($vendorSetting)) {
            $this->Flash->success(__('The vendor setting has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
