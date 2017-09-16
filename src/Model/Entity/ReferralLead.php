<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReferralLead Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $referral_id
 * @property int $vendor_referral_settings_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property bool $status
 * @property \Cake\I18n\Time $is_deleted
 * @property int $vendor_id
 * @property string $preferred_talking_time
 *
 * @property \App\Model\Entity\Referral $referral
 * @property \App\Model\Entity\VendorReferralSetting $vendor_referral_setting
 * @property \App\Model\Entity\Vendor $vendor
 */
class ReferralLead extends Entity
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
