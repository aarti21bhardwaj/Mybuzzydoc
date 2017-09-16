<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
/**
 * VendorReviews Controller
 *
 * @property \App\Model\Table\VendorReviewsTable $Vendors
 */
//removed extra spaces
class ReviewSettingsController extends ApiController
{
	public function initialize(){    
    parent::initialize();
  }
	
  /**
     * notify method (Api)
     * This method is called when review is shared on fb or a user wishes to notify the clinic where he has shared reviews
     * If Review is shared on fb then its status is updated in db. Status is also updated in db for other four sites if  * user notifies.
     * 
     * @return \Cake\Network\Response saved & review status if saved. 
     * @param Boolean (gplus, yelp, ratemd, healthgrades, fb, yahoo), UUID vendorReviewId as ajax call
     * @author James Kukreja
     * @todo Event needs to be created to award points for fb & Authenticate whether the key and request id match
     * 
     */

  public function updatePoints()
  {
    if(!$this->request->is('post')){
     throw new MethodNotAllowedException(__('BAD_REQUEST'));
   }

   $data = $this->request->data;
   if(!isset($data['vendor_id'])){
    throw new BadRequestException(__('MANDATORY_FIELD_MISSING','vendor_id'));
  }
  if(isset($data['vendor_id']) && empty($data['vendor_id'])){
    throw new BadRequestException(__('EMPTY_NOT_ALLOWED','vendor_id'));
  }
  $this->loadModel('Vendors');
  if(!$this->Vendors->findById($data['vendor_id'])->first()){
    throw new BadRequestException(__('INVALID_VENDOR'));
  }
          // $data['ReviewSettings'] = $data; 
  $reviewSettings = $this->ReviewSettings->findByVendorId($data['vendor_id'])->first();
  if(!$reviewSettings)
    $reviewSettings = $this->ReviewSettings->newEntity();

  $reviewSettings = $this->ReviewSettings->patchEntity($reviewSettings, $data);
  if($this->ReviewSettings->save($reviewSettings)){


    $message = __('REVIEW_POINTS_UPDATED');
    $saved = true;
    $errors = "No Errors";

  }else{

    $message = __("ENTITY_UPDATE_ERROR","points");
    $saved = False;
    $errors = $reviewSettings->errors();

  }



        // $response = json_encode($response);
$this->set([
  'response' => ['message'=> $message, 'errors' => $errors],
  '_serialize' => ['response']
  ]);

}

public function add()
{
  throw new NotFoundException(__('BAD_REQUEST'));
}
public function view($id = null)
{
  throw new NotFoundException(__('BAD_REQUEST'));
}
public function edit($id = null)
{
  throw new NotFoundException(__('BAD_REQUEST'));
}
public function delete($id = null)
{
  throw new NotFoundException(__('BAD_REQUEST'));
}
}


?>