<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorMilestone Entity
 *
 * @property int $id
 * @property string $name
 * @property int $vendor_id
 * @property bool $fixed_term
 * @property int $end_duration
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\MilestoneLevel[] $milestone_levels
 * @property \App\Model\Entity\SurveyVendorMilestone[] $survey_vendor_milestones
 */
class VendorMilestone extends Entity
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
