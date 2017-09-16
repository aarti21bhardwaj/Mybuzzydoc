<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VendorDocuments Controller
 *
 * @property \App\Model\Table\VendorDocumentsTable $VendorDocuments
 */
class VendorDocumentsController extends AppController
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
        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $vendorDocuments = $this->VendorDocuments->find()->contain(['Vendors'])->all();
        }else{
            $vendorDocuments = $this->VendorDocuments->findByVendorId($this->Auth->user('vendor_id'))->contain(['Vendors'])->all();
        }

        $this->set(compact('vendorDocuments'));
        $this->set('_serialize', ['vendorDocuments']);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Document id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function view($id = null)
    // {
    //     $vendorDocument = $this->VendorDocuments->get($id, [
    //         'contain' => ['Vendors']
    //     ]);

    //     $this->set('vendorDocument', $vendorDocument);
    //     $this->set('_serialize', ['vendorDocument']);
    // }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vendorDocument = $this->VendorDocuments->newEntity();
        if ($this->request->is('post')) {
            if($this->Auth->user('role')->name != self::SUPER_ADMIN_LABEL){
                $this->request->data['vendor_id'] = $this->Auth->user('vendor_id');
            }
            $vendorDocument = $this->VendorDocuments->patchEntity($vendorDocument, $this->request->data);
            if ($this->VendorDocuments->save($vendorDocument)) {
                
                $this->Flash->success(__('The vendor document has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vendor document could not be saved. Please, try again.'));
            }
        }
        if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
            $vendors = $this->VendorDocuments->Vendors->find('list', ['limit' => 200]);
        }else{
            $vendors = false;
        }
        $this->set(compact('vendorDocument', 'vendors'));
        $this->set('_serialize', ['vendorDocument']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Document id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $vendorDocument = $this->VendorDocuments->findById($id)->first();
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $vendorDocument = $this->VendorDocuments->patchEntity($vendorDocument, $this->request->data);
    //         if ($this->VendorDocuments->save($vendorDocument)) {
    //             $this->Flash->success(__('The vendor document has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         } else {
    //             $this->Flash->error(__('The vendor document could not be saved. Please, try again.'));
    //         }
    //     }
    //     if($this->Auth->user('role')->name == self::SUPER_ADMIN_LABEL){
    //         $vendors = $this->VendorDocuments->Vendors->find('list', ['limit' => 200]);
    //     }else{
    //         $vendors = false;
    //     }
    //     $this->set(compact('vendorDocument', 'vendors'));
    //     $this->set('_serialize', ['vendorDocument']);
    // }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Document id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vendorDocument = $this->VendorDocuments->get($id);
        if ($this->VendorDocuments->delete($vendorDocument)) {
            $this->Flash->success(__('The vendor document has been deleted.'));
        } else {
            $this->Flash->error(__('The vendor document could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
