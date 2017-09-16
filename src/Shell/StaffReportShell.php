<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Log\Log;
use Cake\Mailer\MailerAwareTrait;
use Cake\Event\Event;

use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\Routing\Router;

use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Controller\Controller;


/**
 * Staff Report Shell Command
 */
class StaffReportShell extends Shell
{

    // public function __construct(){


    // }
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @command : bin/cake StaffReport index
     */


    public function index()
    {
        $to = Date::now();
        $toDate = $to->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT],'en-IR@calendar=india');
        $fromDate = $to->subDays(7)->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT],'en-IR@calendar=india');

        $this->loadModel('Vendors');
        $this->loadModel('Users');
        /*$this->loadModel('PromotionAwards');
        $this->loadModel('Promotions');*/
        $this->loadModel('ReviewAwards');
        $this->loadModel('Vendors');
        $this->loadModel('ReferralAwards');
        $this->loadModel('Referrals');
        $this->loadModel('ManualAwards');
        $this->loadModel('ReviewAwards');
        $this->loadModel('ReviewTypes');
        $this->loadModel('LegacyRedemptions');
        $this->loadModel('Tiers');
        $this->loadModel('TierAwards');
        // $this->loadModel('CreditCardCharges');
        $seedVendorArray = [1,2,3,4];
        //pr($seedVendorArray); die();
        $vendors = $this->Vendors->find('all')->contain(['Users'])
                                    ->where(['status' => 1,'id NOT IN ' => $seedVendorArray])
                                    ->toArray();
        //list of vendors
        foreach ($vendors as $key => $vendor) {
            //pr($vendor->users['0']->first_name); die();
            //here component will come from peoplehub
            //$this->loadModel('VendorSettings');
        //$liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
        //                                  ->contain(['SettingKeys' => function($q){
        //                                                                     return $q->where(['name' => 'Live Mode']);
        //                                                                 }
        //                                             ])
        //                                  ->first()->value;
        // $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
        $controller = new Controller();
        $peopleHubComponent = $controller->loadComponent('PeopleHub');
        $updateArray=[];
        $profileId = $peopleHubComponent->getStaffReport($vendor->people_hub_identifier);
        if(!(is_array($profileId) && isset($profileId['status']))){
          foreach ($profileId->data as $key => $value) {
              if($value->id){
                  $updateArray[] = $value->id;
              }
          }
        }

        $staffActivityHistory = $this->Vendors->findById($vendor->id)
        ->contain(['LegacyRedemptions' => function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['LegacyRedemptions.transaction_number IN ' =>$updateArray]);
            };
            return $q->contain(['LegacyRewards','LegacyRedemptionStatuses', 'Users']);

        }
        /*, 'Users.PromotionAwards'=> function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['PromotionAwards.peoplehub_transaction_id IN ' =>$updateArray]);
            };
        return $q->contain(['Promotions']);
        }*/
        , 'Users.ReferralAwards'=> function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['ReferralAwards.peoplehub_transaction_id IN ' =>$updateArray]);
            };

        return $q->contain(['Referrals']);
        }
        , 'Users.ManualAwards'=> function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['ManualAwards.peoplehub_transaction_id IN ' =>$updateArray]);
            };

            return $q;
        }
        ,  'Users.TierAwards'=> function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['TierAwards.peoplehub_transaction_id IN ' =>$updateArray]);
            };
            return $q->contain(['Tiers']);
        }
        , 'Users.ReviewAwards'=> function ($q) use ($updateArray) {
            if(sizeof($updateArray)){
                $q->where(['ReviewAwards.peoplehub_transaction_id IN ' =>$updateArray]);
            };
            return $q->contain(['ReviewTypes']);
        }
        ])->first();
        //pr($staffActivityHistory);
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
        $file = new File('csv/'.$filename.'-legacyAwards.csv', true, 0777);
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

        /*$rowArrayHeader1 = [
                    'Promotion Name',
                    'Points',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader1 = implode(",",$rowArrayHeader1);
        $file1 = new File('csv/'.$filename.'-promotionAwards.csv', true, 0777);
        $file1->append($arrayReviewValHeader1."\r\n");

        foreach ($staffActivityHistory->users[0]->promotion_awards as $key => $promotionValue) {
            $rowArray1 = [
                $promotionValue->promotion->name,
                $promotionValue->points,
                $promotionValue->peoplehub_transaction_id,
            ];
            $arrayPromotionVal = implode(",", $rowArray1);
            $file1->append($arrayPromotionVal."\r\n");
        }*/

        $rowArrayHeader2 = [
                    'Referral From',
                    'Referral To',
                    'Points',
                    'PeopleHub Transaction Id'
                ];
        $arrayReviewValHeader2 = implode(",",$rowArrayHeader2);
        $file2 = new File('csv/'.$filename.'-referralAwards.csv', true, 0777);
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
        $file3 = new File('csv/'.$filename.'-manualAwards.csv', true, 0777);
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
        $file4 = new File('csv/'.$filename.'-tierAwards.csv', true, 0777);
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
        $file5 = new File('csv/'.$filename.'-reviewAwards.csv', true, 0777);
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
                    'file' => 'csv/'.$filename.'-legacyAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                /*"$filename-promotionAwards.csv" => array(
                    'file' => 'csv/'.$filename.'-promotionAwards.csv',
                    'mimetype' => 'text/plain'
                ),*/
                "$filename-referralAwards.csv" => array(
                    'file' => 'csv/'.$filename.'-referralAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-manualAwards.csv" => array(
                    'file' => 'csv/'.$filename.'-manualAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-tierAwards.csv" => array(
                    'file' => 'csv/'.$filename.'-tierAwards.csv',
                    'mimetype' => 'text/plain'
                ),
                "$filename-reviewAwards.csv" => array(
                    'file' => 'csv/'.$filename.'-reviewAwards.csv',
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
    }


    /*public function index()
    {

        $this->loadModel('Users');
        $this->loadModel('PromotionAwards');
        $this->loadModel('ReviewAwards');
        $this->loadModel('Vendors');
        // $this->loadModel('ReferralAwards');
        // $this->loadModel('LegacyRedemptions');
        // $this->loadModel('CreditCardCharges');
        $vendor = $this->Vendors->find('all')->contain(['Users'])
                                    ->where(['status' => 1,'id !=' => 1])
                                    ->toArray();
        $updateArray=[];
        $vendors = $this->Vendors->find('all')->contain(['Users'])
                                    ->where(['status' => 1,'id !=' => 1])
                                    ->toArray();

        $updateArray=[];
        //list of vendors
        foreach ($vendors as $key => $vendor) {

            //users array of specific vendor
            foreach ($vendor->users as $key => $vendorArray) {
                //pr($vendorArray->email);
                if($vendorArray->id){
                    $updateArray[] = $vendorArray->id;
                }

            $this->random = rand(0,999);
            //Generate CSV for Review Awards
            $rewiewAwards = $this->ReviewAwards->find('all')
                                               ->where(['user_id IN' => $updateArray])
                                               ->toArray();

            foreach ($rewiewAwards as $key => $reviewValue) {
                    $arrayReviewVal = implode(",",$reviewValue->toArray());
                    $file = new File('webroot/csv/'.$this->random.'-rewiewAwards.csv', true, 0777);
                    $file->append($arrayReviewVal."\r\n");
                }

            //Generate CSV for Promotion Awards
            $promotionAwards = $this->PromotionAwards->find('all')
                                                     ->where(['user_id IN' => $updateArray])
                                                     ->toArray();

            foreach ($promotionAwards as $key => $promotionValue) {

                    $arrayPromotionVal = implode(",",$reviewValue->toArray());
                    $file = new File('webroot/csv/'.$this->random.'-promotionAwards.csv', true, 0777);
                    $file->append($arrayPromotionVal."\r\n");
                }
            }
        }
            //Email For Review Awards Staff Report
            $email = new Email();
            $email->to($vendorArray->email);
            $email->subject('Review Award Staff Report');
            $email->template('default');
            $email->attachments(['webroot/csv/'.$this->random.'-rewiewAwards.csv']);
            $email->emailFormat('html');
            $email->send();

            //Email For Promotion Awards Staff Report
            $email = new Email();
            $email->to($vendorArray->email);
            $email->subject('Promotion Award Staff Report');
            $email->template('default');
            $email->attachments(['webroot/csv/'.$this->random.'-promotionAwards.csv']);
            $email->emailFormat('html');
            $email->send();

    }*/

}
