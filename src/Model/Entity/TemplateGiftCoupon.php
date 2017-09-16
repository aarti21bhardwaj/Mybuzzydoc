<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TemplateGiftCoupon Entity
 *
 * @property int $id
 * @property int $template_id
 * @property int $gift_coupon_id
 *
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\GiftCoupon $gift_coupon
 */
class TemplateGiftCoupon extends Entity
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
}
