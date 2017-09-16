<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * ResponseOptions Controller
 *
 * @property \App\Model\Table\ResponseOptionsTable $ResponseOptions
 */
class ResponseOptionsController extends ApiController
{   
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $responseOptions = $this->ResponseOptions->find()->all()->groupBy('response_group_id')->toArray();

        $this->set(compact('responseOptions'));
        $this->set('_serialize', ['responseOptions']);
    }
}
