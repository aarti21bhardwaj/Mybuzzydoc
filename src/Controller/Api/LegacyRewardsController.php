<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Legacy Rewards Controller
 *
 * @property \App\Model\Table\LegacyRewardsTable $legacyRewards
 */
class LegacyRewardsController extends ApiController
{
    const SUPER_ADMIN_LABEL = 'admin';

    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $productTypeCondition = '';
        $productType = $this->request->query('product_type');
        if(!empty($productType)){
            $productType = $this->request->query('product_type');
            $productTypeCondition = ['product_type_id' => $productType];
        }
        $this->loadModel('VendorLegacyRewards');
        $this->loadModel('VendorSettings');
        $settings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                         ->contain(['SettingKeys' => function($q){
                                                            return $q->where(['OR' => [['name' => 'Products And Services'], ['name' => 'Admin Products']]]);
                                            }])
                                         ->all()
                                         ->combine('setting_key.name', 'value')
                                         ->toArray();

        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name != self::SUPER_ADMIN_LABEL){
          if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 0){
                // pr(' m here when pS enabled');
                $vendorlegacyReward = $this->VendorLegacyRewards->findByVendorId($this->Auth->user('vendor_id'))                                            ->contain(['LegacyRewards'])
                                                                ->where(['LegacyRewards.vendor_id' => $this->Auth->user('vendor_id'), 'VendorLegacyRewards.status' => 1, 'LegacyRewards.product_type_id NOT IN' => [1,3]])
                                                                ->all()
                                                                ->toArray();
                // pr($vendorlegacyReward);
            }else if($settings['Products And Services'] == 1 && $settings['Admin Products'] == 1){
                $vendorlegacyReward = $this->VendorLegacyRewards->findByVendorId($this->Auth->user('vendor_id'))                                            ->contain(['LegacyRewards'])
                                                                ->where(['LegacyRewards.status' => 1, 'VendorLegacyRewards.status' => 1, 'LegacyRewards.product_type_id NOT IN' => [1,3]])
                                                                ->all()
                                                                ->toArray();
                // pr($vendorlegacyReward);
            }  
        }

            // if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            //     $legacyReward = $this->LegacyRewards->find()
        				// 							->contain(['Vendors', 'RewardCategories', 'ProductTypes'])
            //                                         ->where($productTypeCondition)
        				// 							->all();
            // }else{
            //     $legacyReward = $this->LegacyRewards->find()
            //                                         ->where(['OR' => [['vendor_id' => $this->Auth->user('vendor_id')], ['vendor_id =' => 1]]])->where(['LegacyRewards.status'=>1])
            //                                         ->contain(['Vendors', 'RewardCategories', 'ProductTypes'])
            //                                         ->where($productTypeCondition)
            //                                         ->all();
            // }
        $this->set(compact('vendorlegacyReward','loggedInUser'));
        $this->set('_serialize', ['vendorlegacyReward']);
    }
    
}