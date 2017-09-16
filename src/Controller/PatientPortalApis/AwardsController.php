<?php
namespace App\Controller\PatientPortalApis;

use App\Controller\PatientPortalApis\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Collection\Collection;


/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\VendorRedeemedPointsTable
 */
class  AwardsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('VendorSettings');
        $this->loadComponent('RequestHandler');
    }


    public function promotions()
    {
        if (!$this->request->is('post')) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!$this->request->data){
          throw new BadRequestException(__('Request Data not found. Kindly Provide valid Request Data.'));
        }
        $data = $this->request->data;
        $this->loadModel('Vendors');
        if(!isset($data['vendor_id'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING','vendor_id'));
        }
        if(empty($data['vendor_id'])){
          throw new BadRequestException(__('EMPTY_NOT_ALLOWED','vendor_id'));
        }
        if(!isset($data['promotion_id'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING','promotion_id'));
        }
        if(empty($data['promotion_id'])){
          throw new BadRequestException(__('EMPTY_NOT_ALLOWED','promotion_id'));
        }
        $vendorId = $this->request->data['vendor_id'];
        $validateVendor  = $this->Vendors->find()->where(['id'=>$vendorId,'status'=>1])->contain(['Users'=>['conditions'=>['status'=>1]],'VendorPromotions'=>['conditions'=>['promotion_id'=>$data['promotion_id']]]])->first();
        if(!$validateVendor){
          throw new BadRequestException(__('ENTITY_DOES_NOT_EXISTS','Vendor'));
        }
        if(empty($validateVendor->vendor_promotions)){
          throw new BadRequestException(__('baad me aaiyo.'));
        }

        $points = $validateVendor->vendor_promotions[0]->points;
        $promotionAwardsReqData = array();
        $promotionAwardsReqData['vendor_promotion_id'] = $validateVendor->vendor_promotions[0]->id;
        $promotionAwardsReqData['user_id'] = $validateVendor->users[0]->id;
        $promotionAwardsReqData['points'] = $points;
        $provideRewardResponse = $this->_givePeoplehubPoints($vendorId, $this->request->data['attribute'], $this->request->data['attribute_type'], $points);
        $transactionNumber = $provideRewardResponse['response']->data->id;
        $promotionAwardsReqData['peoplehub_transaction_id'] = $transactionNumber;
        $this->loadModel('PromotionAwards');
        $promotionAwards = $this->PromotionAwards->newEntity();
        $promotionAwards = $this->PromotionAwards->patchEntity($promotionAwards,$promotionAwardsReqData);
        if($this->PromotionAwards->save($promotionAwards)) {
            $this->set('response', $promotionAwards);
            $this->set('_serialize', ['response']);
        }else{
            throw new InternalErrorException(__('ENTITY_ERROR', 'Promotion Awards'));
        }
    }

    private function _givePeoplehubPoints($vendorId, $attribute, $attributeType, $points){

        $rewardCreditData = ['attribute' => $attribute , 'attribute_type' => $attributeType , 'points' => $points , 'reward_type' => 'store_credit'];
        $this->loadComponent('PeopleHub');
        $provideRewardResponse = $this->PeopleHub->provideReward($rewardCreditData, $vendorId);

        if(!$provideRewardResponse['status']){
            throw new BadRequestException(__('PEOPLE_HUB_REQUEST_REJECTED'.json_encode($provideRewardResponse['response']->message)));
        }
        if(!$provideRewardResponse['response']->data->id){

            throw new InternalErrorException(__('PEOPLEHUB_TRANSACTION_FAILED'));
        }

        return $provideRewardResponse;

    }

  }
