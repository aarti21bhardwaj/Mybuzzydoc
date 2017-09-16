<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;

/**
 * vendor Legacy Rewards Controller
 *
 * @property \App\Model\Table\LegacyRewardsTable $legacyRewards
 */
class VendorLegacyRewardsController extends ApiController
{
	public function add()
    {
        if (!$this->request->is('post')) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $data = $this->request->data;
        $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
        $this->request->data['status'] = 1;

        $vendorLegacyRewards = $this->VendorLegacyRewards->newEntity();
        $existingData = $this->VendorLegacyRewards->find()
          ->where(['vendor_id' => $this->request->data['vendor_id'], 'legacy_reward_id' => $this->request->data['legacy_reward_id']])
          ->first();
          if($existingData){
          	if($existingData->status == 0){
          		$existingData->status = 1;
          		$this->VendorLegacyRewards->save($existingData);
          	}
              $response['status'] = 'OK';
              $response['data'] = $existingData;
              $this->set('response', $response);
          }else{
            $vendorLegacyRewards = $this->VendorLegacyRewards->patchEntity($vendorLegacyRewards, $this->request->data);
            if($this->VendorLegacyRewards->save($vendorLegacyRewards)){
              
                    $response['status'] = 'OK';
                    $response['data'] = $vendorLegacyRewards;

            }else{
                throw new BadRequestException(__('BAD_REQUEST'));
            }
        }

          $this->set('response', $response);
          $this->set('_serialize',['response']);
    }

    public function delete($id = null){

    	$this->request->allowMethod(['post', 'delete']);
    	$vendorLegacyReward = $this->VendorLegacyRewards->findById($id)->first();
    	if(!$vendorLegacyReward){
    		throw new NotFoundException('Record not found');
    	}
    	$vendorLegacyReward->status = 0;
    	if($this->VendorLegacyRewards->save($vendorLegacyReward)){
    		 $response['status'] = 'OK';
             $response['data'] = $vendorLegacyReward;
    	}else{
    		throw new BadRequestException(__('BAD_REQUEST'));
    	}
    	$this->set('response', $response);
        $this->set('_serialize',['response']);
    }
}