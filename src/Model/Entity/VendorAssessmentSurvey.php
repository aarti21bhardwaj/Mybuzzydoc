<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorAssessmentSurvey Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $assessment_survey_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\AssessmentSurvey $assessment_survey
 * @property \App\Model\Entity\AssessmentSurveyInstance[] $assessment_survey_instances
 * @property \App\Model\Entity\VendorAssessmentSurveyQuestion[] $vendor_assessment_survey_questions
 */
class VendorAssessmentSurvey extends Entity
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
