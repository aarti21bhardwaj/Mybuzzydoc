<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Session;
use Cake\Routing\Router;


/**
 * Promotions Controller
 *
 * @property \App\Model\Table\PromotionsTable $Promotions
 */
class PromotionsController extends AppController
{

    const SUPER_ADMIN_LABEL = 'admin';
    const STAFF_ADMIN_LABEL = 'staff_admin';
    const STAFF_MANAGER_LABEL = 'staff_manager';

    public function initialize(){
    parent::initialize();
    // $this->Auth->config('authorize', ['Controller']);
    
    
  }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        
        $this->request->data['admin'] = 'admin';
        $loggedInUser = $this->Auth->user();

        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
        $promotions = $this->Promotions->find()->contain(['Vendors'])->where([$this->_vendorCondition])->all();
        }else if($loggedInUser['role']->name  == self::STAFF_ADMIN_LABEL  || $loggedInUser['role']->name  == self::STAFF_MANAGER_LABEL  ||  $loggedInUser['role']->name == $this->request->data['admin']){

        $promotions = $this->Promotions->find()->contain(['Vendors'])->where(['OR' => [['vendor_id' => $this->Auth->user('vendor_id')], ['vendor_id =' => 1]]])->all();
          }

        // $promotions = $this->paginate($this->Promotions);
        $this->set(compact('promotions'));
        $this->set('_serialize', ['promotions']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * View method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $promotion = $this->Promotions->find('all')->where(['Promotions.id'=>$id])->contain(['Vendors'])->first();
        if($promotion){
            $this->set('promotion', $promotion);
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
        $loggedInUser = $this->Auth->user();
        $promotion = $this->Promotions->newEntity(null, ['associated' => ['VendorPromotions']]);
        if ($this->request->is('post')) {
            $vendorPromotion = ['vendor_id' => $this->request->data['vendor_id'], 'points' => $this->request->data['points'], 'frequency' => $this->request->data['frequency']];
            $this->request->data['vendor_promotions'][] = $vendorPromotion;
            
            $promotion = $this->Promotions->patchEntity($promotion, $this->request->data, ['associated' => 'VendorPromotions']);
            
            if($promotion->errors()){
                $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
            }
            if ($this->Promotions->save($promotion)) {

                $this->Flash->success(__('ENTITY_SAVED', 'promotion'));
                if($loggedInUser['role']->name  == self::SUPER_ADMIN_LABEL){

                    return $this->redirect(['controller'=>'Promotions','action' => 'index']);
                }else{
                    return $this->redirect(['controller'=>'VendorPromotions','action' => 'index']);
                }

            }else{
                $this->Flash->error(__('ENTITY_ERROR', 'promotion'));
            }
        }
        $vendors = $this->Promotions->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('promotion', 'vendors'));
        $this->set('_serialize', ['promotion']);
        $this->set('loggedInUser', $loggedInUser);
    }

    /**
     * Edit method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loggedInUser = $this->Auth->user();
        $promotion = $this->Promotions->find()->where(['Promotions.id'=>$id])->first();
        if(!$promotion){
         $this->Flash->error(__('RECORD_NOT_FOUND'));
         return $this->redirect(['action' => 'index']);
     }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
            
             if($promotion->errors()){
                $this->Flash->error(__('KINDLY_PROVIDE_VALID_DATA'));
            }
            if ($this->Promotions->save($promotion)) {
                $this->Flash->success(__('ENTITY_SAVED', 'promotion'));
               return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('ENTITY_ERROR', 'promotions'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $vendors = $this->Promotions->Vendors->find('list', ['limit' => 200]);
        $this->set(compact('promotion', 'vendors'));
        $this->set('_serialize', ['promotion']);
        $this->set('loggedInUser', $loggedInUser);

    }

    /**
     * Delete method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $promotion = $this->Promotions->get($id);
        if ($this->Promotions->delete($promotion)) {
            $this->Flash->success(__('ENTITY_DELETED','promotion'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','promotion'));
        }
        return $this->redirect(['action' => 'index']);
    }

    // public function isAuthorized($user){

    //     if(in_array($user['role']['label'], ['Admin']))
    //         return true;

    //     return parent::isAuthorized($user); 
    // }
     
}