<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MilestoneLevelReward Entity
 *
 * @property int $id
 * @property int $milestone_level_id
 * @property int $reward_type_id
 * @property int $points
 * @property int $amount
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $reward_id
 *
 * @property \App\Model\Entity\MilestoneLevel $milestone_level
 */
class MilestoneLevelReward extends Entity
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
