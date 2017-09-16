<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;


/*$dsn = 'mysql://root:1234@localhost/new_buzzydoc';
ConnectionManager::config('new_buzzydoc', ['url' => $dsn]);*/

/**
* ReferralLeads Controller
*
* @property \App\Model\Table\VendorRedeemedPointsTable 
*/
class  VendorMilestonesController extends ApiController
{

  public function initialize()
  {
    parent::initialize();

  }

  public function getRewardTypes($vendorId = null){


    if (!$this->request->is('get')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $this->loadModel('RewardTypes');
    $rewardTypes = $this->RewardTypes
    ->find()
    ->select(['id', 'type'])
    ->all();

    $this->loadModel('GiftCoupons');
    $giftCoupons = $this->GiftCoupons->findByVendorId($vendorId)->select(['id', 'description'])->all();

    $this->set(compact('rewardTypes', 'giftCoupons'));
    $this->set('_serialize', ['rewardTypes', 'giftCoupons']);
  }


  public function postMilestones(){
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

//Creating new Vendor Entity 
    $this->request->data['vendor_id'] = $this->request->data['vendor_id'];
    
    if($this->request->data['vendor_id'] != 1){
        $vendorMilestone = $this->VendorMilestones->findByVendorId($this->request->data['vendor_id'])->first();
        if($vendorMilestone){
          if(!isset($this->request->data['id'])){
            throw new BadRequestException(__('Milestone Program for this vendor already exists.'));
          }else{
            if($this->request->data['id'] != $vendorMilestone->id){
              throw new BadRequestException(__('Milestone Program for this vendor already exists.'));
            }
          }
        }

    }

    $milestoneModel = TableRegistry::get('VendorMilestones');

    $data = $this->request->data;

    $response = $milestoneModel->connection()->transactional(function () use ($milestoneModel, $data){

          if(isset($data['id'])) {
          
            $milestoneLevels = $milestoneModel->MilestoneLevels->findByVendorMilestoneId($data['id'])->contain(['MilestoneLevelRewards', 'MilestoneLevelRules'])->all();
            if($milestoneLevels){
                foreach($milestoneLevels as $key => $value) {
                  $milestoneModel->MilestoneLevels->delete($value);      
                }
            }

            $milestoneData = $milestoneModel->findById($data['id'])->first();


          }else{

            $milestoneData = $milestoneModel->newEntity($data, [
            'associated' => ['MilestoneLevels','MilestoneLevels.MilestoneLevelRules',  'MilestoneLevels.MilestoneLevelRewards']]);

          } 

          $milestoneData = $milestoneModel->patchEntity($milestoneData, $data, [
            'associated' => ['MilestoneLevels', 'MilestoneLevels.MilestoneLevelRewards', 'MilestoneLevels.MilestoneLevelRules' ]]);


          // pr($milestoneData);die;

          if( $milestoneData->errors()){
            
            throw new BadRequestException(__('KINDLY_PROVIDE_VALID_DATA'));
          }
      // pr($milestoneData); die;

          if($milestoneModel->save($milestoneData, ['associated' => ['MilestoneLevels.MilestoneLevelRewards', 'MilestoneLevels.MilestoneLevelRules', 'MilestoneLevels']])){

            $response['message'] = __('ENTITY_SAVED', 'template');
            $response['id'] = $milestoneData->id;

          } else {

            $errors =  $milestoneData->errors();
            $errorString = "";
            foreach($errors as $key => $value){
              foreach($value as $key1 => $value1)
                $errorString = $errorString." ".$key.":".$value1;

            }
            throw new BadRequestException(__($errorString));
          }
      
          $this->set(compact('response'));
          $this->set('_serialize', ['response']);
    
    });

  }

  public function view($id){

    if(!$this->request->is('get')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $vendorMilestone = $this->VendorMilestones->findById($id)
    ->contain(
      [
      'MilestoneLevels' => function($q){

        return $q->contain(['MilestoneLevelRules', 'MilestoneLevelRewards']);
      } 
      ]
      )
    ->first();
// $vendorTier = $vendor->tiers->indexBy('');
    if(!$vendorMilestone){
      throw new BadRequestException(__('NOT_FOUND','Vendor Milestone'));
    } 
//get($id, ['contain' => ['Users']
// 'contain' => ['Users', 'BuzzyDocPlans', 'Staffs']]);
    $this->set(compact('vendorMilestone'));
    $this->set('_serialize', ['vendorMilestone']);


  }

}  




