<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReferralTierAward Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property int $redeemer_peoplehub_identifier
 * @property int $points
 * @property int $referral_tier_id
 * @property int $referral_id
 * @property int $peoplehub_transaction_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\ReferralTier $referral_tier
 * @property \App\Model\Entity\Referral $referral
 * @property \App\Model\Entity\PeoplehubTransaction $peoplehub_transaction
 */
class ReferralTierAward extends Entity
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
