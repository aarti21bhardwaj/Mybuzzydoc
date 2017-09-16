<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Session;
use Cake\Filesystem\File;
use Cake\Mailer\Email;
use Cake\Collection\Collection;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * CreditCardCharges Controller
 *
 * @property \App\Model\Table\CreditCardChargesTable $CreditCardCharges
 */
class CreditCardChargesController extends AppController
{
    /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */

    
    /*public function index()
    {
        $to = Date::now();
        $toDate = $to->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT],'en-IR@calendar=india');
        $fromDate = $to->subDays(7)->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT],'en-IR@calendar=india');
        
        
        $this->loadModel('Users');
        $this->loadModel('PromotionAwards');
        $this->loadModel('ReviewAwards');
        $this->loadModel('Vendors');
        $this->loadModel('ReferralAwards');
        $this->loadModel('LegacyRedemptions');
        // $this->loadModel('CreditCardCharges');
        $vendors = $this->Vendors->find('all')->contain(['Users'])
                                    ->where(['status' => 1,'id !=' => 1])
                                    ->toArray();
                                    //pr($vendors); die;
        //list of vendors
        foreach ($vendors as $key => $vendor) {
            //pr($vendor->users['0']->first_name); die();
            //here component will come from peoplehub

        $this->loadModel('VendorSettings');
        $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                         ->contain(['SettingKeys' => function($q){
                                                                            return $q->where(['name' => 'Live Mode']);
                                                                        }
                                                    ])
                                         ->first()->value;
        $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);

        $profileId = $this->PeopleHub->getStaffReport($vendor->people_hub_identifier);
        $updateArray=[];
        foreach ($profileId->data as $key => $value) {
            //pr($value->vendor->name); die('hhh');
            if($value->id){
                $updateArray[] = $value->id;
            }
        }

        $staffActivityHistory = $this->Vendors->findById($vendor->id)
        ->contain(['LegacyRedemptions' => function ($q) use ($updateArray) {
        return $q->contain(['LegacyRewards','LegacyRedemptionStatuses', 'Users'])
                ->where(['LegacyRedemptions.transaction_number IN ' =>$updateArray]);
        }
        , 'Users.PromotionAwards'=> function ($q) use ($updateArray) {
        return $q
            ->contain(['Promotions'])
            ->where(['PromotionAwards.peoplehub_transaction_id IN ' =>$updateArray]);
        }
        , 'Users.ReferralAwards'=> function ($q) use ($updateArray) {
        return $q->contain(['Referrals'])->where(['ReferralAwards.peoplehub_transaction_id IN ' =>$updateArray]);
        }
        , 'Users.ManualAwards'=> function ($q) use ($updateArray) {
            return $q->where(['ManualAwards.peoplehub_transaction_id IN ' =>$updateArray]);
        }
        ,  'Users.TierAwards'=> function ($q) use ($updateArray) {
            return $q
                    ->contain(['Tiers'])
                    ->where(['TierAwards.peoplehub_transaction_id IN ' =>$updateArray]);
        }
        , 'Users.ReviewAwards'=> function ($q) use ($updateArray) {
            return $q
                    ->contain(['ReviewTypes'])
                    ->where(['ReviewAwards.peoplehub_transaction_id IN ' =>$updateArray]);
        }
        ])->first();
        pr($staffActivityHistory);
        $reportDate = Date::now();
        $reportDate->subDays(7);
        $filename = $reportDate->i18nFormat('yyyy-MM-dd').'-'.Date::now()->i18nFormat('yyyy-MM-dd').'-'.$vendor->id;
        
        $rowArrayHeader = [
                    'Legacy Reward Name',
                    'Legacy Redemption Status',
                    'Redeemer Name',
                    //'Transaction Number',
                    'Points'
                    //'User Name'
                ];
        $arrayReviewValHeader = implode(",",$rowArrayHeader);
        $file = new File('../webroot/csv/'.$filename.'-legacyAwards.csv', true, 0777);
        $file->append($arrayReviewValHeader."\r\n");

        foreach ($staffActivityHistory->legacy_redemptions as $key => $reviewValue) {
                $legarcyRewardName = $reviewValue->toArray();
                $rowArray = [
                    $reviewValue->legacy_reward->name,
                    $reviewValue->legacy_redemption_status->name,
                    $reviewValue->redeemer_name,
                    //$reviewValue->transaction_number,
                    $reviewValue->legacy_reward->points,
                ];
                $arrayReviewVal = implode(",",$rowArray);
                $file->append($arrayReviewVal."\r\n");
            }

        $rowArrayHeader1 = [
                    'Promotion Name',
                    'Points',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader1 = implode(",",$rowArrayHeader1);
        $file1 = new File('../webroot/csv/'.$filename.'-promotionAwards.csv', true, 0777);
        $file1->append($arrayReviewValHeader1."\r\n");

        foreach ($staffActivityHistory->users[0]->promotion_awards as $key => $promotionValue) {
            $rowArray1 = [
                $promotionValue->promotion->name,
                $promotionValue->points,
                $promotionValue->peoplehub_transaction_id,
            ];
            $arrayPromotionVal = implode(",", $rowArray1);
            $file1->append($arrayPromotionVal."\r\n");
        }

        $rowArrayHeader2 = [
                    'Referral From',
                    'Referral To',
                    'Points',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader2 = implode(",",$rowArrayHeader2);
        $file2 = new File('../webroot/csv/'.$filename.'-referralAwards.csv', true, 0777);
        $file2->append($arrayReviewValHeader2."\r\n");

        foreach ($staffActivityHistory->users[0]->referral_awards as $key => $referralValue) {
            $rowArray2 = [
                $referralValue->referral->refer_from,
                $referralValue->referral->refer_to,
                $referralValue->points,
                $referralValue->peoplehub_transaction_id,
            ];
            $arrayReferralVal = implode(",", $rowArray2);
            $file2->append($arrayReferralVal."\r\n");
        }

        $rowArrayHeader3 = [
                    'Points',
                    'Description',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader3 = implode(",",$rowArrayHeader3);
        $file3 = new File('../webroot/csv/'.$filename.'-manualAwards.csv', true, 0777);
        $file3->append($arrayReviewValHeader3."\r\n");

        foreach ($staffActivityHistory->users[0]->manual_awards as $key => $manualValue) {
            $rowArray3 = [
                $manualValue->points,
                $manualValue->peoplehub_transaction_id,
                $manualValue->description,
            ];
            $arrayManualVal = implode(",", $rowArray3);
            $file3->append($arrayManualVal."\r\n");
        }

        $rowArrayHeader4 = [
                    'Points',
                    'Amount',
                    'Tier Name',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader4 = implode(",",$rowArrayHeader4);
        $file4 = new File('../webroot/csv/'.$filename.'-tierAwards.csv', true, 0777);
        $file4->append($arrayReviewValHeader4."\r\n");

        foreach ($staffActivityHistory->users[0]->tier_awards as $key => $tierValue) {
            $rowArray4 = [
                $tierValue->points,
                $tierValue->amount,
                $tierValue->tier->name,
                $tierValue->peoplehub_transaction_id,
                $tierValue->description,
            ];
            $arrayTierVal = implode(",", $rowArray4);
            $file4->append($arrayTierVal."\r\n");
        }

        $rowArrayHeader5 = [
                    'Points',
                    'Review Type',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader5 = implode(",",$rowArrayHeader5);
        $file5 = new File('../webroot/csv/'.$filename.'-reviewAwards.csv', true, 0777);
        $file5->append($arrayReviewValHeader5."\r\n");

        foreach ($staffActivityHistory->users[0]->review_awards as $key => $reviewValue) {
            $rowArray5 = [
                $reviewValue->points,
                $reviewValue->review_type->name,
                $reviewValue->peoplehub_transaction_id,
            ];
            $arrayReviewVal = implode(",", $rowArray5);
            $file5->append($arrayReviewVal."\r\n");
        }



    }  

     //Email For Review Awards Staff Report
            $email = new Email();
            $email->to($staffActivityHistory->users['0']->email);
            $email->subject('Weekly Report for '.$vendor->org_name);
            $email->template('weeklyReport');
            //$email->attachments(['../webroot/csv/'.$filename.'-promotionAwards.csv']);
            $email->attachments(array(
                "$filename-legacyAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-legacyAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-promotionAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-promotionAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-referralAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-referralAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-manualAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-manualAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-tierAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-tierAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-reviewAwards.csv" => array(
                    'file' => '../webroot/csv/'.$filename.'-reviewAwards.csv',
                    'mimetype' => 'text/plain'
                )

            ));
            $email->viewVars([
                                'fromDate' => $fromDate,
                                'toDate' => $toDate,
                                'vendorName' => $vendor->org_name,
                            ]);
            $email->emailFormat('html');
            $email->send();
    }*/

    public function initialize(){    
        parent::initialize();
    }
    
    public function index()
    {   
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
            return $this->redirect(['action' => 'index']);
            }
        } else{   
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
        }
    
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $creditCardCharges = $this->CreditCardCharges->find()->contain(['Users', 'Vendors'])->where(['CreditCardCharges.created >=' => $from,
            'CreditCardCharges.created <' => $to,
            ])->limit(2000)->order(['CreditCardCharges.created' => 'DESC'])->all();
           
        } else{
        $creditCardCharges = $this->CreditCardCharges->findByVendorId($this->Auth->user('vendor_id'))->contain(['Users', 'Vendors'])->where(['CreditCardCharges.created >=' => $from,
            'CreditCardCharges.created <' => $to,
            ])->limit(2000)->order(['CreditCardCharges.created' => 'DESC'])->all();            
        }
        
        $this->set(compact('creditCardCharges'));
        $this->set('_serialize', ['creditCardCharges']);
    }

    /**
     * View method
     *
     * @param string|null $id Credit Card Charge id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $creditCardCharge = $this->CreditCardCharges->get($id, [
            'contain' => ['Users', 'Vendors']
        ]);

        $this->set('creditCardCharge', $creditCardCharge);
        $this->set('_serialize', ['creditCardCharge']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Credit Card Charge id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Credit Card Charge id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
}
