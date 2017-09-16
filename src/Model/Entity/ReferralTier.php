<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReferralTier Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $name
 * @property int $referrals_required
 * @property int $points
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\PatientReferralTier[] $patient_referral_tiers
 * @property \App\Model\Entity\ReferralTierAward[] $referral_tier_awards
 * @property \App\Model\Entity\ReferralTierGiftCoupon[] $referral_tier_gift_coupons
 * @property \App\Model\Entity\ReferralTierPerk[] $referral_tier_perks
 */
class ReferralTier extends Entity
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
