<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorAssessmentSurveyQuestion Entity
 *
 * @property int $id
 * @property int $vendor_assessment_survey_id
 * @property int $assessment_survey_question_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VendorAssessmentSurvey $vendor_assessment_survey
 * @property \App\Model\Entity\AssessmentSurveyQuestion $assessment_survey_question
 * @property \App\Model\Entity\AssessmentSurveyInstanceResponse[] $assessment_survey_instance_responses
 */
class VendorAssessmentSurveyQuestion extends Entity
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
