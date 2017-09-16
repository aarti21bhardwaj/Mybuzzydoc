<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;

/**
* Awards Controller
*
* @property \App\Model\Table\AwardsTable $Awards
*/
class PatientPortalController extends PatientPortalAppController
{

  public function initialize(){
    parent::initialize();
  }

  /**
  * Index method
  *
  * @return \Cake\Network\Response|null
  */
  public function index()
  {
    throw new NotFoundException(__('BAD_REQUEST'));
  }

  /**
  * View method
  *
  * @param string|null $id Award id.
  * @return \Cake\Network\Response|null
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function view($id = null)
  {
    $this->viewBuilder()->layout('patient-portal');
    $this->loadModel('Vendors');
    $this->loadModel('VendorSettings');
    $vendors = $this->Vendors->findById($id)->first();
    if(!$vendors){
      throw new NotFoundException(__('BAD_REQUEST'));
      // $this->redirect(['Controller' => 'Users','action'=>'login']);
      $this->redirect('/users/login/');
      return;
    }
    $settings = array();
     $vSettings= $this->VendorSettings->findByVendorId($id)
    ->contain(['SettingKeys' => function($q){
      return $q->where(['name' => 'Live Mode']);
    }])->first();
    $mode = null;
    if(!empty($vSettings)){
      $mode = $vSettings->value;
    }
    // if($settings){
    //   $this->Cookie->configKey('v_set', [
    //     'expires' => '+15 minutes',
    //     'path' => '/',
    //     'encryption'=>false
    //   ]);
    //   if($this->Cookie->check('v_set')){
    //     $this->Cookie->delete('v_set');
    //   }
    //   $this->Cookie->write('v_set', json_encode($settings));
    // }
    //
    // $this->Cookie->configKey('v_det', [
    //   'expires' => '+15 minutes',
    //   'path' => '/',
    //   'encryption'=>false
    // ]);
    // if($this->Cookie->check('v_det')){
    //   $this->Cookie->delete('v_det');
    // }
    // $this->Cookie->write('v_det', json_encode($vendors));
    // $vendorPromotions = $this->VendorPromotions->findByVendorId($id)->contain(['Promotions'])->toArray();
    // $allowedPromotions = array();
    // foreach ($vendorPromotions as $key => $value) {
    //   $allowedPromotions[$value->promotion->id]['name'] = $value->promotion->name;
    //   $allowedPromotions[$value->promotion->id]['points'] = $value->promotion->points;
    // }
    //
    // $this->Cookie->configKey('v_pro', [
    //   'expires' => '+15 minutes',
    //   'path' => '/',
    //   'encryption'=>false
    // ]);
    // if($this->Cookie->check('v_pro')){
    //   $this->Cookie->delete('v_pro');
    // }
    // $this->Cookie->write('v_pro',json_encode($allowedPromotions));
    $this->set('vendorId',$vendors->id);
    $this->set('phId',$vendors->people_hub_identifier);
    $this->set('mode',$mode);
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
  * @param string|null $id Award id.
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
  * @param string|null $id Award id.
  * @return \Cake\Network\Response|null Redirects to index.
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function delete($id = null)
  {
    throw new NotFoundException(__('BAD_REQUEST'));
  }


}
