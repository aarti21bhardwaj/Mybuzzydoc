<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Network\Session;
use Cake\Collection\Collection;

/**
* VendorPromotions Controller
*
* @property \App\Model\Table\VendorsTable $Vendors
*/
/**
* ReferralLeads Controller
*
* @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
*/
class VendorPromotionsController extends ApiController
{

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');

  }

  public function patientManagement($vendorId = null){

    if(!$this->request->is('get')){
     throw new MethodNotAllowedException(__('BAD_REQUEST'));
   }
      // $vendorId = 2;
   $vendorPromotion = $this->VendorPromotions
   ->Vendors->findById($this->Auth->user('vendor_id'))
   ->contain(['VendorPromotions', 'VendorPromotions.Promotions', 'Users'])
   ->first();

   $this->set(['response' => $vendorPromotion,
    '_serialize' => ['response']]);

 }
    /**
     * Add method
     *
     * @return \Cake\Network\Response
     */

    public function add()
    {

      if (!$this->request->is('post')) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $vendorPromotion = $this->VendorPromotions->newEntity();
      $existingVp = $this->VendorPromotions->find()
      ->where(['vendor_id' => $this->request->data['vendor_id'], 'promotion_id' => $this->request->data['promotion_id']])
      ->first();
      if($existingVp){
        $this->set('response', $existingVp);
      }else{
        $vendorPromotion = $this->VendorPromotions->patchEntity($vendorPromotion, $this->request->data);
        if ($this->VendorPromotions->save($vendorPromotion)) {
          $url = Router::url('/', true);
          $url = $url.'vendorPromotions/edit/'.$vendorPromotion->id;
          $vendorPromotion->link = $url;
          $this->set('response', $vendorPromotion);
        } else {
          throw new InternalErrorException(__('BAD_REQUEST'));
        }
      }
      $this->set('_serialize', ['response']);      
    }

  /**
  * Vendor Promotions
  *
  * @return \Cake\Network\Response
  * @throws \Cake\Network\Exception\BadRequestException if data in request is not valid.
  * @author Nikhil Verma
  */
  /**
  * @api {post} /api/VendorPromotions/vendorPromotionPoints/ API to get the total points for a user for the promotions of vendors
  * @apiVersion 1.0.0
  * @apiName vendorPromotionPoints
  * @apiGroup Vendor Promotions
  *
  * @apiParam {array} promotions [4,2,3].
  *
  * @apiSuccess {Boolean} success status of the request.
  *
  * @apiSuccessExample Success-Response:
  *          HTTP/1.1 200 OK
  *  {
  *  "response": {
  *      "status": true,
  *      "points": 25,
  *      "message": "total points"
  *      }
  *   }
  *
  */
  public function vendorPromotionPoints()
  {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->data;
    if(!$data){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'request data'));
    }
    if(!$data['promotions'] || !count($data['promotions'])){
      throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'promotions'));
    }
    foreach ($data['promotions'] as $value) {
      if(!$value['promotion_id'] || !$value['multiplier'] || !$value['description']){
        throw new BadRequestException(__('MANDATORY_FIELD_MISSING', 'promotion id, multiplier or description'));
      }
    }

    $ids = (new Collection($data['promotions']))->extract('promotion_id')->toArray();

    if(empty($ids)){
      throw new BadRequestException(__('INVALID_DATA_PROVIDED'));
    }

    $loggedInUser = $this->Auth->user();
            //$this->VendorPromotions->rewardPoints(); die();

    /*This query is to get the id in array for creating subquery*/
    $query = $this->VendorPromotions->find()->where(['id' => $ids], ['id' => 'integer[]']);
    /*This query gets all the relevent points releated to login vendor*/
    $vendorPromotions = $this->VendorPromotions->find('all')->where(['id IN' => $ids,'vendor_id'=>$this->Auth->user('vendor_id')])->all();

    $promotionAwards = [];
    $points = 0;
    foreach ($data['promotions'] as $key => $value) {
        foreach ($vendorPromotions as $value2) {
            if($value['promotion_id'] === $value2['id']){
                $promotionAwards[$key]['points'] = $value2['points']*$value['multiplier'];
                $points += $promotionAwards[$key]['points'];
            }
        }
    }

    $data =array();
    $data['status']=true;
    $data['points'] = $points;
    $data['message']='total points';
    $this->set('response',$data);
    $this->set('_serialize', ['response']);


  }

/**
* View method
*
* @param string|null $id Vendor Promotion id.
* @return \Cake\Network\Response|null
* @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
*/
public function view($id = null)
{
  $vendorPromotion = $this->VendorPromotions->get($id, [
    'contain' => ['Vendors', 'Promotions']
    ]);

  $this->set('vendorPromotion', $vendorPromotion);
  $this->set('_serialize', ['vendorPromotion']);
}

/**
* Edit method
*
* @param string|null $id Vendor Promotion id.
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
* @param string|null $id Vendor Promotion id.
* @return \Cake\Network\Response|null Redirects to index.
* @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
*/
public function delete($id = null)
{
  $this->request->allowMethod(['post', 'delete']);
  $vendorPromotion = $this->VendorPromotions->findById($id)->first();
  if(!$vendorPromotion){
    throw new NotFoundException(__('RECORD_NOT_FOUND')); 
  }
  if ($this->VendorPromotions->delete($vendorPromotion)) {
    $url = Router::url('/', true);
    $url = $url.'vendorPromotions/delete/'.$vendorPromotion->id;
    $vendorPromotion->link = $url;
    $this->set('response', $vendorPromotion);
    $this->set('_serialize', ['response']);
  } else {
    throw new InternalErrorException(__('INTERNAL_SERVER_ERROR'));
  }
  
  
}
}
