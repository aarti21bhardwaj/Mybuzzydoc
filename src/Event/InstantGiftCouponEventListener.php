<?php
namespace App\Event;

use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\I18n\Date;
use Cake\Log\Log;

class InstantGiftCouponEventListener implements EventListenerInterface {


	public function __construct(){
		$controller = new Controller();
    	$this->PatientVisitSpendings = $controller->loadModel('PatientVisitSpendings');
    	$this->VendorInstantGiftCouponSettings = $controller->loadModel('VendorInstantGiftCouponSettings');
	}

	public function implementedEvents()
  	{
    	return [
   		         'InstantGiftCoupon.isAchieved' => 'isRewardUnlocked',
		          ];
  	}

  	public function isRewardUnlocked($event, $data){
  		$hashData = $data['hashData'];
  		Log::write('debug', "data recieved is: ".json_encode($hashData));
      //fetch the vendors instant gift coupons settings
  		$instantGcSetting = $this->VendorInstantGiftCouponSettings->findByVendorId($hashData['vendor_id'])
  																  ->first();

      //if settings are found, proceed further
  		if(!$instantGcSetting){
  			Log::write('debug', "Instant Gift Coupon Setting was not found.");
  		}else{
	  		//check if entry exists in patient_visit_spendings table
	  		$ptVisitSpending = $this->_isPatientVisitSpendingExistent($hashData['patient_id'], $hashData['vendor_id']);
	  		if(!$ptVisitSpending){
          //if ptVisitSpending  entity does not exist, create new. 
	  			$ptVisitSpending = $this->_createPatientVisitSpending($hashData, $instantGcSetting);
	  			if(!$ptVisitSpending){
	  				return false;
            Log::write('debug', "_createPatientVisitSpending method returned");
	  			}
	  		}

	  		//check if threshold_time_period hasn't expired, add points/amount spent to the existing value.
	  		$thresholdTpExpired = $this->_isThresholdTimePeriodExpired($ptVisitSpending, $instantGcSetting);
        if(!$thresholdTpExpired){
          Log::write('debug', "threshold hasn't expired. Hence, increment points");
	  			//check if threshold has been acheieved, set instant_reward_unlocked to true.
	  			$ptVisitSpending = $this->_incrementPoints($ptVisitSpending, $hashData, $instantGcSetting);
	  		  if(!$ptVisitSpending){
            return false;
          }
        }
        return $ptVisitSpending;
  		}
  	}

  	private function _isPatientVisitSpendingExistent($ptId, $vendorId){
      //check if an entry exists in PatientVisitSpendings table for the patient.
  		$ptVisitSpending = $this->PatientVisitSpendings->findByPeoplehubUserId($ptId)
  													   ->where(['vendor_id' => $vendorId])
  													   ->first();
  		if($ptVisitSpending){
  			return $ptVisitSpending;
  		}else{
  			return false;
  		}
  	}

  	private function _createPatientVisitSpending($hashData, $instantGcSetting){
  		//create ptVisitSpending entry.
      $instantRewardUnlocked = 0;
  		// if($instantGcSetting->amount_spent_threshold <= $hashData['amount_spent']){
  		// 	$instantRewardUnlocked = 1;
  		// }
  		// if($instantGcSetting->points_earned_threshold <= $hashData['points_taken']){
  		// 	$instantRewardUnlocked = 1;
  		// }

  		$data = ['vendor_id' => $hashData['vendor_id'], 'peoplehub_user_id' => $hashData['patient_id'], 'amount_spent' => 0, 'points_taken' => 0, 'instant_reward_unlocked' => $instantRewardUnlocked];
  		$ptVisitSpending = $this->PatientVisitSpendings->newEntity($data);
  		Log::write('debug', "PatientVisitSpendings entity is : ".json_encode($ptVisitSpending)); 
  		$ptVisitSpending = $this->PatientVisitSpendings->save($ptVisitSpending);

  		if(!$ptVisitSpending){
  			Log::write('debug', "PatientVisitSpendings could not be saved.");
  			return false;
  		}
  		Log::write('debug', "PatientVisitSpendings were saved.");
  		return $ptVisitSpending;
  	}

    //checks if the threshold time period has expired for the ptVisitSpending
  	private function _isThresholdTimePeriodExpired($ptVisitSpending, $instantGcSetting){
  		Log::write('debug', "PatientVisitSpendings entity is: ".json_encode($ptVisitSpending));
      $createdDate = new Date($ptVisitSpending->created);
          
      	if(!$createdDate->wasWithinLast($instantGcSetting->threshold_time_period.' hours')){
      		return false;
      	}
      	return true;
  	}

    //increment points/amount in the pt visit spending entity.
  	private function _incrementPoints($ptVisitSpending, $hashData, $instantGcSetting){
  		
      $amt = $ptVisitSpending->amount_spent;
  		$points = $ptVisitSpending->points_taken;
      if($hashData['amount_spent'] > 0){
  			$amt += $hashData['amount_spent'];
  		}
  		if($hashData['points_taken'] > 0){
  			$points += $hashData['points_taken'];
  		}

  		$data = ['amount_spent' => $amt, 'points_taken' => $points];

  		$ptVisitSpending = $this->PatientVisitSpendings->patchEntity($ptVisitSpending, $data);
  		Log::write('debug', "PatientVisitSpendings patched entity is : ".json_encode($ptVisitSpending));
  		$ptVisitSpending = $this->PatientVisitSpendings->save($ptVisitSpending);

  		if(!$ptVisitSpending){
  			Log::write('debug', "PatientVisitSpendings could not be saved.");
			  return false;
  		}
  		Log::write('debug', "PatientVisitSpendings were saved.");
      //call _isThresholdAcheived() to check if the threshold  has been acheived or not
  		$response = $this->_isThresholdAchieved($ptVisitSpending, $instantGcSetting);
  		if($response){
  			return $response;
  		}else{
  			return false;
  		}
  	}

    //checks if the threshold has been acheived for a ptVisitSpending entity in the current iteration or not.
  	private function _isThresholdAchieved($ptVisitSpending, $instantGcSetting){
  		
      $instantRewardUnlocked = $ptVisitSpending->instant_reward_unlocked;
      $newInstantRewardUnlocked = 0;
  		//check if either the points taken or the amount spent have crossed their corresponding thresholds. If yes, update the value of $newInstantRewardUnlocked to true.
      if($ptVisitSpending->points_taken >= $instantGcSetting->points_earned_threshold){
      	$newInstantRewardUnlocked = 1;
  		}
  		if($ptVisitSpending->amount_spent >= $instantGcSetting->amount_spent_threshold){
        $newInstantRewardUnlocked = 1;
  		}

      Log::write('debug', "The new value of instantRewardUnlocked is: ".$newInstantRewardUnlocked);
  		//if the rewards have been unlocked in the current iteration, update the unlock_time in the ptVisitSpending entry.
      if($newInstantRewardUnlocked == 1){
  			$data = ['instant_reward_unlocked' => $newInstantRewardUnlocked];
        if($instantRewardUnlocked != $newInstantRewardUnlocked){
          Log::write('debug', "rewards have been unlocked");
          $data['unlock_time'] = date('Y-m-d H:i:s');
        }
        
	  		$ptVisitSpending = $this->PatientVisitSpendings->patchEntity($ptVisitSpending, $data);
	  		Log::write('debug', "PatientVisitSpendings entity is : ".json_encode($ptVisitSpending));
	  		$ptVisitSpending = $this->PatientVisitSpendings->save($ptVisitSpending);

	  		if(!$ptVisitSpending){
	  			Log::write('debug', "PatientVisitSpendings could not be saved.");
          return false;
	  		}
	  		Log::write('debug', "PatientVisitSpendings were saved.");
        // $this->_emailEvent($ptVisitSpending);
  		}
      $unlockedNow = 0;
      if($instantRewardUnlocked != $newInstantRewardUnlocked){
        $unlockedNow = 1;
      }

  		return ['entity' => $ptVisitSpending, 'unlockedNow' => $unlockedNow];
  	}

}

?>