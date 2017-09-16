<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Session;

/**
 * LegacyRewards Controller
 *
 * @property \App\Model\Table\LegacyRewardsTable $LegacyRewards
 */
class LegacyRewardsController extends AppController
{
    const SUPER_ADMIN_LABEL = 'admin';

    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;

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
           $legacyRewards = $this->LegacyRewards->find()->contain(['Vendors', 'RewardCategories', 'ProductTypes'])->where($this->_vendorCondition)->all();
        }else{
            $this->loadModel('VendorSettings');
            $settings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))->contain(['SettingKeys' => function($q){
               return $q->where(['OR' => [['name' => 'Products And Services'], ['name' => 'Admin Products']]]);
            }])->all()->combine('setting_key.name', 'value')->toArray();
            
            // When products and services are enabled only.
            if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 0){
                // pr(' m here when pS enabled'); die;
             $legacyRewards = $this->LegacyRewards->find()
                                                ->contain(['Vendors', 'RewardCategories', 'ProductTypes'])
                                                ->where(['vendor_id =' => $this->Auth->user('vendor_id'), 'LegacyRewards.product_type_id NOT IN' => [1,3]])
                                                ->all()
                                                ->toArray();

            }else if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 1){
                
            // WHen products & services and Admin products both are enabled in vendor settings.
             $legacyRewards = $this->LegacyRewards->find()
                                                ->contain(['Vendors', 'RewardCategories', 'ProductTypes'])
                                                ->where(['LegacyRewards.product_type_id NOT IN' => [1,3], 
                                                            'OR' => [
                                                                        ['vendor_id =' => $this->Auth->user('vendor_id')] , 
                                                                        ['AND' => [
                                                                                    'LegacyRewards.status' => 1],
                                                                                    ['vendor_id =' => 1]
                                                                        ]
                                                                    ]
                                                        ])
                                                ->all()
                                                ->toArray();
            }
        }
        $vendorlegacyReward = $this->LegacyRewards->VendorLegacyRewards
                                                  ->findByVendorId($this->Auth->user('vendor_id'))
                                                  ->all()
                                                  ->indexBy('legacy_reward_id')
                                                  ->toArray();
        // pr($vendorlegacyReward); die;
        $this->set(compact('legacyRewards'));
        $this->set('_serialize', ['legacyRewards']);
        $this->set('vendorlegacyReward', $vendorlegacyReward);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * View method
     *
     * @param string|null $id Legacy Reward id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $legacyReward = $this->LegacyRewards->find('all')->where(['LegacyRewards.id' => $id])->contain(['Vendors', 'RewardCategories', 'ProductTypes', 'LegacyRedemptions'])->first();
        if($legacyReward)
        {
            $this->set('legacyReward', $legacyReward);
            $this->set('_serialize', ['legacyReward']);
        } else {
            $this->Flash->error(__('RECORD_NOT_FOUND'));
            return $this->redirect(['action' => 'index']);
        }

    }
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loggedInUser = $this->Auth->user();
        $this->loadModel('Users');
        $legacyReward = $this->LegacyRewards->newEntity();
        if ($this->request->is('post')) {

            $vendorLegacyReward = ['vendor_id' => $this->request->data['vendor_id'], 'status' => $this->request->data['status']];
            $this->request->data['vendor_legacy_rewards'][] = $vendorLegacyReward;
            $legacyReward = $this->LegacyRewards->patchEntity($legacyReward, $this->request->data, ['associated' => 'VendorLegacyRewards']);
            //pr($legacyReward); die('ss');
            if ($this->LegacyRewards->save($legacyReward)) {
                $this->Flash->success(__('ENTITY_SAVED', 'legacy reward'));
                return $this->redirect(['action' => 'index']);
            } else {
                // $this->Flash->error(__('The legacy reward could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'legacy reward'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $vendors = $this->LegacyRewards->Vendors->find('list')->where(['status'=>1])->toArray();
        $rewardCategories = $this->LegacyRewards->RewardCategories->find('list')->where(['status'=>1])->toArray();
        $productTypes = $this->LegacyRewards->ProductTypes->find('list')->where(['status'=>1, 'name IS NOT' => 'Amazon/Tango'])->all()->toArray();
        $this->set(compact('vendors', 'rewardCategories', 'productTypes', 'legacyReward'));
        $this->set('_serialize', ['legacyReward']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Edit method
     *
     * @param string|null $id Legacy-Reward id, $oldImageName-OldImage, $filePath- Path for image .
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loggedInUser = $this->Auth->user();

        $legacyReward = $this->LegacyRewards->find('all')->where(['LegacyRewards.id' => $id])->contain([])->first();

        if(!$legacyReward)
        {
            $this->Flash->error(__('RECORD_NOT_FOUND'));
            return $this->redirect(['action' => 'index']);
        }
            //If old image is available, unlink the path(and delete the image) and and  upload image from "upload" folder in webroot.         

            $oldImageName = $legacyReward->image_name;
            $path = Configure::read('ImageUpload.unlinkPath');
            if ($this->request->is(['put'])) {
                //Removing image name index if image_is not submitted when editing a reward
                if(empty($this->request->data['image_name']['tmp_name'])){
                    unset($this->request->data['image_name']);
                    $oldImageName ='';
                }
                $data = $this->request->data;
                if(isset($data['amount']) && !empty($data['amount'])){
                    $this->request->data['points'] = null;
                }
                if(isset($data['points']) && !empty($data['points'])){
                    $this->request->data['amount'] = null;
                }
                $legacyReward = $this->LegacyRewards->patchEntity($legacyReward, $this->request->data);
                if ($this->LegacyRewards->save($legacyReward)) {

                    // $this->Flash->success(__('The legacy reward has been saved.'));
                    $this->Flash->success(__('ENTITY_SAVED', 'legacy reward'));

                    if(!empty($oldImageName)){
                        $filePath = $path . '/'.$oldImageName;
                        if( $filePath != '' && file_exists( $filePath ) ){
                            unlink($filePath);
                        }
                }
                return $this->redirect(['action' => 'index']);
            } else {

                // $this->Flash->error(__('The legacy reward could not be saved. Please, try again.'));
                $this->Flash->error(__('ENTITY_ERROR', 'legacy reward'));
            }
        }
        $vendors = $this->LegacyRewards->Vendors->find('list', ['limit' => 200]);
        $rewardCategories = $this->LegacyRewards->RewardCategories->find('list', ['limit' => 200]);
        $productTypes = $this->LegacyRewards->ProductTypes->find('list', ['limit' => 200])->where(['name IS NOT' => 'Amazon/Tango'])->all();
        $this->set(compact('legacyReward', 'vendors', 'rewardCategories', 'productTypes'));
        $this->set('_serialize', ['legacyReward']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Delete method
     *
     * @param string|null $id Legacy Reward id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $legacyReward = $this->LegacyRewards->find('all')->where(['LegacyRewards.id' => $id])->contain([])->first();
        if($legacyReward)
        {
            if ($this->LegacyRewards->delete($legacyReward)) {
                $this->LegacyRewards->VendorLegacyRewards->deleteAll(['legacy_reward_id' => $id]);
                // $this->Flash->success(__('The legacy reward has been deleted.'));
                $this->Flash->success(__('ENTITY_DELETED', 'legacy reward'));
            } else {
                // $this->Flash->error(__('The legacy reward could not be deleted. Please, try again.'));
                $this->Flash->error(__('ENTITY_DELETED_ERROR', 'legacy reward'));
            }

            return $this->redirect(['action' => 'index']);
        }else {
            $this->Flash->error(__('RECORD_NOT_FOUND'));
            return $this->redirect(['action' => 'index']);
        }
    }
    // public function isAuthorized($user){

    //     if(in_array($user['role']['label'], ['Staff Admin', 'Admin', 'Staff Manager']))
    //     return parent::isAuthorized($user); 
    // }
}