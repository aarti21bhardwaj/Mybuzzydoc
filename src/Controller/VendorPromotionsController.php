<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorPromotions Controller
 *
 * @property \App\Model\Table\VendorPromotionsTable $VendorPromotions
 */
class VendorPromotionsController extends AppController
{

      const SUPER_ADMIN_LABEL = 'admin';
      const STAFF_ADMIN_LABEL = 'staff_admin';
      const STAFF_MANAGER_LABEL = 'staff_manager';

      protected $_vendorCondition;
    

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
        $this->loadModel('Promotions');
        // $promotions = $this->Promotions->find('all')->contain(['Vendors','VendorPromotions'])->toArray();          
        $loggedInUser = $this->Auth->user();
        $vendorId = $this->Auth->user('vendor_id');

        if($this->request->is('put')) {
        $vendorId = $this->request->data['vendor_id'];
    
          $vendorPromotions = $this->Promotions->find()
                                               ->contain(['Vendors','VendorPromotions' => function($query) use ($vendorId){
                                                      return $query->where(['vendor_id' => $vendorId]);
                                                  }])
                                               ->where(['OR' => [['vendor_id' => $vendorId], ['vendor_id =' => 1]]])
                                               ->all();
        
        } else if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
  
            $vendorPromotions = $this->Promotions->find()
                                               ->contain(['Vendors','VendorPromotions' => function($query) use ($vendorId){
                                                      return $query->where(['vendor_id' => $this->Auth->user('vendor_id')]);
                                                  }])
                                               ->where([$this->_vendorCondition])
                                               ->all()->toArray();
             // $vendorPromotions = $this->paginate($this->Promotions);
        }else if($loggedInUser['role']->name  == self::STAFF_ADMIN_LABEL  || $loggedInUser['role']->name  == self::STAFF_MANAGER_LABEL  || $loggedInUser['role']->name == $this->request->data['admin']){

            $vendorPromotions = $this->Promotions->find()
                                               ->contain(['Vendors','VendorPromotions' => function($query){
                                                      return $query->where(['vendor_id' => $this->Auth->user('vendor_id')]);
                                                  }])
                                               ->where(['OR' => [['vendor_id' => $vendorId], ['vendor_id =' => 1]]])
                                               ->all()->toArray();
        }

        // pr($vendorPromotions->toArray()); die;

        $vendors = $this->Promotions->Vendors->find('list')->where(['status'=>1, 'org_name <>'=>'admin'])->toArray();
        // pr($vendorPromotions->toArray());
        $this->set('vendorPromotions', $vendorPromotions);
        $this->set('vendors', $vendors);
        $this->set('vendorId', $vendorId);
        $this->set('loggedInUser', $loggedInUser);
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
        $this->loadModel('Promotions');
        $vendorPromotion = $this->Promotions->find('all')->where(['Promotions.id'=>$id])->contain(['VendorPromotions'])->first();
        if($vendorPromotion){
            $this->set('vendorPromotion', $vendorPromotion);
            $this->set('_serialize', ['promotion']);
        }else{
            $this->Flash->error(__('RECORD_NOT_FOUND'));
            return $this->redirect(['action' => 'index']);
        } 
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorPromotion = $this->VendorPromotions->newEntity();
        if ($this->request->is('post')) {
            $vendorPromotion = $this->VendorPromotions->patchEntity($vendorPromotion, $this->request->data);
            if ($this->VendorPromotions->save($vendorPromotion)) {
                $this->Flash->success(__('ENTITY_SAVED', 'vendorPromotion'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'vendorPromotion'));
            }
        }
        $vendors = $this->VendorPromotions->Vendors->find('list', ['limit' => 200]);
        $promotions = $this->VendorPromotions->Promotions->find('list', ['limit' => 200]);
        $this->set(compact('vendorPromotion', 'vendors', 'promotions'));
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
        $loggedInUser = $this->Auth->user();
        $vendorPromotion = $this->VendorPromotions->get($id, [
            'contain' => ['Promotions']
        ]);
        $this->loadModel('Promotions');
        $promotion = $this->Promotions->find()
                                      ->where(['Promotions.id'=>$vendorPromotion->promotion_id])
                                      ->contain(['VendorPromotions'])
                                      ->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
        
            $vendorPromotion = $this->VendorPromotions->patchEntity($vendorPromotion, $this->request->data);

            if ($this->VendorPromotions->save($vendorPromotion)) {
                $this->Flash->success(__('ENTITY_SAVED', 'vendorPromotion'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'vendorPromotion'));
            }
        }
        $vendors = $this->VendorPromotions->Vendors->find('list', ['limit' => 200]);
        $promotions = $this->VendorPromotions->Promotions->find('list', ['limit' => 200]);
        $this->set('promotion', $promotion);
        $this->set(compact('vendorPromotion', 'vendors', 'promotions'));
        $this->set('_serialize', ['vendorPromotion']);
        $this->set('loggedInUser', $loggedInUser);
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

        $vendorPromotion = $this->VendorPromotions->findById($id)->contain(['Promotions'])->last();

        if(!$vendorPromotion){
             $this->Flash->error(__('Not Found'));
        }

        if ($this->VendorPromotions->Promotions->delete($vendorPromotion->promotion) && $this->VendorPromotions->delete($vendorPromotion)) {
            $this->Flash->success(__('ENTITY_DELETED', 'vendorPromotion'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR', 'vendorPromotion'));
        }

        return $this->redirect(['controller'=>'VendorPromotions','action' => 'index']);
        // $this->set('vendorPromotion', $vendorPromotion);
        // $this->set(compact('vendorPromotion'));

    }       
}