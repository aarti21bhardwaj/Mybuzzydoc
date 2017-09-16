<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * LegacyReward Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $vendor_id
 * @property int $reward_category_id
 * @property int $product_type_id
 * @property int $points
 * @property string $image_path
 * @property $image_name
 * @property int $status
 * @property \Cake\I18n\Time $is_deleted
 * @property int $amount
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\RewardCategory $reward_category
 * @property \App\Model\Entity\ProductType $product_type
 * @property \App\Model\Entity\LegacyRedemption[] $legacy_redemptions
 */
class LegacyReward extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

     protected function _getImageUrl()
    {
        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/uploads/'.$this->_properties['image_name'],true);
        }else{
            $url = Router::url('/img/default-img.jpeg',true);
        }
        return $url;
        
    }
}
