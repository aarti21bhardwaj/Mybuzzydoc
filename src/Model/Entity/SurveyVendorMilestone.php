<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveyVendorMilestone Entity
 *
 * @property int $id
 * @property int $vendor_milestone_id
 * @property int $vendor_survey_id
 * @property bool $points
 * @property \Cake\I18n\Time $end_time
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VendorMilestone $vendor_milestone
 * @property \App\Model\Entity\VendorSurvey $vendor_survey
 */
class SurveyVendorMilestone extends Entity
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
