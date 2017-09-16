<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GiftCouponRedemption Entity
 *
 * @property int $id
 * @property int $gift_coupon_award_id
 * @property bool $redemption_status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\GiftCouponAward $gift_coupon_award
 */
class GiftCouponRedemption extends Entity
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
