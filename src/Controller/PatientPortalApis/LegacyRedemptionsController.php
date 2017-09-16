<?php
namespace App\Controller\PatientPortalApis;

use App\Controller\PatientPortalApis\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;

/**
 * Legacy Redemptions Controller
 *
 * @property \App\Model\Table\LegacyRedemptionsTable $legacyRedemptions
 */
class LegacyRedemptionsController extends ApiController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
	}

	public function getPatientActivity($id = null)
	{
		if(!$this->request->is(['get'])){
			throw new BadRequestException(__('BAD_REQUEST'));
		}
		if(!$this->request['id']){
			throw new BadRequestException(__('user id missing'));
		}
		$updateArray=[];
		$profileId = $this->PeopleHub->getPatientActivity( $this->Auth->user('vendor_peoplehub_id'), $id);
		// pr($profileId);die;
		if(!(is_array($profileId) && isset($profileId['status']))){
			foreach ($profileId->data as $key => $value) {
			//	pr($value);
				if($value->id){
					$updateArray[] = $value->id;
				}
			}
		}


		if(empty($updateArray)){
			$updateArray[] = 0;
		}
		//pr($updateArray); die;
		$this->loadModel('Vendors');
		$patientActivityHistory = $this->Vendors->findById($this->Auth->user('vendor_id'))
		->contain(['LegacyRedemptions' => function ($q) use ($updateArray) {
        return $q->contain(['LegacyRewards'])
        		->where(['LegacyRedemptions.transaction_number IN ' =>$updateArray]);
    }, 'Users.PromotionAwards'=> function ($q) use ($updateArray) {
        return $q
        	->contain(['VendorPromotions.Promotions'])
        	->where(['PromotionAwards.peoplehub_transaction_id IN ' =>$updateArray]);
    }, 'Users.ReferralAwards'=> function ($q) use ($updateArray) {
        return $q->where(['ReferralAwards.peoplehub_transaction_id IN ' =>$updateArray]);
    }, 'Users.ManualAwards'=> function ($q) use ($updateArray) {
        return $q->where(['ManualAwards.peoplehub_transaction_id IN ' =>$updateArray]);
    },	'Users.TierAwards'=> function ($q) use ($updateArray) {
        return $q
        		->contain(['Tiers'])
        		->where(['TierAwards.peoplehub_transaction_id IN ' =>$updateArray]);
    }, 'Users.ReviewAwards'=> function ($q) use ($updateArray) {
        return $q
		        ->contain(['ReviewTypes'])
		        ->where(['ReviewAwards.peoplehub_transaction_id IN ' =>$updateArray]);
    },  'Users.GiftCouponAwards'=> function ($q) use ($id) {
        return $q
		        ->contain(['GiftCouponRedemptions', 'GiftCoupons'])
		        ->where(['GiftCouponAwards.redeemer_peoplehub_identifier ' =>$id]);

    }, 'Users.SurveyAwards' => function ($q) use ($updateArray) {
		return $q
				->where(['SurveyAwards.peoplehub_transaction_id IN' => $updateArray]);
    }

    ])->first();

		// pr($patientActivityHistory); die;
		$patientActivity['review_awards'] = array();
		$patientActivity['referral_awards'] = array();
		$patientActivity['promotions'] = array();
		$patientActivity['tiers'] = array();
		$patientActivity['manual_awards'] = array();
		$patientActivity['gift_coupon_awards'] = array();
		$patientActivity['compliance_survey_awards'] = array();


		if(empty($patientActivityHistory)){
			throw new BadRequestException(__('No Record Found'));
		}
		//pr($patientActivityHistory); die();
		foreach ($patientActivityHistory->users as $user) {
		if(!empty($user->review_awards)) {
			$patientActivity['review_awards'] = array_merge($patientActivity['review_awards'],$user->review_awards);
		}
		if(!empty($user->referral_awards)){
			$patientActivity['referral_awards'] = array_merge($patientActivity['referral_awards'],$user->referral_awards);
		}
		if(!empty($user->promotion_awards)){
			$patientActivity['promotions'] = array_merge($patientActivity['promotions'],$user->promotion_awards);
		}
		if(!empty($user->tier_awards)){
			$patientActivity['tiers'] = array_merge($patientActivity['tiers'],$user->tier_awards);
		}
		if(!empty($user->manual_awards)){
			$patientActivity['manual_awards'] = array_merge($patientActivity['manual_awards'],$user->manual_awards);
		}
		if(!empty($user->gift_coupon_awards)){
			$patientActivity['gift_coupon_awards'] = array_merge($patientActivity['gift_coupon_awards'],$user->gift_coupon_awards);
		}
		if(!empty($user->survey_awards)){

			$patientActivity['compliance_survey_awards'] = array_merge($patientActivity['compliance_survey_awards'],$user->survey_awards);

		}
		// $patientActivity['manual_awards'][] = $user->manual_awards;
		// $patientActivity['gift_coupon_awards'][] = $user->gift_coupon_awards;
		}

		//pr($patientActivityHistory); die;
		$patientActivity['redemptions'] = $patientActivityHistory->legacy_redemptions;
		unset($patientActivityHistory);
		$this->set('activityHistory', $patientActivity);
        $this->set('_serialize', ['activityHistory']);
	}


  /**
  * @api {GET} /patient_portal_apis/LegacyRedemptions/getPatientSpecificActivityHistory/ Search Activity
  * @apiDescription Patient Activity.
  * @apiVersion 1.1.0
  * @apiName Search Activity
  * @apiGroup Patient Portal Apis
  * @apiHeader {String}
  * @apiHeaderExample {php} Header-Example:
  * @apiParam {String} [attributeType] can be promotion_awards, tier_awards', review_awards, referral_awards, manual_awards, gift_coupon_awards, survey_awards
  * @apiParam {String} value contains search key.
  * @apiSampleRequest http://buzzy.twinspark.co/patient_portal_apis/LegacyRedemptions/getPatientSpecificActivityHistory/?attributeType=promotion_awards&attributeValue=2&userId=5
  * @apiSuccessExample Success-Response:
  * HTTP/1.1 200 OK
  *
  *  {
  *    "promotion_awards": [
  *        {
  *            "id": 2,
  *            "user_id": 5,
  *            "points": 12,
  *            "peoplehub_transaction_id": 12,
  *            "created": "2016-12-12T00:00:00+00:00",
  *            "modified": "2016-12-07T00:00:00+00:00",
  *            "vendor_promotion_id": 1
  *        }
  *    ]
  *}
  */

	public function getPatientSpecificActivityHistory(){
	if (!$this->request->is(['get'])) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }
    $attributeType = strtolower($this->request->query('attributeType'));
    //pr($attributeType);
    $attributeValue = $this->request->query('attributeValue');
    //pr($attributeValue); die('ssss');
    $userId = $this->request->query('userId');
    //pr($userId); die('user id');
    if(empty($attributeType)){
    	throw new BadRequestException(__('BAD_REQUEST'));
    }

    if(empty($userId)){
    	throw new BadRequestException(__('BAD_REQUEST'));
    }

    if(empty($attributeValue)){
      throw new BadRequestException(__('BAD_REQUEST'));
    }

    if (!empty($attributeType) && !in_array($attributeType, array('promotion_awards','tier_awards','review_awards','referral_awards','manual_awards','gift_coupon_awards','survey_awards'))){
      $attributeType = 'all';
    }
    switch ($attributeType) {
		//Get Promotion Awards
		case 'promotion_awards':
		$this->loadModel('PromotionAwards');
		$promotion_awards = $this->PromotionAwards->find()
													->where(['id'=>$attributeValue,'user_id LIKE'=> $userId]);
		$this->set('promotion_awards', $promotion_awards);
		$this->set('_serialize', ['promotion_awards']);
		break;
		//Get Tier Awards
		case 'tier_awards':
		$this->loadModel('TierAwards');
		$tier_awards = $this->TierAwards->find()
											->where(['id' => $attributeValue,'user_id' => $userId]);
		$this->set('tier_awards', $tier_awards);
		$this->set('_serialize',['tier_awards']);
		break;
		//Get Review Awards
		case 'review_awards':
		$this->loadModel('ReviewAwards');
		$review_awards = $this->ReviewAwards->find()->where(['id' => $attributeValue,'user_id' => $userId]);
		$this->set('review_awards', $review_awards);
		$this->set('_serialize',['review_awards']);
		break;
		//Get Referral Awards
		case 'referral_awards':
		$this->loadModel('ReferralAwards');
		$referral_awards = $this->ReferralAwards->find()->where(['id' => $attributeValue,'user_id' => $userId]);
		$this->set('referral_awards', $referral_awards);
		$this->set('_serialize',['referral_awards']);
		break;
		//Get Manual Awards
		case 'manual_awards':
		$this->loadModel('ManualAwards');
		$manual_awards = $this->ManualAwards->find()->where(['id' => $attributeValue,'user_id' => $userId]);
		$this->set('manual_awards', $manual_awards);
		$this->set('_serialize',['manual_awards']);
		break;
		//Get Gift Coupon Awards
		case 'gift_coupon_awards':
		$this->loadModel('GiftCouponAwards');
		$gift_coupon_awards = $this->GiftCouponAwards->find()->where(['id' => $attributeValue,'user_id' => $userId]);
		$this->set('gift_coupon_awards', $gift_coupon_awards);
		$this->set('_serialize',['gift_coupon_awards']);
		break;
		//Get Compliance Survey Awards
		case 'survey_awards':
		$this->loadModel('SurveyAwards');
		$survey_awards = $this->SurveyAwards->find()->where(['id' => $attributeValue,'user_id' => $userId]);
		if(empty($survey_awards)){
			throw new BadRequestException(__('No Record Found'));
		}
		$this->set('survey_awards', $survey_awards);
		$this->set('_serialize',['survey_awards']);
		break;
    }


    /*$this->loadModel('PromotionAwards');
    $promotion_awards = $this->PromotionAwards->find('all')->where(['user_id LIKE'=>$attributeValue.'%'])->toArray();
    pr($promotion_awards); die;

    $this->loadModel('TierAwards');
    $tier_awards = $this->TierAwards->find('all')->where(['user_id LIKE'=>$attributeValue.'%'])->toArray();
    pr($tier_awards); die('yeyeye');*/

	}

}

?>
