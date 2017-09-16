<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorMilestones Controller
 *
 * @property \App\Model\Table\VendorMilestonesTable $VendorMilestones
 */
class VendorMilestonesController extends AppController
{

    const SUPER_ADMIN_LABEL = 'admin';
    const STAFF_ADMIN_LABEL = 'staff_admin';
    const STAFF_MANAGER_LABEL = 'staff_manager';
    
    
    // public function initialize()
    // {
    //     parent::initialize();
        
    // }
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
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            
            $vendorMilestones = $this->VendorMilestones->find()->contain(['Vendors'])->all();
        
        }else{
            
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);

        }
        $vendorMilestones = $this->VendorMilestones->find()->contain(['Vendors'])->all();
        $this->set(compact('vendorMilestones'));
        $this->set('_serialize', ['vendorMilestones']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        
        $vendorMilestone = false;
        $vendors = false;
        $vendorId = false;

        $loggedInUser = $this->Auth->user();
        // pr($loggedInUser);die;
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            
            if($id){
                $vendorMilestone = $this->VendorMilestones->findById($id)->first();
                if(!$vendorMilestone){
                    return $this->redirect(['action' => 'index']);
                }
            }else{
                $vendorMilestone = $this->VendorMilestones->newEntity();
            }
            
            $vendors = $this->VendorMilestones->Vendors->find()->all()->indexBy('id')->toArray();
        
        }else{


            $vendorMilestone = $this->VendorMilestones->findByVendorId($loggedInUser['vendor_id'])->first();
            
            if(!$vendorMilestone){
               $vendorMilestone = $this->VendorMilestones->newEntity(); 
            } 

            if($id != null && $id != $vendorMilestone->id){

              return $this->redirect(['action' => 'add']);  
            
            }
            
            $vendorId = $loggedInUser['vendor_id'];    
        }

            $this->set(compact('vendorMilestone', 'vendors', 'vendorId'));
            $this->set('_serialize', ['vendorMilestone']);
        

    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Milestone id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorMilestone = $this->VendorMilestones->get($id);
        if ($this->VendorMilestones->delete($vendorMilestone)) {
            $this->Flash->success(__('The vendor milestone has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor milestone could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // public function isAuthorized($user){
    //     $action = $this->request->params['action'];
    //     if(!in_array($user['role']['label'], ['Admin', 'Staff Admin']) && in_array($action, ['index'])) {
    //         return false;
    //     }
    //     if(!in_array($user['role']['label'], ['Staff Admin']) && in_array($action, ['add'])) {
    //         return true;
    //     }

    //     return parent::isAuthorized($user);
    // }
}
