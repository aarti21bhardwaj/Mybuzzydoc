<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MilestoneLevel Entity
 *
 * @property int $id
 * @property int $vendor_milestone_id
 * @property string $name
 * @property int $level_number
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VendorMilestone $vendor_milestone
 * @property \App\Model\Entity\MilestoneLevelAward[] $milestone_level_awards
 * @property \App\Model\Entity\MilestoneLevelReward[] $milestone_level_rewards
 * @property \App\Model\Entity\MilestoneLevelRule[] $milestone_level_rules
 */
class MilestoneLevel extends Entity
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
