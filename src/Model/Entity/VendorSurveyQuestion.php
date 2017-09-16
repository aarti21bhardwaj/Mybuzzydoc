<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorSurveyQuestion Entity
 *
 * @property int $id
 * @property int $vendor_survey_id
 * @property int $survey_question_id
 * @property int $points
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\SurveyQuestion $survey_question
 * @property \App\Model\Entity\SurveyInstanceResponse[] $survey_instance_responses
 */
class VendorSurveyQuestion extends Entity
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
