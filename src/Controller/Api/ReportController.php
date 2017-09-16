<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\I18n\Date;
use Cake\Collection\Collection;
use Cake\I18n\Time;
use Cake\Core\Configure;

/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\VendorRedeemedPointsTable 
 */
class  ReportsController extends ApiController
{
 public function initialize(){    
  parent::initialize();
  // $this->Auth->allow(['vendorActivityReport']);
}

public function vendorActivityReport(){
  $vendorId = $this->Auth->user('vendor_id');
  $interval = $this->request->query('interval');
  if(!$interval){
   throw new BadRequestException(__('Please provide valid interval information')); 
    return $this->redirect(['action' => 'vendorActivityReport']);
  }
  
  $fromDate = $this->request->query('from');
  $toDate = $this->request->query('to');

  if($interval == 'custom' && (!$fromDate || !$toDate)){
    throw new BadRequestException(__('Please provide valid date range for custom interval')); 
  }

  $action = $this->request->query('action');
  if(!$action){
    $action = 'manual_awards'; 
  }
  $model = null;
  switch ($action) {
    case 'manual_awards':
    $model = 'ManualAwards';
    break;
    case 'gift_coupon_awards':
    $model = 'GiftCouponAwards';
    break;
    case 'milestone_level_awards':
    $model = 'MilestoneLevelAwards';
    break;
    case 'promotion_awards':
    $model = 'PromotionAwards';
    break;
    case 'referral_awards':
    $model = 'ReferralAwards';
    break;
    case 'referral_tier_awards':
    $model = 'ReferralTierAwards';
    break;
    case 'review_awards':
    $model = 'ReviewAwards';
    break;
    case 'survey_awards':
    $model = 'SurveyAwards';
    break;
    case 'tier_awards':
    $model = 'TierAwards';
    break;
    case 'legacy_redemptions':
    $model = 'LegacyRedemptions';
    break;
    case 'gift_coupon_redemptions':
    $model = 'GiftCouponRedemptions';
    break;
    default:
            // code...
    break;
  }

$format = '%M %d,%Y';
$limit = 5000;
  switch ($interval) {
   case 'month':
   $fromDate = Time::now();
   $fromDate = $fromDate->firstOfMonth();
   $fromDate = $fromDate->format('Y-m-d 00:00:H:i:s');
   $to = Time::now();
   $to->endOfDay();
   $to = $to->format('Y-m-d H:i:s');
   $limit = 10000;
   break;
   case 'week':
   $fromDate = Time::now();
   $fromDate = $fromDate->startOfWeek();
   $fromDate = $fromDate->format('Y-m-d H:i:s');
   $to = Time::now();
   $to->endOfDay();
   $to = $to->format('Y-m-d H:i:s');
   break;
   case 'year':
   $fromDate =  Time::now();
   $fromDate = $fromDate->startOfYear();
   $fromDate = $fromDate->format('Y-m-d H:i:s');
   $to = Time::now();
   $to->endOfDay();
   $to = $to->format('Y-m-d H:i:s');
   $format = '%M %Y';
   $limit = 100000;
   break;

   case 'custom':
   $fromDate = new Date($fromDate);
   $fromDate = $fromDate->format('Y-m-d H:i:s');
   $to = (!empty($to))? new Date($to): Date::now();
   $to->endOfDay();
   $to = $to->format('Y-m-d H:i:s');
   $limit = 10000;
   break;

   default:
   $fromDate = null;
   $to = null;
   break;
 }    


 if(empty($fromDate) || empty($to)){
   $this->Flash->error(__('Please provide valid information')); 
   return $this->redirect(['action' => 'vendorActivityReport']);
 }

 $this->loadModel($model);
 if($model == 'GiftCouponAwards'){
  $awards =    $this->$model->findByVendorId($vendorId);
  $graphData = $awards->select(['date' => $awards->func()->date_format([
    'created' => 'identifier',
    "'$format'" => 'literal'
    ]),'count' => $awards->func()->count('*')])
  ->where(['created >='=>$fromDate , 'created <='=>$to])
  ->group('date')
  ->toArray();

}else if($model == 'GiftCouponRedemptions'){
 $awards = $this->$model->find();
 $awards = $awards->contain([
  'GiftCouponAwards' => function($query) use ($vendorId){ 
    return $query->where(['GiftCouponAwards.vendor_id' => $vendorId]);
  }]);
 $graphData = $awards->select(['date' => $awards->func()->date_format([
  'GiftCouponRedemptions.created' => 'identifier',
  "'$format'" => 'literal'
  ]),'count' => $awards->func()->count('*')])
 ->where(['GiftCouponRedemptions.created >='=>$fromDate , 'GiftCouponRedemptions.created <='=>$to])
 ->group('date')
 ->toArray();
}else if($model == 'LegacyRedemptions'){
            $pointsValue = Configure::read('pointsValue');
            $awards =   $this->$model->findByVendorId($vendorId)->innerJoinWith('LegacyRedemptionAmounts', function($q) use($pointsValue){

                return $q->select(['points' => 'LegacyRedemptionAmounts.amount *'.$pointsValue]);

            })->leftJoinWith('LegacyRewards', function($q) use($pointsValue){

                return $q->select(['points2' => 'LegacyRewards.points', 'amount' => 'LegacyRewards.amount *'.$pointsValue]);

            });

            $awards = $awards->select(['points' => 'points + points2 + amount', 'vendor_id', 'created']);
            $graphData = $awards->select(['date' => $awards->func()->date_format([
                'LegacyRedemptions.created' => 'identifier',
                "'$format'" => 'literal'
                ]),'vendor_id','points' => $awards->func()->sum('points')])
            ->where(['LegacyRedemptions.created >='=>$fromDate , 'LegacyRedemptions.created <='=>$to])
            ->group('date')
            ->toArray(); 
}else{    
  $awards =    $this->$model->findByVendorId($vendorId);
  $graphData = $awards->select(['date' => $awards->func()->date_format([
    'created' => 'identifier',
    "'$format'" => 'literal'
    ]),'vendor_id','points' => $awards->func()->sum('points')])
  ->where(['created >='=>$fromDate , 'created <='=>$to])
  ->group('date')
  ->toArray();
}
$graphData = (new Collection($graphData))->combine('date', 'points')->toArray();
$this->set('action', $action);
$this->set('model', $model);
$this->set('from', $fromDate);
$this->set('graphData', $graphData);
$this->set('to', $to);
$this->set('interval', $interval);
$this->set('limit', $limit);
$this->set('_serialize', ['action','model','from','to','interval','graphData', 'limit']);
}

}
