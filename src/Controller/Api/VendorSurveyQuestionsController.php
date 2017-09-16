<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Legacy Rewards Controller
 *
 * @property \App\Model\Table\VendorSurveyQuestionsTable 
 */
class VendorSurveyQuestionsController extends ApiController
{   
    public function initialize(){    
        parent::initialize();
    }

    public function edit($id = null)
    {
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
         $vendorSurveyQuestion = $this->VendorSurveyQuestions->get($id, [
            'contain' => []
        ]);
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->patchEntity($vendorSurveyQuestion, $this->request->data);
        $vendorSurveyQuestion = $this->VendorSurveyQuestions->save($vendorSurveyQuestion);
        // pr($vendorSurveyQuestion); die;
        
        $this->set(compact('vendorSurveyQuestion'));
        $this->set('_serialize', ['vendorSurveyQuestion']);
    }
}