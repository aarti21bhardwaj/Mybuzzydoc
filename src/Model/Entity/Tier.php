<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tier Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $name
 * @property int $lowerbound
 * @property int $upperbound
 * @property float $multiplier
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\PatientTier[] $patient_tiers
 * @property \App\Model\Entity\TierAward[] $tier_awards
 * @property \App\Model\Entity\TierPerk[] $tier_perks
 */
class Tier extends Entity
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
