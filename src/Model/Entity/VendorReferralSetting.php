<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorReferralSetting Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $referral_level_name
 * @property int $referrer_award_points
 * @property int $referree_award_points
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property bool $status
 * @property \Cake\I18n\Time $is_deleted
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorReferralSetting extends Entity
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
