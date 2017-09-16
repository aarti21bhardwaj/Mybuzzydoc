<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;

/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
* ReferralLeads Controller
*
* @property \App\Model\Table\VendorRedeemedPointsTable
*/
class  VendorRedeemedPointsController extends ApiController
{


  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
    $this->loadComponent('AuthorizeDotNet');
    $this->Auth->allow(['isRedemptionPossible','add']);
  }


  public function add()
  {
    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }
    $respData =array();
    $data = $this->request->data;
    $vendors = [];
    $mode = $data['mode'];
    unset($data['mode']);
    unset($data['env']);
    foreach ($data as $key => $value) {
      $data[$key]['people_hub_identifier'] =   $value['vendor_id'] ;
      $vendors[] = $value['vendor_id'];
      unset($data[$key]['vendor_id']);
    }
    $this->loadModel('Vendors');
    if($mode == 'demo'){
      $getVendorThreshold = $this->Vendors->find('all')->where(['sandbox_people_hub_identifier IN' => $vendors])->contain(['Users','Users.AuthorizeNetProfiles','VendorDepositBalances'])->toArray();
    }else{
      $getVendorThreshold = $this->Vendors->find('all')->where(['people_hub_identifier IN' => $vendors])->contain(['Users','Users.AuthorizeNetProfiles','VendorDepositBalances'])->toArray();
    }
    if(!$getVendorThreshold){
      $respData['data']['response']=false;
      $respData['data']['message']='Invalid vendor id provided';
      $this->set('response',$respData);
      $this->set('_serialize', ['response']);
      return;
    }
    $vendors = [];
    $vendorBalances = [];
    $pHVendorMap = [];
    $vendorIdWithoutHavingDepositBalance = false;
    foreach ($getVendorThreshold as $key => $valueThreshhold) {
      $vendors[] = $valueThreshhold->id;
      $pHVendorMap[$valueThreshhold->people_hub_identifier] = $valueThreshhold->id;
      if($valueThreshhold['vendor_deposit_balances']){
        $vendorBalances[$valueThreshhold->id] = $valueThreshhold['vendor_deposit_balances'][0]->balance;
      }else{
        $vendorWithoutDepositBalance = $valueThreshhold->people_hub_identifier;
        break;
      }
    }
    foreach ($data as $key => $value) {
      $data[$key]['vendor_id'] = $pHVendorMap[$value['people_hub_identifier']];
    }

    if(count($data)!=count($vendors) && count($data)!=count($vendorBalances)){
      $respData['data']['response']=false;
      $respData['data']['message']='Vendor with PhId '.$vendorWithoutDepositBalance.' is not having any deposit balance info';
      $this->set('response',$respData);
      $this->set('_serialize', ['response']);
      return;
    }

    $updateArray=array();
    foreach ($data as $key => $value) {
      $updateArray[$value['vendor_id']] = $value['amount'];
    }
    $getVendorInfo = [];
  //this is to get vendor info whose card has to be charged
    foreach ($getVendorThreshold as $key => $getVendorThresholds) {
      $dataArray = [];
      foreach($getVendorThresholds->users as $vendorKey => $value ){
        $doWeGotTheProfile = false;
        if(isset($value->authorize_net_profiles) && !empty($value->authorize_net_profiles)){
          foreach($value->authorize_net_profiles as $profileKey => $profileValue ){
            if(!empty($profileValue['profile_identifier']) && !empty($profileValue['payment_profileid'])){
              $dataArray['profile_identifier']= $profileValue['profile_identifier'];
              $dataArray['payment_profileid']= $profileValue['payment_profileid'];
              $dataArray['user_id']= $profileValue['user_id'];
              $dataArray['user_email']= $value->email;
              $dataArray['first_name']= $value->first_name;
              $dataArray['min_deposit']= $getVendorThresholds->min_deposit;
              $dataArray['threshold_value']= $getVendorThresholds->threshold_value;
              $doWeGotTheProfile = true;
              break;
            }
          }
        }
        if($doWeGotTheProfile){
          break;
        }
      }
      if($dataArray){
        $getVendorInfo[$getVendorThresholds->id] = $dataArray;
      }
    }
    if(count($data)!=count($getVendorInfo)){
      $respData['data']['response']=false;
      $respData['data']['message']='Vendors payment profile is not found. Kindly add a Credit Card to redeem points.';
      $this->set('response',$respData);
      $this->set('_serialize', ['response']);
      return;
    }

    if($mode == 'demo'){
      $data =array();
      $data['data']['response']=true;
      $this->set('response',$data);
      $this->set('_serialize', ['response']);
    }else{
      $isValidRedeemption = true;
      $this->loadModel('VendorDepositBalances');
      $updateVendorBalance = null;
      $keyCount = 0;

      $vendorRedeemedHistoryReq = array();
      foreach ($updateArray as $key => $entity) {
        $vendorId = $this->VendorDepositBalances->find()->where(['vendor_id' =>  $key])->first();
        $vendorRedeemedHistoryReqData = [];
        $vendorRedeemedHistoryReqData['vendor_id']=$key;
        $vendorRedeemedHistoryReqData['actual_balance']=$vendorId->balance;
        $vendorRedeemedHistoryReqData['redeemed_amount']=$entity;
        $vendorRedeemedHistoryReqData['remaining_amount']=$vendorId->balance - $entity;

        
        if(($vendorId->balance - $entity) < $getVendorInfo[$key]['threshold_value']){
          $currentDateTime = Time::now();
          $currentDateTime = $currentDateTime->format('H:i:s');
          $data = array();
          $data['user_id'] = $getVendorInfo[$key]['user_id'];
          $data['vendor_id'] = $key;
          $data['balance'] = $getVendorInfo[$key]['min_deposit']-($vendorId->balance - $entity);
          $data['profileId'] = $getVendorInfo[$key]['profile_identifier'];
          $data['payment_profileid'] = $getVendorInfo[$key]['payment_profileid'];
          $data['reason'] = $currentDateTime.' charged via C.C. due to less than threshold amount';
          $data['first_name'] = $getVendorInfo[$key]['first_name'];
          $data['email'] = $getVendorInfo[$key]['email'];
          $vendorRedeemedHistoryReqData['cc_charged_amount']=$getVendorInfo[$key]['min_deposit']-($vendorId->balance - $entity);
        $event = new Event('CreditCard.beforeCharge', $this, [
          'arr' =>[
          'hashData' => $data,
            'eventId' => 5, //give the event_id for which you want to fire the email
            ]
            ]);
        $this->eventManager()->dispatch($event);
        if(isset($event->result['error'])){
          //log this case
        }else{
          if(isset($event->result['response'])){
           $vendorRedeemedHistoryReqData['cc_transaction_identifier']=$event->result['response']['transactionid'];
          }
        }
      }
      $vendorRedeemedHistoryReq[] = $vendorRedeemedHistoryReqData;
      
      $vendorId = $this->VendorDepositBalances->find()->where(['vendor_id' =>  $key])->first();
      $updateReq[] = ['balance'=>$vendorId->balance-$entity,'vendor_id' =>  $key,'id'=>$vendorId->id];
      $updateVendorBalance = $this->VendorDepositBalances->patchEntities($vendorId,$updateReq);
    }
    $this->loadModel('VendorRedemptionHistory');
    $vendorRedeemedHistoryEntity = $this->VendorRedemptionHistory->newEntities($vendorRedeemedHistoryReq);
    $vendorRedeemedHistoryEntity = $this->VendorRedemptionHistory->patchEntities($vendorRedeemedHistoryEntity, $vendorRedeemedHistoryReq);
    if($this->VendorDepositBalances->saveMany($updateVendorBalance)){
      $this->VendorRedemptionHistory->saveMany($vendorRedeemedHistoryEntity);
      $isValidRedeemption = true;
    }else{
       $isValidRedeemption = true;
    }
    $data =array();
    $data['data']['response']=$isValidRedeemption;
    $this->set('response',$data);
    $this->set('_serialize', ['response']);
  }

}

public function isRedemptionPossible()
{
  if(!$this->request->is(['post'])){
    throw new MethodNotAllowedException(__('BAD_REQUEST'));
  }
  $data = $this->request->data;
  $respData =array();
  $vendors = [];
  $amountCorrPhId = [];
  $mode = $data['mode'];
  unset($data['mode']);
  unset($data['env']);
  foreach ($data as $key => $value) {
    $data[$key]['people_hub_identifier'] =  $value['vendor_id'] ;
    $vendors[] = $value['vendor_id'];
    unset($data[$key]['vendor_id']);
    $amountCorrPhId[$value['vendor_id']] = $value['amount'];
  }
  
  $this->loadModel('Vendors');
  
  $vendorPatient = $this->Vendors
                        ->VendorPatients
                        ->findByPatientPeoplehubId($data[0]['user_id'])
                        ->contain(['Vendors' => function($q) use ($vendors){
                            return $q->where(['Vendors.people_hub_identifier IN' => $vendors ]);
                        }])
                        ->where(['redemptions' => 0])
                        ->count();

  if($vendorPatient > 0){
    $respData['data']['response']=false;
    $respData['data']['message']='Patient redemption is turned off by one of the vendors';
    Log::write('debug', json_encode($respData));
    $this->set('response',$respData);
    $this->set('_serialize', ['response']);
    return;
  }

  if($mode == 'demo'){
    $getVendorThreshold = $this->Vendors->find('all')->where(['sandbox_people_hub_identifier IN' => $vendors])->contain(['Users','Users.AuthorizeNetProfiles','VendorDepositBalances'])->toArray();
  }else{
    $getVendorThreshold = $this->Vendors->find('all')->where(['people_hub_identifier IN' => $vendors])->contain(['Users','Users.AuthorizeNetProfiles','VendorDepositBalances'])->toArray();
  }
  if(!$getVendorThreshold){
    $respData['data']['response']=false;
    $respData['data']['message']='Invalid vendor id provided';
    $this->set('response',$respData);
    $this->set('_serialize', ['response']);
    return;
  }
  $getVendorInfo = [];
  //this is to get vendor info whose card has to be charged
  foreach ($getVendorThreshold as $key => $getVendorThresholds) {
    $dataArray = [];
    foreach($getVendorThresholds->users as $vendorKey => $value ){
      $doWeGotTheProfile = false;
      if(isset($value->authorize_net_profiles) && !empty($value->authorize_net_profiles)){
        foreach($value->authorize_net_profiles as $profileKey => $profileValue ){
          if(!empty($profileValue['profile_identifier']) && !empty($profileValue['payment_profileid'])){
            $dataArray['profile_identifier']= $profileValue['profile_identifier'];
            $dataArray['payment_profileid']= $profileValue['payment_profileid'];
            $dataArray['user_id']= $profileValue['user_id'];
            $dataArray['user_email']= $value->email;
            $dataArray['first_name']= $value->first_name;
            $dataArray['min_deposit']= $getVendorThresholds->min_deposit;
            $dataArray['threshold_value']= $getVendorThresholds->threshold_value;
            $doWeGotTheProfile = true;
            break;
          }
        }
      }
      if($doWeGotTheProfile){
        break;
      }
    }
    if($dataArray){
      $getVendorInfo[$getVendorThresholds->id] = $dataArray;
    }
  }
  if(count($data)!=count($getVendorInfo)){
    $respData['data']['response']=false;
    $respData['data']['message']='Vendors payment profile is not found. Kindly add a Credit Card to redeem points.';
    $this->set('response',$respData);
    $this->set('_serialize', ['response']);
    return;
  }
  $this->loadModel('VendorDepositBalances');

  foreach ($getVendorThreshold as $key => $valueThreshhold) {

    $updateReq= [];
    if($valueThreshhold['vendor_deposit_balances']){
      $vendorId = $valueThreshhold['vendor_deposit_balances'][0]->vendor_id; 
      if($valueThreshhold['vendor_deposit_balances'][0]->balance - $amountCorrPhId[$valueThreshhold->people_hub_identifier] < 0 ){
          $currentDateTime = Time::now();
          $currentDateTime = $currentDateTime->format('H:i:s');
          $ccChargeData = array();
          $ccChargeData['user_id'] = $getVendorInfo[$vendorId]['user_id'];
          $ccChargeData['vendor_id'] = $vendorId;
          $ccChargeData['balance'] = $getVendorInfo[$vendorId]['min_deposit']-($valueThreshhold['vendor_deposit_balances'][0]->balance);
          $ccChargeData['profileId'] = $getVendorInfo[$vendorId]['profile_identifier'];
          $ccChargeData['payment_profileid'] = $getVendorInfo[$vendorId]['payment_profileid'];
          $ccChargeData['reason'] = $currentDateTime.' charged via C.C. due to less than threshold amount';
          $ccChargeData['first_name'] = $getVendorInfo[$vendorId]['first_name'];
          $ccChargeData['email'] = $getVendorInfo[$vendorId]['user_email'];
        $event = new Event('CreditCard.beforeCharge', $this, [
          'arr' =>[
          'hashData' => $ccChargeData,
            'eventId' => 5, //give the event_id for which you want to fire the email
            ]
            ]);
        $this->eventManager()->dispatch($event);
            
        $vendorId = $this->VendorDepositBalances->find()->where(['vendor_id' =>  $vendorId])->first();
        $updateReq[] = ['balance'=>($ccChargeData['balance'] + ($valueThreshhold['vendor_deposit_balances'][0]->balance)),'vendor_id' =>  $vendorId,'id'=>$valueThreshhold['vendor_deposit_balances'][0]->id];
        $updateVendorBalance = $this->VendorDepositBalances->patchEntities($vendorId,$updateReq);
        $this->VendorDepositBalances->saveMany($updateVendorBalance);

        $respData['data']['response']=false;
        $respData['data']['message']='Balance not sufficient, so redemption is not possible';
        break;
      }else{
        $respData['data']['response']=true;
      }
    }else{
      $respData['data']['response']=false;
      $respData['data']['message']='Vendor with PhId '.$valueThreshhold->people_hub_identifier.' is not having any deposit balance info';
      break;
    }
  }
  $this->set('response',$respData);
  $this->set('_serialize', ['response']);
}


}