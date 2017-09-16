<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;

/**
 * Staff Report Shell Command
 */
class GiftCouponExpirationShell extends Shell
{

	public function giftCouponExpiration(){

      $this->loadModel('GiftCoupons');
      $this->loadModel('GiftCouponAwards');

      $couponAwards = $this->GiftCouponAwards->find()
                                            ->contain(['GiftCoupons'])
                                            ->where(['status' => 1])
                                            ->all()
                                            ->toArray();

        foreach ($couponAwards as $singleCouponAward) {

          $createdDate = new Date($singleCouponAward['created']);
          
          if(!$createdDate->wasWithinLast($singleCouponAward['gift_coupon']['expiry_duration'].' days')){

            $toBeDeactivated[] = $singleCouponAward;
            
            $statusDisablingArr[] = ['id' => $singleCouponAward['id'],
                                     'status' => 0
                                    ];
          }
        }
        
        if(isset($toBeDeactivated)){
          
            $saveEntities = $this->GiftCouponAwards->patchEntities($toBeDeactivated,$statusDisablingArr);
            $saveEntities = $this->GiftCouponAwards->saveMany($saveEntities);
            
            if($saveEntities){
              $this->log('Disabled the following awarded coupons', 'debug');
              $this->log($saveEntities, 'debug');
            }else{
              $this->log("Couldn't disable the gift coupon(s)", 'error');
              $this->log($saveEntities, 'error');
            }
            
          }else{
            $this->log('No Coupon present to be disabled.', 'debug');
        }  
    }

}