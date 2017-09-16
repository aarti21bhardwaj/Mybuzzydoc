<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;


class TemplatesController extends ApiController

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
        // $templates = $this->paginate($this->Templates);
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $templates = $this->Templates->find()
                                    ->contain(['IndustryTemplates.Industries', 'TemplatePlans'])
                                    ->all()
                                    ->combine('id','name');

        $this->set(compact('templates'));
        $this->set('_serialize', ['templates']);
    }

    /**
     * View method
     *
     * @param string|null $id Template id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {      
        $template = $this->Templates->findById($id)->first();
        $template->review = json_decode($template->review);
        $template->referral = json_decode($template->referral);
        $promotions = $this->Templates->TemplatePromotions->findByTemplateId($template->id)->all();
        $template->tier = json_decode($template->tier);
        if($promotions)
        {   $template->promotion=[];
            foreach ($promotions as $key => $value) {
               $template->promotion[$value->promotion_id] = true;
            }
        }
        $surveys = $this->Templates->TemplateSurveys->findByTemplateId($template->id)->all();
        // pr($surveys);
        if($surveys)
        {   $template->survey=[];
            foreach ($surveys as $key => $value) {
               $template->survey[$value->survey_id] = true;
            }
        }
        $milestone = $this->Templates->VendorTemplateMilestones->findByTemplateId($template->id)->all();
        
        if($milestone)
        {   $template->milestone=[];
            foreach ($milestone as $key => $value) {
               $template->milestone[$value->vendor_milestone_id] = true;
            }
        }

        $gift_coupons = $this->Templates->TemplateGiftCoupons->findByTemplateId($template->id)->contain(['GiftCoupons' => function($q){return $q->select(['GiftCoupons.description']);}])->all();
        $template->selected_gift_coupons = $gift_coupons;
        
        if($gift_coupons)
        {   $template->gift_coupons=[];
            foreach ($gift_coupons as $key => $value) {
               $template->gift_coupons[$value->gift_coupon_id] = true ;
            }
        }

        $this->set(compact('template'));
        $this->set('_serialize', ['template']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
            

            $template = $this->Templates->newEntity();
            $this->request->data['name']= strtolower($this->request->data['name']);
            
            unset($this->request->data['id']);
            
            $template = $this->Templates->patchEntity($template, $this->request->data, ['associated' => ['IndustryTemplates', 'TemplatePlans']]);
            // pr($template);die;
               //here 
            if(!$template->errors()){
                if ($this->Templates->save($template)) {
                    // pr($template); die;
                    $response['template'] = $template;
                    $response['message'] = __('ENTITY_SAVED', 'template');
               
                } else {
                   throw new Exception('ENTITY_ERROR', 'template');
                   $response['message'] = __('ENTITY_ERROR', 'template');
                }
            }else{
                throw new BadRequestException(__('ALREADY_EXISTS','Template'));
                $response = "Not Created";
            }
        

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Template id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        // $template = $this->Templates->get($id, [
        //     'contain' => []
        // ]);
        if (!$this->request->is(['patch', 'post', 'put'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $data= $this->request->data;
        $template = $this->Templates->findById($data['id'])->first();
        
        switch ($data['col']) {
            
            case 1:

                $template->review = $this->_review($data);
                break;
            
            case 2:

                $template->referral = $this->_referral($data);
                break;
            
            case 3:

                $this->_promotion($data, $template);
                break;

            case 4:
                $template->tier = $this->_tier($data);
                break;

            case 5:
                $this->_milestone($data, $template);
                break;

            case 6:
                $this->_giftcoupon($data, $template);
                break;

            case 7:
                $this->_survey($data, $template);
                break;

            default:
            continue;
        } 


        if ($this->Templates->save($template)) {
            $response['template'] = $template;
            $response['message'] = __('ENTITY_SAVED', 'template');
        } else {
            $response['message'] = __('ENTITY_ERROR', 'template');

        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Template id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $template = $this->Templates->get($id);
        if ($this->Templates->delete($template)) {
            $this->Flash->success(__('ENTITY_DELETED','template'));
        } else {
            $this->Flash->error(__('ENTITY_DELETED_ERROR','template'));
        }
        return $this->redirect(['action' => 'index']);
    }

    private function _review($data){

       return json_encode($data['reviews']);
    
    }

    private function _referral($data){

        foreach($data['referrals'] as $key => $value){
            if(!isset($value['referral_level_name']) || $value['referral_level_name'] === ""){
                
                unset($data['referrals'][$key]);                    
            }                     
        }
        $data['referrals'] = array_values($data['referrals']);

        return json_encode($data['referrals']);


    }

    private function _promotion($data, $template){

        $this->Templates->TemplatePromotions->deleteAll(['template_id' => $template->id]);

        $templatePromotion = [];

        foreach($data['promotions'] as $key=>$value)
        {   
            if($value === true)
                $templatePromotion[] = ['template_id' => $template->id, 'promotion_id' => $key];
        }
        $templatePromotion = $this->Templates->TemplatePromotions->newEntities($templatePromotion);
                  
        if($this->Templates->TemplatePromotions->saveMany($templatePromotion)){
            $response['promotion_message']= __('ENTITY_SAVED', 'template promotions');
        }else{
            $response['promotion_message'] = $templatePromotion->errors();
        }

    }

    private function _tier($data){

        foreach($data['tier'] as $key => $value){
            if(!isset($value['name']) || $value['name'] === ""){
                
                unset($data['tier'][$key]);                    
            } 
            $data['tier'][$key]['multiplier'] /= 100;      

        }
        $data['tier'] = array_values($data['tier']);

        return json_encode($data['tier']);


    }

    private function _survey($data, $template){

        $this->Templates->TemplateSurveys->deleteAll(['template_id' => $template->id]);

        $templateSurvey = [];

        foreach($data['survey'] as $key=>$value)
        {   
            if($value === true)
                $templateSurvey[] = ['template_id' => $template->id, 'survey_id' => $key];
        }
        $templateSurvey = $this->Templates->TemplateSurveys->newEntities($templateSurvey);
                  
        if($this->Templates->TemplateSurveys->saveMany($templateSurvey)){
            $response['survey_message']= __('ENTITY_SAVED', 'template surveys');
        }else{
            $response['survey_message'] = $templateSurvey->errors();
        }

    }

    private function _milestone($data, $template){

        $this->Templates->VendorTemplateMilestones->deleteAll(['template_id' => $template->id]);

        $templateMilestone = [];

        foreach($data['milestone'] as $key=>$value)
        {   
            if($value === true)
                $templateMilestone[] = ['template_id' => $template->id, 'vendor_milestone_id' => $key];
        }
        $templateMilestone = $this->Templates->VendorTemplateMilestones->newEntities($templateMilestone);
                  
        if($this->Templates->VendorTemplateMilestones->saveMany($templateMilestone)){
            $response['milestone_message']= __('ENTITY_SAVED', 'template milestone');
        }else{
            $response['milestone_message'] = $templateMilestone->errors();
        }

    }

    private function _giftcoupon($data, $template){

        $this->Templates->TemplateGiftCoupons->deleteAll(['template_id' => $template->id]);

        $templateGiftCoupon = [];

        foreach($data['gift_coupon'] as $key=>$value)
        {   
            if($value === true)
                $templateGiftCoupon[] = ['template_id' => $template->id, 'gift_coupon_id' => $key];
        }
        $templateGiftCoupon = $this->Templates->TemplateGiftCoupons->newEntities($templateGiftCoupon);
                  
        if($this->Templates->TemplateGiftCoupons->saveMany($templateGiftCoupon)){
            $response['gift_coupon_message']= __('ENTITY_SAVED', 'template gift coupon');
        }else{
            $response['gift_coupon_message'] = $templateGiftCoupon->errors();
        }

    }

    // private function _review($data){


    // }


    public function templateDetails($id = null){

        $template = $this   ->Templates
                            ->findById($id)
                            ->contain(['TemplatePromotions.Promotions', 'TemplateGiftCoupons.GiftCoupons','TemplateSurveys.Surveys.SurveyQuestions.Questions', 'VendorTemplateMilestones.VendorMilestones.MilestoneLevels' => function($q){
                                return $q->contain(['MilestoneLevelRewards', 'MilestoneLevelRules']);
                            }])
                            ->first();
                            
        $template->review = json_decode($template->review);
        $template->referral = json_decode($template->referral);
        $template->tier = json_decode($template->tier);

        $this->set('template', $template);
        $this->set('_serialize', ['template']);
    }


}
?>