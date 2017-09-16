<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReferralSettingGiftCoupon Entity
 *
 * @property int $id
 * @property int $vendor_referral_setting_id
 * @property int $gift_coupon_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VendorReferralSetting $vendor_referral_setting
 * @property \App\Model\Entity\GiftCoupon $gift_coupon
 */
class ReferralSettingGiftCoupon extends Entity
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
