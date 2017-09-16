<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Collection\Collection;
use Cake\Core\Configure;
/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 */
class ReportsController extends AppController
{   
    public function initialize(){    
        parent::initialize();
    }

    private $_patientNames;
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $reports = $this->paginate($this->Reports);

        $this->set(compact('reports'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => []
            ]);

        $this->set('report', $report);
        $this->set('_serialize', ['report']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $report = $this->Reports->newEntity();
        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->data);
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The report could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('report'));
        $this->set('_serialize', ['report']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => []
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->data);
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The report could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('report'));
        $this->set('_serialize', ['report']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Report id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $report = $this->Reports->get($id);
        if ($this->Reports->delete($report)) {
            $this->Flash->success(__('The report has been deleted.'));
        } else {
            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function _extractPatientName($awardData,$activityData){
        $patientNames = array();
        if(!empty($awardData)){
            foreach ($awardData as $key => $value) {
                if(isset($activityData[$value['peoplehub_transaction_id']]) &&
                   (isset($activityData[$value['peoplehub_transaction_id']]['user'])) &&
                   (!empty($activityData[$value['peoplehub_transaction_id']]['user']))){
                    $this->_patientNames[$value['peoplehub_transaction_id']] =  $activityData[$value['peoplehub_transaction_id']]['user']['name'];
            }
        }
    }
            // return $patientNames;
}


    public function redemptions()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $from = new Date($from);
            $from = date("d-m-Y", strtotime($from));
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            $to = date("d-m-Y", strtotime($to));

            } else {
            $this->Flash->error(__('Please enter a valid date range')); 
            return $this->redirect(['action' => 'redemptions']);
            }
        } else{
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $from = date("d-m-Y", strtotime($from));
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            // $to = implode('-', array_reverse(explode('/',$to)));
            $to = date("d-m-Y", strtotime($to));
        }
    $loggedInUser = $this->Auth->user();
    $this->loadModel('VendorSettings');
    $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
    ->contain(['SettingKeys' => function($q){
        return $q->where(['name' => 'Live Mode']);
    }
    ])
    ->first()->value;
    $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
    $reqData = ['action'=> 'redeem', 'to' => $to, 'from' => $from];
    $peopleHubdata = $this->PeopleHub->getVendorActivity($this->Auth->user('vendor_peoplehub_id'), $reqData);
    if(is_array($peopleHubdata) && isset($peopleHubdata['status'])){
      pr($peopleHubdata);
      die('token error');
    }
        $data = $peopleHubdata->data;


    $this->set('data', $data);
    $this->set('loggedInUser', $loggedInUser);
    $this->set('_serialize', ['data']);
}

public function pointsHistoryReport()
{
    $this->loadModel('Users');
    $this->loadModel('VendorSettings');
    $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
    ->contain(['SettingKeys' => function($q){
        return $q->where(['name' => 'Live Mode']);
    }
    ])
    ->first()->value;
    $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
    $peopleHubdata = $this->PeopleHub->getRedeemerName($this->Auth->user('vendor_peoplehub_id'));
    if(is_array($peopleHubdata) && isset($peopleHubdata['status'])){
      pr($peopleHubdata);
      die('token error');
  }
  $peopleHubdata = json_decode(json_encode($peopleHubdata), True);
  $collection = new Collection($peopleHubdata['data']);
  $userById = $collection->indexBy('id')->toArray();

        // pr($userById);die;
  $patientActivityHistory = $this->Users->findByVendorId($this->Auth->user('vendor_id'))
  ->contain(['PromotionAwards', 'ReferralAwards', 'ReviewAwards', 'TierAwards','GiftCouponAwards',  'MilestoneLevelAwards', 'SurveyAwards','ManualAwards'])->all();

  foreach ($patientActivityHistory as $staffUser) {
    if(!empty($staffUser->tier_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->tier_awards, $userById);       
    }
    if(!empty($staffUser->promotion_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->promotion_awards, $userById);       
    }
    if(!empty($staffUser->referral_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->referral_awards, $userById);          
    }
    if(!empty($staffUser->review_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->review_awards, $userById);       
    }
    if(!empty($staffUser->gift_coupon_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->gift_coupon_awards, $userById);       
    }
    if(!empty($staffUser->milestone_level_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->milestone_level_awards, $userById);       
    }
    if(!empty($staffUser->survey_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->survey_awards, $userById);       
    }
    if(!empty($staffUser->manual_awards)){
        $patientNames[] = $this->_extractPatientName($staffUser->manual_awards, $userById);       
    }
}
$nameList= array();
$patientNames = $this->_patientNames;
$this->_patientNames = '';
if ($this->request->is('put') || $this->request->is('post')) {

    $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);

    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            // James Kukreja: - Updated preg match to check mm/dd/yyyy instead of mm-dd-yyyy
            $reportFromDate = $fromToDateRangeArray[0];
            $reportFromDate = new Date($reportFromDate);
            $reportToDate = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $reportToDate = new Date($reportToDate);
            $reportToDate = $reportToDate->addDays(1);
            $whereCondition = ['created >=' => $reportFromDate,
            'created <' => $reportToDate,
            ];
        }
     else {
      $this->Flash->error(__('Please enter a valid date range')); 
      return $this->redirect(['action' => 'pointsHistoryReport']);
     }
    // pr($fromToDateRangeArray);




    $staffReports = $this->Users->findByVendorId($this->Auth->user('vendor_id'))


    ->contain([ 

        'GiftCouponAwards' => function($q) use ($whereCondition){
            return $q->where(['GiftCouponAwards.created >=' => $whereCondition['created >='],
                'GiftCouponAwards.created <' => $whereCondition['created <'],
                ])
            -> contain(['GiftCoupons'])->limit(2000)->order(['GiftCouponAwards.created' => 'DESC']);
        },

        'MilestoneLevelAwards' => function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['MilestoneLevelAwards.created' => 'DESC']);
        },

        'SurveyAwards' => function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['SurveyAwards.created' => 'DESC']);
        },

        'ManualAwards' => function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['ManualAwards.created' => 'DESC']);
        },

        'PromotionAwards' => function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['PromotionAwards.created' => 'DESC']);
        },
        'ReviewAwards' => function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['ReviewAwards.created' => 'DESC']);
        },
        'ReferralAwards'=> function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['ReferralAwards.created' => 'DESC']);
        },
        'TierAwards'=> function($q) use ($whereCondition){
            return $q->where($whereCondition)->limit(2000)->order(['TierAwards.created' => 'DESC']);
        },
        'LegacyRedemptions'=> function($q) use ($whereCondition){

            return $q->where(['LegacyRedemptions.created >=' => $whereCondition['created >='],
                'LegacyRedemptions.created <' => $whereCondition['created <'],
                ])
            -> contain(['LegacyRewards', 'LegacyRedemptionStatuses', 'LegacyRedemptionAmounts'])->limit(2000)->order(['LegacyRedemptions.created' => 'DESC']);

        }
        ]);
} else {

    $from =isset($_GET['from']) ? $_GET['from'] : null;
        $from = new Date($from);
        $to =isset($_GET['to']) ? $_GET['to'] : null;
        $to = new Date($to);
        $to = $to->addDays(1);

    $staffReports = $this->Users->findByVendorId($this->Auth->user('vendor_id'))->contain(['PromotionAwards'  => function($q)use ($from, $to){ return $q->where(['PromotionAwards.created >=' => $from,
                                'PromotionAwards.created <' => $to,
                                ])->limit(2000)->order(['PromotionAwards.created' => 'DESC']);},
        'ReviewAwards'  => function($q)use($from, $to){ return $q->where(['ReviewAwards.created >=' => $from,
                                'ReviewAwards.created <' => $to,
                                ])->limit(2000)->order(['ReviewAwards.created' => 'DESC']);},
        'ReferralAwards'  => function($q)use($from, $to){ return $q->where(['ReferralAwards.created >=' => $from,
                                'ReferralAwards.created <' => $to,
                                ])->limit(2000)->order(['ReferralAwards.created' => 'DESC']);},
        'LegacyRedemptions'  => function($q)use($from, $to){ return $q->where(['LegacyRedemptions.created >=' => $from,
                                'LegacyRedemptions.created <' => $to,
                                ])->limit(2000)->order(['LegacyRedemptions.created' => 'DESC']);},
        'TierAwards' => function($q)use($from, $to){ return $q->where(['TierAwards.created >=' => $from,
                                'TierAwards.created <' => $to,
                                ])->limit(2000)->order(['TierAwards.created' => 'DESC']);},
        'LegacyRedemptions.LegacyRedemptionStatuses' => function($q){ return $q->limit(2000);},
        'LegacyRedemptions.LegacyRewards' => function($q)use($from, $to){ return $q->limit(2000);},
        'LegacyRedemptions.LegacyRedemptionAmounts' => function($q)use($from, $to){ return $q->where(['LegacyRedemptionAmounts.created >=' => $from,
                                'LegacyRedemptionAmounts.created <' => $to,
                                ])->limit(2000);},
        'GiftCouponAwards'  => function($q)use($from, $to){ return $q->where(['GiftCouponAwards.created >=' => $from,
                                'GiftCouponAwards.created <' => $to,
                                ])->limit(2000)->order(['GiftCouponAwards.created' => 'DESC']);},
        'GiftCouponAwards.GiftCoupons'  => function($q){ return $q->limit(2000);}, 
        'MilestoneLevelAwards'  => function($q)use($from, $to){ return $q->where(['MilestoneLevelAwards.created >=' => $from,
                                'MilestoneLevelAwards.created <' => $to,
                                ])->limit(2000)->order(['MilestoneLevelAwards.created' => 'DESC']);}, 
        'SurveyAwards'  => function($q)use($from, $to){ return $q->where(['SurveyAwards.created >=' => $from,
                                'SurveyAwards.created <' => $to,
                                ])->limit(2000)->order(['SurveyAwards.created' => 'DESC']);}, 
        'ManualAwards' => function($q)use($from, $to){ return $q->where(['ManualAwards.created >=' => $from,
                                'ManualAwards.created <' => $to,
                                ])->limit(2000)->order(['ManualAwards.created' => 'DESC']);}]
        );

}

//$staffReports = $this->paginate($staffReports, $settings);
//pr($staffReports);die;
$this->set('patientNames', $patientNames);
$this->set('staffReports', $staffReports);
$this->set('_serialize', ['staffReports']);
}

    public function selfSignUp(){

        if($this->request->is('post')|| $this->request->is('put')){
            $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $from = new Date($from);
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            } else {
            $this->Flash->error(__('Please enter a valid date range')); 
            return $this->redirect(['action' => 'selfSignUp']);
            }
        } else{   
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
        }

        $this->loadModel('VendorPatients');
        $vendorPatients = $this->VendorPatients
                               ->findByVendorId($this->Auth->user('vendor_id'))
                               ->where(['patient_name IS NOT NULL'])
                               ->andWhere(['user_id IS NULL'])->where(['VendorPatients.created >=' => $from,
                                'VendorPatients.created <' => $to,
                                ])->limit(2000)->order(['VendorPatients.created' => 'DESC'])
                               ->all();
                               
        $this->set(compact('vendorPatients'));
        $this->set('_serialize', ['vendorPatients']);

    }

    public function vendorActivityReport(){
        $vendorId = 5;
        // $vendorId = $this->Auth->user('vendor_id');
        $interval = $this->request->query('interval');
        if(!$interval){
            $interval = 'month';
        }

        $this->set('interval', $interval);
        $this->set('_serialize', ['action','model','from','to','interval','graphData']);    
    }
}