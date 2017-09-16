<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\InternalErrorException;
/**
 * UpdatePromotionAwards shell command.
 */
class UpdatePromotionAwardsShell extends Shell
{

   public function updateDescription(){
    $promotionAwardsModel = TableRegistry::get('PromotionAwards');
    // pr($promotionAwardsModel); die;
    $awardsWithoutDescription = $promotionAwardsModel->find('withTrashed')->where(['PromotionAwards.description IS NULL'])->contain(['VendorPromotions' => function($q){
            return $q->find('withTrashed')
                     ->contain('Promotions');
        }])->all();
        
    foreach ($awardsWithoutDescription as $value) {
        $description[] =[ 'id' => $value['id'], 'description' => $value['vendor_promotion']['promotion']['description']];
    }

    if(!$awardsWithoutDescription){
        $this->out('No entity found with NULL description');
    }

    $awardsWithoutDescription = $promotionAwardsModel->patchEntities($awardsWithoutDescription, $description);
    $this->out($awardsWithoutDescription);
    $saveDescription = $promotionAwardsModel->saveMany($awardsWithoutDescription);
    
    if(!$saveDescription){
        throw new InternalErrorException('Description cannot save');
    }
    $this->out('Description updated');
   }
}
