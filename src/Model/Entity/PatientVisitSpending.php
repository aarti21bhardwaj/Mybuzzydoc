<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PatientVisitSpending Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $peoplehub_user_id
 * @property int $amount_spent
 * @property int $points_taken
 * @property bool $instant_reward_unlocked
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $token
 * @property \Cake\I18n\Time $is_deleted
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\PeoplehubUser $peoplehub_user
 */
class PatientVisitSpending extends Entity
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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token'
    ];
}
