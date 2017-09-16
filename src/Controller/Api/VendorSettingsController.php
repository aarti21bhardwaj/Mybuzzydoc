<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Collection\Collection;
use Cake\Network\Exception\NotFoundException;

/**
 * VendorSettings Controller
 *
 * @property \App\Model\Table\VendorSettingsTable $VendorSettings
 */
class VendorSettingsController extends ApiController
{

    public function initialize()
      {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        //$this->Auth->allow(['add']);
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
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
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
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function updateVendorSettingLive($id = null)
    {

        $vendorSetting = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))->first();
        
        $data = $this->request->data;
        $data['value'] = 1;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorSetting = $this->VendorSettings->patchEntity($vendorSetting, $data);
            if ($this->VendorSettings->save($vendorSetting)) {
                $this->set('response',$vendorSetting);
                $this->set('_serialize', ['response']);
            }
        }

        /*if($data['value'] == 1){
            //delete all the asscociated data from following tables

        }*/

        
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotFoundException(__('BAD_REQUEST'));
    }
}
