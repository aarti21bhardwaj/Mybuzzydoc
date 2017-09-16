<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Collection\Collection;

/**
 * QuickReports Controller
 *
 * @property \App\Model\Table\QuickReportsTable $QuickReports
 */
class QuickReportsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $fromDate = Time::now()->subMonth();
        $toDate =  Time::now();

        // pr($fromDate); pr($toDate); die;
        $this->loadModel('Vendors');
        $quickReports = $this->Vendors->find()->contain(['ManualAwards'=>function($query) use($fromDate, $toDate) { 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            // 'LegacyRedemptions.LegacyRedemptionAmounts'=>function($query) { 
            // return $query->select(['LegacyRedemptionAmounts.legacy_redemption_id','totalPoints' => $query->func()->sum('LegacyRedemptionAmounts.amount')]); },
            'MilestoneLevelAwards'=>function($query) use($fromDate, $toDate){ 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'PromotionAwards'=>function($query) use($fromDate, $toDate){ 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'ReferralAwards'=>function($query) use($fromDate, $toDate) { 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'ReferralTierAwards'=>function($query) use($fromDate, $toDate) { 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'ReviewAwards'=>function($query) use($fromDate, $toDate) { 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'SurveyAwards'=>function($query) use($fromDate, $toDate){ 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate,])->group('vendor_id'); },
            'TierAwards'=>function($query) use($fromDate, $toDate){ 
            return $query->select(['vendor_id','totalPoints' => $query->func()->sum('points')])->where(['created >=' => $fromDate,
            'created <=' => $toDate])->group('vendor_id'); }
        ])->toArray();


        $this->set(compact('quickReports', 'fromDate', 'toDate'));
        $this->set('_serialize', ['quickReports']);
    }


    public function patients(){
        $fromDate = Time::now()->subMonth();
        $toDate =  Time::now();

        $this->loadComponent('PeopleHub');
        $response = $this->PeopleHub->getResellerActivities(['from' => $fromDate, 'to' => $toDate]);
        $response = json_decode(json_encode($response->data), True);
        
        //This avg is vendor wise(Vendor people hub id)
        $average = (new Collection($response))->groupBy('vendor_id')->map(function ($value, $key) {
            
            $arr = (new Collection($value))->groupBy('date')->map(function($val1, $key1){
                return count(array_unique((new Collection($val1))->extract('user_id')->toArray()));
            })->toArray();

            return round(array_sum($arr)/30, 2); 
        
        })->toArray();

        $this->loadModel('Vendors');
        $vendors = $this->Vendors->find()->all()->toArray();

        $this->set(compact('vendors', 'average', 'fromDate', 'toDate'));
        $this->set('_serialize', ['vendors']);
        // pr($quickReports ); die;
    }
    
}
