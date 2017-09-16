<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorInstantGiftCouponSetting Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $amount_spent_threshold
 * @property int $points_earned_threshold
 * @property int $threshold_time_period
 * @property int $redemption_expiry
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorInstantGiftCouponSetting extends Entity
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
