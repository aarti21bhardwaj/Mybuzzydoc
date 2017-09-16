<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;

/**
 * Awards Controller
 *
 * @property \App\Model\Table\AwardsTable $Awards
 */
class AwardsController extends AppController
{

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
