<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;


/**
 * InstantGiftCouponExpiration shell command.
 */
class InstantGiftCouponExpirationShell extends Shell
{

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    public function updateUnlockTime(){
        $this->loadModel('PatientVisitSpendings');
        $ptVisitData = $this->PatientVisitSpendings->find('withTrashed')
                                                   ->where(['unlock_time IS NULL', 'instant_reward_unlocked' => 1])
                                                   ->all()->toArray();

        $data = [];
        foreach ($ptVisitData as $entity) {
            $data[] = ['id' => $entity->id, 'unlock_time' => $entity->modified];
        }
       
        if(count($data) > 0){
            $ptVisitSpendingEnitites = $this->PatientVisitSpendings->patchEntities($ptVisitData, $data);
            $ptVisitSpendingEnitites = $this->PatientVisitSpendings->saveMany($ptVisitSpendingEnitites);
            if($ptVisitSpendingEnitites){
                $this->out('entities have been saved');
            }
        }

    }

    public function isExpired(){
        $this->loadModel('VendorInstantGiftCouponSettings');
        $this->loadModel('PatientVisitSpendings');

        $vendorInstantGcSetting = $this->VendorInstantGiftCouponSettings->find()
                                                                 ->all()
                                                                 ->indexBy('vendor_id')
                                                                 ->toArray();
        // pr($vendorInstantGcSetting);die;
        $ptVisitSpendings = $this->PatientVisitSpendings->find()
                                                        ->all();
        // pr(date('Y-m-d H:i:s'));die;
        $count = 0;
        foreach ($ptVisitSpendings as $ptVisitData) {
            // pr($ptVisitData); die;
            $createdDate = new Date($ptVisitData->created);
            $modifiedDate = new Date($ptVisitData->unlock_time);
            $thresholdTp = $vendorInstantGcSetting[$ptVisitData->vendor_id]->threshold_time_period;
            $redemptionExpiry = $vendorInstantGcSetting[$ptVisitData->vendor_id]->redemption_expiry;
            
            if(!$createdDate->wasWithinLast($thresholdTp.' hours') && !$ptVisitData->instant_reward_unlocked){
            
                pr('expired threshold period');
                $endOffer = ['id' => $ptVisitData->id, 'is_deleted' => date('Y-m-d H:i:s')];
                $count++;
            }
            if(!$createdDate->wasWithinLast($thresholdTp.' hours') && $ptVisitData->instant_reward_unlocked && !$modifiedDate->wasWithinLast($redemptionExpiry.' hours')){
            
                pr('expired redemption period');
                $endOffer = ['id' => $ptVisitData->id, 'is_deleted' => date('Y-m-d H:i:s')];   
                $count++;
            }

            if(isset($endOffer)){
                $ptVisitSpendingEntity[] = $endOffer;
            }

        }

        $this->out($count.' offers have expired');
        
        if(isset($ptVisitSpendingEntity) && count($ptVisitSpendingEntity) > 0){
            $ptVisitSpendingEnitites = $this->PatientVisitSpendings->patchEntities($ptVisitSpendings, $ptVisitSpendingEntity);
            $ptVisitSpendingEnitites = $this->PatientVisitSpendings->saveMany($ptVisitSpendingEnitites);
            if($ptVisitSpendingEnitites){
                $this->out('The entities have been saved');
            }
        }

    }
}
