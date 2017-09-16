<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssessmentSurvey Entity
 *
 * @property int $id
 * @property string $name
 * @property int $survey_type_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\SurveyType $survey_type
 * @property \App\Model\Entity\AssessmentSurveyInstanceResponse[] $assessment_survey_instance_responses
 * @property \App\Model\Entity\AssessmentSurveyQuestion[] $assessment_survey_questions
 * @property \App\Model\Entity\VendorAssessmentSurvey[] $vendor_assessment_surveys
 */
class AssessmentSurvey extends Entity
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
