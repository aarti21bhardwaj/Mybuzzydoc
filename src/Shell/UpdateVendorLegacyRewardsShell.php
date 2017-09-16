<?php
namespace App\Shell;

use Cake\Console\Shell;

/**
 * UpdateVendorLegacyRewards shell command.
 */
class UpdateVendorLegacyRewardsShell extends Shell
{

   
    public function update()
    {
        $this->loadModel('LegacyRewards');
        $rewards = $this->LegacyRewards->find()
                                       ->where(['vendor_id !=' => 1])
                                       ->all()
                                       ->indexBy('id')
                                       ->toArray();

        $rewardIds = array_keys($rewards);

        $this->loadModel('VendorLegacyRewards');
        $vendorLegacyRewards = $this->VendorLegacyRewards->find()
                                                        ->where(['legacy_reward_id IN' => $rewardIds])
                                                        ->all()
                                                        ->indexBy('legacy_reward_id')
                                                        ->toArray();

        foreach ($rewards as $key => $value) {
            
            if(!isset($vendorLegacyRewards[$key])){

                $data[] = [ 
                            'id' => $key,
                            'status' => 1,
                            'vendor_legacy_rewards' =>  [[
                                                            'vendor_id' => $value['vendor_id'],
                                                            'legacy_reward_id' => $key,
                                                            'status' => $value['status']
                                                        ]]
                                            ];
            }
        }

        if(isset($data)){
            
            $newEntities = $this->LegacyRewards->patchEntities($rewards, $data, ['associated' => 'VendorLegacyRewards']);
            $save = $this->LegacyRewards->saveMany($newEntities);
            if($save){
                $this->out('Vendor Legacy rewards saved for existing legacy rewards by vendors.');
            }
        }
    }
}
