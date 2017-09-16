<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PatientTier Entity
 *
 * @property int $id
 * @property int $peoplehub_identifier
 * @property int $tier_id
 * @property int $amount_spent
 * @property float $effective_discount_rate
 * @property int $year
 * @property \Cake\I18n\Time $start_date
 * @property \Cake\I18n\Time $end_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Tier $tier
 */
class PatientTier extends Entity
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
