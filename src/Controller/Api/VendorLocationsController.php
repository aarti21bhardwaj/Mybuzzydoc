<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;

/**
 * Vendors Controller
 *
 * @property \App\Model\Table\VendorsTable $Vendors
 */
class VendorLocationsController extends ApiController
{ 

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
    // $this->Auth->allow(['add']);
  }

  /**
     * Add method
     * This method also hits PeopleHub add vendor api. If People hub id is received it is saved along with the vendor.
     * A staff admin user is always created at the time of vendor creation.
     * 
     * @return \Cake\Network\Response
     * @throws \Cake\Network\Exception\InternalErrorException When record not saved.
     * @throws \Cake\Network\Exception\BadRequestException if data in request is not valid.
     * @throws \Cake\Network\Exception\MethodNotAllowedException if request is not post.
     * @author James Kukreja
     * @todo   min_deposit & threshold_value to be taken from database in future based on reward template type.
     */
  
  public function add()
  {
    throw new NotFoundException(__('BAD_REQUEST'));
  }

 public function edit()
  {
    throw new NotFoundException(__('BAD_REQUEST'));
  }
   public function delete()
  {
    throw new NotFoundException(__('BAD_REQUEST'));
  }


  /**
   * View method
   *
   * @param string|null $id Vendor id.
   * @return \Cake\Network\Response|null
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id=null)
  {
    if ($this->request->is('get'))
    {
      // pr('here');die;
      $vendorLocations = $this->VendorLocations->findByVendorId($id)->all();
      // pr($vendorLocations);die;
      if($vendorLocations){
        
        $response['VendorLocations'] = $vendorLocations;


      }else{
        throw new BadRequestException(__('No Locations Found'));
      } 
    
    $this->set('response', $response);
    $this->set('_serialize', ['response']);
    } 
  }  
    
}