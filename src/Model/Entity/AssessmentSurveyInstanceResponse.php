<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssessmentSurveyInstanceResponse Entity
 *
 * @property int $id
 * @property int $assessment_survey_instance_id
 * @property int $response_option_id
 * @property string $vendor_assessment_survey_question_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\AssessmentSurvey $assessment_survey
 * @property \App\Model\Entity\ResponseOption $response_option
 * @property \App\Model\Entity\VendorAssessmentSurveyQuestion $vendor_assessment_survey_question
 */
class AssessmentSurveyInstanceResponse extends Entity
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
